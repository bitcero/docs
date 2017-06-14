<?php
// $Id: rmcommon.php 869 2011-12-22 08:50:24Z i.bitcero $
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class DocsRmcommonPreload{
    
    static function eventRmcommonLoadRightWidgets($widgets){
        global $xoopsModule;
        
        if (!isset($xoopsModule) || ($xoopsModule->getVar('dirname')!='system' && $xoopsModule->getVar('dirname')!='docs'))
            return $widgets;
        
        if (defined("RMCSUBLOCATION") && RMCSUBLOCATION=='newresource'){
            include_once '../include/admin-widgets.php';
            $widgets[] = rd_widget_options();
            $widgets[] = doc_widget_index();
        }
        
        return $widgets;
    }

    /**
    * Add new code converter to decode [TOC], [RDRESOURCE] and [RDFEATURED]
    */
    static function eventRmcommonTextTodisplay($text, $source){
        global $xoopsModule;
        
        if(!$xoopsModule || $xoopsModule->dirname()!='docs' || defined('RD_NO_FIGURES'))
            return $text;
        
        if(function_exists('xoops_cp_header')) return $text;
        
        // If home page contains some index

        $text = preg_replace_callback("/\[RD_RESINDEX\]/", 'generate_res_index', $text);
        $text = preg_replace_callback("/\[RD_FEATINDEX\]/", 'generate_res_index', $text);

        // Enlaces internos
        $text = preg_replace_callback("/\[\[([^\[\]]+)\]\]/", 'docs_make_internal_links', $text);

        // Build TOC
        //$text = preg_replace_callback("/\[TOC\]/", 'rd_generate_toc', $text);
        
        return $text;
        
    }

    /**
     * Add custom codes support
     */
    static function eventRmcommonIncludeCommonLanguage(){
        global $rmCodes;

        include_once XOOPS_ROOT_PATH.'/modules/docs/include/tc_replacements.php';

        $rmCodes->add( 'TOC', 'rd_generate_toc' );
        $rmCodes->add( 'note', 'rd_build_note' );
        $rmCodes->add( 'figure', 'rd_build_figure' );
        $rmCodes->add( 'table_responsive', 'rd_build_table' );
        $rmCodes->add( 'monospaced', 'docs_monospaced_block' );

    }

    /**
     * Save htaccess configuration
     *
     * @param string $dirname Module directory
     * @param array $save Array with options saved
     * @param array $add New settings added to database
     * @param array $delete Existing settings deleted from database
     * @return null
     */
    static function eventRmcommonSavedSettings( $dirname, $save, $add, $delete ){

        if ( $dirname != 'docs' )
            return $dirname;

        // URL rewriting
        $rule = "RewriteRule ^".trim($save['htpath'],'/')."/?(.*)$ modules/docs/index.php [L]";
        if ( $save['permalinks'] == 1 ){

            $ht = new RMHtaccess('docs');
            $htResult = $ht->write($rule);
            if($htResult!==true){
                showMessage(__('An error ocurred while trying to write .htaccess file!','docs'), RMMSG_ERROR);
            }

        } else {
            $ht = new RMHtaccess( 'docs' );
            $ht->removeRule();
            $ht->write();
        }

        return null;
    }

    static function eventRmcommonEditorTopPlugins( $plugins, $type, $id ){
        global $xoopsModule;

        if(!$xoopsModule || 'docs' != $xoopsModule->getVar('dirname')){
            return $plugins;
        }

        $plugins[] = RDFunctions::editor_plugin( $id, $type );

        return $plugins;

    }

}
