<?php
// $Id: header.php 821 2011-12-08 23:46:19Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include(XOOPS_ROOT_PATH.'/header.php');

$mc =& $xoopsModuleConfig;

//include_once 'include/functions.php';
define('RDURL', RDFunctions::url());
define('RDPATH', XOOPS_ROOT_PATH.'/modules/docs');
$xoopsTpl->assign('rdurl', RDURL);

RMTemplate::get()->add_inline_script('var docsUrl = "' . RDURL . '";');

$xoopsTpl->assign('docs_custom_css', $mc['custom_css']);
