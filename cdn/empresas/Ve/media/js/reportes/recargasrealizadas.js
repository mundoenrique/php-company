	var baseURL = $('body').attr('data-app-base');
	var isoPais = $('body').attr('data-country');
	var api = "api/v1/";
	var colores = ["#54C2D0","#50C592","#2B569F","#994596","#F5921E","#298C9A","#2C855F","#1A325B","#522551","#B46607"];


$(".fecha").keypress(function(e){
	if(e.keycode != 8 || e.keycode != 46){
		return false;
	}
});

$(document).ready(function() {


		$("#cargando_empresa").fadeIn("slow");
		$.getJSON(baseURL + api + isoPais + '/empresas/lista').always(function( data ) {
			$("#cargando_empresa").fadeOut("slow");
			if(!(data.ERROR)){

	  			$.each(data.lista, function(k,v){

					$("#RecargasRealizadas-Empresa").append('<option value="'+v.accodcia+'">'+v.acnomcia+'</option>');
				});
			}else{
				if(data.ERROR.indexOf('-29') !=-1){
		             alert("Usuario actualmente desconectado");
		             $(location).attr('href',base+'/'+isoPais+'/login');
		         }else{
		         	$("#RecargasRealizadas-Empresa").append('<option value="">'+data.ERROR+'</option>');
		         }
			}

   		});

		$( "#repRecargasRealizadas_anio").datepicker({
	      dateFormat:"mm/yy",
	      changeMonth: true,
	      changeYear:true,
	      numberOfMonths: 1,
	      maxDate: "+0D"
	    });

	    $("#export_excel").click(function(){

	    	$('form#formulario').empty();
		$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
		$('form#formulario').append('<input type="hidden" name="anio" value="'+filtro_busq.anio+'" />');
		$('form#formulario').append('<input type="hidden" name="mes" value="'+filtro_busq.mes+'" />');
		$('form#formulario').attr('action',base+api+isoPais+"/reportes/recargasRealizadasXLS");
		$('form#formulario').submit();

		/*datos = {
			empresa:filtro_busq.empresa,
			anio:filtro_busq.anio,
			mes:filtro_busq.mes
		}

		descargarArchivo(datos, base+api+isoPais+"/reportes/recargasRealizadasXLS", "Exportar Excel" );
*/

	});

	$("#export_pdf").click(function(){

		/*datos = {
			empresa:filtro_busq.empresa,
			anio:filtro_busq.anio,
			mes:filtro_busq.mes
		}

		descargarArchivo(datos, base+api+isoPais+"/reportes/recargasRealizadasPDF", "Exportar PDF" );
		  	*/
		$('form#formulario').empty();
		$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
		$('form#formulario').append('<input type="hidden" name="anio" value="'+filtro_busq.anio+'" />');
		$('form#formulario').append('<input type="hidden" name="mes" value="'+filtro_busq.mes+'" />');
		$('form#formulario').attr('action',base+api+isoPais+"/reportes/recargasRealizadasPDF");
		$('form#formulario').submit();

	});

//METODO PARA REALIZAR LA BUSQUEDA
var filtro_busq={};
	    $("#repRecargasRealizadas_btnBuscar").click(function(){

	    	var $consulta;
	    	filtro_busq.empresa=$("#RecargasRealizadas-Empresa").val();
	    	filtro_busq.anio=$("#repRecargasRealizadas_anio").val().split("/")[1];
	    	filtro_busq.mes=$("#repRecargasRealizadas_anio").val().split("/")[0];
	    	filtro_busq.paginaActual=1;
	    	if(validar_filtro_busqueda("lotes-2")){
	    	$('#cargando').fadeIn("slow");
	    	$(this).hide();
	    	$('#div_tablaDetalle').fadeOut("fast");
//SE REALIZA LA INVOCACION AJAX
		    	$consulta = $.post(baseURL + api + isoPais + "/reportes/recargasrealizadas",filtro_busq );
//DE SER EXITOSA LA COMUNICACION CON EL SERVICIO SE EJECUTA EL SIGUIENTE METODO "DONE"
		 		$consulta.done(function(data){
		 				$("#mensaje").remove();
		 				$('#cargando').fadeOut("slow");
		 				$("#repRecargasRealizadas_btnBuscar").show();
		 				$("#div_tablaDetalle").fadeIn("slow");
			 			$("#tabla-datos-general").fadeIn("fast");
			 			var tbody=$("#tbody-datos-general");
			 			var thead= $('#datos-principales');
			 			thead.empty();
			 			tbody.empty();
			 			var tr;
			 			var td;
			 			var th;
			 			var mes =$("#repRecargasRealizadas_anio").val();
			 			mes= mes.split("/")[0];
			 			var month=new Array();
			 			month[-2]="Noviembre";
			 			month[-1]="Diciembre";
						month[0]="Enero";
						month[1]="Febrero";
						month[2]="Marzo";
						month[3]="Abril";
						month[4]="Mayo";
						month[5]="Junio";
						month[6]="Julio";
						month[7]="Agosto";
						month[8]="Septiembre";
						month[9]="Octubre";
						month[10]="Noviembre";
						month[11]="Diciembre";

	//DE TRAER RESULTADOS LA CONSULTA SE GENERA LA TABLA CON LA DATA...
	//DE LO CONTRARIO SE GENERA UN MENSAJE "No existe Data relacionada con su filtro de busqueda"
					th=$(document.createElement("th")).appendTo(thead);
			 		th.html("Producto");
			 		th=$(document.createElement("th")).appendTo(thead);
			 		th.html(month[mes-3]);
			 		th=$(document.createElement("th")).appendTo(thead);
			 		th.html(month[mes-2]);
			 		th=$(document.createElement("th")).appendTo(thead);
			 		th.html(month[mes-1]);
			 		th=$(document.createElement("th")).appendTo(thead);
			 		th.html("Total");

		 			if(data.rc == "0"){
		 				$("#view-results").attr("style","");
			 			$.each(data.recargas,function(posLista,itemLista){
			 				tr=$(document.createElement("tr")).appendTo(tbody);
			 				td=$(document.createElement("td")).appendTo(tr);
			 				td.html(itemLista.producto);
			 				td=$(document.createElement("td")).appendTo(tr);
			 				if(itemLista.montoRecarga1!=null){td.html(itemLista.montoRecarga1)}
			 				else{td.html("0,00")}
			 				td.attr("style","text-align: center");
			 				td=$(document.createElement("td")).appendTo(tr);
			 				if(itemLista.montoRecarga2!=null){td.html(itemLista.montoRecarga2)}
			 				else{td.html("0,00")}
			 				td.attr("style","text-align: center");
			 				td=$(document.createElement("td")).appendTo(tr);
			 				if(itemLista.montoRecarga3!=null){td.html(itemLista.montoRecarga3)}
			 				else{td.html("0,00")}
			 				td.attr("style","text-align: center")
			 				td=$(document.createElement("td")).appendTo(tr);
			 				if(itemLista.totalProducto!=null){td.html(itemLista.totalProducto)}
			 				else{td.html("0,00")}
			 				td.attr("style","text-align: center");
			 			});

			 			tr=$(document.createElement("tr")).appendTo(tbody);
			 			td=$(document.createElement("td")).appendTo(tr);
		 				td.html("Totales");
		 				td.attr("style","text-align: right");

			 			td=$(document.createElement("td")).appendTo(tr);
		 				td.html(data.totalRecargas1);
		 				td.attr("style","text-align: center");

		 				td=$(document.createElement("td")).appendTo(tr);
		 				td.html(data.totalRecargas2);
		 				td.attr("style","text-align: center");

		 				td=$(document.createElement("td")).appendTo(tr);
		 				td.html(data.totalRecargas3);
		 				td.attr("style","text-align: center");

		 				td=$(document.createElement("td")).appendTo(tr);
		 				td.html(data.totalRecargas);
		 				td.attr("style","text-align: center");



			 		$("#grafica").click(function(){
			    	var _axis="Bolivares";
					var jsonChart={
						title:{
							text:"Recargas realizadas"
						},
						legend:{
							position:"top"
						},
						series:[],
						categoryAxis:{
							categories:[]
						},
						valueAxis:{
							name:_axis,
							title:{
								text:""
							}
						}

					}

// SE OBTIENE LAS CATEGORIAS
					$.each(data.listaGrafico[0].categorias,function(posLista,itemLista){
						jsonChart.categoryAxis.categories.push(itemLista.nombreCategoria);
					});
					var width_categoria=300;
					width_categoria=(parseInt(width_categoria)*parseInt(data.listaGrafico[0].categorias.length));
					$( "#chart" ).dialog({modal:true, width: 800, height: 400});

// SE OBTIENE LAS SERIES
					$.each(data.listaGrafico[0].series,function(posSeries,itemSeries){
						var serie={};
						serie.name=itemSeries.nombreSerie;
						serie.data=itemSeries.valores;
						serie.color = colores[posSeries];
						$.each(serie.data[0],function(pos,item){
							replaceAll(item,",","");
							replaceAll(item,".","");
						});
						serie.axis= _axis;
						jsonChart.series.push(serie);
					});
// GRAFICA
					$("#chart").kendoChart(jsonChart);



			  	    });
					$('#tabla-datos-general tbody tr:even').addClass('even ');
		 			}else{
						if(data.rc =="-29"){
				             alert("Usuario actualmente desconectado");
				             $(location).attr('href',base+'/'+isoPais+'/login');
				         }else{
				 			$("#mensaje").remove();
				 			var contenedor = $("#div_tablaDetalle");
				 			$("#tabla-datos-general").fadeOut("fast");
				 			$("#view-results").attr("style","display:none");
				 			var div =$(document.createElement("div")).appendTo(contenedor);
				 			div.attr("id","mensaje");
				 			div.attr("style","background-color:rgb(252,199,199); margin-top:43px;");
				 			var p = $(document.createElement("p")).appendTo(div);
				 			p.html(data.mensaje);
				 			p.attr("style","text-align:center;padding:10px;font-size:14px");
				 		}
		 			}

		 		});
			}

	    });



});



