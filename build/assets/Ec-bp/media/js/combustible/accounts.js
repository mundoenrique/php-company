var language, table = $('#novo-table'), type = '', accountAvailable = $('#accountAvailable'), accountAllocated = $('#accountAllocated');
var viewControl = 'accountAllocated';
var dataAccount = [];
accountAllocated.prop('disabled',true);
$('#filter-title').show();
$('#filter').show();


function catchErrorCode(code,msg,accept) {
    var title = 'Notificación';
    switch (code){
        case 1:
            // $('#novo-container-body').hide();
            // $('#novo-notFound').show();
            break;
        case 2:
            notiSystem(title,msg,accept,code);
            break;
        case 3:
            notiSystem(title,msg,accept,code);
    }

}

//Manejo de errores
function notiSystem (title,msg,accept,code) {
    var codeMsg = code;
    $( "#msg-system" ).dialog({
        title : title,
        modal: 'true',
        width: '210px',
        draggable: false,
        rezise: false,
        open: function(event, ui) {
            $('.ui-dialog-titlebar-close', ui.dialog).hide();
        },
        // dialogClass: "no-close",
        buttons: [
            {
                text: accept,
                click: function() {
                    if(codeMsg == 3){location.href = $('#logUrl').val() + '/logout';};
                    if(codeMsg == 2){
											$( "#msg-system" ).dialog( "close" );
										};


                }
            },
        ],
        create:function () {
            $( "#msg-system" ).closest(".ui-dialog")
                .find(".ui-button:first") // the first button
                .addClass("buttons-action");
        }
    });
    $( "#msg-system" ).text(msg);
}


$(function() {
    type = 'allocated';
    getDataAccount(type);
    // $('#loading').hide();
    $('.container').on('click','#details',function () {
        var card = $(this).data("card");
        account(card)
    });
});

var jsonData = [];
function getDataAccount(type) {
    $.post(baseURL + '/' + isoPais + '/trayectos/modelo', {way: 'accounts', modelo: 'account',data:type})
        .done(function(data) {
			if (data.code == undefined && JSON.parse(data.resp).lista != undefined && JSON.parse(data.resp).lista != []) {
				dataAccount = JSON.parse(data.resp);
				lang = data.lang
				jsonData = dataAccount.lista;
				createTable(jsonData);

			} else {
				$('#loading').hide();

				catchErrorCode(data.code, data.msg, data.language.TAG_ACCEPT);

				jsonData = [];
				createTable(jsonData);

			}
		});
}


function account (card){
    $('form#formularioAccount').append('<input type="hidden" name="data-id" value="' + card + '" />');
    $('form#formularioAccount').attr('action', baseURL + '/' + isoPais + '/trayectos/detalleCuentas');
    $('form#formularioAccount').submit();
    // console.log('tarjeta enviada: '+ card)
}


