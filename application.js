/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$(document).ready(function(){
   
   setTimeout(function(){
       $(".flash").fadeOut(500);
   }, 1000);
   
   $(".comments_link").click(function(){
       $(this).next("div").slideToggle();
   });
   
   if($(".comments").height() <= 200){
       $(".comments").show();
   }
      
});

