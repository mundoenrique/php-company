var lang,
    action;
$(function() {
    $.post(baseURL + '/' + isoPais + '/trayectos/modelo', {way: 'vehicleGroups', modelo: 'vehicleGroups'})
        .done( function(data) {
            lang = data.lang;
            switch (data.code) {
                case 1:
                case 0:
                    var jsonData = data.msg;
                    $('#novo-table').DataTable({
                        select: false,
                        dom: 'Bfrtip',
                        "lengthChange": false,
                        "pagingType": "full_numbers",
                        "pageLength": 5, //Cantidad de registros por pagina
                        "language": { "url": baseCDN + '/media/js/combustible/Spanish.json'}, //Lenguaje: español //cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json
											buttons: [],
												data: jsonData, //Arreglo con los  valores del objeto
                        columns: [
                            {
                                title: lang.TAG_NAME,
                                data:'name'
                            },
                            {
                                title: lang.TAG_DESCRIPTION,
                                data:'description'
                            },
                            {
                                title: lang.TAG_STATUS,
                                data: function (data) {
                                    return data.status == 1 ? lang.TAG_ACTIVE : lang.TAG_INACTIVE;
                                }

                            },
                            {
                                title: lang.TAG_ACTION,
                                data: function(data) {//parámetros adicionales (type,row,meta)
                                    return '<a id="editar" data-id="' + data.idFleet + '" title="'+ lang.TAG_EDIT +'"><span aria-hidden="true" class="icon icon-list" data-icon="&#xe08f;"></span></a>'  +
                                            '<a id="vehicles" data-id="'+ data.idFleet +'" data-name="' + data.name +'" title="' + lang.GROUP_VIEW_VEHICLES + '"><span aria-hidden="true" class="icon icon-list" data-icon="&#xe003;"></span></a>';
                                }
                            }
                        ]
                    });
                    break;
                case 3:
                    $('#msg-info').append('<p class="agrups">'+ data.msg +'</p>');
                    $('#close-info')
                        .attr('finish', 'c')
                        .text(lang.TAG_ACCEPT);
                    notiSystem('msg', data.title);
                    break;
            }
            $('#loading').hide();
            $('#add').prop('disabled', false)
        });

    //llamado a la función para registrar grupos de vehículos
    $('#add').on('click', function (){

        $('#send-save').attr('function', 'register');
        $('#func').val('register');
        var id = 0;
        addEdit(id);
    });

    //llamado a la función para actualizar grupos de vehículos
    $('#novo-table').on('click', '#editar',function () {

        var id = $(this).data("id");
        $('#func').val('update');
        $('#send-save')
            .attr('function', 'update')
            .prop('disabled', true)
            .addClass('withoutChanges');
        addEdit(id)
    });

    //Registrar o actualizar un grupo de vehículos
    $('#formAddEdit').on('change keyup', function(){
        action = $('#func').val();

        if (action === 'update') {
            $('#send-save')
                .prop('disabled', false)
                .removeClass('withoutChanges')
                .text(lang.TAG_SAVE_CHANGES)
        }
    });

    $('#send-save').on('click', function(e) {
        e.preventDefault();

        validar_campos();

        if($('#formAddEdit').valid() == true) {
            $("#add-edit").dialog("close");
            var title = action === 'update' ? lang.GROUP_EDIT : lang.GROUP_ADD;

            $('#send-info, #close-info').text('');
            $('#msg-info').empty();
            $('#msg-info').append('<div id="loading" class="agrups"><img src=" ' + baseCDN + '/media/img/loading.gif' + '"></div>');
            notiSystem('msg', title);

            var formAddEdit = $('#formAddEdit').serialize();

            $.post(baseURL + "/"+isoPais + '/trayectos/modelo',{way: 'addEditGroups', data: formAddEdit, modelo: 'vehicleGroups'})
                .done(function (response) {
                    $('#msg-info').empty();
                    switch (response.code) {
                        case 0:
                            $('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
                            $('#close-info')
                                .removeClass('button-cancel')
                                .attr('finish', 'g')
                                .text(lang.TAG_ACCEPT);
                            break;
                        case 1:
                            $('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
                            $('#close-info')
                            .removeClass('button-cancel')
                            .text(lang.TAG_ACCEPT);
                            break;
                        case 2:
                            $('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
                            $('#close-info')
                                .removeClass('button-cancel')
                                .attr('finish', 'g')
                                .text(lang.TAG_ACCEPT);
                            notiSystem('msg', response.title, '320px', 'gruposVehiculos');
                            break;
                        case 3:
                            $('#msg-info').append('<p class="agrups">' + response.msg + '</p>');
                            $('#close-info')
                                .removeClass('button-cancel')
                                .attr('finish', 'c')
                                .text(lang.TAG_ACCEPT);
                            break;
                    }
                });
        }
    });


    //llamado a la lista de vehiculos del grupo
    $('#novo-table').on('click', '#vehicles', function () {

        var id = $(this).data('id'),
            name = $(this).data('name');
        vehicles(id, name);
    });

});
