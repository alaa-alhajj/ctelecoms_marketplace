$title=$('.slider_content h1,.slider_content h2,.slider_content h3,.slider_content h4,.slider_content h5,.slider_content h6');
$text=$('.slider_content span,.slider_content div,.slider_content ul,.slider_content ol');
$button=$('.slider_content a,.slider_content button,.slider_content input');
var animate_ord = 0;

$(document).ready(function (){
    
    $(".scroll-top").click(function() {
      $("html, body").animate({ scrollTop: 0}, 'slow');
      return false;
    });
    
    $( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd'});
    
    //---------------- Build slider 1 (Highlight) --------------------------
     var slider=$('.bxslider1').bxSlider(
             {
                mode: 'fade',
                infiniteLoop: true,
                captions: false,
                auto:true,
                pause:5000,
                controls:false,
                autoHover:false,
                onSlideBefore: function($slideElement, oldIndex, newIndex){
			resetBx($title,$text,$button);
                         },
                onSlideAfter: function($slideElement, oldIndex, newIndex){
                        generateBxAnimation($title,$text,$button);
                        },
                onSliderLoad:function(currentIndex){
                        $('.bx-controls-direction a').hide();
                        $('.bx-wrapper').hover(
                            function () { $('.bx-controls-direction a').fadeIn(300); },
                            function () { $('.bx-controls-direction a').fadeOut(300); }
                        );
                        generateBxAnimation($title,$text,$button);
                    }
             } 
            );
         //continue auto loop after clicking on bullets
        $('.bx-pager-item a').click(function(e){
            var i = $(this).attr('data-slide-index');
            slider.goToSlide(i);
            slider.stopAuto();
            setTimeout(function(){
               slider.startAuto(); 
            },100);
            return false;
        });
        
    
    onkeydown = function(e){
         var x = e.keyCode;
            if (x === 27) { 
                $('.register-div').fadeOut(1000);
            }
    }; 
    

    //image effect 
    $('.blur-img').mouseenter(function(){
            $(this).animo('blur', {duration: 3, amount: 2});

    }).mouseout(function(){
            $(this).animo('focus');
    });

    //hover on course
    //----------------------------------------------------------
     checkHoverAndMouseLeaveCourseItem();
     
    //-----------------------------------------------------------
    // Trainer slider
    $("#cslide-slides").cslide();
    

    //----------------------------------------------------
    /**************************** Inner Pages Script ****************************/
    // courses page script 
    checkHoverAndMouseLeaveInnerPageCourseItem();
    
    //*************** Set First project active in projects sections **********//
    $('.proj-btn1').addClass(' active ');
    
    //********************** Get count of Projects ***************/
    var projList = document.getElementsByClassName('proj-details');
    $('.max-high-items').val(projList.length);
    
}); //end ready function

var repeat=true;
$(window).scroll(function(){
     var bool=$('.counter').visible();
     if(bool && repeat){
            viewCounter($('.counter').html(),50);
            repeat=false;
     }
});

function checkHoverAndMouseLeaveCourseItem(){
        $(".course .effeckt-caption").hover(function(){
            var id=$(this).attr('id');
            $(".sub-title-div"+id+" .sub-title").fadeIn(100);
        });

        $(".course .effeckt-caption").mouseleave(function(){
            var id=$(this).attr('id');
            $(".sub-title-div"+id+" .sub-title").fadeOut(100);
        });
}

function checkHoverAndMouseLeaveInnerPageCourseItem(){
    $(".item-img").hover(function(){
        var id=$(this).attr('id');
        $(".course-item"+id+" .item-title"+id+" .title").fadeIn(100);
    });
    
    $(".item-img").mouseleave(function(){
        var id=$(this).attr('id');
        $(".course-item"+id+" .item-title"+id+" .title").fadeOut(100);
    });
} 
    
//--------------- slider function ------------------- 
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

function animation1 (title,text,button)
{
    setTimeout(function(){
       title.show('fade',1000); 
       text.show('fade',1000);
       button.show('fade',1000);
    },200);
    
}

function animation2 (title,text,button)
{
    setTimeout(function(){
        title.show('slide',{direction : 'left'},1000);
        text.show('slide',{direction : 'left'},1000);
        button.show('slide',{direction : 'left'},1000);
    },200);

}

function animation3 (title,text,button)
{
    setTimeout(function(){
        title.fadeIn("slow");
        text.fadeIn("slow");
        button.fadeIn("slow");
    },200);

}

function resetBx(title,text,button){
    title.hide();
    text.hide();
    button.hide();
}
//--------------------------------------------
function hideRegisterDiv(){
     $('.register-div').fadeOut(1000);
}

//-------------------------------------------

function viewCounter(maxValue,delay){
    count=0;
    setInterval(function() { count++; if (count <= maxValue) { $(".counter").html(count); } }, delay);    
}

//=============================== Projects section Script =========================\\

var p = setInterval("changeHigh(0)",6000);

function highProcess(high_id){
        var id=high_id;
       
        $('.projects-show .project-details .proj-details').fadeOut();
        $('.projects-show .project-details .proj-details'+id).fadeIn(2000);  
        
        $('.projects-show .projects-btns .proj-btn').removeClass('active');
	$('.projects-show .projects-btns .proj-btn'+id).addClass('active');
}

function changeHigh(high_id){
    var max_high_items=$('.max-high-items').val();
    var curr_high_item=$('.curr-high-item').val();
    
    
    if(high_id != 0){
        highProcess(high_id);
        curr_high_item=high_id;
    }else{
        curr_high_item++;
        if(curr_high_item > max_high_items){ 
            curr_high_item=1;
        }
        highProcess(curr_high_item);        
    }
    clearTimeout(p);
    p=setInterval("changeHigh(0)",6000);
    $('.curr-high-item').attr('value',curr_high_item);    
}
