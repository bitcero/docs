<?php
// $Id: xoops_version.php 972 2012-05-31 04:21:14Z i.bitcero $
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

include_once 'include/xv-header.php';

if(function_exists("load_mod_locale")) load_mod_locale('docs');

$modversion = array(

    /**
     * XOOPS general information
     */
    'name'          => 'Documentor',
    'description'   => __('Create documentation in Xoops, quicky and an advanced way.','docs'),
    'version'       => 1,
    'help'          => 'docs/readme.html',
    'license'       => 'GPL 2',
    'official'      => 0,
    'image'         => 'images/logo.png',
    'dirname'       => 'docs',
    'onInstall'     => 'include/install.php',

    /**
     * Common Utilities specific information
     */
    'rmnative'      => 1,
    'rmversion'     => array('major'=>1,'minor'=>0,'revision'=>1,'stage'=>-2, 'name'=>'Documentor'),
    'rewrite'       => 1,
    'permissions'   => 'include/permissions.php',
    'updateurl'     => "http://www.xoopsmexico.net/modules/vcontrol/",

    # Icons
    'icon16'        => 'images/icon16.png',
    'icon24'        => 'images/icon24.png',
    'icon32'        => 'images/icon32.png',
    'icon48'        => 'images/icon48.png',

    # Credits
    'author'        => 'Eduardo Cortes',
    'authormail'    => 'i.bitcero@gmail.com',
    'authorweb'     => 'Sitio Personal',
    'authorurl'     => 'http://eduardocortes.mx',
    'credits'       => 'Eduardo Cortes',

    # Social
    'social'        => array(
        array('title' => __('Twitter', 'mywords'),'type' => 'twitter','url' => 'http://www.twitter.com/bitcero/'),
        array('title' => __('Facebook', 'mywords'),'type' => 'facebook-square','url' => 'http://www.facebook.com/eduardo.cortes.hervis/'),
        array('title' => __('Instagram', 'mywords'),'type' => 'instagram','url' => 'http://www.instagram.com/eduardocortesh/'),
        array('title' => __('Flickr', 'mywords'),'type' => 'flickr','url' => 'http://www.flickr.com/photos/bitcero/'),
        array('title' => __('LinkedIn', 'mywords'),'type' => 'linkedin-square','url' => 'http://www.linkedin.com/in/bitcero/'),
        array('title' => __('GithHub', 'mywords'),'type' => 'github','url' => 'http://www.github.com/bitcero/'),
        array('title' => __('My Blog', 'mywords'),'type' => 'quote-left','url' => 'http://eduardocortes.mx')
    ),

    # Backend
    'hasAdmin'      => 1,
    'adminindex'    => 'admin/index.php',
    'adminmenu'     => 'admin/menu.php',

    # Frontend
    'hasMain'       => 1,

    # Search
    'hasSearch'     => 1,
    'search'        => array(
        'file'  => 'include/search.php',
        'func'  => 'docs_search_function'
    ),

    /**
     * Database
     */

    # SQL file
    'sqlfile'       => array( 'mysql' => 'sql/mysql.sql'),

    # Tables
    'tables'        => array(
        'mod_docs_resources',
        'mod_docs_sections',
        'mod_docs_references',
        'mod_docs_figures',
        'mod_docs_votedata',
        'mod_docs_edits',
        'mod_docs_meta'
    ),

    # Configuration options
    'config' => array(

        array(
            'name' => 'permalinks',
            'title' => __('URLs mode','docs'),
            'description' => __('This options indicate the way in which RapidDocs will generate the URLs for documents.', 'docs'),
            'formtype' => 'select',
            'valuetype' => 'int',
            'default' => 0,
            'options' => array(__('PHP Default','docs')=>0,__('Name based','docs')=>1)
        ),

        array(
            'name' => 'subdomain',
            'title' => __('Subdomain on which RapidDocs will be used', 'docs'),
            'description' => __('You can specify a subdomain if your htaccess file has been configured for it (e.g. http://docs.xoops.org).', 'docs'),
            'formtype' => 'textbox',
            'valuetype' => 'text',
            'default' => ''
        ),

        array(
            'name' => 'htpath',
            'title' => __('Base path for URLs', 'docs'),
            'description' => __('Indicate the base path used to generate URLs (eg. /docs).', 'docs'),
            'formtype' => 'textbox',
            'valuetype' => 'text',
            'default' => '/modules/ahelp'
        ),

        array(
            'name' => 'index_num',
            'title' => __('Number of documents in the index', 'docs'),
            'description' => __('This is the limit for documents displayed in index.', 'docs'),
            'formtype' => 'textbox',
            'valuetype' => 'int',
            'default' => 15
        ),

        array(
            'name' => 'createres',
            'title' => __('Allow the creation of new documents', 'docs'),
            'description' => __('This option affects the creation of new documents in front end. Administrators will continue creating documents in back end.'),
            'formtype' => 'yesno',
            'valuetype' => 'int',
            'default' => 1
        ),

        array(
            'name' => 'create_groups',
            'title' => __('Groups with authorization to create new documents', 'docs'),
            'description' => __('Select the groups that you wish to authorize to create new documents.', 'docs'),
            'formtype' => 'group_multi',
            'valuetype' => 'array',
            'default' => 1
        ),

        array(
            'name' => 'approved',
            'title' => __('Auto approve documents created for authorized users', 'docs'),
            'description' => '',
            'formtype' => 'yesno',
            'valuetype' => 'int',
            'default' => 0
        ),

        array(
            'name' => 'standalone',
            'title' => __('Activar soporte para despliegue independiente', 'docs'),
            'description' => __('This option enables the document render without use of the XOOPS theme. Is very useful to integrate with other components as embeded help.', 'docs'),
            'formtype' => 'yesno',
            'valuetype' => 'int',
            'default' => 0
        ),

        array(
            'name' => 'standalone_css',
            'title' => __('Hoja de estilos para despliegue independiente', 'docs'),
            'description' => '',
            'formtype' => 'textbox',
            'valuetype' => 'text',
            'default' => XOOPS_URL.'/modules/docs/css/standalone.css'
        )

    ),

    # Module blocks
    'blocks'        => array(

        array(
            'file' => 'rd_resources.php',
            'name' => __('Documents','docs'),
            'description' => __('List of Documents','docs'),
            'show_func' => 'rd_block_resources',
            'edit_func' => 'rd_block_resources_edit',
            'template' => 'rd_bk_resources.html',
            'options' => "recents|5|0|1|1|0|0"
        ),

        array(
            'file' => 'rd_index.php',
            'name' => __('Document TOC','docs'),
            'description' => __('Table of content for a specific Document','docs'),
            'show_func' => 'rd_block_index',
            'edit_func' => '',
            'template' => 'rd_bk_index.html',
            'options' => ''
        )

    )

);
