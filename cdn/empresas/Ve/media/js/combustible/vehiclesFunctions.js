//llamado a la lista de vehiculos o al detalle de un vehículo
function listVehicle (data) {
    $.post(baseURL + '/' + isoPais + '/trayectos/modelo', {way: 'vehicles', modelo: 'vehicles', data: data})
            .done(function(response) {
            lang = response.lang;
            $('#msg-info').empty();
            switch (response.code) {
                case 0:
                case 1:
                    var vehiclesList = response.msg,
                        titleList = response.title;

                    titleList === 'lista' ? displayTable(vehiclesList) : modalAddEdit(data[0].idVehicle, vehiclesList);

                    break;
                case 2:
                    $('#msg-info').append('<p><class="agrups">' + response.msg + '</p>');
                    $('#close-info')
                        .attr('finish', 'up')
                        .text(lang.TAG_ACCEPT);
                    notiSystem('msg', response.title);
                    break;
                case 3:
                    $('#msg-info').append('<p><class="agrups">' + response.msg + '</p>');
                    $('#close-info')
                        .attr('finish', 'c')
                        .text(lang.TAG_ACCEPT);
                    notiSystem('msg', response.title);
                    break;
            }
            $('#add').prop('disabled', false);
            $('#loading').hide();
        });
}
// despliegue del listado de veículos
function displayTable (vehiclesList) {
    $('#novo-table').DataTable({
        select: false,
        dom: 'Bfrtip',
        "lengthChange": false,
        "pagingType": "full_numbers",
        "pageLength": 5, //Cantidad de registros por pagina
        "language": { "url": baseCDN + '/media/js/combustible/Spanish.json'}, //Lenguaje: español //cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json
        data: vehiclesList, //Arreglo con los  valores del objeto
        columns: [
            {
                title: lang.VEHI_PLATE,
                data: 'plate'
            },
            {
                title: lang.VEHI_BRAND,
                data: 'brand'
            },
            {
                title: lang.VEHI_MODEL,
                data: 'model'
            },
            {
                title: lang.VEHI_YEAR,
                data: 'year'
            },
            {
              title: lang.TAG_STATUS,
                data: 'status'
            },
            {
                title: lang.TAG_ACTION,
                data: function (list) {
                    return '<a id="editar" id-vehi="' + list.idVehicle + '" title="'+ lang.TAG_EDIT +'"><span aria-hidden="true" class="icon icon-list" data-icon="&#xe08f;"></span></a>'
                }
            }
        ]
    });
}

//Modal para editar y registrar un vehículo
function modalAddEdit (idVehicle, vehiclesList) {
    var title = idVehicle !== 0 ? lang.VEHI_EDIT : lang.VEHI_ADD;

    if (idVehicle !== 0) {

        $('#msg-system').dialog('close');
        $('#statusDriver').show();
        $('#plate').val(vehiclesList.plate);
        $('#brand').val(vehiclesList.brand);
        $('#model').val(vehiclesList.model);
        $('#year').val(vehiclesList.year);
        $('#capacity').val(vehiclesList.capacity);
        $('#odometer').val(vehiclesList.odometer);
        $('#status > option[value="' + vehiclesList.status + '"]').prop('selected', true);
        $("#send-save").text(lang.TAG_WITHOUT_CHANGES);
        notiSystem('groups', title, '505px');
    } else {

        $('#formAddEdit')[0].reset();
        $('#statusDriver').hide();
        $('#send-save').text(lang.TAG_SEND);
        notiSystem('groups', title, '505px');
    }
}

