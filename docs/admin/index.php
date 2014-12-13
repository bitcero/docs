<?php
// $Id: index.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

define( 'RMCLOCATION', 'index' );
include 'header.php';

// Get top resources
$db = XoopsDatabaseFactory::getDatabaseConnection();
$sql = "SELECT * FROM ".$db->prefix("mod_docs_resources")." WHERE public=1 ORDER BY `reads` DESC LIMIT 0, 15";
$result = $db->query($sql);

$top_data = array();

while($row = $db->fetchArray($result)){
    $res = new RDResource();
    $res->assignVars($row);
    
    $top_data['reads'][] = $res->getVar('reads');
    $top_data['names'][] = $res->getVar('title').' ('.$res->getVar('reads').')';
    
}

if ( !empty( $top_data ) )
    array_multisort($top_data['names'], SORT_STRING, $top_data['reads'], SORT_NUMERIC);

$sql = "SELECT * FROM ".$db->prefix("mod_docs_sections")." ORDER BY `comments` DESC LIMIT 0, 15";
$result = $db->query($sql);

$comm_data = array();

while($row = $db->fetchArray($result)){
    $sec = new RDSection();
    $sec->assignVars($row);
    
    $comm_data['comments'][] = $sec->getVar('comments');
    $comm_data['names'][] = $sec->getVar('title').' ('.$sec->getVar('comments').')';
    
}

if ( !empty( $comm_data ) )
    array_multisort($comm_data['names'], SORT_STRING, $comm_data['comments'], SORT_NUMERIC);

// Resume data
list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_docs_resources")));
$resume_data['resources'] = $num;

list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_docs_sections")));
$resume_data['sections'] = $num;

list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_docs_figures")));
$resume_data['figures'] = $num;

list($num) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".$db->prefix("mod_docs_references")));
$resume_data['notes'] = $num;

// No published resoruces
$sql = "SELECT * FROM ".$db->prefix("mod_docs_resources")." WHERE public=0 ORDER BY created DESC LIMIT 0,5";
$result = $db->query($sql);
$nopublished = array();
while($row = $db->fetchArray($result)){
    $res = new RDResource();
    $res->assignVars($row);
    $nopublished[] = array(
        'id' => $res->id(),
        'title' => $res->getVar('title'),
        'created' => $res->getVar('created'),
        'desc' => TextCleaner::getInstance()->truncate($res->getVar('description'), 60)
    );
}

// No published resoruces
$sql = "SELECT * FROM ".$db->prefix("mod_docs_resources")." WHERE approved=0 ORDER BY created DESC LIMIT 0,5";
$result = $db->query($sql);
$noapproved = array();
while($row = $db->fetchArray($result)){
    $res = new RDResource();
    $res->assignVars($row);
    $noapproved[] = array(
        'id' => $res->id(),
        'title' => $res->getVar('title'),
        'created' => $res->getVar('created'),
        'desc' => TextCleaner::getInstance()->truncate($res->getVar('description'), 60)
    );
}

xoops_cp_header();

RMTemplate::get()->add_style('admin.css','docs');
RMTemplate::get()->add_style('dashboard.css','docs');
RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.gcharts.js');
RMTemplate::get()->add_head('<script type="text/javascript">var xoops_url="'.XOOPS_URL.'";</script>');
RMTemplate::get()->add_script('../include/js/dashboard.js');
include RMTemplate::get()->get_template('admin/docs-dashboard.php', 'module', 'docs');

xoops_cp_footer();
