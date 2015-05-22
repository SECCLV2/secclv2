$(document).ready(function() {
	$('.frmPaginador').removeAttr('action');

	$('.linkPaginador').on('click', function(ev) {
		ev.preventDefault();
		var url = $(this).attr('href');
		$('.frmPaginador').attr('action', url);
		$('.frmPaginador').submit();
	});
});