$(function(){

$("#cambioClave").on("click",function(event){

	 event.preventDefault();

		var passActual = $("#userpwdOld").val();

		var pass = $("#userpwd").val();

		var passC = $("#userpwdConfirm").val();

		var active = $("input[name='useractive']").val();

		var alerta;

	if( passActual ==='' || pass ==='' || passC ==='' ){
		alerta = "Todos los campos son obligatorios (*)";
		notificacion(alerta);
	}else if( pass!==passC  ){
		alerta = "Contraseñas no coinciden";
		notificacion(alerta);

	}else if ( pass.length > 15 ) {
		alerta = "Máximo 15 caracteres";
		notificacion(alerta);
	}else if ( !($('#length').hasClass("valid") && $('#letter').hasClass("valid") && $('#capital').hasClass("valid") && $('#number').hasClass("valid") && $('#consecutivo').hasClass("valid") && $('#especial').hasClass("valid"))){
		alerta = "Verifique el formato de la contraseña";
		notificacion(alerta);
	}else{
		if(active=='1'){
			passActual = passActual.toUpperCase();
		}
		passActual = hex_md5(passActual); $("#userpwdOld").val('');
		pass = hex_md5(pass); $("#userpwd").val('');
		passC = hex_md5(passC); $("#userpwdConfirm").val('');

		changePassNewUser(passActual,pass,passC);
	}

});

function notificacion(mensaje){

	$('<h3>'+mensaje+'</h3>').dialog({
		title: 'Cambiar contraseña',
		modal: true,
		resizable:false,
		close:function(){$(this).dialog('destroy');},
		buttons: {OK: function(){$(this).dialog('destroy');}}
	});
}


function changePassNewUser(passOld,pass,passC){

	$aux = $('#loading').dialog({title:"Cambiando contraseña", modal: true, resizable:false, close:function(){$aux.dialog('close');}});
	var ceo_cook = decodeURIComponent(
		document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
	);
	$consulta = $.post(baseURL+isoPais+"/changePassNewUserAuth", { userpwdOld: passOld, userpwd: pass, userpwdConfirm: passC, ceo_name: ceo_cook} );
	$consulta.done(function(data){
		$aux.dialog('destroy');
		data = $.parseJSON(data);
		if(data.rc == 0) {
			$("<div><h3>" + data.msg + "</h3><h5>" + data.redirect + "</h5></div>").dialog({title:"Cambiar contraseña", modal:true, resizable:false,close:function(){$(this).dialog('destroy');}});
			notificacion(data.msg);
			$(location).attr('href',baseURL+isoPais+"/dashboard");
		} else if (data.rc == '-29') {
			alert(data.msg);
			$(location).attr('href',baseURL+isoPais+"/logout");
		} else {
			notificacion(data.msg);
		}
	});

}

 $('#userpwd').keyup(function() {
        // set password variable
        var pswd = $(this).val();
        //validate the length
        if ( pswd.length < 8 || pswd.length > 15 ) {
            $('#length').removeClass('valid').addClass('invalid');
        } else {
            $('#length').removeClass('invalid').addClass('valid');
        }

        //validate letter
        if ( pswd.match(/[A-z]/) ) {
            $('#letter').removeClass('invalid').addClass('valid');
        } else {
            $('#letter').removeClass('valid').addClass('invalid');
        }

        //validate capital letter
        if ( pswd.match(/[A-Z]/) ) {
            $('#capital').removeClass('invalid').addClass('valid');
        } else {
            $('#capital').removeClass('valid').addClass('invalid');
        }

        //validate number

      if (!pswd.match(/((\w|[!@#$%])*\d(\w|[!@#$%])*\d(\w|[!@#$%])*\d(\w|[!@#\$%])*\d(\w|[!@#$%])*(\d)*)/) && pswd.match(/\d{1}/) ) {
            $('#number').removeClass('invalid').addClass('valid');
        } else {
            $('#number').removeClass('valid').addClass('invalid');
        }

      	if (! pswd.match(/(.)\1{2,}/) ) {
            $('#consecutivo').removeClass('invalid').addClass('valid');
        } else {
            $('#consecutivo').removeClass('valid').addClass('invalid');
        }

      	if ( pswd.match(/([!@\*\-\?¡¿+\/.,_#])/ )) {
            $('#especial').removeClass('invalid').addClass('valid');
        } else {
            $('#especial').removeClass('valid').addClass('invalid');
        }


    }).focus(function() {

        $("#userpwd").showBalloon({position: "right", contents: $('#psw_info')});
        $('#psw_info').show();

    }).blur(function() {

        $("#userpwd").hideBalloon({position: "right", contents: $('#psw_info')});

    });


});
