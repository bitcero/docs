<?php
// $Id: tc_replacements.php 821 2011-12-08 23:46:19Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function generate_res_index($matches)
{
    $mc = RMSettings::module_settings('docs');

    switch ($matches[0]) {
        case '[RD_RESINDEX]':
            if (defined('RESINDEX_ALL')) {
                return '';
            }

            define('RESINDEX_ALL', 1);

            return RDFunctions::resources_index('all', $mc->index_num);
            break;
        case '[RD_FEATINDEX]':
            if (defined('RESINDEX_FEATURED')) {
                return '';
            }

            define('RESINDEX_FEATURED', 1);

            return RDFunctions::resources_index('featured', $mc->index_num);
            break;
    }
}

/**
 * Build a note or reference
 *
 * @param int $atts ID of note
 * @return string|void
 */
function rd_build_note($atts)
{
    global $xoopsModuleConfig;

    $cc = RMCustomCode::get();
    extract($cc->atts([
        'id' => 0,
    ], $atts));

    static $note_number = 1;
    $ref = new RDReference($id);
    if ($ref->isNew()) {
        return;
    }

    $tpl = RMTemplate::get();

    $rep = ['<p>', '</p>'];
    $tpl->append('references', ['id' => $ref->id(), 'text' => str_replace($rep, '', $ref->getVar('text'))]);

    $ret = '<sup id="top' . $note_number . "\"><a class='note-link' href='#note-$note_number' title='" . $ref->getVar('title') . "'>";
    $ret .= "$note_number</a></sup>";

    $note_number++;

    return $ret;
}

/**
 * Builds a responsive table
 * @param $atts
 * @param $content
 * @return string|void
 */
function rd_build_table($atts, $content)
{
    global $xoopsModuleConfig;

    $cc = RMCustomCode::get();
    extract($cc->atts([
        'class' => 'table-responsive',
    ], $atts));

    $ret = "<div class=\"$class\">" . $content . '</div>';

    return $ret;
}

/**
 * Build a figure
 *
 * @param int $atts ID of figure
 * @return string
 */
function rd_build_figure($atts)
{
    static $figures_number = 1;

    $cc = RMCustomCode::get();
    extract($cc->atts([
        'id' => 0,
    ], $atts));

    if ($id <= 0) {
        return;
    }

    $fig = new RDFigure($id);
    if ($fig->isNew()) {
        return;
    }

    ob_start();
    include RMEvents::get()->run_event('docs.template.build.figure', RMTemplate::getInstance()->get_template('specials/docs-single-figure.php', 'module', 'docs'));
    $ret = ob_get_clean();

    $figures_number++;

    return $ret;
}

/**
 * Generate a Table of Contents for an specific section
 * @param mixed $atts
 * @return false|string|void
 */
function rd_generate_toc($atts)
{
    $cc = RMCustomCode::get();
    extract($cc->atts([
        'id' => 0,
        'doc' => 0,
    ], $atts));

    if ($id <= 0) {
        return;
    }

    $sec = new RDSection($id);
    if ($sec->isNew()) {
        return;
    }

    $toc = RDFunctions::get_section_tree($id, new RDResource($sec->getVar('id_res')));

    ob_start();
    include RMEvents::get()->run_event('docs.template.toc', RMTemplate::getInstance()->get_template('specials/docs-section-toc.php', 'module', 'docs'));
    $ret = ob_get_clean();

    return $ret;
}

function docs_make_internal_links($m)
{
    $tc = TextCleaner::getInstance();
    global $res;

    if ('' == $m[1]) {
        return;
    }

    $parts = explode(':', $m[1]);
    $link = '<a href="' . RDURL . '/';

    if (count($parts) > 1) {
        foreach ($parts as $i => $part) {
            if ($i <= 1) {
                $link .= $tc->sweetstring($part) . '/';
            } else {
                $link .= '#' . $tc->sweetstring($part);
            }
        }
    } else {
        $link .= $res->nameid . '/' . $tc->sweetstring($parts[0]) . '/';
    }

    $link .= '">' . array_pop($parts) . '</a>';

    return $link;
}
