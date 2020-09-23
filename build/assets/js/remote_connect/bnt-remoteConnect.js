var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
var eventer = window[eventMethod];
var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
var funcionRespuesta = "";

eventer(messageEvent, function (e) {

    var key = e.message ? "message" : "data";
    var data = e[key];
    alert(data.Exitoso);
    alert(data.MensajeError);

    $("#iframeAutorizacionCanal").remove();
    $("#formAutorizacionCanal").remove();

    if (typeof window[funcionRespuesta] === 'function') {
        window[funcionRespuesta](data.Exitoso, data.MensajeError);
    }

}, false);

function AutorizacionCanales(claveAutorizacion, control, urlPOST, urlEsperando, nombreFunction) {
    $("#" + control).html('');
    var iframe = $('<iframe id="iframeAutorizacionCanal" name="iframeAutorizacionCanal" src="' + urlEsperando + '" style="width:100%; height:100%"></iframe>');
    var form = $('<form action="' + urlPOST + '" target="iframeAutorizacionCanal" method="post" id="formAutorizacionCanal"></form>');
    funcionRespuesta = nombreFunction;
    $("<input type='hidden' />")
        .attr("name", "ClaveAutorizacion")
        .attr("value", claveAutorizacion)
        .appendTo(form);

    $("#" + control).append(iframe);
    $("#" + control).append(form);
    form.submit();

}
