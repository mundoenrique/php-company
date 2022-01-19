'use strict'
$(function () {
	var issuedData;
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');

	$("#monthYear").datepicker({
		dateFormat: 'mm/yy',
		showButtonPanel: true,
		onClose: function (dateText, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, month, 1));
			$(this)
				.focus()
				.blur();
		},
		beforeShow: function (input, inst) {
			inst.dpDiv.addClass("ui-datepicker-month-year");
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
				var maxTime = new Date(dateSelected.getFullYear(), dateSelected.getMonth() + lang.CONF_DATEPICKER_MONTHRANGE, dateSelected.getDate() - 1);

				if (currentDate > maxTime) {
					$('#finalDate').datepicker('option', 'maxDate', maxTime);
				} else {
					$('#finalDate').datepicker('option', 'maxDate', currentDate);
				}
			}
		}
	});

	$('#issued-cards-btn').on('click', function (e) {
		e.preventDefault();
		form = $('#issued-cards-form');
		validateForms(form);

		if (form.valid()) {
			$('.issued-cards-result').addClass('hide');
			$('#downloads').addClass('hide');
			$('#pre-loader-table').removeClass('hide');
			$('#issued-cards-table').empty();
			$('#download-issuedcards').empty();
			who = 'reports';
			where = 'issuedCards';
			data = getDataForm(form);
			data.queryType = $('input:radio[name=selection]:checked').val();
			data.type = 'info';
			delete data.all
			delete data.products
			insertFormInput(true);

			callNovoCore(who, where, data, function (response) {
				issuedData = response.data.issuedCardsList
				var table = '';
				var totalRecords = issuedData.length - 1;

				if (issuedData != '') {
					$('#downloads').removeClass('hide');
				}

				$.each(issuedData, function (index, value) {
					if ((index == 0 && response.data.queryType == '0') || response.data.queryType == '1') {
						table += '<table class="resut-issued-card cell-border h6 display responsive w-100">';
						table += '<thead class="bg-primary secondary regular">';
						table += '<tr>';

						if (!value || response.data.queryType == '0') {
							table += '<th>' + lang.GEN_PRODUCT + '</th>';
						} else if (value || response.data.queryType == '1') {
							table += '<th>' + value.nomProducto + '</th>';
						}

						table += '<th>' + lang.GEN_TABLE_EMISSION + '</th>';
						table += '<th>' + lang.GEN_TABLE_REP_TARJETA + '</th>';
						table += '<th>' + lang.GEN_TABLE_REP_CLAVE + '</th>';
						table += '<th>' + lang.GEN_TABLE_TOTAL + '</th>';
						table += '</tr>';
						table += '</thead>';
						table += '<tbody>';
					}

					if (value) {
						if (response.data.queryType == '0') {
							table += '<tr>';
							table += '<td>' + value.nomProducto + '</td>';
							table += '<td>' + value.totalEmision + '</td>';
							table += '<td>' + value.totalReposicionTarjeta + '</td>';
							table += '<td>' + value.totalReposicionClave + '</td>';
							table += '<td>' + value.totalProducto + '</td>';
							table += '</tr>';
						}

						if (response.data.queryType == '1') {
							table += '<tr>';
							table += '<td>' + lang.GEN_TABLE_PRINCIPAL + '</td>';

							if (lang.CONF_ISSUED_MONTHLY == 'ON' && value.emision > 0) {
								table += '<td>';
								table += '<a class="hyper-link" title="' + lang.GEN_TABLE_EMISSION + '" index="' + index + '" type="ep" href="javascript:">';
								table += value.emision;
								table += '</a>';
								table += '</td>';
							} else {
								table += '<td>' + value.emision + '</td>';
							}

							if (lang.CONF_ISSUED_MONTHLY == 'ON' && value.repPlastico > 0) {
								table += '<td>';
								table += '<a class="hyper-link" title="' + lang.GEN_TABLE_EMISSION + '" index="' + index + '" type="rp" href="javascript:">';
								table += value.repPlastico;
								table += '</a>';
								table += '</td>';
							} else {
								table += '<td>' + value.repPlastico + '</td>';
							}

							table += '<td>' + value.repClave + '</td>';
							table += '<td>' + value.totalProducto + '</td>';
							table += '</tr>';
							table += '<tr>';
							table += '<td>' + lang.GEN_TABLE_SUPLEMENTARIA + '</td>';
							table += '<td>' + value.emisionSuplementaria.totalEmision + '</td>';
							table += '<td>' + value.emisionSuplementaria.totalReposicionTarjeta + '</td>';
							table += '<td>' + value.emisionSuplementaria.totalReposicionClave + '</td>';
							table += '<td>' + value.emisionSuplementaria.totalProducto + '</td>';
							table += '</tr>';
							table += '<tr>';
							table += '<td>' + lang.GEN_TABLE_TOTAL + '</td>';
							table += '<td>' + value.totalEmision + '</td>';
							table += '<td>' + value.totalReposicionTarjeta + '</td>';
							table += '<td>' + value.totalReposicionClave + '</td>';
							table += '<td>' + value.totalProducto + '</td>';
							table += '</tr>';
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
					language: dataTableLang,
					"columnDefs": [
						{
							"targets": 3,
							"visible": lang.CONF_CARDS_INQUIRY_ISSUE_STATUS == "ON",
						},
					],
				});

				$('#pre-loader-table').addClass('hide');
				$('.issued-cards-result').removeClass('hide');
				insertFormInput(false);

				if (issuedData != '' && response.code == 0) {
					$.each(data, function (index, value) {
						$('#download-issuedcards').append('<input type="hidden" id="' + index + '" value="' + value + '">');
					});

					$('#download-issuedcards')
						.append('<input type="hidden" id="type" value="download">')
						.append('<input type="hidden" id="fiscalId" value="' + $('#enterpriseCode option:selected').attr('id-fiscal') + '">')
						.append('<input type="hidden" id="enterpriseName" value="' + $('#enterpriseCode option:selected').text().trim() + '">')
						.append('<input type="hidden" id="format">')
						.append('<input type="hidden" id="detailIndex">')
						.append('<input type="hidden" id="detailType">');
				}
			});
		}

	});

	$('#downloads').on('click', 'button', function (e) {
		$('#download-issuedcards input:hidden[id=format]').val($(e.currentTarget).attr('format'));
		$('#download-issuedcards input:hidden[id=detailIndex]').val('');
		$('#download-issuedcards input:hidden[id=detailType]').val('');
		form = $('#download-issuedcards');
		who = 'reports';
		where = 'issuedCards';
		data = getDataForm(form);

		callNovoCore(who, where, data, function (response) {

			if (response.code == 0) {
				downLoadfiles(response.data);
			}

			$('.cover-spin').hide();
			insertFormInput(false);
		});
	});

	$('#issued-cards-table').on('click', 'tbody td a.hyper-link', function() {
		$('#download-issuedcards input:hidden[id=detailIndex]').val($(this).attr('index'));
		$('#download-issuedcards input:hidden[id=detailType]').val($(this).attr('type'));

		var modalBtn = {
			width: 1050,
			posMy: 'top',
			posAt: 'top',
			maxHeight: 'none',
			close: true
		};

		var detailInfo = $(this).attr('type') == 'ep'
			? issuedData[$(this).attr('index')].detalleEmisiones[0]
			: issuedData[$(this).attr('index')].detalleReposiciones[0];
		$('#system-msg').addClass('w-100');
		appMessages(detailInfo.nombreProducto, '', '', modalBtn);

		var tableDetailInfo = '';
		tableDetailInfo += '<div class="flex ml-2 flex-auto justify-start items-center">';
		tableDetailInfo += '<div class="download-icons">';
		tableDetailInfo += '<button class="btn px-1 big-modal download" title="' + lang.GEN_BTN_DOWN_XLS + '" data-toggle="tooltip" format="xls">';
		tableDetailInfo += '<i class="icon icon-file-excel" aria-hidden="true"></i>';
		tableDetailInfo += '</button>';
		tableDetailInfo += '</div>';
		tableDetailInfo += '</div>';
		tableDetailInfo += '<table class="detail-issued-card cell-border h6 display responsive w-100">';
		tableDetailInfo += '<thead class="bg-primary secondary regular">';
		tableDetailInfo += '<tr>';
		tableDetailInfo += '<th>' + lang.GEN_TABLE_BULK_ISSUE_DATE + '</th>';
		tableDetailInfo += '<th>' + lang.GEN_TABLE_BULK_NUMBER + '</th>';
		tableDetailInfo += '<th>' + lang.GEN_TABLE_CARD_NUMBER + '</th>';
		tableDetailInfo += '<th>' + lang.GEN_TABLE_DNI + '</th>';
		tableDetailInfo += '<th>' + lang.GEN_TABLE_NAME + '</th>';
		tableDetailInfo += '<th>' + lang.GEN_TABLE_LASTNAME + '</th>';
		tableDetailInfo += '<th>' + lang.GEN_TABLE_LOCATION + '</th>';
		tableDetailInfo += '<th>' + lang.GEN_TABLE_EMISSION_STATUS + '</th>';
		tableDetailInfo += '<th>' + lang.GEN_TABLE_PLASTIC_STATUS + '</th>';
		tableDetailInfo += '</tr>';
		tableDetailInfo += '</thead>';
		tableDetailInfo += '<tbody>';

		$.each(detailInfo.detalle, function(index, element) {
			tableDetailInfo += '<tr>';
			tableDetailInfo += '<td>' + element.fechaEmision + '</td>';
			tableDetailInfo += '<td>' + element.nroLote + '</td>';
			tableDetailInfo += '<td>' + element.nroTarjeta + '</td>';
			tableDetailInfo += '<td>' + element.cedula + '</td>';
			tableDetailInfo += '<td>' + element.nombres + '</td>';
			tableDetailInfo += '<td>' + element.apellidos + '</td>';
			tableDetailInfo += '<td>' + element.ubicacion + '</td>';
			tableDetailInfo += '<td>' + element.estadoEmision + '</td>';
			tableDetailInfo += '<td>' + element.estadoPlastico + '</td>';
			tableDetailInfo += '</tr>';
		});

		tableDetailInfo += '</tbody>';
		tableDetailInfo += '</table>';

		$('#system-msg').append(tableDetailInfo);

		$('.detail-issued-card').DataTable({
			ordering: false,
			responsive: true,
			searching: false,
			paging: true,
			lengthChange: false,
			pageLength: 5,
			pagingType: "full_numbers",
			language: dataTableLang
		});

	});

	$('#system-msg').on('click', 'button.download', function (e) {
		$('.cover-spin').show(0);
		$('#download-issuedcards input:hidden[id=format]').val($(e.currentTarget).attr('format'));
		form = $('#download-issuedcards');
		who = 'reports';
		where = 'issuedCards';
		data = getDataForm(form);
		insertFormInput(true);

		callNovoCore(who, where, data, function (response) {

			if (response.code == 0) {
				downLoadfiles(response.data);
			}

			$('.cover-spin').hide();
			insertFormInput(false);
		});
	});
});

