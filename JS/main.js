$(document).ready(function(){
    $("#searchBar").hide();
    
    $("#btnsearch").mouseenter(function(){
        $("#searchBar").fadeIn("slow");
    });

    $("#btnsearch").mouseleave(function(){
        $("#searchBar").fadeOut("slow");
    });   
});

lightbox.option({
    resizeDuration: 200,
    wrapAround: true,
    disableScrolling: true,
});