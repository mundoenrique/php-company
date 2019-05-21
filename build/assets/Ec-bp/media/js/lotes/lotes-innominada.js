var datatable;

	function calendario(input){
		$("#"+input).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			changeYear: true,
			numberOfMonths: 1,
			dateFormat:"mm/yy",
			minDate: "+0D"
		});
	}

  //Eliminar Lote

    function eliminar_lote(idlote, numlote){


      var canvas = "<div id='dialog-confirm'>";
	      canvas +="<p>Lote número: " + numlote + "</p>";
	      canvas += "<fieldset><input type='password' id='pass' size=30 placeholder='Ingrese su contraseña' class='text ui-widget-content ui-corner-all'/>";
	      canvas += "<h5 id='msg'></h5></fieldset></div>";

      $(canvas).dialog({
        title: 'Eliminar Lote',
        modal: true,
        resizable: false,
        close: function(){
        	$(this).dialog("destroy");
        },
        buttons: {
		  Eliminar: function(){
			var pass = $(this).find('#pass').val();
        	if(pass!==""){
				pass = hex_md5( pass );
	          	action_eliminar_lote(idlote, numlote, pass);

        	} else {
				$(this).find( $('#msg')).text("Debe ingresar su contraseña");
				return false;
          	}
			$(this).dialog("destroy");
          }
        }
      });

    }

    function solicitud_exitosa(){


      var canvas = "<div id='dialog-confirm'>";
	      canvas +="<p>La solicitud fue procesada de manera exitosa</p></div>";

      $(canvas).dialog({
        title: 'Solicitud procesada',
        modal: true,
        resizable: false,
        close: function(){
        	$(this).dialog("destroy");
        },
        buttons: {
		  Aceptar: function(){
			$(this).dialog("destroy");
          }
        }
      });

    }

    function action_eliminar_lote(idlote, numlote, pass){
		$aux = $("#loading").dialog({title:'Eliminando lote ' + numlote,modal:true, close:function(){$(this).dialog('close')}, resizable:false });
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$.post(baseURL+isoPais+'/lotes/innominada/eliminarLotesInnominadas', { "data-pass": pass, "data-idlote": idlote, "data-numlote": numlote, ceo_name: ceo_cook }).done( function(data){
			$aux.dialog('destroy');
				if(!data.ERROR){
					notificacion("Lote eliminado","<p>El nro. de lote <b>" + numlote + "</b> ha sido eliminado correctamente</p>");
					location.reload();

				}else{
					if(data.ERROR=='-29'){
						alert('Usuario actualmente desconectado');
						location.reload();

					}else{
						notificacion('Eliminando lote', data.ERROR);
					}
				}
		});
    }
  //--Fin Eliminar Lote

	function validate(){
		var
		contenido = "",
		count = 0,
		valid = false,
		cantTartjetas = $('#cant_tarjetas').val(),
		maxTarjetas = parseInt($('#cant_tarjetas').attr('max-tjta'));

		if($("#sucursal").val()==""){
			++count;
			contenido+= "<h6>" + count + ". Ha seleccionado una sucursal</h6>";
		}
		if($("#cant_tarjetas").val()==""||$("#cant_tarjetas").val()=="0"){
			++count;
			contenido+= "<h6>" + count + ". Ha ingresado una cantidad</h6>";
		}
		if($("#fecha_expira").val()==""){
			++count;
			contenido+= "<h6>" + count + ". Ha seleccionado una fecha de expiración</h6>";
		}
		if($("#embozo_1").val()==""){
			++count;
			contenido+= "<h6>" + count + ". Ha ingresado una Linea Embozo 1</h6>";
		}
		if(!/[^a-zA-Z0-9 ]/.test($("#embozo_2").val())){

		}else {
			++count;
			contenido+= "<h6>" + count + ". No haya ingresado caracteres especiales en Linea Embozo 2</h6>";
		}
		if(!/[^a-zA-Z0-9 ]/.test($("#embozo_1").val())){

		}else {
			++count;
			contenido+= "<h6>" + count + ". No haya ingresado caracteres especiales en Linea Embozo 1</h6>";
		}
		if(maxTarjetas !== 0 && cantTartjetas > maxTarjetas) {
			++count;
			contenido+= "<h6>"+count+". La cantidad de tarjetas no sea superior a "+maxTarjetas+"</h6>";
		}
		if(count > 0){
			notificacion("Solicitud de Innominadas","<h2>Verifique que:</h2>" + contenido);
			valid = true;
		}
		return valid;
	}

	function notificacion(titu, msj){
		var canvas = "<div>"+msj+"</div>";
			$(canvas).dialog({
				title : titu,
				modal:true,
				close: function(){$(this).dialog('destroy')},
				resizable:false,
				buttons:{
					OK: function(){
						$(this).dialog('destroy');
					}
				}
			});
	}

$(function(){

	calendario("fecha_expira");

	$('#procesar').on('click', function(){
		if(!validate()){
			$aux = $("#loading").dialog({title:'Procesando solicitud',modal:true, close:function(){$(this).dialog('close')}, resizable:false });
			var fecha_expira = $('#fecha_expira').val().split('/');
				fecha_expira = fecha_expira[0] + fecha_expira[1].substr(2);
				var ceo_cook = decodeURIComponent(
					document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
				);
			var arrData = {
				'data-cant' : $('#cant_tarjetas').val(),
				'data-monto' : $('#monto').val(),
				'data-lembozo1' : $('#embozo_1').val(),
				'data-lembozo2' : $('#embozo_2').val(),
				'data-codsucursal' : $('#sucursal').val(),
				'data-fechaexp' : fecha_expira,
				'ceo_name' : ceo_cook
			};

			$.post(baseURL+isoPais+'/lotes/innominada/createCuentasInnominadas', arrData).done( function(data){
				$aux.dialog('destroy');
				if(!data.ERROR){
					solicitud_exitosa();
	          		window.location.href = baseURL+isoPais+"/lotes/autorizacion";
				}else{
					if(data.ERROR=='-29'){
						alert('Usuario actualmente desconectado');
						location.reload();

					}else{
						notificacion("Imposible procesar solicitud",data.ERROR);
					}
				}
			});
		}
	});
	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
	$.post(baseURL+isoPais+'/lotes/innominada/listaSucursalesInnominadas', { ceo_name: ceo_cook}).done( function(data){
		$('#cargando').hide();
		$('#sucursal').prop('disabled', false);
		var html = "";
		$('#sucursal').empty();
			html = $('<option/>', {
				'value': '',
				'html': 'Selecciona'
			});
		$('#sucursal').append(html);
		if(data)
		{
			for (var i=0; i<data.length; i++) {

			html = $('<option/>', {
				'value': data[i].cod,
				'html': data[i].nomb_cia
			});
			$('#sucursal').append(html);
			}
		}
	});

	$('.borrar').on('click', function(){
		eliminar_lote($(this).attr('idlote'), $(this).attr('numlote'));
	});

	$('#table-lotes-inventario').dataTable({
		"iDisplayLength": 10,
		'bDestroy':true,
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
	});

})
