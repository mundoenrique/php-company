$(function(){

$('.OS-icon ').attr('style', 'display: none !important;');
$('#lotes-general').show();

if($("#msg").val()){
	notificacion("ADVERTENCIA", $("#msg").val());
}

	COS_var = {
		fecha_inicio: "",
		fecha_fin: "",
		loteTipo: "",
		fecIsend :"",
		fecfsend :"",
		tablaOS: null,
		tablaOSNF: null
	}

	$("#tabla-datos-general").find(".OSinfo").hide(); // ocultar lotes de os

	 // MOSTRAR/OCULTAR LOTES SEGUN OS
	$("#tabla-datos-general").on("click","#ver_lotes", function(){

		var OS = $(this).parents("tr").attr('id');
		var $lotes = $("#tabla-datos-general").find("."+OS);

		$lotes.is(":visible") ? $lotes.fadeOut("slow") : $lotes.fadeIn("slow");
		$('.OSinfo').not("."+OS).hide();

	});


	// EVENTO BUSCAR OS SEGUN FILTRO
	$("#buscarOS").on("click", function(){

		var statuLote = $("#status_lote").val();


		if( statuLote!=='' && COS_var.fecha_inicio!=='' && COS_var.fecha_fin!=='' ){

			if( Date.parse(COS_var.fecha_fin) >= Date.parse(COS_var.fecha_inicio) ){

		$aux = $("#loading").dialog({title:'Buscando orden de servicio',modal:true, close:function(){$(this).dialog('destroy')}, resizable:false });
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			$('form#formulario').empty();
			$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
    		$('form#formulario').append('<input type="hidden" name="data-fechIn" value="'+COS_var.fecIsend+'" />');
    		$('form#formulario').append('<input type="hidden" name="data-fechFin" value="'+COS_var.fecfsend+'" />');
    		$('form#formulario').append('<input type="hidden" name="data-status" value="'+statuLote+'" />');
    		$('form#formulario').attr('action',baseURL+isoPais+"/consulta/ordenes-de-servicio");
    		$('form#formulario').submit();

			}else{
				notificacion("Buscar orden de servicio","Rango de fecha Incoherente.");
			}
		}else{
			notificacion("Buscar orden de servicio","<h2>Verifica que:</h2><h6>1. Has seleccionado un rango de fechas.</h6><h6>2. Has seleccionado un estatus de lote.</h6>")
		}
	});



	$("tbody").on("click",".viewLo", function(){  // ver detalle de lote

		var idLote = $(this).attr('id');

		$('form#detalle_lote').append('<input type="hidden" name="data-lote" value="'+idLote+'" />');
		$("#detalle_lote").submit();

	});


	$("#tabla-datos-general").on("click","#dwnPDF", function(){ // descargar orden de servicio

		var OS = $(this).parents("tr").attr('id');
			$aux = $("#loading").dialog({title:'Descargando archivo PDF',modal:true, close:function(){$(this).dialog('close')}, resizable:false });
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			$('form#formulario').empty();
			$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
    		$('form#formulario').append('<input type="hidden" name="data-idOS" value="'+OS+'" />');
    		$('form#formulario').append($('#data-OS'));
    		$('form#formulario').attr('action',baseURL+api+isoPais+"/consulta/downloadOS");
    		$('form#formulario').submit();
    		setTimeout(function(){$aux.dialog('destroy')},8000);
	});



	$(":radio").on("change", function(){
		$("#fecha_inicial").val('');
		$("#fecha_final").val('');
		var dias = $(this).val();

		var hoy = new Date();
		var resta = new Date(hoy.getTime() - (dias * 24 * 3600 * 1000));

		COS_var.fecha_inicio = (resta.getMonth()+1)+"/"+resta.getDate()+"/"+resta.getFullYear();
		COS_var.fecIsend = resta.getDate()+"/"+(resta.getMonth()+1)+"/"+resta.getFullYear();

		COS_var.fecha_fin = (hoy.getMonth()+1)+"/"+hoy.getDate()+"/"+hoy.getFullYear();
		COS_var.fecfsend = hoy.getDate()+"/"+(hoy.getMonth()+1)+"/"+hoy.getFullYear();


	});


	calendario( "fecha_inicial" );
	calendario( "fecha_final");


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


	function calendario(input){

		$("#"+input).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			changeYear: true,
			numberOfMonths: 1,
        	dateFormat:"dd/mm/yy",
			maxDate: "+0D",
			onSelect: function(selectedate){
				if(input=='fecha_inicial'){
					$("#fecha_final").datepicker('option','minDate',selectedate);
				}
				if(input=='fecha_final'){
					$("#fecha_inicial").datepicker('option','maxDate',selectedate);
				}
				if($("#fecha_inicial").val()!=''&&$("#fecha_final").val()!=''){
					$.each($(":radio"),function(){this.checked=0;});
					var aux = $("#fecha_inicial").val().split('/');
					COS_var.fecha_inicio = aux[1]+"/"+aux[0]+"/"+aux[2];
					COS_var.fecIsend = $("#fecha_inicial").val();

					aux = $("#fecha_final").val().split('/');
					COS_var.fecha_fin = aux[1]+"/"+aux[0]+"/"+aux[2];
					COS_var.fecfsend = $("#fecha_final").val();
				}
			}
		});
	}


	var paginar = function($tabla){
		var tabla = $tabla.dataTable( {
          "iDisplayLength": 10,
          'bDestroy':true,
          "sPaginationType": "full_numbers",
          "aaSorting":[],
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
		return tabla;
	}

	COS_var.tablaOS= paginar($('#tabla-datos-general'));
	COS_var.tablaOSNF= paginar($('#tablelotesNF'));

showOptions();
	$('.paging_full_numbers').on('click', function(){
		showOptions();
	});

function showOptions(){
	$('#tabla-datos-general tr').hover(
		function(){

			if( !$(this).hasClass('OSinfo') ){
				$(this).find('.OS-icon').show();
				$(this).css('margin-left',0);
			}
		},
		function(){

			var OS = $(this).attr('id');
			var $lotes = $("#tabla-datos-general").find("."+OS);

			if( !$(this).hasClass('OSinfo') && !$lotes.is(":visible") ){
				$(this).find('.OS-icon').hide();
				$(this).css('margin-left',31);
			}
		}
	);
}


$('#tabla-datos-general').on('click','#anular', function(){

 	var btnAnular = this;
	$item = $(this).parents('tr');
	var idOS = $(this).parents('tr').attr('id');


	var canvas = "<div id='dialog-confirm'>";
      canvas +="<p>Orden nro.: "+idOS+"</p>";
      canvas += "<fieldset><input type='password' id='pass' size=30 placeholder='Ingresa tu contraseña' class='text ui-widget-content ui-corner-all'/>";
      canvas += "<h5 id='msg'></h5></fieldset></div>";

      var pass;

      $(canvas).dialog({
        title: 'Anular Orden de Servicio',
        modal: true,
        resizable: false,
        close: function(){$(this).dialog("destroy");},
        buttons: {
          Anular: function(){
            pass = $(this).find('#pass').val();

            if( pass!==""){

              pass = hex_md5( pass );
              $('#pass').val( '' );
              $(this).dialog('destroy');
              var $aux = $('#loading').dialog({title:'Anulando Orden de Servicio' ,modal: true, resizable:false, close:function(){$aux.dialog('close');}});
							var ceo_cook = decodeURIComponent(
								document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
							);
							var dataRequest = JSON.stringify ({
								data_idOS:idOS,
								data_pass:pass
							})
							dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
              $.post(baseURL+api+isoPais+'/consulta/anularos',{request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)})
			   .done(function(response){
					data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
            $aux.dialog('destroy');

                if(!data.ERROR){
                  notificacion("Anulando Orden de Servicio",'Anulación exitosa');

                 COS_var.tablaOS.fnDeleteRow( COS_var.tablaOS.fnGetPosition(btnAnular.parentNode.parentNode) );

                }else{
                	if(data.ERROR=='-29'){

	 				alert('Usuario actualmente desconectado'); location.reload();
	 				}    else{
                  notificacion("Anulando Orden de Servicio",data.ERROR);   }
                }

              });

            }else{
              $(this).find( $('#msg') ).text('Debes ingresar tu contraseña');
            }

          }
        }
      });


	});




}); // fin document ready
