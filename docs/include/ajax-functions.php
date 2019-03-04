<?php
// $Id: ajax-functions.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require  dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

$mhandler = xoops_getHandler('module');
$xoopsModule = $mhandler->getByDirName('docs');

require  dirname(__DIR__) . '/header.php';

global $xoopsLogger;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;

/**
 * Shows a list of existing resources in RapidDocs
 */
function resources_list()
{
    global $xoopsUser, $xoopsModule;

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    //Navegador de páginas
    $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_docs_resources');
    list($num) = $db->fetchRow($db->queryF($sql));

    $page = rmc_server_var($_REQUEST, 'page', 1);
    $limit = 20;

    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num <= 0 ? 0 : ($page - 1) * $limit;

    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('javascript:;" onclick="docsAjax.getSectionsList({PAGE_NUM});');

    //Fin navegador de páginas

    $sql = 'SELECT * FROM ' . $db->prefix('mod_docs_resources');
    if ($xoopsUser->isAdmin()) {
        $sql .= " ORDER BY `created` DESC LIMIT $start,$limit";
    } else {
        $sql .= ' WHERE public=1 OR (public=0 AND owner=' . $xoopsUser->uid() . ") ORDER BY `created` DESC LIMIT $start,$limit";
    }

    $result = $db->queryF($sql);
    $resources = [];

    while (false !== ($rows = $db->fetchArray($result))) {
        $res = new RDResource();
        $res->assignVars($rows);

        $resources[] = [
            'id' => $res->id(),
            'title' => $res->getVar('title'),
        ];
    }

    include RMTemplate::get()->get_template('ajax/rd_sections_list.php', 'module', 'docs');
}

/**
 * Sends the note data in json format
 */
function send_note_foredit()
{
    $id = rmc_server_var($_GET, 'id', 0);
    if ($id <= 0) {
        echo json_encode(['message' => __('Note id not provided!', 'docs'), 'error' => 1]);
        die();
    }

    $ref = new RDReference($id);
    if ($ref->isNew()) {
        echo json_encode(['message' => __('Specified note does not exists!', 'docs'), 'error' => 1]);
        die();
    }

    $ret = [
        'id' => $ref->id(),
        'title' => $ref->getVar('title'),
        'res' => $ref->getVar('id_res'),
        'text' => $ref->getVar('text', 'e'),
        'error' => 0,
    ];

    echo json_encode($ret);
    die();
}

/**
 * Get a list of existing notes cording to given parameters
 */
function notes_list()
{
    global $rmc_config;

    $id = rmc_server_var($_REQUEST, 'id', 0);
    $container = rmc_server_var($_GET, 'container', '');

    $rmc_config = RMFunctions::configs();

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    //Navegador de páginas
    $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_docs_references') . " WHERE id_res='$id'";
    $sql1 = '';
    if ($search) {
        //Separamos la frase en palabras para realizar la búsqueda
        $words = explode(' ', $search);

        foreach ($words as $k) {
            //Verificamos el tamaño de la palabra
            if (mb_strlen($k) <= 2) {
                continue;
            }
            $sql1 .= ('' == $sql1 ? ' AND ' : ' OR ') . " title LIKE '%$k%' ";
        }
    }
    list($num) = $db->fetchRow($db->queryF($sql . $sql1));

    $page = rmc_server_var($_GET, 'page', 1);
    $page = $page <= 0 ? 1 : $page;
    $limit = rmc_server_var($_GET, 'limit', 5);

    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num <= 0 ? 0 : ($page - 1) * $limit;

    $nav = new RMPageNav($num, $limit, $page, 4);
    $nav->target_url('javascript:;" onclick="docsAjax.getNotes(' . $id . ',6,{PAGE_NUM},\'' . $container . '\');"');

    //Lista de Referencias existentes
    $sql = 'SELECT id_ref,title,text FROM ' . $db->prefix('mod_docs_references') . " WHERE id_res='$id'";
    $sql .= " ORDER BY id_ref DESC LIMIT $start,$limit";
    $result = $db->query($sql);
    $references = [];
    while (false !== ($rows = $db->fetchArray($result))) {
        $references[] = ['id' => $rows['id_ref'], 'title' => $rows['title'], 'content' => TextCleaner::getInstance()->truncate($rows['text'], 150)];
    }

    include RMTemplate::get()->get_template('ajax/rd_notes_list.php', 'module', 'docs');
    die();
}

/**
 * Get a list of existing igures according to given parameters
 */
function figures_list()
{
    global $rmc_config;

    $id = rmc_server_var($_REQUEST, 'id', 0);
    $container = rmc_server_var($_GET, 'container', '');

    $rmc_config = RMFunctions::configs();

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    //Navegador de páginas
    $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_docs_figures') . " WHERE id_res='$id'";
    $sql1 = '';
    if ($search) {
        //Separamos la frase en palabras para realizar la búsqueda
        $words = explode(' ', $search);

        foreach ($words as $k) {
            //Verificamos el tamaño de la palabra
            if (mb_strlen($k) <= 2) {
                continue;
            }
            $sql1 .= ('' == $sql1 ? ' AND ' : ' OR ') . " desc LIKE '%$k%' ";
        }
    }
    list($num) = $db->fetchRow($db->queryF($sql . $sql1));

    $page = rmc_server_var($_GET, 'page', 1);
    $page = $page <= 0 ? 1 : $page;
    $limit = rmc_server_var($_GET, 'limit', 5);

    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num <= 0 ? 0 : ($page - 1) * $limit;

    $nav = new RMPageNav($num, $limit, $page, 4);
    $nav->target_url('javascript:;" onclick="docsAjax.getFigures(' . $id . ',6,{PAGE_NUM},\'' . $container . '\');"');

    //Lista de Referencias existentes
    $sql = 'SELECT id_fig,`desc`,content FROM ' . $db->prefix('mod_docs_figures') . " WHERE id_res='$id'";
    $sql .= " ORDER BY id_fig DESC LIMIT $start,$limit";
    $result = $db->query($sql);
    $figures = [];
    while (false !== ($rows = $db->fetchArray($result))) {
        $figures[] = ['id' => $rows['id_fig'], 'desc' => $rows['desc'], 'content' => TextCleaner::getInstance()->truncate($rows['content'], 150)];
    }

    include RMTemplate::get()->get_template('ajax/rd_figures_list.php', 'module', 'docs');
    die();
}

$action = rmc_server_var($_REQUEST, 'action', '');
switch ($action) {
    case 'resources-list':
        resources_list();
        break;
    case 'note-edit':
        send_note_foredit();
        break;
    case 'notes-list':
        notes_list();
        break;
    case 'figures-list':
        figures_list();
        break;
    default:
        break;
}
