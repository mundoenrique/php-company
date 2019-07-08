$(function() {

// Datos a enviar

var widget_var = {
 	acrif:"",
 	acnomcia:"",
 	acrazonsocial:"",
 	acdesc:"",
 	accodcia:"",
	accodgrupoe:"",

 	idproducto:"", nombprod:"", marcprod:""
}

// -- fin datos a enviar



// Cambiar empresa


	// $("#sEmpresa").on("click",function(){

		$('#sEmpresa').hide();

		$.getJSON(baseURL+api+isoPais+'/empresas/lista').always(function(response) {
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
			$("#widget-info-2").find($('img#cargando')).remove();

			$('#sEmpresaS').show();

			if(!data.ERROR){
  				$.each(data.lista, function(k,v){
					$("#empresasS").append('<option value="'+v.acrif+'" acnomcia="'+v.acnomcia+'" acrazonsocial="'+v.acrazonsocial+'" acdesc="'+v.acdesc+'" accodcia="'+v.accodcia+'" accodgrupoe='+v.accodgrupoe+'>'+v.acnomcia+'</option>');
				});
  			}else{
  				if(data.ERROR=='-29'){
  				alert('Usuario actualmente desconectado'); location.reload();
  				}
  			}
   		});

	// });


//--Fin Cambiar empresa

// Seleccionar empresa

	$("#empresasS").on("change",function(){
		widget_var.acrif = $(this).val();
		widget_var.acnomcia = $('option:selected', this).attr('acnomcia');
		widget_var.acrazonsocial = $('option:selected', this).attr('acrazonsocial');
 		widget_var.acdesc = $('option:selected', this).attr('acdesc');
		widget_var.accodcia = $('option:selected', this).attr('accodcia');
		widget_var.accodgrupoe = $('option:selected', this).attr('accodgrupoe');

		if (widget_var.acrif!=0) {
			$('#productosS').empty();
			$("#productosS").append('<option>Cargando...</option>');
			$(this).attr('disabled',true);
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);

			var dataRequest = JSON.stringify ({
				acrif: widget_var.acrif
			})
				dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
				$.post(baseURL+api+isoPais+"/producto/lista", {request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)} )
				.done(function(response){
					data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
				$("#empresasS").removeAttr('disabled');
				$('#productosS').empty().css('display', 'block');
				$("#productosS").append('<option value="0">Seleccione un producto</option>');


				if(!data.ERROR){
				$.each(data, function(k,v){
					$("#productosS").append('<option value="'+v.idProducto+'" nombre='+v.nombre+' marca='+v.marca+' >'+v.descripcion+" / "+v.marca.toUpperCase()+'</option>');
				});
				}else{
						if(data.ERROR=='-29'){
						alert('Usuario actualmente desconectado'); location.reload();
						}
					}
			});
		}
	});

//--Fin Seleccionar empresa


// Seleccionar producto

	$("#productosS").on("change", function(){

		widget_var.idproducto = $(this).val();
		widget_var.nombprod = $('option:selected', this).attr('nombre');
		widget_var.marcprod = $('option:selected', this).attr('marca');

	});

//--Fin Seleccionar producto

//	Enviar todo

	$('#aplicar').on('click',function(){
		var change = false;
		if($('#empresasS').val() != 0 && $('#productosS').val() != 0) {
			change = true;
		}
		if(change){
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);

			var dataRequest = JSON.stringify ({
				data_accodgrupoe:widget_var.accodgrupoe,
				data_acrif:widget_var.acrif,
				data_acnomcia:widget_var.acnomcia,
				data_acrazonsocial:widget_var.acrazonsocial,
				data_acdesc:widget_var.acdesc,
				data_accodcia:widget_var.accodcia,
				data_idproducto:widget_var.idproducto,
				data_nomProd:widget_var.nombprod,
				data_marcProd:widget_var.marcprod,
				llamada:'productos'
			});
				dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
				$.post( baseURL+"api/v1/"+isoPais+"/empresas/cambiar", {request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)} )
				.done(function(response){
					data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))

          if(data === 1){
          	$(location).attr('href',baseURL+isoPais+"/dashboard/productos/detalle");
          }else{
          	MarcarError('Intente de nuevo');
          }
				}
			);
		}else{
      		MarcarError('Debe seleccionar empresa y producto');
    }
  });

function MarcarError(msj){
  $.balloon.defaults.classname = "error-login-2";
  $.balloon.defaults.css = null;
  $("#aplicar").showBalloon({position: "left", contents: msj});  //mostrar tooltip
  setTimeout( function(){ $("#aplicar").hideBalloon({position: "left", contents: msj}); }, 2500 );  // ocultar tooltip
}

//-- Fin enviar todo


 // BTN OTROS PROGRAMAS

 $('#sPrograms').on('click',function(){
 	$(location).attr('href',baseURL+isoPais+"/dashboard/programas");
 });

 //--FIN BTN OTROS PROGRAMS


	// widget FIXED

var top = ($('#sidebar-products').offset().top-100) - parseFloat($('#sidebar-products').css('marginTop').replace(/auto/, 0));
       $(window).scroll(function (event) {

         var y = $(this).scrollTop();

          if (y >= top) {

            $('#sidebar-products').addClass('sub-widget');

        } else {

            $('#sidebar-products').removeClass('sub-widget');
         }
     });

//--FIN widget FIXED

});
