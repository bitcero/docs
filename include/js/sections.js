$(document).ready(function(){
       
    $("ol.sec_connected").nestedSortable({
			disableNesting: 'no-nest',
			forcePlaceholderSize: true,
			handle: 'div',
			helper:	'clone',
			items: 'li',
			maxLevels: 10,
			opacity: .6,
			placeholder: 'placeholder',
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div'
		});
    
    $("#start-sortable").click(function(){
        $(this).fadeOut('fast');
        $("#table-sections").fadeOut('fast', function(){
            $("#sections-sortable").fadeIn('fast');
        });
    });
    
    $(".cancel-sortable").click(function(){
        $("#sections-sortable").fadeOut('fast', function(){
            $("#table-sections").fadeIn('fast');
        });
        $("#start-sortable").fadeIn('fast');
    });
    
    $(".save-sortable").click(function(){

        $("#rd-wait").fadeIn('fast');
        s = $('ol.sec_connected').nestedSortable('serialize');
        params = {
            items: s,
            action: 'savesort',
            'XOOPS_TOKEN_REQUEST':$('#XOOPS_TOKEN_REQUEST').val()
        };
        
        $.post("sections.php", params, function(data){
            
            if(data.error==1){
                alert(data.message);
            }
            
            window.location.href = data.url;
            
        }, 'json');
        
    });
    
});