function createTable(datajson) {
    $('#loading').hide();
    var dataResponse = [],dataResponseCondition;
    var dataColumns = [];

    if(datajson != ''){
        dataResponse = datajson;
        dataResponseCondition = dataResponse[0].condicion;

        if(dataResponseCondition == 1){
            dataColumns = [
                {
                    title: "Número de tarjeta",
                    data:'noTarjeta'
                },
                {
                    title: "Usuario",
                    data:'usuarioRegistro',
                    'render': function (usuarioRegistro) {
                        return usuarioRegistro.toLowerCase();
                    }
                },

                {
                    title: "Fecha asignación",
                    data:'fechaAsignacion',
                    'render': function (fechaAsignacion) {
                        //Cambiar orden de fecha para mostrar al usuario
                        var dateSplit = fechaAsignacion.split(' '),
                            date      = dateSplit[0].split('-'),
                            time      = dateSplit[1],
                            dataTime = date[2] + '-' + date[1] + '-' + date[0];

                        return dataTime + ' ' + time;
                    }
                },
                {
                    title: "Detalles",
                    click:function () {
                        // console.log('presiono tarjeta');
                    },
                    data:'noTarjeta',
                    "render": function(noTarjeta) {//parámetros adicionales (type,row,meta)

                        var cardNumber = noTarjeta.substring(10);

                        return '<a id="details" data-card="'+ cardNumber +'" title="Ver detalles"><span aria-hidden="true" class="icon icon-list" data-icon="&#xe003;"></span></a>';
                    }
                }
            ]
        }else{
            dataColumns = [
                {
                    title: "Número de tarjeta",
                    data:'noTarjeta'
                },
                {
                    title: "Detalles",
                    data:'noTarjeta',
                    "render": function(noTarjeta) {//parámetros adicionales (type,row,meta)

                        var cardNumber = noTarjeta.substring(10);


                        return '<a id="details" data-card="'+ cardNumber +'" title="Ver detalles"><span aria-hidden="true" class="icon icon-list" data-icon="&#xe003;"></span></a>';
                    }
                }
            ]
        }



    }
    else{
        dataResponse = [];

        dataColumns = [
            {
                title: "Número de tarjeta",
                data:'noTarjeta'
            },
            {
                title: "Detalles",
                data:'noTarjeta',
                "render": function(noTarjeta) {//parámetros adicionales (type,row,meta)

                    var cardNumber = noTarjeta.substring(10);


                    return '<a id="details" data-card="'+ cardNumber +'" title="Ver detalles"><span aria-hidden="true" class="icon icon-list" data-icon="&#xe003;"></span></a>';
                }
            }
        ]
    }
    var dataTable = table.DataTable({
			"drawCallback": function (data) {
				if (datajson.length == 0) {
					$('#down-excel').css('display', 'none');
				}
			},
        dom: 'Bfrtip',
        "lengthChange": false,
        "pagingType": "full_numbers",
        "pageLength": 5, //Cantidad de registros por pagina
        "language": { "url": baseCDN + '/media/js/combustible/Spanish.json'}, //Lenguaje: español //cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json
			buttons: [
				{
					text: '<span id="down-excel" aria-hidden="true" class="icon" data-icon="&#xe05a" status="' + dataResponseCondition + '"></span>',
					className: 'down-report',
					titleAttr: 'Descargar reporte EXCEL'
				}
			],
				data: dataResponse, //Arreglo con los  valores del objeto
        columns: dataColumns
    });
}

//Funcion para recargar datatable con datos segun el estatus de la cuenta
function ChangeDataAccount(type) {
    $("#novo-table_wrapper").empty();
    $('#loading').show();
    table.dataTable().fnClearTable();
    table.dataTable().fnDestroy();
    table.empty();

    $.post(baseURL + '/' + isoPais + '/trayectos/modelo', {way: 'accounts', modelo: 'account',data:type})
        .done(function(data) {
            // console.log(data);
					if (data.code == undefined && JSON.parse(data.resp).lista != undefined && JSON.parse(data.resp).lista != []) {
						dataAccount = JSON.parse(data.resp);
						lang = data.lang;
						jsonData = dataAccount.lista;
                // jsonData = [];
                createTable(jsonData);

					} else {
						$('#loading').hide();

						catchErrorCode(data.code, data.msg, data.language.TAG_ACCEPT);
                jsonData = [];
                createTable(jsonData);
            }

        });
}


$('#filter-selected').on('click', '#accountAvailable, #accountAllocated', function(e){
    var container = $('#filter-selected'),
        thisId = e.target.id,
        parentId = e.target.parentNode.id,
        id;

    id = (thisId) ? thisId : parentId;

    $('#' + viewControl).removeClass('selected');
    $('#' + id)
        .removeClass('item-hover')
        .addClass('selected')
        .mouseleave(function() {
            if(!($(this) === $('#' + viewControl) && viewControl === 'accountAllocated')) {
                // $(this).addClass('item-hover');
            }
        });

    if(!(id === viewControl && viewControl === 'accountAllocated')) {
        // console.log(id);

        // prepareList (id);
    }

    viewControl = id;

    if(viewControl == 'accountAvailable'){
        type = 'available';
        ChangeDataAccount(type);
        accountAvailable.prop('disabled',true);
        accountAvailable.removeClass('item-hover');
        accountAllocated.addClass('item-hover');
        if(accountAllocated.prop('disabled') == true){
            accountAllocated.prop('disabled',false);
        }

    }
    if(viewControl == 'accountAllocated'){
        type = 'allocated';
        ChangeDataAccount(type);
        accountAvailable.prop('disabled',true);
        accountAllocated.removeClass('item-hover');
        accountAvailable.addClass('item-hover');
        if(accountAvailable.prop('disabled') == true){
            accountAvailable.prop('disabled',false);
        }
    }

});

$('#novo-container-body').on('click', '#down-excel', function (e) {
	e.preventDefault();
	var dataReport = {
		status: $('#down-excel').attr('status')
	}
	downReports('CuentasExcel', 'reportes_trayectos', dataReport, 'cuentas-xls');
});

