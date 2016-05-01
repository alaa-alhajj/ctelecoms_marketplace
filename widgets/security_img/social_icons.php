<?
	$face = str_replace('http://','',lookupField('site_settings','id','value',6));
	$twitter = str_replace('http://','',lookupField('site_settings','id','value',7));
	$youtube = str_replace('http://','',lookupField('site_settings','id','value',8));
	
	if($face){$face_link=' href="http://'.$face.'" target="_blank" '; }else{$face_link=' href="#" ';}
	if($twitter){$twitter_link=' href="http://'.$twitter.'" target="_blank" ';}else{$twitter_link=' href="#" ';}
	if($youtube){$youtube_link=' href="http://'.$youtube.'" target="_blank" '; }else{$youtube_link=' href="#" ';}
	
?>
<div class='social_icons' dir='<?=_DIR?>'>
	<div class='social_title'><?=Get_social?></div>
	<div class='social_links'>
		<a <?=$face_link?> ><img src='<?=_PREF?>wid/social_icons/images/face_icon.png' /></a>
		<a <?=$twitter_link?> ><img src='<?=_PREF?>wid/social_icons/images/tweet_icon.png' /></a>
		<a <?=$youtube_link?> ><img src='<?=_PREF?>wid/social_icons/images/you_icon.png' /></a>
	</div>
</div>