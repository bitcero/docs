<?php
// $Id: header.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require  dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

$mc = &$xoopsModuleConfig;
$db = XoopsDatabaseFactory::getDatabaseConnection();
