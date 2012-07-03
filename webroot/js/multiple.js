(function( $ ) {
	$(function(){
		$('.MultipleTable .btDelete').live('click',function (){
			$line = $(this).closest('.line');
			multipleDeleteRow($line);
			return false;
		});
		$('.MultipleTable .btAdd').live('click',function (){
			$table = $(this).closest('.MultipleTable');
			$model = $('.modelLine',$table);
			$last = $('.line:last',$table);
			$clone = $model.clone();
			$clone.removeClass('modelLine').find('input, textarea, select').attr('disabled',false);
			count = $('.line:not(.modelLine)',$table).length;
			$clone.html($clone.html().replace(/---i---/g,count));
			$last.after($clone);
			return false;
		});
		
		$('.MultipleTable input, .MultipleTable textarea, .MultipleTable select').filter('[spc=deleteInput]').each(function (){
			if($(this).val() == 1){
				$line = $(this).closest('.line');
				multipleDeleteRow($line);
			}
		});
	});
	window.multipleDeleteRow = function($line){
		$line.addClass('deletedLine').find('input, textarea, select').filter(':not([spc=keyInput],[spc=deleteInput])').attr('disabled',true);
		$('input, textarea, select',$line).filter('[spc=deleteInput]').val(1);
	};
})( jQuery );