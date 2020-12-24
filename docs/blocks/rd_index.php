<?php
// $Id: rd_index.php 821 2011-12-08 23:46:19Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function rd_block_index($options)
{
    global $xoopsModule, $xoopsModuleConfig, $res, $sec;

    if (!$xoopsModule || 'docs' !== $xoopsModule->dirname()) {
        return;
    }
    if (!defined('RD_LOCATION') || (RD_LOCATION !== 'content' && RD_LOCATION !== 'resource_content')) {
        return;
    }

    // get the sections
    $sections = [];
    RDFunctions::sections_tree_index(0, 0, $res, '', '', false, $sections, false);

    $block['sections'] = $sections;
    $block['section'] = $sec;
    $block['resource'] = $res->getVar('nameid');

    return $block;
}
