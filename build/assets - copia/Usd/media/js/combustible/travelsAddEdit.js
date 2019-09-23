var lang;
$(function() {
    var travelID = $('#travel').attr('id-travel'),
        func = $('#travel').attr('func'),
        travel = {},
        modelo = 'travels',
        method = '';

    if(func === 'register') {
        $('#loading').addClass('elem-hidden');
        $('#get-date').removeClass('elem-hidden');
        $('#formDate input').prop('disabled', false);
        $('#travelAdd').prop('disabled', false);

        //DataPicker
        calendario('first-date', 'add');
        calendario('last-date', 'add');
    } else if (func === 'update') {
        method = 'travelDetail';
        sendData (modelo, method, travelID);
    }


    $('#travelAdd').on('click', function(e) {
        e.preventDefault();
        var step = $(this).attr('step');
        // console.log(step);
        switch (step) {
            case 'first':
                validar_campos();
                if($('#formDate').valid() == true) {
                    $('#travelAdd, #clear-form').attr('step', 'second');
                    $('#get-date').addClass('elem-hidden');
                    $('#dateTrip').removeClass('elem-hidden');
                    $('#first-date').prop('disabled', true);
                    $('#last-date').prop('disabled', true);
                    travel = {
                        'firstDate': $('#first-date').val() + ' ' + $('#first-hour').val() + ':'
                                    + $('#first-minute').val() + ':00',
                        'lastDate': $('#last-date').val() + ' ' + $('#last-hour').val() + ':'
                                    + $('#last-minute').val() + ':00',
                    };
                    $('#start-date').val(travel.firstDate);
                    $('#final-date').val(travel.lastDate);
                    method = 'getDrivVehi';
                    sendData (modelo, method, travel);
                }
                break;
            case 'second':
                $('#origin, #destination').addClass('ignore');
                validar_campos();
                if($('#formAdd').valid() == true) {
                    $('#travelAdd, #clear-form').attr('step', 'third');
                    travel['driverId'] = $('#driver').val();
                    travel['driver'] = $('#driver option:selected').text();
                    travel['vehicleId'] = $('#vehicle').val();
                    travel['vehicle'] = $('#vehicle option:selected').text();
                    $('#pointStart').removeClass('elem-hidden');
                    $('#driverD').val($('#driver option:selected').text());
                    $('#vehicleD').val($('#vehicle option:selected').text());
                    $('#driver').hide();
                    $('#vehicle').hide();
                    $('#driverD').show();
                    $('#vehicleD').show();
                    initMap();
                    $('#start').val(travel.firstDate);
                    $('#final').val(travel.lastDate);
                    $('#driv').val(travel.driver);
                    $('#vehi').val(travel.vehicle);
                    $('#org').val(travel.origin);
                    $('#dest').val(travel.destination);
                    $('#origin').removeClass('ignore');
                }

                break;
            case 'third':
                validar_campos();
                if($('#formAdd').valid() == true) {
                    $('#travelAdd, #clear-form').attr('step', 'fourth');
                    $('#clear-form, #travelAdd').removeClass('elem-hidden');
                    $('#pointStart').addClass('elem-hidden');
                    initMap();
                    $('#pointEnd').removeClass('elem-hidden');
                }
                break;
            case 'fourth':
                $('#destination').removeClass('ignore');
                validar_campos();
                if($('#formAdd').valid() == true) {
                    // console.log('Entro');
                    $('#travelAdd, #clear-form').attr('step', 'five');
                    $('#get-route, #cRuta, #v-Ruta, #routes-alter').addClass('elem-hidden');
                    $('#resume').removeClass('elem-hidden');
                    $('#pointEnd').addClass('elem-hidden');
                    travel['origin'] = $('#org').val();
                    travel['destination'] = $('#dest').val();
                    travel['orgL'] = $('#orgL').val();
                    travel['desL'] = $('#desL').val();
                    travel['pStart'] = $('#pStart').val();
                    travel['pEnd'] = $('#pEnd').val();
                    $('#travelAdd').text(lang.TAG_END);
                    initMap();
                }
                break;
            case 'five':
                $('#resume, #clear-form, #travelAdd').addClass('elem-hidden');
                // $('#travel').addClass('elem-hidden');
                // $('#loading').removeClass('elem-hidden');
                method = 'addTravel';
                sendData (modelo, method, travel);
                break;
            case 'six':
                // $('#get-route, #v-Ruta, #travelAdd').addClass('elem-hidden');
                method = 'cancelTravel';
                sendData(modelo, method, travelID);
                break;
        }
    });

    $('#clear-form').on('click', function() {
        var step = $(this).attr('step');
        // console.log(step);
        switch (step) {
            case 'first':
                var form = 'formDate',
                    action = 'add';
                clearForm(form, action);
                break;
            case 'second':
                var form = 'formDate',
                    action = 'add';
                clearForm(form, action);
                $('#travelAdd, #clear-form').attr('step', 'first');
                $('#first-date').prop('disabled', false);
                $('#last-date').prop('disabled', false);
                $('#get-date').removeClass('elem-hidden');
                $('#clear-form').text(lang.TAG_RETURN);
                $('#travelAdd').text(lang.TAG_FOLLOW);
                $('#v-Ruta').text(lang.TRAVELS_VIEW_INFO);
                $('#get-route, #cRuta, #v-Ruta, #routes-alter').addClass('elem-hidden');
                $('#panel_ruta').text('show');
                break;
            case 'third':
                $('#travelAdd, #clear-form').attr('step', 'second');
                $('#pointStart').addClass('elem-hidden');
                $('#driver').show();
                $('#vehicle').show();
                $('#driverD').hide();
                $('#vehicleD').hide();

                break;
            case 'fourth':
                $('#travelAdd, #clear-form').attr('step', 'third');
                initMap();
                $('#pointEnd').addClass('elem-hidden');
                $('#pointStart').removeClass('elem-hidden');
                $('#clear-form').text(lang.TAG_RETURN);
                $('#travelAdd').text(lang.TAG_FOLLOW);
                break;
            case 'five':
                $('#travelAdd, #clear-form').attr('step', 'fourth');
                $('#travel').removeClass('elem-hidden');
                $('#pointEnd, #get-route').removeClass('elem-hidden');
                // $('#get-route, #cRuta, #v-Ruta, #routes-alter').addClass('elem-hidden');
                $('#resume').addClass('elem-hidden');
                initMap();
                break;

            // case 'six':
            //     $('#travelAdd, #clear-form').attr('step', 'third');
            //     $('#resume').addClass('elem-hidden');
            //     $('#travelAdd').text(lang.TAG_FOLLOW);
            //     $('#clear-form').text(lang.TAG_RETURN);
            //     $('#get-route, #v-Ruta, #cRuta').removeClass('elem-hidden');
            //     $('#v-Ruta')
            //         .attr('see', 'show')
            //         .text(lang.TRAVELS_VIEW_INFO);
            //     $('html, body').animate({
            //         scrollTop: $('.breadcrumb-item-current').offset().top
            //     }, 0);
            //     break;

        }
    });

	$('#down-pdf').on('click', function (e) {
		e.preventDefault();
		var dataReport = {
			travelId: $('#travel').attr('id-travel')
		};
		downReports('ViajesPdf', 'reportes_trayectos', dataReport, 'viajes-pdf');
	})

});
