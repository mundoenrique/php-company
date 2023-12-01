var filtro_busq = {};

$('.fecha').keypress(function (e) {
  if (e.keycode != 8 || e.keycode != 46) {
    return false;
  }
});

$(document).ready(function () {
  $('#cargando_empresa').fadeIn('slow');
  $.getJSON(baseURL + api + isoPais + '/empresas/consulta-empresa-usuario').always(function (response) {
    data = JSON.parse(
      CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8)
    );
    $('#cargando_empresa').fadeOut('slow');
    if (!data.ERROR) {
      $.each(data.lista, function (k, v) {
        $('#Reporte-tarjeta-hambiente').append(
          '<option value="' +
            v.accodcia +
            '" acnomcia="' +
            v.acnomcia +
            '" acrazonsocial="' +
            v.acrazonsocial +
            '" acdesc="' +
            v.acdesc +
            '" acrif="' +
            v.acrif +
            '">' +
            v.acnomcia +
            '</option>'
        );
      });
    } else {
      if (data.ERROR.indexOf('-29') != -1) {
        alert('Usuario actualmente desconectado');
        $(location).attr('href', baseURL + isoPais + '/login');
      } else {
        $('#Reporte-tarjeta-hambiente').append('<option value="" >' + data.ERROR + '</option>');
      }
    }
  });

  $('#Reporte-tarjeta-hambiente').on('change', function () {
    acrif = $('option:selected', this).attr('acrif');

    if (acrif) {
      $('#EstatusLotes-producto').children('option:not(:first)').remove();

      $('#cargando_producto').fadeIn('slow');
      $(this).attr('disabled', true);
      var ceo_cook = decodeURIComponent(
        document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
      );
      var dataRequest = JSON.stringify({
        acrif: acrif,
      });
      dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
      $.post(
        baseURL + api + isoPais + '/reportes/consulta-producto-empresa',
        {
          request: dataRequest,
          ceo_name: ceo_cook,
          plot: btoa(ceo_cook),
        },
        function (response) {
          data = JSON.parse(
            CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8)
          );
          $('#cargando_producto').fadeOut('slow');
          $('#Reporte-tarjeta-hambiente').removeAttr('disabled');
          if (!data.ERROR) {
            $.each(data, function (k, v) {
              $('#EstatusLotes-producto').append(
                '<option value="' + v.idProducto + '" >' + v.descripcion + ' / ' + v.marca.toUpperCase() + '</option>'
              );
            });
          } else {
            if (data.ERROR.indexOf('-29') != -1) {
              alert('Usuario actualmente desconectado');
              $(location).attr('href', baseURL + isoPais + '/login');
            } else {
              $('#EstatusLotes-producto').append('<option value="">Empresa sin productos</option>');
            }
          }
        }
      );
    }
  });

  $('#export_excel').click(function () {
    descargarArchivo(filtro_busq, baseURL + api + isoPais + '/reportes/estatustarjetashabientesExpXLS', 'Exportar XLS');
  });

  $('#export_pdf').click(function () {
    descargarArchivo(filtro_busq, baseURL + api + isoPais + '/reportes/estatustarjetashabientesExpPDF', 'Exportar PDF');
  });

  //METODO PARA REALIZAR LA BUSQUEDA

  $('#EstatusLotes-btnBuscar').click(function () {
    evBuscar = true;
    buscarStatusTarjetasHambientes('1');
  });

  function buscarStatusTarjetasHambientes(paginaActual) {
    var $consulta;
    filtro_busq.nombreEmpresa = $('option:selected', '#Reporte-tarjeta-hambiente').attr('acnomcia');
    filtro_busq.lotes_producto = $('#EstatusLotes-producto').val();
    filtro_busq.acrif = $('option:selected', '#Reporte-tarjeta-hambiente').attr('acrif');

    filtro_busq.paginaActual = paginaActual;
    EstatusLotes_producto = $('option:selected', '#EstatusLotes-producto').text();
    nombreProducto = EstatusLotes_producto.split('/');
    filtro_busq.nombreProducto = nombreProducto[0].trim();

    if (validar_filtro_busqueda('lotes-2')) {
      $('#cargando').fadeIn('slow');
      $('#EstatusLotes-btnBuscar').hide();
      $('#div_tablaDetalle').fadeOut('fast');
      var ceo_cook = decodeURIComponent(
        document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
      );

      var dataRequest = JSON.stringify({
        nombreEmpresa: filtro_busq.nombreEmpresa,
        lotes_producto: filtro_busq.lotes_producto,
        acrif: filtro_busq.acrif,
        paginaActual: filtro_busq.paginaActual,
        nombreProducto: filtro_busq.nombreProducto,
      });
      dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
      /******* SE REALIZA LA INVOCACION AJAX *******/
      $consulta = $.post(baseURL + api + isoPais + '/reportes/estatusTarjetashabientes', {
        request: dataRequest,
        ceo_name: ceo_cook,
        plot: btoa(ceo_cook),
      });
      /******* DE SER EXITOSA LA COMUNICACION CON EL SERVICIO SE EJECUTA EL SIGUIENTE METODO "DONE" *******/
      $consulta.done(function (response) {
        data = JSON.parse(
          CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8)
        );
        $('#mensaje').remove();
        $('#cargando').fadeOut('slow');
        $('#EstatusLotes-btnBuscar').show();
        $('#div_tablaDetalle').fadeIn('slow');

        var tbody = $('#tbody-datos-general');
        if (evBuscar) {
          tbody.empty();
        }

        contenedor = $('#div_tablaDetalle');
        var tr;
        var td;

        /****** DE TRAER RESULTADOS LA CONSULTA SE GENERA LA TABLA CON LA DATA... *******/
        /****** DE LO CONTRARIO SE GENERA UN MENSAJE "No existe Data relacionada con su filtro de busqueda" ******/

        if ($('.tbody-statuslotes').hasClass('dataTable')) {
          $('.tbody-statuslotes').dataTable().fnClearTable();
          $('.tbody-statuslotes').dataTable().fnDestroy();
        }
        if (data.rc == '0') {
          $('#view-results').attr('style', 'display:block');
          $('#tabla-estatus-lotes').fadeIn('fast');
          $('#contend-pagination').show();
          $.each(data.listadoTarjetaHabientes, function (posLista, itemLista) {
            tr = $(document.createElement('tr')).appendTo(tbody);
            td = $(document.createElement('td')).appendTo(tr);
            td.attr('style', 'max-width: 319px !important; min-width: 319px !important;');
            td.html(itemLista.idExtPer);

            td = $(document.createElement('td')).appendTo(tr);
            td.attr('style', 'max-width: 319px !important; min-width: 319px !important;');
            td.html(itemLista.Tarjetahabiente);
          });
          //$('#tabla-estatus-lotes tbody tr:even').addClass('even');

          paginacion(data.totalPaginas, data.paginaActual);
        } else {
          if (data.rc == '-29') {
            alert(data.mensaje);
            $(location).attr('href', baseURL + isoPais + '/login');
          } else {
            $('#mensaje').remove();
            //var contenedor=$("#div_tablaDetalle");
            $('#tabla-estatus-lotes').fadeOut('fast');
            $('#view-results').attr('style', 'display:none');
            $('#contend-pagination').hide();
            var div = $(document.createElement('div')).appendTo(contenedor);
            div.attr('id', 'mensaje');
            div.attr('style', 'background-color:rgb(252,199,199); margin-top:45px;');
            var p = $(document.createElement('p')).appendTo(div);
            p.html('No posee registros');
            p.attr('style', 'text-align:center;padding:10px;font-size:14px');
          }
        }
      });
    }
  } //Fin Funcion  buscarStatusTarjetasHambientes

  function validar_filtro_busqueda(div) {
    var valido = true;
    //VALIDA INPUT:TEXT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
    $.each($('#' + div + " input[type='text'].required"), function (posItem, item) {
      var $elem = $(item);
      if ($elem.val() == '') {
        valido = false;
        $elem.attr('style', 'border-color:red');
      } else {
        $elem.attr('style', '');
      }
    });

    //VALIDA SELECT QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
    $.each($('#' + div + ' select.required'), function (posItem, item) {
      var $elem = $(item);
      if ($elem.val() == '') {
        valido = false;
        $elem.attr('style', 'border-color:red');
      } else {
        $elem.attr('style', '');
      }
    });

    //VALIDA INPUT:CHECKBOX  y INPUT:RADIO QUE SEAN REQUERIDOS NO SE ENCUENTREN VACIOS
    var check = $('#' + div + " input[type='checkbox'].required:checked").length;
    var radio = $('#' + div + " input[type='radio'].required:checked ").length;
    if (check == '' && $('#' + div + " input[type='checkbox'].required").length != '') {
      valido = false;
      $('#' + div + " input[type='checkbox'].required")
        .next()
        .attr('style', 'color:red');
    } else {
      $('#' + div + " input[type='checkbox'].required")
        .next()
        .attr('style', '');
    }

    if (radio == '' && $('#' + div + " input[type='radio'].required").length != '') {
      valido = false;
      $('#' + div + " input[type='radio'].required")
        .next()
        .attr('style', 'color:red');
    } else {
      $('#' + div + " input[type='radio'].required")
        .next()
        .attr('style', '');
    }

    if (!valido) {
      $('.div_tabla_detalle').fadeOut('fast');
      $('#mensajeError').html('Por favor, rellene los campos marcados en color rojo.');
      $('#mensajeError').fadeIn('fast');
    } else {
      $('#mensajeError').fadeOut('fast');
    }

    return valido;
  }

  function CalculateDateDiff(dateFrom, dateTo) {
    var dateT = new Date(
      parseInt(dateTo.split('/')[2]),
      parseInt(dateTo.split('/')[1]),
      parseInt(dateTo.split('/')[0])
    );
    var dateF = new Date(
      parseInt(dateFrom.split('/')[2]),
      parseInt(dateFrom.split('/')[1]),
      parseInt(dateFrom.split('/')[0])
    );
    var difference = dateT - dateF;

    var years = Math.floor(difference / (1000 * 60 * 60 * 24 * 365));
    difference -= years * (1000 * 60 * 60 * 24 * 365);
    var months = Math.floor(difference / (1000 * 60 * 60 * 24 * 30.4375));

    var dif = '';
    if (years > 0) dif = years + ' años ';

    if (months > 0) {
      if (years > 0) dif += ' y ';
      dif += months + ' meses';
    }

    if (years > 0) {
      $('#mensajeError').html('El rango de fecha no debe ser mayor a 3 meses');
      $('#mensajeError').fadeIn('fast');
      return true;
    }
    if (months < 3) {
      $('#mensajeError').fadeOut('fast');
      return false;
    } else {
      $('#mensajeError').html('El rango de fecha no debe ser mayor a 3 meses');
      $('#mensajeError').fadeIn('fast');
    }

    return true;
  }

  function downloadme(x) {
    myTempWindow = window.open(x, '', 'left=1000,screenX=1000');
    myTempWindow.document.execCommand('SaveAs', 'null', 'download.pdf');
  }

  function paginacion(total, inicial) {
    var texHtml = '';
    $('#list_pagination').html('');
    for (var i = 1; i <= total; ++i) {
      texHtml +=
        '<span class="cajonNum"><a href="javascript:" id="page_' + i + '" class="num-pagina">' + i + '</a></span>';
    }
    $('#list_pagination').html(texHtml);

    $('#list_pagination').scrollLeft(0);

    ancho = $('#page_' + inicial).position().left - 4;

    $('#list_pagination').animate(
      {
        scrollLeft: ancho,
      },
      200
    );

    $('.num-pagina').css('text-decoration', 'none');
    $('#page_' + inicial).css('text-decoration', 'underline');

    $('.num-pagina').unbind('click');
    $('.num-pagina').click(function () {
      var id = this.id;
      id = id.split('_');
      buscarStatusTarjetasHambientes(id[1]);
    });

    $('#anterior-1').unbind('mouseover');
    $('#anterior-1').unbind('mouseout');
    $('#anterior-1')
      .mouseover(function () {
        scroll_interval = setInterval(function () {
          if ($('#list_pagination').scrollLeft() > 0) {
            ancho = $('#list_pagination').scrollLeft() - 1;
            $('#list_pagination').scrollLeft(ancho);
          }
        }, 20);
      })
      .mouseout(function () {
        clearInterval(scroll_interval);
      });
    $('#anterior-2').unbind('mouseover');
    $('#anterior-2').unbind('mouseout');
    $('#anterior-2')
      .mouseover(function () {
        scroll_interval = setInterval(function () {
          if ($('#list_pagination').scrollLeft() > 0) {
            ancho = $('#list_pagination').scrollLeft() - 1;
            $('#list_pagination').scrollLeft(ancho);
          }
        }, 1);
      })
      .mouseout(function () {
        clearInterval(scroll_interval);
      });
    $('#siguiente-1').unbind('mouseover');
    $('#siguiente-1').unbind('mouseout');
    $('#siguiente-1')
      .mouseover(function () {
        scroll_interval = setInterval(function () {
          ancho = $('#list_pagination').scrollLeft() + 1;
          $('#list_pagination').scrollLeft(ancho);
        }, 20);
      })
      .mouseout(function () {
        clearInterval(scroll_interval);
      });
    $('#siguiente-2').unbind('mouseover');
    $('#siguiente-2').unbind('mouseout');
    $('#siguiente-2')
      .mouseover(function () {
        scroll_interval = setInterval(function () {
          ancho = $('#list_pagination').scrollLeft() + 1;
          $('#list_pagination').scrollLeft(ancho);
        }, 1);
      })
      .mouseout(function () {
        clearInterval(scroll_interval);
      });

    $('#anterior-22').unbind('click');
    $('#anterior-22').click(function () {
      buscarStatusTarjetasHambientes(1);
    });

    $('#siguiente-22').unbind('click');
    $('#siguiente-22').click(function () {
      buscarStatusTarjetasHambientes(total);
    });
  }
  /***********************Paginacion fin***********************/

  calendario('EstatusLotes-fecha-in');
  calendario('EstatusLotes-fecha-fin');

  function calendario(input) {
    $('#' + input).datepicker({
      defaultDate: '+1w',
      changeMonth: true,
      changeYear: true,
      numberOfMonths: 1,
      dateFormat: 'dd/mm/yy',
      maxDate: '+0D',
      onClose: function (selectedate) {
        if (input == 'EstatusLotes-fecha-in' && selectedate) {
          $('#EstatusLotes-fecha-fin').datepicker('option', 'minDate', selectedate);
        } else if (input == 'EstatusLotes-fecha-in') {
          $('#EstatusLotes-fecha-fin').datepicker('option', 'minDate', '');
        }
        if (input == 'EstatusLotes-fecha-fin' && selectedate) {
          $('#EstatusLotes-fecha-in').datepicker('option', 'maxDate', selectedate);
        } else if (input == 'EstatusLotes-fecha-fin') {
          $('#EstatusLotes-fecha-in').datepicker('option', 'maxDate', '+0D');
        }
      },
    });
  }

  function descargarArchivo(filtro_busq, url, titulo) {
    var ceo_cook = decodeURIComponent(document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1'));
    $aux = $('#cargando').dialog({
      title: titulo,
      modal: true,
      close: function () {
        $(this).dialog('close');
      },
      resizable: false,
    });

    $('form#formulario').empty();
    $('form#formulario').append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '"/>');
    $('form#formulario').append(
      '<input type="hidden" name="nombreEmpresa" value="' + filtro_busq.nombreEmpresa + '" />'
    );
    $('form#formulario').append(
      '<input type="hidden" name="nombreProducto" value="' + filtro_busq.nombreProducto + '" />'
    );
    $('form#formulario').append(
      '<input type="hidden" name="lotes_producto" value="' + filtro_busq.lotes_producto + '" />'
    );
    $('form#formulario').append('<input type="hidden" name="acrif" value="' + filtro_busq.acrif + '" />');
    $('form#formulario').attr('action', url);
    $('form#formulario').submit();
    setTimeout(function () {
      $aux.dialog('destroy');
    }, 8000);
  }

  function notificacion(titulo, mensaje) {
    var canvas = '<div>' + mensaje + '</div>';

    $(canvas).dialog({
      title: titulo,
      modal: true,
      maxWidth: 700,
      maxHeight: 300,
      resizable: false,
      close: function () {
        $(this).dialog('destroy');
      },
      buttons: {
        OK: function () {
          $(this).dialog('destroy');
        },
      },
    });
  }
});
