<?php
// $Id: install.php 834 2011-12-10 02:35:58Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function xoops_module_pre_install_docs(&$mod)
{
    xoops_setActiveModules();
    
    $mods = xoops_getActiveModules();
    
    if (!in_array("rmcommon", $mods)) {
        $mod->setErrors('Documentor could not be instaled if <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a> has not be installed previously!<br />Please install <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>.');
        return false;
    }
    
    return true;
}

function xoops_module_update_docs($module, $previous)
{
    global $xoopsDB;

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("rd_edits").'` TO  `'.$xoopsDB->prefix("mod_docs_edits").'` ;');
    $xoopsDB->queryF("ALTER TABLE  ``" . $xoopsDB->prefix("mod_docs_edits") . "` ENGINE = INNODB");

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("rd_figures").'` TO  `'.$xoopsDB->prefix("mod_docs_figures").'` ;');
    $xoopsDB->queryF("ALTER TABLE  ``" . $xoopsDB->prefix("mod_docs_figures") . "` ENGINE = INNODB");

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("rd_meta").'` TO  `'.$xoopsDB->prefix("mod_docs_meta").'` ;');
    $xoopsDB->queryF("ALTER TABLE  ``" . $xoopsDB->prefix("mod_docs_meta") . "` ENGINE = INNODB");

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("rd_references").'` TO  `'.$xoopsDB->prefix("mod_docs_references").'` ;');
    $xoopsDB->queryF("ALTER TABLE  ``" . $xoopsDB->prefix("mod_docs_references") . "` ENGINE = INNODB");

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("rd_resources").'` TO  `'.$xoopsDB->prefix("mod_docs_resources").'` ;');
    $xoopsDB->queryF("ALTER TABLE  ``" . $xoopsDB->prefix("mod_docs_resources") . "` ENGINE = INNODB");

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("rd_sections").'` TO  `'.$xoopsDB->prefix("mod_docs_sections").'` ;');
    $xoopsDB->queryF("ALTER TABLE  ``" . $xoopsDB->prefix("mod_docs_sections") . "` ENGINE = INNODB");

    $xoopsDB->queryF('RENAME TABLE `'.$xoopsDB->prefix("rd_votedata").'` TO  `'.$xoopsDB->prefix("mod_docs_votedata").'` ;');
    $xoopsDB->queryF("ALTER TABLE  ``" . $xoopsDB->prefix("mod_docs_votedata") . "` ENGINE = INNODB");

    $xoopsDB->queryF("ALTER TABLE `" . $xoopsDB->prefix("mod_docs_sections") . "` ADD `level` TINYINT(1) NOT NULL DEFAULT '1' AFTER `order`;");
    $xoopsDB->queryF("ALTER TABLE `" . $xoopsDB->prefix("mod_docs_resources") . "` ADD `image` VARCHAR(255) NOT NULL AFTER `editors`;");
    $xoopsDB->queryF("ALTER TABLE `" . $xoopsDB->prefix("mod_docs_resources") . "` ADD `tagline` VARCHAR(255) NOT NULL AFTER `title`;");

    return true;
}
