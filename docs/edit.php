<?php
// $Id: edit.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include ('../../mainfile.php');

if (!$xoopsUser){
	redirect_header(RDfunctions::url(),2, __('Operation not allowed!','docs'));
	die();
}

/**
* @desc Muestra todas las secciones existentes de la publicacion
**/
function showSection(){

	global $xoopsModule,$xoopsUser,$xoopsModuleConfig, $xoopsTpl, $xoopsConfig;
	global $id, $xoopsSecurity;

	include ('header.php');
	
	//Verifica si se proporcionó una publicación para la sección
	if (trim($id)=='')
		RDFunctions::error_404();
	
	//Verifica si la publicación existe
    global $res;
	$res = new RDResource($id);
	if ($res->isNew())
        RDfunctions::error_404();
	
	//Verificamos si es una publicación aprobada
	if (!$res->getVar('approved')){
		redirect_header(RDURL, 2, __('Specified section does not exists!','docs'));
		die();
	}
	
	//Verificamos si el usuario tiene permisos de edicion
	if ($xoopsUser->uid() != $res->getVar('owner') && 
		!$res->isEditor($xoopsUser->uid()) && 
		!$xoopsUser->isAdmin()){
		redirect_header(RDFunctions::url(), 1, __('Operation not allowed!','docs'));
		die();
	}
    
    $sections = array();
    RDFunctions::sections_tree_index(0, 0, $res, '', '', false, $sections, false);
    
    //Breadcrumb
    RDFunctions::breadcrumb();
    RMBreadCrumb::get()->add_crumb(sprintf(__('Manage Document "%s"', 'docs'), $res->getVar('title')));
    
    RMTemplate::get()->add_style('docs.css','docs');
    
    array_walk($sections, 'rd_insert_edit');
	
	$xoopsTpl->assign('xoops_pagetitle', sprintf(__('Pages in %s','docs'), $res->getVar('title')));
    
    if ($xoopsModuleConfig['permalinks']){
        $new_link = RDFunctions::url().'/new/'.$res->id().'/';
    } else {
        $new_link = RDFunctions::url().'?page=edit&action=new&res='.$res->id();
    }
	
    include RMEvents::get()->run_event('docs.template.editsection', RMTemplate::get()->get_template('docs-sections-control-panel.php', 'module', 'docs'));
    
	include ('footer.php');

}


/**
* @desc Formulario para crear nueva sección
**/
function formSection($edit=0){
	global $xoopsConfig,$xoopsUser,$xoopsModuleConfig, $id, $res;    
    
    $res = rmc_server_var($_GET, 'id', $res);
    //Verifica si se proporcionó una publicación para la sección
    if ($res<=0)
        RDFunctions::error_404();
    
    
    $res = new RDResource($res);
    
    if($res->isNew())
        RDFunctions::error_404();
    
    //Verificamos si es una publicación aprobada
    if (!$res->getVar('approved')){
        redirect_header(RDURL, 2, __('Specified section does not exists!','docs'));
        die();
    }
    
    //Verificamos si el usuario tiene permisos de edicion
    if (!$xoopsUser->uid()==$res->getVar('owner') && 
        !$res->isEditor($xoopsUser->uid()) && 
        !$xoopsUser->isAdmin()){
        redirect_header($section->permalink(), 1, __('Operation not allowed!','docs'));
        die();
    }

	if ($edit){
        
        $id = rmc_server_var($_GET, 'id', $id);
        
		//Verifica si la sección es válida
		if ($id=='')
			RDfunctions::error_404();
		
		//Comprueba si la sección es existente
		$section = new RDSection($id);
		if ($section->isNew())
			RDFunctions::error_404();
	}

	include ('header.php');

	include_once RMCPATH.'/class/form.class.php';
    define('NO_CUSTOM_CODES', 1);
	$rmc_config = RMFunctions::configs();
	$editor = new RMFormEditor('','content','100%','300px',$edit ? $section->getVar('content', $rmc_config['editor_type']=='tiny' ? 's' : 'e') : '', '', false);
    if ($rmc_config['editor_type']=='tiny'){
        $tiny = TinyEditor::getInstance();
        $tiny->configuration['content_css'] .= ','.XOOPS_URL.'/modules/docs/css/figures.css';
        $tiny->add_config('theme_advanced_buttons1', 'rd_refs');
        $tiny->add_config('theme_advanced_buttons1', 'rd_figures');
        $tiny->add_config('theme_advanced_buttons1', 'rd_toc');
    }

	// Arbol de Secciones
	$sections = array();
	RDFunctions::sections_tree_index(0, 0, $res, '', '', false, $sections, false);
    
    // Breadcrumb
    RDFunctions::breadcrumb();
    $res_edit = RDFunctions::url().($xoopsModuleConfig['permalinks'] ? '/list/'.$res->id().'/' : '?action=list&id='.$res->id());
    RMBreadCrumb::get()->add_crumb($res->getVar('title'), $res_edit);
    if ($edit)
        RMBreadCrumb::get()->add_crumb(sprintf(__('Editing "%s"','docs'), $section->getVar('title')));
    else
        RMBreadCrumb::get()->add_crumb(__('Create new section', 'docs'));
    
    RMTemplate::get()->add_jquery(true);
    RMTemplate::get()->add_style('forms.css', 'docs');
    RMTemplate::get()->add_script('scripts.php?file=metas.js', 'docs');
    include RMEvents::get()->run_event('docs.template.formsections.front', RMTemplate::get()->get_template('docs-section-form.php','module','docs'));

	include ('footer.php');

}

