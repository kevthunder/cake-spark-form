
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
			$('.btMore',this).live('click.multiple',function (){
				$(this).multiple('toggleMore');
				return false;
			});
			
			$(this).multiple('controls','input, textarea, select').filter('[spc=deleteInput]').each(function (){
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
	 controls:function(selector,parent){
      var $container = $(this);
      if(!parent) parent = $container;
      var $match = $(selector,parent);
      return $match.filter(function(i,elem){
        return $(elem).multiple('getContainer')[0] == $container[0];
      });
   },
   getLine : function(key){
      var $line, $table;
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
				var activeCount = $table.multiple('controls','.line:not(.modelLine, .deletedLine)').length;
				if(!$line && arguments.length == 1){
					if($('.line[spc=keyInput][val='+key+']').length){
						$line = $('.line[spc=keyInput][val='+key+']');
					}
					if(!$line && !isNaN($key) && key < activeCount){
						$line = $table.multiple('controls','.line:not(.modelLine, .deletedLine)')[key];
					}
				}
      }
      return $line;
   },
	 deleteRow : function(){
      return this.each(function(key){
        var $line = $(this).multiple('getLine',key);

				if($line){
          var $table = $line.multiple('getContainer');
          var $lineGroup;
          if($line.attr('rel')){
            $lineGroup = $table.multiple('controls','[rel='+$line.attr('rel')+']');
          }else{
            $lineGroup = $line;
          }
					if($table.attr('max') && activeCount-1 < $table.attr('max')){
						$table.multiple('controls','.btAdd').removeClass('.disabled');
					}
					$lineGroup.addClass('deletedLine').find('input, textarea, select').filter(':not([spc=keyInput],[spc=deleteInput])').attr('disabled',true);
					$('input, textarea, select',$lineGroup).filter('[spc=deleteInput]').val(1);
				}
      });
	 },
   toggleMore : function(){
      return this.each(function(key){
        var $line = $(this).multiple('getLine',key);
				if($line){
          var $table = $line.multiple('getContainer');
          var $lineGroup;
          if($line.attr('rel')){
            $lineGroup = $table.multiple('controls','[rel='+$line.attr('rel')+']');
          }else{
            $lineGroup = $line;
          }
          var $collapsible = $table.multiple('controls','.collapsible',$lineGroup).add($lineGroup.filter('.collapsible'));
          var $btMore = $table.multiple('controls','.btMore',$lineGroup);
          if($collapsible.hasClass('collapsed')){
            $collapsible.removeClass('collapsed');
            $btMore.addClass('open');
          }else{
            $collapsible.addClass('collapsed');
            $btMore.removeClass('open');
          }
        }
        
      });
   },
	 nbRows : function(nb){
		var $table = this.multiple('getContainer');
		$table = $table.eq(0);
		var activeCount = $table.multiple('controls','.line:not(.modelLine, .deletedLine)').length;
		if(arguments.length == 1 && !isNaN(nb)){
			if(activeCount < nb){
				$table.multiple('addRow',nb-activeCount);
			}else if(activeCount > nb){
				$table.multiple('controls','.line:not(.modelLine, .deletedLine)').slice(nb).multiple('deleteRow');
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
					var activeCount = $table.multiple('controls','.line:not(.modelLine, .deletedLine)').length;
					if($table.attr('max') && activeCount+1 >= $table.attr('max')){
						$table.multiple('controls','.btAdd').addClass('.disabled');
						if(activeCount >= $table.attr('max')){
							return false
						}
					}
					var $model = $table.multiple('controls','.modelLine');
					var $last = $table.multiple('controls','.line, .subline').last();
					var $clone = $model.clone();
					var count = $table.multiple('controls','.line:not(.modelLine)').length;

					$clone.each(function(){
            $(this).removeClass('modelLine').find('input, textarea, select').attr('disabled',false);
          });
          var $replace = '---'+String.fromCharCode(105 + parseInt($table.attr("depth")))+'---';
					$last.after($('<div/>').append($clone).html().replace(new RegExp($replace, "g"),count));
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