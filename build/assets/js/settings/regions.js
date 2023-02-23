
'use strict'

function getRegion(dataResponse,row){
	var region = dataResponse.paisTo;
	var selectedState = '';
	var selectedCity = '';
	var disabled = 'disabled';
	var country = lang.GEN_BTN_SELECT;
	var codCountry = '';

	if (region){
		disabled = '';
		country = region.pais;
		codCountry = region.codPais;
	}

	$('#countryCodBranch').empty();
	$('#countryCodBranch').append('<option value="' + codCountry + '" selected ' + disabled + '>' + country + '</option>');
	$('#stateCodBranch').empty();
	$('#stateCodBranch').prepend('<option value="" selected ' + disabled + '>' + lang.GEN_BTN_SELECT + '</option>');
	$('#cityCodBranch').empty();
  $('#cityCodBranch').prepend('<option value="" selected ' + disabled + '>' + lang.GEN_BTN_SELECT + '</option>');

	$.each(region.listaEstados, function(key, val){
		if(row!=''){
			selectedState = val['codEstado'] == dataResponse.data[row].stateCod ? 'selected' : '';
		}
		$('#stateCodBranch').append("<option value='"+ val[ 'codEstado'] +"' "+selectedState+">"+ val['estados'] +"</option>");
	});

	if(row!=''){
		getCities(dataResponse.data[row].stateCod);
	}

	$('#stateCodBranch').on('change', function() {
		$('#cityCodBranch').empty();
		$('#cityCodBranch').prepend('<option value="" selected ' + disabled + '>' + lang.GEN_BTN_SELECT + '</option>');
		getCities($(this).val());
	});

	function getCities(stateCode){
		$.each(region.listaEstados, function(key, val){
			if (val['codEstado']== stateCode) {
				$.each(val['listaCiudad'], function(key2, val2) {
					if(row!=''){
					selectedCity = val2['codCiudad'] == dataResponse.data[row].cityCod ? 'selected' : '';
					}
					$('#cityCodBranch').append("<option value='"+ val2['codCiudad'] +"' "+selectedCity+">"+ val2['ciudad'] +"</option>");
				});
			}
		});
	}
}
