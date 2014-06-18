// $Id$
// --------------------------------------------------------------
// RapidDocs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function rd_ep_insert(what, name){
    var e = document.getElementById(name);
    var scrollTop = e.scrollTop;
    selected = $("#"+name).getSelection();
    
    if(selected.text==null)
        selected.text = '';
    
    text = what.replace('%replace%', selected.text);

    $("#"+name).replaceSelection(text, true);
            
    var cursorPos = 0;
    if (selected.text==''){
        cursorPos = selected.start + what.indexOf("%replace%");
    } else {
        cursorPos = selected.start + text.length;
    }
        
    cursorPos = cursorPos<0 || cursorPos<=selected.start ? (selected.start + text.length) : cursorPos;
        
    e.selectionEnd = cursorPos;
    e.scrollTop = scrollTop;
    $("#"+name).focus();
}