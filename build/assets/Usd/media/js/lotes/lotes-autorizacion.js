$(function(){

$('#lotes-general').show();

if( parseInt($('#cantXauth').val())<=0 ){
  $('#select-allA').hide();
}else{
 $('.listaxAuth #check-all').append('<em>Seleccionar todos ('+$('#cantXauth').val()+' lotes)</em>');
}
if( !$("#loteXdesa").val()&& !$('#lotesxAuth').val() ){
  $(".listaxAuth .checkbox-select").remove();
}


  var js_var ={
    loteF:"", numloteF:"", tipoloteF:"",
    loteA:"", numloteA:"", tipoloteA:""
  }



  //   LOTE POR FIRMAR

   $('#lotes-2').on('click','#select-allF', function() {

      $all = this.checked;

      if( $(this).is(':checked') ){
        js_var.loteF = "";
        js_var.numloteF="";
        js_var.tipoloteF="";
      }

      selectAllFirm();
  });


  $('#lotes-2').on('click', '#check-oneF', function() {
      if( $(this).is(':checked') ){

        js_var.loteF+= $(this).attr('value')+",";
        js_var.numloteF+= $(this).attr('numlote')+",";
        js_var.tipoloteF+= $(this).attr('ctipolote')+",";
      }else{
        js_var.loteF = js_var.loteF.replace( $(this).attr('value')+",", "" );
        js_var.numloteF = js_var.numloteF.replace( $(this).attr('numlote')+",", "" );
        js_var.tipoloteF = js_var.tipoloteF.replace( $(this).attr('ctipolote')+",", "" );
      }

  });

 // BOTON FIRMAR LOTE
  $('#lotes-2').on('click','#firma', function(){

    var pass = $('#clave').val();

    if(pass!="" && js_var.loteF!=""){

      pass = hex_md5( pass );
      $('#clave').val( '' );

      /*if( !(js_var.loteF instanceof Array) ){
        js_var.loteF = js_var.loteF.substr(0,js_var.loteF.lastIndexOf(','));
        js_var.loteF = js_var.loteF.split(',');
      }*/
      var $aux = $('#loading').dialog({title:"Firmando lote",modal: true, bgiframe: true, dialogClass: 'hide-close'});
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			var dataRequest = JSON.stringify ({
				data_lotes: js_var.loteF,
				data_pass:pass,
			})
			dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
      $.post(baseURL+isoPais+'/lotes/autorizacion/firmar',{ request: dataRequest,ceo_name: ceo_cook,plot: btoa(ceo_cook)}).done(function(data){
				data = JSON.parse(CryptoJS.AES.decrypt(data.code, data.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
         $aux.dialog('destroy');
        if(!data.ERROR){
					$('<div>Proceso exitoso.<h5>Listando lotes</h5></div>').dialog({
						title: "Firmando lote",
						modal: true,
						bgiframe: true
					});
         location.reload();
        }else{
           if(data.ERROR=='-29'){
                alert('Usuario actualmente desconectado'); location.reload();
              }else{
          notificacion('Firmando lote',data.ERROR);
          }
        }

      });
     // resetValuesFirm();
    }else{
      notificacion("Firmando lotes","Selecciona al menos un lote e ingresa tu contraseña");
    }

  });


 //  LOTES POR AUTORIZAR

$('#lotes-2').on('click','#select-allA', function() {

      $all = $(this);

      if( $all.is(':checked') ){
        js_var.loteA = "";
        js_var.numloteA="";
        js_var.tipoloteA="";
      }

      selectAllAuth();

  });


  $('#lotes-2').on('click', '#check-oneA', function() {

     if( $(this).is(':checked') ){
        js_var.loteA+= $(this).attr('value')+",";
        js_var.numloteA+= $(this).attr('numlote')+",";
        js_var.tipoloteA+= $(this).attr('ctipolote')+",";

      }else{
        js_var.loteA = js_var.loteA.replace( $(this).attr('value')+",", "" );
        js_var.numloteA = js_var.numloteA.replace( $(this).attr('numlote')+",", "" );
        js_var.tipoloteA = js_var.tipoloteA.replace( $(this).attr('ctipolote')+",", "" );
      }


  });

 $('#lotes-2').on('click','#button-autorizar', function(){

    var pass = $('#claveAuth').val();
    var osTipo = $('#selec_tipo_lote').val();

    if(pass!="" && js_var.loteA!="" && osTipo!=""){

      pass = hex_md5( pass );
      $('#claveAuth').val( '' );

      $('#loading').dialog({title:'Autorizando lotes', modal:true, resizable:false, dialogClass: 'hide-close', close:function(){$(this).dialog('destroy')}});

			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			var dataRequest = JSON.stringify ({
				data_lotes:js_var.loteA,
				 data_pass:pass,
				 data_tipoOS:osTipo
			})
			dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
      $.post(baseURL+isoPais+'/lotes/preliminar',{request: dataRequest,  ceo_name: ceo_cook,plot: btoa(ceo_cook)})
      .done(function(response){
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
        var code = data.code, title = data.title, msg = data.msg, dataCalc = data.data;
				$('#loading').dialog('destroy');
				if(code === 0) {
					$("#data-COS").attr('value', dataCalc);
					$("<div><h3>Proceso exitoso</h3><h5>Se generó el cálculo de la orden de servicio.</h5></div>").dialog({
						title:"Autorizando lotes",
						modal: true,
						resizable: false,
						draggable: false,
						open: function(event, ui) {
							$('.ui-dialog-titlebar-close', ui.dialog).hide();
						},
						buttons: {
							Siguiente: function () {
								var ceo_cook = decodeURIComponent(
									document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
								);
								$('#autorizacion').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
								$('#autorizacion').submit();
							}
						}
					});
				} else {
					notificacion(title, msg, code);
				}
    	});
    // resetValuesAuth();
    }else{
        notificacion("Autorizando lotes","<h2>Verifica que: </h2><h3>1. Has seleccionado al menos un lote.</h3><h3>2. Has ingresado tu contraseña.</h4><h3>3. Has seleccionado el tipo orden de servicio.</h3>");
    }
  });
 $('#lotes-2').on('click','#button-eliminar', function(){ //eliminar en autorizar

    var pass = $('#claveAuth').val();

    if(pass!="" && js_var.loteA!=""){

      pass = hex_md5( pass );
      $('#claveAuth').val( '' );

      /*if( !(js_var.loteA instanceof Array) ){
      js_var.loteA = js_var.loteA.substr(0,js_var.loteA.lastIndexOf(','));
      js_var.loteA = js_var.loteA.split(',');
      js_var.numloteA = js_var.numloteA.substr(0,js_var.numloteA.lastIndexOf(','));
      js_var.numloteA = js_var.numloteA.split(',');
      js_var.tipoloteA = js_var.tipoloteA.substr(0,js_var.tipoloteA.lastIndexOf(','));
      js_var.tipoloteA = js_var.tipoloteA.split(',');
      }*/

      eliminarLotes(js_var.loteA, js_var.numloteA, js_var.tipoloteA,pass);
     // resetValuesAuth();

    }else{
      notificacion("Autorizando lotes","Selecciona al menos un lote e ingresa tu contraseña");
    }

  });


  $('#lotes-2').on('click','.icon-desa', function(){


$item = $(this);

      var acnumlote = [$(this).attr('numlote')];

      var canvas = "<div id='dialog-confirm'>";
          canvas +="<p>Lote nro.: "+acnumlote+"</p>";
          canvas +="<fieldset><input type='password' id='pass' placeholder='Ingresa tu contraseña' size=30/>";
          canvas += "</fieldset><h5 id='msg'></h5></div>";

      var pass;
      var idlote = $(this).attr('idlote')+","; //[$(this).attr('idlote')];

      $(canvas).dialog({
        title: "Desasociar firma de lote",
        modal: true,
        bgiframe: true,
        close: function(){ $(this).dialog('destroy'); },
        buttons: {
          Desasociar: function(){

            pass = $(this).find( $('#pass')).val();

          if(pass!==""){
            pass = hex_md5( pass );
            $('#pass').val( '' );
            $(this).dialog('destroy');
						var $aux = $('#loading').dialog({title:"Desasociar firma de lote", modal: true,bgiframe: true});
						var ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);
						var dataRequest = JSON.stringify ({
							data_lotes: idlote,
							data_pass:pass
						})
						dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();

            $.post(baseURL+isoPais+'/lotes/autorizacion/desasociar',{ request: dataRequest,ceo_name: ceo_cook,plot: btoa(ceo_cook)}).done( function(response){
							data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
              $aux.dialog('destroy');
               if(!data.ERROR){

                 notificacion('Desasociar firma de lote', 'Revocación de firma exitosa');

                location.reload();

              }else{
               if(data.ERROR=='-29'){
                alert('Usuario actualmente desconectado'); location.reload();
              }else{

              // resetValuesAuth();
               notificacion('Desasociar firma de lote', data.ERROR);
             }
              }

            });

          }else{
            $(this).find( $('#msg')).text("Debes ingresar tu contraseña");
          }


          }
        }
      });

  });


 $('#lotes-2').on('click','#eliminarF', function(){ // boton eliminar en firma

    var pass = $('#clave').val();

    if(pass!="" && js_var.loteF!=""){

      pass = hex_md5( pass );
      $('#clave').val( '' );

      /*if( !(js_var.loteF instanceof Array) ){
      js_var.loteF = js_var.loteF.substr(0,js_var.loteF.lastIndexOf(','));
      js_var.loteF = js_var.loteF.split(',');
      js_var.numloteF = js_var.numloteF.substr(0,js_var.numloteF.lastIndexOf(','));
      js_var.numloteF = js_var.numloteF.split(',');
      js_var.tipoloteF = js_var.tipoloteF.substr(0,js_var.tipoloteF.lastIndexOf(','));
      js_var.tipoloteF = js_var.tipoloteF.split(',');
      }*/

      eliminarLotes(js_var.loteF, js_var.numloteF,js_var.tipoloteF,pass)
     //resetValuesFirm();

    }else{
			notificacion("Eliminar lotes","Selecciona al menos un lote e ingresa tu contraseña");
    }

  });


$('#lotes-2').on('click','#borrar', function(){
    $item = $(this);

      var pass;
      /*var idlote = [$(this).attr('idlote')];
      var acnumlote = [$(this).attr('numlote')];
      var ctipolote = [$(this).attr('ctipolote')];*/
      var idlote = $(this).attr('idlote')+",";
      var acnumlote = $(this).attr('numlote')+",";
      var ctipolote = $(this).attr('ctipolote')+",";

      var canvas = "<div id='dialog-confirm'>";
			canvas += "<p>Lote nro.: " + $(this).attr('numlote')+"</p>";
      canvas += "<fieldset><input type='password' id='pass' size=30 placeholder='Ingresa tu contraseña' class='text ui-widget-content ui-corner-all'/>";
      canvas += "<h5 id='msg'></h5></fieldset></div>";
      tabla = $(this).parents('table').attr('id');

      if( (!$('#clave').val()&&tabla=='table-firmar') || (!$('#claveAuth').val()&&tabla=='table-auth') ){

      $(canvas).dialog({
        title: "Eliminar Lote",
        modal: true,
        position: { my: "center top", at: "center 500" },
        bgiframe: true,
        close: function(){ $(this).dialog('destroy'); },
        buttons: {
          Eliminar: function(){

            pass = $(this).find( $('#pass')).val();


           if(pass!==""){

            pass = hex_md5( pass );
            $('#pass').val( '' );
            $(this).dialog('destroy');

            eliminarLotes(idlote,acnumlote,ctipolote,pass);

          }else{
            $(this).find( $('#msg')).text("Debes ingresar tu contraseña");
          }

          }
        }
      });

      }else{

        if($('#clave').val()!=''&&tabla=='table-firmar'){
          pass = hex_md5($('#clave').val());
        }else if($('#claveAuth').val()!=''&&tabla=='table-auth'){
          pass = hex_md5($('#claveAuth').val());
        }

        eliminarLotes(idlote,acnumlote,ctipolote,pass);

      }

  });


$('#lotes-2').on('click','#detalle', function(){ // autorizacion/detalleAuth
		var lote = $(this).attr('idlote');
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
    $(':checkbox').each(function(){this.checked=0;});
    $("form#detalleAuth").append('<input type="hidden" name="data-lote" value="'+lote+'" />');
    $("form#detalleAuth").submit();
});


function notificacion(titulo, mensaje, code) {
	code = code !== undefined ? code : '';
  var canvas = "<div>"+mensaje+"</div>";

  $(canvas).dialog({
    title: titulo,
    modal: true,
    maxWidth: 700,
    maxHeight: 300,
		bgiframe: true,
		draggable: false,
		open: function(event, ui) {
			$('.ui-dialog-titlebar-close', ui.dialog).hide();
		},
    buttons: {
      OK: function(){
				$(this).dialog("close");
				if(code === 3) {
					$(location).attr('href', baseURL+isoPais+'/login');
				}
			}
    }
  });
}



// PAGINACION DETALLE LOTE AUTORIZACION

function toDataTable($table){

  var dt = $table.dataTable( {
          "iDisplayLength": 10,
          'bRetrieve': true,
          "sPaginationType": "full_numbers",
          "oLanguage": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_, de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0, de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "<<",
                "sLast":     ">>",
                "sNext":     ">",
                "sPrevious": "<"
            }
          }
         } );

  return dt;
}


toDataTable($('#table-lote-detail'));
toDataTable($('#table-firmar'));
toDataTable($('#table-auth'));


$('#table-auth_paginate').on('click', function(){

  selectAllAuth();

});

function selectAllAuth(){

  if( $('#select-allA').is(':checked') ){
     $('.listaxAuth').find($(':checkbox')).each(function(){
          if($(this).attr('value')!==undefined ){
          js_var.loteA+= $(this).attr('value')+",";
          js_var.numloteA+= $(this).attr('numlote')+",";
          js_var.tipoloteA+= $(this).attr('ctipolote')+",";
          }
          this.checked = true;
        });
}else{
        js_var.loteA ="";
        js_var.numloteA="";
        js_var.tipoloteA="";
        $('.listaxAuth').find($(':checkbox')).each(function(){
        this.checked = false;
        });
      }

}

$('#table-firmar_paginate').on('click', function(){

  selectAllFirm();

});

function selectAllFirm(){

if( $('#select-allF').is(':checked') ){

        $('.lotes-contenedor-autorizacion').find($(':checkbox')).each(function(){
          if($(this).attr('value')!==undefined ){
          js_var.loteF+= $(this).attr('value')+",";
          js_var.numloteF+= $(this).attr('numlote')+",";
          js_var.tipoloteF+= $(this).attr('ctipolote')+",";
         }
          this.checked = 1;
        });


      }else{
        js_var.loteF ="";
        js_var.numloteF="";
        js_var.tipoloteF="";
        $('.lotes-contenedor-autorizacion').find($(':checkbox')).each(function(){
        this.checked = 0;
        });
      }


}




function resetValuesFirm(){
  js_var.loteF=""; js_var.numloteF=""; js_var.tipoloteF="";
  $.each($('.lotes-contenedor-autorizacion').find($(':checkbox')), function(){
    this.checked = 0;
  });
}

function resetValuesAuth(){
  js_var.loteA=""; js_var.numloteA=""; js_var.tipoloteA="";
  $.each($('.listaxAuth').find($(':checkbox')), function(){
    this.checked = false;
  });
}



$.each( $('.icon-desa'), function(){
   $(this).parents('tr').css('cursor','help');
   $(this).parents('tr').balloon({contents: $('#msg_2dafirma'), position:'top'});
});


function eliminarLotes(idlote,acnumlote,ctipolote,pass){

  var $aux = $('#loading').dialog({title:"Eliminando lote",modal: true, bgiframe: true, dialogClass: 'hide-close' });
	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
	var dataRequest = JSON.stringify ({
		data_lotes: idlote,
		data_acnumlote:acnumlote,
		data_ctipolote:ctipolote,
		data_pass:pass,
	})
	dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
         $.post(baseURL+isoPais+'/lotes/autorizacion/eliminarAuth',
          { request: dataRequest,ceo_name: ceo_cook,plot: btoa(ceo_cook)})
          .done(function(data){
						data = JSON.parse(CryptoJS.AES.decrypt(data.code, data.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
      $aux.dialog('destroy');
               if(!data.ERROR){

                 //$item.parents('tr').fadeOut("slow");

                 notificacion('Eliminando lote', 'Eliminación exitosa');

                 location.reload();
              }else{
               if(data.ERROR=='-29'){
                alert('Usuario actualmente desconectado'); location.reload();
                }else{

                notificacion('Eliminando lote', data.ERROR);
                }
              }

            });

}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

  $('#downPDF').on('click', function(){
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('#exportTo').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
    $('#exportTo').attr('action', baseURL + api + isoPais + "/reportes/detalleLoteAuthExpPDF");
    $('#data-lote').val($("#data-lote").val());
    $('#exportTo').submit();

  });

  $('#downXLS').on('click', function(){
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('#exportTo').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
    $('#exportTo').attr('action', baseURL + api + isoPais + "/reportes/detalleLoteAuthExpXLS");
    $('#data-lote').val($("#data-lote").val());
    $('#exportTo').submit();

  });

}); //Fin document ready
