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

function WS(funcion, datosPost, titulo){

	$.post(baseURL+api+isoPais+'/lotes/reproceso/'+funcion, datosPost).done(function(data){

		if(!data.ERROR){
			$(".ui-dialog-content").dialog("destroy");
			notificacion(titulo,'Proceso exitoso');
			$('.campos-reproceso input').val('');
			$(".buscar").addClass('elem-hidden');
			funcion=='modificar' ? $("."+datosPost.pgActual).remove():$("#lista-reproceso tbody").empty();
			buscar(1);
		}else{
			if(data.ERROR=='-29'){
				alert('Usuario actualmente desconectado'); location.reload();
			}else{
				notificacion(titulo, data.ERROR);
			}
		}
	});
}
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
		conceptoRegex = /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]{1,20}$/;
		alfaRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]*$/;
		ciRegex = /^[0-9]{6,8}$/;
		nroctaRegex = /^[0-9]{20}$/;
		montoRegex = /^-?[0-9]+([\,][0-9]{0,2})?$/;

		var validadorRegEx = {
			validez : true,
			validadorRegEx : function( varRegex, value, id, contenedor ){
				if( !varRegex.test( value ) || value=='' ){
					contenedor.find( id ).addClass( 'error' );
					this.validez = false;
				}
			}
		}

		validadorRegEx.validez = true;

		if( tipo == "E" ){
			validadorRegEx.validadorRegEx( emailRegex, datosPost.emailEmpl, '#emailEmpl', $contenedor );
			validadorRegEx.validadorRegEx( emailRegex, datosPost.emailGuard, '#emailGuard', $contenedor );
			validadorRegEx.validadorRegEx( rifRegex, datosPost.idfiscalGuard, '#idfiscalGuard', $contenedor );
			validadorRegEx.validadorRegEx( nroctaRegex, datosPost.nroCuentaGuard, '#nroCuentaGuard', $contenedor );
		}

	  validadorRegEx.validadorRegEx( ciRegex, datosPost.idPersona, '#idPersona', $contenedor );
		validadorRegEx.validadorRegEx( alfaRegex, datosPost.apellEmpl, '#apellEmpl', $contenedor );
		validadorRegEx.validadorRegEx( alfaRegex, datosPost.nombEmpl, '#nombEmpl', $contenedor );
		validadorRegEx.validadorRegEx( alfaRegex, datosPost.apellInfant, '#apellInfant', $contenedor );
		validadorRegEx.validadorRegEx( alfaRegex, datosPost.nombInfant, '#nombInfant', $contenedor );
		validadorRegEx.validadorRegEx( alfanumericRegex, datosPost.nombGuard, '#nombGuard', $contenedor );
		validadorRegEx.validadorRegEx( conceptoRegex, datosPost.concepto, '#concepto', $contenedor );
		validadorRegEx.validadorRegEx( montoRegex, datosPost.monto, '#monto', $contenedor );
		if(datosPost.monto.length >=20){
				$contenedor.find("#monto").addClass('error');
				validadorRegEx.validez = false;
		}

		return validadorRegEx.validez;

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

	$("#loading").dialog({title:"Buscar Datos",modal:true,resizable:false,close: function(){$(this).dialog("destroy");}})

	$.post(baseURL+api+isoPais+'/lotes/reproceso/buscar',{'data-tipo':tipoLote,'data-paginar':true,'data-pgActual':pgActual,'data-tamPg':tamPg})
	.done(function(data){
		$("#tipoCheque").removeAttr('disabled');
		$("#buscar").removeAttr('disabled');
		$("#loading").dialog("destroy");

		ReprocesoMasivo.countTotal = data.totalRegistros;

		if(data.rc == 0){
			pintar(data);
		}else if(data.rc=='-29'||data.rc=='-61'){
			alert('Usuario actualmente desconectado');
			location.reload();
		}else{
			$('.buscar').addClass('elem-hidden');
			notificacion('Buscar Datos', data.ERROR);
		}
	});
}
var ReprocesoMasivo = {
	monto : "",
	concepto : "",
	countTotal : 0,
	countAproved :0,
	dataPreparada : [],
	msjStatusHtml : function(){
		return " La modificación aplica a <strong>"+this.countTotal+"</strong> registros<br><br>";
	},
	createTh : function ( texto ){
		var th = document.createElement( "th" );
		th.appendChild( document.createTextNode(texto) );
		return th;
	},
	createTh_ : function (nodo){
		var th = document.createElement( "th" );
		th.appendChild(nodo);
		return th;
	},
	createInput : function( type, id, value, clase ){
		var input  = document.createElement("input");
		input.setAttribute( "type", type );
		input.setAttribute( "id", id );
		input.setAttribute( "class", clase);
		input.setAttribute( "value", value );
		input.onchange = function(){updateInput(this)};
		return input;
	},
	run : function ( camposReprocesoMasivo ){

		this.hide('Estadistica');
		this.hide('camposReprocesoMasivo');
		var dataBuild = "";

		buildHtml( dataBuild, this );

			this.addHtml( 'MensajeRegistros', this.msjStatusHtml() );
			this.show( 'Estadistica' );
			this.show( 'camposReprocesoMasivo' );

			camposReprocesoMasivo.dialog({
				title: "Gestión de Reproceso Masivo de Datos",
				modal: true,
				width:510,
				height: 230,
				resizable: false,
				close:function(){
					ReprocesoMasivo.hide('Estadistica');
					$( this ).dialog( "destroy" );
					ReprocesoMasivo.addHtml( 'Estadistica', '');
					ReprocesoMasivo.hide( 'camposReprocesoMasivo' );
				},
				buttons: {
					Modificar: function(){
						$( this ).dialog( "destroy" );
						ReprocesoMasivo.hide('Estadistica');
						ReprocesoMasivo.addHtml('Estadistica', '' );
						ReprocesoMasivo.hide( 'camposReprocesoMasivo' );
						ReprocesoMasivo.sendData();
					}
				}
			});
	},
	inputsCaptured : function( className ){

		var listaInputs = [];

		$.each($(":checkbox"),function(k,v){

			if (this.checked && $(this).attr("id")!="selectAll") {
				var datos = JSON.parse($(this).parents('tr').attr('datos'));

				listaInputs.push(datos);
			}
		});
		return listaInputs;
	},
	sendData : function (){
		datosPost = {};
		datosPost.monto =  this.monto;
		datosPost.concepto = this.concepto;

		var patron1 = /[0-9]+|[0-9]+([,][0-9]+)|[0-9]+([.][0-9]+)|[0-9]+([.][0-9]+)+([,][0-9]+)|[0-9]+([,][0-9]+)+([.][0-9]+)/;//Numeros
		if(	datosPost.concepto !=  "" || datosPost.monto == "" || datosPost.monto == " " ||  patron1.test(datosPost.monto) ){
				WS( 'reprocesarMasivo', datosPost, 'Gestión de Reproceso Masivo de Datos');
				 this.monto = "";
				 this.concepto = "";
		}else{
				notificacion('Gestión de Reproceso Masivo de Datos','Operación fallida, datos incompletos.');
		}
	},
	addHtml : function( id, html){
		var tr = document.getElementById(id);
		tr.innerHTML = html;
	},
	show:function( element ) {
        var ElementId = document.getElementById( element );
        if( ElementId ){
            ElementId.style.display = 'block';
        }//fin del if
  },
  hide : function( element ) {
      var ElementId = document.getElementById( element );
      if( ElementId ){
           ElementId.style.display = 'none';
      }//fin del if
  },
	styleTableClass : function( element ) {
      var ElementClass = document.getElementsByClassName( element );
      if( ElementClass ){
				ElementClass.style.border = '1px solid black';
      }//fin del if
  }
};

