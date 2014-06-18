<?php
// $Id: hpage.php 821 2011-12-08 23:46:19Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION', 'homepage');
include 'header.php';

function rd_show_page(){

    RMTemplate::get()->assign('xoops_pagetitle', __('Home Page','docs'));
    xoops_cp_header();

    include_once RMCPATH.'/class/form.class.php';
    $content = @file_get_contents(XOOPS_CACHE_PATH.'/rd_homepage.html');
    $content = TextCleaner::getInstance()->to_display($content);
    $editor = new RMFormEditor('', 'homepage', '100%', '450px', $content);
    $rmc_config = RMFunctions::configs();
    if ($rmc_config['editor_type']=='tiny'){
        $tiny = TinyEditor::getInstance();
        $tiny->add_config('theme_advanced_buttons1', 'res_index');
    }
    
    include RMEvents::get()->run_event('docs.get.homepage.template', RMTemplate::get()->get_template('admin/rd_homepage.php', 'module', 'docs'));
    
    xoops_cp_footer();
}

function rd_save_page(){
    
    $page = rmc_server_var($_POST, 'homepage', '');
    
    if (file_put_contents(XOOPS_CACHE_PATH.'/rd_homepage.html', $page)){
        redirectMsg('hpage.php', __('Page saved successfully!','docs'), 0);
    } else {
        redirectMsg('hpage.php', __('Page could not be saved!','docs'), 1);
    }

}

$action=isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action){
    case 'save':
        rd_save_page();
        break;
    default:
        rd_show_page();
        break;
}
