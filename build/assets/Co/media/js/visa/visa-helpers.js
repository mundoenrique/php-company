function controlsDate(input)
{
	$('#' + input).datepicker({
		changeMonth: true,
		changeYear: true,
		numberOfMonths: 1,
		dateFormat:"dd/mm/yy",
		onSelect: function(selectDate) {
			var inputSelect = input === 'first-date' ? 'last-date' : 'first-date';

			input === 'first-date' ? $('#' + inputSelect).datepicker('option', 'minDate', selectDate) :
				$('#' + inputSelect).datepicker('option', 'maxDate', selectDate);

		}
	});

	$('#first-date').datepicker('option', 'minDate', '0');
	$('#last-date').datepicker('option', 'minDate', '0');

}

function formaterDate(selectDate)
{
	var dateArray = selectDate.split('/'),
		date = dateArray[2] + '-' + dateArray[1] + '-' + dateArray[0];

	return date;
}

function notiSystem(title, message, type) {
	$('#msg-system').dialog({
		title: title,
		modal: 'true',
		width: '210px',
		draggable: false,
		rezise: false,
		open: function(event, ui) {
			$('.ui-dialog-titlebar-close', ui.dialog).hide();
			$('#msg-info > p').text(message)
		}
	});
	$('#close-info').on('click', function(e){
		e.preventDefault();
		$('#msg-system').dialog('close');
		switch (type) {
			case 'u':
				location.reload(true);
				break;

			case 'controls':
				window.location.replace(baseURL + isoPais + '/controles/visa');
				break;

			case 'serv':
				window.location.replace(baseURL + isoPais + '/dashboard/productos/detalle');
				break;

			case 'close':
				window.location.replace(baseURL + isoPais + '/logout');
				break;

		}
	});
}

