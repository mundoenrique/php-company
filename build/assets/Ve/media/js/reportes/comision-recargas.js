var firstDate, lastDate;
var responseServ = $('#response-serv');
var codeServ = parseInt(responseServ.attr('code'));
var titleServ = responseServ.attr('title');
var msgServ = responseServ.attr('msg');
$(function() {
	if(codeServ != 0) {
		msgSystemrepor(codeServ, titleServ, msgServ);
	}
	//Llamado a dataTable
	datatableStart();
	//Cofigura datepicker para las fechas
	$.datepicker.setDefaults({
		defaultDate: null,
		maxDate: "+0D"
	});
	//llamado para setear el calendario
	dateControls('first-date');
	dateControls('last-date');
	//Quita el borde rojo y esconde el mensaje de error
	$('.campo input:required, .campo select:required').on('click', function(){
		$(this).removeClass('border-red');
		$('#search').removeAttr('disabled');
		$('#mensajeError').hide();
	});
	//Llamado al listado de recargas con comisión
	$('#search').on('click', function(e) {
		e.preventDefault();
		validaCampos(function(response) {
			var dataReport = {
				firstDate: $('#first-date').val(),
				lastDate: $('#last-date').val(),
				company: $('#company').val()
			}
			$('#downloads').attr('first-date', dataReport.firstDate);
			$('#downloads').attr('last-date', dataReport.lastDate);
			$('#downloads').attr('company', dataReport.company);
			//Llamado al listado
			ReportRechar(dataReport);
		});
	});
	//Descarga de reportes excel y pdf
	$('#downloads').on('click', '#comisiones-xls, #comisiones-pdf', function(e) {
		var thisId = e.target.id;
		var parentId = e.target.parentNode.id;
		var filterList = thisId ? thisId : parentId;
		var attr = $('#novo-table > tbody > tr > td:first-child').hasClass( "dataTables_empty" );
		if(!attr) {
			var downloadData = {
				firstDate: $('#downloads').attr('first-date'),
				lastDate: $('#downloads').attr('last-date'),
				company: $('#downloads').attr('company'),
				report: filterList
			}
			$('#comisiones-xls, #comisiones-pdf').hide();
			$('#loading-report').fadeIn();
			//Llamado a la descarga de archivo
			downloadReport(downloadData);
		}
	});
});
/**
 * Función que despleiga el plugin datatable
 */
function datatableStart() {
	$('#novo-table').DataTable({
		dom: 'Bfrtip',
		select: false,
		searching: false,
		ordering: false,
		lengthChange: false,
		pagingType: 'full_numbers',
		pageLength: 10,
		language: { "url":  baseCDN + '/media/js/combustible/Spanish.json'}
	}).on( 'draw', function () {
		$('#loading').hide();
		$('#detail-report').fadeIn();
		if(codeServ == 0) {
			$('#search').removeAttr('disabled');
		} else {
			$('#first-date, #last-date').attr('disabled', true)
		}
	});
}
/**
 * Función que despleiga el plugin datatable
 */
function dateControls(inputId) {
	$('#' + inputId).removeAttr('disabled');
	$('#' + inputId).datepicker({
		onSelect: function(selectedDay) {
			var inputSet = inputId === 'first-date' ? 'last-date' : 'first-date';
			var minMax = inputId === 'first-date' ? 'minDate' : 'maxDate';

			firstDate = inputId === 'first-date' ? selectedDay : firstDate;
			lastDate = inputId === 'last-date' ? selectedDay : lastDate;
			$('#' + inputSet).datepicker('option', minMax, selectedDay);

			if(firstDate && lastDate) {
				daysDifference();
			}

		}
	});
}
/**
 * Función para validar los campos
 */
function validaCampos(_response_) {
	var valid = true;
	$.each($('.container-body select:required, .container-body input:required'), function(pos, elemnt){
		if($(this).val() === '') {
			$(this).addClass('border-red');
			valid = false;
		} else {
			$(this).removeClass('border-red');
		}
	});
	if(!valid) {
		$('#mensajeError')
		.text('Por favor, complete los campos que se le indican en color rojo')
		.fadeIn();
	} else {
		valid = daysDifference();
		valid ? _response_(valid) : '';
	}
}
/**
 * Función para obtener la diferencias de días
 */
