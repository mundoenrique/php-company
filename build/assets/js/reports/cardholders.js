'use strict'
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var resultscardHolders = $('#resultscardHolders');
	var cardHolderBtn = $('#cardholder-btn');

	cardHolderBtn.on('click', function (e) {
		form = $('#cardholder-form');
		btnText = $(this).text().trim()
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			insertFormInput(true);
			$('.cardholders-result').addClass('hide');
			$('#pre-loade-result').removeClass('hide');
			resultscardHolders.dataTable().fnClearTable();
			resultscardHolders.dataTable().fnDestroy();
			who = 'Reports';
			where = 'CardHolders';

			callNovoCore(who, where, data, function (response) {
				var cardHolders = response.data.cardHoldersList;
				createTable(cardHolders);
				if (cardHolders.length == 0) {
					$('.download-icons').addClass('hide')
				} else {
					$('.download-icons').removeClass('hide')
				}
				insertFormInput(false);
				cardHolderBtn.html(btnText);
				$('#pre-loade-result').addClass('hide')
				$('.cardholders-result').removeClass('hide');
			})
		}
	});

	function createTable(cardHolders){
		resultscardHolders.DataTable({
			"ordering": false,
			"responsive": true,
			"pagingType": "full_numbers",
			"data": cardHolders,
			"columns": [
				{ data: 'cardHoldersId' },
				{ data: 'cardHoldersNum' },
				{ data: 'cardHoldersName' },
			],
			"columnDefs": [
				{
					"targets": 1,
					"visible": lang.SETT_CARD_NUMBER_COLUMN == "ON",
				},
			],
			"language": dataTableLang
		});
	};

	//Descargar reporte en formato Excel o PDF
	$('.downloadReport').on('click', function(e) {
		e.preventDefault();
		form = $('#cardholder-form');
		validateForms(form);

		if (form.valid()) {
			$('.cover-spin').show();
			who = 'Reports';
			where = 'exportReportCardHolders';
			data = getDataForm(form);
			data.enterpriseName = $('option:selected', "#enterpriseCode").text().trim();
			data.productName = $('option:selected', "#productCode").text().trim();
			data.downloadFormat = $(this).attr('format');

			callNovoCore(who, where, data, function(response) {

				if (response.code == 0) {
					downLoadfiles (response.data);
				}

				$('.cover-spin').hide();
			});

		}
	});
});
