'use strict'
var table;
var access;
var params;
var balance;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var masterAccountBtn = $('#masterAccountBtn');
	insertFormInput(false);

	masterAccountBtn.on('click', function (e) {
		e.preventDefault();
		$('#tableServicesMaster').dataTable().fnClearTable();
		$('#tableServicesMaster').dataTable().fnDestroy();
		form = $('#masterAccountForm');
		btnText = $(this).text().trim()
		validateForms(form);

		if (form.valid()) {
			var dataForm = getDataForm(form)
			insertFormInput(true)
			$('.hide-table').addClass('hide')
			$('#pre-loader-table').removeClass('hide')
			verb = 'POST'; who = "Services"; where = 'TransfMasterAccount';
			dataTableBuild(dataForm)
		}
	})

	$('#tableServicesMaster').on('click', '.toggle-all', function () {
		$(this).closest("tr").toggleClass("selected");
		if ($(this).closest("tr").hasClass("selected")) {
			table.rows().select();
		} else {
			table.rows().deselect();
		}
	})

	$('#tableServicesMaster').on('click', 'button', function(e) {
		var event = $(e.currentTarget);
		var action = event.attr('title');
		$(this).closest('tr').addClass('select');
		sendRequest(action, 'select')
	})

	$('#consulta, #abono, #cargo,').on('click', function(e) {
		e.preventDefault()
		var event = $(e.currentTarget);
		var action = event.text().trim();
		var thisId = event.attr('id');
		sendRequest(action, 'selected')
	});
})

function dataTableBuild(dataForm) {
	verb = 'POST'; who = "Services"; where = 'TransfMasterAccount';
	table = $('#tableServicesMaster').DataTable({
		drawCallback: function(d) {
			$('#balance-aviable').text(lang.GEN_CURRENCY+' '+balance)
			$('#cost-trans').text(lang.GEN_CURRENCY+' '+params.costoComisionTrans)
			$('#cost-inquiry').text(lang.GEN_CURRENCY+' '+params.costoComisionCons)
			insertFormInput(false)
			$('#pre-loader-table').addClass('hide')
			$('.hide-table').removeClass('hide')
		},
		"ordering": false,
		"searching": false,
		"lengthChange": false,
		"pagelength": 10,
		"pagingType": "full_numbers",
		"table-layout": "fixed",
		"select": {
			"style": "multi",
			"info": false,
			selector: ':not(td:nth-child(-n+6))'
		},
		"language": dataTableLang,
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: baseURL + 'async-call',
			method: 'POST',
			dataType: 'json',
			data: function (req) {
				data = req
				data.idNumber = dataForm.idNumber;
				data.cardNumber = dataForm.cardNumber;
				data.screenSize = screen.width;
				var dataRequest = JSON.stringify({
					who: who,
					where: where,
					data: data
				});

				dataRequest = cryptoPass(dataRequest, true);
				var request = {
					request: dataRequest,
					ceo_name: ceo_cook,
					plot: btoa(ceo_cook)
				}

				return request
			},
			dataSrc: function (response) {
				response = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
				console.log(response)
				access = response.access;
				balance = response.balance;
				params = response.params;
				return response.data;
			},
		},
		"columnDefs": [
			{
				"targets": 0,
				"className": "select-checkbox",
				"checkboxes": {
					"selectRow": true
				},
			},
			{
				"targets": 3,
				"width": "auto",
				render: function (data, type, row) {
					return data.length > 20 ?
						data.substr(0, 20) + '…' :
						data;
				}
			},
			{
				"targets": 5,
				"width": "70px"
			},
		],
		"columns": [
			{
				data: function (data) {
					return ''
				}
			},
			{ data: 'cardNumber' },
			{ data: 'name' },
			{ data: 'idNumber' },
			{
				 data: function (data) {
					 return '--'
				 }
			},
			{
				data: function (data) {
						var ammount;
						ammount = '<form>';
						ammount+=		'<input class="form-control h6" type="text"></input>';
						ammount+= '</form>';
					return ammount
				}
		 },
			{
				data: function (data) {
					var options = '';

					if(access.TRASAL) {
						options+=		'<button class="btn mx-1 px-0" title="Consulta saldo" data-toggle="tooltip">';
						options+=			'<i class="icon novoglyphs icon-balance" aria-hidden="true"></i>';
						options+= 	'</button>';
					}

					if (access.TRACAR) {
						options+=		'<button class="btn mx-1 px-0" title="Abono tarjeta" data-toggle="tooltip">';
						options+=			'<i class="icon novoglyphs icon-credit-card" aria-hidden="true"></i>';
						options+=		'</button>';

					}

					if (access.TRAABO) {
						options+=		'<button class="btn mx-1 px-0" title="Cargo tarjeta" data-toggle="tooltip">';
						options+=			'<i class="icon novoglyphs icon-card-fee" aria-hidden="true"></i>';
						options+=		'</button>';
					}

					if (!access.TRABLQ) {
						options+=		'<button class="btn mx-1 px-0" title="Bloqueo tarjeta" data-toggle="tooltip">';
						options+=			'<i class="icon novoglyphs icon-lock" aria-hidden="true"></i>';
						options+=		'</button>';
					}

					if (!access.TRAASG) {
						options+=		'<button class="btn mx-1 px-0" title="Asignación tarjeta" data-toggle="tooltip">';
						options+=			'<i class="icon novoglyphs icon-arrow-left" aria-hidden="true"></i>';
						options+=		'</button>';
					}

					return options;
				}
		 }
		]
	});
}

function sendRequest(action, classSelect) {
	var cardsData = table.rows(classSelect).data();
	$('#accept').addClass('send-request');
	data = {
		btn1: {
			text: lang.GEN_BTN_DELETE,
			action: 'close'
		},
		btn2: {
			text: lang.GEN_BTN_CANCEL,
			action: 'close'
		}
	}

	if (action === 'Abono tarjeta' || action === 'Cargo tarjeta') {

		console.log(cardsData.length)
		for(var i = 0; i <= cardsData.length; i++) {
			console.log(cardsData[i])
		}
	}

	switch (action) {
		case 'Consulta saldo':

		break;
		case 'Abono tarjeta':

		break;
		case 'Cargo tarjeta':

		break;
		case 'Bloqueo tarjeta':

		break;
		case 'Asignación tarjeta':

		break;
	}


}
