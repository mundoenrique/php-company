$(function() { // Document ready

  var f, dir, forma;


 // $('thead').hide();
 $('#lotes-2').show();
 $(".aviso").removeClass("elem-hidden");
  actualizarLote();

// Cargar archivo

  var dat;

  $('#archivo').on('click',function(){
      $("#userfile").trigger('click');
  });

  $('#userfile').fileupload({
    type: 'post',
    replaceFileInput:false,
   // formData: {'data-tipoLote':tipol},
    url:baseURL+isoPais+"/lotes/upload",

        add: function (e, data) {
          f=$('#userfile').val();
          $('#archivo').val($('#userfile').val());
            dat = data;
            var ext = $('#userfile').val().substr( $('#userfile').val().lastIndexOf(".") +1 ).toLowerCase();
            if( ext == "txt"|| ext == "xls" || ext=="xlsx"){
            data.context = $('#cargaLote')
                .click(function () {

                  if( $("#tipoLote").val() != ""  ){
                    $("#cargaLote").replaceWith('<h3 id="cargando">Cargando...</h3>');
										var ceo_cook = decodeURIComponent(
											document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
										);
                    dat.formData = {'data-tipoLote':$("#tipoLote").val(), 'data-formatolote':$("#tipoLote option:selected").attr('rel'), ceo_name: ceo_cook };
                    dat.submit().success( function (result, textStatus, jqXHR){

                      if(result){
                        result = $.parseJSON(result);

                        if(!result.ERROR){
                          mostrarError(result);
                        }else{
                           if(result.ERROR=='-29'){
                          alert('Usuario actualmente desconectado');  location.reload();
                          } else{

                          notificacion("Cargando archivo",result.ERROR);}
                        }
                      }


                      $('#userfile').val("");
                      $('#archivo').val("");
                    });
                  }else{
                    notificacion("Cargando archivo","Seleccione un tipo de lote");
                  }
                });
            }else{
              notificacion("Cargando archivo","Tipo de archivo no permitido. <h5>Formato requerido: txt</h5>");
              $('#userfile').val("");
              $('#archivo').val("");
            }
        },
        done: function (e, data) {

            $('#userfile').val("");
            $('#archivo').val("");
            $('#cargando').replaceWith( '<button id="cargaLote" >'+$('#boton').val()+'</button>' );
        },
        error: function(e){
          notificacion("Cargando archivo","error al intentar cargar el archivo");
           $('#userfile').val("");
            $('#archivo').val("");
          $('#cargando').replaceWith( '<button id="cargaLote" >'+$('#boton').val()+'</button>' );
        }
    });

//-- Fin cargar archivo

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
    actualizarLote();
  }

}

// Refrescar lote cada 10 segundos

self.setInterval(function(){actualizarLote()},10000);
var datatable;

function actualizarLote(){

//$("#table-text-lotes tbody").append("<h3 id='actualizador'>Cargando...</h3>");
if(!$("#table-text-lotes").hasClass('dataTable')){
$('#actualizador').show();
}
  $.get(baseURL+api+isoPais+"/lotes/lista/pendientes",
    function(data){


      var icon, batch, color, title;

      if(!data.result.ERROR){

        if($("#table-text-lotes").hasClass('dataTable')){
          $('#table-text-lotes').dataTable().fnClearTable();
          $('#table-text-lotes').dataTable().fnDestroy();
        }

        $("#table-text-lotes tbody").empty();
        $('thead').show();
        forma = 1;

        $.inArray('tebcon',data.funciones)!=-1 ? confirma="" : confirma='hidden';
        $.inArray('tebelc',data.funciones)!=-1 ? elimina="" : elimina='hidden';

        $.each(data.result.lista, function(k,v){

          if( v.estatus==5 ){ //"con error";
            icon = "&#xe003;";
            color = "icon-batchs-red";
            dir = "detalle";
            title = "Ver lote";
          }else if( v.estatus==1 ){ //"ok";
            icon = "&#xe083;";
            color = "icon-batchs-green";
            dir = "confirmacion";
            title = "Confirmar lote";
          }else if( v.estatus==0 ){ //verificando";
            icon = "&#xe00a;";
            color = "icon-batchs-orange";
            title = "Validando lote";
					}else if(v.estatus == 6){ //ok pero con errores
						icon="&#xe083;";
						color="icon-batchs-purple";
						title = "Confirmar lote";
					}

          (v.numLote==="") ? v.numLote = '-' : v.numLote;
          (v.nombre==="") ? v.nombre='-' : v.nombre;

        batch = "<tr><td id='icon-batchs' class="+color+"><span aria-hidden='true' class='icon' data-icon=''></span></td>";
        batch += "<td>"+v.numLote+"</td><td id='td-nombre'>"+v.nombreArchivo+"</td><td class='field-date'>"+v.fechaCarga+"</td><td>"+v.descripcion+"</td>";
        batch += "<td id='icons-options'><a "+elimina+" id='borrar' title='Eliminar Lote' data-idTicket="+v.idTicket+" data-idLote='"+v.idLote+"' data-arch='"+v.nombreArchivo+"'><span aria-hidden='true' class='icon' data-icon='&#xe067;'></span></a>";
				batch += v.estatus == 6 ? "<a "+confirma+" class='detalle' title='Ver lote' data-idTicket="+v.idTicket+" data-edo="+v.estatus+" data-forma="+forma+" data-opc='verLote'><span aria-hidden='true' class='icon' data-icon='&#xe003;'></span></a>" : "";
				batch += "<a "+confirma+" class='detalle' title='"+title+"' data-idTicket="+v.idTicket+" data-edo="+v.estatus+" data-forma="+forma+" ><span aria-hidden='true' class='icon' data-icon="+icon+"></span></a></td></tr>";

        $("#actualizador").hide();
        $("#table-text-lotes tbody").append(batch);

        forma+=1;
      });

        $('#table-text-lotes').dataTable( {
          "iDisplayLength": 10,
          'bDestroy':true,
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
         } );

     }else{
      if(data.result.ERROR=='-29'){
          alert('Usuario actualmente desconectado');  location.reload();
          }

      if(forma>1){
        $('#table-text-lotes').dataTable().fnClearTable();
        $('#table-text-lotes').dataTable().fnDestroy();
      }

      $('thead').hide();
      $("#actualizador").hide();
      $("#table-text-lotes tbody").html("<h2 style='text-align:center'>"+data.result.ERROR+"</h2>");


     }


  });

}//Fin function refrescar()


