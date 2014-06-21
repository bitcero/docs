<?php
// $Id: modinfo.php 911 2012-01-06 08:46:39Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if(function_exists("load_mod_locale")) load_mod_locale('docs');

define('_MI_RD_DISPLAYMETH', __('Display method for documents index','docs'));
define('_MI_RD_DISPLAYMETHD',__('Choose the method that you wish to use when display the documents index. This option affects to All, Recent and Featured index of documents.','docs'));
define('_MI_RD_SUBDOMAIN',__('Subdomain on which RapidDocs will be used','docs'));
define('_MI_RD_SUBDOMAIND', __('You can specify a subdomain if your htaccess file has been configured for it (e.g. http://docs.xoops.org).','docs'));
define('_MI_RD_URLSMODE', __('URLs mode:','docs'));
define('_MI_RD_URLSMODED',__('This options indicate the way in which RapidDocs will generate the URLs for documents.','docs'));
define('_MI_RD_BASEPATH',__('Base path for URLs','docs'));
define('_MI_RD_BASEPATHD', __('Indicate the base path used to generate URLs (eg. /docs).','docs'));
define('_MI_RD_COLS', __('Number of columns for index','docs'));
define('_MI_RD_COLSD', __('This option is used only when selected display method is "as list"','docs'));
define('_MI_RD_NUMRES', __('Number of documents in the index','docs'));
define('_MI_RD_NUMRESD', __('This is the limit for documents displayed in index','docs'));
define('_MI_RD_CREATENEW', __('Groups with authorization to create new documents','docs'));
define('_MI_RD_CREATENEWD', __('Select the groups that you wish to authorize to create new documents.','docs'));
define('_MI_RD_CREATEENABLED', __('Allow the creation of new documents','docs'));
define('_MI_RD_APPROVE', __('Auto approve documents created for authorized users','docs'));
define('_MI_RD_ATTRS','Default attributes for figures');
define('_MI_RD_ATTRSD','You can specify the attributes that will be added to figures when you create a new figure.');
define('_MI_RD_FIGEDITOR','Editor type for figures');
