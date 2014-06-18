<?php
// $Id: publish.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

include ('../../mainfile.php');

/**
* @desc Formulario para la creación de una nueva publicación
**/
function formPublish(){
	global $xoopsModuleConfig, $xoopsUser, $xoopsTpl, $xoopsConfig;
    
	include ('header.php');
    
	//Verificamos si existen permisos para crear un nuevo recurso
	if (!$xoopsModuleConfig['createres']){
		redirect_header(RDFunctions::url(), 1, __('The creation of new Documents has been disabled by administrator.','docs'));
		die();
	}

	//Verificamos si usuario tiene permisos de crear nuevo recurso
	$res=new RDResource();
	if (!RDFunctions::new_resource_allowed(($xoopsUser ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS)))){
		redirect_header(RDFunctions::url(), 1, __('You can not create Documents.','docs'));
		die();
	}
	
    $xoopsTpl->assign('xoops_pagetitle', __('Create Document','docs'));

	$form=new RMForm(__('Create Document','docs'),'frmres', RMFunctions::current_url());
	$form->setExtra("enctype='multipart/form-data'");
	$form->addElement(new RMFormText(__('Document title','docs'),'title',50,150),true);
	$form->addElement(new RMFormTextArea(__('Description','docs'),'desc',5,50),true);
	
	//editores de la publicación	
	$form->addElement(new RMFormUser(__('Editors','docs'),'editors',1,$xoopsUser ? array($xoopsUser->uid()) : array(),30));

	//Grupos con permiso de acceso
	$form->addElement(new RMFormGroups(__('Groups that can read Document','docs'),'groups',1,1,1,array(1,2)),true);
	$form->addElement(new RMFormYesno(__('Quick index','docs'),'quick'));
    $form->addElement(new RMFormYesno(__('Show index to restricted users','docs'),'showindex'));
    $form->addElement(new RMFormYesno(__('Show content in a single page','docs'),'single'));

	$form->addElement(new RMFormLabel(__('Approved','docs'),$xoopsModuleConfig['approved'] ? __('Inmediatly','docs') : __('Wait for approval','docs')));

	$buttons =new RMFormButtonGroup();
	$buttons->addButton('sbt',__('Publish Document'),'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="history.go(-1);"');

	$form->addElement($buttons);
	$form->addElement(new RMFormHidden('action','save'));

	$form->display();
    
    RMTemplate::get()->add_style('forms.css', 'docs');
    
	include ('footer.php');


}

/**
* @desc Almacena información perteneciente a una publicación
**/
function savePublish(){
	global $xoopsSecurity, $xoopsModuleConfig, $xoopsUser, $xoopsConfig;
    
    $config_handler =& xoops_gethandler('config');
    $xconfig = $config_handler->getConfigsByCat(XOOPS_CONF_MAILER);

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}
    
    if ($xoopsModuleConfig['permalinks'])
        $purl = RDFunctions::url().'/publish/';
    else
        $purl = RDFunctions::url().'?page=publish&action=publish';

	if (!$xoopsSecurity->check()){
		redirect_header($prul, 1, __('Session token expired!','docs'));
		die();
	}
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
	//Comprueba que el título de publicación no exista
	$sql="SELECT COUNT(*) FROM ".$db->prefix('mod_docs_resources')." WHERE title='$title' ";
	list($num)=$db->fetchRow($db->queryF($sql));
	if ($num>0){
		redirect_header($purl, 1, __('Already exists a Document with same name!','docs'));
		die();
	}

	$res= new RDResource();

	//Genera $nameid Nombre identificador
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

		
	$res->setVar('title', $title);
	$res->setVar('description',$desc);
	$res->setVar('created', time());
    $res->setVar('modified', time());
	$res->setVar('editors', $editors);
	$res->setVar('groups', $groups);
	$res->setVar('public', 1);
	$res->setVar('quick', $quick);
	$res->setVar('nameid', $nameid);
	$res->setVar('owner', $xoopsUser->uid());
	$res->setVar('owname', $xoopsUser->uname());
	$res->setVar('approved', $xoopsModuleConfig['approved']);	
    $res->setVar('single', $single);
	
	if (!$res->save()){
		redirect_header($prul, 1, __('Document could not be created!','docs'));
	}else{
		//Si no se aprobó la publicación enviamos correo al administrador
		if (!$xoopsModuleConfig['approved']){
			$mailer = new RMMailer('text/plain');
            $mailer->add_user($xconfig['from'], $xconfig['fromname'], 'to');
            $mailer->set_subject(__('New Document created at RapidDocs is waiting for approval','rmcommon'));
            
            $mailer->assign('to_name', $xconfig['fromname']);
            $mailer->assign('link_to_resource', XOOPS_URL.'/modules/docs/admin/resources.php?action=edit&id='.$res->id());
            $mailer->template(RMTemplate::get()->get_template('mail/resource_for_approval.php', 'module', 'docs'));
            
            if (!$mailer->send()){
                redirect_header(RDFunctions::url(), 1, __('Your Document has been created, however the email to administrator could no be sent.','docs'));
                die();
            }
            redirect_header(RDFunctions::url(), 1, __('Your Document has been created and is pending for approval. We will sent an email when you can access to it and add content.','docs'));
            die();
			
		}
        
        
        if ($xoopsModuleConfig['permalinks'])
            $purl = RDFunctions::url().'/list/'.$res->id().'/';
        else
            $purl = RDFunctions::url().'?page=edit&action=list&id='.$res->id();
        
		redirect_header($purl,1,__('Document created successfully!','docs'));
		die();
		
	}
}



$action = rmc_server_var($_POST, 'action', isset($action) ? $action : '');

switch ($action){
	case 'save':
		savePublish();
	break;
	default: 
		formPublish();

}
