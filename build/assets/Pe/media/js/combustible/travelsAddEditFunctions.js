function sendData (modelo, method, travel)
{
    dataRequest = JSON.stringify(travel);
    $.ajax({
        url: baseURL + '/' + isoPais + '/trayectos/modelo',
        type: 'POST',
        data: {modelo: modelo, way: method, data: dataRequest},
        datatype: 'json',
        beforeSend: function(xrh, status) {
            $('#loading').removeClass('elem-hidden');
        }
    })
        .done(function(response) {
            lang = response.lang;
            $('#loading').addClass('elem-hidden');
            switch (response.code) {
                case 0:
                    var action = response.title;
                    switch (action) {
                        case 'list':
                            var listDriver = response.msg.driverList,
                                listVehi = response.msg.vehiclesList;

                            routeTravel(listDriver, listVehi);
                            break;
                        case 'detail':
                            putTravel(response.msg)
                            break;
                        case 'created':
                        case 'cancelled':
                            $('#msg-info').append('<p class="agrups">'+ response.msg +'</p>');
                            $('#close-info')
                                .removeClass('button-cancel')
                                .attr('finish', 'b')
                                .text(lang.TAG_ACCEPT);
                            notiSystem(lang.BREADCRUMB_TRAVELS, '/trayectos/viajes');
                            break;
                    }
                    break;
                case 1:
                    $('#msg-info').append('<p class="agrups">'+ response.msg +'</p>');
                    $('#close-info')
                        .removeClass('button-cancel')
                        .attr('finish', 'u')
                        .text(lang.TAG_ACCEPT);
                    notiSystem(response.title);
                    break;
                case 2:
                    $('#msg-info').append('<p class="agrups">'+ response.msg +'</p>');
                    $('#close-info')
                        .removeClass('button-cancel')
                        .attr('finish', 'b')
                        .text(lang.TAG_ACCEPT);
                    notiSystem(response.title, '/trayectos/viajes');
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
        })
        .fail(function(error) {
            $('#msg-info').append('<p class="agrups">'+ response.msg +'</p>');
            $('#close-info')
                .removeClass('button-cancel')
                .attr('finish', 'c')
                .text(lang.TAG_ACCEPT);
            notiSystem(response.title);
        });
}

function routeTravel (listDriver, listVehi)
{
    $('#driver, #vehicle').find('option').not('#list-driver, #list-vehicle').remove();
    $('#travelAdd').text(lang.TAG_FOLLOW);
    $('#clear-form').text(lang.TAG_RETURN);
    $('#get-route').removeClass('elem-hidden');
    $('html, body').animate({
        scrollTop: $('.breadcrumb-item-current').offset().top
    }, 0);
    $('#cRuta').removeClass('elem-hidden');
    $('#list-driver').text(lang.TRAVELS_SELECT);
    $.each(listDriver, function(key, value){
        $('#driver').append('<option value="'+ value.user +'">' + value.driver + '</option>')
    });

    $('#list-vehicle').text(lang.TRAVELS_SELECT);
    $.each(listVehi, function(key, value){
        $('#vehicle').append('<option value="'+ value.idVehicle +'">' + value.vehicle + '</option>')
    });
    $('#driver, #vehicle').removeClass('elem-hidden');
    mapBuild();
}

function putTravel(travelDetail)
{
    var status;
    $('#coordStart').val(travelDetail.orgL);
    // $('#destination').val(travelDetail.destination);
    $('#coordEnd').val(travelDetail.desL);
    $('#startDetail').val(travelDetail.beginDate);
    $('#finalDetail').val(travelDetail.finalDate);
    $('#drivDetail').val(travelDetail.driver);
    $('#vehiDetail').val(travelDetail.vehicle);
    $('#pStartDetail').val(travelDetail.origin);
    $('#orgL').val(travelDetail.orgL);
    $('#pEndDetail').val(travelDetail.destination);
    $('#desL').val(travelDetail.desL);


    status = travelDetail.status;
    mapBuild();

    if(status != 3 && status != 4) {
        $('#travelAdd').text(lang.TRAVELS_CANCEL);
        $('#travelAdd, #clear-form').attr('step', 'six');
        $('#travelAdd').prop('disabled', false);
    }

    $('#datailTravel').removeClass('elem-hidden');

    $('html, body').animate({
        scrollTop: $('.breadcrumb-item-current').offset().top
    }, 0);

}

function mapBuild()
{
    initMap('map-direction');
    if ($('#map-direction').attr('map') == 1) {
        initMap();
        $('#v-Ruta').removeClass('elem-hidden')
    }
    $('#cRuta').on('click', function (e) {
        e.preventDefault();
        var iniM = $('#map-direction').attr('map')
        $('#def-route').fadeIn('slow');
        $('#map-route').removeClass('map-route');
        $('#map-route').addClass('map-route-up');
        $('#map-content').removeClass('map-content');
        $('#map-content').addClass('map-content-up');
        if (iniM == 0 || iniM == 1) {
            setTimeout(function(){
                initMap('map-content');
            }, 1000);
            $('#map-direction').attr('map', 3);
        }

        $('#search').on('click', function(){
            getRoute();
            closeMap();
            $('html, body').animate({
                scrollTop: $('.breadcrumb-item-current').offset().top
            }, 0);
            $('#v-Ruta').removeClass('elem-hidden');
        });
        $("#cancel").on('click', function(){
            closeMap();
        });
    });

    $('.opciones_ruta').on('change', function(){
        getRoute();
    });


    $('#v-Ruta').on('click', function() {
        var see = $(this).attr('see');
        if(see == 'show') {
            $(this).attr('see', 'hide');
            $('#routes-alter').removeClass('elem-hidden');
            $('#v-Ruta').text(lang.TRAVELS_HIDE_INFO);
        } else {
            $(this).attr('see', 'show');
            $('#routes-alter').addClass('elem-hidden');
            $('#v-Ruta').text(lang.TRAVELS_VIEW_INFO);
        }
    });
}

//cerrar mapa
function closeMap ()
{
    $('#map-route').removeClass('map-route-up');
    $('#map-route').addClass('map-route');
    $('#map-content').removeClass('map-content-up');
    $('#map-content').addClass('map-content');
    $('#def-route').fadeOut('slow');
}

//Validar campos del formulario
function validar_campos()
{
    jQuery.validator.setDefaults({
        debug: true,
        success: 'valid'
    });

    jQuery.validator.addMethod('dateConfirm', function(value, element, regex) {
        if (element.id === 'last-date') {
            var firstDate = $('#first-date').val(),
                lastDate = $('#last-date').val();
            firstDate = formater(firstDate);
            lastDate = formater(lastDate);
            dayDiff = Math.ceil((lastDate - firstDate) / (1000 * 60 * 60 * 24));
            return dayDiff >= 0 ? true : false;
        }
    });

    jQuery.validator.addMethod('numberTime', function(value, element, regex) {
        var onlynum = /^[0-9]{2}$/,
            valid = onlynum.test(value) ? true : false;

        if ((element.id === 'first-minute' || element.id === 'last-minute') && valid == true) {
            valid = value < 60 ? true : false;
        }

        if ((element.id === 'first-hour' || element.id === 'last-hour') && valid == true) {
            valid = value < 24 ? true : false;
        }

        return valid;
    });

    jQuery.validator.addMethod('mayor', function(value) {
        var valid = true,
            firstDate = formater($('#first-date').val() + ' ' + $('#first-hour').val() + ':' + $('#first-minute').val() + ':00');
            lastDate = formater($('#last-date').val() + ' ' + $('#last-hour').val() + ':' + $('#last-minute').val() + ':00');
        if($('#first-date').val() == $('#last-date').val()) {
            valid = lastDate <= firstDate ? false : true;
        }

        return valid;
    });


    $('#formDate').validate({
        errorElement: "label",
        ignore: "",
        errorContainer: "#msg-date",
        errorClass: "field-error",
        validClass: "field-success",
        errorLabelContainer: "#msg-date",
        rules: {
            'first-date': {required: true},
            'first-hour': {required: true, numberTime: true},
            'first-minute': {required: true, numberTime: true},
            'last-date': {required: true, dateConfirm: true},
            'last-hour': {required: true, numberTime: true},
            'last-minute': {required: true, numberTime: true, mayor: true}
        },
        messages: {
            'first-date': {
                required: 'Indique la fecha de inicio'
            },
            'first-hour': 'La hora de inicio debe estar en formato 24h',
            'first-minute': 'Minutos de inicio debe estar entre 00 y 59',
            'last-date': {
                required: 'Indique la Fecha final',
                dateConfirm: 'La fecha de fin no puede ser anterior a la fecha inicio'
            },
            'last-hour': 'La hora de final debe estar en formato 24h',
            'last-minute': {
                required: 'Minutos de final debe estar entre 00 y 59',
                numberTime: 'Minutos de final debe estar entre 00 y 59',
                mayor: 'La hora fin debe ser mayor a la hora de inicio'
            }
        }
    });

    $('#formAdd').validate({
        errorElement: "label",
        ignore: ".ignore",
        errorContainer: "#msg-route",
        errorClass: "field-error",
        validClass: "field-success",
        errorLabelContainer: "#msg-route",
        rules: {
            'driver': {required: true},
            'vehicle': {required: true},
            'origin': {required: true},
            'destination': {required: true}
        },
        messages: {
            'driver': 'Seleccione un conductor',
            'vehicle': 'Seleccione un vehículo',
            'origin': 'Debe definir origen del viaje',
            'destination': 'Debe definir destino del viaje',
        }
    });
}
