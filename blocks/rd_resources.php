<?php
// $Id: rd_resources.php 911 2012-01-06 08:46:39Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Este archivo permite controlar el bloque o los bloques
* Bloques Existentes:
* 
* 1. Publicaciones Recientes
* 2. Publicaciones Populares (Mas Leídas)
* 3. Publicaciones Mejor Votadas
*/

function rd_block_resources($options){
	global $xoopsModule;
	
	include_once XOOPS_ROOT_PATH.'/modules/docs/class/rdresource.class.php';
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	
	$mc = RMSettings::module_settings('docs');
	
	$sql = "SELECT * FROM ".$db->prefix("mod_docs_resources").' WHERE public=1 AND approved=1';
	
	switch ($options[0]){
		case 'recents':
			$sql .= " ORDER BY created DESC";
			break;
		case 'popular':
			$sql .= " ORDER BY `reads` DESC";
			break;
	}
	
	$sql .= " LIMIT 0, ".($options[1]>0 ? $options[1] : 5);
	
	$result = $db->query($sql);
	$block = array();
	while ($row = $db->fetchArray($result)){
		$res = new RDResource();
		$res->assignVars($row);
		$ret = array();
		
		$ret['id'] = $res->id();
		$ret['title'] = $res->getVar('title');
		if ($options[2]){
			$ret['desc'] = $options[3]==0 ? $res->getVar('description') : TextCleaner::truncate($res->getVar('description'), $options[3]);
		}
        $ret['link'] = $res->permalink();		
		$ret['author'] = sprintf(__('Created by %s','docs'), '<strong>'.$res->getVar('owname').'</strong>');
		$ret['reads'] = sprintf(__('Viewed %s times','docs'), '<strong>'.$res->getVar('reads').'</strong>');
		
		$block['resources'][] = $ret;
		
	}
    
    RMTemplate::getInstance()->add_style('blocks.css', 'docs');
	
	return $block;
	
}

function rd_block_resources_edit($options){

    ob_start();
    ?>
    <div class="row form-group">
        <div class="col-sm-5 col-md-3">
            <label><?php _e('Block type:','docs'); ?></label>
        </div>
        <div class="col-sm-7 col-md-9">
            <select name='options[0]' class="form-control">
                <option value='recents'"<?php echo $options[0]=='recents' ? " selected" : ""; ?>><?php _e('Recent Documents','docs'); ?></option>
                <option value='popular'"<?php echo $options[0]=='popular' ? " selected" : ""; ?>><?php _e('Top Documents','docs'); ?></option>
            </select>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-5 col-md-3">
            <label><?php _e('Number of documents:','docs'); ?></label>
        </div>
        <div class="col-sm-7 col-md-9">
            <input type='text' name='options[1]' value='<?php echo $options[1]; ?>' class="form-control" style="max-width: 100px;">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-5 col-md-3">
            <label><?php _e('Show description','docs'); ?></label>
        </div>
        <div class="col-sm-7 col-md-9">
            <label class="radio-inline">
                <input type='radio' value='1' name='options[2]'"<?php echo $options[2] ? " checked" : ""; ?>>
                <?php _e('Yes', 'docs'); ?>
            </label>
            <label class="radio-inline">
                <input type='radio' value='0' name='options[2]'"<?php echo !$options[2] ? " checked" : ""; ?>>
                <?php _e('No', 'docs'); ?>
            </label>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-5 col-md-3">
            <label><?php _e('Description length:','docs'); ?></label>
        </div>
        <div class="col-sm-7 col-md-9">
            <input type='text' name='options[3]' value='<?php echo $options[3]; ?>' style="max-width: 100px;">
            <small class="help-block">
                <?php _e('If you wish to show all description, then specify the length as "0".'); ?>
            </small>
        </div>
    </div>
    <?php

    $form = ob_get_clean();
	
	return $form;
	
}
