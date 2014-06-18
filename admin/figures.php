<?php
// $Id: figures.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION', 'figures');
include 'header.php';

/**
* @desc Muestra todas las figuras existentes
**/
function rd_show_figures(){
	global $xoopsModule, $xoopsSecurity;

	$id_res=isset($_REQUEST['res']) ? intval($_REQUEST['res']) : 0;
	$search=isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
    
    if($id_res<=0){
        redirectMsg('resources.php', __('First select a Document to view the figures.','docs'), 1);
    }
    
    $res = new RDResource($id_res);
    if($res->isNew()){
        redirectMsg('resources.php', __('Specified Document does not exists!','docs'), 1);
    }
    
	$search = rmc_server_var($_GET, 'search', '');
    
    //Separamos frase en palabras
    $words=explode(" ",$search);
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    //Navegador de páginas
    $sql="SELECT COUNT(*) FROM ".$db->prefix('mod_docs_figures').($id_res ? " WHERE id_res='$id_res'" : '');
    $sql1='';
    if ($search){
        foreach ($words as $k){
            //verifica que palabra sea mayor a 2 letras    
            if (strlen($k)<=2) continue;
            $sql1.=($sql1=='' ? ($id_res ? " AND " : " WHERE ") : " OR ")." (title LIKE '%$k%' OR desc LIKE '%$k%')";    

        }    
    }
    
    list($num) = $db->fetchRow($db->query($sql.$sql1));
    $page = rmc_server_var($_REQUEST, 'page', 1);
    $limit = 15;

    $tpages = ceil($num/$limit);
    $page = $page > $tpages ? $tpages : $page; 

    $start = $num<=0 ? 0 : ($page - 1) * $limit;
    
    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('figures.php?res='.$id_res.'&amp;page={PAGE_NUM}');
    
    $sql = str_replace("COUNT(*)","*", $sql);
    $sql2=" LIMIT $start,$limit";
    $result=$db->queryF($sql.$sql1.$sql2);
    $figures = array();
    while ($rows=$db->fetchArray($result)){
        $fig= new RDFigure();
        $fig->assignVars($rows);
    
        $figures[] = array(
            'id'=>$fig->id(),
            'title'=>$fig->getVar('title'),
            'text'=> substr(strip_tags($fig->getVar('desc')), 0, 150).(strlen($fig->getVar('desc')) <= 150 ? '' : '...')
        );
    
    }
    
    // Event
    $figures = RMEvents::get()->run_event('docs.loading.figures', $figures, $res);
    
    RMTemplate::get()->add_style('admin.css', 'docs');
    RMTemplate::get()->add_style('jquery.css', 'rmcommon');
    RMTemplate::get()->add_script('../include/js/admin.js');
    RMTemplate::get()->assign('xoops_pagetitle', sprintf(__('Figures in %s', 'docs'), $res->getVar('title')));
    RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_head('<script type="text/javascript">
    var rd_select_message = "'.__('You have not selected any figure!','docs').'";
    var rd_message = "'.__('Do you really wish to delete selected figures?','docs').'";
    </script>');
    xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".__('Figures','docs'));
    xoops_cp_header();
    
    include RMEvents::get()->run_event('docs.admin.template.figures', RMTemplate::get()->get_template('admin/rd_figures.php', 'module', 'docs'));
	 
	xoops_cp_footer();

}

/**
* @desc Permite la edición de figuras
**/
function rd_figures_form($edit=0){
	global $xoopsModule,$xoopsConfig;
    
    
	xoops_cp_location("<a href='./'>".$xoopsModule->name()."</a> &raquo; ".$edit ? __('Edit Figure','docs') : __('Create Figure'));
	xoops_cp_header();

	$id = rmc_server_var($_GET, 'id', 0);
	$id_res = rmc_server_var($_GET, 'res', 0);
    
    if($id_res<=0){
        redirectMsg('resources.php', __('First select a Document to manage the figures.','docs'), 1);
    }
    
    $res = new RDResource($id_res);
    if($res->isNew()){
        redirectMsg('resources.php', __('Specified Document does not exists!','docs'), 1);
    }
    
    if($edit){
        
        if($id<=0){
            redirectMsg('figures.php?res='.$id_res, __('You have not specified a figure to edit!','docs'));
            break;
        }
        
        $fig = new RDFigure($id);
        if($fig->isNew()){
            redirectMsg('figures.php?res='.$id_res, __('Specified figure does not exists!','docs'));
            break;
        }
        
    }
	
	$form=new RMForm($edit ? __('Editing Figure','docs') : __('Create Figure','docs'),'frmfig','figures.php');
	
	$form->addElement(new RMFormText(__('Title','docs'),'title',50,255,$edit ? $fig->getVar('title') : ''),true);
    $form->addElement(new RMFormText(__('Description','docs'),'desc',50,255, $edit ? $fig->getVar('desc') : ''),true);
	$form->addElement(new RMFormEditor(__('Content','docs'),'figure','100%','300px',$edit ? $fig->getVar('content','e') : ''),true);

	$form->addElement(new RMFormText(__('Attributes','docs'),'attrs',50,150, $edit ? $fig->getVar('attrs') : ''));

	$buttons=new RMFormButtonGroup();

	$buttons->addButton('sbt', $edit ? __('Save Changes','docs') : __('Save Figure','docs'),'submit');
	$buttons->addButton('cancel', __('Cancel','docs'),'button','onclick="window.location=\'figures.php?res='.$id_res.'\';"');	

	$form->addElement($buttons);

	$form->addElement(new RMFormHidden('action',$edit ? 'saveedit' : 'save'));
	if ($edit) $form->addElement(new RMFormHidden('id',$id));
	$form->addElement(new RMFormHidden('res',$id_res));
	

	$form->display();

	xoops_cp_footer();
}

/**
* @desc Almacena información perteneciente a la figura
**/
function rd_save_figures($edit = 0){
    global $xoopsSecurity;
    
	foreach ($_POST as $k=>$v){
		$$k=$v;
	}

	$ruta="?res=$res";

	if (!$xoopsSecurity->validateToken()){
        redirectMsg('./figures.php'.$ruta, __('Session token expired!','docs'),1);
        die();
    }

	if ($edit){
        //Verifica que referencia sea válida
        if ($id<=0){
            redirectMsg('./figures.php'.$ruta, __('Figure id not specified!','docs'),1);
            die();
        }

        //Verifica que referencia exista
        $fig= new RDFigure($id);
        if ($fig->isNew()){
            redirectMsg('./figures.php'.$ruta, __('Specified figure does not exists!','docs'),1);
            die();
        }
    } else {
        
        $fig = new RDFigure();
        
    }

	$fig->setVar('title', $title);
	$fig->setVar('desc', $desc);
	$fig->setVar('content', $figure);
    $fig->setVar('id_res', $res);
    $fig->setVar('attrs', $attrs);

	if ($fig->save()){
        redirectMsg('./figures.php?action=locate&res='.$res.'&id='.$fig->id(), __('Figure saved successfully!','docs'),0);
        die();
    }else{
        redirectMsg('./figures.php?action=locate&id='.$fig->id().'&res='.$res, __('Figure could not be saved!','docs').'<br />'.$fig->errors(),1);
        die();
    }
    
}

/**
* @desc Elimina figuras
**/
function rd_delete_figures(){
	global $xoopsModule, $xoopsSecurity;

	$ids = rmc_server_var($_POST, 'ids', array());
	$page = rmc_server_var($_POST, 'page', 1);
	$id_res = rmc_server_var($_POST, 'res', 0);
	$search = rmc_server_var($_POST, 'search', '');

	$ruta="?res=$id_res&search=$search&page=$page";

	//Comprueba si se proporciono una referencia 
    if (!is_array($ids)){
        redirectMsg('./figures.php'.$ruta, __('You have not selected any figure!','docs'),1);
        die();    
    }
    
        
    if (!$xoopsSecurity->check()){
        redirectMsg('./figures.php'.$ruta, __('Session token expired!','docs'), 1);
        die();
    }

    $errors='';
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "DELETE FROM ".$db->prefix("mod_docs_figures")." WHERE id_fig IN(".implode(",",$ids).")";
    if(!$db->queryF($sql)){
        redirectMsg($ruta, __('Figures could not be deleted!','docs').'<br />'.$db->error(), 1);
    } else {
        redirectMsg($ruta, __('Figures deleted successfully!','docs'), 0);
    }

}

function rd_locate_figure(){
    
    $res = rmc_server_var($_GET, 'res', 0);
    $id = rmc_server_var($_GET, 'id', 0);
    
    if ($res<=0 || $id<=0){
        header('location: resources.php');
        die();
    }
    
    $limit = 15;
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = "SELECT id_fig FROM ".$db->prefix("mod_docs_figures")." WHERE id_res='$res'";
    $result = $db->query($sql);
    if ($db->getRowsNum($result)<=$limit){
        header('location: figures.php?res='.$res);
        die();
    }
    
    $counter = 0;
    while(list($fig) = $db->fetchRow($result)){
        $counter++;
        if ($fig==$id){
            $page = ceil($counter/$limit);
            break;
        }
    }
    
    header('location: figures.php?res='.$res.'&page='.$page.'#figure'.$id);
    die();
    
}


$action = rmc_server_var($_REQUEST, 'action', '');

switch ($action){
    case 'new':
        rd_figures_form();
        die();
	case 'edit':
		rd_figures_form(1);
	    break;
	case 'save':
		rd_save_figures();
	    break;
    case 'saveedit':
        rd_save_figures(1);
        break;
    case 'locate':
        rd_locate_figure();
        break;
	case 'delete':
		rd_delete_figures();
	    break;
	default:
		rd_show_figures();
        break;
}
