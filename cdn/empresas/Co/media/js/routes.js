var path = window.location.href.split( '/' );
var baseURL = path[0] + "//" + path[2] + '/' + path[3] + '/';
var isoPais = path[4];
var cdn = path[2].replace('online', 'cdn');
var baseCDN = path[0]+ "//" +cdn+'/'+path[3]+'/'+path[4];
var api = "api/v1/";

function formatterDate(date) {
	var dateArray = date.split('/'),
		dateStr = dateArray[1] + '/' + dateArray[0] + '/' + dateArray[2];

	return new Date(dateStr);
}

function downloadReport() {

}
