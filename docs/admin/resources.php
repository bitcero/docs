<?php
// $Id: resources.php 911 2012-01-06 08:46:39Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION', 'resources');
include 'header.php';

/**
* @desc Muestra todas las publicaciones existentes
**/
function show_resources( $public = 1 ){
	global $xoopsModule,$xoopsConfig, $xoopsSecurity;
	
    $query = rmc_server_var($_REQUEST,'query','');
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
	//Navegador de páginas
	$sql = "SELECT COUNT(*) FROM ".$db->prefix('mod_docs_resources');
    if($query)
        $sql .= " WHERE title LIKE '%query%'";
        
	list($num)=$db->fetchRow($db->queryF($sql));
	
	$page = rmc_server_var($_REQUEST, 'page', 1);
	$limit = 15;

	$tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('resources.php?page={PAGE_NUM}');

	//Fin navegador de páginas
	
	$sql="SELECT * FROM ".$db->prefix('mod_docs_resources');
    $sql .= $query!='' ? " WHERE title LIKE '%$query%'" : '';

    if ( $public == 0 )
        $sql .= ($query != '' ? ' AND ' : ' WHERE ') . ' public=0 ';

    $sql .= " ORDER BY `created` DESC LIMIT $start,$limit";

	$result=$db->queryF($sql);
	$resources = array();
	
	while ($rows=$db->fetchArray($result)){
		$res = new RDResource();
		$res->assignVars($rows);
		$resources[] = array(
            'id'=>$res->id(),
            'title'=>$res->getVar('title'),
			'created'=>formatTimestamp($res->getVar('created'), 'm'),
            'public'=>$res->getVar('public'),
			'quick'=>$res->getVar('quick'),
            'approvededit'=>$res->getVar('editor_approve'),
            'featured'=>$res->getVar('featured'),
			'approved'=>$res->getVar('approved'),
            'owname'=>$res->getVar('owname'),
            'owner'=>$res->getVar('owner'),
            'description'=>$res->getVar('description'),
            'sections'=>$res->sections_count(),
            'notes'=>$res->notes_count(),
            'figures'=>$res->figures_count()
        );
	}


    RMTemplate::get()->add_style('admin.css', 'docs');
    RMTemplate::get()->assign('xoops_pagetitle', __('Documents', 'docs'));
    RMTemplate::get()->add_script('jquery.checkboxes.js', 'rmcommon', array('footer' => 1, 'directory' => 'include'));
    RMTemplate::get()->add_script('admin.js', 'docs');
    
    RMTemplate::get()->add_head_script('var rd_message = "'.__('Do you really wish to delete selected Documents?','docs').'";
    var rd_select_message = "'.__('You must select an element before to do this action!','docs').'";');
    
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Documents','docs'));

	xoops_cp_header();
	
	include RMTemplate::get()->get_template('admin/rd_resources.php', 'module', 'docs'); 
	
	xoops_cp_footer();

}

/**
* Formulario para crear publicaciones
**/
function rd_show_form($edit=0){
	global $xoopsModule,$xoopsConfig,$xoopsModuleConfig;

    RDFunctions::toolbar();
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".($edit ? __('Editing Document','docs') : __('Create Document','docs')));
	xoops_cp_header();

	$id= rmc_server_var($_GET,'id', 0);
	$page = rmc_server_var($_GET, 'page', 1);

	if ($edit){
		//Comprueba si la publicación es válida
		if ($id<=0){
			redirectMsg('./resources.php?page='.$page, __('You must provide an ID from some Document to edit!', 'docs'),1);
			die();		
		}
		
		//Comprueba si la publicación existe
		$res= new RDResource($id);
		if ($res->isNew()){
			redirectMsg('./resources.php?page='.$page, __('Specified Document does not exists!','docs'),1);
			die();
		}	

	}

	$form = new RMForm($edit ? sprintf(__('Edit Document: %s','docs'), $res->getVar('title')) : __('New Document','docs'),'frmres','resources.php');
	
	$form->addElement(new RMFormText(__('Document title', 'docs'),'title',50,150,$edit ? $res->getVar('title') : ''),true);
	if ($edit) $form->addElement(new RMFormText(__('Document slug', 'docs'),'nameid',50,150,$res->getVar('nameid')));

    //$form->addElement(new RMFormEditor( __('Description', 'docs'),'desc', '100%', '200px', $edit ? $res->getVar('description','e') : ''),true);
    $form->addElement(new RMFormTextArea(__('Description', 'docs'),'desc',5,50,$edit ? $res->getVar('description','e') : ''),true);
	$form->addElement(new RMFormUser(__('Editors','docs'),'editors',1,$edit ? $res->getVar('editors') : '',30));

    $form->addElement( new RMFormImage( __('Featured image', 'docs'), 'image', $edit ? $res->getVar('image', 'e') : '' ) );

	//Propietario de la publicacion
	if ($edit){
		$form->addElement(new RMFormUser(__('Document owner', 'docs'),'owner',0,$edit ? array($res->getVar('owner')) : '',30));
	}
	$form->addElement(new RMFormYesno(__('Approve content and changes by editors', 'docs'),'approvededit',$edit ? $res->getVar('editor_approve') : 0));
	$form->addElement(new RMFormGroups(__('Groups that can see this Document', 'docs'),'groups',1,1,5,$edit ? $res->getVar('groups') : array(1,2)),true);
	$form->addElement(new RMFormYesno(__('Set as public','docs', 'docs'),'public',$edit ? $res->getVar('public') : 0));
	$form->addElement(new RMFormYesNo(__('Quick index', 'docs'),'quick',$edit ? $res->getVar('quick') : 0));

	
	//Mostrar índice a usuarios sin permiso de publicación
	$form->addElement(new RMFormYesno(__('Show index to restricted users','docs'),'showindex',$edit ? $res->getVar('show_index') : 0));
	$form->addElement(new RMFormYesno(__('Featured','docs'),'featured',$edit ? $res->getVar('featured') : 0));
	$form->addElement(new RMFormYesno(__('Approve inmediatly','docs'),'approvedres',$edit ? $res->getVar('approved') : 1));
    $form->addElement(new RMFormYesno(__('Show content in a single page','docs'),'single',$edit ? $res->getVar('single') : 0));
    $form->element('single')->setDescription(__('When you enable this option the Document content will show in a single page.','docs'));

	$buttons =new RMFormButtonGroup();
	$buttons->addButton('sbt',$edit ? __('Update Document','docs') : __('Create Document','docs'),'submit');
	$buttons->addButton('cancel',__('Cancel','docs'),'button', 'onclick="window.location=\'resources.php\';"');

	$form->addElement($buttons);

	$form->addElement(new RMFormHidden('action',$edit ? 'saveedit': 'save' ));
	if ($edit) $form->addElement(new RMFormHidden('id',$id));
	$form->addElement(new RMFormHidden('page',$page));
	$form->addElement(new RMFormHidden('app',$edit ? $res->getVar('approved') : 0));
	$form->display();


	xoops_cp_footer();
}


/**
* @desc Almacena la información de la publicación
**/
function rd_save_resource($edit=0){
	global $xoopsModuleConfig,$xoopsUser, $xoopsSecurity;
	
	$nameid = '';
    $q = '';
	foreach ($_POST as $k=>$v){
		$$k=$v;
        if ($k=='XOOPS_TOKEN_REQUEST' || $k=='action') continue;
        $q .= $q=='' ? "$k=".urlencode($v) : "&$k=".urlencode($v);
	}
    
    if($action=='save')
        $q .= '&amp;action=new';
    else
        $q .= "&amp;action=edit";

	if (!$xoopsSecurity->check()){
		redirectMsg('resources.php?'.$q, __('Session token expired!','docs'), 1);
        die();
	}
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
	if ($edit){
		//Comprueba si la publicación es válida
		if ($id<=0){
			redirectMsg('resources.php', __('You must provide a valid Document ID','docs'), 1);
			die();		
		}
		
		//Comprueba si la publicación existe
		$res= new RDResource($id);
		if ($res->isNew()){
			redirectMsg('resources.php', __('Specified Document does not exists!','docs'), 1);
			die();
		}	

		//Comprueba que el título de publicación no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('mod_docs_resources')." WHERE title='$title' AND id_res<>'".$id."'";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('resources.php?'.$q,__('A Document with same title exists already!','docs'),1);	
			die();
		}

		
	}else{
		//Comprueba que el título de publicación no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('mod_docs_resources')." WHERE title='$title' ";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			redirectMsg('resources.php?'.$q,__('A Document with same title exists already!','docs'),1);    
            die();
		}
		$res = new RDResource();
	}
	
	//Genera $nameid Nombre identificador
	if ($nameid=='' || $res->getVar('title')!=$title){
		$found=false; 
		$i = 0;
		do{
    		$nameid = TextCleaner::getInstance()->sweetstring($title).($found ? $i : '');
        	$sql = "SELECT COUNT(*) FROM ".$db->prefix('mod_docs_resources'). " WHERE nameid = '$nameid'";
        	list ($num) =$db->fetchRow($db->queryF($sql));
        	if ($num>0){
        		$found =true;
        	    $i++;
        	}else{
        		$found=false;
        	}
	    }while ($found==true);

	}
	
	$res->setVar('title', $title);
	$res->setVar('description', $desc);
	$res->isNew() ? $res->setVar('created', time()) : $res->setVar('modified', time());
	$res->setVar('editors', $editors);
	$res->setVar('image', $image);
	$res->setVar('editor_approve', $approvededit);
	$res->setVar('groups', $groups);
	$res->setVar('public', $public);
	$res->setVar('quick', $quick);
	$res->setVar('nameid', $nameid);
	$res->setVar('show_index', $showindex);
	$res->setVar('featured', $featured);
	$res->setVar('approved', $approvedres);
    $res->setVar('single', $single);
	if ($res->isNew()){
		$res->setVar('owner', $xoopsUser->uid());
		$res->setVar('owname', $xoopsUser->uname());
	}elseif ($owner!=$res->getVar('owner')){
		$xuser=new $xoopsUser($owner);
		$res->setVar('owner', $owner);
		$res->setVar('owname', $xuser->uname());
	}

	if (!$res->save()){
        redirectMsg('resources.php?'.$q, __('Document could not be saved!','docs').'<br />'.$res->errors(), 1);
        die();
	}else{
		if (!$res->isNew()){
			
			/**
			* Comprobamos si el recurso no estaba aprovado previamente
			* para enviar la notificación.
			* La notificación solo se envía si el dueño es distinto
			* al administrador actual.
			*/
			if (!$app && $app!=$res->getVar('approved') && $xoopsUser->uid()!=$res->getVar('owner')){
				$errors = RDfunctions::mail_approved($res);
				redirectMsg('./resources.php?page='.$page,$errors,1);				
			}


		}

		redirectMsg('./resources.php?limit='.$limit.'&page='.$page,__('Document saved successfully!','docs'),0);
	}

}

