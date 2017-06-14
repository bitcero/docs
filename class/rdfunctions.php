<?php
// $Id: rdfunctions.php 895 2012-01-02 18:35:50Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RDFunctions
{

    /**
     * Get the HTMl code for editor plugin
     */
    static public function editor_plugin($id, $type)
    {
        global $xoopsModule;

        if (defined('RMCSUBLOCATION') && 'newresource' == RMCSUBLOCATION) {
            $id_res = RMHttpRequest::get('id', 'integer', 0);
        }

        ob_start();
        include RMTemplate::get()->path('specials/docs-plugin-content.php', 'module', 'docs');
        $plugin = ob_get_clean();
        return $plugin;
    }

    public static function get_licenses()
    {
        $file = XOOPS_ROOT_PATH . '/modules/docs/include/licenses.php';

        if (!file_exists($file)) {
            return false;
        }

        $content = file_get_contents($file);
        $content = explode("\n", $content);
        return $content;

    }

    /**
     * @desc Envía correo de aprobación de publicación
     *
     * @param Object $res Publicación
     **/
    function mail_approved(RDResource &$res)
    {

        global $xoopsModuleConfig, $xoopsConfig;

        $config_handler =& xoops_gethandler('config');
        $mconfig = $config_handler->getConfigsByCat(XOOPS_CONF_MAILER);

        $errors = '';
        $user = new XoopsUser($res->getVar('owner'));
        $member_handler =& xoops_gethandler('member');
        $method = $user->getVar('notify_method');

        $mailer = new RMMailer('text/plain');
        $mailer->add_xoops_users($user);
        $mailer->set_subject(sprintf(__('Publication <%s> approved!', 'docs'), $res->getVar('title')));
        $mailer->assign('dear_user', $user->getVar('name') != '' ? $user->getVar('name') : $user->getVar('uname'));
        $mailer->assign('link_to_resource', $res->permalink());
        $mailer->assign('site_name', $xoopsConfig['sitename']);
        $mailer->assign('resource_name', $res->getVar('title'));
        $mailer->template(RMTemplate::get()->get_template('mail/resource_approved.php', 'module', 'docs'));

        switch ($method) {
            case '1':
                $mailer->set_from_xuser($mconfig['fromuid']);
                $ret = $mailer->send_pm();
                break;
            case '2':
                $ret = $mailer->send();
                break;
        }

        $page = rmc_server_var($_POST, 'page', 1);

        return $ret;

    }

    /**
     * @desc Genera el arbol de categorías en un array
     *
     * @param array $array Referencia del Array que se rellenará
     * @param int $parent Id de la Sección padre
     * @param int $indent Sangría
     * @param int $resource Resource identifier
     * @param string $fields Columns to get from database
     * @param int $exclude Ide of section to exclude from index
     *
     * @return bool
     */
    static function getSectionTree(&$array, $parent = 0, $indent = 0, $resource = 0, $fields = '*', $exclude = 0)
    {
        global $xoopsDB;
        $sql = "SELECT $fields FROM " . $xoopsDB->prefix("mod_docs_sections") . " WHERE " . ($resource > 0 ? "id_res='$resource' AND" : '') . "
                parent='$parent' " . ($exclude > 0 ? "AND id_sec<>'$exclude'" : '') . " ORDER BY `order`";
        $result = $xoopsDB->query($sql);
        while ($row = $xoopsDB->fetchArray($result)) {
            $ret = array();
            $ret = $row;
            $ret['saltos'] = $indent;
            $array[] = $ret;
            self::getSectionTree($array, $row['id_sec'], $indent + 1, $resource, $fields, $exclude);
        }

        return true;

    }

    /**
     * Crea un índice numerado de las seccione existentes
     *
     * @param int        Parent ID of the section
     * @param int        Number of spaces for indentation
     * @param RDResource Resource object
     * @param string     Var name to assign in {@link RMTemplate}
     * @param string     Contains the number for current section
     * @param bool       Indicates that the index must be assigned to {@link RMTemplate}
     * @param array      Refernce to an array that will be filled with index (when $assign = false)
     * @param bool       Indicates if function must be return also the content for section
     *
     * @return true;
     */
    static function sections_tree_index(
        $parent = 0,
        $jumps = 0,
        RDResource $res,
        $var = 'rd_sections_index',
        $number = '',
        $assign = true,
        &$array = null,
        $text = false,
        $nested = false)
    {

        global $xoopsUser;

        $db = XoopsDatabaseFactory::getDatabaseConnection();

        if ($var == '' && $assign) return false;

        if (get_class($res) != 'RDResource') return;

        $sql = "SELECT * FROM " . $db->prefix("mod_docs_sections") . " WHERE " . ($res->id() > 0 ? "id_res='" . $res->id() . "' AND" : '') . "
                parent='$parent' ORDER BY `order`";
        $result = $db->query($sql);
        $sec = new RDSection();
        $i = 1; // Counter
        $num = 1;

        while ($row = $db->fetchArray($result)) {
            $sec->assignVars($row);
            $section = array(
                'id' => $sec->id(),
                'title' => $sec->getVar('title'),
                'nameid' => $sec->getVar('nameid'),
                'jump' => $jumps,
                'link' => $sec->permalink(),
                'order' => $sec->getVar('order'),
                'author' => $sec->getVar('uid'),
                'author_name' => $sec->getVar('uname'),
                'created' => $sec->getVar('created'),
                'modified' => $sec->getVar('modified'),
                'number' => $jumps == 0 ? $num : ($number != '' ? $number . '.' : '') . $i,
                'comments' => $sec->getVar('comments'),
                'edit' => !$xoopsUser ? 0 : ($xoopsUser->isAdmin() ? true : $res->isEditor($xoopsUser->uid())),
                'resource' => $sec->getVar('id_res'),
                'metas' => $sec->metas(),
                'parent' => $sec->getVar('parent'),
                'empty' => '' == $sec->content,
                'single' => $sec->single,
            );

            if ($text) {
                $section['content'] = $sec->getVar('content');
            }

            $sec->clear_metas();
            if ($nested) {
                $secs = array();
                self::sections_tree_index($sec->id(), $jumps + 1, $res, $var, ($number != '' ? $number . '.' : '') . $i, $assign, $secs, $text, true);

                $section['sections'] = $secs;

                $array[] = $section;

            } else {

                if ($assign) {
                    RMTemplate::get()->assign($var, $section);
                } else {
                    $array[] = $section;
                }

                self::sections_tree_index($sec->id(), $jumps + 1, $res, $var, ($number != '' ? $number . '.' : '') . $i, $assign, $array, $text);
            }
            $i++;
            if ($jumps == 0) $num++;
        }

        return true;

    }

    /**
     * Get all references list according to given parameters
     *
     * @param int        Resource ID
     * @param Referenced var to return results count
     * @param string     Search keyword
     * @param int        Start results
     * @param int        Results number limit
     *
     * @return array
     */
    static public function references($res = 0, &$count, $search = '', $start = 0, $limit = 15)
    {

        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = "SELECT COUNT(*) FROM " . $db->prefix('mod_docs_references') . ($res > 0 ? " WHERE id_res='$res'" : '');

        if ($search != '')
            $sql .= ($res > 0 ? " AND " : " WHERE ") . " (text LIKE '%$search%')";

        $cache = ObjectsCache::get();
        if ($res > 0) {
            $res = $cache->cached('docs', 'res-' . $res);
            if (!$res) {
                $res = new RDResource($res);
                $cache->set_cache('docs', 'res-' . $res->id(), $res);
            }
        }

        list($num) = $db->fetchRow($db->query($sql));
        $limit = $limit <= 0 ? 15 : $limit;
        $count = $num;

        //Fin de navegador de páginas    
        $sql = str_replace("COUNT(*)", "*", $sql);
        $sql .= " ORDER BY id_ref DESC LIMIT $start,$limit";

        $result = $db->query($sql);
        $references = array();
        $ref = new RDReference();
        while ($rows = $db->fetchArray($result)) {

            $ref->assignVars($rows);

            if ($res->isNew()) {
                $res = $cache->cached('docs', 'res-' . $ref->id_res);
                if (!$res) {
                    $res = new RDResource($ref->id_res);
                    $cache->set_cache('docs', 'res-' . $ref->id_res, $res);
                }
            }

            $references[] = array(
                'id' => $ref->id(),
                'text' => $ref->getVar('text'),
                'resource' => $res->getVar('title')
            );

        }

        return $references;

    }

    /**
     * Get all figures list according to given parameters
     *
     * @param int        Resource ID
     * @param Referenced var to return results count
     * @param string     Search keyword
     * @param int        Start results
     * @param int        Results number limit
     *
     * @return array
     */
    public function figures($res = 0, &$count, $search = '', $start = 0, $limit = 15)
    {

        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = "SELECT COUNT(*) FROM " . $db->prefix('mod_docs_figures') . ($res > 0 ? " WHERE id_res='$res'" : '');

        if ($search != '')
            $sql .= ($res > 0 ? " AND " : " WHERE ") . " (desc LIKE '%$k%' OR content LIKE '%$k%')";

        if ($res > 0) $res = new RDResource($res);

        list($num) = $db->fetchRow($db->query($sql));
        $limit = $limit <= 0 ? 15 : $limit;
        $count = $num;

        //Fin de navegador de páginas    
        $sql = str_replace("COUNT(*)", "*", $sql);
        $sql .= " ORDER BY id_fig DESC LIMIT $start,$limit";

        $cache = ObjectsCache::get();

        $result = $db->query($sql);
        $figures = array();
        $ref = new RDFigure();
        while ($rows = $db->fetchArray($result)) {

            $ref->assignVars($rows);

            if ($res->isNew()) {
                $res = $cache->cached('docs', 'res-' . $ref->id_res);
                if (!$res) {
                    $res = new RDResource($ref->id_res);
                    $cache->set_cache('docs', 'res-' . $ref->id_res, $res);
                }
            }

            $figures[] = array('id' => $ref->id(), 'title' => $ref->getVar('title'), 'desc' => $ref->getVar('desc'), 'content' => substr(TextCleaner::getInstance()->clean_disabled_tags($ref->getVar('content')), 0, 50) . "...",
                'resource' => $res->getVar('title'));

        }

        return $figures;

    }

    /**
     * Get the MAX or MIN value for order
     *
     * Get the current order of a section (max or min)
     *
     * @param string MAX or MIN
     */
    public function order($which = 'MAX', $parent = 0, $res = 0)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        if ($which != 'MAX' && $which != 'MIN') $which = 'MAX';

        $sql = "SELECT $which(`order`) FROM " . $db->prefix("mod_docs_sections") . " WHERE parent='$parent' AND id_res='$res'";
        list($order) = $db->fetchRow($db->query($sql));
        return $order;

    }

    /**
     * Get the URL for module
     * return string
     */
    static public function url()
    {
        $config = RMSettings::module_settings('docs');
        if ($config->permalinks) {


            $perma = ($config->subdomain != '' ? $config->subdomain : XOOPS_URL) . $config->htpath;

        } else {

            $perma = XOOPS_URL . '/modules/docs/';

        }

        return $perma;
    }

    /**
     * Get list of resources according to given parameters
     * @param string $type
     * @param int $limit
     * @param bool $render
     * @return array|string
     */
    static function resources_index($type = 'all', $limit = 15, $render = true)
    {

        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = "SELECT * FROM " . $db->prefix("mod_docs_resources");
        if ($type == 'featured') {
            $sql .= " WHERE public=1 AND approved=1 AND featured=1 ORDER BY RAND()";
        } elseif ($type == 'all') {
            $sql .= " WHERE public=1 AND approved=1 ORDER BY created DESC";
        } elseif ($type == 'popular'){
            $sql .= " WHERE public=1 AND approved=1 ORDER BY `reads` DESC";
        }

        $sql .= " LIMIT 0,$limit";

        $result = $db->query($sql);
        $resources = array();
        $res = new RDResource();
        while ($row = $db->fetchArray($result)) {

            $res->assignVars($row);
            $resources[] = array(
                'id' => $res->id(),
                'title' => $res->getVar('title'),
                'desc' => $res->getVar('description'),
                'link' => $res->permalink(),
                'image' => $res->image,
                'created' => $res->created,
                'reads' => $res->reads,
                'owner' => $res->owname
            );
        }

        if(!$render){
            return $resources;
        }

        ob_start();

        include RMEvents::get()->run_event('docs.template.resources.index', RMTemplate::get()->get_template('docs-resources-index.php', 'module', 'docs'));

        $ret = ob_get_clean();

        return $ret;

    }

    /**
     * Send an error 404
     *
     * @param string $msg
     */
    static function error_404($msg = '')
    {

        RMFunctions::error_404($msg == '' ? __('Document not found', 'docs') : $msg, 'docs');

    }

    /**
     * Gets the first parent for a section
     *
     * @param int $id
     *
     * @return RDSection object
     */
    static function super_parent($id)
    {
        global $db;

        if ($id <= 0) return;

        $cache = ObjectsCache::get();
        $sec = $cache->cached('docs', 'sec-' . $id);
        if (!$sec) {
            $sec = new RDSection($id);
            $cache->set_cache('docs', 'sec-' . $id, $sec);
        }

        if ($sec->isNew()) return array();

        if ($sec->getVar('parent') > 0) {
            $section = self::super_parent($sec->getVar('parent'));
        } else {
            $section = $sec;
        }

        return $section;

    }

    /**
     * Get a single section and all his sub sections
     */
    public function get_section_tree($id, RDResource $res, $number = 1, $text = false)
    {
        global $xoopsUser;

        $db = XoopsDatabaseFactory::getDatabaseConnection();

        if (get_class($res) != 'RDResource') return;

        $sql = "SELECT * FROM " . $db->prefix("mod_docs_sections") . " WHERE " . ($res->id() > 0 ? "id_res='" . $res->id() . "' AND" : '') . "
                id_sec='$id'";
        $result = $db->query($sql);
        if ($db->getRowsNum($result) <= 0) return;

        $sec = new RDSection();
        $row = $db->fetchArray($result);
        $sec->assignVars($row);

        $sections[0] = array(
            'id' => $sec->id(),
            'title' => $sec->getVar('title'),
            'nameid' => $sec->getVar('nameid'),
            'jump' => 0,
            'link' => $sec->permalink(),
            'order' => $sec->getVar('order'),
            'author' => $sec->getVar('uid'),
            'author_name' => $sec->getVar('uname'),
            'created' => $sec->getVar('created'),
            'modified' => $sec->getVar('modified'),
            'number' => $number,
            'comments' => $sec->getVar('comments'),
            'edit' => !$xoopsUser ? 0 : ($xoopsUser->isAdmin() ? true : $res->isEditor($xoopsUser->uid())),
            'resource' => $sec->getVar('id_res'),
            'metas' => $sec->metas()
        );

        if ($text) {
            $sections[0]['content'] = $sec->getVar('content');
        }

        self::sections_tree_index($sec->id(), 1, $res, '', $number, false, $sections, $text);

        return $sections;
    }

    /**
     * @desc Determina si usuario tiene permiso para crear nueva publicación
     *
     * @param int array $gid  Ids de grupos a que pertenece usuario
     * @param int array $groups Ids de grupos con permiso a crear publicación
     **/
    public function new_resource_allowed($gid)
    {

        $config = RMSettings::module_settings('docs');

        $groups = $config->create_groups;

        if (!is_array($gid)) {
            if ($gid == XOOPS_GROUP_ADMIN) return true;
            return in_array($gid, $groups);
        }

        if (in_array(XOOPS_GROUP_ADMIN, $gid)) return true;

        foreach ($gid as $k) {

            if (in_array($k, $groups)) return true;
        }

        return false;

    }

    /**
     * Creates the BreadCrumb bar with basic options
     */
    public function breadcrumb()
    {
        global $xoopsModule;
        // Breadcrumb
        $bc = RMBreadCrumb::get();
        $bc->add_crumb(__('Home Page', 'docs'), XOOPS_URL);
        $bc->add_crumb($xoopsModule->name(), RDFunctions::url());
    }

    /**
     * Make the correct link for a specific page
     */
    public function make_link($page, $params = array())
    {

        $config = RMSettings::module_settings('docs');

        if (!$config->permalinks) {

            $q = '';
            foreach ($params as $k => $v) {
                $q .= "&amp;$k=$v";
            }

            $link = XOOPS_URL . '/modules/docs/index.php?page=' . ($page == 'explore' ? 'search' : $page) . $q;
            return $link;

        }

        $base_url = ($config->subdomain != '' ? $config->subdomain : XOOPS_URL) . rtrim($config->htpath, '/') . '/';

        switch ($page) {
            case 'explore':
                $link = $base_url . $page . '/' . $params['by'] . '/' . (isset($params['page']) ? 'page/' . $params['page'] . '/' : '');
                break;
            case 'search':
                $link = $base_url . $page . '/';
                break;
        }

        return $link;

    }

    /**
     * For standalone documents
     */
    static function standalone()
    {
        global $xoopsTpl, $xoopsModuleConfig;

        RMTemplate::get()->add_style('standalone.min.css', 'docs');
        RMTemplate::get()->add_style('font-awesome.min.css', 'rmcommon');
        RMTemplate::get()->add_script('jquery.ck.js', 'rmcommon', ['footer' => 1]);
        //RMTemplate::get()->add_head('<link rel="stylesheet" type="text/css" media="all" href="'.$xoopsModuleConfig['standalone_css'].'" />');
        $rd_contents = ob_get_clean();
        $xoopsTpl->assign('rd_contents', $rd_contents);

        // Text alignment
        $align = isset($_COOKIE['docu_align']) ? $_COOKIE['docu_align'] : 'left';
        if (!in_array($align, array('left', 'center', 'justify'))) {
            $align = 'left';
        }
        $xoopsTpl->assign('body_align', 'align-' . $align);
        $xoopsTpl->assign('standalone_theme', 'theme-' . $xoopsModuleConfig['theme']);
        if(isset($_COOKIE['docsindex']) && 1 == $_COOKIE['docsindex']){
            $xoopsTpl->assign('indexClass', 'with-index');
        }

        unset($rd_contents);
        $xoopsTpl->display(RMTemplate::getInstance()->path('docs-display-standalone.tpl', 'module', 'docs'));
        die();

    }

    /**
     * Construct and return the dialog to insert links in editors
     */
    static public function insertLinkDialog()
    {

        global $xoopsUser;

        $user = RMHttpRequest::post('user', 'string', $xoopsUser->uname());
        $id = RMHttpRequest::post('id', 'integer', 0);
        $editor_id = RMHttpRequest::request('editor', 'string', '');
        $editor_type = RMHttpRequest::request('type', 'string', '');

        $user = str_replace('@', '', $user);


        $db = XoopsDatabaseFactory::getDatabaseConnection();

        // Load books
        $sql = "SELECT id_res, `title` FROM " . $db->prefix("mod_docs_resources") . " WHERE owname = '$user' AND approved = 1 AND (`public` = 1 OR owner = " . $xoopsUser->uid() . ")";
        $result = $db->query($sql);
        $books = array();
        $sections = array();

        while ($row = $db->fetchArray($result)) {
            $books[] = array(
                'title' => $row['title'],
                'id' => $row['id_res']
            );
        }

        // Load sections if exists
        if ($id > 0) {
            $res = new RDResource($id);

            if (!$res->isNew()) {
                self::sections_tree_index(0, 0, $res, '', '', false, $sections);
            }
        }


        ob_start();
        include RMTemplate::get()->get_template('ajax/docs-link-dialog.php', 'module', 'docs');
        $dialog = ob_get_clean();
        return $dialog;

    }

    /**
     * Construct and return the dialog to insert notes in content
     */
    static public function insertNotesDialog()
    {
        global $xoopsUser, $ajax;

        if (!$xoopsUser) {
            $ajax->ajax_response(
                __('You don\'t have authorization for this action!', 'docs'),
                1, 1
            );
        }

        // Book ID
        $id = RMHttpRequest::request('id', 'integer', 0);
        $editor_id = RMHttpRequest::request('editor', 'string', '');
        $editor_type = RMHttpRequest::request('type', 'string', '');
        $search = RMHttpRequest::request('search', 'string', '');
        $page = RMHttpRequest::request('page', 'integer', 1);

        if ($id <= 0) {
            $ajax->ajax_response(
                __('You must specify a valid book identifier', 'docs'),
                1, 1
            );
        }

        $res = new RDResource($id);
        if ($id <= 0) {
            $ajax->ajax_response(
                __('The specified book does not exists!', 'docs'),
                1, 1
            );
        }

        if ($res->owner != $xoopsUser->uid()) {
            $ajax->ajax_response(
                __('You don\'t have access to references for this book', 'docs'),
                1, 1
            );
        }

        $total = 0;
        $start = ($page * 10) - 10;
        $notes = self::references($res->id(), $total, $search, $start, 10);

        $nav = new RMPageNav($total, 10, $page);
        $nav->target_url('#" data-page="{PAGE_NUM}');

        ob_start();
        include RMTemplate::get()->get_template('ajax/docs-notes-dialog.php', 'module', 'docs');
        $dialog = ob_get_clean();
        return $dialog;

    }

    /**
     * Save a note to database
     */
    static public function saveNote()
    {

        global $xoopsUser;

        $id = RMHttpRequest::post('id', 'integer', 0);
        $text = RMHttpRequest::post('text', 'string', '');

        $ajax = new Rmcommon_Ajax();
        $ajax->prepare_ajax_response();

        // Check if user have edition permissions over book
        if (!$xoopsUser) {
            $ajax->ajax_response(
                __('You must be logged in order to create notes', 'docs'),
                1, 0
            );
        }

        if ($id <= 0) {
            $ajax->ajax_response(
                __('Book not specified', 'docs'),
                1, 1
            );
        }

        $res = new RDResource($id);
        if ($res->isNew()) {
            $ajax->ajax_response(
                __('Specified book does not exists', 'docs'),
                1, 1
            );
        }

        if (!$res->isEditor($xoopsUser->uid()) && !$xoopsUser->isAdmin() && $res->owner != $xoopsUser->uid()) {
            $ajax->ajax_response(
                __('You don\'t have rights to add notes to this book', 'docs'),
                1, 1
            );
        }

        // Create the note
        $note = new RDReference();
        $note->setVar('text', $text);
        $note->setVar('id_res', $res->id());

        if ($note->save()) {
            $ajax->ajax_response(
                __('The note has been created', 'docs'),
                0, 1, array(
                    'note' => $note->id(),
                    'res' => $res->id(),
                    'text' => $text
                )
            );
        } else {
            $ajax->ajax_response(
                __('An error occurs while trying to save note:', 'docs') . ' ' . $note->errors(),
                1, 0
            );
        }

    }

}

/**
 * Insert edit link in sectionsa array
 */
function rd_insert_edit(&$item, $key)
{
    global $xoopsUser, $res, $xoopsModule;

    if (!$xoopsUser) return;

    if (!$item['edit']) return;

    if (!$res->isEditor($xoopsUser->uid()) && !$xoopsUser->isAdmin())
        return array('editlink' => '');

    $config = RMSettings::module_settings('docs');

    if ($xoopsUser->isAdmin()) {

        $item['editlink'] = XOOPS_URL . '/modules/docs/admin/sections.php?action=edit&amp;id=' . $res->id() . '&amp;sec=' . $item['id'];

    } else {

        if ($config->permalinks) {

            $item['editlink'] = RDURL . '/edit/' . $item['id'] . '/' . $item['resource'] . '/';

        } else {

            $item['editlink'] = RDURL . '?page=content&amp;id=' . $item['id'] . '&amp;res=' . $res->id();

        }

    }

}