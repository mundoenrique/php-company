'use strict'
var reportsResults;
currentDate = new Date();
$(function () {
	$('#pre-loader').remove();
	$('.hide-out').removeClass('hide');
	var rechargeMadeBtn = $('#recharge-made-btn');
	var resultsRecharge = $('#resultsRecharge');
	var downLoad = $('.download');

	$("#initialDatemy").datepicker({
		dateFormat: 'mm/yy',
		showButtonPanel: true,
		onSelect: function(selectDate){
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

	rechargeMadeBtn.on('click', function (e) {
		form = $('#recharge-made-form');
    btnText = $(this).text().trim()
		validateForms(form);

		if (form.valid()) {
			data = getDataForm(form);
			insertFormInput(true);
			$('.rechargeMade-result').addClass('hide');
			$('#pre-loade-result').removeClass('hide');
			resultsRecharge.dataTable().fnClearTable();
			resultsRecharge.dataTable().fnDestroy();
			who = 'Reports';
			where = 'RechargeMade';

			callNovoCore(who, where, data, function (response) {
				var table = resultsRecharge.DataTable({
					"ordering": false,
					"responsive": true,
					"pagingType": "full_numbers",
					"language": dataTableLang
				});

				if (response.data.rechargeMadeList.length == 0) {
					$('.download-icons').addClass('hide');
					$("#product").html("&nbsp;");
					$("#month1").text("");
					$("#month2").text("");
					$("#month3").text("");
					$("#total").text("");
				} else {
					$('.download-icons').removeClass('hide');
					$("#product").text(lang.GEN_PRODUCT);
					$("#month1").text(response.data.rechargeMadeList[0].monthRecharge1);
					$("#month2").text(response.data.rechargeMadeList[0].monthRecharge2);
					$("#month3").text(response.data.rechargeMadeList[0].monthRecharge3);
					$("#total").text(lang.GEN_TABLE_TOTAL);
					$.each(response.data.rechargeMadeList[0].recharge, function (index, value) {
						table.row.add([
							value.producto,
							value.montoRecarga1,
							value.montoRecarga2,
							value.montoRecarga3,
							value.totalProducto
						]).draw()
					});
					table.row.add([
						lang.GEN_TABLE_TOTALES,
						response.data.rechargeMadeList[0].totalRecharge1,
						response.data.rechargeMadeList[0].totalRecharge2,
						response.data.rechargeMadeList[0].totalRecharge3,
						response.data.rechargeMadeList[0].totalRecharge
					]).draw()
				}

				form = $('#download-rechargemade');
        	form.html('')
         		$.each(data, function (index, value) {
            	if (index != 'screenSize') {
              	form.append('<input type="hidden" name="'+index+'" value="'+value+'">')
            	}
						});

						insertFormInput(false);
            rechargeMadeBtn.html(btnText);
            $('#pre-loade-result').addClass('hide')
            $('.rechargeMade-result').removeClass('hide');
			});
		}
	});

	downLoad.on('click', 'button', function (e) {
		e.preventDefault();
		var event = $(e.currentTarget);
		var action = event.attr('title');

		var accodcia = $('option:selected', "#enterpriseCode").val().trim();
		var initialDatemy = $('#initialDatemy').val();

		form = $('#download-rechargemade');
		form.append('<input type="hidden" name="type" value="' + action + '"></input>');
		form.append('<input type="hidden" name="who" value="DownloadFiles"></input>');
		form.append('<input type="hidden" name="where" value="RechargeMadeReport"></input>');
		form.append('<input type="hidden" name="accodcia" value="' + accodcia + '"></input>');
		form.append('<input type="hidden" name="initialDatemy" value="' + initialDatemy + '"></input>');
		insertFormInput(true, form);
		form.submit();
		setTimeout(function () {
			insertFormInput(false);
			$('.cover-spin').hide();
		}, lang.SETT_TIME_DOWNLOAD_FILE);
	});
});
