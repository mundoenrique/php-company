$(function(){ // Document ready

	
	var baseURL =  $('body').attr('data-app-base');
	var isoPais = $('body').attr('data-country');
	var api ="api/v1/";

	$("#archivo").on('click',function () {
    	$("#userfile").trigger('click');
	});


	$("#userfile").on("click",function(){

		$(this).fileupload({
		    type: 'post',  
		    replaceFileInput:false,       
		    url:baseURL+api+isoPais+"/servicios/actualizar-datos/cargarArchivo", 
		        
		        add: function (e, data) {
		          f=$('#userfile').val();  
		          $('#archivo').val($('#userfile').val());
		            dat = data;

		            var ext = $('#userfile').val().substr( $('#userfile').val().lastIndexOf(".") +1 );
		            if( ext === "xls" || ext === "xlsx" ){
			            data.context = $('#cargarXLS').click(function () {  
			           			
			                    $("#cargarXLS").replaceWith('<h3 id="cargando_archivo">Cargando...</h3>');  
			                   // dat.formData = {'data-rif':$("option:selected","#listaEmpresasSuc").attr("data-rif")};               
			                    dat.submit().success( function (result, textStatus, jqXHR){
			                     
			                      if(result){                        
			                        if(!result.ERROR){
			                          mostrarError(result);
			                        }else{
			                        	if(result.rc=='-61'){
											alert('Usuario actualmente desconectado'); location.reload();
										}else{
			                          notificacion("Cargar archivo: actualizar datos",result.ERROR);}
			                        }                        
			                      }
			                         	                                       
			                      $('#userfile').val("");
			                      $('#archivo').val("");
			                    }); 
			                                          
			            });
		            }else{
		              notificacion("Cargar archivo: actualizar datos","Tipo de archivo no permitido. <h5>Formato requerido: excel (.xls ó .xlsx)</h5>");
		              $('#userfile').val("");
		              $('#archivo').val("");
		            }
		        },
		        done: function (e, data) {
		           
		            $('#userfile').val(""); $('#archivo').val("");
		            $('#cargando_archivo').replaceWith( '<button id="cargarXLS" >Cargar archivo</button>' );
		        },
		        error: function(e){
		          notificacion("Cargar archivo: actualizar datos","Error al intentar cargar el archivo");
		          $('#userfile').val(""); $('#archivo').val("");
		          $('#cargando_archivo').replaceWith( '<button id="cargarXLS" >Cargar archivo</button>' );
		        }
	    });
	});


	function mostrarError(result){
      
		  if(result.rc!="0"){

		    var canvas = "<h4>ENCABEZADO</h4>";
		    $.each(result.erroresFormato.erroresEncabezado.errores,function(k,v){
		      canvas += "<h6>"+v+"</h6>"; 
		    });    
		  
		    canvas += "<h4>REGISTRO</h4>";
		    $.each(result.erroresFormato.erroresRegistros, function(k,vv){
		        canvas += "<h5>"+vv.nombre+"</h5>";
		        $.each(result.erroresFormato.erroresRegistros[k].errores, function(i,v){          
		            canvas += "<h6>"+v+"<h6/>";
		        });

		    });
		    notificacion(result.msg, canvas);
		  
		  }else{
		    notificacion("Cargando archivo", "Archivo cargado con éxito.\n"+result.msg);
		    //actualizarLote();
		  }
 
	}


	$("#buscar-datos").on("click",function(){

		if( $('#nombre').val()!="" ){

			$('#nombre').removeAttr('style');
			$("#buscar-datos").hide();
			$("#loading").dialog({title: "Buscando datos", modal:true});

			$.post(baseURL+api+isoPais+'/servicios/actualizar-datos/buscar-datos').done(function(data){

				if(!data.ERROR){

				}else{
					if(data.ERROR=="-29"){
						alert('Usuario actualmente desconectado');
						location.reload();
					}else{
						notificacion('Buscar datos', data.ERROR);
					}
				}
			});

		}else{
			$('#nombre').attr("style","border-color:red");	
		}

	});


	$('#tabla-act-datos').on('click','#downXLS', function(){

		var OS = $(this).parents("tr").attr('id');	
		
			$aux = $("#loading").dialog({title:'Descargando archivo de datos',modal:true, close:function(){$(this).dialog('close')}, resizable:false });
			$('form#formulario').empty();
    		$('form#formulario').append('<input type="hidden" name="data-idOS" value="'+OS+'" />');
    		$('form#formulario').append($('#data-OS'));
    		$('form#formulario').attr('action',baseURL+api+isoPais+"/servicios/actualizar-datos/downXLS");
    		$('form#formulario').submit(); 
    		setTimeout(function(){$aux.dialog('destroy')},8000);
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



}); //--Fin document ready :)