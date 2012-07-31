
(function( $ ){

  var methods = {
     init : function( options ) {

       return this.each(function(){
         
         var $this = $(this);
         var data = $this.data('multiple');
         
         // If the plugin hasn't been initialized yet
         if ( ! data ) {
			
			$('.btAdd',this).live('click.multiple',function (){
				$(this).multiple('addRow');
				return false;
			});
			$('.btDelete',this).live('click.multiple',function (){
				$(this).multiple('deleteRow');
				return false;
			});
			
			$('input, textarea, select',this).filter('[spc=deleteInput]').each(function (){
				if($(this).val() == 1){
					$line = $(this).closest('.line');
					$line.multiple('deleteRow');
				}
			});

			$(this).data('multiple', {
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
	 getContainer:function(){
		if($(this).is(".MultipleTable")){
			return $(this);
		}else{
			return $(this).closest('.MultipleTable');
		}
	 },
	 deleteRow : function(){
        return this.each(function(key){
			var $line;
			if($(this).is(".line")){
				$line = $(this);
			}
			if(!$line && $(this).closest('.line').length && $(this).closest('.MultipleTable').length){
				$line = $(this).closest('.line');
			}
			if(!$line){
				$table = $(this).multiple('getContainer');
			}else{
				$table = $line.multiple('getContainer');
			}
			if($table && $table.length){
				var activeCount = $('.line:not(.modelLine, .deletedLine)',$table).length;
				if(!$line && arguments.length == 1){
					if($('.line[spc=keyInput][val='+key+']').length){
						$line = $('.line[spc=keyInput][val='+key+']');
					}
					if(!$line && !isNaN($key) && key < activeCount){
						$line = $('.line:not(.modelLine, .deletedLine)',$table)[key];
					}
				}
				if($line){
					if($table.attr('max') && activeCount-1 < $table.attr('max')){
						$('.btAdd',$table).removeClass('.disabled');
					}
					$line.addClass('deletedLine').find('input, textarea, select').filter(':not([spc=keyInput],[spc=deleteInput])').attr('disabled',true);
					$('input, textarea, select',$line).filter('[spc=deleteInput]').val(1);
				}
			}
		});
	 },
	 nbRows : function(nb){
		var $table = this.multiple('getContainer');
		$table = $table.eq(0);
		var activeCount = $('.line:not(.modelLine, .deletedLine)',$table).length;
		if(arguments.length == 1 && !isNaN(nb)){
			if(activeCount < nb){
				$table.multiple('addRow',nb-activeCount);
			}else if(activeCount > nb){
				$('.line:not(.modelLine, .deletedLine)',$table).slice(nb).multiple('deleteRow');
			}
		}
		return activeCount;
	 },
	 addRow : function(nb){
		var $table = this.multiple('getContainer');
		if($table && $table.length){
			return $table.each(function(key){
				if(arguments.length == 0 || isNaN(nb)){
					nb = 1;
				}
				var $table = $(this);
				for(var i=0;i<nb;i++){
					var activeCount = $('.line:not(.modelLine, .deletedLine)',$table).length;
					if($table.attr('max') && activeCount+1 >= $table.attr('max')){
						$('.btAdd',$table).addClass('.disabled');
						if(activeCount >= $table.attr('max')){
							return false
						}
					}
					var $model = $('.modelLine',$table);
					var $last = $('.line:last',$table);
					var $clone = $model.clone();
					$clone.removeClass('modelLine').find('input, textarea, select').attr('disabled',false);
					var count = $('.line:not(.modelLine)',$table).length;
					$clone.html($clone.html().replace(/---i---/g,count));
					$last.after($clone);
				}
			});
		}
		return false;
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

  
	$(function(){
		$('.MultipleTable').multiple();
	});
  
})( jQuery );