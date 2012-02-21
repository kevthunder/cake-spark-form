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
			$clone.removeClass('modelLine').find('input').attr('disabled',false);
			count = $('.line:not(.modelLine)',$table).length;
			$clone.html($clone.html().replace(/---i---/g,count));
			$last.after($clone);
			return false;
		});
		
		$('.MultipleTable input[spc=deleteInput]').each(function (){
			if($(this).val() == 1){
				$line = $(this).closest('.line');
				multipleDeleteRow($line);
			}
		});
	});
	window.multipleDeleteRow = function($line){
		$line.addClass('deletedLine').find('input:not([spc=keyInput],[spc=deleteInput])').attr('disabled',true);
		$('input[spc=deleteInput]',$line).val(1);
	};
})( jQuery );