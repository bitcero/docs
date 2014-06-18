<?php
// $Id: scripts.php 821 2011-12-08 23:46:19Z i.bitcero $
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$wfile = isset($_GET['file']) ? $_GET['file'] : '';
if ($wfile=='') exit();

$path = dirname(__FILE__);
if (!file_exists($path.'/'.$wfile)) exit();

$path .= '/'.$wfile;

include_once '../../../../mainfile.php';
include_once '../../../rmcommon/loader.php';

global $xoopsLogger;
$xoopsLogger->renderingEnabled = false;
error_reporting(0);
$xoopsLogger->activated = false;

switch($wfile){
    
    default:
        include $path;
        break;
    
}
