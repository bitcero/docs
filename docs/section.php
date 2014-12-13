<?php
/**
 * Documentor for XOOPS
 * Documentation system for XOOPS based on Common Utilities
 * 
 * Copyright © 2014 Eduardo Cortés
 * -----------------------------------------------------------------
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
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * -----------------------------------------------------------------
 * @package      Documentor
 * @author       Eduardo Cortés <yo@eduardocortes.mx>
 * @copyright    2009 - 2014 Eduardo Cortés
 * @license      GPL v2
 * @link         http://eduardocortes.mx
 * @link         http://xoopsmexico.net
 */

/**
 * This file respond to AJAX request, but also can respond to
 * standalone request. This file must receive as parameter
 * the ID of a section:
 * <code>/section.php?id=1</code>
 */

require '../../mainfile.php';

class RDAjaxResponse
{
    use RMModuleAjax;
}

/**
 * This function get the level of current section
 * in order to create the hteml header tag (h1, h2, etc.)
 * @param int $id Id of parent section
 * @param int $level Current level
 * @return mixed
 */
function get_level( $id, $level ){

    $section = new RDSection( $id );
    if ( $section->isNew() )
        return $level;

    if ( $section->parent == 0 )
        return $level;

    $level = get_level( $section->parent, $level + 1 );

    return $level;

}

function get_position( $section ){
    global $xoopsDB;

    $result = $xoopsDB->query( "SELECT * FROM " . $xoopsDB->prefix("mod_docs_sections") . " WHERE parent = " . $section->parent . " ORDER BY `order`" );
    $i = 1;
    while( $row = $xoopsDB->fetchArray( $result ) ){

        if ( $row['id_sec'] == $section->id() )
            return $i;

        $i++;

    }

    return $i;

}

function get_parent_position( $id, $parents ){

    foreach( $parents as $id_parent => $parent ){
        if ( $id_parent == $id )
            return $parent;
    }

    return null;

}

function form_number( $section, $number ){
    global $parents;

    if ( $section->parent > 0 ){

        $number = get_position( $section ) . ($number != '' ? '.' . $number : '');
        $parent = new RDSection( $section->parent );
        $number = form_number( $parent, $number );

    } else {

        $number = get_parent_position( $section->id(), $parents ) . '.' . $number;

    }

    return $number;

}

$ajax = new RDAjaxResponse();
$ajax->prepare_ajax_response();

define( 'RDURL', RDFunctions::url() );

if ( empty( $id ) )
    $ajax->ajax_response(
        __('No ID', 'docs'), 1, 0
    );

$section = new RDSection( $id, $res->id() );
if ( $section->isNew() )
    $ajax->ajax_response(
        __('Content not found', 'docs'), 1, 0
    );

$result = $xoopsDB->query( "SELECT id_sec FROM " . $xoopsDB->prefix("mod_docs_sections") . " WHERE parent = 0 AND id_res = '". $res->id() ."' ORDER BY `order`");
$parents = array();
$i = 1;
while ( $row = $xoopsDB->fetchArray( $result ) ) {
    $parents[$row['id_sec']] = $i;
    $i++;
}

$super = RDFunctions::super_parent( $section->id() );

if ( $section->parent == 0 ) {
    $level = 2;
    $number = get_parent_position( $section->id(), $parents ) . '.0';
}else {
    $level = get_level($section->parent, 3);
    $number = form_number($section, '');
}

$standalone = $xoopsModuleConfig['standalone'];

ob_start();
include RMTemplate::get()->get_template( 'docs-ajax-section.php', 'module', 'docs' );
$content = ob_get_clean();

$ajax->ajax_response(
    'ok', 0, 0, array(
        'content'   => $content,
        'title'     => $section->title,
        'id'        => $section->id()
    )
);

