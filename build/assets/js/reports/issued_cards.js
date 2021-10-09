'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	$("#monthYear").datepicker({
		dateFormat: 'mm/yy',
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		maxDate: "+0D",
		closeText: 'Aceptar',
		yearRange: '-12:' + currentDate.getFullYear(),

		onSelect: function (selectDate) {
			$(this)
				.focus()
				.blur();
		},

		onClose: function (dateText, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, month, 1));
		},
		beforeShow: function (input, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			inst.dpDiv.addClass("ui-datepicker-month-year");
			$(this).datepicker('setDate', new Date(year, month, 1));
		}
	});

	$('.date-picker').datepicker({
		onSelect: function (selectedDate) {
			$(this)
				.focus()
				.blur();
			var dateSelected = selectedDate.split('/');
			dateSelected = dateSelected[1] + '/' + dateSelected[0] + '/' + dateSelected[2];
			dateSelected = new Date(dateSelected);
			var inputDate = $(this).attr('id');

			if (inputDate == 'initDate') {
				$('#finalDate').datepicker('option', 'minDate', dateSelected);
				var maxTime = new Date(dateSelected.getFullYear(), dateSelected.getMonth() + 3, dateSelected.getDate() - 1);

				if (currentDate > maxTime) {
					$('#finalDate').datepicker('option', 'maxDate', maxTime);
				} else {
					$('#finalDate').datepicker('option', 'maxDate', currentDate);
				}
			}
		}
	});

	$('#issued-cards-btn').on('click', function(e) {
		e.preventDefault();
		form = $('#issued-cards-form');
		validateForms(form);

		if (form.valid()) {
			$('.issued-cards-result').addClass('hide');
			$('#downloads').addClass('hide');
			$('#pre-loader-table').removeClass('hide');
			$('#issued-cards-table').empty();
			$('#download-issuedcards').empty();
			verb = "POST";
			who = 'reports';
			where = 'issuedCards';
			data = getDataForm(form);
			data.queryType = $('input:radio[name=selection]:checked').val();
			data.type = 'info';
			delete data.all
			delete data.products
			insertFormInput(true);

			callNovoCore(verb, who, where, data, function (response) {
				var table = '';
				var totalRecords = response.data.issuedCardsList.length -1;

				if (response.data.issuedCardsList != '') {
					$('#downloads').removeClass('hide');
				}

				$.each(response.data.issuedCardsList, function (index, value) {
					if ((index == 0 && response.data.queryType == '0') || response.data.queryType == '1') {
						table += '<table class="resut-issued-card cell-border h6 display responsive w-100">';
						table += '<thead class="bg-primary secondary regular">';
						table += '<tr>'

						if (!value || response.data.queryType == '0') {
							table += '<th>' + lang.GEN_PRODUCT +'</th>';
						} else if (value || response.data.queryType == '1') {
							table += '<th>' + value.nomProducto + '</th>';
						}

						table += '<th>' + lang.GEN_TABLE_EMISSION +'</th>';
						table += '<th>' + lang.GEN_TABLE_REP_TARJETA +'</th>';
						table += '<th>' + lang.GEN_TABLE_REP_CLAVE +'</th>';
						table += '<th>' + lang.GEN_TABLE_TOTAL +'</th>';
						table += '</tr>'
						table += '</thead>';
						table += '<tbody>';
					}

					if (value) {
						if (response.data.queryType == '0') {
							table += '<tr>'
							table += '<td>' + value.nomProducto + '</td>'
							table += '<td>' + value.totalEmision + '</td>'
							table += '<td>' + value.totalReposicionTarjeta + '</td>'
							table += '<td>' + value.totalReposicionClave + '</td>'
							table += '<td>' + value.totalProducto + '</td>'
							table += '</tr>'
						}

						if (response.data.queryType == '1') {
							table += '<tr>'
							table += '<td>' + lang.GEN_TABLE_PRINCIPAL + '</td>'
							table += '<td>' + value.totalEmision + '</td>'
							table += '<td>' + value.totalReposicionTarjeta + '</td>'
							table += '<td>' + value.totalReposicionClave + '</td>'
							table += '<td>' + value.totalProducto + '</td>'
							table += '</tr>'
							table += '<tr>'
							table += '<td>' + lang.GEN_TABLE_SUPLEMENTARIA+ '</td>'
							table += '<td>' + value.emisionSuplementaria.totalEmision+ '</td>'
							table += '<td>' + value.emisionSuplementaria.totalReposicionTarjeta+ '</td>'
							table += '<td>' + value.emisionSuplementaria.totalReposicionClave+ '</td>'
							table += '<td>' + value.emisionSuplementaria.totalProducto + '</td>'
							table += '</tr>'
							table += '<tr>'
							table += '<td>' + lang.GEN_TABLE_TOTAL + '</td>'
							table += '<td>' + value.totalEmision + '</td>'
							table += '<td>' + value.totalReposicionTarjeta + '</td>'
							table += '<td>' + value.totalReposicionClave + '</td>'
							table += '<td>' + value.totalProducto + '</td>'
							table += '</tr>'
						}
					}

					if ((index == totalRecords && response.data.queryType == '0') || response.data.queryType == '1') {
						table += '</tbody>';
						table += '</table>';
					}
				});

				$('#issued-cards-table').append(table);
				$('.resut-issued-card').DataTable({
					ordering: false,
					responsive: true,
					searching: false,
					paging: false,
					pagingType: "full_numbers",
					language: dataTableLang
				});

				$('#pre-loader-table').addClass('hide');
				$('.issued-cards-result').removeClass('hide');
				insertFormInput(false);

				if (response.data.issuedCardsList != '' && response.code == 0) {
					$.each(data, function (index, value) {
						$('#download-issuedcards').append('<input type="hidden" id="' + index + '" value="' + value + '">');
					});

					$('#download-issuedcards')
						.append('<input type="hidden" id="type" value="download">')
						.append('<input type="hidden" id="fiscalId" value="' + $('#enterpriseCode option:selected').attr('id-fiscal') + '">')
						.append('<input type="hidden" id="enterpriseName" value="' + $('#enterpriseCode option:selected').text().trim() + '">')
						.append('<input type="hidden" id="format">');
				}
			});
		}

	});

	$('#downloads').on('click', 'button', function (e) {
		$('#download-issuedcards input:hidden[id=format]').val($(e.currentTarget).attr('format'));
		form = $('#download-issuedcards');
		verb = "POST";
		who = 'reports';
		where = 'issuedCards';
		data = getDataForm(form);

		callNovoCore(verb, who, where, data, function (response) {

			if (response.code == 0) {
				downLoadfiles(response.data);
			}

			insertFormInput(false);
		});
	});
});

