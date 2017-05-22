if( ! $("html").hasClass("mobile") ){

	js = ["jquery-ui-1.10.3.custom.min.js","jquery-md5.js","jquery.kwicks.js","jquery.ui.sliderbutton.js","jquery.balloon.min.js","users/login.js"];
	$.each(js, function(k,v){
		//$("body").append("<script src='"+$("#path_JScdn").val()+v+"?v=1.1' type='text/javascript'></script>");
		$.getScript($("#path_JScdn").val()+v).fail(function( jqxhr, settings, exception ) {
			console.log(exception)
		});
		/*ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    	ga.src = $("#path_JScdn").val()+v;
    	s = document.getElementsByTagName('script')[k]; s.parentNode.insertBefore(ga, s);*/
    });
	
}