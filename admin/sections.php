<?php
// $Id: sections.php 895 2012-01-02 18:35:50Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION', 'sections');
include 'header.php';

include_once '../include/functions.php';

/**
* @desc Obtiene las secciones hijas de una sección
* @param int $id Publicación a que pertenece
* @param int $parent Sección padre a qur pertenece
**/
function child(&$sections, $id,$parent,$indent){
	global $tpl,$db,$util;

	$child= array();
	$sql="SELECT * FROM ".$db->prefix('mod_docs_sections')." WHERE id_res='$id' AND parent='$parent' ORDER BY `order`";
	$result=$db->queryF($sql);
	while ($rows=$db->fetchArray($result)){
		$sec= new RDSection();
		$sec->assignVars($rows);

		$sections[] = array(
            'id'=>$sec->id(),
            'title'=>$sec->getVar('title'),
            'order'=>$sec->getVar('order'),
			'resource'=>$sec->getVar('id_res'),
            'parent'=>$sec->getVar('parent'),
            'indent'=>$indent,
            'permalink'=>$sec->permalink(),
            'author'=>$sec->getVar('uname'),
            'created'=>formatTimestamp($sec->getVar('created'), 'l'),
            'modified'=>formatTimestamp($sec->getVar('modified'), 'l')
        );

		child($sections, $id,$sec->id(),$indent+1);
	}
}

function rd_show_sections(){
	global $xoopsModule, $xoopsSecurity, $cuIcons;

	$id= RMHttpRequest::get( 'id', 'integer', 0);
    if($id<=0){
        redirectMsg('resources.php', __('Select a Document to see the sections inside this','docs'), 0);
        die();
    }

    $res = new RDResource($id);
    if($res->isNew()){
        redirectMsg('resources.php', __('The specified Document does not exists!','docs'), 1);
        die();
    }

    $db = XoopsDatabaseFactory::getDatabaseConnection();

	//Lista de Publicaciones
	$sql="SELECT id_res,title FROM ".$db->prefix('mod_docs_resources');
	$result=$db->queryF($sql);
    $resources = array();
	while ($rows=$db->fetchArray($result)){
		$r = new RDResource();
		$r->assignVars($rows);
		$resources[] = array('id'=>$r->id(),'title'=>$r->getVar('title'));
        unset($r);
	}

    //Secciones
    $sections = array();
    RDFunctions::sections_tree_index(0,0,$res,'','',false,$sections,false,true);

    $bc = RMBreadCrumb::get();
    $bc->add_crumb( __('Documents', 'docs'), 'resources.php', 'fa fa-book' );
    $bc->add_crumb(__('Sections', 'docs'));

    // Event
    $sections = RMEvents::get()->run_event('docs.loading.sections', $sections);

		RMTemplate::getInstance()->add_jquery(true, true);
    RMTemplate::get()->assign('xoops_pagetitle', __('Sections Management','docs'));
    RMTemplate::get()->add_style('admin.min.css', 'docs');
    RMTemplate::get()->add_script('jquery.ui.nestedSortable.js','docs', array('footer' => 1));
		RMTemplate::get()->add_script('sections.js','docs', ['id' => 'sections-js', 'footer' => 1]);

	xoops_cp_header();

    include RMEvents::get()->run_event('docs.get.sections.template', RMTemplate::get()->get_template('admin/docs-sections.php', 'module', 'docs'));

	xoops_cp_footer();

}


