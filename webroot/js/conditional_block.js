
(function( $ ) {
	$(function(){
    bind();
		update();
	})
  function update($blocks,$source){
    if(!$blocks) $blocks = $('.conditionalBlock');
    $blocks.each(function(){
      var show = false;
      var $cur_source = $source ? $source : $($(this).attr("source"),$(this).closest('form,body'));
      var when = $(this).attr("when");
      if(when){
        if(when[0] == '[') {
          when = $.parseJSON(when);
        }else{
          when = [when];
        }
        show = when.indexOf($cur_source.val()) != -1;
      }
      if(show){
        $(this).show();
        $('input, select, textarea',this).removeAttr('disabled');
      }else{
        $(this).hide();
        $('input, select, textarea',this).attr('disabled',true);
      }
    });
  }
  function bind(){
    $('.conditionalBlock').each(function(){
      var $source = $($(this).attr("source"),$(this).closest('form,body'));
      var data = $source.data('conditionalBlock');
      if(!data){
        data = $(this)
      }else{
        data.add($(this));
      }
      $source.data('conditionalBlock',data);
      $source.bind('change.conditionalBlock',eventHandler);
    });
  }
  function eventHandler(e){
    $blocks = $(this).data('conditionalBlock');
    if($blocks){
      update($blocks,$(this));
    }
  }
})( jQuery );
