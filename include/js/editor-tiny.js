// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

var editor = jQuery.extend({
    
    insertNote: function(id){
        ed = tinyMCEPopup.editor;
        ed.execCommand("mceInsertContent", true, '[note:'+id+']');
        tinyMCEPopup.close();
    },
    
    insertFigure: function(id){
        ed = tinyMCEPopup.editor;
        ed.execCommand("mceInsertContent", true, '[figure:'+id+']');
        tinyMCEPopup.close();
    },
    
    close: function(){
        tinyMCEPopup.close();
    }
    
});
