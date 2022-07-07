$(document).ready(function(){
    var ventana = $(window).innerWidth();
    var superCont = $("#supercontenedor").innerWidth();
    $("#span1").html(ventana);
    $("#span2").html(superCont);
    
    
    $(window).resize(function(){
    var ventana = $(window).innerWidth();
    var superCont = $("#supercontenedor").innerWidth();
    $("#span1").html(ventana);
    $("#span2").html(superCont);
    });
    
    });