// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

name = get('name');
var exmPopup = window.parent[name];

var editor = jQuery.extend({
    
    insertNote: function(id){
        exmPopup.insertText('[note:'+id+']');
        exmPopup.closePopup();
    },
    
    insertFigure: function(id){
        exmPopup.insertText('[figure:'+id+']');
        exmPopup.closePopup();
    },
    
    close: function(){
        exmPopup.closePopup();
    }
    
});
