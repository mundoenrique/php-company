function lisTravels(typeList)
{
    dataRequest = JSON.stringify(typeList);
    $.post(baseURL + '/' + isoPais + '/trayectos/modelo', {way: 'travels', modelo: 'travels', data: dataRequest})
    .done( function(response) {
        lang = response.lang;
        switch (response.code) {
            case 1:
            case 0:
                var travelsList = response.msg;
                displayTable(travelsList);
                break;
            case 2:
                $('#msg-info').append('<p class="agrups">'+ response.msg +'</p>');
                $('#close-info')
                    .removeClass('button-cancel')
                    .attr('finish', 'u')
                    .text(lang.TAG_ACCEPT);
                notiSystem(response.title);
                break;
            case 3:
                $('#msg-info').append('<p class="agrups">'+ response.msg +'</p>');
                $('#close-info')
                    .removeClass('button-cancel')
                    .attr('finish', 'c')
                    .text(lang.TAG_ACCEPT);
                notiSystem(response.title);
                break;
        }
        $('#loading').hide();
        $('#add').prop('disabled', false)
    });

}

// despliegue del listado de viajes
function displayTable(travelsList)
{
    $('#novo-table').DataTable({
        select: false,
        dom: 'Bfrtip',
        "lengthChange": false,
        "pagingType": "full_numbers",
        "pageLength": 5, //Cantidad de registros por pagina
        "language": {"url": baseCDN + '/media/js/combustible/Spanish.json'}, //Lenguaje: español //cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json
        data: travelsList, //Arreglo con los  valores del objeto
        columns: [
            {
                title: lang.TRAVEL_START_DATE,
                data:'startDate'
            },
            {
                title: lang.TRAVEL_END_DATE,
                data:'endDate'
            },
            {
                title: lang.TRAVEL_ORIGIN,
                data: 'origin'

            },
            {
                title: lang.TRAVEL_DESTINATION,
                data: 'destination'

            },
            {
                title: lang.TAG_STATUS,
                data: 'status'

            },
            {
                title: lang.TAG_ACTION,
                data: function(data) {//parámetros adicionales (type,row,meta)
                    var icon = (data.status == 'Cancelado' || data.status == 'Finalizado') ? '&#xe006;' : '&#xe028;',
                        title = (data.status == 'Cancelado' || data.status == 'Finalizado') ? lang.TRAVELS_VIEW : lang.TAG_CANCEL;

                    return '<a id="edit" id-travel="' + data.idTravel + '" title="'+ title +'"><span aria-hidden="true" class="icon icon-list" data-icon="' + icon + '"></span></a>'
                }
            }
        ]
    });
}

function prepareList(dataRequest)
{
    var filterList,
        typeList = new Object();

        switch (dataRequest) {
            case 'count':
                $('#filter-body').addClass('whith-form');
                $('#container-filter').hide();
                $('#footer-filter').hide();

                typeList.type = dataRequest;
                clearTable ();
                //llamado a la función que solicita la info para de los viajes
                lisTravels(typeList);
                break;
                $('#label-text').text(lang.TRAVELS_IN_PLATE);
            case 'vehicles':
                $('#label-text')
                    .text(lang.TRAVELS_IN_PLATE)
                    .attr('for', lang.TRAVELS_LABEL_IN);
                $('#filter-option').show();
                $('#search-option')
                    .hide()
                    .prop('disabled', true);
                $('#plate')
                    .show()
                    .prop('disabled', false);
                break;
            case 'date':
                $('#filter-option').hide();
                $('#search-option').prop('disabled', true);
                $('#plate').prop('disabled', true);
                break;
            default:
                var text = dataRequest === 'drivers' ? lang.TRAVELS_SELECT_DRIVER : lang.TRAVELS_SELECT_STATUS;
                $('#label-text')
                    .text(text)
                    .attr('for', lang.TRAVELS_LABEL_SEARCH);
                $('#filter-option').show();
                $('#search-option')
                    .show()
                    .prop('disabled', false);
                $('#plate')
                    .hide()
                    .prop('disabled', true);
                $.post(baseURL + '/' + isoPais + '/trayectos/modelo', {way: 'getList', modelo: 'travels', data: dataRequest})
                    .done(function(response){
                        switch (response.code) {
                            case 0:
                                buildSelect(response.msg);
                                break;
                            case 2:
                                $('#msg-info').append('<p class="agrups">'+ response.msg +'</p>');
                                $('#close-info')
                                    .removeClass('button-cancel')
                                    .attr('finish', 'u')
                                    .text(lang.TAG_ACCEPT);
                                notiSystem(response.title);
                                break;
                            case 3:
                                $('#msg-info').append('<p class="agrups">'+ response.msg +'</p>');
                                $('#close-info')
                                    .removeClass('button-cancel')
                                    .attr('finish', 'c')
                                    .text(lang.TAG_ACCEPT);
                                notiSystem(response.title);
                                break;
                        }

                    });

        }

    $('#search').prop('disabled', false);

}

function buildSelect(filterList)
{
    $('#load').text(lang.TRAVELS_SELECT);
    $.each(filterList, function(key, value){
        $('#search-option').append('<option value="' + value.val + '">' + value.text + '</option>')
    })
}

function clearTable()
{
    $("#novo-table_wrapper").remove();
    $('#novo-table').dataTable().fnClearTable();
    $('#novo-table').dataTable().fnDestroy();
    $('#table-travels').append('<table id="novo-table" class="hover cell-border" width="620px"></table>');
    $('#loading').show();
}

function validar_campos()
{
    jQuery.validator.setDefaults({
        debug: true,
        success: 'valid'
    });

    jQuery.validator.addMethod('dateConfirm', function(value, element, regex){
        element.id === 'first-date' ? firstDate = formater(value) : lastDate = formater(value);
        dayDiff = Math.ceil((lastDate - firstDate) / (1000 * 60 * 60 * 24));
        // console.log(dayDiff);
        return dayDiff < 31 ? true : false;
    })


    $('#form-filter').validate({
        errorElement: 'label',
        ignore: '',
        errorContainer: '#msg',
        errorClass: 'field-error',
        validClass: 'field-success',
        errorLabelContainer: '#msg',
        rules: {
            'search-option': {required: true},
            'plate': {required: true},
            'first-date': {required: true, dateConfirm: true},
            'last-date': {required: true, dateConfirm: true}
        },
        messages: {
            'search-option': 'Seleccione un opción',
            'plate': 'Indique la matricula del vehículo',
            'first-date': {
                required: 'Indique la fecha de inicio',
                dateConfirm: 'La consulta no puede ser mayor a un mes'
            },
            'last-date': {
                required: 'Indique la fecha final',
                dateConfirm: 'La consulta no puede ser mayor a un mes'
            }
        }
    });
}

function addEdit(id, func)
{
    $('#formulario').empty();
    $('#formulario').attr('action', baseURL + '/' + isoPais + '/trayectos/viajes/detalles');
    $('#formulario').append('<input type="hidden" name="data-id" value="' + id + '" />');
    $('#formulario').append('<input type="hidden" name="function" value="' + func + '" />');
    $('#formulario').submit();
}
