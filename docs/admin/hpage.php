<?php
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define('RMCLOCATION', 'homepage');
require __DIR__ . '/header.php';

function rd_show_page()
{
    global $cuSettings;

    RMTemplate::getInstance()->assign('xoops_pagetitle', __('Home Page', 'docs'));
    xoops_cp_header();

    require_once RMCPATH . '/class/form.class.php';
    $content = @file_get_contents(XOOPS_CACHE_PATH . '/docs-homepage.html');
    $editor = new RMFormEditor('', 'homepage', '100%', '450px', 'tiny' === $cuSettings->editor_type ? TextCleaner::getInstance()->to_display($content) : TextCleaner::getInstance()->specialchars($content));
    $rmc_config = RMSettings::cu_settings();
    if ('tiny' === $rmc_config->editor_type) {
        $tiny = TinyEditor::getInstance();
        $tiny->add_config('theme_advanced_buttons1', 'res_index');
    }

    include RMEvents::get()->run_event('docs.get.homepage.template', RMTemplate::getInstance()->get_template('admin/docs-homepage.php', 'module', 'docs'));

    xoops_cp_footer();
}

function rd_save_page()
{
    $page = rmc_server_var($_POST, 'homepage', '');

    if (file_put_contents(XOOPS_CACHE_PATH . '/docs-homepage.html', $page)) {
        redirectMsg('hpage.php', __('Page saved successfully!', 'docs'), 0);
    } else {
        redirectMsg('hpage.php', __('Page could not be saved!', 'docs'), 1);
    }
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action) {
    case 'save':
        rd_save_page();
        break;
    default:
        rd_show_page();
        break;
}
