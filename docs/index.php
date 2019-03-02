<?php
// $Id: index.php 882 2011-12-28 02:08:59Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

require '../../mainfile.php';
define('INCLUDED_INDEX', 1);

/**
* This file redirects all petions directly to his content
*/

$isStandalone = $xoopsModuleConfig['standalone'];

if ($isStandalone) {
    header('X-Frame-Options: GOFORIT');
}

if ($xoopsModuleConfig['permalinks']) {
    $url = RMUris::current_url();
    if (false!==strpos($url, XOOPS_URL.'/modules/docs')) {
        header('location: '.RDFunctions::url());
        die();
    }
    
    // If friendly urls are activated
    $path = str_replace(XOOPS_URL, '', RMUris::current_url());
    $path = str_replace($xoopsModuleConfig['htpath'], '', $path);
    $path = trim($path, '/');
    
    if ($xoopsModuleConfig['subdomain']!='') {
        $path = str_replace(rtrim($xoopsModuleConfig['subdomain'], '/'), '', $path);
        $path = trim($path, '/');
    }
    
    $params = explode("/", $path);
} else {
    
    // If friendly urls are disabled
    $path = parse_url(RMUris::current_url());
    if (isset($path['query'])) {
        parse_str($path['query']);
    }
    
    if (!isset($page) || $page=='') {
        require 'mainpage.php';
        die();
    }
    
    $file = $page.'.php';
    if (!file_exists(XOOPS_ROOT_PATH.'/modules/docs/'.$file)) {
        RDfunctions::error_404();
    }
    
    if (!$xoopsModuleConfig['standalone'] && isset($standalone)) {
        unset($standalone);
    }
    
    include $file;
    
    die();
}

foreach ($params as $i => $p) {
    if ($p=='standalone') {
        $standalone = $params[$i+1];
        $temp = array_slice($params, 0, $i);
        if ($i==count($params)-1) {
            $temp = array_merge($temp, array_slice($params, $i+1));
        }
        $params = $temp;
        break;
    }
}

// Mainpage
if (!isset($params[0]) || $params[0]=='' || $params[0]=='standalone') {
    include 'mainpage.php';
    die();
}

// PDF Book
if ($params[0]=='pdfbook') {
    $id = $params[1];
    $_GET['action'] = 'pdfbook';
    include 'content.php';
    die();
}

// Print Book
if ($params[0]=='printbook') {
    $id = $params[1];
    $_GET['action'] = 'printbook';
    include 'content.php';
    die();
}

// Print Book
if ($params[0]=='pdfsection') {
    $id = $params[1];
    $_GET['action'] = 'pdfsection';
    include 'content.php';
    die();
}

// Print Section
if ($params[0]=='printsection') {
    $id = $params[1];
    $_GET['action'] = 'printsection';
    include 'content.php';
    die();
}

// Edit form
if ($params[0]=='edit') {
    $id = $params[1];
    $res = $params[2];
    $action = 'edit';
    include 'edit.php';
    die();
}

// Publish
if ($params[0]=='publish') {
    $action = 'publish';
    include 'publish.php';
    die();
}

// Book edition
if ('edit-book'==$params[0]) {
    $action = 'publish';
    $id = $params[1];
    include 'publish.php';
    die();
}

// New form
if ($params[0]=='new') {
    $res = $params[1];
    $action = 'new';
    include 'edit.php';
    die();
}

// Sections list
if ($params[0]=='list') {
    $id = $params[1];
    include 'edit.php';
    die();
}

// Explore
if ($params[0]=='explore' || $params[0]=='search') {
    $action = $params[0];
    
    if (isset($params[3])) {
        $page = $params[3];
    }
    
    $by = isset($params[1]) ? $params[1] : '';
    include 'search.php';
    die();
}

/**
 * The user has requested a specific book,
 * then we need to check that the owner exists
 */
$uname = $params[0];
$user = new RMUser($uname);

if ($user->isNew()) {
    /**
     * If the user does not exists, then we send the 404 error
     */
    RDFunctions::error_404(__('The document that you\'ve trying to reach does not exists!', 'docs'));
}

// Section
if (count($params)>=3) {

    /**
     * $params[0] = Owner user name
     * $params[1] = Document slug
     * $params[2] = Section slug
     */

    $res = new RDResource($params[1]);
    if (!$res->isNew()) {
        $id = $params[2];

        $hideIndex = RMHttpRequest::get('hideIndex', 'integer', 0);
        if ($hideIndex == 1) {
            require 'section.php';
            exit();
        }

        $res = $res->id();
        include 'content.php';
        die();
    }
}

// Once all verifications has been passed then the resource
// param is present, then we will show it

$id = $params[1];
require 'resource.php';
