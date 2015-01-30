function is_in_frame()
{
	return (window.self != window.top);
}

var FrontPage_FadeImage_fading = false;
function DoneFadingImage()
{
	FrontPage_FadeImage_fading = false;
}

function ShowFadeImage(obj,name)
{
	var file = 'FrontPage_fadeimage_'+name+'.png';
	if($('FadeImage1').src.indexOf(file)!=-1) return ;
	if(obj) Effect.Pulsate(obj,{pulses:1,duration:.3,from:.5});
	if(FrontPage_FadeImage_fading) return;
	FrontPage_FadeImage_fading = true;
	$('FadeImage2').src = $('FadeImage1').src;
	$('FadeImage1').style.display='none';
	$('FadeImage1').src = tempdir+'images/frontpage/'+file;
	Effect.Appear('FadeImage1', { duration: .5 });
	Effect.Fade('FadeImage2', { duration: .5 });
	setTimeout('DoneFadingImage()',500);
}

var FrontPage_PulseServiceImage_fading = false;
function DonePulsingServiceImage()
{
	FrontPage_PulseServiceImage_fading = false;
}

function PulseServiceImage(obj)
{
	if(FrontPage_PulseServiceImage_fading) return;
	if(obj) Effect.Pulsate(obj,{pulses:1,duration:.5,from:.5});
	FrontPage_PulseServiceImage_fading = true;
	setTimeout('DonePulsingServiceImage()',500);
	
}