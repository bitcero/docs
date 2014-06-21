$(document).ready(function(){
    $(".note-link").click(function(){
        
        var id = $(this).attr('href').replace("#note-",'');
        $("#note-"+id).effect("highlight",{}, 5000);
        
    });
});