var directionsDisplay,
    directionsService;
var urlGeo = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=',
    key    = 'AIzaSyDc-lvekbTTsJpJbbU7P1rfkIw0cRQ_bt8';

var startPosition   = [],
    endPosition     = [];

function initMap() {

    navigator.geolocation.getCurrentPosition( success, error );

    function success(position) {
	    directionsDisplay = new google.maps.DirectionsRenderer();
	    directionsService = new google.maps.DirectionsService();
        var coordenadas = position.coords;
        latLng = new google.maps.LatLng(coordenadas.latitude, coordenadas.longitude);
        //Marcador de ubicación
        marker = new google.maps.Marker({
            position: latLng,
            title: "Mi posición",
            animation: google.maps.Animation.DROP,
            draggable: true
        });
        var mapOptions = {
            center: latLng,
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById('map-direction'), mapOptions);
        marker.setMap(map);

        // Create the search box and link it to the UI element.
        var input = document.getElementById('origin');
        var searchBox = new google.maps.places.SearchBox(input);
        // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();
            if (places.length == 0) {
                return;
            }
            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();

            marker.setPosition(places[0].geometry.location);

            var markerPosition={
                "latitud": marker.position.lat(),
                "longitud": marker.position.lng()
            };

            startPosition = [marker.position.lat(),marker.position.lng()];
            $('#pStart').val(startPosition);

            $.post(urlGeo + marker.position.lat() + ',' + marker.position.lng() + '&key=' + key)
                .done(function(data) {
                    var directionStart = data.results[0].formatted_address;
                    $('#origin').val(directionStart);
                    $('#orgL').val(data.results[0].place_id);
                    $('#org').val(directionStart);
                });

            if (places[0].geometry.viewport) {
                bounds.union(places[0].geometry.viewport);
            } else {
                bounds.extend(places[0].geometry.location);
            }
            map.fitBounds(bounds);
        });

        //Marcador de ubicación
        marker2 = new google.maps.Marker({
            position: latLng,
            title: "Mi posición",
            animation: google.maps.Animation.DROP,
            draggable: true
        });

        var map2 = new google.maps.Map(document.getElementById('map-direction2'), mapOptions);
        marker2.setMap(map2);

        // Create the search box and link it to the UI element.
        var input2 = document.getElementById('destination');
        var searchBox2 = new google.maps.places.SearchBox(input2);

        // Bias the SearchBox results towards current map's viewport.
        map2.addListener('bounds_changed', function() {
            searchBox2.setBounds(map2.getBounds());
        });

        searchBox2.addListener('places_changed', function() {
            var places2 = searchBox2.getPlaces();
            if (places2.length == 0) {
                return;
            }
            // For each place, get the icon, name and location.
            var bounds2 = new google.maps.LatLngBounds();

            marker2.setPosition(places2[0].geometry.location);
            var markerPosition2={
                "latitud": marker2.position.lat(),
                "longitud": marker2.position.lng()
            };

            endPosition = [marker2.position.lat(),marker2.position.lng()];
            $('#pEnd').val(endPosition);

            $.post(urlGeo + marker2.position.lat() + ',' + marker2.position.lng() + '&key=' + key)
                .done(function(data) {
                    var directionEnd = data.results[0].formatted_address;
                    $('#destination').val(directionEnd);
                    $('#desL').val(data.results[0].place_id);
                    $('#dest').val(directionEnd);
                });

            if (places2[0].geometry.viewport) {
                bounds2.union(places2[0].geometry.viewport);
            } else {
                bounds2.extend(places2[0].geometry.location);
            }
            map2.fitBounds(bounds2);
        });

        google.maps.event.addListener(marker, 'dragend', function (event) {

            var lat  = event.latLng.lat(),
                lng = event.latLng.lng();
            var startPosition = [event.latLng.lat(),event.latLng.lng()];
            $('#pStart').val(startPosition);

            $.post(urlGeo + lat + ',' + lng + '&key=' + key)
                .done(function(data) {
                    var directionStart = data.results[0].formatted_address;
                    $('#origin').val(directionStart);
                    $('#orgL').val(data.results[0].place_id);
                    $('#org').val(directionStart);
                });
        });
        google.maps.event.addListener(marker2, 'dragend', function (event) {

            var lat  = event.latLng.lat(),
                lng = event.latLng.lng();

            var endPosition = [event.latLng.lat(),event.latLng.lng()];
            $('#pEnd').val(endPosition);

            $.post(urlGeo + lat + ',' + lng + '&key=' + key)
                .done(function(data) {
                    var directionEnd = data.results[0].formatted_address;
                    $('#destination').val(directionEnd);
                    $('#desL').val(data.results[0].place_id);
                    $('#dest').val(directionEnd);
                });
        });


        if($('#resume').hasClass('elem-hidden') == false){
//Ruta
            var mapOptionsResume = {
                center: latLng,
                zoom: 15,
                scrollwheel: false,
                navigationControl: false,
                mapTypeControl: false,
                scaleControl: false,
                draggable: false,
                disableDefaultUI: true,
                disableDoubleClickZoom: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            var mapResume = new google.maps.Map(document.getElementById('map-resume'), mapOptionsResume);
            var start = $('#orgL').val();
            var end = $('#desL').val();

            var request = {
                origin: {'placeId':String(start)},
                destination: {'placeId':String(end)},
                travelMode: google.maps.DirectionsTravelMode.DRIVING,
                unitSystem: google.maps.DirectionsUnitSystem.METRIC,
                provideRouteAlternatives: true
            };

            directionsService.route(request, function (response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setMap(mapResume);
                    directionsDisplay.setPanel($("#panel_ruta").get(0));
                    directionsDisplay.setDirections(response);
                } else {
                    notiSystemMap('Viajes',1,"");
                }
            });
        }

        if($('#datailTravel').hasClass('elem-hidden') == false) {

            //Detalle
            var mapOptionsDetail = {
                center: latLng,
                zoom: 15,
                scrollwheel: false,
                navigationControl: false,
                mapTypeControl: false,
                scaleControl: false,
                draggable: false,
                disableDefaultUI: true,
                disableDoubleClickZoom: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            var mapDetail = new google.maps.Map(document.getElementById('map-detail'), mapOptionsDetail);

            var requestDetail = {
                origin: $('#coordStart').val(),
                destination: $('#coordEnd').val(),
                travelMode: google.maps.DirectionsTravelMode.DRIVING,
            };

            directionsService.route(requestDetail, function (response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setMap(mapDetail);
                    directionsDisplay.setDirections(response);
                } else {
                    notiSystemMap('Viajes',1,"");
                }
            });
        }
    }
    function error(error){
        switch(error.code) {
            case error.PERMISSION_DENIED:
				var Mensaje = "Por favor habilite el permiso para la geolocalización en su navegador.";
				notiSystemMap ( "Viajes", 0, Mensaje );
                break;
            case error.POSITION_UNAVAILABLE:
				var Mensaje = "La ubicación no está disponible.";
				notiSystemMap ( "Viajes", 0, Mensaje );
                break;
            case error.TIMEOUT:
				var Mensaje =  "Se ha excedido el tiempo para obtener la ubicación.";
				notiSystemMap ( "Viajes", 0, Mensaje );
				break;
            case error.UNKNOWN_ERROR:
				var Mensaje =  "La ubicación no está disponible, por favor intente mas tarde.";
				notiSystemMap ( "Viajes", 0, Mensaje );
                break;
        }
    }
}

