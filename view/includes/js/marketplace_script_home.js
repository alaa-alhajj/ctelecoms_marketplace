$(document).ready(function() {
 
   
            $title = $('.slider-content .contents h1,.slider-content .contents h2,.slider-content .contents h3,.slider-content .contents h4,.slider-content .contents h5,.slider-content .contents h6');
            $text = $('.slider-content .contents p,.slider-content .contents span,.slider-content .contents div,.slider-content .contents ul,.slider-content .contents ol');
            $button = $('.slider-content .contents a,.slider-content .contents button,.slider-content .contents input');
            $img = $('.slider-content .contents img');

            $('.bxslider').bxSlider({auto: true,  controls: false, pause: 10000, autoHover: true, onSlideBefore: function() {
                    resetBx($title, $text, $button,$img);

                }, onSlideAfter: function() {
                    generateBxAnimation($title, $text, $button, $img);

                }, onSliderLoad: function() {
                    generateBxAnimation($title, $text, $button, $img);
                }
            });

            function generateBxAnimation(title, text, button, img)
            {
                animation1(title, text, button, img);
            }
            function animation1(title, text, button, img)
            {
                setTimeout(function() {
                    title.addClass('displayBlock');
                    text.addClass('displayBlock');
                    button.addClass('displayBlock');
                    img.addClass('displayBlock');
                    title.addClass('animated fadeInDown');
                    $('.slider-content .contents img,.slider-content .contents .prices').addClass('animated fadeInDown');
                    button.addClass('animated fadeInUp');
                }, 200);



            }

            function resetBx(title, text, button, img)
            {
               title.removeClass('animated fadeInDown');
                  $('.slider-content .contents img,.slider-content .contents .prices').removeClass('animated fadeInDown');
               button.removeClass('animated fadeInUp');

                title.removeClass('displayBlock');
                text.removeClass('displayBlock');
                button.removeClass('displayBlock');
                img.removeClass('displayBlock');
            }
    $('body').on('click', '.addToCompare', function() {
        $this = $(this);
        $this.find(".icon-style").addClass("fa-spin");
    });

    $(window).scroll(function(event) {
        var scroll = $(window).scrollTop();
        //console.log(scroll);
    });

    function animateMydiv($this_icon) {
        $this_icon.show();
        $this_icon.animate({'left': 115 + 'px'}, 1000, function() {
            $this_icon.hide();
            $this_icon.animate({'left': '-20px'}, 10, function() {
            });
            animateMydiv($this_icon);
        });
    }
    $('body').on('click', '.addTocart', function() {
        $this_icon = $(this).find(".shopCartIcon i");
        animateMydiv($this_icon);
        //  $(this).find(".shopCartIcon").removeClass("shopCartIcon");
    });

    $('body').on('click', '.more-slide', function() {
        $thisButton = $(this);
        $this = $(this).parents().find(".slider-content");
        $this.addClass("slider-content768");
        $this.children().fadeIn();
        $thisButton.hide();
        //  alert($this);
    });
});