function daysDifference() {
	var daysDiff;
	var valid = true;
	var firstDat = formatterDate($('#first-date').val());
	var lastDat = formatterDate($('#last-date').val());

	daysDiff = (lastDat.getTime() - firstDat.getTime()) / (1000 * 60 * 60 * 24);

	if(daysDiff > 60) {
		$('#mensajeError')
		.text('La consulta no puede ser mayor a 60 días')
		.fadeIn();
		$('.campo input:required').addClass('border-red');
		$('#search').attr('disabled', true);
		valid = false;
	} else {
		$('.campo input:required').removeClass('border-red');
		$('#search').removeAttr('disabled');
		$('#mensajeError').hide();
	}
	return valid;
}
/**
 * Función solicitar la lista de recargas
 */
function ReportRechar(dataReport) {
	dataReport = JSON.stringify(dataReport);
	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
	var dataRequest = JSON.stringify ({
		mod: 'reports_additional',
		way: 'ReportRecharWithComm',
		request: dataReport
	});
	dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
	$.ajax({
		method: 'POST',
		url: baseURL + isoPais + '/reportes/comisiones-recarga',
		data: {request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)},
		beforeSend: function() {
			$('#detail-report').hide();
			$('#loading').fadeIn();
			$('#search').attr('disabled', true);
		}
	}).done(function(response) {
		response = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));
		var code = response.code, title = response.title, msg = response.msg, date = response.date;
		var data, table;
		$('#novo-table').dataTable().fnClearTable();
		$('#novo-table').dataTable().fnDestroy();
		$('#first-date, #last-date')
		.val('')
		.datepicker('option', {minDate: null, maxDate: "+0D"});
		$('#info-date').text(date);

		switch(code) {
			case 0:
				data = response.data;
				$('#downloads > a')
				.removeAttr('class')
				.attr('class', response.css);

				$.each(data, function(index, value){
					table = '<tr>'
					table += '<td>' + value.fecha + '</td>'
					table += '<td>' + value.idPersona + '</td>'
					table += '<td>' + value.tarjeta + '</td>'
					table += '<td>' + value.montoRecarga + '</td>'
					table += '<td>' + value.montoComisionTh + '</td>'
					table += '<td>' + value.montoComisionEmpresa + '</td>'
					table += '<td>' + value.montoTotalOS + '</td>'
					table += '</tr>'
					$('#novo-table tbody').append(table);
				});
				break;
			default:
			msgSystemrepor(code, title, msg);
		}
		datatableStart();

	}).fail(function(){

	});
}
/**
 * Función para solicitar la descarga del archivo xls  o pdf
 */
function downloadReport(downloadData) {
	var report = downloadData.report;
	downloadData = JSON.stringify(downloadData);
	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
	var dataRequest = JSON.stringify({
		mod: 'reports_additional',
		way: 'DownloadReport',
		request: downloadData
	});
	dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
	$.ajax({
		method: 'POST',
		url: baseURL + isoPais + '/reportes/comisiones-recarga',
		data: {request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)},
		beforeSend: function() {
			$('#search').attr('disabled', true);
		}
	}).done(function(response) {
		response = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
		var code = response.code, title = response.title, msg = response.msg;
		$('#search').removeAttr('disabled');
		$('#loading-report').hide();
		$('#comisiones-xls, #comisiones-pdf').fadeIn();
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
		switch(code) {
			case 0:
				var ceo_cook = decodeURIComponent(
					document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
				);
				$('#down-report').empty();
				$('#down-report').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
				$('#down-report').append('<input type="hidden" name="mod" value="lists_and_requirements" />');
				$('#down-report').append('<input type="hidden" name="way" value="downloadFile" />');
				$('#down-report').append('<input type="hidden" name="request" value="' + msg + '" />');
				$('#down-report').submit();
				break;
			default:
			msgSystemrepor(code, title, msg);

		}
	}).fail(function(){

	});
}

function msgSystemrepor(code, title, msg) {
	var msgSystem = $('#msg-system-report');
	msgSystem.dialog({
		title: title,
		modal: 'true',
		width: '210px',
		draggable: false,
		rezise: false,
		open: function(event, ui) {
			$('.ui-dialog-titlebar-close', ui.dialog).hide();
			$('#msg-info').append('<p>' + msg + '</p>');
		}
	});
	$('#close-info').on('click', function(){
		$(msgSystem).dialog('close');
		switch(code) {
			case 1:
				window.location.replace(baseURL + isoPais + '/dashboard/productos/detalle');
				break;
			case 2:
				window.location.replace(baseURL + isoPais + '/logout');
				break;
		}
	});
}
