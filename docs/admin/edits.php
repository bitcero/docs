<?php
// $Id: edits.php 821 2011-12-08 23:46:19Z i.bitcero $
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('RMCLOCATION', 'waiting');
include 'header.php';

/**
* @desc Muestra una lista con los elementos editados esperando aprovación
*/
function showEdits(){
	global $xoopsModule, $db, $adminTemplate, $tpl, $mc;
	
	$sql = "SELECT * FROM ".$db->prefix("mod_docs_edits");
    list($num) = $db->fetchRow($db->query($sql));
    $page = rmc_server_var($_REQUEST, 'page', 1);
    $limit = 15;

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('edits.php?page={PAGE_NUM}');
    
    $sql = "SELECT * FROM ".$db->prefix("mod_docs_edits")." ORDER BY `modified` DESC LIMIT $start,$limit";
    $result = $db->query($sql);
    $sections = array();
	
	while ($row = $db->fetchArray($result)){
		$edit = new RDEdit();
		$edit->assignVars($row);
		$sec = new RDSection($edit->getVar('id_sec'));
		$sections[] = array(
            'id'=>$edit->id(),
            'section'=>array(
                'id'=>$sec->id(),
                'title'=>$sec->getVar('title'),
				'link'=>$sec->permalink()
            ),
            'title'=>$edit->getVar('title'),
            'date'=>RMTimeFormatter::get()->format($edit->getVar('modified'), __('%M% %d%, %Y%', 'docs')),
			'uname'=>$edit->getVar('uname')
        );
	}
	
	$bc = RMBreadCrumb::get();
    $bc->add_crumb( __('Waiting for approval', 'docs'), '', 'fa fa-clock-o' );
	
	xoops_cp_header();
    
    RMTemplate::get()->add_script('jquery.checkboxes.js', 'rmcommon');
    RMTemplate::get()->add_script('admin.js', 'docs');
    RMTemplate::get()->add_style('admin.css', 'docs');
    
    include RMEvents::get()->run_event("docs.waiting.template", RMTemplate::get()->get_template("admin/docs-waiting.php",'module','docs'));
	
	xoops_cp_footer();
}

/**
* @desc Muestra el contenido de las secciones editadas y original para su revisión
*/
function reviewEdit(){
	global $xoopsModule;
	
	$id = rmc_server_var($_GET, 'id', 0);
	
	if ($id<=0){
		redirectMsg('edits.php', __('You have not specified any section!','docs'), 1);
		die();
	}
	
	$edit = new RDEdit($id);
	if ($edit->isNew()){
		redirectMsg('edits.php', __('Specified content does not exists!','docs'), 1);
		die();
	}
	
	$sec = new RDSection($edit->getVar('id_sec'));
	if ($sec->isNew()){
		redirectMsg('edits.php', __('The section indicated by current element does not exists!','docs'), 1);
		die();
	}
	
	// Datos de la Sección
	$section = array(
        'id'=>$sec->id(), 
        'title'=>$sec->getVar('title'),
		'text'=>$sec->getVar('content'),
        'link'=>$sec->permalink(),
        'res'=>$sec->getVar('id_res')
    );
	
	// Datos de la Edición
	$new_content = array(
        'id'=>$edit->id(),
        'title'=>$edit->getVar('title'),
        'text'=>$edit->getVar('content')
    );
	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./edits.php'>".__('Waiting Content','docs')."</a> &raquo; ".sprintf(__('Editing %s','docs'), $sec->getVar('title')));
	
	xoops_cp_header();
    
    RMTemplate::get()->add_style('admin.css', 'docs');
	
    include RMEvents::get()->run_event('docs.template.review.waiting', RMTemplate::get()->get_template('admin/docs-review-edit.php', 'module', 'docs'));
    
	xoops_cp_footer();
	
}