/**
* @desc Almacena toda la información referente a la sección
**/
function saveSection($edit=0,$ret=0){
	global $xoopsUser, $xoopsModuleConfig;

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	//Verifica si se proporcionó una publicación para la sección
	if ($res<=0){
		redirect_header(RDURL, 1, __('Operation not allowed!','docs'));
		die();
	}
	
	//Verifica si la publicación existe
	$res= new RDResource($res);
	if ($res->isNew()){
		redirect_header(RDURL, 1, __('Operation not allowed!','docs'));
		die();
	}
	
	
	//Verificamos si es una publicación aprobada
	if (!$res->getVar('approved')){
		redirect_header(RDURL, 2, __('This Document has not been approved yet!','docs'));
		die();
	}

	// TODO: Crear el link correcto de retorno
    if ($xoopsModuleConfig['permalinks']) {
	    $retlink = RDFunctions::url().'/list/'.$res->getVar('nameid').'/';
    } else {
        $retlink = RDFunctions::url().'?page=edit&action=list&res='.$res->id();
	}
    	
	//Verificamos si el usuario tiene permisos de edicion
	if (!$xoopsUser->uid()==$res->getVar('owner') && 
		!$res->isEditor($xoopsUser->uid()) &&
		!$xoopsUser->isAdmin()){
		redirect_header(RDURL, 2, __('You can not edit this content!','docs'));
		die();
	}
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();

	if ($edit){
		//Verifica si la sección es válida
		if ($id==''){
			redirect_header($retlink, 1, __('Specified section is not valid!','docs'));
			die();
		}
		
		//Comprueba si la sección es existente
		$sec = new RDSection($id);
		if ($sec->isNew()){
			redirect_header($retlink, 1, __('Specified section does not exists!','docs'));
			die();
		}

		//Comprueba que el título de la sección no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('mod_docs_sections')." WHERE title='$title' AND id_res='$res' AND id_sec<>".$sec->id();
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirect_header($sec->editlink(), 1, __('Already exists another section with same title!','docs'));	
			die();
		}
		
		/**
		* Comprobamos si debemos almacenar las ediciones en la
		* tabla temporal o directamente en la tabla de secciones
		*/
		if (!$res->getVar('editor_approve') && !$xoopsUser->isAdmin()){
			$sec = new RDEdit(null, $id_sec);
		}
		
	}else{
	

		//Comprueba que el título de la sección no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('mod_docs_sections')." WHERE title='$title' AND id_res='".$res->id()."'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirect_header(ah_make_link('publish/'.$res->nameId().'/'),1,_MS_AH_ERRTITLE);	
			die();
		}
		$sec = new RDSection();
	}

	//Genera $nameid Nombre identificador
	if ($title<>$sec->getVar('title')){	
		$found=false; 
		$i = 0;
		do{
    			$nameid = TextCleaner::getInstance()->sweetstring($title).($found ? $i : '');
        		$sql = "SELECT COUNT(*) FROM ".$db->prefix('mod_docs_sections'). " WHERE nameid = '$nameid'";
        		list ($num) =$db->fetchRow($db->queryF($sql));
        		if ($num>0){
        			$found =true;
        		    $i++;
        		}else{
        			$found=false;
        		}
        	
    		}while ($found==true);
	}
	
	if (!$res->getVar('editor_approve') && !$xoopsUser->isAdmin() && !($res->getVar('owner')==$xoopsUser->uid())){
        $sec->setVar('id_sec',$id);
    }
    
	$sec->setVar('title', $title);
	$sec->setVar('content',$content);
	$sec->setVar('order', $order);
	$sec->setVar('id_res', $res->id());
	isset($nameid) ? $sec->setVar('nameid', $nameid) : '' ;
	$sec->setVar('parent', $parent);
	$sec->setVar('uid', $xoopsUser->uid());
	$sec->setVar('uname', $xoopsUser->uname());
	
	if ($edit){
		$sec->setVar('modified', time());
	}else{
		$sec->setVar('created', time());
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
	
	
	if (!$sec->save()){
        
		redirect_header($sec->editlink(), 3, __('Section could not be saved!','docs'));
        
	}else{
        
        if($edit)
            $sec = new RDSection($sec->getVar('id_sec'));
        
		if ($return==1){
		    redirect_header($sec->permalink(),1, __('Database updated successfully!','docs'));
        } elseif ($return==2){
            redirect_header($sec->editlink(),1, __('Database updated successfully!','docs'));
		}else{
		    redirect_header($retlink, 1, __('Database updated successfully!','docs'));
		}
	}

}

