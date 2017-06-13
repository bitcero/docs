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
	global $xoopsModuleConfig, $xoopsUser, $xoopsTpl, $xoopsConfig, $global, $id;
    
	include ('header.php');

	if ( $id <= 0 ){
		$id = RMHttpRequest::get('id', 'integer', 0);
	}
    
	//Verificamos si existen permisos para crear un nuevo recurso
	if (!$xoopsModuleConfig['createres']){
		redirect_header(RDFunctions::url(), 1, __('The creation of new Documents has been disabled by administrator.','docs'));
		die();
	}

	//Verificamos si usuario tiene permisos de crear nuevo recurso
	if (!RDFunctions::new_resource_allowed(($xoopsUser ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS)))){
		redirect_header(RDFunctions::url(), 1, __('You can not create Documents.','docs'));
		die();
	}

	if ( $id > 0 ){
		$res=new RDResource($id);
	} else {
		$res=new RDResource();
	}

	if( !$res->isNew() && $xoopsUser && $xoopsUser->uid() != $res->owner){
		RMUris::redirect_with_message(
			__('You don\'t have permission to edit this document', 'docs'),
			RDURL, RMMSG_ERROR
		);
	}
	
    $xoopsTpl->assign('xoops_pagetitle', __('Create Document','docs'));

	$form=new RMForm(__('Create Document','docs'),'frmres', RMUris::current_url());
	$form->setExtra("enctype='multipart/form-data'");
	$form->addElement(new RMFormText(__('Document title','docs'),'title',50,150, $res->isNew() ? '' : $res->title),true);
	$form->addElement(new RMFormEditor(__('Description','docs'),'desc',5, '250px', $res->isNew() ? '' : $res->getVar('description', 'e')),true);

	// Image
	if ( RMFunctions::plugin_installed( 'advform' ) )
		$form->addElement( new RMFormImageUrl( __('Featured image', 'docs' ), 'image', $res->isNew() ? '' : $res->image ) );
	else
		$form->addElement( new RMFormText( __('Featured image', 'docs' ), 'image', 50, 255, $res->isNew() ? '' : $res->image ) );

	// Licenses
	$select = new RMFormSelect(__('License', 'docs'), 'license', 0, $res->isNew() ? null : array($res->getVar('license')));
	$select->addOption('', __('No license', 'docs'));
	$licenses = RDFunctions::get_licenses();
	foreach( $licenses  as $license ){
		$license = trim($license);
		if ( '---books' == $license ){
			$select->addGroup(__('Licenses for Books', 'docs'), 'books');
		} elseif( '---others' == $license ){
			$select->addGroup(__('Other Licenses', 'docs'), 'others');
		} else {
			$select->addOption( $license, $license );
		}
	}
	$form->addElement($select);
	unset($license, $licenses);
	
	//editores de la publicación	
	$form->addElement(new RMFormUser(__('Editors','docs'),'editors',1, $res->isNew() ? ($xoopsUser ? array($xoopsUser->uid()) : array()) : $res->editors,30));

	//Grupos con permiso de acceso
	$form->addElement(new RMFormGroups(__('Groups that can read Document','docs'),'groups',1,1,1,$res->isNew() ? array(1,2) : $res->groups),true);
	$form->addElement(new RMFormYesno(__('Quick index','docs'),'quick', $res->isNew() ? 1 : $res->quick));
    $form->addElement(new RMFormYesno(__('Show index to restricted users','docs'),'showindex', $res->isNew() ? 0 : $res->show_index));
    $form->addElement(new RMFormYesno(__('Show content in a single page','docs'),'single', $res->isNew() ? 0 : $res->single));

	$form->addElement(new RMFormLabel(__('Approved','docs'),$xoopsModuleConfig['approved'] ? __('Inmediatly','docs') : __('Wait for approval','docs')));

	$buttons =new RMFormButtonGroup();
	$buttons->addButton('sbt', $res->isNew() ? __('Publish Document', 'docs') : __('Save Changes', 'docs'), 'submit');
	$buttons->addButton('cancel',_CANCEL,'button', 'onclick="history.go(-1);"');

	$form->addElement($buttons);
	$form->addElement(new RMFormHidden('action',$res->isNew() ? 'save' : 'update'));
	if ( !$res->isNew() ){
		$form->addElement(new RMFormHidden('id',$res->id()));
	}

	$form->display();
    
    RMTemplate::get()->add_style('docs.min.css', 'docs');
    
	include ('footer.php');


}

/**
* @desc Almacena información perteneciente a una publicación
**/
function savePublish( $edit = 0 ){
	global $xoopsSecurity, $xoopsModuleConfig, $xoopsUser, $xoopsConfig;
    
    $config_handler =& xoops_gethandler('config');
    $xconfig = $config_handler->getConfigsByCat(XOOPS_CONF_MAILER);

	foreach ($_POST as $k=>$v){
		$$k=$v;
	}
    
    if ($xoopsModuleConfig['permalinks'])
        $purl = RDFunctions::url().( $edit ? '/edit-book/' . $id . '/' : '/publish/');
    else
        $purl = RDFunctions::url().'?page=publish&action=publish' . ($edit ? '&id=' . $id : '');

	if (!$xoopsSecurity->check()){
		redirect_header($purl, 1, __('Session token expired!','docs'));
		die();
	}

	$db = XoopsDatabaseFactory::getDatabaseConnection();

	if ( $edit ){

		if ( $id <= 0 ){
			RMUris::redirect_with_message(
				__('Document not valid!', 'docs'),
				RDURL, RMMSG_ERROR
			);
		}

		$res = new RDResource($id);
		if ( $res->isNew() ){
			RMUris::redirect_with_message(
				__('Document not found!', 'docs'),
				RDURL, RMMSG_ERROR
			);
		}

		// Check that the document belongs to user
		if( $res->owner != $xoopsUser->uid() ){
			RMUris::redirect_with_message(
				__('You don\'t have permission to edit this document', 'docs'),
				RDURL, RMMSG_ERROR
			);
		}

		//Comprueba que el título de publicación no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('mod_docs_resources')." WHERE owner = " . $xoopsUser->uid() . " AND title='$title' AND id_res != $id";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			RMUris::redirect_with_message(
				__('Already exists a Document with same name!','docs'),
				$purl, RMMSG_ERROR
			);
			die();
		}

	} else {

		//Comprueba que el título de publicación no exista
		$sql="SELECT COUNT(*) FROM ".$db->prefix('mod_docs_resources')." WHERE owner = " . $xoopsUser->uid() . " AND title='$title' ";
		list($num)=$db->fetchRow($db->queryF($sql));
		if ($num>0){
			RMUris::redirect_with_message(
				__('Already exists a Document with same name!','docs'),
				$purl, RMMSG_ERROR
			);
			die();
		}

		$res = new RDResource();
	}

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
	$res->setVar('image', $image);
	$res->setVar('created', time());
    $res->setVar('modified', time());
	$res->setVar('editors', $editors);
	$res->setVar('license', $license);
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
	case 'update':
		savePublish(1);
		break;
	default: 
		formPublish();

}
