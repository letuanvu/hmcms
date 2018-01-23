function getParameterByName(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function calcHeight(){
	$('.live_edit_loading').hide();
	var the_height=document.getElementById('fullheight_iframe').contentWindow.document.body.scrollHeight;
	document.getElementById('fullheight_iframe').height=the_height;
}

$(document).ready(function(){

	$('live_text').parent().attr('href','#').css('position','relative');
	$('live_textarea').parent().attr('href','#').css('position','relative');
	$('live_editor').parent().attr('href','#').css('position','relative');
	$('live_multiimage').parent().attr('href','#').css('position','relative');
	$('live_ahref').parent().attr('href','#').css('position','relative');
	$('live_image').parent().attr('href','#').css('position','relative');
	$('live_option').parent().attr('href','#').css('position','relative');
	$('live_menu').parent().attr('href','#').css('position','relative');
	
	var loading_html = '<div class="cssload-preloader cssload-loading"> <span class="cssload-slice"></span> <span class="cssload-slice"></span> <span class="cssload-slice"></span> <span class="cssload-slice"></span> <span class="cssload-slice"></span> <span class="cssload-slice"></span> </div>';

	var live_editing = getParameterByName('live_editing');
	if(live_editing!=''){
		var option_tag = $('*[data-option_name='+live_editing+']');
		if (option_tag.length) {
			$('html, body').animate({
				scrollTop: $('*[data-option_name='+live_editing+']').offset().top - 40
			}, 100);
		}
	}

	$('body').append('<div class="hm_live_edit_bg"><div class="hm_live_edit_popup"></div></div>');
	var height = $(document).height();
	$('.hm_live_edit_bg').height($(document).height());

	$('live_text live_edit_btn').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<div class="live_edit_loading">'+loading_html+'</div><iframe id="live_edit_iframe" src="/admin/?run=live_edit.php&live_edit=on&type=text&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, 100);
	});

	$('live_textarea live_edit_btn').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<div class="live_edit_loading">'+loading_html+'</div><iframe id="live_edit_iframe" src="/admin/?run=live_edit.php&live_edit=on&type=textarea&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, 100);
	});

	$('live_editor live_edit_btn').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<div class="live_edit_loading">'+loading_html+'</div><iframe id="live_edit_iframe" src="/admin/?run=live_edit.php&live_edit=on&type=editor&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, 100);
	});

	$('live_multiimage live_edit_btn').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<div class="live_edit_loading">'+loading_html+'</div><iframe id="live_edit_iframe" src="/admin/?run=live_edit.php&live_edit=on&type=multiimage&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, 100);
	});

	$('live_image live_edit_btn').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<div class="live_edit_loading">'+loading_html+'</div><iframe id="live_edit_iframe" src="/admin/?run=live_edit.php&live_edit=on&type=image&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, 100);
	});

	$('live_ahref live_edit_btn').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<div class="live_edit_loading">'+loading_html+'</div><iframe id="live_edit_iframe" src="/admin/?run=live_edit.php&live_edit=on&type=ahref&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, 100);
		return false;
	});

	$('live_option').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<div class="live_edit_loading">'+loading_html+'</div><iframe id="live_edit_iframe" src="/admin/?run=live_edit.php&live_edit=on&type=option&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, 100);
		return false;
	});

	$('.hm_live_edit_bg').click(function(){
		$('.hm_live_edit_bg').hide();
		$('.hm_live_edit_popup').hide();
		var option_name = $('.hm_live_edit_bg').attr('option_name');
		window.location.href = window.location.pathname+'?live_editing='+option_name;
	});
	
	$('live_menu .hm_live_option_form .form-control').change(function(){
		$(this).parents('form').submit();
	});


});