/**
* @desc Elimina publicaciones
**/
function rd_delete_resource(){
	global $xoopsModule,$xoopsSecurity;

	$ids = rmc_server_var($_POST, 'ids', array());
    $page = rmc_server_var($_POST, 'page', 1);

	if (!is_array($ids)){
        redirectMsg("resources.php?page=".$page, __("Select at least one document to delete!",'docs'), 1);
        die();
    }
		
	if (!$xoopsSecurity->check()){
	    redirectMsg('./resources.php?page='.$page, __('Session token expired!','docs'), 1);
		die();
	}
    
    $errors = '';
    foreach($ids as $id){
        
        if ($id<=0){
            $errors .= sprintf(__('"%s" is not a valid Document ID','docs'), $id);
            continue;
        }
        
        $res = new RDResource($id);
        if ($res->isNew()){
            $errors .= sprintf(__('Document with ID "%s" does not exists','docs'), $id);
            continue;
        }
        
        if (!$res->delete()){
            $errors .= sprintf(__('Document "%s" could not be deleted!','docs'), $res->getVar('title')).'<br />'.$res->errors();
        }
        
    }
    
    if ($errors!=''){
        redirectMsg("resources.php?page=$page", __('Errors ocurred while deleting documents','docs').'<br />'.$errors, 1);
    } else {
        redirectMsg("resources.php?page=$page", __('Documents deleted susccessfully!','docs'), 0);
    }

}

