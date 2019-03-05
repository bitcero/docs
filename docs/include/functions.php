<?php
// $Id: functions.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

/**
 * Assign vars to Smarty var, then this var can be used as index of the resource
 * @param int Id of the section parent
 * @param int Jumps (level)
 * @param object Resource (owner)
 * @param string Smarty var to append
 * @param string Index number to add (eg. 1.1)
 * @param bool Indicates if the array will be assigned to Smarty var or not
 * @param array Reference to an array for fill.
 * @param mixed $parent
 * @param mixed $jumps
 * @param mixed $var
 * @param mixed $number
 * @param mixed $assign
 * @param null|mixed $array
 * @return empty
 */
function assignSectionTree($parent, $jumps, AHResource $res, $var = 'index', $number = '', $assign = true, &$array = null)
{
    global $tpl;
    $db = XoopsDatabaseFactory::getDatabaseConnection();

    if ('AHResource' !== get_class($res)) {
        return;
    }

    $sql = 'SELECT * FROM ' . $db->prefix('pa_sections') . ' WHERE ' . ($res->id() > 0 ? "id_res='" . $res->id() . "' AND" : '') . "
			parent='$parent' ORDER BY `order`";
    $result = $db->query($sql);
    $sec = new AHSection();
    $i = 1; // Counter
    $num = 1;
    while (false !== ($row = $db->fetchArray($result))) {
        $sec->assignVars($row);
        $link = ah_make_link($res->nameId() . '/' . $sec->nameId());
        if ($assign) {
            $tpl->append($var, ['title' => $sec->title(), 'nameid' => $sec->nameId(), 'jump' => $jumps, 'link' => $link, 'number' => 0 == $jumps ? $num : ('' != $number ? $number . '.' : '') . $i]);
        } else {
            $array[] = ['title' => $sec->title(), 'nameid' => $sec->nameId(), 'jump' => $jumps, 'link' => $link, 'number' => 0 == $jumps ? $num : ('' != $number ? $number . '.' : '') . $i];
        }
        assignSectionTree($sec->id(), $jumps + 1, $res, $var, ('' != $number ? $number . '.' : '') . $i, $assign, $array);
        $i++;
        if (0 == $jumps) {
            $num++;
        }
    }

    return true;
}

/**
 * @desc Obtiene el primer parent de la sección especificada
 * @param int Id de la sección
 * @param mixed $id
 */
function ahBuildReference($id)
{
    global $xoopsModuleConfig, $tpl;

    $ref = new AHReference($id);
    if ($ref->isNew()) {
        return;
    }

    $ret = "<a name='top$id'></a><a href='javascript:;' " . (!$xoopsModuleConfig['refs_method'] ? "title='" . $ref->title() . "' " : ' ');
    if ($xoopsModuleConfig['refs_method']) {
        $ret .= "onclick=\"doReference(event,'$id');\"";
    } else {
        $ret .= "onclick=\"showReference($id,'$xoopsModuleConfig[refs_color]');\"";
        $tpl->append('references', ['id' => $ref->id(), 'text' => $ref->reference()]);
        $tpl->assign('have_refs', 1);
    }
    $ret .= "><img src='" . XOOPS_URL . "/modules/ahelp/images/reflink.png' align='textop' " . (!$xoopsModuleConfig['refs_method'] ? "alt='" . $ref->title() . "'" : '') . '></a>';

    return $ret;
}

function ahBuildFigure($id)
{
    $fig = new AHFigure($id);
    if ($fig->isNew()) {
        return;
    }

    $ret = '<div ';
    if ('' != $fig->_class()) {
        $ret .= "class='" . $fig->_class() . "' ";
    }
    if ('' != $fig->style()) {
        $ret .= "style='" . $fig->style() . "' ";
    }

    $ret .= $fig->figure();

    $ret .= "<div class='ahFigureFoot'>" . $fig->desc() . '</div></div>';

    return $ret;
}

/**
 * @desc Función para crear las referencias del documento
 * @param mixed $text
 */
function ahParseReferences($text)
{
    // Parseamos las figuras
    $pattern = "/\[fig:(.*)]/esU";
    $replacement = 'ahBuildFigure(\\1)';
    $text = preg_replace($pattern, $replacement, $text);

    return $text;
}

function ah_make_link($link = '')
{
    global $xoopsModuleConfig;

    $mc = &$xoopsModuleConfig;
    $url = $mc['access'] ? XOOPS_URL . $mc['htpath'] . '/' : XOOPS_URL . '/modules/ahelp/index.php?page=';

    return $url . $link;
}
