var scroll_interval;
var ancho=0;

$(".fecha").keypress(function(e){
	if(e.keycode != 8 || e.keycode != 46){
		return false;
	}
});

$(document).ready(function() {
	$("#cedula").attr('maxlength','10');

		var tamPg=20;

		$("#cargando_empresa").fadeIn("slow");
		$.getJSON(baseURL + api + isoPais + '/empresas/lista').always(function( data ) {
			$("#cargando_empresa").fadeOut("slow");
			if(!(data.ERROR)){

				$.each(data.lista, function(k,v){

					$("#repReposiciones_empresa").append('<option accodcia="'+v.accodcia+'" acnomcia="'+v.acnomcia+'" acrazonsocial="'+v.acrazonsocial+'" acdesc="'+v.acdesc+'" value="'+v.acrif+'">'+v.acnomcia+'</option>');
				});
			}else{
				if(data.ERROR.indexOf('-29') !=-1){
		             alert("Usuario actualmente desconectado");
		             $(location).attr('href',baseURL+isoPais+'/login');
		         }else{
		         	$("#repReposiciones_empresa").append('<option value="">'+data.ERROR+'</option>');
		         }
			}
		});

		$("#repReposiciones_empresa").on("change",function(){
			acrif = $('option:selected', this).attr("value");

			if(acrif){

			$("#repReposiciones_producto").children( 'option:not(:first)' ).remove();
			$("#cargando_producto").fadeIn("slow");
			$("#repReposiciones_empresa").attr('disabled',true);
			$.post(baseURL + api + isoPais + "/producto/lista", { 'acrif': acrif }, function(data){
				$("#cargando_producto").fadeOut("slow");
				$("#repReposiciones_empresa").removeAttr('disabled');
				if(!data.ERROR){
					$.each(data, function(k,v){
						$("#repReposiciones_producto").append('<option value="'+v.idProducto+'" des="'+v.descripcion+"/" +v.marca.toUpperCase()+'" >'+v.descripcion+" / "+v.marca.toUpperCase()+'</option>');
					});
				}else{
					if(data.ERROR.indexOf('-29') !=-1){
		             alert("Usuario actualmente desconectado");
		             $(location).attr('href',baseURL+isoPais+'/login');
		         }else{
					$("#repReposiciones_producto").append('<option value="">'+data.ERROR+'</option>');
				}
				}
			});
		}
		});




		$( "#repReposiciones_fechaInicial" ).datepicker({
	      defaultDate: "+1w",
	      changeMonth: true,
	      changeYear: true,
	      dateFormat:"dd/mm/yy",
	      numberOfMonths: 1,
	      maxDate: "+0D",
	      onClose: function( selectedDate ) {
	      	if(selectedDate){
			$( "#repReposiciones_fechaFinal" ).datepicker( "option", "minDate", selectedDate );
			}else{
				$( "#repReposiciones_fechaFinal" ).datepicker( "option", "minDate", "" );
			}
	      }
	    });

	    $( "#repReposiciones_fechaFinal" ).datepicker({
	      defaultDate: "+1w",
	      dateFormat:"dd/mm/yy",
	      changeMonth: true,
	      changeYear: true,
	      numberOfMonths: 1,
	      maxDate: "+0D",
	      onClose: function( selectedDate ) {
	      	if (selectedDate) {
	        $( "#repReposiciones_fechaInicial" ).datepicker( "option", "maxDate", selectedDate );
	    	}else{
	    		$( "#repReposiciones_fechaInicial" ).datepicker( "option", "maxDate", "+0D" );
	    	}
	      }
	    });

	    $.each($(".radio"),function(pos,item){
			$(item).click(function(){
				if($(this).is(":checked")){
					if($(this).val()!="3"){
						$( "#repReposiciones_fechaInicial" ).attr("disabled","true");
						$( "#repReposiciones_fechaFinal" ).attr("disabled","true");
					}else{
						$( "#repReposiciones_fechaFinal" ).removeAttr("disabled");
						$( "#repReposiciones_fechaInicial" ).removeAttr("disabled");
					}
				}
			});
		});

	$.each($(".radio"),function(pos,item){
		$(item).click(function(){
			if($(this).is(":checked")){
				if($(this).val()=="1"){
					$("#repReposiciones_fechaInicial").datepicker("setDate", "-3m");
					$("#repReposiciones_fechaFinal").datepicker("setDate", "today");
				}else if($(this).val()=="2"){
					$("#repReposiciones_fechaInicial").datepicker("setDate", "-6m");
					$("#repReposiciones_fechaFinal").datepicker("setDate", "today");
				}
			}
		});
	});


	    $("#export_excel").click(function(){

			$('form#formulario').empty();
			$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
			$('form#formulario').append('<input type="hidden" name="fechaInicial" value="'+filtro_busq.fechaInicial+'" />');
			$('form#formulario').append('<input type="hidden" name="fechaFin" value="'+filtro_busq.fechaFin+'" />');
			$('form#formulario').append('<input type="hidden" name="idTarjetaHabiente" value="'+filtro_busq.idTarjetaHabiente+'" />');
			$('form#formulario').append('<input type="hidden" name="tipoReposicion" value="'+filtro_busq.tipoReposicion+'" />');
			$('form#formulario').append('<input type="hidden" name="producto" value="'+filtro_busq.producto+'" />');
			$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="'+filtro_busq.acnomcia+'" />');
			$('form#formulario').append('<input type="hidden" name="nomProducto" value="'+filtro_busq.des+'" />');
			$('form#formulario').append('<input type="hidden" name="paginaActual" value="1" />');
			$('form#formulario').attr('action',baseURL+api+isoPais+"/reportes/reposicionesExpXLS");
			$('form#formulario').submit();

			/*datos={
				empresa:filtro_busq.empresa,
				fechaInicial: filtro_busq.fechaInicial,
				fechaFin: filtro_busq.fechaFin,
				idTarjetaHabiente: filtro_busq.idTarjetaHabiente,
				tipoReposicion: filtro_busq.tipoReposicion,
				producto: filtro_busq.producto,
				nomEmpresa: filtro_busq.acnomcia,
				nomProducto: filtro_busq.des,
				paginaActua:1
			}
	    	$aux = $("#cargando").dialog({title:'Descargando archivo de datos',modal:true, close:function(){$(this).dialog('close')}, resizable:false });
			$.post(baseURL+api+isoPais+"/reportes/reposicionesExpXLS",datos).done(function(data){
    			$aux.dialog('destroy')
    			if(!data.ERROR){
    				$('form#formulario').empty();
    				$('form#formulario').append('<input type="hidden" name="bytes" value="'+JSON.stringify(data.bytes)+'" />');
    				$('form#formulario').append('<input type="hidden" name="ext" value="'+data.ext+'" />');
    				$('form#formulario').append('<input type="hidden" name="nombreArchivo" value="'+data.nombreArchivo+'" />');
    				$('form#formulario').attr('action',baseURL+'/'+isoPais+"/file");
    				$('form#formulario').submit()
    			}else{
    				if(data.ERROR=="-29"){
    					alert('Usuario actualmente desconectado');
						location.reload();
    				}else{
    					notificacion('Descargando archivo de datos',data.ERROR)
    				}

    			}
    		})*/

	    });


//METODO PARA REALIZAR LA BUSQUEDA
$("#repReposiciones_btnBuscar").click(function(){
	evBuscar=true;
	buscarReposiciones("1");
});

var filtro_busq={};
function buscarReposiciones(paginaActual){

	    	var $consulta;
	    //	pag=paginaActual;
	    	if(validar_filtro_busqueda("lotes-2")){
	    		$('#cargando').fadeIn("slow");
	    		$("#repReposiciones_btnBuscar").hide();
	    		$('#div_tablaDetalle').fadeOut("fast");
		    	filtro_busq.empresa=$("#repReposiciones_empresa").val();
		    	filtro_busq.fechaInicial=$("#repReposiciones_fechaInicial").val();
		    	filtro_busq.fechaFin=$("#repReposiciones_fechaFinal").val();
		    	filtro_busq.idTarjetaHabiente=$("#cedula").val().replace(/ /g,'');
		    	filtro_busq.tipoReposicion=$("#repReposiciones_tipoReposicion").val();
		    	filtro_busq.producto =$("#repReposiciones_producto").val();
		    	filtro_busq.paginaActual=paginaActual;
		    	filtro_busq.tamPg = tamPg;
		    	filtro_busq.paginar = true;

		    	filtro_busq.acnomcia = $("option:selected","#repReposiciones_empresa").attr("acnomcia");
		    	filtro_busq.des = $("option:selected","#repReposiciones_producto").attr("des");

	//SE REALIZA LA INVOCACION AJAX
		    	$consulta = $.post(baseURL + api + isoPais + "/reportes/reposiciones",filtro_busq );
	//DE SER EXITOSA LA COMUNICACION CON EL SERVICIO SE EJECUTA EL SIGUIENTE METODO "DONE"
		 		$consulta.done(function(data){

		 			$('#cargando').fadeOut("slow");
		 			$("#repReposiciones_btnBuscar").show();

			 			var tbody=$("#tbody-datos-general");
			 			if (evBuscar) {
					 			tbody.empty();
					 			}
			 			contenedor=$("#div_tablaDetalle");
			 			var tr;
			 			var td;
	//DE TRAER RESULTADOS LA CONSULTA SE GENERA LA TABLA CON LA DATA...
	//DE LO CONTRARIO SE GENERA UN MENSAJE "No existe Data relacionada con su filtro de busqueda"

		 			if(data.rc == "0"){
		 				$("#mensaje").remove();
		 			$("#tabla-datos-general").fadeIn('fast');
		 			$("#view-results").attr("style","display:block");
	    			$('#div_tablaDetalle').fadeIn("slow");
	    			//$("#paginacion").show();
	    			$("#contend-pagination").show();

	    			$('#div_tablaDetalle').fadeIn("slow");
			 			$.each(data.listadoReposiciones,function(posLista,itemLista){
			 				tr=$(document.createElement("tr")).appendTo(tbody);
			 				tr.addClass('pg'+data.paginaActual);
			 				td=$(document.createElement("td")).appendTo(tr);
			 				td.html(itemLista.tarjeta);
			 				td.attr("style","text-align: center");
			 				td=$(document.createElement("td")).appendTo(tr);
			 				td.html(itemLista.tarjetahabiente);
			 				td.attr("style","text-align: center");
			 				td=$(document.createElement("td")).appendTo(tr);
			 				td.html(itemLista.idExtPer);
			 				td.attr("style","text-align: center");
			 				td=$(document.createElement("td")).appendTo(tr);
			 				td.html(itemLista.fechaExp);
			 				td.attr("style","text-align: center");

			 			});
			 			/*
			 			if (evBuscar) {
					 			paginar(data.totalPaginas, data.paginaActual);
					 			evBuscar=false;
					 			}
			 			*/

						paginacion(data.totalPaginas, data.paginaActual);

			 			$('#tabla-datos-general tbody tr:even').addClass('even ');
		 			}else{
		 				if(data.rc =="-29"){
				             alert(data.mensaje);
				             $(location).attr('href',baseURL+isoPais+'/login');
				         }else{
				 				$('#div_tablaDetalle').fadeIn("slow");
								//$("#paginacion").hide();
								$("#contend-pagination").hide();
					 			$("#mensaje").remove();
					 			$("#tabla-datos-general").fadeOut("fast");
					 			$("#view-results").attr("style","display:none");
					 			var div =$(document.createElement("div")).appendTo(contenedor);
					 			div.attr("id","mensaje");
					 			div.attr("style","background-color:rgb(252,199,199); margin-top:60px;");
					 			var p = $(document.createElement("p")).appendTo(div);
					 			p.html(data.mensaje);
					 			p.attr("style","text-align:center;padding:10px;");
					 		}
		 			}

		 		});
			}

}


function validar_filtro_busqueda(div){
	var valido=true;
		//VALIDA INPUT:TEXT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
		$.each($("#"+div+" input[type='text'].required"),function(posItem,item){
			var $elem=$(item);
			if( $elem.attr('id') !="cedula" ){
				if($elem.val()==""){
					valido=false;
					$elem.attr("style","border-color:red");
				}else{
					$elem.attr("style","");
				}
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
/*
function paginar(totalPaginas, paginaActual) {
	$("#paginacion").paginate({
		count 		: totalPaginas,
		start 		: paginaActual,
		display     : tamPg,
		border					: false,
		text_color  			: '#79B5E3',
		background_color    	: 'none',
		text_hover_color  		: '#2573AF',
		background_hover_color	: 'none',
		images		: false,
		mouse		: 'press',
		onChange     			: function(page){
									if( !$('.tbody-SC tbody tr').hasClass('pg'+page) ){
												buscarReposiciones(page);
											}
											$('.tbody-SC tbody tr').hide();
											$('.tbody-SC .pg'+page).show();


								  }
	});
}
*/

/***********************Paginacion inicio***********************/
	function paginacion(total, inicial){
		var texHtml="";
		$("#list_pagination").html("");
			for(var i=1;i<=total;++i) {
					texHtml+='<span class="cajonNum"><a href="javascript:" id="page_'+ i +'" class="num-pagina">'+ i +'</a></span>';
			}
		$("#list_pagination").html(texHtml);

		$("#list_pagination").scrollLeft(0);

		ancho = $("#page_"+ inicial).position().left - 4;

		$("#list_pagination").animate({
	        scrollLeft: ancho
	    }, 200);

		$(".num-pagina").css('text-decoration','none');
		$("#page_"+ inicial).css('text-decoration','underline');

		$(".num-pagina").unbind("click");
		$(".num-pagina").click(function(){
			var id = this.id;
				id = id.split("_");
			buscarReposiciones(id[1]);
		});

		$("#anterior-1").unbind("mouseover");
		$("#anterior-1").unbind("mouseout");
		$("#anterior-1").mouseover(function(){
			scroll_interval = setInterval(
			function() {
				if($("#list_pagination").scrollLeft()>0){
				  ancho = $("#list_pagination").scrollLeft() - 1
				  $("#list_pagination").scrollLeft(ancho);
				}
			}, 20);
		}).mouseout(function() {
			  clearInterval(scroll_interval);
		});
		$("#anterior-2").unbind("mouseover");
		$("#anterior-2").unbind("mouseout");
		$("#anterior-2").mouseover(function(){
			scroll_interval = setInterval(
			function() {
				if($("#list_pagination").scrollLeft()>0){
				  ancho = $("#list_pagination").scrollLeft() - 1
				  $("#list_pagination").scrollLeft(ancho);
				}
			}, 1);
		}).mouseout(function() {
			  clearInterval(scroll_interval);
		});
		$("#siguiente-1").unbind("mouseover");
		$("#siguiente-1").unbind("mouseout");
		$("#siguiente-1").mouseover(function(){
			scroll_interval = setInterval(
					function() {
					  ancho = $("#list_pagination").scrollLeft() + 1
					  $("#list_pagination").scrollLeft(ancho);
					},
					20
				  );
		}).mouseout(function() {
			  clearInterval(scroll_interval);
		});
		$("#siguiente-2").unbind("mouseover");
		$("#siguiente-2").unbind("mouseout");
		$("#siguiente-2").mouseover(function(){
			scroll_interval = setInterval(
					function() {
					  ancho = $("#list_pagination").scrollLeft() + 1
					  $("#list_pagination").scrollLeft(ancho);
					},
					1
				  );
		}).mouseout(function() {
			  clearInterval(scroll_interval);
		});

		$("#anterior-22").unbind("click");
		$("#anterior-22").click(function(){
			buscarReposiciones(1);
		});

		$("#siguiente-22").unbind("click");
		$("#siguiente-22").click(function(){
			buscarReposiciones(total);
		});

	}
/***********************Paginacion fin***********************/


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


});