function updateInput( e ){
	var str = e.id;
	var res = ( e.className == 'conceptoRPM' )?
							str.split( "conceptoRPM" ):str.split( "montoRPM" );
	if( e.className == 'conceptoRPM' ){
			ReprocesoMasivo.concepto = e.value;
	}else{
			ReprocesoMasivo.monto = e.value;
	}
}

function buildHtml( data, ReprocesoMasivo ){

	var style = "border : 1px solid #A4A4A4;background: none repeat scroll 0 0 #54c2d0;color: white;font-size: 13px;";
	var style2 = "border : 1px solid #A4A4A4;padding-right:5px;"+
						 "padding-left: 5px;font-weight: initial;";

		var tr = document.createElement( "tr" );

		var th3 =  ReprocesoMasivo.createTh('Concepto');
		th3.style.border = '1px solid #A4A4A4';
		th3.style.background = 'none repeat scroll 0 0 #54c2d0';
		th3.style.color = 'white';
		th3.style.fontSize =  '13px';
		var th4 =  ReprocesoMasivo.createTh('Monto Total');
		th4.style.border = '1px solid #A4A4A4';
		th4.style.background = 'none repeat scroll 0 0 #54c2d0';
		th4.style.color = 'white';
		th4.style.fontSize =  '13px';

		tr.appendChild( th3 );
		tr.appendChild( th4 );

		var TablaMasiva = document.getElementById( 'Estadistica' );
		TablaMasiva.appendChild( tr );

			var tr2 = document.createElement( "tr" );

			var inputMontoRPM = ReprocesoMasivo.createInput( "input",
				'montoRPM', "", 'montoRPM');

			var inputConceptoRPM =	ReprocesoMasivo.createInput( "input",
					'conceptoRPM', "",'conceptoRPM' );

			inputConceptoRPM.setAttribute( "size", 35 );

			var th2_3 = ReprocesoMasivo.createTh_( inputConceptoRPM );
			var th2_4 = ReprocesoMasivo.createTh_( inputMontoRPM );

			tr2.appendChild( th2_3 );
			tr2.appendChild( th2_4 );

			TablaMasiva.appendChild( tr2 );

}
$("#modificacionMasiva").on('click', function(){
		ReprocesoMasivo.run( $("#camposReprocesoMasivo"));
});

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
		tr += "<td class='checkbox-select'><input id='select' class='checkboxSelected' type='checkbox' "+checked+" /></td>";
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
		title: 'Modificar Datos',
		modal:true,
		width:660,
		buttons:{
			Modificar: function(){
				datosPost = {};
				datosPost.tipo = tipoLote;
				datosPost.idPersona = 	$("#camposBenef #idPersona").val();
				datosPost.apellEmpl = $("#camposBenef #apellEmpl").val();
				datosPost.nombEmpl = 	$("#camposBenef #nombEmpl").val();
				datosPost.emailEmpl = $("#camposBenef #emailEmpl").val();
				datosPost.apellInfant =	$("#camposBenef #apellInfant").val();
				datosPost.nombInfant =	$("#camposBenef #nombInfant").val();
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

				WSbeneficiario('modificar', datosPost, $("#passmodificar").val(),
						'Modificar Datos',$("#camposBenef"),$(".ui-button"));
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
	$("#passreprocesar").val('');

	if (pass!='') {

		var tituloModal = 'Seleccione modalidad de pago';
		var nuevoIva = document.getElementById('nuevoIva');

		if( nuevoIva.value == 1 ){

			var idModalidad =  $("input[name=methodChoice]:checked").attr('id');
			var descripcion = $("input[name=methodChoice]:checked").val();

			$("#modal_modalidad_pago").dialog({
					title: tituloModal,
					modal: true,
					resizable: false,
					width: 320,
					buttons: {
							OK: function(){

								$(this).dialog('destroy');

								idModalidad =  $("input[name=methodChoice]:checked").attr('id');
								descripcion = $("input[name=methodChoice]:checked").val();

								if(idModalidad != null){
												idModalidad = idModalidad.split("methodChoice");
												medioPago = {
														 "idPago" : idModalidad[1],
														 "descripcion" : descripcion };
											  sendReproceso( pass, medioPago, tituloModal, tipoLote, nuevoIva.value );
								}else{
									notificacion( "Autorizando lotes", "Por favor escoja una modalidad de pago." );
								}

							}
					}
			});
		}else{
  		sendReproceso( pass, "", tituloModal, tipoLote, nuevoIva.value );
		}
	}else{
		notificacion('Reprocesar Datos','Debe ingresar su contraseña');
		$("#passreprocesar").addClass('error');
	}

});

