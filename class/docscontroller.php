<?php
// $Id: docscontroller.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* This file contains the object MywordsController that
* will be uses by Common Utilities to do some actions
* like update comments
*/

class DocsController implements iCommentsController
{
    public function increment_comments_number($comment){
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
        // Invalid parameters
        if(!isset($res) || $res<=0) return;
        
        if(isset($id) && $id>0)
            $sql = "UPDATE ".$db->prefix("mod_docs_sections")." SET comments=comments+1 WHERE id_sec=$id";
        else
            $sql = "UPDATE ".$db->prefix("mod_docs_resources")." SET comments=comments+1 WHERE id_res=$res";
        
        $db->queryF($sql);

    }
    
    public function reduce_comments_number($comment){
		
		$db = XoopsDatabaseFactory::getDatabaseConnection();
        $params = urldecode($comment->getVar('params'));
        parse_str($params);
        
         // Invalid parameters
        if(!isset($res) || $res<=0) return;
        
        if(isset($id) && $id>0)
            $sql = "UPDATE ".$db->prefix("mod_docs_sections")." SET comments=comments-1 WHERE id_sec=$id";
        else
            $sql = "UPDATE ".$db->prefix("mod_docs_resources")." SET comments=comments-1 WHERE id_res=$res";
        
        $db->queryF($sql);
		
    }
    
    public function get_item($params, $com, $url = false){
        static $cresources;
        static $csections;
        
        $params = urldecode($params);
        parse_str($params);
        if(!isset($res) || $res<=0) return __('Unknow element','docs');;
        
        include_once XOOPS_ROOT_PATH.'/modules/docs/class/rdresource.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/docs/class/rdsection.class.php';
        
        if(isset($id) && $id>0){

            if (isset($csections[$id])){
                $ret = $csections[$id]->getVar('title');
                return $ret;
            }
            
            $sec = new RDSection($id);
            if($sec->isNew())
                return __('Unknow element','docs');
            
            $ret = $sec->getVar('title');
            $csections[$id] = $sec;
            
            return $ret;
            
        } else {
            
            if (isset($cresources[$res])){
                $ret = $cresources[$res]->getVar('title');
                return $ret;
            }
            
            $resoruce = new RDResource($res);
            if($resoruce->isNew())
                return __('Unknow element','docs');
            
            $ret = $resoruce->getVar('title');
            $cresources[$res] = $resoruce;
            
            return $ret;
            
        }
        
        
    }
	
	public function get_item_url($params, $com){
		static $cresources;
        static $csections;
        
        $params = urldecode($params);
        parse_str($params);
        if(!isset($res) || $res<=0) return __('Unknow element','docs');;
        
        include_once XOOPS_ROOT_PATH.'/modules/docs/class/rdresource.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/docs/class/rdsection.class.php';
        
        if(isset($id) && $id>0){

            if (isset($csections[$id])){
                $ret = $csections[$id]->permalink().'#comment-'.$com->id();
                return $ret;
            }
            
            $sec = new RDSection($id);
            if($sec->isNew())
                return '';
            
            $ret = $sec->permalink().'#comment-'.$com->id();
            $csections[$id] = $sec;
            
            return $ret;
            
        } else {
            
            if (isset($cresources[$res])){
                $ret = $cresources[$res]->permalink().'#comment-'.$com->id();
                return $ret;
            }
            
            $resoruce = new RDResource($res);
            if($resoruce->isNew())
                return '';
            
            $ret = $resoruce->permalink().'#comment-'.$com->id();
            $cresources[$res] = $resoruce;
            
            return $ret;
            
        }
	}
    
    public function get_main_link(){
		
		$mc = RMSettings::module_settings('mywords');
		
		if ($mc->permalinks > 1){
			return XOOPS_URL.$mc['basepath'];
		} else {
			return XOOPS_URL.'/modules/mywords';
		}
		
    }
    
}
