$(document).ready(function(){
	
	$('.content_table_revision').ready(function() { 
		var id = getParameterByName('id');
		$.post( '?run=revision_ajax.php&id='+id+'&action=data', function( data ) {
			build_table(data);
		});
	});
	
	var build_table = function (data){
		var obj = jQuery.parseJSON(data);
		var html_revision = [];
		var revision = obj.revision;
		$.each( revision, function( number , value ) {
			var id = value.id;
			var name = value.name;
			var slug = value.slug;
			var public_time = value.public_time;
			var line = '<td>'+public_time+'</td><td>'+name+'</td><td>'+slug+'</td><td class="td_action td_action_'+id+'"><a class="btn btn-default btn-xs" href="?run=chapter.php&action=view&id='+id+'">Xem</a><button type="button" data-id="'+id+'" class="quick_delete_permanently_content_button btn btn-danger btn-xs">Xóa vĩnh viễn</button></td>';
			html_revision.push('<tr class="content_tr content_'+id+'">'+line+'</tr>');
		});
		html_revision = html_revision.join(' ');
		$('.content_table .content_table_revision').html(html_revision);
	};
	
	$(document).on('click', '.quick_delete_permanently_content_button', function(){
		var id = $(this).attr('data-id');
		$('.content_tr.content_'+id).addClass('warning');
		$('.td_action_'+id+' .quick_delete_permanently_content_button').remove();
		$('.td_action_'+id).append('<button type="button" data-id="'+id+'" class="confirm_delete_permanently_content_button btn btn-danger btn-xs">Xác nhận xóa vĩnh viễn</button>');
		$.notify('Vui lòng xác nhận', { globalPosition: 'top right',className: 'success' } );
	});
	
	$(document).on('click', '.confirm_delete_permanently_content_button', function(){
		var id = $(this).attr('data-id');
		var status = 'draft';
		var content_key = getParameterByName('key');
		$.post( '?run=content_ajax.php&action=delete_permanently', { id:id }, function( data ) {
			$.notify('Đã xóa vĩnh viễn', { globalPosition: 'top right',className: 'success' } );
			var id = getParameterByName('id');
			$.post( '?run=revision_ajax.php&id='+id+'&action=data', function( data ) {
				build_table(data);
			});
		});
	});

});