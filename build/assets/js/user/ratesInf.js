$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	insertFormInput(false)

	$('#rates').DataTable({
		"ordering": false,
		"pagingType": "full_numbers",
		"select": false,
		"searching": false,
		"ordering": false,
		"lengthChange": false,
		"paging": false,
		"info": false
	});
})
