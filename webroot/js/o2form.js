$(function(){
	$('.disable_checkbox').change(function(){
		var input_div = $(this).closest('div.input');
		var inputs = input_div.find('input:not(.disable_checkbox), select, textarea')
		if($(this).attr('checked')){
			inputs.attr('disabled','disabled');
		}else{
			inputs.attr('disabled','');
		}
	});
	$('.disable_checkbox').change();
});