function validar_filtro_busqueda(div){
  var valido=true;
//VALIDA INPUT:TEXT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
  $.each($("#"+div+" input[type='text'].required"),function(posItem,item){
       var $elem=$(item);
        if($elem.val()==""){
                valido=false;
                $elem.attr("style","border-color:red");
        }else{
                $elem.attr("style","");
        }
  });

//VALIDA SELECT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
  $.each($("#"+div+" select.required"),function(posItem,item){
       var $elem=$(item);
      if($elem.val()==""){
              valido=false;
              $elem.attr("style","border-color:red");
      }else{
              $elem.attr("style","");
      }
  });


//VALIDA INPUT:CHECKBOX  y INPUT:RADIO QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
  var check = $("#"+div+" input[type='checkbox'].required:checked").length;
  var radio = $("#"+div+" input[type='radio'].required:checked ").length;
   if((check == "")&&($("#"+div+" input[type='checkbox'].required").length!="")){
        valido=false;
     	$("#"+div+" input[type='checkbox'].required").next().attr("style","color:red");
   }else{
   		$("#"+div+" input[type='checkbox'].required").next().attr("style","");
   }

    if((radio == "")&&($("#"+div+" input[type='radio'].required").length!="")){
        valido=false;
      	$("#"+div+" input[type='radio'].required").next().attr("style","color:red");
    }else{
		$("#"+div+" input[type='radio'].required").next().attr("style","");
    }


    if(!valido){
    	$(".div_tabla_detalle").fadeOut("fast");
    	$("#mensajeError").fadeIn("fast");
    }else{
		$("#mensajeError").fadeOut("fast");
    }


  return valido;
}


  function descargarArchivo(datos, url, titulo){

  $aux = $("#cargando").dialog({title:titulo,modal:true, close:function(){$(this).dialog('close')}, resizable:false });

      $.post(url,datos).done(function(data){
          $aux.dialog('destroy')
          if(!data.ERROR){
            $('form#formulario').empty();
            $('form#formulario').append('<input type="hidden" name="bytes" value="'+JSON.stringify(data.bytes)+'" />');
            $('form#formulario').append('<input type="hidden" name="ext" value="'+data.ext+'" />');
            $('form#formulario').append('<input type="hidden" name="nombreArchivo" value="'+data.nombreArchivo+'" />');
            $('form#formulario').attr('action',base+'/'+isoPais+"/file");
            $('form#formulario').submit()
          }else{
            if(data.ERROR=="-29"){
              alert('Usuario actualmente desconectado');
            location.reload();
            }else{
              notificacion(titulo,data.ERROR)
            }

          }
        })

}

function notificacion(titulo, mensaje){

  var canvas = "<div>"+mensaje+"</div>";

  $(canvas).dialog({
    title: titulo,
    modal: true,
    maxWidth: 700,
    maxHeight: 300,
    resizable: false,
    close:function(){
      $(this).dialog("destroy");
    },
    buttons: {
      OK: function(){
            $(this).dialog("destroy");
          }
    }
  });
}
