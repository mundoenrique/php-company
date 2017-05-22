$(function(){ // Document ready

	var path =window.location.href.split( '/' );
	var baseURL = path[0]+ "//" +path[2]+'/'+path[3];
	var isoPais = path[4];
	var api ="/api/v1/";
	var tamPg = 10;
	var selPgActual=1;
	var tipoLote;


	$("#crear").click(function(){
		$('.crear').removeClass('elem-hidden');
		$('.cargar').addClass('elem-hidden');
		$('.buscar').addClass('elem-hidden');

		$("#tipoCheque").change();

		resett();
	});

	$("#cargar").click(function(){
		$('.crear').addClass('elem-hidden');
		$('.cargar').removeClass('elem-hidden');
		$('.buscar').addClass('elem-hidden');
		resett();
	});


	$("#tipoCheque").on('change', function(){

		tipo = $(this).val();
		tipo=="G" ? $('.chq-elect').addClass('elem-hidden') : $('.chq-elect').removeClass('elem-hidden');

		$('.campos-reproceso input').val('');
		$('.error').removeClass('error');
	});


	$('#monto').on('keyup', function(){
		if( !$(this).val().match(/^-?[0-9]+([\,][0-9]{0,2})?$/) ){
			$(this).val('');
		}
	});


	$('#btnCrearBenf').on('click', function(){
		
		$('#monto').keyup();
		$('#nroCuentaGuard').keyup();

		pass=$('#passcrear').val();
		$('#passcrear').val('');

		datosPost = {};
		datosPost.tipo = $('#tipoCheque').val();
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
		datosPost.paginar = true;
		datosPost.pgActual = 1;
		datosPost.tamPg = tamPg;
		
		WSbeneficiario('crear', datosPost, pass, 'Crear beneficiario',$(".crear"), $("#btnCrearBenf"));


	});

	function WSbeneficiario(funcion, datosPost,pass, titulo, $contenedor, $btn){

		tipoLote = datosPost.tipo.toUpperCase();


	    $contenedor.find(".error").removeClass("error");

		if( verificar(datosPost, datosPost.tipo, $contenedor) ){

			if( pass=='' ){
				notificacion(titulo, "Debe ingresar su contraseña.");
				$("#pass"+funcion).addClass("error");
			}else{
				$("#pass"+funcion).removeClass("error");
				datosPost.pass=hex_md5(pass);

				$("#loading").dialog({title:titulo,modal:true,resizable:false,close: function(){$(this).dialog("destroy");}})
				$btn.hide(); 
				datosPost.monto = datosPost.monto.replace(',','.');
				$.post(baseURL+api+isoPais+'/lotes/reproceso/'+funcion, datosPost).done(function(data){
					$("#loading").dialog("destroy");
					$btn.show();
					if(!data.ERROR){
						$(".ui-dialog-content").dialog("destroy");
						notificacion(titulo,'Proceso exitoso');
						$('.campos-reproceso input').val('');		
						
						$(".buscar").addClass('elem-hidden');
						funcion=='modificar' ? $("."+datosPost.pgActual).remove():$("#lista-reproceso tbody").empty();
						
						pintar(data);	
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
		alfanumericRegex = /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]*$/;
		alfaRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]*$/;
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

			var ext = $('#userfile').val().substr( $('#userfile').val().lastIndexOf(".") +1 ).toLowerCase();
			if( ext == "txt"|| ext == "xls" || ext=="xlsx" ){
				data.context = $('#cargarXLS').click(function () {  

					$("#cargarXLS").replaceWith('<h3 id="cargando_archivo">Cargando...</h3>');  
				    dat.formData = {'data-tipoLote':$("#tipoCheque").val()};               
				    dat.submit().success( function (result, textStatus, jqXHR){

				        if(!result.ERROR){
				            mostrarError(result);
				           
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
				notificacion("Cargar archivo masivo beneficiarios","Tipo de archivo no permitido. <h5>Formato requerido: .txt ó excel</h5>");
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

function mostrarError(result){
      
  if(result.rc!="0"){

    var canvas = "<h4>ENCABEZADO</h4>";
    $.each(result.mensajes.erroresEncabezado.errores,function(k,v){
      canvas += "<h6>"+v+"</h6>"; 
    });    
  
    canvas += "<h4>REGISTRO</h4>";
    $.each(result.mensajes.erroresRegistros, function(k,vv){
        canvas += "<h5>"+vv.nombre+"</h5>";
        $.each(result.mensajes.erroresRegistros[k].errores, function(i,v){          
            canvas += "<h6>"+v+"<h6/>";
        });

    });
    notificacion(result.msg, canvas);
  
  }else{
    notificacion("Cargando archivo", "Archivo cargado con éxito.\n"+result.msg);
     tipoLote = $("#tipoCheque").val().toUpperCase();
     $("#lista-reproceso tbody").empty();
	 pintar(result);
  }
 
}


$("#buscar").on("click", function(){
	$("#lista-reproceso tbody").empty();
	resett();
	tipoLote = $("#tipoCheque").val().toUpperCase();
	buscar(1);
});

function buscar(pgActual){
	$('.crear').addClass('elem-hidden');
	$('.cargar').addClass('elem-hidden');
	$(".buscar").addClass('elem-hidden');
	$("#tipoCheque").attr('disabled',true);
	$("#buscar").attr('disabled',true);

	$("#loading").dialog({title:"Buscar beneficiarios",modal:true,resizable:false,close: function(){$(this).dialog("destroy");}})

	$.post(baseURL+api+isoPais+'/lotes/reproceso/buscar',{'data-tipo':tipoLote,'data-paginar':true,'data-pgActual':pgActual,'data-tamPg':tamPg})
	.done(function(data){
		$("#tipoCheque").removeAttr('disabled');
		$("#buscar").removeAttr('disabled');
		$("#loading").dialog("destroy");

		if(!data.ERROR){

			pintar(data);

		}else if(data.ERROR=='-29'||data.ERROR=='-61'){
			alert('Usuario actualmente desconectado');
			location.reload();
		}else{
			$('.buscar').addClass('elem-hidden');
			notificacion('Buscar beneficiarios', data.ERROR);
		}
	});
}

var totalRegistros;
function pintar(data){
	$('.crear').addClass('elem-hidden');
	$('.cargar').addClass('elem-hidden');
	$('.buscar').removeClass('elem-hidden');
	
	totalRegistros = data.totalRegistros;

	if(tipoLote=='G'){
		$("#td-nombre-2").addClass('elem-hidden');
		$(".td-elect").addClass("td-largo");
	}else{
		$("#td-nombre-2").removeClass('elem-hidden');
		$(".td-elect").removeClass("td-largo");
	}

	if(allAll){
		checked = "checked";
	}else{
		checked = null;
	}

	$.each(data.lista, function(k,v){		
		datos = JSON.stringify(v);
		tr = "<tr class='"+data.paginaActual+"' datos='"+datos+"' all="+allAll+">";
		tr += "<td class='checkbox-select'><input id='select' type='checkbox' "+checked+" /></td>";
		if(tipoLote=='E'){
			$("#th-empleado").addClass("td-medio");
			tr += "<td class='td-medio'>"+v.id_per+"</td><td class='td-medio'>"+v.nombre.toLowerCase()+" "+v.apellido.toLowerCase()+"</td><td >"+v.beneficiario.toLowerCase()+"</td><td id='td-nombre-2'>"+v.nro_cuenta+"</td><td class='td-medio'>"+v.monto_total+"</td>";
		}else{
			$("#th-empleado").removeClass("td-medio");
			tr += "<td class='td-medio'>"+v.id_per+"</td><td>"+v.nombre.toLowerCase()+" "+v.apellido.toLowerCase()+"</td><td class='td-largo'>"+v.beneficiario.toLowerCase()+"</td><td class='td-medio'>"+v.monto_total+"</td>";
		}
		tr += "<td class='td-corto'><a id='iconModificar'><span aria-hidden='true' class='icon' data-icon=&#xe08f; title='Modificar'></span></a>";
		tr += "<a id='iconEliminar'><span aria-hidden=true class=icon data-icon=&#xe067; title='Eliminar'></span></a></td></tr>";
		$("#lista-reproceso tbody").append(tr);
	});

	paginar(data);
}

$("#lista-reproceso").on("click","#iconModificar", function(){

	$("#camposBenef").find(".error").removeClass('error');

	tipoLote=="G" ? $("#camposBenef .chq-elect").addClass('elem-hidden'):$("#camposBenef .chq-elect").removeClass('elem-hidden');

	datos = JSON.parse($(this).parents('tr').attr('datos'));
	pg=$(this).parents('tr').attr('class');

	$("#camposBenef #idPersona").val(datos.id_per);
	$("#camposBenef #apellEmpl").val(datos.apellido);
	$("#camposBenef #nombEmpl").val(datos.nombre);
	$("#camposBenef #emailEmpl").val(datos.email_empleado);
	$("#camposBenef #apellInfant").val(datos.apellido_infante);
	$("#camposBenef #nombInfant").val(datos.nombre_infante);
	$("#camposBenef #nombGuard").val(datos.beneficiario);
	$("#camposBenef #idfiscalGuard").val(datos.rif_guarderia);
	$("#camposBenef #nroCuentaGuard").val(datos.nro_cuenta);
	$("#camposBenef #emailGuard").val(datos.email_guarderia);
	$("#camposBenef #monto").val(datos.monto_total.replace('.',''));
	$("#camposBenef #concepto").val(datos.concepto);

	$("#camposBenef").dialog({
		title: 'Modificar beneficiario',
		modal:true, 
		width:660,
		buttons:{
			Modificar: function(){
				datosPost = {};
				datosPost.tipo = tipoLote;
				datosPost.idPersona = datos.id_per;
				datosPost.apellEmpl = datos.apellido;
				datosPost.nombEmpl = datos.nombre;
				datosPost.emailEmpl = $("#camposBenef #emailEmpl").val();
				datosPost.apellInfant = datos.apellido_infante;
				datosPost.nombInfant = datos.nombre_infante;
				datosPost.nombGuard = $("#camposBenef #nombGuard").val();
				datosPost.idfiscalGuard = $("#camposBenef #idfiscalGuard").val();
				datosPost.nroCuentaGuard = $("#camposBenef #nroCuentaGuard").val();
				datosPost.emailGuard = $("#camposBenef #emailGuard").val();
				datosPost.monto = $("#camposBenef #monto").val();
				datosPost.concepto = $("#camposBenef #concepto").val();
				datosPost.id_registro = datos.id_registro;
				datosPost.paginar = true;
				datosPost.pgActual = pg;
				datosPost.tamPg = tamPg;

				WSbeneficiario('modificar', datosPost, $("#passmodificar").val(), 'Modificar beneficiario',$("#camposBenef"),$(".ui-button"));
				$("#passmodificar").val("");
			}
		},
		create: function(ev, ui){
			$('.ui-dialog-buttonpane .ui-dialog-buttonset button').before("<input type='password' id=passmodificar placeholder='Ingrese su contraseña' style='margin-right:10px'>");
		},
		close: function(){$(this).dialog("destroy");}
	});

});

$("#lista-reproceso").on("click","#iconEliminar", function(){

	datos = JSON.parse($(this).parents('tr').attr('datos'));

	var canvas = "<div id='dialog-confirm'>";
	canvas +="<p>Empleado: "+datos.nombre+' '+datos.apellido+"</p>";          
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
					
					eliminarBeneficiario(pass,[datos.id_registro]);

				}else{
					$(this).find( $('#msg') ).text('Debe ingresar su contraseña');
				}

			}
		}
	});
});


$("#reprocesar").on('click', function(){

	pass = $("#passreprocesar").val();
	$("#passreprocesar").val('')

	if (pass!='') {
		$("#passreprocesar").removeClass('error');
		$aux = $('#loading').dialog({title:'Generando Orden de Servicio',close: function(){$(this).dialog('close');}, modal: true, resizable:false});

		$.post(baseURL+api+isoPais+"/lotes/reproceso/reprocesar", {"data-lista":listarItems(),"data-tipoLote":tipoLote,"data-pass":hex_md5(pass)} )
		.done(function(data) { 
			$aux.dialog('destroy');
			if(data.indexOf("ERROR")==-1){
				$("<div><h3>Proceso exitoso</h3><h5>Redireccionando...</h5></div>").dialog({title:"Reprocesar lotes", modal:true, resizable:false,close:function(){$(this).dialog('destroy');}});

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
	}else{
		notificacion('Reprocesar lotes','Debe ingresar su contraseña');
		$("#passreprocesar").addClass('error');
	}

});

$("#btn-eliminar-benf").on("click",function() {

	pass = $("#passreprocesar").val();

	if( pass!==""){
		$("#passreprocesar").removeClass('error');
		pass = hex_md5( pass );
		$('#passreprocesar').val( '' );

		eliminarBeneficiario(pass,listarItems());
	}else{
		notificacion('Eliminar beneficiarios','Debe ingresar su contraseña');
		$("#passreprocesar").addClass('error');
	}
	resett();
});

function listarItems () {
	lista = [];

	if(allAll){
		lista=null;
	}else{
		$.each($(":checkbox"),function(k,v){
		if (this.checked && $(this).attr("id")!="selectAll") {
			datos = JSON.parse($(this).parents('tr').attr('datos'));			
			lista.push(datos.id_registro);
		}
		
		});	
	}
	
	return lista;
}

function eliminarBeneficiario (pass, items) {
	var $aux = $('#loading').dialog({title:"Eliminando beneficiario",modal: true, resizable:false, close:function(){$aux.dialog('close');}}); 
	$.post(baseURL+api+isoPais+"/lotes/reproceso/eliminar", 
		{'data-tipoLote':tipoLote, 'data-lista':items,'data-pass':pass, 'data-paginar':true,'data-pgActual':1,'data-tamPg':tamPg})
	.done(
		function(data){

			$aux.dialog('destroy');

			if(!data.ERROR){                  
				notificacion("Eliminando beneficiario",'Proceso exitoso');
				$("#lista-reproceso tbody").empty();
				pintar(data);

			}else{           
				if(data.ERROR=='-29'){
					alert('Usuario actualmente desconectado');  location.reload();
				}else{     
					if(data.rc=='-150'){
						$('.buscar').addClass('elem-hidden');
					}
					notificacion("Eliminando beneficiario",data.ERROR);
				}

			}

		});
}


$("#selectAll").on("click", function () {
	if($(this).is(':checked')){
		if(!allAll && tamPg<=parseInt(totalRegistros)){
			alert = "<div class='not-info'><p>Ha seleccionado esta página ("+tamPg+" items) <a id='allAll'>seleccionar todas las páginas ("+totalRegistros+" items)</a></p></div>";
			$("#lista-reproceso").prepend(alert);			
		}		
		$("."+selPgActual).attr("all",1);
		$.each($("."+selPgActual+" :checkbox"),function(k,v){			
			this.checked=true;
		});
	}else{
		if(allAll){
			resett();
			$("tbody tr").attr("all",0);
		}else{
			$(".not-info").remove();		
			allAll=false;
			$("."+selPgActual).attr("all",0);
			$.each($("."+selPgActual+" :checkbox"),function(k,v){
				this.checked=false;
			});
		}		
	}
	
});

var allAll;
$("#allAll").on("click", function () {
	$(".not-info").hide();
	allAll=true;
	$.each($(":checkbox"),function(k,v){
		this.checked=true;
	});
})

function resett () {
	$.each($(":checkbox"),function(k,v){
		this.checked=false;
	});
	allAll=false;
}

function paginar(datos){
	$('#paginado').paginate({ 
		count: datos.totalPaginas,
		display: datos.tamanoPagina,
		start: datos.paginaActual,
		border: false,
		text_color: '#79B5E3',
		background_color: 'none', 
		text_hover_color: '#2573AF',
		background_hover_color: 'none', 
		images: false,
		onChange: function(page){     
			$(".not-info").hide();
			selPgActual=page;
			if (  $("."+page).attr("all")==1 || allAll ) {
				$("#selectAll")[0].checked=true;
			}else{
				$("#selectAll")[0].checked=false;
			}
			
			if( !$('#lista-reproceso').find($('.'+page)).hasClass(page) ){
				
				buscar(page);               
			} 
			$('#lista-reproceso tbody tr').hide();
			$('#lista-reproceso .'+page).show();
		}
	});
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

}); // Fin Document ready