function sendReproceso( pass, medioPago, tituloModal, tipoLote, nuevoIva ){

	if(pass!="" ){

		$("#passreprocesar").removeClass('error');
		$("#loading").dialog({title:tituloModal,modal:true,resizable:false,
							close: function(){$(this).dialog("destroy");}});

		var url = baseURL+api+isoPais+"/lotes/reproceso/reprocesar";

		var SendData = {
			"data-lista":listarItems(),
			"data-tipoLote":tipoLote,
			"data-pass":hex_md5(pass),
			"data-medio-pago":medioPago,
			"data-nuevo-iva" :nuevoIva
		};
		$.post( url, SendData ).done(function(data) {

			$( "#loading" ).dialog( "destroy" );

			if( data.indexOf("ERROR")==-1 ){

				var htmlSuccess = "<div><h3>Proceso exitoso</h3><h5>Redireccionando...</h5></div>";

				$( htmlSuccess ).dialog(
						{ title:"Reprocesar Datos", modal:true, resizable:false,
								close:function(){$(this).dialog('destroy');}} );

				$( "#data-OS" ).attr( 'value', data );
				$( "form#toOS" ).submit();

			}else{

				var jsonData = $.parseJSON( data );

				if( jsonData.ERROR == '-29' ){
					alert( 'Usuario actualmente desconectado' ); location.reload();
				}

				notificacion( "Reprocesar Datos", jsonData.ERROR );

			}
		});
	}else{
			notificacion("Autorizando lotes",
			"<h2>Verifique que: </h2><h3>1. Ha seleccionado al menos un lote</h3><h3>2. Ha ingresado su contraseña</h4><h3>3. Ha seleccionado el tipo orden de servicio</h3>");
	}
}

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
	var $aux = $('#loading').dialog({title:"Eliminando beneficiario",
	modal: true, resizable:false, close:function(){$aux.dialog('close');}});
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