/**
* @desc Formulario de creación y edición de sección
**/
function rd_show_form($edit=0){
	global $xoopsModule, $xoopsConfig, $xoopsSecurity, $xoopsUser, $xoopsModuleConfig, $rmc_config;

    define('RMCSUBLOCATION','newresource');
	$id = RMHttpRequest::get( 'id', 'integer', 0 );
    $parent = RMHttpRequest::get( 'parent', 'integer', 0 );

    if ($id<=0){
        redirectMsg('sections.php?id='.$id, __('You must select a Document in order to create a new section','docs'),1);
        die();
    }

    // Check if provided Document exists
    global $res;
    $res= new RDResource($id);
    if ($res->isNew()){
        redirectMsg('sections.php?id='.$id, __('Specified Document does not exists!','docs'),1);
        die();
    }


	if ($edit){

        $id_sec = rmc_server_var($_GET, 'sec', 0);

		//Verifica si la sección es válida
		if ($id_sec<=0){
			redirectMsg('sections.php?id='.$id, __('Specify a section to edit','docs'),1);
			die();
		}

		//Comprueba si la sección es existente
		$sec=new RDSection($id_sec);
		if ($sec->isNew()){
			redirectMsg('sections.php?id='.$id, __('Specified section does not exists','docs'),1);
			die();
		}

	}

    // Get order
    $order = RDFunctions::order('MAX', $parent, $res->id());
    $order++;

    $rmc_config = RMSettings::cu_settings();
    $form=new RMForm('','frmsec','sections.php');

    if ($rmc_config->editor_type == 'tiny'){
        $tiny = TinyEditor::getInstance();
        $tiny->add_config('theme_advanced_buttons1', 'rd_refs');
        $tiny->add_config('theme_advanced_buttons1', 'rd_figures');
        $tiny->add_config('theme_advanced_buttons1', 'rd_toc');
    }

    $editor = new RMFormEditor('','content','100%','400px',$edit ? $rmc_config->editor_type == 'tiny' ? $sec->getVar('content') : $sec->getVar('content', 'e') : '','', 0);
    $usrfield = new RMFormUser('','uid',false,$edit ? array($sec->getVar('uid')) : $xoopsUser->getVar('uid'));

    RMTemplate::get()->add_style('admin.min.css', 'docs');
    RMTemplate::get()->add_script('scripts.php?file=metas.js', 'docs');
    RMTemplate::get()->add_script('jquery.validate.min.js', 'rmcommon', array('footer' => 1));
    RMTemplate::get()->add_script('docs.min.js', 'docs', array('footer' => 1));
    RMTemplate::get()->add_head_script('var docsurl = "'.XOOPS_URL.'/modules/docs";');

    $lang = include(XOOPS_ROOT_PATH . '/modules/docs/include/js-lang.php');
    RMTemplate::get()->add_head_script($lang);

    $bc = RMBreadCrumb::get();
    $bc->add_crumb( __('Documents', 'docs'), 'resources.php', 'fa fa-book' );
    $bc->add_crumb( __('Sections', 'docs'), 'sections.php?id=' . RMHttpRequest::get( 'id', 'integer', 0 ), 'fa fa-list' );
    $bc->add_crumb( $edit ? __('Edit Section', 'docs') : __('New Section', 'docs'), '', $edit ? 'fa fa-edit' : 'fa fa-plus' );
    RMTemplate::get()->assign('xoops_pagetitle', ($edit ? __('Edit Section','docs') : __('Create Section','docs')));
    xoops_cp_header();

    $sections = array();
    RDFunctions::getSectionTree($sections, 0, 0, $id, 'id_sec, title', isset($sec) ? $sec->id() : 0);
    include RMEvents::get()->run_event('docs.get.secform.template', RMTemplate::get()->get_template('admin/docs-sections-form.php', 'module', 'docs'));

	xoops_cp_footer();
}