function approveEdits(){
	
	$edits = isset($_REQUEST['edits']) ? $_REQUEST['edits'] : array();
	
	if (!is_array($edits) && $edits<=0){
		redirectMsg('./edits.php', _AS_AH_NOID, 1);
		die();
	}
	
	$edits = !is_array($edits) ? array($edits) : $edits;
	
	if (count($edits)<=0){
		redirectMsg('./edits.php', _AS_AH_NOID, 1);
		die();
	}
	
	$errors = false;
	
	foreach ($edits as $k){
		$edit = new AHEdit($k);
		if ($edit->isNew()){
			$errors = true;
			continue;
		}
		
		$sec = new AHSection($edit->section());
		if ($sec->isNew()){
			$errors = true;
			continue;
		}
		
		// Guardamos los valores
		$sec->setTitle($edit->title());
		$sec->setNameId($edit->nameId());
		$sec->modified($edit->modified());
		$sec->setUid($edit->uid());
		$sec->setUname($edit->uname());
		$sec->setOrder($edit->order());
		$sec->setParent($edit->parent());
		$sec->setVar('dohtml', $edit->getVar('dohtml'));
		$sec->setVar('doxcode', $edit->getVar('doxcode'));
		$sec->setVar('dobr', $edit->getVar('dobr'));
		$sec->setVar('doimage', $edit->getVar('doimage'));
		$sec->setVar('dosmiley', $edit->getVar('dosmiley'));
		$sec->setContent($edit->content());
		
		if (!$sec->save()){
			$errors = true;
			continue;
		}
		
		$edit->delete();
		
	}
	
	if ($errors){
		redirectMsg('./edits.php', _AS_AH_ERRORSONAPPROVE, 1);
		die();
	} else {
		redirectMsg('./edits.php', _AS_AH_DBOK, 0);
		die();
	}
	
}

function deleteEdits(){
	$edits = isset($_REQUEST['edits']) ? $_REQUEST['edits'] : array();
	
	if (!is_array($edits) && $edits<=0){
		redirectMsg('./edits.php', _AS_AH_NOID, 1);
		die();
	}
	
	$edits = !is_array($edits) ? array($edits) : $edits;
	
	if (count($edits)<=0){
		redirectMsg('./edits.php', _AS_AH_NOID, 1);
		die();
	}
	
	$errors = false;
	
	foreach ($edits as $k){
		$edit = new AHEdit($k);
		if ($edit->isNew()){
			$errors = true;
			continue;
		}
		
		$edit->delete();
		
	}
	
	if ($errors){
		redirectMsg('./edits.php', _AS_AH_ERRORSONAPPROVE, 1);
		die();
	} else {
		redirectMsg('./edits.php', _AS_AH_DBOK, 0);
		die();
	}
}

function showFormEdits(){
	global $xoopsModule, $xoopsConfig;
	
    $id = rmc_server_var($_GET, 'id', 0);
	
	if ($id<=0){
		redirectMsg('edits.php', __('You have not specified any waiting section!','docs'), 1);
		die();
	}
	
	$edit = new RDEdit($id);
	if ($edit->isNew()){
		redirectMsg('edits.php', __('Specified content does not exists!','docs'), 1);
		die();
	}
	
	$sec = new RDSection($edit->getVar('id_sec'));
	if ($sec->isNew()){
		redirectMsg('edits.php', __('This waiting content does not have any section assigned!','docs'), 1);
		die();
	}
	
	$res = new RDResource($sec->getVar('id_res'));

	$form=new RMForm(__('Editing Waiting Content','docs'),'frmsec','edits.php');
	$form->addElement(new RMFormLabel(__('Belong to','docs'),$res->getVar('title')));
	$form->addElement(new RMFormText(__('Title','docs'),'title',50,200,$edit->getVar('title')),true);
	$form->addElement(new RMFormEditor(__('Contenido','docs'),'content','90%','300px',$edit->getVar('content', 'e')),true);
	
	// Arbol de Secciones
	$ele= new RMFormSelect(__('Parent Section','docs'),'parent');
	$ele->addOption(0,__('Select section...','docs'));
	$tree = array();
	RDFunctions::sections_tree_index(0, 0, $res, '', '', false, $tree, false);
	foreach ($tree as $k){
		$ele->addOption($k['id'], str_repeat('&#151;', $k['jump']).' '.$k['title'], $edit->getVar('parent')==$k['id'] ? 1 : 0);
	}
	
	$form->addElement($ele);

	$form->addElement(new RMFormText(__('Display order','docs'),'order',5,5,$edit->getVar('order')),true);
	// Usuario
	$form->addElement(new RMFormUser(__('Owner','docs'), 'uid', 0, array($edit->getVar('uid')), 30));

	$buttons =new RMFormButtonGroup();
	$buttons->addButton('sbt',__('Save Now','docs'),'submit');
	$buttons->addButton('cancel',__('Cancel','docs'),'button', 'onclick="window.location=\'edits.php\';"');

	$form->addElement($buttons);

	$form->addElement(new RMFormHidden('action','save'));
	$form->addElement(new RMFormHidden('id',$edit->id()));
	
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; <a href='./edits.php'>".__('Waiting Content','docs')."</a> &raquo; ".sprintf(__('Editing %s','docs'), $edit->getVar('title')));
	xoops_cp_header();
	
    RMTemplate::get()->assign('xoops_pagetitle', __('Editing Waiting Content','docs'));
	$form->display();

	xoops_cp_footer();
}

