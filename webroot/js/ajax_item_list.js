$(function(){
	$("div.ajax_item_list div.search input.submit").live('click',function(){
		var $container = $(this).closest("div.ajax_item_list");
		var $search = $(this).closest("div.search");
		var q = $('input:first',$search).val();
		loadAjaxListPage($container,$(this).attr("action"),{q:q});
		return false;
	});
	$("div.ajax_item_list div.ajax_paging a").live('click',function(){
		var $container = $(this).closest("div.ajax_item_list");
		loadAjaxListPage($container,$(this).attr("href"),{});
		return false;
	});
	$("div.ajax_item_list div.item a.add").live('click',function(){
		var $container = $(this).closest("div.ajax_item_list");
		var $item = $(this).closest('div.item');
		var id =  $('input',$item).val();
		var $clone = $item.clone();
		$clone.find('input').attr('disabled',false).attr('name',$container.attr('inputname')+'[]');
		$('div.selected',$container).append($clone);
		$('div.item_'+id,$container).addClass('selected_item');
	});
	$("div.ajax_item_list div.item a.remove").live('click',function(){
		var $container = $(this).closest("div.ajax_item_list");
		var $item = $(this).closest('div.item');
		var id =  $('input',$item).val();
		$('div.selected div.item_'+id,$container).remove();
		$('div.item_'+id,$container).removeClass('selected_item');
	});
});
function loadAjaxListPage($container,url,data){
	$("div.loader",$container).show();
	$("div.select_page",$container).load(url, data, function(result, status){
		$("div.loader",$container).hide();
	});
}