/**
* @desc Modifica el orden de las secciones
**/
function changeOrderSections(){
	global $xoopsSecurity, $xoopsModuleConfig;
    
	$orders = rmc_server_var($_POST, 'orders', array());
	$id = rmc_server_var($_POST, 'id', 0);
    
    if ($xoopsModuleConfig['permalinks'])
        $url_ret = RDfunctions::url().'/list/'.$id.'/';
    else
        $url_ret = RDFunctions::url().'?page=edit&action=list&id='.$id;
	
	if (!$xoopsSecurity->check()){
		redirect_header($url_ret, 0, __('Session token expired!','docs'));
		die();
	}	

	if (!is_array($orders) || empty($orders)){
		redirect_header($url_ret, 1, __('Sorry, the data provided contains some errors!','docs'),1);
		die();
	}
	
	$errors='';
	foreach ($orders as $k=>$v){
		
        if($k<=0) continue;
        
		//Comprueba si la sección es existente
		$sec = new RDSection($k);
		if ($sec->isNew()) continue;		

		$sec->setVar('order', $v);
        
		if (!$sec->save()){
			$errors.=sprintf(__('Order could not be saved for section %s','docs'), $sec->getVar('title')).'<br />';		
		}
        
	}

	if ($errors!=''){
		redirect_header($url_ret, 1, __('Errors ocurred while trying to update orders').'<br />'.$errors);
	}else{
		redirect_header($url_ret, 0, __('Sections updated successfully!','docs'));
	}

}

$action = rmc_server_var($_POST, 'action', isset($action) ? $action : '');

switch ($action){
	case 'new':
		formSection();
		break;	
	case 'edit':
		formSection(1);
		break;
	case 'save':
		saveSection();
		break;
	case 'saveedit':
		saveSection(1,0);
		break;
	case 'saveret':
		saveSection(0,1);
		break;
	case 'saveretedit':
		saveSection(1, 1);
		break;
	case 'changeorder':
		changeOrderSections();		
		break;
	default:
		showSection();
		break;
}