/**
* @desc Publica o no publicaciones
**/
function public_resources($pub=0){
	global $xoopsSecurity;
    
	$resources= rmc_server_var($_POST, 'ids', array());
	$page = rmc_server_var($_POST, 'page', 1);	

	if (!$xoopsSecurity->check()){
		redirectMsg('./resources.php?page='.$page, __('Session token expired!','docs'), 1);
		die();
	}	

	//Verifica que se haya proporcionado una publicación
	if (!is_array($resources) || empty($resources)){
		redirectMsg('./resources.php?page='.$page, __('You must select at least a single Document!','docs'),1);
		die();		
	}
	
	$errors='';
	foreach ($resources as $k){
		
		//Comprueba si la publicación es válida
		if ($k<=0){
			$errors.=sprintf(__('Provided Document ID "%s" is not valid!','docs'), $k);
			continue;
		}
		
		//Comprueba si la publicación existe
		$res= new RDResource($k);
		if ($res->isNew()){
			$errors.=sprintf(__('Document with ID "%s" does not exists!','docs'), $k);
			continue;
		}
		
		$res->setVar('public', $pub);
		if (!$res->save()){
			$errors.=sprintf(__('Document "%s" could not be updated!','docs'), $res->getVar('title'));
		}		
	}
	
	if ($errors!=''){
		redirectMsg('./resources.php?page='.$page, __('Errors ocurred while trying to update resources.','docs').'<br />'.$errors,1);
		die();		
	}else{
		redirectMsg('./resources.php?page='.$page, __('Documents updated successfully!','docs'),0);
	}

}

