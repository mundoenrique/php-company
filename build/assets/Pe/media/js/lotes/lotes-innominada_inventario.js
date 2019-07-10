var	numlote  = "";
var	fecha_inicial = "";
var	fecha_final = "";
var	calendario = function(input){
		//,minDate: "+0D"
		$("#"+input).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			changeYear: true,
			numberOfMonths: 1,
			dateFormat:"dd/mm/yy"
		});
	},
	getTarjetas = function(numlote, acrif, acnomcia, dtfechorcarga, nmonto){
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('form#formulario').empty();
		$('form#formulario').append('<input type="hidden" name="data-numlote" value="' + numlote + '" />');
		$('form#formulario').append('<input type="hidden" name="data-acrif" value="' + acrif + '" />');
		$('form#formulario').append('<input type="hidden" name="data-acnomcia" value="' + acnomcia + '" />');
		$('form#formulario').append('<input type="hidden" name="data-dtfechorcarga" value="' + dtfechorcarga + '" />');
		$('form#formulario').append('<input type="hidden" name="data-nmonto" value="' + nmonto + '" />');
		$('form#formulario').attr('action',baseURL+isoPais+"/lotes/innominada/detalle");
		$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'" />');
		$('form#formulario').submit();
	},
	validate = function(){
		var contenido = "";
		var count = 0;
		var valid = false;
		if($("#nro_lote").val()==""&&$("#fecha_inicial").val()==""&&$("#fecha_final").val()==""){
			++count;
			valid = true;
			contenido+= "<h6>" + count + ". Ha ingresado un nro. de lote</h6>";
		}
		if(($("#fecha_inicial").val()==""||$("#fecha_final").val()=="")&&$("#nro_lote").val()==""){
			valid = true;
			if($("#fecha_inicial").val()==""){
				++count;
				contenido+= "<h6>" + count + ". Ha seleccionado una Fecha inicial</h6>";
			}
			if($("#fecha_final").val()==""){
				++count;
				contenido+= "<h6>" + count + ". Ha seleccionado una Fecha final</h6>";
			}
		}
		if(valid){
			notificacion("Solicitud de Innominadas","<h2>Verifica que:</h2>" + contenido);
		}
		return valid;
	},
	notificacion = function(titu, msj){
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
	},
	listaInnominadas = function(){
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('form#formulario').empty();
		$('form#formulario').append('<input type="hidden" name="data-numlote" value="' + getNumlote() + '" />');
		$('form#formulario').append('<input type="hidden" name="data-fecha_inicial" value="' + getFecha_inicial() + '" />');
		$('form#formulario').append('<input type="hidden" name="data-fecha_final" value="' + getFecha_final() + '" />');
		$('form#formulario').attr('action',baseURL+isoPais+"/lotes/innominada/afiliacion");
		$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'" />');
		$('form#formulario').submit();
	},
	setNumlote = function(){
		numlote = $("#nro_lote").val();
	},
	setFecha_inicial = function(){
		fecha_inicial = $("#fecha_inicial").val();
	},
	setFecha_final = function(){
		fecha_final = $("#fecha_final").val();
	},
	getNumlote = function(){
		return numlote;
	},
	getFecha_inicial = function(){
		if(fecha_inicial!=""){
			fecha_inicial = fecha_inicial.split("/");
			fecha_inicial = fecha_inicial[2] + "-" + fecha_inicial[1] + "-" + fecha_inicial[0];
		}
		return fecha_inicial;
	},
	getFecha_final = function(){
		if(fecha_final!=""){
			fecha_final = fecha_final.split("/");
			fecha_final = fecha_final[2] + "-" + fecha_final[1] + "-" + fecha_final[0];
		}
		return fecha_final;
	},
	toDataTable = function($table){
		var dt = $table.dataTable({
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
		     });
		return dt;
	};

$(function(){

	$("#buscarOS").on("click", function(){
		//if(!validate()){
			setNumlote();
			setFecha_inicial();
			setFecha_final();
			listaInnominadas();
		//}
	});

	$(".detalle-item").on("click", function(){
		getTarjetas($(this).attr("data-acnumlote"), $(this).attr("data-acrif"), $(this).attr("data-acnomcia"), $(this).attr("data-dtfechorcarga"), $(this).attr("data-nmonto"));
	});

	toDataTable($('#table-text-lotes-inventario'));

	calendario("fecha_inicial");
	calendario("fecha_final");

})
