$title=$('.slider_content h1,.slider_content h2,.slider_content h3,.slider_content h4,.slider_content h5,.slider_content h6');
$text=$('.slider_content div,.slider_content p,.slider_content span,.slider_content div,.slider_content ul,.slider_content ol');
$button=$('.slider_content a,.slider_content button,.slider_content input');
var animate_ord = 0;


$('#bxslider').bxSlider({auto:true,pause:8000,autoHover:true,preloadImages: 'visible'
		,onSlideBefore: function(){
			resetBx($title,$text,$button);
        
        },onSlideAfter: function(){
            generateBxAnimation($title,$text,$button);
			
    },onSliderLoad:function(){
		
        generateBxAnimation($title,$text,$button);
    }
    });

function generateBxAnimation(title,text,button){
   
	animate_ord++;
	
	if(animate_ord>3){
		animate_ord=1;
	}
	if(animate_ord==1){
		 animation1 (title,text,button);
	}else if(animate_ord==2){
		 animation2 (title,text,button);
	}else if(animate_ord==3){
		 animation3 (title,text,button);
	}
  
}
function animation1 (title,text,button){   
   // $title.velocity("transition.flipXIn",1500);
   // $text.velocity("transition.swoopIn",1500);   
}
function animation2 (title,text,button){
  //  $title.velocity("transition.fadeIn",1500);
  //  $text.velocity("transition.slideUpBigIn",1500);
}
function animation3 (title,text,button){
	
 //   $title.velocity("transition.slideDownBigIn",1500);
 //   $text.velocity("transition.perspectiveUpIn",1500);
//
}
function resetBx(title,text,button){
    $title.hide();
    $text.hide();
}


