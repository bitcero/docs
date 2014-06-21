/**
* $Id$
*/

$(document).ready(function(){
    
    $("#bulk-top").change(function(){
        
        $("#bulk-bottom").val($(this).val());
        
    });
    
    $("#bulk-bottom").change(function(){
        
        $("#bulk-top").val($(this).val());
        
    });
    
    $("#the-op-top").click(function(){
        $("#frm-resources").submit();
    });
    
});

function rd_check_delete(id, form){
    
    if(id<=0) return false;
    
    $("#"+form+" input[type=checkbox]").removeAttr("checked");
    $("#item-"+id).attr("checked",'checked');
    
    $("#bulk-top").val('delete');
    
    before_submit(form);
    
}

function before_submit(form){
    
    var eles = $("#"+form+" input[name='ids[]']");
    var go = false;

    for(i=0;i<eles.length;i++){
        if ($(eles[i]).is(":checked"))
            go = true;
    }
    
    if (!go){
        alert(rd_select_message);
        return false;
    }
    
    if ($("#bulk-top").val()=='delete'){
        if (confirm(rd_message))
            $("#"+form).submit();
    } else {
        $("#"+form).submit();
    }
}

function rd_show_figure_editor(){
        
    $("#rd-figures-editor").show('fast');
}