/**
* @desc Almacena información de las secciones
**/
function rd_save_sections($edit=0){
	global $xoopsUser, $xoopsSecurity, $common;

	$single = '';

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	if (!$xoopsSecurity->check()){
		redirectMsg('./sections.php?op=new&id='.$id, __('Session token expired!','docs'), 1);
		die();
	}

    if($id<=0){
        redirectMsg('resources.php', __('A Document was not specified!','docs'), 1);
        die();
    }

    $res = new RDResource($id);
    if($res->isNew()){
        redirectMsg('resources.php', __('Specified Document does not exists!','docs'), 1);
        die();
    }

    $db = XoopsDatabaseFactory::getDatabaseConnection();

	if ($edit){

		//Verifica si la sección es válida
		if ($id_sec<=0){
			redirectMsg('./sections.php?id='.$id, __('No section has been specified','docs'),1);
			die();
		}

		//Comprueba si la sección es existente
		$sec=new RDSection($id_sec);
		if ($sec->isNew()){
			redirectMsg('./sections.php?id='.$id, __('Section does not exists!','docs'),1);
			die();
		}

		//Comprueba que el título de la sección no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('mod_docs_sections')." WHERE title='$title' AND id_res='$id' AND id_sec<>$id_sec";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./sections.php?op=new&id='.$id, __('Already exists another section with same title!','docs'),1);
			die();
		}


	}else{

		//Comprueba que el título de la sección no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('mod_docs_sections')." WHERE title='$title' AND id_res='$id' AND parent = $parent";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('./sections.php?op=new&id='.$id, __('Already exists another section with same title!','docs'),1);
			die();
		}
		$sec = new RDSection();

	}

	//Genera $nameid Nombre identificador
	$nameid = !isset($nameid) || $nameid=='' ? TextCleaner::getInstance()->sweetstring($title) : $nameid;

	$sec->setVar('title', $title);
	$sec->setVar('content', $content);
	$sec->setVar('order', $order);
	$sec->setVar('id_res', $id);
	$sec->setVar('nameid', $nameid);
	$sec->setVar('parent', $parent);
	$sec->setVar('single', 'on' == $single ? 1 : 0);

	if (!isset($uid)){
		$sec->setVar('uid', $xoopsUser->uid());
		$sec->setVar('uname', $xoopsUser->uname());
	} else {
		$xu = new XoopsUser($uid);
		if ($xu->isNew()){
			$sec->setVar('uid', $xoopsUser->uid());
			$sec->setVar('uname', $xoopsUser->uname());
		} else {
			$sec->setVar('uid', $uid);
			$sec->setVar('uname', $xu->uname());
		}
	}
	if ($sec->isNew()){
		$sec->setVar('created', time());
		$sec->setVar('modified', time());
	}else{
		$sec->setVar('modified', time());
	}

    // Metas
    if ($edit) $sec->clear_metas(); // Clear all metas
    // Initialize metas array if not exists
    if (!isset($metas)) $metas = array();
    // Get meta key if "select" is visible
    if (isset($meta_name_sel) && $meta_name_sel!='') $meta_name = $meta_name_sel;
    // Add meta to metas array
    if (isset($meta_name) && $meta_name!=''){
        array_push($metas, array('key'=>$meta_name, 'value'=>$meta_value));
    }
    // Assign metas
    foreach($metas as $value){
        $sec->add_meta($value['key'], $value['value']);
    }

    RMEvents::get()->run_event('docs.saving.section',$sec);

	if (false == $sec->save()){
		if ($sec->isNew()){
			redirectMsg('./sections.php?action=new&id='.$id, __('Database could not be updated!','docs') . "<br />" . $sec->errors(),1);
			die();
		}else{
			redirectMsg('./sections.php?action=edit&id='.$id.'&sec='.$id_sec, __('Sections has been saved but some errors ocurred','docs'). "<br />" . $sec->errors(),1);
			die();
		}

	}else{

        $res->setVar('modified', time());
        $res->save();
        RMEvents::get()->run_event('docs.section.saved',$sec);

        //header('location: sections.php?action=review&id=' . $sec->id() . '&res=' . $id . '&return=' . $return);
        //die();

		if ($return){
		    $common->uris()::redirect_with_message(
		        __('Database updated successfully!', 'docs'),
                'sections.php?action=edit&sec='.$sec->id().'&id='.$id, RMMSG_SUCCESS
            );
			//redirectMsg('./sections.php?action=edit&sec='.$sec->id().'&id='.$id, __('Database updated successfully!','docs'),0);
		} else {
            $common->uris()::redirect_with_message(
                __('Database updated successfully!', 'docs'),
                './sections.php?id='.$id, RMMSG_SUCCESS
            );
		}
	}


}

/**
* @desc Elimina la información de una sección
**/
function rd_delete_sections(){
    global $xoopsModule;

    $id = rmc_server_var($_GET, 'id', 0);
	$id_sec = rmc_server_var($_GET, 'sec', 0);

    // Check if a Document id has been provided
    if ($id<=0){
        redirectMsg('resources.php', __('You have not specify a Document id','docs'), 1);
        die();
    }

    $res = new RDResource($id);
    if($res->isNew()){
        redirectMsg('The specified Document does not exists!','docs');
        die();
    }

    // Check if a section id has been provided
	if ($id_sec<=0){
		redirectMsg('./sections.php?id='.$id, __('You have not specified a section ID to delete!','docs'),1);
		die();
	}

	$sec=new RDSection($id_sec);
	if ($sec->isNew()){
		redirectMsg('./sections.php?id='.$id, __('Specified section does not exists!','docs'),1);
		die();
	}

	if (!$sec->delete()){
	    redirectMsg('./sections.php?id='.$id, __('Errors ocurred while trying to delete sections!','docs').'<br />'.$sec->errors(),1);
		die();
	}else{
	    redirectMsg('./sections.php?id='.$id, __('Sections deleted successfully!','docs'), 0);
	}

}


/**
 * Respuesta en json
 */
