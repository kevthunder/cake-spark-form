(function( $ ) {
	$(function(){
		$('.MultipleTable .btDelete').live('click',function (){
			$line = $(this).closest('.line');
			$line.addClass('deletedLine').find('input:not([spc=keyInput],[spc=deleteInput])').attr('disabled',true);
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
	})
})( jQuery );