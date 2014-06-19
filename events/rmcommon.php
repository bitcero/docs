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
    
    public function eventRmcommonLoadRightWidgets($widgets){
        global $xoopsModule;
        
        if (!isset($xoopsModule) || ($xoopsModule->getVar('dirname')!='system' && $xoopsModule->getVar('dirname')!='docs'))
            return $widgets;
        
        if (defined("RMCSUBLOCATION") && RMCSUBLOCATION=='newresource'){
            include_once '../include/admin-widgets.php';
            $widgets[] = rd_widget_options();
            $widgets[] = rd_widget_references();
            $widgets[] = rd_widget_figures(); 
        }
        
        if(defined('RMCSUBLOCATION') && RMCSUBLOCATION=='notes_list'){
            include_once '../include/admin-widgets.php';
            $widgets[] = rd_widget_newnote();
        }
        
        return $widgets;
    }
    
    // Plugin for TinyMCE
    public function eventRmcommonTinymcePluginLoading(){
        
        $config = RMSettings::module_settings('docs');
        
        $ret = parse_url($_SERVER['HTTP_REFERER']);
        
        if($config->permalinks){
            $ref = XOOPS_URL.'/'.trim($config->htpath, '/').'/';
        } else {
            $ref = XOOPS_URL.'/modules/docs/edit.php';
        }
        
        $ref = parse_url($ref);
        $pass = false;
        if (substr($ret['path'], 0, strlen($ref['path']))==$ref['path']){
            $pass = true;
        }
        
        // Check if page is valid
        if(substr($ret['path'], -12)=='sections.php' || $pass){
          // Sections Editor
          // Show figures, references and TOC buttons
          parse_str($ret['query'], $str); ?>
            ed.addCommand('mceRapidDocsReferences', function() {
                ed.windowManager.open({
                    file : '<?php echo XOOPS_URL; ?>/modules/docs/references.php?id=<?php echo $str['id']; ?>',
                    width : 600 + parseInt(ed.getLang('exmsystem.delta_width', 0)),
                    height : 600 + parseInt(ed.getLang('exmsystem.delta_height', 0)),
                    inline : 1,
                    title: '<?php _e('Notes & References','docs'); ?>',
                    maximizable: 'true'
                }, {
                    plugin_url : '<?php echo XOOPS_URL; ?>/modules/docs'
                });
            });

            ed.addCommand('mceRapidDocsFigures', function() {
                ed.windowManager.open({
                    file : '<?php echo XOOPS_URL; ?>/modules/docs/figures.php?id=<?php echo $str['id']; ?>',
                    width : 600 + parseInt(ed.getLang('exmsystem.delta_width', 0)),
                    height : 600 + parseInt(ed.getLang('exmsystem.delta_height', 0)),
                    inline : 1,
                    title: '<?php _e('Figures','docs'); ?>',
                    maximizable: 'true'
                }, {
                    plugin_url : '<?php echo XOOPS_URL; ?>/modules/docs'
                });
            });

            ed.addCommand('mceRapidDocsTOC', function() {
                ed.execCommand("mceInsertContent", true, '[TOC]');
            });
            
            // Register buttons
            ed.addButton('rd_refs', {
                title : '<?php _e('Insert notes & references','docs'); ?>',
                image : '<?php echo XOOPS_URL; ?>/modules/docs/images/notes_plugin.png',
                cmd : 'mceRapidDocsReferences'
            });
            
            // Register buttons
            ed.addButton('rd_figures', {
                title : '<?php _e('Insert figures','docs'); ?>',
                image : '<?php echo XOOPS_URL; ?>/modules/docs/images/figures_plugin.png',
                cmd : 'mceRapidDocsFigures'
            });

            ed.addButton('rd_toc', {
                title : '<?php _e('Insert Table Of Contents','docs'); ?>',
                image : '<?php echo XOOPS_URL; ?>/modules/docs/images/toc.png',
                cmd : 'mceRapidDocsTOC'
            });
          
            
        <?php
        } elseif(substr($ret['path'], -9)=='hpage.php') {
        ?>
           
            ed.addCommand('mceRapidDocsIndexSections', function() {
                //ed.execCommand("mceInsertContent", true, '[RDRESOURCES]');
                c = ed.controlManager.createListBox('resources_index', {title : '<?php _e('Documents Index','docs'); ?>', cmd : 'FormatBlock'});
                c.add('<?php _e('Documents Index','docs'); ?>','[RESOURCESINDEX]');
                c.showMenu();
            });

            ed.addButton('rd_resources', {
                title : '<?php _e('Insert Documents Index','docs'); ?>',
                image : '<?php echo XOOPS_URL; ?>/modules/docs/images/index.png',
                cmd : 'mceRapidDocsIndexSections'
            });
        <?php
        }
    }
    
    public function eventRmcommonTinyPluginControls(){
        
        $ret = parse_url($_SERVER['HTTP_REFERER']);

        // Check if page is valid
        if(substr($ret['path'], -9)=='hpage.php'){
        ?>
        createControl: function(n,cm){
            switch(n){
                case 'res_index':
                    var c = cm.createSplitButton('resindex', {
                        title: '<?php _e('Documents Index','docs'); ?>',
                        image: '<?php echo XOOPS_URL; ?>/modules/docs/images/index.png'
                    });
                    
                    c.onRenderMenu.add(function(c, m) {
                        m.add({
                            title : '<?php _e('All Documents','docs'); ?>', 
                            onclick : function(){
                                tinyMCE.activeEditor.execCommand("mceInsertContent", true, '[RD_RESINDEX]');
                            }
                        });
                        
                        m.add({
                            title : '<?php _e('Featured Documents','docs'); ?>', 
                            onclick : function(){
                                tinyMCE.activeEditor.execCommand("mceInsertContent", true, '[RD_FEATINDEX]');
                            }
                        });

                    });

                    return c;
            }
            return null;
        },
        <?php   
        }
        
    }
    
    // Plugins for XoopsCode Editor
    public function eventRmcommonLoadExmcodePlugins(){
        $ret = parse_url($_SERVER['HTTP_REFERER']);
        // Check if page is valid
        if(substr($ret['path'], -12)=='sections.php' || defined('RD_SECTION_FORM')){
          // Sections Editor
          // Show figures, references and TOC buttons
          parse_str($ret['query'], $str); ?>
        x.add_plugin('docsrefs', {
            show: function(){
                
                x.popup({
                    width: 600,
                    height: 600,
                    title: 'Notes & References',
                    url: '<?php echo XOOPS_URL; ?>/modules/docs/references.php?id=<?php echo $str['id']; ?>',
                    single: 1,
                    maximizable: 1
                });
                
            }
        });
        
        x.add_plugin('docsfigs', {
            show: function(){

                x.popup({
                    width: 600,
                    height: 600,
                    title: 'Figures',
                    url: '<?php echo XOOPS_URL; ?>/modules/docs/figures.php?id=<?php echo $str['id']; ?>',
                    single: 1,
                    maximizable: 1
                });

            }
        });

        x.add_plugin('docstoc', {
            insert: function(){

                x.insertText('[TOC]');

            }
        });

        x.add_button('rdrefs',{
           name : 'rdrefs', 
           title : 'Add notes & references',
           alt : 'Notes & References',
           cmd : 'show',
           plugin : 'docsrefs',
           row: 'top',
           icon: '<?php echo XOOPS_URL; ?>/modules/docs/images/notes_plugin.png'
        });

        x.add_button('rdfigs',{
           name : 'rdfigs',
           title : 'Add figures',
           alt : 'Add Figures',
           cmd : 'show',
           plugin : 'docsfigs',
           row: 'top',
           icon: '<?php echo XOOPS_URL; ?>/modules/docs/images/figures_plugin.png'
        });

        x.add_button('rdtoc',{
           name : 'rdtoc',
           title : 'Add Table Of Contents',
           alt : 'Add Table Of Contents',
           cmd : 'insert',
           plugin : 'docstoc',
           row: 'top',
           icon: '<?php echo XOOPS_URL; ?>/modules/docs/images/toc16.png'
        });
    <?php
        } elseif(substr($ret['path'], -9)=='hpage.php') { ?>

            x.add_plugin('docsresindex', {
                init: function(x){
                    var options = '<li><a href="javascript:;" onclick="'+x.name+'.insertText(\'[RD_RESINDEX]\');"><?php _e('All Documents','docs'); ?></a></li>';
                    options += '<li><a href="javascript:;" onclick="'+x.name+'.insertText(\'[RD_FEATINDEX]\');"><?php _e('Featured Documents','docs'); ?></a></li>';
                    
                    x.dropdown.add_dropdown(x,{
                        name : 'rdresindex',
                        content : options,
                        width: '200px'
                    });
                },
                show: function(x){
                    x.dropdown.show_menu(x, 'rdresindex');
                }
            });

            x.add_button('rdresindex',{
               name : 'rdresindex',
               title : 'Add Documents Index',
               alt : 'Add Documents Index',
               cmd : 'show',
               plugin : 'docsresindex',
               row: 'top',
               type : 'dropdown',
               icon: '<?php echo XOOPS_URL; ?>/modules/docs/images/index16.png'
            });

        <?php

        }
    }
    
    /**
    * Add buttons to TinyMCE Editor
    */
    public function eventRmcommonExmcodePlugins($plugins){
        global $xoopsModule;
        if ($xoopsModule->dirname()!='docs') return $plugins;
        $plugins .= ',docsrefs,docsfigs,docstoc,docsresindex';
        return $plugins;
    }
    /**
    * Add buttons to EXMCode Editor
    */
    public function eventRmcommonExmcodeButtons($buttons){
        global $xoopsModule;
        if ($xoopsModule->dirname()!='docs') return $buttons;
        $buttons .= ',rdrefs,rdfigs,rdtoc,rdresindex';
        return $buttons;
    }
    /**
    * Add options to simple editor
    */
    public function eventRmcommonSimpleEditorPlugins($plugins, $name){
        global $xoopsModule;
        
        if(!$xoopsModule || $xoopsModule->dirname()!='docs') return $plugins;
        //if(!defined('RMCLOCATION') || RMCLOCATION!='homepage') return $plugins;
        
        RMTemplate::get()->add_script('../include/js/editor_options.js');
        RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.fieldselection.js');
        $plugins[] = '<a href="javascript:;" onclick="rd_ep_insert(\'[RD_RESINDEX]\',\''.$name.'\');" title="'.__('All Documents','docs').'"><img src="../images/index16.png" alt="'.__('All Documents','docs').'" /></a>';
        $plugins[] = '<a href="javascript:;" onclick="rd_ep_insert(\'[RD_FEATINDEX]\',\''.$name.'\');" title="'.__('Featured Documents','docs').'"><img src="../images/resfeatured.png" alt="'.__('Featured Documents','docs').'" /></a>';
        return $plugins;
    }
    /**
    * Add options to html editor
    */
    public function eventRmcommonHtmlEditorPlugins($plugins, $name){
        return self::eventRmcommonSimpleEditorPlugins($plugins, $name);
    }
    /**
    * Add new code converter to decode [TOC], [RDRESOURCE] and [RDFEATURED]
    */
    public function eventRmcommonTextTodisplay($text, $source){
        global $xoopsModule;
        
        if(!$xoopsModule || $xoopsModule->dirname()!='docs' || defined('RD_NO_FIGURES'))
            return $text;
        
        if(function_exists('xoops_cp_header')) return $text;
        
        // If home page contains some index
        include_once XOOPS_ROOT_PATH.'/modules/docs/include/tc_replacements.php';
        $text = preg_replace_callback("/\[RD_RESINDEX\]/", 'generate_res_index', $text);
        $text = preg_replace_callback("/\[RD_FEATINDEX\]/", 'generate_res_index', $text);
        
        // Build notes
        $pattern = "/\[note:(.*)]/sU";
        $replacement = "rd_build_note";
        $text = preg_replace_callback($pattern, $replacement, $text);
        
        // Build figures
        $pattern = "/\[figure:(.*)]/sU";
        $replacement = "rd_build_figure";
        $text = preg_replace_callback($pattern, $replacement, $text);
        
        // Build TOC
        $text = preg_replace_callback("/\[TOC\]/", 'rd_generate_toc', $text);
        
        return $text;
        
    }

}
