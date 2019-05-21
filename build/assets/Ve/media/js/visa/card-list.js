$(function() {

	getCardList();

	$('#novo-table').on('click','.editar', function () {
		var clientID = $(this).data('id'),
			card = $(this).parent().siblings().eq(0).text();
		addEdit(clientID, card);
	});

	$('#buscar').on('click', function(e){
		e.preventDefault();
		$('#novo-table').dataTable().fnDestroy();

		validaForm();

		if($('#data-card').valid() === true) {
			var dni = $('#dni').val(),
				card = $('#card').val();

			getCardList(dni, card);
			$('#loading').show();
			$('#novo-table').hide();
		}


	});
});

function getCardList(dni, card)
{
	dni = dni === undefined ? '' : dni;
	card = card === undefined ? '' : card;
	//dataTable paginando desde el servidor---------------------------------------------------------
	$('#novo-table').dataTable({
		"drawCallback": function(data) {

			var code =  data.json.code, title = data.json.title, message = data.json.msg, type;

			switch(code){
				case 2:
					type = 'serv';
					break;
				case 3:
					type = 'close';
					break;
			}
			if(code !== 0) {
				notiSystem(title, message, type);
			}
			if(data.json.data.length > 0) {
				$('#dni').val('');
				$('#card').val('');
			}
			$('#loading').hide();
			$('#novo-table').show();

		},
		"language": { "url": baseCDN + '/media/js/combustible/Spanish.json'},
		"pageLength": 10,
		"bSort": false,
		"bFilter" : false,
		"bLengthChange": false,
		"processing": false,
		"pagingType": "full_numbers",
		"serverSide": true,
		"ajax": {
			url: baseURL + isoPais + '/card-list',
			data: function (d) {
				var ceo_cook = decodeURIComponent(
					document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
				);
				d.model = 'CardList';
				d.dni = dni;
				d.card = card;
				d.ceo_name: ceo_cook
			}
		},
		"columns": [
			{
				title: "Nro. tarjeta",
				data: "cardNumber"
			},
			{
				title: "Nombre completo",
				data: "clientName"
			},
			{
				title: "DNI",
				data: "clientID"
			},
			{
				title: "Acciones",
				data: "clientID",
				"render": function(clientID) {
					return '<a class="editar" data-id="' + clientID + '" title="Editar controles"><span aria-hidden="true" class="icon icon-list" data-icon="&#xe014;"></span></a>'
				}
			}
		]
	});
	//----------------------------------------------------------------------------------------------
}


function addEdit(id, card) {
	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
	$('form#formulario').empty();
	$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
	$('form#formulario').append('<input type="hidden" name="dni" value="' + id + '" />');
	$('form#formulario').append('<input type="hidden" name="card" value="' + card + '" />');
	$('form#formulario').attr('action', baseURL + isoPais + '/controles/visa/configurar');
	$('form#formulario').submit();
}
