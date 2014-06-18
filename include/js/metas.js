$(document).ready(function(){
    
    $("a.mw_show_metaname").click(function(){
        $("select#meta-name-sel").hide();
        $("input#meta-name").show();
        $("input#meta-name").focus();
        $("a.rd_show_metaname").hide();
        $("a.rd_hide_metaname").show();
    });
    
    $("a.rd_hide_metaname").click(function(){
        $("input#meta-name").hide();
        $("select#meta-name-sel").show();
        $("select#meta-name-sel").focus();
        $("a.rd_hide_metaname").hide();
        $("a.rd_show_metaname").show();
    });
    
    $("input#rd-addmeta").click(function(){
        if ($("select#meta-name-sel").is(":visible")){
            var name = $("select#meta-name-sel").val();
        } else {
            var name = $("input#meta-name").val()
        }
        
        if (name==''){
            $("label#error-metaname").slideDown('fast');
            return;
        }
        
        var value = $("textarea#meta-value").val();
        if (value==''){
            $("label#error-metavalue").slideDown('fast');
            return;
        }
        
        $("label#error-metaname").hide();
        $("label#error-metavalue").hide();
        
        var exit = false;
        if ($("table#metas-container input").length>0){
            $("table#metas-container input").each(function(){
                if ($(this).val()==name){
                    alert('<?php _e('There is already a meta with same name','docs'); ?>');
                    exit = true;
                    return;
                }
            });
        }
        
        if (exit) return;
        
        var count = 0;
        $("table#metas-container input").each(function(){
            id = $(this).attr("id").substring(0, 8);
            if (id=='meta-key'){
                num = $(this).attr("id").replace("meta-key-","");
                if (count <= num)
                    count = num;
            }
        });
        
        count++;
        
        $("table#metas-container").show();
        var html = '<tr class="even">';
        html += '<td valign="top"><input type="text" name="metas['+count+'][key]" id="meta-key-'+count+'" value="'+name+'" class="rd_large" style="width: 95%;" />';
        html += '<a href="javascript:;" onclick="remove_meta($(this));"><?php _e('Remove','docs'); ?></td>';
        html += '<td><textarea name="metas['+count+'][value]" id="metas['+count+'][value]" class="rd_large">'+value+'</textarea></td></tr>';
        $("table#metas-container").append(html);
        
        $("select#meta-name-sel option[selected='selected']").removeAttr('selected');
        $("select#meta-name-sel option[value='']").attr("selected",'selected');
        $("textarea#meta-value").val('');
        $("input#meta-name").val('');
        
        $("tr#row-"+count).effect('highlight',{},'2000');
        
    });
    
    $("#section-url span").click(function(){
        if($("#section-url span input").length>0) return;
        $("#nameid").remove();
        $("#section-url span").html('<input type="text" name="nameid" value="'+$(this).html()+'" size="20" />');
    });
    
});