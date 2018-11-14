function callServiceReports(uri, method, action, scheme, data, _response_) {
	$.ajax({
		url: uri,
		type: method,
		data: {way: action, class: scheme, data:data},
		datatype: 'JSON',
	}).done(function(response) {
		_response_(response);
	});
}
