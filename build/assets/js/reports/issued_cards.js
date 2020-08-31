'use strict'
var reportsResults;
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var issuedCardsBtn = $('#issued-cards-btn');
	var downLoad = $('.download');

	$("#monthYear").datepicker({
		dateFormat: 'mm/yy',
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		maxDate: "+0D",
		closeText: 'Aceptar',
		yearRange: '-12:' + currentDate.getFullYear(),

		onSelect: function (selectDate) {
			$(this)
				.focus()
				.blur();
		},

		onClose: function (dateText, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(year, month, 1));
		},
		beforeShow: function (input, inst) {
			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			inst.dpDiv.addClass("ui-datepicker-month-year");
			$(this).datepicker('setDate', new Date(year, month, 1));
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
			verb = "POST"; who = 'Reports'; where = 'IssuedCards';
			callNovoCore(verb, who, where, data, function (response) {
				$("#div_tablaDetalle").fadeIn("slow");
				var contenedor = $("#div_tablaDetalle");
				contenedor.empty();
				var showButtonGrap = false;

				if (response.data.issuedCardsList.length == 0) {
					$('.download-icons').addClass('hide');
					createTable();
				} else {
					$('.download-icons').removeClass('hide');
					if(response.data.tipoConsulta == 1){
						$.each(response.data.issuedCardsList[0].lista, function (index, value) {
							(!response.data.issuedCardsList[0].lista) ? $('.icon-file-excel').hide() : $('.icon-file-excel').show();
							(!response.data.issuedCardsList[0].lista) ? $('.icon-graph').hide() : $('.icon-graph').show();
							var iconG1 = $(document.createElement("div")).appendTo(contenedor);
							iconG1.attr("class", "center mx-1");

							var iconG2 = $(document.createElement("div")).appendTo(iconG1);
							iconG2.attr("class", "flex");

							var iconG3 = $(document.createElement("div")).appendTo(iconG2);
							iconG3.attr("class", "flex mr-2 pt-3 flex-auto justify-end items-center download");

							var iconG4 = $(document.createElement("div")).appendTo(iconG3);
							iconG4.attr("class", "download-icons");

							if (index == 0) {
								var buttonExcel = $(document.createElement("button")).appendTo(iconG4);
								buttonExcel.attr("class", "btn px-1 big-modal");
								buttonExcel.attr("title", lang.GEN_BTN_DOWN_XLS);
								buttonExcel.attr("data-toggle", "tooltip");

								var iconButton = $(document.createElement("i")).appendTo(buttonExcel);
								iconButton.attr("class", "icon icon-file-excel");
								iconButton.attr("aria-hidden", "true");
							}

							if (showButtonGrap) {
								var buttonGraph = $(document.createElement("button")).appendTo(iconG4);
								buttonGraph.attr("class", "btn px-1 big-modal");
								buttonGraph.attr("title", lang.GEN_BTN_SEE_GRAPH);
								buttonGraph.attr("data-toggle", "tooltip");

								var iconButton = $(document.createElement("i")).appendTo(buttonGraph);
								iconButton.attr("class", "icon icon-graph");
								iconButton.attr("aria-hidden", "true");
							}

							var	tabla = $(document.createElement("table")).appendTo(contenedor);
							tabla.attr("class", "cell-border h6 display responsive w-100 my-5");
							tabla.attr("id", 'resultsIssued'+ index);

							var	thead = $(document.createElement("thead")).appendTo(tabla);
							thead.attr("class", "bg-primary secondary regular");

							var table = $('#resultsIssued'+ index).DataTable({
								"ordering": false,
								"responsive": true,
								"pagingType": "full_numbers",
								"language": dataTableLang,
								"searching": false,
								"paging": false,
								"info": false,
								columns: [
									{ title: value.nomProducto },
									{ title: lang.GEN_TABLE_EMISSION },
									{ title: lang.GEN_TABLE_REP_TARJETA },
									{ title: lang.GEN_TABLE_REP_CLAVE },
									{ title: lang.GEN_TABLE_TOTAL }
								]
							});
							table.row.add([
								lang.GEN_TABLE_PRINCIPAL,
								value.totalEmision,
								value.totalReposicionTarjeta,
								value.totalReposicionClave,
								value.totalProducto
							]).draw();
							table.row.add([
								lang.GEN_TABLE_SUPLEMENTARIA,
								value.emisionSuplementaria.totalEmision,
								value.emisionSuplementaria.totalReposicionTarjeta,
								value.emisionSuplementaria.totalReposicionClave,
								value.emisionSuplementaria.totalProducto
							]).draw();
							table.row.add([
								lang.GEN_TABLE_TOTAL,
								value.totalEmision,
								value.totalReposicionTarjeta,
								value.totalReposicionClave,
								value.totalProducto
							]).draw()
						});
					}else if(response.data.tipoConsulta == 0){
						var iconG1 = $(document.createElement("div")).appendTo(contenedor);
							iconG1.attr("class", "center mx-1");

							var iconG2 = $(document.createElement("div")).appendTo(iconG1);
							iconG2.attr("class", "flex");

							var iconG3 = $(document.createElement("div")).appendTo(iconG2);
							iconG3.attr("class", "flex mr-2 pt-3 flex-auto justify-end items-center download");

							var iconG4 = $(document.createElement("div")).appendTo(iconG3);
							iconG4.attr("class", "download-icons");

							var buttonExcel = $(document.createElement("button")).appendTo(iconG4);
							buttonExcel.attr("class", "btn px-1 big-modal");
							buttonExcel.attr("title", lang.GEN_BTN_DOWN_XLS);
							buttonExcel.attr("data-toggle", "tooltip");

							var iconButton = $(document.createElement("i")).appendTo(buttonExcel);
							iconButton.attr("class", "icon icon-file-excel");
							iconButton.attr("aria-hidden", "true");

							if (showButtonGrap) {
								var buttonGraph = $(document.createElement("button")).appendTo(iconG4);
								buttonGraph.attr("class", "btn px-1 big-modal");
								buttonGraph.attr("title", lang.GEN_BTN_SEE_GRAPH);
								buttonGraph.attr("data-toggle", "tooltip");

								var iconButton = $(document.createElement("i")).appendTo(buttonGraph);
								iconButton.attr("class", "icon icon-graph");
								iconButton.attr("aria-hidden", "true");
							}
						var	tabla = $(document.createElement("table")).appendTo(contenedor);
						tabla.attr("class", "cell-border h6 display responsive w-100 my-5");
						tabla.attr("id", 'resultsIssued');

						var	thead = $(document.createElement("thead")).appendTo(tabla);
						thead.attr("class", "bg-primary secondary regular");

						var table = $('#resultsIssued').DataTable({
							"ordering": false,
							"responsive": true,
							"pagingType": "full_numbers",
							"language": dataTableLang,
							"searching": false,
							"paging": false,
							"info": false,
							columns: [
								{ title: lang.GEN_PRODUCT },
								{ title: lang.GEN_TABLE_EMISSION },
								{ title: lang.GEN_TABLE_REP_TARJETA },
								{ title: lang.GEN_TABLE_REP_CLAVE },
								{ title: lang.GEN_TABLE_TOTAL }
							]
						});
						(!response.data.issuedCardsList[0].lista) ? $('.icon-file-excel').hide() : $('.icon-file-excel').show();
						(!response.data.issuedCardsList[0].lista) ? $('.icon-graph').hide() : $('.icon-graph').show();
						$.each(response.data.issuedCardsList[0].lista, function (index, value) {
							table.row.add([
									value.nomProducto,
									value.totalEmision,
									value.totalReposicionTarjeta,
									value.totalReposicionClave,
									value.totalProducto
							]).draw();
						});
					}
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

function createTable() {
	var contenedor = $("#div_tablaDetalle");
	var tabla = $(document.createElement("table")).appendTo(contenedor);
	tabla.attr("class", "cell-border h6 display responsive w-100 my-5");
	tabla.attr("id", "resultsIssued");

	var thead = $(document.createElement("thead")).appendTo(tabla);
	thead.attr("class", "bg-primary secondary regular");

	var table = $("#resultsIssued").DataTable({
		ordering: false,
		responsive: true,
		pagingType: "full_numbers",
		language: dataTableLang,
		searching: false,
		paging: false,
		info: false,
		columns: [
			{ title: lang.GEN_PRODUCT },
			{ title: lang.GEN_TABLE_EMISSION },
			{ title: lang.GEN_TABLE_REP_TARJETA },
			{ title: lang.GEN_TABLE_REP_CLAVE },
			{ title: lang.GEN_TABLE_TOTAL },
		],
	});
}
