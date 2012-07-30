(function( $ ) {
	$(function(){
		$('.MultipleTable .btDelete').live('click',function (){
			$line = $(this).closest('.line');
			multipleDeleteRow($line);
			return false;
		});
		$('.MultipleTable .btAdd').live('click',function (){
			$table = $(this).closest('.MultipleTable');
			activeCount = $('.line:not(.modelLine, .deletedLine)',$table).length;
			if($table.attr('max') && activeCount+1 >= $table.attr('max')){
				$(this).addClass('.disabled');
				if(activeCount >= $table.attr('max')){
					return false
				}
			}
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
		$table = $($line).closest('.MultipleTable');
		activeCount = $('.line:not(.modelLine, .deletedLine)',$table).length;
		if($table.attr('max') && activeCount-1 < $table.attr('max')){
			$('.btAdd',$table).removeClass('.disabled');
		}
		$line.addClass('deletedLine').find('input, textarea, select').filter(':not([spc=keyInput],[spc=deleteInput])').attr('disabled',true);
		$('input, textarea, select',$line).filter('[spc=deleteInput]').val(1);
	};
})( jQuery );

/*
(function( $ ){

  var methods = {
     init : function( options ) {

       return this.each(function(){
         
         var $this = $(this);
         var data = $this.data('multiple');
         
         // If the plugin hasn't been initialized yet
         if ( ! data ) {
         
           

           $(this).data('multiple', {
               target : $this,
               multiple : multiple
           });

         }
       });
     },
     destroy : function( ) {

       return this.each(function(){

         var $this = $(this);
         var data = $this.data('multiple');

         // Namespacing FTW
         $(window).unbind('.multiple');
         $this.removeData('multiple');

       })

     },
	 deleteRow : function(){
        return this.each(function(){
			var $line;
			if($(this).is(".line")){
				$line = $(this);
			}
			if($(this).closest('.line').length && $(this).closest('.MultipleTable').length){
				
			}
		}
	 }
  };

  $.fn.multiple = function( method ) {
    
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.multiple' );
    }    
  
  };

})( jQuery );*/