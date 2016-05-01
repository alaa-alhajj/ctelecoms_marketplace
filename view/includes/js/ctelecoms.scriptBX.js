$title=$('.slider_content h1,.slider_content h2,.slider_content h3,.slider_content h4,.slider_content h5,.slider_content h6');
$text=$('.slider_content p,.slider_content span,.slider_content div,.slider_content ul,.slider_content ol');
$button=$('.slider_content a,.slider_content button,.slider_content input');



$('#bxslider').bxSlider({auto:true,pause:10000,autoHover:true,preloadImages: 'visible',onSlideBefore: function(){
        resetBx($title,$text,$button);
        
        },onSlideAfter: function(){
            generateBxAnimation($title,$text,$button);
        
    },onSliderLoad:function(){
        generateBxAnimation($title,$text,$button);
    }
    });

function generateBxAnimation(title,text,button)
{
   animation1 (title,text,button);
}
function animation1 (title,text,button)
{
    setTimeout(function(){
        title.addClass('displayBlock');
        text.addClass('displayBlock');
        button.addClass('displayBlock');
        $('.slider_content').addClass('animated fadeInUp');
    },500);

   
    
}
function animation2 (title,text,button)
{
    setTimeout(function(){
        title.addClass('displayBlock');
        text.addClass('displayBlock');
        button.addClass('displayBlock');
        $('.slider_content').addClass('animated fadeIn');
    },1000);

}
function animation3 (title,text,button)
{
    setTimeout(function(){
        title.addClass('displayBlock');
        text.addClass('displayBlock');
        button.addClass('displayBlock');
        $('.slider_content').addClass('animated bounceInRight');
    },1000);

}
function resetBx(title,text,button)
{
    $('.slider_content').removeClass('animated');
    $('.slider_content').removeClass('fadeIn');
    $('.slider_content').removeClass('fadeInUp');
    $('.slider_content').removeClass('bounceInRight');
  
    
    title.removeClass('displayBlock');
    text.removeClass('displayBlock');
    button.removeClass('displayBlock');
}