var tbody,tr,td,datos,span,pass,totalDeposito,totalpagos;var tipo=new Array();consultaPagoOS();function consultaPagoOS(){$("#cargando").dialog({modal:true,maxWidth:700,maxHeight:300,dialogClass:"hide-close"});$.post(baseURL+api+isoPais+"/consulta/detalleOS",{idOrden:$("#idOrden").val()}).always(function(a){$(".ui-dialog-content").dialog().dialog("close");if(a.rc=="0"){totalDeposito=a.ordenServicio.montoDeposito;$("#listaBanco").empty();$("#listaBanco").append("<option value >Selecciona Banco</option>");$.each(a.bancos,function(c,b){$("#listaBanco").append('<option codBac="'+b.codBic+'" >'+b.nomBanco+"</option>")});tipo=new Array();$.each(a.listaPagos,function(c,b){tipo[b.idTipoPago]=b.tipoPago});totalpagos=0;$.each(a.pagos,function(c,b){totalpagos=totalpagos+b.nMonto});$(".listapagos").hide();console.log(a.pagos.length);datos=a.ordenServicio;tbody=$("#tbody-datos-OS");tbody.empty();tr=$(document.createElement("tr")).appendTo(tbody);td=$(document.createElement("td")).appendTo(tr);td.html(datos.datosEmpresa.acrazonsocial);td=$(document.createElement("td")).appendTo(tr);td.html(datos.idOrden);td=$(document.createElement("td")).appendTo(tr);td.html(datos.datosEmpresa.acrif);td=$(document.createElement("td")).appendTo(tr);td.html(datos.fechaGeneracion);td=$(document.createElement("td")).appendTo(tr);td.html(datos.cantlotes);td=$(document.createElement("td")).appendTo(tr);td.html(datos.cantreg);td=$(document.createElement("td")).appendTo(tr);td.html(datos.montoDeposito);if(a.pagos.length>0){$(".listapagos").show();tbody=$("#tbody-datos-pagos");tbody.empty();$.each(a.pagos,function(c,b){tr=$(document.createElement("tr")).appendTo(tbody);td=$(document.createElement("td")).appendTo(tr);td.html(tipo[parseInt(b.tipoPago)]);td.attr("class","th-empresa");td=$(document.createElement("td")).appendTo(tr);td.html(b.fecha);td.attr("style","text-align: center");td=$(document.createElement("td")).appendTo(tr);td.html(b.referencia);td.attr("style","text-align: center");td=$(document.createElement("td")).appendTo(tr);td.html(b.bancoid);td.attr("style","text-align: center");td=$(document.createElement("td")).appendTo(tr);td.html(b.nMonto);td.attr("style","text-align: center");td=$(document.createElement("td")).appendTo(tr);td.attr("style","text-align: center");td.append("<a id='eliminar' idpago= '"+b.idPago+"' class='eliminar'><span data-icon='&#xe067' aria-hidden='true' class='icon OS-icon'></span></a>")})}$(".eliminar").click(function(){var b={};b.orden=$("#idOrden").val();b.idPago=$(this).attr("idpago");confirmar("Confirmacion",b)})}else{notificacion("Notificacion","Disculpe,".data.ERROR)}})}$("#fecha_pago").datepicker({defaultDate:"",dateFormat:"dd/mm/yy",changeMonth:true,changeYear:true,numberOfMonths:1,});$("#fecha_pago").datepicker($.datepicker.regional.es);$("#registrar").click(function(){var a={};numberRegex=/^\d*[0-9](|.\d*[0-9]|.\d*[0-9])+$/;a.orden=$("#idOrden").val();a.tipopago=$("option:selected","#tipo").val();a.banco=$("option:selected","#listaBanco").attr("codBac");a.referencia=$("#referencia").val();a.monto=parseFloat($("#monto").val());a.fecha=$("#fecha_pago").val();a.pass=hex_md5($("#pass").val());var b=totalpagos+parseFloat($("#monto").val());if(!($("#monto").val()==""||$("option:selected","#tipo").val()==""||$("option:selected","#listaBanco").val()==""||$("#referencia").val()==""||$("#fecha_pago").val()=="")){if(b<=totalDeposito&&parseFloat($("#monto").val())>0&&numberRegex.test($("#monto").val())){if(!$("#pass").val()==""){$("#pass").attr("style","");$("#monto").attr("style","");$("#cargando").dialog({modal:true,maxWidth:700,maxHeight:300,dialogClass:"hide-close"});$consulta=$.post(baseURL+api+isoPais+"/consulta/registrarPago",a);$consulta.done(function(c){$(".ui-dialog-content").dialog().dialog("close");if(c.rc=="0"){$("#referencia").val("");$("#monto").val("");$("#fecha_pago").val("");$("#pass").val("");consultaPagoOS();notificacion("Notificacion","Registro Agregado correctamente.")}else{notificacion("Notificacion",c.ERROR)}})}else{notificacion("Notificacion","Desculpe, debe ingresar la contraseña antes de continuar con esta operacion");$("#pass").attr("style","border-color:red")}}else{notificacion("Notificacion","Disculpe, el monto a registrar es mayor al total de depositos");$("#monto").attr("style","border-color:red")}}else{notificacion("Notificacion","Disculpe, verifique que todos los campos estan llenos")}});function notificacion(a,c){var b="<div><h5>"+c+"</h5></div>";$(b).dialog({dialogClass: "hide-close",title:a,modal:true,maxWidth:700,maxHeight:300,buttons:{"Aceptar": {
					text: 'Aceptar',
					class: 'novo-btn-primary-modal',
					click: function () {
					$(this).dialog("close");
					}
				}}})}function confirmar(b,a){var c="<div id='dialog-confirm'>";c+="<p>Ingresa tu contraseña</p>";c+="<fieldset><label for='password'>Password: </label>";c+="<input type='password' id='pass' value='' />";c+="</fieldset><h5 id='msg'></h5></div>";$(c).dialog({dialogClass: "hide-close",title:b,modal:true,position:{my:"center top",at:"center 500"},close:function(){$(this).dialog("destroy")},buttons:{
					"Aceptar": {
						text: 'Aceptar',
						class: 'novo-btn-primary-modal',
						click: function () {
							pass=$(this).find("#pass").val();if(pass!==""){a.pass=hex_md5(pass);llamarWSeliminar(pass,a);$(this).find("#pass").val("");$(this).dialog("destroy")}else{$(this).find("#msg").empty();$(this).find("#msg").append("Debes ingresar la contraseña")}}
						}
					}})}function llamarWSeliminar(b,a){$("#cargando").dialog({modal:true,maxWidth:700,maxHeight:300,dialogClass:"hide-close"});$consulta=$.post(baseURL+api+isoPais+"/consulta/eliminaPagoOS",a);$consulta.done(function(c){$(".ui-dialog-content").dialog().dialog("close");if(c.rc=="0"){consultaPagoOS();notificacion("Notificacion","Eliminacion realizada con exito.")}else{notificacion("Notificacion",c.ERROR)}})};
