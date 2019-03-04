<?php
// $Id: menu.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('docs');

if (RMCLOCATION == 'sections') {
    $res = RMHttpRequest::request('id', 'integer', 0);
} else {
    $res = RMHttpRequest::request('res', 'integer', 0);
}

//Inicio
$adminmenu[0]['title'] = __('Dashboard', 'docs');
$adminmenu[0]['link'] = 'admin/index.php';
$adminmenu[0]['icon'] = '../images/dashboard.png';
$adminmenu[0]['location'] = 'dashboard';

// Home page
$adminmenu[1]['title'] = __('Home Page', 'docs');
$adminmenu[1]['link'] = 'admin/hpage.php';
$adminmenu[1]['icon'] = '../images/homepage.png';
$adminmenu[1]['location'] = 'homepage';

//Publicaciones
$adminmenu[2]['title'] = __('Documents', 'docs');
$adminmenu[2]['link'] = 'admin/resources.php';
$adminmenu[2]['icon'] = '../images/book.png';
$adminmenu[2]['location'] = 'resources';
$adminmenu[2]['options'] = [
    ['title' => __('All Documents', 'docs'), 'link' => 'admin/resources.php', 'selected' => 'resources', 'icon' => 'fa fa-files-o'],
    ['title' => __('New Document', 'docs'), 'link' => 'admin/resources.php?action=new', 'selected' => 'newresource', 'icon' => 'fa fa-plus'],
    ['title' => __('Drafts', 'docs'), 'link' => 'admin/resources.php?action=drafts', 'selected' => 'drafts', 'icon' => 'fa fa-eraser'],
];

//Secciones
$adminmenu[3]['title'] = __('Sections', 'docs');
$adminmenu[3]['link'] = 'admin/sections.php?id=' . $res;
$adminmenu[3]['icon'] = '../images/section.png';
$adminmenu[3]['location'] = 'sections';
$adminmenu[3]['options'] = [
    ['title' => __('All sections', 'docs'), 'link' => 'admin/sections.php?id=' . $res, 'selected' => 'sections', 'icon' => 'fa fa-puzzle-piece'],
    ['title' => __('New section', 'docs'), 'link' => 'admin/sections.php?action=new&amp;id=' . $res, 'selected' => 'newsection', 'icon' => 'fa fa-plus'],
];

//Referencias
$adminmenu[4]['title'] = __('Notes', 'docs');
$adminmenu[4]['link'] = 'admin/notes.php?res=' . $res;
$adminmenu[4]['icon'] = '../images/notes.png';
$adminmenu[4]['location'] = 'notes';

//Figuras
$adminmenu[5]['title'] = __('Figures', 'docs');
$adminmenu[5]['link'] = 'admin/figures.php?res=' . $res;
$adminmenu[5]['icon'] = '../images/figures.png';
$adminmenu[5]['location'] = 'figures';
$adminmenu[5]['options'] = [
    ['title' => __('All figures', 'docs'), 'link' => 'admin/figures.php?res=' . $res, 'selected' => 'figures'],
    ['title' => __('New figure', 'docs'), 'link' => 'admin/figures.php?action=new&amp;res=' . $res, 'selected' => 'newfigure'],
];

//Ediciones
$adminmenu[6]['title'] = __('Waiting', 'docs');
$adminmenu[6]['link'] = './admin/edits.php';
$adminmenu[6]['icon'] = '../images/waiting.png';
$adminmenu[6]['location'] = 'waiting';