function saveEdit(){
	global $db, $util, $xoopsUser;
	
	foreach ($_POST as $k => $v){
		$$k = $v;
	}
	
	if (!$util->validateToken()){
		redirectMsg('edits.php?op=edit&id='.$id, _AS_AH_SESSINVALID, 1);
		die();
	}
	
	if ($id<=0){
		redirectMsg('./edits.php', _AS_AH_NOID, 1);
		die();
	}
	
	$edit = new AHEdit($id);
	if ($edit->isNew()){
		redirectMsg('./edits.php', _AS_AH_NOTEXISTS, 1);
		die();
	}
	
	$sec = new AHSection($edit->section());
	if ($sec->isNew()){
		redirectMsg('./edits.php', _AS_AH_NOTEXISTSSEC, 1);
		die();
	}
	
	//Comprueba que el título de la sección no exista
	$sql="SELECT COUNT(*) FROM ".$db->prefix('pa_sections')." WHERE title='$title' AND id_res='".$sec->resource()."' AND id_sec<>'".$sec->id()."'";
	list($num)=$db->fetchRow($db->queryF($sql));
	if ($num>0){
		redirectMsg('./edits.php?op=edit&id='.$edit->id(), _AS_AH_ERRTITLE,1);	
		die();
	}
	
	//Genera $nameid Nombre identificador
	$found=false; 
	$i = 0;
	do{
    		$nameid = $util->sweetstring($title).($found ? $i : '');
        	$sql = "SELECT COUNT(*) FROM ".$db->prefix('pa_sections'). " WHERE nameid = '$nameid'";
        	list ($num) =$db->fetchRow($db->queryF($sql));
        	if ($num>0){
        		$found =true;
        	    $i++;
        	}else{
        		$found=false;
        	}
        
    	}while ($found==true);
	
	$sec->setTitle($title);
	$sec->setContent($content);
	$sec->setOrder($order);
	$sec->setNameId($nameid);
	$sec->setParent($parent);
	$sec->setVar('dohtml', isset($dohtml) ? 1 : 0);
	$sec->setVar('doxcode', isset($doxcode) ? 1 : 0);
	$sec->setVar('dobr', isset($dobr) ? 1 : 0);
	$sec->setVar('dosmiley', isset($dosmiley) ? 1 : 0);
	$sec->setVar('doimage', isset($dosmiley) ? 1 : 0);
	if (!isset($uid)){
		$sec->setUid($xoopsUser->uid());
		$sec->setUname($xoopsUser->uname());
	} else {
		$xu = new XoopsUser($uid);
		if ($xu->isNew()){
			$sec->setUid($xoopsUser->uid());
			$sec->setUname($xoopsUser->uname());
		} else {
			$sec->setUid($uid);
			$sec->setUname($xu->uname());
		}
	}
	$sec->setModified(time());
	if (!$sec->save()){
		redirectMsg('edits.php', _AS_AH_DBERROR . '<br />' . $sec->errors(), 1);
		die();
	} 
	
	$edit->delete();
	redirectMsg('edits.php', _AS_AH_DBOK, 0);
	
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch($action){
	case 'review':
		reviewEdit();
		break;
	case 'approve':
		approveEdits();
		break;
	case 'edit':
		showFormEdits();
		break;
	case 'save':
		saveEdit();
		break;
	case 'delete':
		deleteEdits();
		break;
	default:
		showEdits();
		break;
}

?>
