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
 * @param mixed $options
 * @return array
 */
function rd_block_resources($options)
{
    global $xoopsModule;

    require_once XOOPS_ROOT_PATH . '/modules/docs/class/rdresource.class.php';
    $db = XoopsDatabaseFactory::getDatabaseConnection();

    $mc = RMSettings::module_settings('docs');

    $sql = 'SELECT * FROM ' . $db->prefix('mod_docs_resources') . ' WHERE public=1 AND approved=1';

    switch ($options[0]) {
        case 'recents':
            $sql .= ' ORDER BY created DESC';
            break;
        case 'popular':
            $sql .= ' ORDER BY `reads` DESC';
            break;
    }

    $sql .= ' LIMIT 0, ' . ($options[1] > 0 ? $options[1] : 5);

    $result = $db->query($sql);
    $block = [];
    while (false !== ($row = $db->fetchArray($result))) {
        $res = new RDResource();
        $res->assignVars($row);
        $ret = [];

        $ret['id'] = $res->id();
        $ret['title'] = $res->getVar('title');
        if ($options[2]) {
            $ret['desc'] = 0 == $options[3] ? $res->getVar('description') : TextCleaner::truncate($res->getVar('description'), $options[3]);
        }
        $ret['link'] = $res->permalink();
        $ret['author'] = sprintf(__('Created by %s', 'docs'), '<strong>' . $res->getVar('owname') . '</strong>');
        $ret['reads'] = sprintf(__('Viewed %s times', 'docs'), '<strong>' . $res->getVar('reads') . '</strong>');

        $block['resources'][] = $ret;
    }

    RMTemplate::get()->add_style('blocks.css', 'docs');

    return $block;
}

function rd_block_resources_edit($options)
{
    $rtn = "<table cellspacing='1' cellpadding='2' border='0'>
				<tr class='even'>
					<td style='width: 180px;'>" . __('Block type:', 'docs') . "</td>
					<td>
					<select name='options[0]'>
						<option value='recents'" . ('recents' === $options[0] ? " selected='selected'" : '') . '>' . __('Recent Documents', 'docs') . "</option>
						<option value='popular'" . ('popular' === $options[0] ? " selected='selected'" : '') . '>' . __('Top Documents', 'docs') . "</option>
					</select>
					</td>
				</tr>
				<tr class='even'>
					<td>" . __('Number of documents:', 'docs') . "</td>
					<td><input type='text' name='options[1]' value='$options[1]' size='5'></td>
				</tr>
				<tr class='even'>
					<td>" . __('Show description', 'docs') . "</td>
					<td>
						<input type='radio' value='1' name='options[2]'" . ($options[2] ? " checked" : '') . '> ' . _YES . "
						<input type='radio' value='0' name='options[2]'" . (!$options[2] ? " checked" : '') . '> ' . _NO . "
					</td>
				</tr>
				<tr class='even'>
					<td>" . __('Description length:', 'docs') . '<br><br><small><em>' . __('If you wish to show all description, then specify the length as "0".') . "</em></small></td>
					<td><input type='text' name='options[3]' value='$options[3]' size='5'></td>
				</tr>
	        </table>";

    return $rtn;
}
