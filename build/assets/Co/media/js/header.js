$(function(){ //document ready

  head_var = {
    idleTimer : null,
    out : null
    //inFormOrLink : false
  }

	// FILTER FIXED
if( $('.filter').length > 0 ){

var top = ($('.filter').offset().top-100) - parseFloat($('.filter').css('marginTop').replace(/auto/, 0));
       $(window).scroll(function (event) {

         var y = $(this).scrollTop();

          if (y >= top) {

            $('.filter').addClass('sub');

          } else {

            $('.filter').removeClass('sub');
         }
     });
}

//--FIN FILTER FIXED


// USER TIME OUT

  function resetTimer(){
    clearTimeout(head_var.idleTimer);
    head_var.idleTimer = setTimeout(function(){whenUserIdle()},900000); // 15 minutos de inactividad

  }
  $(document.body).bind('mousemove',resetTimer);
  $(document.body).bind('keydown',resetTimer);
  $(document.body).bind('click',resetTimer);

  resetTimer(); // Start the timer when the page loads


function whenUserIdle(){
  notificacion('Desconexión automática','<p>No se ha detectado actividad en la página.</p><p>Sesión próxima a expirar.</p>');
  head_var.out = setTimeout(function(){
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('#logout').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
		$('#logout').submit();
	}, 3000 );
}

function notificacion(title, msj){

  var canvas = "<div>"+msj+"</div>";

  $(canvas).dialog({
    title: title,
    modal: true,
    maxWidth: 700,
    maxHeight: 300,
    resizable: false,
    buttons: {
      salir: function(){
				var ceo_cook = decodeURIComponent(
					document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
				);
				$('#logout').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
        $('#logout').submit();
      },
      "Mantener sesion": function(){
        $(this).dialog("destroy");
        clearTimeout(head_var.out);
      }
    }
  });
}


//FIN USER TIME OUT

// SUB-MENU CONFIGURACION

$('#config').balloon({contents: $('.submenu'), position: 'bottom', classname: 'config-menuH'});


  $.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '<Ant',
    nextText: 'Sig>',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''};
  $.datepicker.setDefaults($.datepicker.regional['es']);



$(':input').on('click', function(){$('#ui-datepicker-div').css('top',$(this).position().top-100); $('#ui-datepicker-div').css('left',$(this).position().left);});


if (!navigator.cookieEnabled) {
	$('<div><h5>La funcionalidad de cookies de su navegador se encuentra desactivada.</h5><h4>Por favor vuelva activarla.</h4></div>').dialog({
		title: "Conexion empresas Online",
		modal: true,
		maxWidth: 700,
		maxHeight: 300,
		resizable: false,
		close: function(){$(this).dialog("destroy");},
		buttons: {
			Aceptar: function(){
				$(this).dialog("destroy");
			}
		}
	});

	$(location).attr('href','logout');
}


// scroll para el menu-
$.each( $('.menuHeader ul'), function(k,v){

  if( $(this).find('li').length > 4 ){

    $(this).addClass('ulScroll');

      $(this).find('#scrollup').show();
     $(this).find('#scrolldown').show();
     $(this).find('.ui-icon').css('background-image','url('+$('#ruta-cdn').val()+'media/css/images/ui-icons_ef8c08_256x240.png)');

     $(this).find('li').css('left','0');

     $(this).menu().removeClass('ui-menu-icons');
     $(this).menu().removeClass('ui-widget-content');
     $(this).menu().removeClass('ui-corner-all');
     $.each($(this).find('a'), function(){
        $(this).removeClass('ui-corner-all');
     });

  }
});


function validarNumerico(valor){
  if(valor.match(/^[0-9]*$/)){
    return true;
  }else{
    return false;
  }
}


$('#wrapper').on('keyup', '.nro',function(){
  if( ! validarNumerico($(this).val()) ){
    $(this).val('');
  }
});


}); //Fin document ready
