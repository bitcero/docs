<?php
// $Id: rate.php 911 2012-01-06 08:46:39Z i.bitcero $
// --------------------------------------------------------------
// Ability Help
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// @author BitC3R0 <i.bitcero@gmail.com>
// @license: GPL v2

define('AH_LOCATION','rate');
include '../../mainfile.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$rate = isset($_GET['rate']) ? intval($_GET['rate']) : 0;
$ret = isset($_GET['ret']) ? $_GET['ret'] : '';

$mc =& $xoopsModuleConfig;

if ($id<=0){
	redirect_header(XOOPS_URL.'/modules/ahelp/', 2, _MS_AH_NOID);
	die();
}

$res = new RDResource($id);
if ($res->isNew()){
	redirect_header(XOOPS_URL.'/modules/ahelp/', 2, _MS_AH_NOID);
	die();
}

$retlink = $ret!='' ? urldecode($ret) : XOOPS_URL.'/modules/ahelp/'.($mc['access'] ? 'content/'.$res->id().'/'.$res->nameId() : 'content.php?id='.$res->id());

if ($rate<=0 || $rate>5){
	redirect_header($retlink, 2, _MS_AH_NORATE);
	die();
}

$db->queryF("DELETE FROM ".$db->prefix("pa_votedata")." WHERE date<'".(time()-86400)."'");

$ip = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT COUNT(*) FROM ".$db->prefix("pa_votedata")." WHERE ";
if ($xoopsUser){
	$sql .= "uid='".$xoopsUser->uid()."' AND date>'".(time()-86400)."' AND res='".$res->id()."'";
} else {
	$sql .= "ip='$ip' AND date>'".(time()-86400)."' AND res='".$res->id()."'";
}

list($num) = $db->fetchRow($db->query($sql));

if ($num>0){
	redirect_header($retlink, 2, _MS_AH_NODAY);
	die();
}

if ($res->addVote($rate)){
	$db->queryF("INSERT INTO ".$db->prefix("pa_votedata")." (`uid`,`ip`,`date`,`res`) VALUES
			('".($xoopsUser ? $xoopsUser->uid() : 0)."','$ip','".time()."','$id')");
	redirect_header($retlink, 1, _MS_AH_VOTEOK);
	die();
} else {
	
	redirect_header($retlink, 1, _AS_AH_VOTEFAIL);
	die();
	
}
