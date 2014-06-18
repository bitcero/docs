$(document).ready(function(){
    setTimeout('closeMessages()',10000);
});
        
function closeMessages(){
    var infos = $(".errorMsg");
    if (infos.length>0)
        $(".errorMsg").slideUp('slow');
            
    var infos = $(".infoMsg");
    if (infos.length>0)
        $(".infoMsg").slideUp('slow');
            
}