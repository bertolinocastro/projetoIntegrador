jQuery(window).load(function () {
    $(".carregamento").delay(2000).fadeOut("slow"); //retire o delay quando for copiar!
    $(".cntainer").delay(2000).fadeIn("slow");
});


function menu(){
    if(document.getElementById("menu-mobile").style.display == "block"){
      document.getElementById("menu-mobile").style.display="none"; 
      document.getElementById("corpo").style.overflow="inherit";
    }else{
      document.getElementById("menu-mobile").style.display="block";
      document.getElementById("corpo").style.overflow="hidden";
    }
    
  };
$(document).ready(function(){
    $( ".c-hamburger" ).click(function() {
      $(".painel_adm").toggleClass("fechado");
    });
    $(".escolh1").click(function(){
        if($(".form2").hasClass("ativo")){
            $(".form1").addClass("ativo");
            $(".escolh1").addClass("active");
            $(".escolh2").removeClass("active");
            $(".form2").removeClass("ativo");
            $(".form2").addClass("desligado");
            $(".form1").removeClass("desligado");
        }
    });
    $(".escolh2").click(function(){
        if($(".form1").hasClass("ativo")){
            $(".form2").addClass("ativo");
            $(".escolh2").addClass("active");
            $(".escolh1").removeClass("active");
            $(".form1").removeClass("ativo");
            $(".form1").addClass("desligado");   
            $(".form2").removeClass("desligado");
       }
    });
    $(".op-select").click(function() {
      $(this).toggleClass("cor");
    });
   
});

