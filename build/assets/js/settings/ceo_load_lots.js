function format(d) {
  // `d` is the original data object for the row
  return '<table class="detailLot cell-border h6 semibold tertiary" style="width:100%">' +
    '<tbody>' +
    '<tr class="bold" style="margin-left: 0px;">' +
    '<td>Lote nro.</td>' +
    '<td>Fecha</td>' +
    '<td>Tipo</td>' +
    '<td>Cant.</td>' +
    '<td>Estatus</td>' +
    '<td>Monto Recarga</td>' +
    '<td>Monto Comisión</td>' +
    '<td>Monto total</td>' +
    '</tr>' +
    '<tr>' +
    '<td class="bold"><a href="ceo_service_order_lot_detail.php">19060909</a></td>' +
    '<td>09/06/2019</td>' +
    '<td>Recarga en línea</td>' +
    '<td>1</td>' +
    '<td>Autorizado</td>' +
    '<td>10</td>' +
    '<td>0</td>' +
    '<td>10</td>' +
    '</tr>' +
    '</tbody>' +
    '</table>'
}

$(document).ready(function () {

  //vars
  var options = document.querySelectorAll(".nav-item-config");
  var i;

  // ------------------------------------------------------- //
  // Table
  // ------------------------------------------------------ /
  var table = $('#tableAuth').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [{
        "targets": 0,
        "className": "select-checkbox",
        "checkboxes": {
          "selectRow": true
        },
      },
      // {"targets": 0, "width": "10px"},
      // {"targets": 1, "width": "80px"},
      // {"targets": 2, "width": "85px"},
      {
        "targets": 3,
        // "width": "130px",
        render: function (data, type, row) {
          return data.length > 20 ?
            data.substr(0, 20) + '…' :
            data;
        }
      },
      // {"targets": 4, "width": "100px"},
      // {"targets": 5, "width": "80px"},
      // {"targets": 6, "width": "70px"},
      // {"targets": 7, "width": "80px"},
    ],
    "table-layout": "fixed",
    "select": {
      "style": "multi",
      "info": false,
      selector: ':not(td:nth-child(-n+7))'
    },
    "language": {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "slengthMenu": "Mostrar _MENU_ registros por pagina",
      "sSearch": "",
      "sSearchPlaceholder": "Buscar...",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "sprocessing": "Procesando ...",
      "oPaginate": {
        "sFirst": "««",
        "sLast": "»»",
        "sNext": "»",
        "sPrevious": "«"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      },
      select: {
        "rows": "%d Lote seleccionado"
      }
    }
  });
  $('#tableAuth').on('click', '.toggle-all', function () {
    $(this).closest("tr").toggleClass("selected");
    if ($(this).closest("tr").hasClass("selected")) {
      table.rows().select();
    } else {
      table.rows().deselect();
    }
  });

  $('#tableFirm').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      // {"targets": 0, "width": "10px"},
      // {"targets": 1, "width": "80px"},
      // {"targets": 2, "width": "85px"},
      {
        "targets": 3,
        // "width": "130px",
        render: function (data, type, row) {
          return data.length > 20 ?
            data.substr(0, 20) + '…' :
            data;
        }
      },
      // {"targets": 4, "width": "95px"},
      // {"targets": 5, "width": "80px"},
      // {"targets": 6, "width": "70px"},
      // {"targets": 7, "width": "70px"},
    ],
    "language": {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "slengthMenu": "Mostrar _MENU_ registros por pagina",
      "sSearch": "",
      "sSearchPlaceholder": "Buscar...",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "sprocessing": "Procesando ...",
      "oPaginate": {
        "sFirst": "««",
        "sLast": "»»",
        "sNext": "»",
        "sPrevious": "«"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
  });

  $('#pendingLots').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      // {"targets": 0, "width": "110px"},
      {
        "targets": 1,
        // "width": "220px",
        render: function (data, type, row) {
          return data.length > 20 ?
            data.substr(0, 20) + '…' :
            data;
        }
      },
      // {"targets": 2, "width": "180px"},
      // {"targets": 3, "width": "170px"},
      // {"targets": 4, "width": "130px"},
    ],
    "table-layout": "fixed",
    "language": {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "slengthMenu": "Mostrar _MENU_ registros por pagina",
      "sSearch": "",
      "sSearchPlaceholder": "Buscar...",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "sprocessing": "Procesando ...",
      "oPaginate": {
        "sFirst": "««",
        "sLast": "»»",
        "sNext": "»",
        "sPrevious": "«"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
  });

  var resultServiceOrders = $('#resultServiceOrders').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      // {"targets": 0, "width": "85px"},
      // {"targets": 1, "width": "100px"},
      // {"targets": 2, "width": "60px"},
      {
        "targets": 3,
        // "width": "120px",
        render: function (data, type, row) {
          return data.length > 20 ?
            data.substr(0, 20) + '…' :
            data;
        }
      },
      // {"targets": 4, "width": "70px"},
      // {"targets": 5, "width": "110px"},
      // {"targets": 6, "width": "110px"},
    ],
    "language": {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "slengthMenu": "Mostrar _MENU_ registros por pagina",
      "sSearch": "",
      "sSearchPlaceholder": "Buscar...",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "sprocessing": "Procesando ...",
      "oPaginate": {
        "sFirst": "««",
        "sLast": "»»",
        "sNext": "»",
        "sPrevious": "«"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
  });


  $('#resultServiceOrders tbody').on('click', 'button.details-control', function () {
    var tr = $(this).closest('tr');
    var row = resultServiceOrders.row(tr);

    if (row.child.isShown()) {
      // This row is already open - close it
      row.child.hide();
      tr.removeClass('shown');
    } else {
      // Open this row
      row.child(format(row.data())).show();
      tr.addClass('shown');
    }
  });


  $('#resultsAccount').DataTable({
    "ordering": false,
    "responsive": true,
    "pagingType": "full_numbers",
    "columnDefs": [
      // {"targets": 0, "width": "80px"},
      // {"targets": 1, "width": "37px"},
      // {"targets": 2, "width": "50px"},
      // {"targets": 3, "width": "54px"},
      // {"targets": 4, "width": "60px"},
      // {"targets": 5, "width": "65px"},
      {
        "targets": 6,
        // "width": "200px",
        render: function (data, type, row) {
          return data.length > 20 ?
            data.substr(0, 20) + '…' :
            data;
        }
      },
      // {"targets": 7, "width": "46px"},
      // {"targets": 8, "width": "43px"},
    ],
    "language": {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "slengthMenu": "Mostrar _MENU_ registros por pagina",
      "sSearch": "",
      "sSearchPlaceholder": "Buscar...",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "sprocessing": "Procesando ...",
      "oPaginate": {
        "sFirst": "««",
        "sLast": "»»",
        "sNext": "»",
        "sPrevious": "«"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
  });

  $('#authLotDetail').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      // {"targets": 0, "width": "80px"},
      // {"targets": 1, "width": "37px"},
      // {"targets": 2, "width": "50px"},
      // {"targets": 3, "width": "54px"},
      // {"targets": 4, "width": "60px"},
      // {"targets": 5, "width": "65px"},
      // {"targets": 7, "width": "46px"},
      // {"targets": 8, "width": "43px"},
    ],
    "language": {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "slengthMenu": "Mostrar _MENU_ registros por pagina",
      "sSearch": "",
      "sSearchPlaceholder": "Buscar...",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "sprocessing": "Procesando ...",
      "oPaginate": {
        "sFirst": "««",
        "sLast": "»»",
        "sNext": "»",
        "sPrevious": "«"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
  });

  $('#concenAccount, .detailLot').DataTable({
    "ordering": false,
    "pagingType": "full_numbers",
    "columnDefs": [
      // {"targets": 0, "width": "80px"},
      // {"targets": 1, "width": "37px"},
      // {"targets": 2, "width": "50px"},
      // {"targets": 3, "width": "54px"},
      // {"targets": 4, "width": "60px"},
      // {"targets": 5, "width": "65px"},
      // {"targets": 7, "width": "46px"},
      // {"targets": 8, "width": "43px"},
    ],
    "language": {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "slengthMenu": "Mostrar _MENU_ registros por pagina",
      "sSearch": "",
      "sSearchPlaceholder": "Buscar...",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "sprocessing": "Procesando ...",
      "oPaginate": {
        "sFirst": "««",
        "sLast": "»»",
        "sNext": "»",
        "sPrevious": "«"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
  });

  var maxBirthdayDate = new Date();

  $.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '<Ant',
    nextText: 'Sig>',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
  };
  $.datepicker.setDefaults($.datepicker.regional['es']);

  // maxBirthdayDate.setFullYear( maxBirthdayDate.getFullYear() - 18);

  $("#datepicker_start, #datepicker_end").datepicker({
    // showOtherMonths: true,
    // selectOtherMonths: true,
    changeMonth: true,
    changeYear: true,
    // minDate: 
    maxDate: maxBirthdayDate,
    yearRange: '-10:' + maxBirthdayDate.getFullYear(),
    // yearRange: '-90:-18',
    showAnim: "slideDown",
    beforeShow: function (input, inst) {
      inst.dpDiv.removeClass("ui-datepicker-month-year");
    }
  });

  $('.month-year').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    yearRange: "-20:+0",
    maxDate: '-M',
    dateFormat: 'MM yy',
    onClose: function (dateText, inst) {
      var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
      var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
      $(this).datepicker('setDate', new Date(year, month, 1));
    },
    beforeShow: function (input, inst) {
      var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
      var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
      inst.dpDiv.addClass("ui-datepicker-month-year");
      $(this).datepicker('option', 'defaultDate', new Date(year, month, 1));
    }
  });

  // $('option').each(function () {
  //   var text = $(this).text();
  //   if (text.length > 30) {
  //     text = text.substring(0, 9) + '...';
  //     $(this).text(text);
  //   }
  // });

  $('.slide-slow').click(function () {
    $(".section").slideToggle("slow");
  });

  $('.btns').click(function () {
    var btnv = this.value;
    $('#' + btnv).show('slideUp').siblings().hide('slideDown');
  });
  //core
  for (i = 0; i < options.length; i++) {
    $(`#${options[i].id}View`).hide();
    options[i].addEventListener('click', function (e) {
      var j, idNameCapitalize, idName;
      idName = this.id;
      idNameCapitalize = idName.charAt(0).toUpperCase() + idName.slice(1);

      for (j = 0; j < options.length; j++) {
        options[j].classList.remove("active");
        $(`#${options[j].id}View`).hide();
      }
      this.classList.add("active");
      $(`#${idName}View`).fadeIn(700, 'linear');
    });
  };

  //Reports
  var optionValues = [];
  var prevOption;
  $('#reports option').each(function () {
    optionValues.push($(this).val());
  });

  $(".reports-form").delay(2000).removeClass('none');
  
  optionValues.splice(0, 2);

  for (i = 0; i < optionValues.length; i++) {
    $(`#${optionValues[i]}`).hide();
  };

  $("#reports").change(function () {
    if ($(this).val() == "customer-movements") {
      $("#search-criteria").addClass('none');
      $("#line-reports").addClass('none');
      $("#btn-download").removeClass('none');
      $("#btn-download").fadeIn(700, 'linear');;
    } else {
      $("#search-criteria").removeClass('none');
      $("#line-reports").removeClass('none');
      $("#btn-download").addClass('none');
    }
    $('#' + $(this).val()).fadeIn(700, 'linear');
    $(prevOption).hide();
    $('#' + $(this).val()).show();
    prevOption = '#' + $(this).val();
  });
});