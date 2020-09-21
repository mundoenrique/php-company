'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
		insertFormInput(false);
	$('#sectionPermits').addClass('hide');

	$('#enableUserBtn').on('click', function() {
		$('#sectionPermits').fadeIn(700, 'linear');
		$('#enableSectionBtn').remove();
	});
});
