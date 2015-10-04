<?php
/**
 * Documentor for XOOPS
 *
 * Copyright © 2015 Red Mexico http://www.redmexico.com.mx
 * -------------------------------------------------------------
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * -------------------------------------------------------------
 * @copyright    Red Mexico http://www.redmexico.com.mx
 * @license      GNU GPL 2
 * @package      docs
 * @author       Eduardo Cortés (AKA bitcero)    <i.bitcero@gmail.com>
 * @url          http://www.redmexico.com.mx
 * @url          http://www.eduardocortes.mx
 */

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
    'onUpdate'      => 'include/install.php',

    /**
     * Common Utilities specific information
     */
    'rmnative'      => 1,
    'url'           => 'https://github.com/bitcero/docs',
    'rmversion'     => array('major'=>1,'minor'=>0,'revision'=>21,'stage'=>-2, 'name'=>'Documentor'),
    'rewrite'       => 0,
    'permissions'   => 'include/permissions.php',
    'updateurl'     => "http://www.xoopsmexico.net/modules/vcontrol/",

    # Icons
    'icon16'        => 'images/icon16.png',
    'icon24'        => 'images/icon24.png',
    'icon32'        => 'images/icon32.png',
    'icon48'        => 'images/icon48.png',
    'icon'          => 'fa fa-book text-teal',

    # Credits
    'author'        => 'Eduardo Cortes',
    'authormail'    => 'i.bitcero@gmail.com',
    'authorweb'     => 'Sitio Personal',
    'authorurl'     => 'http://eduardocortes.mx',
    'credits'       => 'Eduardo Cortes',

    # Social
    'social'        => array(
        array('title' => __('Twitter', 'mywords'),'type' => 'twitter','url' => 'http://www.twitter.com/bitcero/'),
        array('title' => __('Facebook', 'mywords'),'type' => 'facebook','url' => 'http://www.facebook.com/eduardo.cortes.hervis/'),
        array('title' => __('Instagram', 'mywords'),'type' => 'instagram','url' => 'http://www.instagram.com/eduardocortesh/'),
        array('title' => __('Flickr', 'mywords'),'type' => 'flickr','url' => 'http://www.flickr.com/photos/bitcero/'),
        array('title' => __('LinkedIn', 'mywords'),'type' => 'linkedin','url' => 'http://www.linkedin.com/in/bitcero/'),
        array('title' => __('GithHub', 'mywords'),'type' => 'github','url' => 'http://www.github.com/bitcero/'),
        array('title' => __('My Blog', 'mywords'),'type' => 'blog','url' => 'http://eduardocortes.mx')
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

    # Configuration categories
    'categories' => array(
        'general' => __('General', 'docs'),
        'advanced' => __('Advanced', 'docs')
    ),

    # Configuration options
    'config' => array(

        array(
            'category' => 'general',
            'name' => 'permalinks',
            'title' => __('URLs mode','docs'),
            'description' => __('This options indicate the way in which RapidDocs will generate the URLs for documents.', 'docs'),
            'formtype' => 'select',
            'valuetype' => 'int',
            'default' => 0,
            'options' => array(__('PHP Default','docs')=>0,__('Name based','docs')=>1)
        ),

        array(
            'category' => 'general',
            'name' => 'subdomain',
            'title' => __('Subdomain on which RapidDocs will be used', 'docs'),
            'description' => __('You can specify a subdomain if your htaccess file has been configured for it (e.g. http://docs.xoops.org).', 'docs'),
            'formtype' => 'textbox',
            'valuetype' => 'text',
            'default' => ''
        ),

        array(
            'category' => 'general',
            'name' => 'htpath',
            'title' => __('Base path for URLs', 'docs'),
            'description' => __('Indicate the base path used to generate URLs (eg. /docs).', 'docs'),
            'formtype' => 'textbox',
            'valuetype' => 'text',
            'default' => '/modules/ahelp'
        ),

        array(
            'category' => 'general',
            'name' => 'ajax',
            'title' => __('Enable AJAX navigation', 'docs'),
            'description' => __('Activate the AJAX navigation for resource index. By enabling this options, the page load time will reduce drastically, but javascript is required.', 'docs'),
            'formtype' => 'yesno',
            'valuetype' => 'int',
            'default' => 1
        ),

        array(
            'category' => 'general',
            'name' => 'index_num',
            'title' => __('Number of documents in the index', 'docs'),
            'description' => __('This is the limit for documents displayed in index.', 'docs'),
            'formtype' => 'textbox',
            'valuetype' => 'int',
            'default' => 15
        ),

        array(
            'category' => 'general',
            'name' => 'createres',
            'title' => __('Allow the creation of new documents', 'docs'),
            'description' => __('This option affects the creation of new documents in front end. Administrators will continue creating documents in back end.'),
            'formtype' => 'yesno',
            'valuetype' => 'int',
            'default' => 1
        ),

        array(
            'category' => 'general',
            'name' => 'create_groups',
            'title' => __('Groups with authorization to create new documents', 'docs'),
            'description' => __('Select the groups that you wish to authorize to create new documents.', 'docs'),
            'formtype' => 'group_multi',
            'valuetype' => 'array',
            'default' => 1
        ),

        array(
            'category' => 'general',
            'name' => 'approved',
            'title' => __('Auto approve documents created for authorized users', 'docs'),
            'description' => '',
            'formtype' => 'yesno',
            'valuetype' => 'int',
            'default' => 0
        ),

        array(
            'category' => 'general',
            'name' => 'standalone',
            'title' => __('Activate standalone support', 'docs'),
            'description' => __('This option enables the document render without use of the XOOPS theme. Is very useful to integrate with other components as embeded help.', 'docs'),
            'formtype' => 'yesno',
            'valuetype' => 'int',
            'default' => 0
        ),

        array(
            'category' => 'general',
            'name' => 'standalone_css',
            'title' => __('Hoja de estilos para despliegue independiente', 'docs'),
            'description' => '',
            'formtype' => 'textbox',
            'valuetype' => 'text',
            'default' => XOOPS_URL.'/modules/docs/css/standalone.min.css'
        ),

        array(
            'category' => 'general',
            'name' => 'theme',
            'title' => __('Standalone theme', 'docs'),
            'description' => __('Allows to select the appearance for module when standalone display is enabled', 'docs'),
            'formtype' => 'select',
            'valuetype' => 'text',
            'default' => 'default',
            'options' => array(
                __('Default', 'docs')           => 'default',
                __('Maverik', 'docs')           => 'maverik',
                __('Maverik Inverted', 'docs')  => 'maverik-inverted',
                __('Azure', 'docs')             => 'azure',
                __('Azure Inverted', 'docs')    => 'azure-inverted',
                __('Sweet', 'docs')             => 'sweet',
                __('Evergreen', 'docs')         => 'ever-green',
                __('Midnight', 'docs')          => 'midnight'
            )
        ),

        array(
            'category'  => 'advanced',
            'name' => 'custom_css',
            'title' => __('Custom CSS', 'docs'),
            'description' => __('This CSS code will be inserted in the module pages. You can add your own styles.', 'docs'),
            'formtype'  => 'textarea',
            'valuetype' => 'text',
            'default'   => ''
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
