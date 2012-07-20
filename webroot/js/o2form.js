$(function(){
	$('.disable_checkbox').change(function(){
		var input_div = $(this).closest('div.input');
		var inputs = input_div.find('input:not(.disable_checkbox)[type!="hidden"], select, textarea')
		if(Boolean($(this).attr('checked')) != Boolean($(this).attr('revert'))){
			inputs.attr('disabled','disabled');
			if($(this).attr('hide')){
				inputs.hide();
			}
		}else{
			inputs.removeAttr('disabled');
			if($(this).attr('hide')){
				inputs.show();
			}
			setTimeout(function(){inputs.focus();},10);
		}
	});
	$('.disable_checkbox').change();
});
