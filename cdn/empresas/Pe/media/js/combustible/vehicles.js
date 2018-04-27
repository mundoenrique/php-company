var idflota = $('#vehicle-gruop').attr('group-id'),
    nameFlota = $('#vehicle-gruop').attr('group-name'),
    idVehi,
    lang,
    action,
		selectStatus;
$('#idFlota').val(idflota);

$(function() {
    //llamado a la lista de vehículos de la flota
    var data = [{
        'idFlota': idflota,
        'action': 'lista',
        'idVehicle': ''
    }];

    listVehicle (data);

    //llamado a la función para registrar grupos de vehículos
    $('#add').on('click', function (){

        $('#func').val('register');
        var idVehi = 0;
        modalAddEdit(idVehi);
    });

    //llamado a la función para editar un vehículo
    $('#novo-table').on('click', '#editar', function(){
        idVehi = $(this).attr('id-vehi');
        $('#func').val('update');
        $('#idVehicle').val(idVehi);
        $('#send-save')
            .addClass('withoutChanges')
            .prop('disabled', true);

        $('#send-info, #close-info').text('');
        $('#msg-info').empty();
        $('#msg-info').append('<div id="loading" class="agrups"><img src=" ' + baseCDN + '/media/img/loading.gif' + '"></div>');
        notiSystem('msg', lang.VEHI_EDIT);
        data = [{
                'idFlota': idflota,
                'action': 'vehicle',
                'idVehicle': idVehi
            }];

        listVehicle (data);
    });

    //Evalua la acción de registrar o actualizar
    $('#formAddEdit input').on('change keyup', function(){

        action = $('#func').val();

        if (action === 'update') {
            $('#send-save')
                .prop('disabled', false)
                .removeClass('withoutChanges')
                .text(lang.TAG_SAVE_CHANGES)
        }
    });

    //Cambiar el estado de un vehículo
    $('#status').on('change', function(){
        var status = $(this).val(),
            valueStatus = $('#status option:selected').text(),
            msg = status ===   'DISASSOCIATE' ? lang.VEHI_DISASSOCIATE_OK :
                                    lang.VEHI_CHANGE_MSG + ' ' + lang.TAG_STATUS + ' ' + valueStatus;

        $('#msg-info').append('<p><class="agrups">' + msg + '</p>');
        $('#send-info').text(lang.TAG_ACCEPT);
        $('#close-info')
        .addClass('button-cancel')
        .text(lang.TAG_CANCEL);
        notiSystem('msg', lang.VEHI_CHANGE_STATUS);

        $('#send-info').on('click', function(){
            $('#send-info, #close-info').text('');
            $('#msg-info').empty();
            $('#msg-info').append('<div id="loading" class="agrups"><img src=" ' + baseCDN + '/media/img/loading.gif' + '"></div>');
            changeStatus(status);
        })
    });

    //Editar o registrar un vehículo
    $('#send-save').on('click', function(e){
        e.preventDefault();

        validar_campos();

        if($('#formAddEdit').valid() == true) {
            $("#add-edit").dialog("close");
            var title = action === 'update' ? lang.VEHI_EDIT : lang.VEHI_ADD,
                formAddEdit;

            $('#send-info, #close-info').text('');
            $('#msg-info').empty();
            $('#msg-info').append('<div id="loading" class="agrups"><img src=" ' + baseCDN + '/media/img/loading.gif' + '"></div>');
            notiSystem('msg', title);

            formAddEdit = $('#formAddEdit').serialize();

            addEditVehicle(formAddEdit);

        }
    });

		//Impresión reporte EXCEL de vehículos
		$('#table-drivers').on('click', '#down-excel', function(e) {
			e.preventDefault();
			var dataReport = {
				status: $('#vehicles-sel').val()
			}
			downReports('VehiculosExcel', 'reportes_trayectos', dataReport, 'vehiculos-xls');
		});


});

selectStatus = $("#status").html();
