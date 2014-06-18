$(document).ready(function(){
    $("#form-figures .submit").click(function(){
        var error = false;
        
        <?php if($rmc_config['editor_type']=='tiny'): ?>
        tinyMCE.triggerSave();
        <?php endif; ?>
        
        if($("#desc").val()==''){
            $("#desc").effect('highlight',{},1000);
            $(".error_desc").show();
            error = true;
        }
        
        if($("#content").val()==''){
            $(".error_content").show();
            error = true;
        }
        
        if(error) return;
        
        $("#frm-figs").submit();
        
    });
    
    $("#desc").change(function(){
        if ($(".error_desc").is(':visible'))
            $(".error_desc").hide();
    });
    
    $("#content").change(function(){
        if ($(".error_content").is(':visible'))
            $(".error_content").hide();
    });
    
});
