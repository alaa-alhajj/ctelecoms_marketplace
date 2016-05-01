function getNews(news_id){	
	$('.tab-menu .tab-item').removeClass('selected-item');	
	$('.tab-menu .tab-item'+news_id).addClass('selected-item');	
	$('.tab-block').hide();	
	$('.tab-block'+news_id).show();	
}



$('.document').ready(function(){
	 $('.slider_content').addClass('animated fadeInUp');
	   $( '.slider_content' ).addClass( "big-blue", 2000, "easeOutBounce" );
	$('.search-icon').click(function(){
		$('.search-panel').toggle('fade');
	});
	
	$('.login-btn').click(function(){
		var required_status=true;
		
		$('.form-panel .required').each(function(){
			if($(this).val()==''){
				$(this).css('border','1px solid #BB0F0D');
				$(this).parent().children('.required-msg').show();
				required_status=false;
			}else{
				$(this).css('border','1px solid #bababa');
				$(this).parent().children('.required-msg').hide();
			}
		});
		
		if(required_status==true){
			$('.ajax-effects').show();
			var username= $('.login-username').val();
			var password= $('.login-password').val();
			$.ajax({				
				url: _PREF+'widgets/users/ajax-login.php?username='+username+'&password='+password, 				
				cache: false,  				
				success: function(html){ 
					
					if(html=='success'){
						$('.ajax-effects').hide();
						$('.ajax-success-msg').show();
						setTimeout("$('.login-username').attr('value','');$('.login-password').attr('value','');$('.ajax-success-msg').hide();$('.form-panel').hide('explode','slow');",2000);
						setTimeout("document.location='"+_PREF+"ar/inbox';",3000);
						
					}else if(html=='failed'){
						$('.ajax-effects').hide();
						$('.ajax-error-msg').show();
						setTimeout("$('.login-username').attr('value','');$('.login-password').attr('value','');$('.ajax-error-msg').hide();",3000);
					}
				}  			
			});	
		}
	});
	
	$('.show-login').click(function(){
		$('.mask').fadeIn();
		$('.form-panel').show('explode','slow');
	});
	
	$('.mask').click(function(){
		$('.mask').fadeOut();
		$('.form-panel').hide('explode','slow');
	});
	
	$('.form-panel .close').click(function(){
		$('.mask').fadeOut();
		$('.form-panel').hide('explode','slow');
	});
});