/**
* @descActiva o no la opción de indice rápido
**/
function quick_resources($quick=0){
	global $xoopsSecurity;
    
	$resources= rmc_server_var($_POST, 'ids', array());
    $page = rmc_server_var($_POST, 'page', 1);    

    if (!$xoopsSecurity->check()){
        redirectMsg('./resources.php?page='.$page, __('Session token expired!','docs'), 1);
        die();
    }    

    //Verifica que se haya proporcionado una publicación
    if (!is_array($resources) || empty($resources)){
        redirectMsg('./resources.php?page='.$page, __('You must select at least a single Document!','docs'),1);
        die();        
    }
	
	$errors='';
	foreach ($resources as $k){
		
		//Comprueba si la publicación es válida
        if ($k<=0){
            $errors.=sprintf(__('Provided Document ID "%s" is not valid!','docs'), $k);
            continue;
        }
        
        //Comprueba si la publicación existe
        $res= new RDResource($k);
        if ($res->isNew()){
            $errors.=sprintf(__('Document with ID "%s" does not exists!','docs'), $k);
            continue;
        }
		
		$res->setVar('quick', $quick);
		if (!$res->save()){
			$errors.=sprintf(__('Document "%s" could not be updated!','docs'), $res->getVar('title'));
		}		
	}
	
	if ($errors!=''){
        redirectMsg('./resources.php?page='.$page, __('Errors ocurred while trying to update resources.','docs').'<br />'.$errors,1);
        die();        
    }else{
        redirectMsg('./resources.php?page='.$page, __('Documents updated successfully!','docs'),0);
    }

}

/**
* @desc Permite recomendar una publicación
**/
function recommend_resource($sw){
    
	$id = rmc_server_var($_GET, 'id', 0);
	$page = rmc_server_var($_GET, 'page', 0);
	
	$res = new RDResource($id);
	$res->setVar('featured', $sw);
	if ($res->save()){
		redirectMsg("resources.php?limit='.$limit.'&pag='.$pag", __('Database updated successfully!','docs'), 0);
	} else {
		redirectMsg("resources.php?limit='.$limit.'&pag='.$pag", __('Database coould not be updated!','docs').'<br />'.$res->errors(), 1);
	}
	
}

/**
* @desc Permite aprobar o no una publicación
**/
function approved_resources($app=0){

	global $xoopsSecurity,$xoopsConfig,$xoopsModuleConfig;
    
	$resources = rmc_server_var($_POST, 'ids', array());
	$page = rmc_server_var($_POST, 'page', 1);
	
	if (!$xoopsSecurity->check()){
		redirectMsg('./resources.php?page='.$page, __('Session token expired!','docs'), 1);
		die();
	}
	
	//Verifica que se haya proporcionado una publicación
	if (!is_array($resources) || empty($resources)){
		redirectMsg('./resources.php?page='.$page, __('Select at least a Document!','docs'),1);
		die();		
	}
	
	$errors='';
	foreach ($resources as $k){
		
		//Comprueba si la publicación es válida
		if ($k<=0){
			$errors.=sprintf(__('Document ID "%s" is not valid!','docs'), $k);
			continue;
		}
		
		//Comprueba si la publicación existe
		$res= new RDResource($k);
		if ($res->isNew()){
			$errors.=sprintf(__('Document with ID "%s" does not exists!','docs'), $k);
			continue;
		}
        
		$approved=$res->getVar('approved');
		$res->setVar('approved', $app);
        
		if (!$res->save()){
			$errors.=sprintf(__('Resoource "%s" could not be saved!','docs'), $k);
		}else{
			if ($app && !$approved){
				$errors .= RDFunctions::mail_approved($res)!=true ? __('Notification email could not be sent!','docs').'<br />' : '';
			}
			
		}	
	}
    
	if ($errors!=''){
		redirectMsg('./resources.php?page='.$page,__('Errors ocurred while trying to update resources.').'<br />'.$errors,1);
	}else{
		redirectMsg('./resources.php?page='.$page, __('Documents updated successfully!','docs'),0);
	}

}


$action=isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action){
	case 'new':
		rd_show_form();
	break;
	case 'edit':
		rd_show_form(1);
	break;
	case 'save':
		rd_save_resource();
	break;
	case 'saveedit':
		rd_save_resource(1);
	break;
	case 'delete':
		rd_delete_resource();
	break;
	case 'recommend':
		recommend_resource(1);
	break;
	case 'norecommend':
		recommend_resource(0);
	break;
	case 'public':
		public_resources(1);
	break;
	case 'private':
		public_resources(0);
	break;
	case 'qindex':
		quick_resources(1);
	break;
	case 'noqindex':
		quick_resources(0);
	break;	
	case 'approve':
		approved_resources(1);
	break;
	case 'draft':
		approved_resources(0);
	break;
	default:
		show_resources( $action == 'drafts' ? 0 : 1 );

}
