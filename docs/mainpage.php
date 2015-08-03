<?php
// $Id: mainpage.php 821 2011-12-08 23:46:19Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include ('../../mainfile.php');
include ('header.php');

RDFunctions::breadcrumb();

RMTemplate::get()->add_style('docs.min.css','docs');

$content = @file_get_contents(XOOPS_CACHE_PATH.'/docs-homepage.html');
$content = TextCleaner::getInstance()->to_display($content);
include RMEvents::get()->run_event('docs.get.home.page', RMtemplate::get()->get_template('docs-index.php','module','docs'));

include ('footer.php');