function routeDetail() {
    //Detalle
    var mapOptionsDetail = {
        center: latLng,
        zoom: 15,
        scrollwheel: false,
        navigationControl: false,
        mapTypeControl: false,
        scaleControl: false,
        draggable: false,
        disableDefaultUI: true,
        disableDoubleClickZoom: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var mapDetail = new google.maps.Map(document.getElementById('map-detail'), mapOptionsDetail);

    var requestDetail = {
        origin: '10.48394044581661,-66.86893584714358',
        destination: '10.491282969640187,-66.85743453488772',
        travelMode: google.maps.DirectionsTravelMode.DRIVING,
        // unitSystem: google.maps.DirectionsUnitSystem.METRIC,
        // provideRouteAlternatives: true
    };

    directionsService.route(requestDetail, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setMap(mapDetail);
            directionsDisplay.setDirections(response);
        } else {
            console.log("No existen rutas entre ambos puntos");
        }
    });
}

function notiSystemMap ( title, init = 1, Mensaje ) {

	let htmlMsg = (init == 1)?
		"<p>No existen rutas entre ambos puntos</p>":
			"<p>"+Mensaje+"</p>";

	$( "#msg" ).html(htmlMsg);

    $( "#msj-map").dialog({
        title : title,
        modal: 'true',
        width: '210px',
        // closeOnEscape: false,
        draggable: false,
        rezise: false,
        open: function(event, ui) {

            $('.ui-dialog-titlebar-close', ui.dialog).hide();
        }
    });

    $('button#accept-info').click(function () {

        $('#resume, #clear-form, #travelAdd').addClass('elem-hidden');
        $('#travelAdd, #clear-form').attr('step', 'third');
        $('#pointStart, #get-route, #clear-form, #travelAdd').removeClass('elem-hidden');
        $( "#msj-map" ).dialog('close');

		if(init == 1){	initMap(); }

        $('#clear-form').text(lang.TAG_RETURN);
        $('#travelAdd').text(lang.TAG_FOLLOW);

    });
}
