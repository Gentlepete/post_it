/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$(document).ready(function(){
   
   function showNavAndLogin(){
       $("#login_toggle, #nav_toggle").unbind("click");
       if(window.innerWidth <= 1350){
            $("#login_form, #navigation").css("left", "-180px");
            
            $("#login_toggle, #nav_toggle").click(function(){
                var $oldLeft = $(this).css('left');
                var $element = $(this).next("div");
                var $elementWidth = $element.css('width');
                if (!$element.hasClass("shown")){
                    
                    $(this).animate({
                        left: "160px"
                    }, 300);
                    
                    $element.animate({
                        left: "0px"
                    }, 300);
                    
                    
                    $element.addClass("shown");
                }else{ 
                    
                     $(this).animate({
                        left: "-10px"
                    }, 300);
                    
                    $element.animate({
                    left: "-180px"
                    }, 300);

                    $element.removeClass("shown");
                }  
            });
        }else{
            
            $("#login_form, #navigation").css("left", "1%");
        }
   };
   
   setTimeout(function(){
       $(".flash").fadeOut(500);
   }, 1000);
   
   $(".comments_link").click(function(){
       $(this).next("div").slideToggle();
   });
   
   if($(".comments").height() <= 200){
       $(".comments").show();
   }
   showNavAndLogin();
    window.onresize = function(){
        showNavAndLogin();
    };
   
});

