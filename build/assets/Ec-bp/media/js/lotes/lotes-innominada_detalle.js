var	numlote  = "";
var	notificacion = function(titu, msj){
		var canvas = "<div>"+msj+"</div>";
			$(canvas).dialog({
				title : titu,
				modal:true,
				close: function(){$(this).dialog('destroy')},
				resizable:false,
				buttons:{
					Aceptar: function(){
						$(this).dialog('destroy');
					}
				}
			});
	},
	getReporteTarjetas = function(){
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		$('form#formulario').append('<input type="hidden" name="ceo_name" value="'+ceo_cook+'">');
		$('form#formulario').attr('action',baseURL+isoPais+"/lotes/innominada/generarReporteTarjetasInnominadas");
		$('form#formulario').submit();
	},
	setNumlote = function(){
		numlote = $("#nro_lote").val();
	},
	getNumlote = function(){
		return numlote;
	},
	toDataTable = function($table){
		var dt = $table.dataTable({
		      "iDisplayLength": 10,
		      'bRetrieve': true,
		      "sPaginationType": "full_numbers",
		      "oLanguage": {
		        "sProcessing":     "Procesando...",
		        "sLengthMenu":     "Mostrar _MENU_ registros",
		        "sZeroRecords":    "No se encontraron resultados.",
		        "sEmptyTable":     "No se encontraron registros.",
		        "sInfo":           "Mostrando registros del _START_ al _END_, de un total de _TOTAL_ registro(s).",
		        "sInfoEmpty":      "Mostrando registros del 0 al 0, de un total de 0 registro(s).",
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

	toDataTable($('#table-text-lotes-inventario'));

	$("#downXLS").on("click", function(){
		getReporteTarjetas();
	});

	/*$.post(baseURL+isoPais+'/lotes/innominada/generarReporteTarjetasInnominadas').done( function(data){

	});*/

})
