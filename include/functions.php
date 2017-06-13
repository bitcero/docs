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
* @return empty
*/
function assignSectionTree($parent = 0, $jumps = 0, AHResource $res, $var = 'index', $number='', $assign = true, &$array = null){
	global $tpl;
	$db = XoopsDatabaseFactory::getDatabaseConnection();
	
	if (get_class($res)!='AHResource') return;
	
	$sql = "SELECT * FROM ".$db->prefix("pa_sections")." WHERE ".($res->id()>0 ? "id_res='".$res->id()."' AND" : '')."
			parent='$parent' ORDER BY `order`";
	$result = $db->query($sql);
	$sec = new AHSection();
	$i = 1; // Counter
	$num = 1;
	while ($row = $db->fetchArray($result)){
		$sec->assignVars($row);
		$link = ah_make_link($res->nameId().'/'.$sec->nameId());
		if ($assign){
			$tpl->append($var, array('title'=>$sec->title(),'nameid'=>$sec->nameId(), 'jump'=>$jumps,'link'=>$link, 'number'=>$jumps==0 ? $num : ($number !='' ? $number.'.' : '').$i));
		} else {
			$array[] = array('title'=>$sec->title(), 'nameid'=>$sec->nameId(), 'jump'=>$jumps,'link'=>$link, 'number'=>$jumps==0 ? $num : ($number !='' ? $number.'.' : '').$i);
		}
		assignSectionTree($sec->id(), $jumps+1, $res, $var, ($number !='' ? $number.'.' : '').$i, $assign, $array);
		$i++;
		if ($jumps==0) $num++;
	}
	
	return true;
}

function ahBuildFigure($id){
    
    $fig = new AHFigure($id);
    if ($fig->isNew()) return;
    
    $ret = "<div ";
    if ($fig->_class()!='') $ret .= "class='".$fig->_class()."' ";
    if ($fig->style()!='') $ret .= "style='".$fig->style()."' ";
    
    $ret .= $fig->figure();
    
    $ret .= "<div class='ahFigureFoot'>".$fig->desc()."</div></div>";
    
    return $ret;
    
}

/**
* @desc Función para crear las referencias del documento
*/
function ahParseReferences($text){
	
    
    
    // Parseamos las figuras
    $pattern = "/\[fig:(.*)]/esU";
    $replacement = "ahBuildFigure(\\1)";
    $text = preg_replace($pattern, $replacement, $text);
    
	return $text;
	
}

function ah_make_link($link=''){
    global $xoopsModuleConfig;
    
    $mc =& $xoopsModuleConfig;
    $url = $mc['access'] ? XOOPS_URL.$mc['htpath'].'/' : XOOPS_URL.'/modules/ahelp/index.php?page=';
    
    return $url.$link;
    
}