function json_response($m,$e=0,$res=0){

    global $xoopsLogger;
    $xoopsLogger->renderingEnabled = false;
    error_reporting(0);
    $xoopsLogger->activated = false;

    $url = 'sections.php'.($res>0?'?id='.$res:'');

    $resp = array(
        'message' => $m,
        'error' => $e,
        'url' => $url
    );

    showMessage($m, $e);

    echo json_encode($resp);
    die();

}
/**
* @desc Modifica el orden de las secciones
**/
function changeOrderSections(){
    global $xoopsSecurity;

    if(!$xoopsSecurity->check())
        json_response(__('Session token expired!','docs'), 1);

     parse_str(rmc_server_var($_POST, 'items', ''));

    if(empty($list))
        json_response(__('Data not valid!','docs'), 1);

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $res = '';

    $pos = 0;
    foreach($list as $id => $parent){
        $parent = $parent=='root' ? 0 : $parent;

        if($parent==0 && !is_object($res))
            $res = new RDSection ($id);

        $sql = "UPDATE ".$db->prefix("mod_docs_sections")." SET parent=$parent, `order`=$pos WHERE id_sec=$id";
        $db->queryF($sql);
        $pos++;
    }

    json_response(__('Sections positions saved!','docs'),0, $res->getVar('id_res'));

}

/**
 * This function allows to review special markup included in page content.
 */
function docs_review_content(){

    // Section
    $id = RMHttpRequest::get( 'id', 'integer', 0 );
    // Document
    $doc_id = RMHttpRequest::get( 'res', 'integer', 0 );
    // Return or go to list
    $return = RMHttpRequest::get( 'return', 'integer', 0 );

    $page = new RDSection( $id, $doc_id );
    if ( $page->isNew() )
        RMUris::redirect_with_message(
            __('Specified page does not exists!', 'docs'),
            'sections.php',
            RMMSG_ERROR
        );

    $links = array();
    preg_match_all( "/\[\[([^\[\]]+)\]\]/", $page->content, $links );

    if ( count($links) <= 1 )
        RMUris::redirect_with_message(
            '',
            'sections.php?id=' . $doc_id . ($return ? '&sec=' . $id : ''),
            RMMSG_ERROR
        );

    $document = new RDResource( $doc_id );
    $tc = TextCleaner::getInstance();
    $results = $links[1];

    $reported = array();

    foreach( $results as $data ){

        $link = explode( ":", $data );
        if ( count( $link ) == 1 ){

            /**
             * The link points directly to a page inside this document
             */
            //
            $linked_page = new RDSection( $tc->sweetstring( $link[0] ), $doc_id );
            if ( $linked_page->isNew() )
                $reported['pages'][] = ucfirst( $link[0] );

        } elseif ( count( $link ) > 1 ) {

            /**
             * The link points to a document and a page from that document
             */
            $linked_document = new RDResource( $tc->sweetstring( $link[0] ) );
            if ( $linked_document->isNew() )
                $reported['docs'][] = ucfirst( $link[0] );

            $count = count( $link );
            if ( $linked_document->id() != $document->id() )
                $path = '<strong>' . ucfirst( $link[0] ) . '</strong>';
            else
                $path = '';

            for( $i=1; $i < $count; $i++){

                $path .= ($path != '' ? "/" : '') . ucfirst( $link[$i] );
                if ( $linked_document->isNew() )
                    $reported['pages'][] = $path;
                else {
                    $linked_page = new RDSection( $tc->sweetstring( $link[$i] ), $linked_document->id() );
                    if ( $linked_page->isNew() )
                        $reported['pages'][] = $path;
                }


            }

        }

    }

    RMTemplate::get()->assign( 'xoops_pagetitle', __('Content review', 'docs' ) );
    $bc = RMBreadCrumb::get();
    $bc->add_crumb( $document->title, 'resources.php', 'fa fa-book' );
    $bc->add_crumb( $page->title, 'sections.php?id=' . $doc_id, 'fa fa-list' );
    $bc->add_crumb( __('Content review', 'docs'), '', 'fa fa-eye' );
    RMTemplate::get()->add_style( 'admin.min.css', 'docs' );

    RMTemplate::get()->header();

    include RMTemplate::get()->get_template( "admin/docs-review-content.php", 'module', 'docs' );

    RMTemplate::get()->footer();

}