//Validar campos del formulario
function validar_campos() {
    var year = new Date();
    year = year.getFullYear() + 1;
    console.log(year);
    jQuery.validator.setDefaults({
        debug: true,
        success: "valid"
    });

	jQuery.validator.addMethod('regval', function (value, element) {

		let patron1 = /([A-Za-z]{3}-[0-9]{3})/; // XXX-NNN
		let patron2 = /([0-9]{2}[A-Za-z]{3}[0-9]{2})/; //NNXXXNN
		let patron3 = /([A-Za-z]{3}-[0-9]{2}[A-Za-z]{1})||([A-Za-z]{3}\s[0-9]{2}[A-Za-z]{1})/;//XXX-NNX
		let patron4 = /([A-Za-z]{3}[0-9]{2}[A-Za-z]{1})/; //XXXNNX

		//	Formato Antiguo
		let patron5 = /([A-Za-z]{3}\s[0-9]{2}[A-Za-z]{1})||([A-Za-z]{3}\s[0-9][A-Za-z]{1})/; // Particulares: XXX NNX
		let patron6 = /([A-Za-z]{3}\s[0-9]{3})||([A-Za-z]{3}[0-9]{3})/; // Moto: XXX NNN
		let patron7 = /([0-9]{2}[A-Za-z]{1}\s[A-Za-z]{3})||([0-9]{2}[A-Za-z]{1}[A-Za-z]{3})/; // Carga: NNX XXX

		//Formato Actual
		let patron8 = /([A-Za-z]{2}[0-9]{3}[A-Za-z]{2})/; // Particulares: XXNNNXX
		let patron9 = /([A-Za-z]{2}[0-9]{1}[A-Za-z]{1}[0-9]{2}[A-Za-z]{1})/; // Moto: XXNXNNX
		let patron10 = /([A-Za-z]{1}[0-9]{2}[A-Za-z]{2}[0-9]{1}[A-Za-z]{1})/; // Carga: XNNXXNX

		//Otros
		let patron11 = /([A-Za-z]{2}[0-9]{4})/; // Particulares: XXNNNN

		if( patron1.test(value) || patron2.test(value) ||
			patron3.test(value) || patron4.test(value) || patron5.test(value) ||
			patron6.test(value) || patron7.test(value) || patron8.test(value) ||
			patron9.test(value) || patron10.test(value)|| patron11.test(value)){
				return true;
		}
	}, 'Formato válido para la placa XXX-000, 00XXX00, XXX-00X, XXX00X, XXX 00X, XXX 000, 00X XXX, XX000XX, XX0X00X, X00XX0X');

	var formaterModel = /^[a-zA-Z0-9-\s]*$/;

    $("#formAddEdit").validate({
        errorElement: "label",
        ignore: "",
        errorContainer: "#msg",
        errorClass: "field-error",
        validClass: "field-success",
        errorLabelContainer: "#msg",

        rules: {
            'plate': {regval: true},
            'brand': {required: true, lettersonly: true, maxlength: 25},
            'model': {required: true, pattern: formaterModel, maxlength: 25},
            'year' : {required: true, digits: true, minlength: 4, maxlength: 4, max: year},
            'capacity' : {required: true, digits: true, maxlength: 3},
            'odometer' : {required: true, digits: true, maxlength: 7}
        },

        messages:{
            'brand': 'La marca no admite números ni caracteres especiales, max 25 caracteres',
            'model': 'El modelo solo admite el carácter especial \"-\", max 25 caracteres',
            'year': 'Formato válido para el año 0000, max ' + year,
            'capacity': 'La capacidad debe ser un número, max 3 caracteres',
            'odometer': 'El odómetro debe ser un número, max 7 caracteres'
        }
    });
}

//Cambiar el estado del vehículo
function changeStatus (status) {

    var data = [{
        'idFlota': idflota,
        'idVehicle': idVehi,
        'status': status
    }];

    $.post(baseURL + '/' + isoPais + '/trayectos/modelo', {way: 'changeStatus', modelo: 'vehicles', data: data})
        .done(function(response){
            var langs = response.lang;
            $('#msg-info').empty();
            switch (response.code) {
                case 0:
                    $('#msg-info').append('<p><class="agrups">' + response.msg + '</p>');
                    $('#close-info')
                        .removeClass('button-cancel')
                        .attr('finish', 'up')
                        .text(lang.TAG_ACCEPT);
                    break;
                case 2:
                    $('#msg-info').append('<p><class="agrups">' + response.msg + '</p>');
                    $('#close-info')
                        .removeClass('button-cancel')
                        .attr('finish', 'up')
                        .text(lang.TAG_ACCEPT);
                    notiSystem('msg', response.title)
                    break;
                case 3:
                    $('#msg-info').append('<p><class="agrups">' + response.msg + '</p>');
                    $('#close-info')
                        .removeClass('button-cancel')
                        .attr('finish', 'c')
                        .text(lang.TAG_ACCEPT);
                    notiSystem('msg', response.title);
                    break;
            }
        });
}

//Ediatar o registrar un vehículo
function addEditVehicle (formAddEdit) {
    $.post(baseURL + '/' + isoPais + '/trayectos/modelo', {way: 'addEditVehicles', modelo: 'vehicles', data: formAddEdit})
        .done(function(response){
            $('#msg-info').empty();
            var langReg = response.lang;
            switch (response.code) {
                case 0:
                    $('#msg-info').append('<p><class="agrups">' + response.msg + '</p>');
                    $('#close-info')
                        .removeClass('button-cancel')
                        .attr('finish', 'up')
                        .text(lang.TAG_ACCEPT);
                    break;
                case 1:
                    $('#msg-info').append('<p><class="agrups">' + response.msg + '</p>');
                    $('#close-info')
                    .removeClass('button-cancel')
                    .text(lang.TAG_ACCEPT);
                    break;
                case 2:
                    $('#msg-info').append('<p><class="agrups">' + response.msg + '</p>');
                    $('#close-info')
                        .removeClass('button-cancel')
                        .attr('finish', 'up')
                        .text(lang.TAG_ACCEPT);
                    notiSystem('msg', response.title);
                    break;
                case 3:
                    $('#msg-info').append('<p><class="agrups">' + response.msg + '</p>');
                    $('#close-info')
                        .removeClass('button-cancel')
                        .attr('finish', 'c')
                        .text(lang.TAG_ACCEPT);
                    notiSystem('msg', response.title);
                    break;

            }
        });
}