//--Fin refrescar lotes


// Borrar Lote
$("#table-text-lotes").on("click","#borrar",
  function(){
    var ticket = $(this).attr("data-idTicket");
    var lote = $(this).attr("data-idLote");
    var arch = $(this).attr("data-arch");

    confirmar( $(this).parents('tr'), ticket, lote, arch, "Eliminar Lote" );

  }
);

  //Confirmar borrado lote

    function confirmar($item, ticket, lote, arch, titu){


      var canvas = "<div id='dialog-confirm'>";
      canvas +="<p>Nombre: "+arch+"</p>";
      canvas += "<fieldset><input type='password' id='pass' size=30 placeholder='Ingrese su contraseña' class='text ui-widget-content ui-corner-all'/>";
      canvas += "<h5 id='msg'></h5></fieldset></div>";

      var pass;

      $(canvas).dialog({
        title: titu,
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
              var $aux = $('#loading').dialog({title:"Eliminando lote",modal: true, resizable:false, close:function(){$aux.dialog('close');}});
							var ceo_cook = decodeURIComponent(
								document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
							);
              $.post(baseURL+api+isoPais+"/lotes/eliminar", {'data-idTicket':ticket, 'data-idLote':lote, 'data-pass':pass, ceo_name: ceo_cook}).done(
                function(data){

                $aux.dialog('destroy');

                if(!data.ERROR){
                  notificacion("Eliminando lote",'Eliminación exitosa');

                  $item.fadeOut("slow");
                  actualizarLote();
                }else{
                  if(data.ERROR=='-29'){
                          alert('Usuario actualmente desconectado');  location.reload();
                          }      else{
                  notificacion("Eliminando lote",data.ERROR);}

                }

              });

            }else{
              $(this).find( $('#msg') ).text('Debe ingresar su contraseña');
            }

          }
        }
      });

    }
  //--Fin Confirmar borrado lote

//Fin borrar lote


// Ver Lote
$("#table-text-lotes").on("click", ".detalle",
  function(){
    var estado = $(this).attr("data-edo");
    var ticket = $(this).attr("data-idTicket");
		var opc=$(this).attr("data-opc");

    if(estado=="1" || (estado=="6" && !opc ) ){
      $("form#confirmar").append('<input type="hidden" name="data-estado" value="'+estado+'" />');
      $("form#confirmar").append('<input type="hidden" name="data-idTicket" value="'+ticket+'" />');
      $("form#confirmar").submit();
    }else if(estado=="5" || estado=="6") {
      $("form#detalle").append('<input type="hidden" name="data-estado" value="'+estado+'" />');
      $("form#detalle").append('<input type="hidden" name="data-idTicket" value="'+ticket+'" />');
      $("form#detalle").submit();
    }

});

//Fin Ver lote



//POUP Notificacion

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

//--Fin POUP Notificacion




}); //--Fin document ready :)