function docs_create_reviewed_content(){
    global $xoopsUser;
    // Section
    $id = RMHttpRequest::get( 'id', 'integer', 0 );
    // Document
    $doc_id = RMHttpRequest::get( 'res', 'integer', 0 );
    // Return or go to list
    $return = RMHttpRequest::get( 'return', 'integer', 0 );

    $page = new RDSection( $id, $doc_id );
    if ( $page->isNew() )
        RMUris::redirect_with_message(
            __('Specified page does not exists!', 'docs'),
            'sections.php',
            RMMSG_ERROR
        );

    $links = array();
    preg_match_all( "/\[\[([^\[\]]+)\]\]/", $page->content, $links );

    if ( count($links) <= 1 ){
        header('location: sections.php?id=' . $doc_id . ($return ? '&sec=' . $id : ''));
        die();
    }

    $document = new RDResource( $doc_id );
    $tc = TextCleaner::getInstance();
    $results = $links[1];

    $reported = array();

    foreach( $results as $data ){

        $link = explode( ":", $data );

        if ( count( $link ) == 1 ){

            /**
             * The link points directly to a page inside this document
             */
            //
            $linked_page = new RDSection( $tc->sweetstring( $link[0] ), $doc_id );
            if ( $linked_page->isNew() ){

                $linked_page->setVar('title', ucfirst( $link[0] ) );
                $linked_page->setVar('nameid', $tc->sweetstring( $link[0] ) );
                $linked_page->setVar('id_res', $doc_id );
                $linked_page->setVar('uid', $xoopsUser->uid() );
                $linked_page->setVar('uname', $xoopsUser->uname() );
                $linked_page->setVar('created', time() );
                $linked_page->setVar('modified', time() );
                $linked_page->save();
            }

        } elseif ( count( $link ) > 1 ) {

            /**
             * The link points to a document and a page from that document
             */
            $linked_document = new RDResource( $tc->sweetstring( $link[0] ) );
            if ( $linked_document->isNew() ){

                $linked_document->setVar( 'title', $link[0] );
                $linked_document->setVar( 'created', time() );
                $linked_document->setVar( 'modified', time() );
                $linked_document->setVar( 'owner', $xoopsUser->uid() );
                $linked_document->setVar( 'owname', $xoopsUser->uname() );
                $linked_document->setVar( 'editors', array($xoopsUser->uid()) );
                $linked_document->setVar( 'editors_approve', 1 );
                $linked_document->setVar( 'groups', array(0) );
                $linked_document->setVar( 'public', 1 );
                $linked_document->setVar( 'nameid', $tc->sweetstring( $link[0] ) );
                $linked_document->setVar( 'show_index', 1 );
                $linked_document->setVar( 'approved', 1 );
                $linked_document->save();

            }

            $count = count( $link );
            $parent = 0;

            for( $i=1; $i < $count; $i++){

                $linked_page = new RDSection( $tc->sweetstring( $link[$i] ), $linked_document->id(), $parent );
                if ( $linked_page->isNew() ){
                    $linked_page->setVar('title', ucfirst( $link[$i] ) );
                    $linked_page->setVar('nameid', $tc->sweetstring( $link[$i] ) );
                    $linked_page->setVar('id_res', $linked_document->id() );
                    $linked_page->setVar('uid', $xoopsUser->uid() );
                    $linked_page->setVar('uname', $xoopsUser->uname() );
                    $linked_page->setVar('created', time() );
                    $linked_page->setVar('modified', time() );
                    $linked_page->setVar('parent', $parent);
                    $linked_page->save();
                    $parent = $linked_page->id();
                    echo $parent.'<br>';
                }


            }

        }

    }

    RMUris::redirect_with_message(
        __('Documents and pages created successfully', 'docs'),
        'sections.php?id=' . $doc_id . ( $return ? '&action=edit&sec=' . $id : '' ),
        RMMSG_SUCCESS
    );

}


$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
	case 'new':
		rd_show_form();
	    break;
	case 'edit':
		rd_show_form(1);
	    break;
	case 'save':
		rd_save_sections();
		break;
	case 'saveedit':
		rd_save_sections(1);
		break;
	case 'delete':
		rd_delete_sections();
	    break;
    case 'savesort':
        changeOrderSections();
        break;
	case 'recommend':
		recommendSections(1);
	    break;
	case 'norecommend':
		recommendSections(0);
	    break;
    case 'review':

        /**
         * Review links and special markup included in page
         */
        docs_review_content();
        break;

    case 'create-review':

        /**
         * Create the missing document and pages
         */
        docs_create_reviewed_content();
        break;

    case 'link-resources':
        /**
         * Show the form to insert links to resources
         */
        $ajax = new Rmcommon_Ajax();
        $ajax->prepare_ajax_response();
        $ajax->ajax_response(
            __('Insert Link', 'docs'),
            RMMSG_INFO, 1,
            array(
                'content' => RDFunctions::insertLinkDialog(),
                'width' => 'medium')
        );
        break;

    case 'insert-notes':
        $ajax = new Rmcommon_Ajax();
        $ajax->prepare_ajax_response();
        $ajax->ajax_response(
            __('Insert Note', 'docs'),
            RMMSG_INFO, 1,
            array(
                'content' => RDFunctions::insertNotesDialog(),
                'width' => 'medium')
        );
        break;

    case 'save-note':
        RDFunctions::saveNote();
        break;

	default:
		rd_show_sections();
        break;
}
