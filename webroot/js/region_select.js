//could be split between in two : extendedSelect and linkedSelect
(function( $ ) {
	$(function(){
		$(".extendedSelect").each(initOther);
	})
	function initOther(){
		var $extendedSelect = $(this);
		var $selects = $("select",this);
		$selects.each(tcheckOther);
		$selects.change(tcheckOther);
		var $form = $(this).closest('form');
		var $formData = $form.data('otherField');
		if(!$formData){
			$formData = $selects;
		}else{
			$formData.add($selects);
		}
		$form.data('otherField',$formData);
		$form.unbind('submit.otherField');
		$form.bind('submit.otherField',formSubmit);
		if($(this).attr('linked')){
			var $linked = $($(this).attr('linked'));
			var $linkedData = $linked.data('linkedSelect');
			if(!$linkedData){
				$linkedData = $extendedSelect;
			}else{
				$linkedData.add($extendedSelect);
			}
			$linked.data('linkedSelect',$linkedData);
			$linked.change(tcheckLinked);
			$linked.each(tcheckLinked);
		}
	}
	function tcheckLinked(){
		var $select = $(this);
		var $linked = $select.data('linkedSelect');
		var val = $select.val();
		$linked.each(function(){
			var $current;
			var $cases = $('.extendedSelectCase',this);
			if(val == ''){
				$current = $('.extendedSelectEmpty',this);
			}else{
				$current = $('.extendedSelectCase[rel="'+val+'"]',this);
				if(!$current.length){
					$current = $('.extendedSelectMore',this);
				}
			}
			/*if(window.console){
				console.log($cases);
				console.log(val);
				console.log($select);
				console.log($current);
			}*/
      if(!$current.hasClass('extendedSelectCurrent')){
        $cases.removeClass('extendedSelectCurrent');
        $cases.hide();
        $('input, select, textarea',$cases).attr('disabled',true);
        $current.addClass('extendedSelectCurrent');
        $current.show();
        $('input, select, textarea',$current,$cases).attr('disabled',false);
        $(this).trigger('refresh');
      }
		});
	}
	function tcheckOther(){
		var $select = $(this);
		var val = $select.val();
		var $other = $('.otherInput',$select.parent());
		if($other.length && val == 'other'){
			$other.show();
			$other.attr('disabled',false);
		}else{
			$other.hide();
			$other.attr('disabled',true);
		}
	}
	function formSubmit(){
		var $selects = $form.data('otherField');
		if(window.console){
			console.log($selects);
		}
		$selects.each(function (){
			var $select = $(this);
			var $other = $('.otherInput',$select.parent());
			var val = $select.val();
			var name = $select.attr('name');
			if($select.attr('original_name')){
				name = $select.attr('original_name');
			}
			if($other.length && val == 'other'){
				$select.attr('original_name',name);
				$other.attr('name',name);
				$select.attr('name',name.substr(0,name.length-1)+'_select]');
			}
		});
	}
})( jQuery );
