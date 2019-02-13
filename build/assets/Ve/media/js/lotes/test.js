$('.crear').hide();
$('.cargar').hide();
$('.buscar').hide();

$("#crear1").click(function(){
$('.crear').show();
$('.cargar').hide();
$('.buscar').hide();

});

$("#cargar1").click(function(){
$('.crear').hide();
$('.cargar').show();
$('.buscar').hide();

});

$("#buscar1").click(function(){

$('.crear').hide();
$('.cargar').hide();
$('.buscar').show();
});



$(function(){ // Document ready

	$("#tipoChequeCrear").on('change', function(){
		tipo = $(this).val();

		if(tipo=="G"){ 
			$('.chq-elect').addClass('elem-hidden');
		}else{
			$('.chq-elect').removeClass('elem-hidden');
		}

		$('.campos-reproceso input').val('');
	});


	$('#monto').on('keyup', function(){
		if( !$(this).val().match(/^-?[0-9]+([\,][0-9]{0,2})?$/) ){
			$(this).val('');
		}
	});


	$('#btnCrearBenf').on('click', function(){
		
		$('#monto').keyup();
		$('#nroCuentaGuard').keyup();

		pass=$('#passCrear').val();
		$('#passCrear').val('');

		datosPost = {};
		datosPost.tipo = $('#tipoChequeCrear').val();
		datosPost.idPersona = $("#idPersona").val();
		datosPost.apellEmpl = $("#apellEmpl").val();
		datosPost.nombEmpl = $("#nombEmpl").val();
		datosPost.emailEmpl = $("#emailEmpl").val();
		datosPost.apellInfant = $("#apellInfant").val();
		datosPost.nombInfant = $("#nombInfant").val();
		datosPost.nombGuard = $("#nombGuard").val();
		datosPost.idfiscalGuard = $("#idfiscalGuard").val();
		datosPost.nroCuentaGuard = $("#nroCuentaGuard").val();
		datosPost.emailGuard = $("#emailGuard").val();
		datosPost.monto = $("#monto").val();
		datosPost.concepto = $("#concepto").val();
		datosPost.pass=hex_md5(pass);

		WSbeneficiario('crear', datosPost,pass, 'Crear beneficiario',$("#lotes-contenedor"), $("#btnCrearBenf"));


	});

	function WSbeneficiario(funcion, datosPost,pass, titulo, $contenedor, $btn){

		$.each($contenedor.find(".error"), function(){
			$(this).removeClass("error");
		});

				
		if( verificar(datosPost, datosPost.tipo, $contenedor) ){
						
			if( pass=='' ){
				notificacion(titulo, "Debe ingresar su contraseña.");
				$("#passCrear").addClass("error");
			}else{
				
				$("#loading").dialog({title:titulo,modal:true,resizable:false,close: function(){$(this).dialog("destroy");}})
				$btn.hide();
				$.post(baseURL+api+isoPais+'/lotes/reproceso/'+funcion, datosPost).done(function(data){
					$("#loading").dialog("destroy");
					$btn.show();
					if(!data.ERROR){
						notificacion(titulo,'Proceso exitoso');
						$('.campos-reproceso input').val('');
					}else{
						if(data.ERROR=='-29'){
							alert('Usuario actualmente desconectado'); location.reload();
						}else{
							notificacion(titulo, data.ERROR);                            
						}
					}

				});
			}
		}else{
			notificacion(titulo, "Formulario inválido, verifique los datos suministrados.");
		}
	}



	function verificar(datosPost, tipo, $contenedor){
		emailRegex = /^([^]+[\w-\.]+@([\w-]+\.)+[\w-]{2,4})+$/;
		rifRegex = /^[gjvepEPVGJ]{1}[-]{1}[0-9]{8}[-]{1}[0-9]{1}$/;
		alfanumericRegex = /^[a-zA-Z0-9\s]*$/;
		alfaRegex = /^[a-zA-Z\s]*$/;
		ciRegex = /^[0-9]{8}$/;
		nroctaRegex = /^[0-9]{20}$/;
		montoRegex = /^-?[0-9]+([\,][0-9]{0,2})?$/;

		validez=true;
		
		if(tipo=="E"){
			if( !emailRegex.test(datosPost.emailEmpl) || datosPost.emailEmpl=='' ){	
				$contenedor.find("#emailEmpl").addClass('error');				
				validez= false;
			}
			if( !emailRegex.test(datosPost.emailGuard) || datosPost.emailGuard=='' ){	
				$contenedor.find("#emailGuard").addClass('error');				
				validez= false;
			}
			if( !rifRegex.test(datosPost.idfiscalGuard) || datosPost.idfiscalGuard=='' ){			
				$contenedor.find("#idfiscalGuard").addClass('error');			
				validez= false;
			}
			if( !nroctaRegex.test(datosPost.nroCuentaGuard) || datosPost.nroCuentaGuard=='' ){		
				$contenedor.find("#nroCuentaGuard").addClass('error');			
				validez= false;
			}
		}

		if( ! ciRegex.test(datosPost.idPersona) || datosPost.idPersona=='' ){
			$contenedor.find("#idPersona").addClass('error');			
			validez= false;
		}
		if( ! alfaRegex.test(datosPost.apellEmpl) || datosPost.apellEmpl=='' ){
			$contenedor.find("#apellEmpl").addClass('error');			
			validez= false;
		}
		if( ! alfaRegex.test(datosPost.nombEmpl) || datosPost.nombEmpl=='' ){
			$contenedor.find("#nombEmpl").addClass('error');			
			validez= false;
		}
		if( ! alfaRegex.test(datosPost.apellInfant) || datosPost.apellInfant=='' ){
			$contenedor.find("#apellInfant").addClass('error');			
			validez= false;
		}
		if( ! alfaRegex.test(datosPost.nombInfant) || datosPost.nombInfant=='' ){
			$contenedor.find("#nombInfant").addClass('error');			
			validez= false;
		}
		if( ! alfanumericRegex.test(datosPost.nombGuard) || datosPost.nombGuard=='' ){
			$contenedor.find("#nombGuard").addClass('error');			
			validez= false;
		}
		if( ! alfanumericRegex.test(datosPost.concepto) || datosPost.concepto=='' ){
			$contenedor.find("#concepto").addClass('error');			
			validez= false;
		}
		if( ! montoRegex.test(datosPost.monto) || datosPost.monto=='' ){
			$contenedor.find("#monto").addClass('error');			
			validez= false;
		}

		return validez;

	}

	$("#archivo").on('click',function () {
		$("#userfile").trigger('click');
	});

	$("#userfile").fileupload({
		type: 'post',  
		replaceFileInput:false,       
		url:baseURL+api+isoPais+"/lotes/reproceso/cargarMasivo", 

		add: function (e, data) {
			f=$('#userfile').val();  
			$('#archivo').val($('#userfile').val());
			dat = data;

			var ext = $('#userfile').val().substr( $('#userfile').val().lastIndexOf(".") +1 );
			if( ext === "txt" || ext === "TXT" ){
				data.context = $('#cargarXLS').click(function () {  

					$("#cargarXLS").replaceWith('<h3 id="cargando_archivo">Cargando...</h3>');  
				                   // dat.formData = {'data-rif':$("option:selected","#listaEmpresasSuc").attr("data-rif")};               
				                   dat.submit().success( function (result, textStatus, jqXHR){

				                   	if(!result.ERROR){
				                         // mostrarError(result);
				                     }else{
				                     	if(result.ERROR=='-29'||result.ERROR=='-61'){
				                     		alert('Usuario actualmente desconectado'); location.reload();
				                     	}else{
				                     		notificacion("Cargar archivo masivo beneficiarios",result.ERROR);}
				                     	}                        


				                     	$('#userfile').val("");
				                     	$('#archivo').val("");
				                     }); 

				               });
	}else{
		notificacion("Cargar archivo masivo beneficiarios","Tipo de archivo no permitido. <h5>Formato requerido: .txt</h5>");
		$('#userfile').val("");
		$('#archivo').val("");
	}
	},
	done: function (e, data) {

		$('#userfile').val(""); $('#archivo').val("");
		$('#cargando_archivo').replaceWith( '<button id="cargarXLS" >Cargar archivo</button>' );
	},
	error: function(e){
		notificacion("Cargar archivo masivo beneficiarios","Error al intentar cargar el archivo");
		$('#userfile').val(""); $('#archivo').val("");
		$('#cargando_archivo').replaceWith( '<button id="cargarXLS" >Cargar archivo</button>' );
	}
	});

	var tipoBuskr;
	$("#buscar").on("click", function(){
		tipoBuskr = $("#tipoChequeBuscar").val();
		$("#loading").dialog({title:"Buscar beneficiarios",modal:true,resizable:false,close: function(){$(this).dialog("destroy");}})
		$.post(baseURL+api+isoPais+'/lotes/reproceso/buscar',{'data-tipo':tipoBuskr})
		.done(function(data){
			$("#loading").dialog("destroy");
			if(!data.ERROR){

				$("#lista-reproceso").removeClass('elem-hidden');
				$("#lista-reproceso tbody").empty();
				$("#reprocesar").parents('div').removeClass("elem-hidden");

				$.each(data.lista, function(k,v){
					datos = JSON.stringify(v);
					tr = "<tr datos='"+datos+"'>";
					tr += "<td class='td-corto'>"+v.id_per+"</td><td >"+v.nombre+" "+v.apellido+"</td><td >"+v.beneficiario+"</td><td >"+v.nro_cuenta+"</td><td>"+v.monto_total+"</td>";
					tr += "<td class='td-corto'><a id='iconModificar'><span aria-hidden='true' class='icon' data-icon=&#xe08f; title='Modificar'></span></a>";
					tr += "<a id='iconEliminar'><span aria-hidden=true class=icon data-icon=&#xe067; title='Eliminar'></span></a></td></tr>";
					$("#lista-reproceso tbody").append(tr);
				});

			}else if(data.ERROR=='-29'||data.ERROR=='-61'){
				alert('Usuario actualmente desconectado');
				location.reload();
			}else{
				notificacion('Buscar beneficiarios', data.ERROR);
			}
		});

	});

	$("#lista-reproceso").on("click","#iconModificar", function(){

		if(tipoBuskr=="2"){ 
			$("#camposBenef .chq-elect").addClass('elem-hidden');
		}else{
			$("#camposBenef .chq-elect").removeClass('elem-hidden');
		}
		$("#camposBenef").dialog({
			title: 'Modificar beneficiario',
			modal:true, 
			width:660,
			buttons:{
				Modificar: function(){
					datosPost = {};
					datosPost.tipo = tipoBuskr;
					datosPost.idPersona = $("#camposBenef #idPersona").val();
					datosPost.apellEmpl = $("#camposBenef #apellEmpl").val();
					datosPost.nombEmpl = $("#camposBenef #nombEmpl").val();
					datosPost.emailEmpl = $("#camposBenef #emailEmpl").val();
					datosPost.apellInfant = $("#camposBenef #apellInfant").val();
					datosPost.nombInfant = $("#camposBenef #nombInfant").val();
					datosPost.nombGuard = $("#camposBenef #nombGuard").val();
					datosPost.idfiscalGuard = $("#camposBenef #idfiscalGuard").val();
					datosPost.nroCuentaGuard = $("#camposBenef #nroCuentaGuard").val();
					datosPost.emailGuard = $("#camposBenef #emailGuard").val();
					datosPost.monto = $("#camposBenef #monto").val();
					datosPost.concepto = $("#camposBenef #concepto").val();

					WSbeneficiario('modificar', datosPost, 'pass', 'Modificar beneficiario',$("#camposBenef"),$(".ui-button"));
				}
			}
		});

	});

	$("#lista-reproceso").on("click","#iconEliminar", function(){

		nomb = $(this).parents('tr').attr('nomb');
		ci = $(this).parents('tr').attr('ci');

		var canvas = "<div id='dialog-confirm'>";
          canvas +="<p>Beneficiario: "+nomb+"</p>";          
          canvas += "<fieldset><input type='password' id='pass' placeholder='Ingrese su contraseña' class='text ui-widget-content ui-corner-all'/>";
          canvas += "<h5 id='msg'></h5></fieldset></div>";  

          var pass;

          $(canvas).dialog({
          	title: 'Eliminar beneficiario',
          	modal: true,
          	resizable: false,
          	close: function(){$(this).dialog("destroy");},
          	buttons: {
          		Eliminar: function(){
          			pass = $(this).find('#pass').val();

          			if( pass!==""){

          				pass = hex_md5( pass );
          				$('#pass').val( '' );
          				$(this).dialog('destroy');
          				var $aux = $('#loading').dialog({title:"Eliminando beneficiario",modal: true, resizable:false, close:function(){$aux.dialog('close');}}); 
          				$.post(baseURL+api+isoPais+"/lotes/reproceso/eliminar", {'data-ci':ci, 'data-pass':pass}).done(
          					function(data){

          						$aux.dialog('destroy');

          						if(!data.ERROR){                  
          							notificacion("Eliminando beneficiario",'Eliminación exitosa');

          							$(this).parents('tr').fadeOut("slow");
          							
          						}else{           
          							if(data.ERROR=='-29'){
          								alert('Usuario actualmente desconectado');  location.reload();
          							}      else{     
          								notificacion("Eliminando beneficiario",data.ERROR);}

          							}

          						});

          			}else{
          				$(this).find( $('#msg') ).text('Debe ingresar su contraseña');
          			}

          		}
          	}
          });
	});


	$("#reprocesar").on('click', function(){

		$aux = $('#loading').dialog({title:'Generando Orden de Servicio',close: function(){$(this).dialog('close');}, modal: true, resizable:false});
	 	$.post(baseURL+api+isoPais+"/lotes/reproceso/reprocesar")
	 	.done(function(data) { 
	 		$aux.dialog('destroy');
	 		if(data.indexOf("ERROR")==-1){
	 			$("<div><h3>Proceso exitoso</h3><h5>Redireccionando...</h5></div>").dialog({title:"Reprocesar lotes", modal:true, resizable:false,close:function(){$(this).dialog('destroy');}});
	 			//notificacion("Confirmar cálculo orden de servicio","Proceso exitoso");
	 			$("#data-OS").attr('value',data);
      			$("form#toOS").submit(); 

	 		}else{
	 			var jsonData = $.parseJSON(data);
	 			if(jsonData.ERROR=='-29'){
	 				alert('Usuario actualmente desconectado'); location.reload();
	 			}
	 			notificacion("Reprocesar lotes",jsonData.ERROR);	 			
	 		}
	 		
	 	});

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

}); // Fin Document ready