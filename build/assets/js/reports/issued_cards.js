'use strict'
var reportsResults;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var issuedCardsBtn = $('#issued-cards-btn');
	var resultsIssued = $('#resultsIssued');
	var downLoad = $('.download');

	$("#monthYear").datepicker({
		dateFormat: 'mm/yy',
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		maxDate: "+0D",
		closeText: 'Aceptar',
		yearRange: '-12:' + currentDate.getFullYear(),

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

	$("#monthYear").focus(function () {
		$(".ui-datepicker-calendar").hide();
		$("#ui-datepicker-div").position({
			my: "center top",
			at: "center bottom",
			of: $(this)
		});
	});

	issuedCardsBtn.on('click', function (e) {
		form = $('#issued-cards-form');
		var radioB = $('input:radio[name=results]:checked').val();
		form.append('<input type="hidden" id="radioButton" name="radioButton" value="' + radioB + '"></input>');
    btnText = $(this).text().trim();
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			insertFormInput(true);
			$('#div_tablaDetalle').fadeOut("fast");
			$('.issuedCards-result').addClass('hide');
			$('#pre-loade-result').removeClass('hide');
			resultsIssued.dataTable().fnClearTable();
			resultsIssued.dataTable().fnDestroy();
			verb = "POST"; who = 'Reports'; where = 'IssuedCards';
			callNovoCore(verb, who, where, data, function (response) {

				$("#view-results").attr("style", "");
				$("#div_tablaDetalle").fadeIn("slow");
				var contenedor = $("#div_tablaDetalle");
				contenedor.empty();
				var tbody;
				var thead;
				var caption;
				var tr;
				var td;
				var th;
				var div;
				var tabla;
				var a;
				var span;

				div = $(document.createElement("div")).appendTo(contenedor);

				if (response.data.issuedCardsList.length == 0) {
					$('.download-icons').addClass('hide')

					$(document).ready(function() {
						$('#resultsIssued').DataTable({
							"ordering": false,
							"responsive": true,
							"pagingType": "full_numbers",
							"language": dataTableLang,
							"searching": false,
							"paging": false,
							"info": false
						});
					});

						tabla = $(document.createElement("table")).appendTo(contenedor);
						tabla.attr("class", "cell-border h6 display responsive w-100 py-3");
						tabla.attr("id", "resultsIssued");

						thead = $(document.createElement("thead")).appendTo(tabla);
						thead.attr("id", "thead-datos-principales");
						thead.attr("class", "bg-primary secondary regular");
						tbody = $(document.createElement("tbody")).appendTo(tabla);

						tr = $(document.createElement("tr")).appendTo(thead);
						tr.attr("id", "datos-principales");
						th = $(document.createElement("th")).appendTo(tr);
						th.html(lang.GEN_PRODUCT);
						th = $(document.createElement("th")).appendTo(tr);
						th.html(lang.GEN_TABLE_EMISSION);
						th = $(document.createElement("th")).appendTo(tr);
						th.html(lang.GEN_TABLE_REP_TARJETA);
						th = $(document.createElement("th")).appendTo(tr);
						th.html(lang.GEN_TABLE_REP_CLAVE);
						th = $(document.createElement("th")).appendTo(tr);
						th.html(lang.GEN_TABLE_TOTAL);
				} else {
					$('.download-icons').removeClass('hide');

					$.each(response.data.issuedCardsList[0].lista, function (index, value) {

						$(document).ready(function() {
							$('#resultsIssued' + index).DataTable({ "bPaginate": false, "bFilter": false, "bInfo": false });
						});

						if(index > 0) {
							div = $(document.createElement("div")).appendTo(contenedor);
							div.attr("id", "top-batchs");
						}

						tabla = $(document.createElement("table")).appendTo(contenedor);
						tabla.attr("class", "cell-border h6 display responsive w-100");
						tabla.attr("id", "resultsIssued" + index);

						thead = $(document.createElement("thead")).appendTo(tabla);
						thead.attr("id", "thead-datos-principales");
						thead.attr("class", "bg-primary secondary regular");
						tbody = $(document.createElement("tbody")).appendTo(tabla);

						tr = $(document.createElement("tr")).appendTo(thead);
						tr.attr("id", "datos-principales");
						th = $(document.createElement("th")).appendTo(tr);
						th.html(value.nomProducto);
						th = $(document.createElement("th")).appendTo(tr);
						th.html(lang.GEN_TABLE_EMISSION);
						th = $(document.createElement("th")).appendTo(tr);
						th.html(lang.GEN_TABLE_REP_TARJETA);
						th = $(document.createElement("th")).appendTo(tr);
						th.html(lang.GEN_TABLE_REP_CLAVE);
						th = $(document.createElement("th")).appendTo(tr);
						th.html(lang.GEN_TABLE_TOTAL);

						tr = $(document.createElement("tr")).appendTo(tbody);
						td = $(document.createElement("td")).appendTo(tr);
						td.html(lang.GEN_TABLE_PRINCIPAL);
						td = $(document.createElement("td")).appendTo(tr);
						if (value.totalEmision != 0) {
							td = $(document.createElement("a")).appendTo(td);
							td.attr("title", "emisión");
						}

						td.attr("id", index);
						td.html(value.totalEmision);
						td = $(document.createElement("td")).appendTo(tr);
						if (value.totalReposicionTarjeta != 0) {
							td = $(document.createElement("a")).appendTo(td);
						}
						td.attr("id", index);
						td.html(value.totalReposicionTarjeta);
						td = $(document.createElement("td")).appendTo(tr);
						td.html(value.totalReposicionClave);
						td = $(document.createElement("td")).appendTo(tr);
						td.html(value.totalProducto);

						tr = $(document.createElement("tr")).appendTo(tbody);
						td = $(document.createElement("td")).appendTo(tr);
						td.html(lang.GEN_TABLE_SUPLEMENTARIA);
						td = $(document.createElement("td")).appendTo(tr);
						if (value.emisionSuplementaria.totalEmision != 0) {
							td = $(document.createElement("a")).appendTo(td);
							td.attr("title", "emisión suplementaria");
						}
						td.attr("id", index);
						td.attr("class", "suplementario_emision");
						td.html(value.emisionSuplementaria.totalEmision);
						td = $(document.createElement("td")).appendTo(tr);
						if (value.emisionSuplementaria.totalReposicionTarjeta != 0) {
							td = $(document.createElement("a")).appendTo(td);
							td.attr("title", "reposición suplementaria");
						}
						td.attr("id", index);
						td.attr("class", "suplementario_reposicion");
						td.html(value.emisionSuplementaria.totalReposicionTarjeta);
						td = $(document.createElement("td")).appendTo(tr);
						td.html(value.emisionSuplementaria.totalReposicionClave);
						td = $(document.createElement("td")).appendTo(tr);
						td.html(value.emisionSuplementaria.totalProducto);

						tr = $(document.createElement("tr")).appendTo(tbody);
						td = $(document.createElement("td")).appendTo(tr);
						td.html(lang.GEN_TABLE_TOTAL);
						td.attr("style", "pr-5 text-right")
						td = $(document.createElement("td")).appendTo(tr);
						td.html(value.totalEmision);
						td = $(document.createElement("td")).appendTo(tr);
						td.html(value.totalReposicionTarjeta);
						td = $(document.createElement("td")).appendTo(tr);
						td.html(value.totalReposicionClave);
						td = $(document.createElement("td")).appendTo(tr);
						td.html(value.totalProducto)
					});
				}

				form = $('#download-issuedcards');
				form.html('')
					 $.each(data, function (index, value) {
						if (index != 'screenSize') {
							form.append('<input type="hidden" name="'+index+'" value="'+value+'">')
						}
					});

					insertFormInput(false);
          issuedCardsBtn.html(btnText);
          $('#pre-loade-result').addClass('hide')
        	$('.issuedCards-result').removeClass('hide');

			});
		}
	});

	downLoad.on('click', 'button', function (e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');
		var accodcia = $('option:selected', "#enterpriseCode").val().trim();
		var nameEnterprise = $('option:selected', "#enterpriseCode").text().trim();
		var initialDatemy = $('#monthYear').val();
		var radioB = $('input:radio[name=results]:checked').val();

		form = $('#download-issuedcards');
		form.append('<input type="hidden" id="type" name="type" value="' + action + '"></input>');
		form.append('<input type="hidden" id="who" name="who" value="DownloadFiles"></input>');
		form.append('<input type="hidden" id="where" name="where" value="IssuedCardsReport"></input>');
		form.append('<input type="hidden" id="accodcia" name="accodcia" value="' + accodcia + '"></input>');
		form.append('<input type="hidden" id="nameEnterprise" name="nameEnterprise" value="' + nameEnterprise + '"></input>');
		form.append('<input type="hidden" id="initialDatemy" name="initialDatemy" value="' + initialDatemy + '"></input>');
		form.append('<input type="hidden" id="radioButton" name="radioButton" value="' + radioB + '"></input>');

		insertFormInput(true, form);
		form.submit();
		setTimeout(function () {
			insertFormInput(false);
			$('.cover-spin').hide();
		}, lang.GEN_TIME_DOWNLOAD_FILE);
	});
});
