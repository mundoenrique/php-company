var scroll_interval;
var ancho=0;


var tamPg = 20;

$(document).ready(function() {
		

	$("#SaldosAmanecidos-TH").attr('maxlength','8');		

		$("#cargando_empresa").fadeIn("slow");
		$.getJSON(baseURL + api + isoPais + '/empresas/lista').always(function( data ) {
			$("#cargando_empresa").fadeOut("slow");
			if(!(data.ERROR)){
				
	  			$.each(data.lista, function(k,v){
	  				
					$("#SaldosAmanecidos-empresa").append('<option value="'+v.acrif+'" acnomcia="'+v.acnomcia+'" acrazonsocial="'+v.acrazonsocial+'" acdesc="'+v.acdesc+'" accodcia="'+v.accodcia+'">'+v.acnomcia+'</option>');
				});
			}else{
				if(data.ERROR=="-29"){
		             alert("Usuario actualmente desconectado");
		             $(location).attr('href',baseURL+isoPais+'/login');
		         }else{
		         	$("#SaldosAmanecidos-empresa").append('<option value="">'+data.ERROR+'</option>');
		         }
			}
   		});

   		$("#SaldosAmanecidos-empresa").on("change",function(){

			acrif = $('option:selected', this).attr("value");


			if(acrif){

			$("#SaldosAmanecidos-producto").children( 'option:not(:first)' ).remove();

			$("#cargando_producto").fadeIn("slow");
			$(this).attr('disabled',true);
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);
			$.post(baseURL + api + isoPais + "/producto/lista", { 'acrif': acrif, ceo_name: ceo_cook }, function(data){
				$("#cargando_producto").fadeOut("slow");
				$("#SaldosAmanecidos-empresa").removeAttr('disabled');
				if(!data.ERROR){	
					$.each(data, function(k,v){  				
						if(v.descripcion.toLowerCase().indexOf("bonus")==-1 && v.descripcion.toLowerCase().indexOf("provis")==-1 && v.descripcion.toLowerCase().indexOf("alimentacion")==-1 && v.descripcion.toLowerCase().indexOf("alimentación")==-1){
							$("#SaldosAmanecidos-producto").append('<option value="'+v.idProducto+'" des = "'+v.descripcion+'" marca = "'+v.marca.toUpperCase()+'" >'+v.descripcion+" / "+v.marca.toUpperCase()+'</option>');	
						}
					}); 
				}else{
					$("#SaldosAmanecidos-producto").append('<option value="">'+data.ERROR+'</option>');
				} 

			});
		}
		});



//METODO PARA REALIZAR LA BUSQUEDA 
	    $("#SaldosAmanecidos-btnBuscar").click(function(){
	    	buscarSaldos(1);
	    	evBuscar=true;
		});

/*
 		function paginar(totalPaginas, paginaActual) {
			$("#paginacion").paginate({
				count 		: totalPaginas,
				start 		: paginaActual,
				display     : 20,
				border					: false,
				text_color  			: '#79B5E3',
				background_color    	: 'none',	
				text_hover_color  		: '#2573AF',
				background_hover_color	: 'none', 
				images		: false,
				mouse		: 'press',
				onChange     			: function(page){

											if( !$('tbody tr').hasClass('pg'+page) ){
												buscarSaldos(page);
											}
											$('tbody tr').hide();
											$('tbody .pg'+page).show();
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
			buscarSaldos(id[1]);
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
			buscarSaldos(1);
		});

		$("#siguiente-22").unbind("click");
		$("#siguiente-22").click(function(){
			buscarSaldos(total);
		});

	}
/***********************Paginacion fin***********************/

			function validar_filtro_busqueda(div){
				var valido=true;

				//VALIDA SELECT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS 
				$.each($("#"+div+" select.required"),function(posItem,item){
					var $elem=$(item);
					if($elem.val()=="" && !($elem==$("select-small"))){
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
					var filtro_busq={};


	  			function buscarSaldos(paginaActual){
				
			    	var $consulta;

			    	if(validar_filtro_busqueda("lotes-2")){
			    		$('#cargando').fadeIn("slow");
			    		$("#SaldosAmanecidos-btnBuscar").hide();
			    		$('#div_tablaDetalle').fadeOut("fast");
				    	filtro_busq.empresa=$("#SaldosAmanecidos-empresa").val();
				    	filtro_busq.cedula=$("#SaldosAmanecidos-TH").val().replace(/ /g,'');
				    	filtro_busq.producto=$("#SaldosAmanecidos-producto").val();
				    	filtro_busq.nomEmpresa=$('option:selected', "#SaldosAmanecidos-empresa").attr("acnomcia");
				    	filtro_busq.descProd=$('option:selected', "#SaldosAmanecidos-producto").attr("des");
				    	filtro_busq.paginaActual=paginaActual;				    	
				    	filtro_busq.paginar=true;
				    	filtro_busq.tamPg=tamPg;

						
				    	
			//SE REALIZA LA INVOCACION AJAX
						var ceo_cook = decodeURIComponent(
							document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
						);
						filtro_busq.ceo_name = ceo_cook;
				    	$consulta = $.post(baseURL + api + isoPais + "/reportes/saldosamanecidos",filtro_busq );
			//DE SER EXITOSA LA COMUNICACION CON EL SERVICIO SE EJECUTA EL SIGUIENTE METODO "DONE"
				 		$consulta.done(function(data){
				 			$("#mensaje").remove();
				 			$('#cargando').fadeOut("slow");
				 			$("#SaldosAmanecidos-btnBuscar").show();
				 			$("#div_tablaDetalle").fadeIn("slow");
				 			$("#view-results").attr("style","");
					 			var tbody=$("#tbody-datos-general");
					 			if (evBuscar) {
					 			tbody.empty();
					 			}
					 			var tr;
					 			var td;
			//DE TRAER RESULTADOS LA CONSULTA SE GENERA LA TABLA CON LA DATA... 
			//DE LO CONTRARIO SE GENERA UN MENSAJE "No existe Data relacionada con su filtro de busqueda"

				 			if(data.rc == "0"){
				 			$("#tabla-datos-general").fadeIn("fast");
					 			$.each(data.saldo.lista,function(posLista,itemLista){
					 				tr=$(document.createElement("tr")).appendTo(tbody);
					 				tr.addClass('pg'+data.paginaActual);
					 				td=$(document.createElement("td")).appendTo(tr);
					 				td.html(itemLista.nombre);
					 				td.attr("style","text-align: center");
					 				td.addClass("td-largo");
					 				td=$(document.createElement("td")).appendTo(tr);
					 				td.html(itemLista.idExtPer);
					 				td.attr("style","text-align: center");
					 				td=$(document.createElement("td")).appendTo(tr);
					 				td.html(itemLista.tarjeta);
					 				td.attr("style","text-align: center");					 				
					 				td=$(document.createElement("td")).appendTo(tr);
					 				td.html(itemLista.saldo);
					 				td.attr("style","text-align: center");
					 				td=$(document.createElement("td")).appendTo(tr);
					 				td.html(itemLista.fechaUltAct);
					 				td.addClass("td-medio");
					 				td.attr("style","text-align: center");
					 			});
					 			/*
					 			if (evBuscar) {
					 				$('#paginacion').show();
					 			paginar(data.totalPaginas, data.paginaActual);
					 			evBuscar=false;
					 			}
					 			*/

								paginacion(data.totalPaginas, data.paginaActual);
					 			
					 			$('#tabla-datos-general tbody tr:even').addClass('even ');

				 			}else{
								if(data.rc =="-29"){
						              alert("Usuario actualmente desconectado");
						            location.reload();
						         }else{
						         	//$('#paginacion').hide();
						         	$('#contend-pagination').hide();
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

			}


$("#export_excel").click(function(){

			$('form#formulario').empty();
			$('form#formulario').append('<input type="hidden" name="empresa" value="'+filtro_busq.empresa+'" />');
			$('form#formulario').append('<input type="hidden" name="cedula" value="'+filtro_busq.cedula+'" />');
			$('form#formulario').append('<input type="hidden" name="producto" value="'+filtro_busq.producto+'" />');
			$('form#formulario').append('<input type="hidden" name="nomEmpresa" value="'+filtro_busq.nomEmpresa+'" />');
			$('form#formulario').append('<input type="hidden" name="descProd" value="'+filtro_busq.descProd+'" />');
			$('form#formulario').append('<input type="hidden" name="paginaActual" value="'+1+'" />');
			$('form#formulario').attr('action',baseURL+api+isoPais+"/reportes/saldosamanecidosExpXLS");
			$('form#formulario').submit(); 

			/*datos={
				empresa:filtro_busq.empresa,
				cedula:filtro_busq.cedula,
				producto:filtro_busq.producto,
				nomEmpresa:filtro_busq.nomEmpresa,
				descProd:filtro_busq.descProd,
				paginaActual:1
			}

			$aux = $("#cargando").dialog({title:"Exportar Excel",modal:true, close:function(){$(this).dialog('close')}, resizable:false });

			$.post(baseURL+api+isoPais+"/reportes/saldosamanecidosExpXLS",datos).done(function(data){
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
    					notificacion("Exportar Excel",data.ERROR)	
    				}
    				
    			}
    		})*/

	    });


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