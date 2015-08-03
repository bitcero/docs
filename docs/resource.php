<?php
// $Id: resource.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if (isset($special) && ($special=='references' || $special=='figures')){
	$xoopsOption['module_subpage'] = 'content';
} else {
	$xoopsOption['module_subpage'] = 'resource';
}

// Check if Document exist
$res= new RDResource($id);
if ($res->isNew()){
    // Error 404 - When resrouce does not exists
	RDFunctions::error_404();
}

if($res->getVar('single'))
    define('RD_LOCATION','resource_content');

include ('header.php');

//Verificamos si la publicacion esta aprobada
if (!$res->getVar('approved')){
	redirect_header(RDURL, 1, __('Sorry, this Document does not exists!','docs'));
	die();
}

//Verifica si el usuario cuenta con permisos para ver la publicación
$allowed = $res->isAllowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS);
if (!$allowed && !$res->getVar('show_index')){
	redirect_header(RDURL, 2, __('Sorry, you are not authorized to view this Document','docs'));
	die();
}

if (!$allowed && !$res->getVar('quick')){
	redirect_header(RDURL, 2, __('Sorry, you are not authorized to view this Document','docs'));
	die();
}

RDFunctions::breadcrumb();
RMBreadCrumb::get()->add_crumb($res->getVar('title'), $res->permalink());

// Comments
RMFunctions::get_comments('docs', 'res='.$res->id(), 'module', 0);
RMFunctions::comments_form('docs', 'res='.$res->id(), 'module', RDPATH.'/class/docs-controller.php');

// Owner and editors
$owner = new RMUser( $res->owner );
$editors = array();
foreach( $res->editors as $uid ){
    if ( $uid == $res->owner ) continue;
    $editor = new RMUser( $uid );
    $editors[$uid] =  $editor->name != '' ? $editor->name : $editor->uname;
}

// Check if we must show all content for Document
if($res->getVar('single')){
    global $last_author;
    if(!$allowed)
        RDfunctions::error_404();
        
    // Show all content
    $toc = array();
    RDFunctions::sections_tree_index(0, 0, $res, '', '', false, $toc, true);
    $last_author = array();
    array_walk($toc, 'rd_insert_edit');
    $last_modification = 0;
    foreach($toc as $sec){
        if($sec['modified']>$last_modification){
            $last_modification = $sec['modified'];
            $last_author = array(
                'id' => $sec['author'],
                'name' => $sec['author_name']
            );
        }
    }
    
    RMTemplate::get()->add_jquery();
    RMTemplate::get()->add_script('docs.min.js', 'docs');
    
    // Comments
    RMFunctions::get_comments('docs', 'res='.$res->id(), 'module', 0);
    RMFunctions::comments_form('docs', 'res='.$res->id(), 'module', RDPATH.'/class/docscontroller.php');
    
    $res->add_read();
    
    // URLs
    if ($xoopsModuleConfig['permalinks']){
        $config = array();
        $config =& $xoopsModuleConfig;
        /**
        * @todo Generate friendly links
        */
        if (RMFunctions::plugin_installed('topdf')){
            $pdf_book_url = ($xoopsModuleConfig['subdomain']!='' ? $xoopsModuleConfig['subdomain'] : XOOPS_URL).$xoopsModuleConfig['htpath'].'/pdfbook/'.$toc[0]['id'].'/';
        }
        $print_book_url = ($xoopsModuleConfig['subdomain']!='' ? $xoopsModuleConfig['subdomain'] : XOOPS_URL).$xoopsModuleConfig['htpath'].'/printbook/'.$toc[0]['id'].'/';
        if (RDFunctions::new_resource_allowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS))
            $publish_url = RDFunctions::url().'/publish/';
    } else {
        if (RMFunctions::plugin_installed('topdf')){
            $pdf_book_url = XOOPS_URL.'/modules/docs/index.php?page=content&amp;id='.$res->id().'&amp;action=pdfbook';
        }
        $print_book_url = XOOPS_URL.'/modules/docs/index.php?page=content&amp;id='.$res->id().'&amp;action=printbook';
        if (RDFunctions::new_resource_allowed($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS))
            $publish_url = RDFunctions::url().'/?action=publish';
    }
    
    include RMTemplate::get()->get_template('docs-display-full-resource.php','module','docs');
    
} else {
	
	if (!$allowed){
		RDFunctions::error_404();
	}

    if ($res->getVar('quick')){
        // Show Quick Index to User

        //Obtiene índice
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql="SELECT * FROM ".$db->prefix('mod_docs_sections')." WHERE id_res='".$res->id()."' AND parent=0 ORDER BY `order`";
        $result=$db->queryF($sql);

        // Quick index array
        $quick_index = array();

        while ($rows=$db->fetchArray($result)){
            $sec=new RDSection();
            $sec->assignVars($rows);

            $quick_index[] = array(
                'id'=> $id,
                'title'=> $sec->getVar('title'),
                'desc'=> TextCleaner::getInstance()->clean_disabled_tags(TextCleaner::truncate($sec->getVar('content'), 100)),
                'link'=> $sec->permalink()
            );
        }


    }

    $toc = array();
    RDFunctions::sections_tree_index(0, 0, $res, '', '', false, $toc);

    // Comments
    RMFunctions::get_comments('docs', 'res='.$res->id(), 'module', 0);
    RMFunctions::comments_form('docs', 'res='.$res->id(), 'module', RDPATH.'/class/docscontroller.php');
    
    include RMTemplate::get()->get_template('docs-resource-index.php','module','docs');
	
}

RMTemplate::get()->add_style('docs.min.css', 'docs');
RMTemplate::get()->add_jquery();
RMTemplate::get()->add_script('docs.min.js', 'docs');

if($standalone)
    RDFunctions::standalone();

include ('footer.php');
