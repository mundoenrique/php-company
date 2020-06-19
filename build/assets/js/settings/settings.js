$(document).ready(function() {
    //vars
    var options = document.querySelectorAll(".nav-item-config");
    var i;
		$('#tlf1').attr('maxlength', 15);
		$('#tlf2').attr('maxlength', 15);
		$('#tlf3').attr('maxlength', 15);
		$('#currentEmail').attr('maxlength', 40);
    $('.slide-slow').click(function() {
        $(".section").slideToggle("slow");
        $(".help-block").text("");
        $("#currentPass").removeClass("has-error");
        $("#newPass").removeClass("has-error");
        $("#confirmPass").removeClass("has-error");
    });

    $('.btns').click(function() {
        var btnv = this.value;
        $('#' + btnv).show('slideUp').siblings().hide('slideDown');
    });
    //core
		$.each(options, function(key, val){
			$('#'+options[key].id+'View').hide();
			options[key].addEventListener('click', function(e) {
					var idName = this.id;
					$.each(options, function(key, val){
							options[key].classList.remove("active");
							$('#'+options[key].id+'View').hide();
					})
					this.classList.add("active");
					$('#'+idName+'View').fadeIn(700, 'linear');
			});
	});
});

$(function() {

    var enterpriseWidgetForm = $('#enterprise-widget-form');
    var changePassForm = $('#formChangePass');
    var changeEmailForm = $('#formChangeEmail');
    var addContactForm = $("#formAddContact");
    var changeTelephoneForm = $('#formChangeTelephones');
    var WidgetSelcet = $('#enterprise-select');
    var buttonPassword = $('#btnChangePass');
    var buttonEmail = $('#btnChangeEmail');
    var currentEmail = $('#currentEmail');
    var buttonTelephone = $('#btnChangeTelephones');
    var buttonClean = $("#btnLimpiar");
    var buttonContact = $('#btnAddContact');
    var newPass = $('#newPass');

    switch (client) {
        case 'banco-bog':
            $('#downloads').addClass('active');
            $('#downloadsView').show();
            break;
        case 'pichincha':
        case 'novo':
        case 'produbanco':
        case 'banorte':
            $('#user').addClass('active');
            $('#userView').show();
            break;
    }
    // Password Change

    newPass.on('keyup focus', function() {
        var pswd = $(this).val();
        passStrength(pswd);
    });


    buttonPassword.on('click', function(e) {
        e.preventDefault();
        changeBtn = $(this);
        form = changePassForm;
        btnText = changeBtn.text().trim();
        validateForms(form)

        if (form.valid()) {
            data = getDataForm(form)

            if (data.userType == '1') {
                data.currentPass = data.currentPass.toUpperCase();
            }

            data.currentPass = cryptoPass(data.currentPass);
            data.newPass = cryptoPass(data.newPass);
            data.confirmPass = cryptoPass(data.confirmPass);
            insertFormInput(true, form);
            changeBtn.html(loader);
            changePassword(data, btnText);
        }
    });


    // Password Change End

    // Email Change

    buttonEmail.on('click', function(e) {
        e.preventDefault();
        changeBtn = $(this);
        form = changeEmailForm;
        btnText = changeBtn.text().trim();
        validateForms(form);

        if (form.valid()) {
            data = getDataForm(form);
            data.email = currentEmail.val().toLowerCase();
            insertFormInput(true, form);
            changeBtn.html(loader);
            changeEmail(data, btnText);
        }
    });

    // Email Change End

		// Telephones Change
		$('#tlf1').keyup(function (){
			this.value = (this.value + '').replace(/[^0-9]+$/g, '');
		 });
		 $('#tlf2').keyup(function (){
			this.value = (this.value + '').replace(/[^0-9]+$/g, '');
		 });
		 $('#tlf3').keyup(function (){
			this.value = (this.value + '').replace(/[^0-9]+$/g, '');
		 });
    buttonTelephone.on('click', function(e) {
        e.preventDefault();
        changeBtn = $(this);
        form = changeTelephoneForm;
        btnText = changeBtn.text().trim();
        validateForms(form);

        if (form.valid()) {
            data = getDataForm(form);
            tlf1 = $('#tlf1').val();
            tlf2 = $('#tlf2').val();
            tlf3 = $('#tlf3').val();
            acrif = $('#acrif').val();
            var passData = {
                tlf1: tlf1,
                tlf2: tlf2,
                tlf3: tlf3,
                acrif: acrif
            };
            insertFormInput(true, form);
            changeBtn.html(loader);
            changeTelephones(passData, btnText);

        }
    });

    // Telephones Change End

    // Add Contact
    buttonClean.click(function(e) {
        addContactForm[0].reset();
    });

    buttonContact.on('click', function(e) {
        e.preventDefault();
        changeBtn = $(this);
        form = addContactForm;
        btnText = changeBtn.text().trim();
        validateForms(form)

        if (form.valid()) {
            data = getDataForm(form)
            nombres = $('#contName').val();
            apellido = $('#surname').val();
            cargo = $('#contOcupation').val();
            idExtPer = $('#contNIT').val();
            email = $('#contEmail').val();
            tipoContacto = $('#contType').val();
            username = $('#contUser').val();
            password = $('#contPass').val();
            acrif = $('#contAcrif').val();
            var passData = {
                nombres: nombres,
                apellido: apellido,
                cargo: cargo,
                idExtPer: idExtPer,
                email: email,
                tipoContacto: tipoContacto,
                acrif: acrif,
                usuario: {
                    userName: userName,
                    password: password
                }
            };
            insertFormInput(true, form);
            changeBtn.html(loader);
            addContact(passData, btnText);
        }
    });

    // Add Contact End

    // Selector empresas

    enterpriseWidgetForm.on('change', function() {
        $("#completeForm").addClass("hide");
        numpos = WidgetSelcet.find('option:selected').attr('numpos');
        nameBusine = WidgetSelcet.find('option:selected').attr('name');
        acrif = WidgetSelcet.find('option:selected').attr('acrif');
        razonSocial = WidgetSelcet.find('option:selected').attr('razonSocial');
        contacto = WidgetSelcet.find('option:selected').attr('contacto');
        ubicacion = WidgetSelcet.find('option:selected').attr('ubicacion');
        fact = WidgetSelcet.find('option:selected').attr('fact');
        tel1 = WidgetSelcet.find('option:selected').attr('tel1');
        tel2 = WidgetSelcet.find('option:selected').attr('tel2');
        tel3 = WidgetSelcet.find('option:selected').attr('tel3');

        var passData = {
            numpos: numpos,
            acrif: acrif,
            nameBusine: nameBusine,
            razonSocial: razonSocial,
            contacto: contacto,
            fact: fact,
            ubicacion: ubicacion,
            fact: fact,
            tel1: tel1,
            tel2: tel2,
            tel3: tel3,
        };
        $('.hide-out').removeClass("hide");
        selectionBussine(passData);
    });

 
    //Download file.ini
    if(countEnterprise==1){
        $('#btn-download').removeAttr("disabled");
        btnDownload();
    }else if(countEnterprise > 1 && enterpriseInf != 0){
        $('#btn-download').removeAttr("disabled");
        btnDownload();
    } else {
        $('#btn-download').removeClass("btn-link");
        $('#btn-download').attr('title',lang.GEN_BTN_INI);
    };
    
    function btnDownload(){
        $('#btn-download').on('click', function (e) {
            e.preventDefault();
            data = {};
            insertFormInput(true);
            verb = 'POST'; who = 'Settings'; where = 'getFileIni';
            $('.cover-spin').show(0);
            callNovoCore(verb, who, where, data, function (response) {
                if(response.code == 0) {
                       var File = new Int8Array(response.data.file);
                    var blob = new Blob([File], {type: "application/"+response.data.ext});
                    var url = window.URL.createObjectURL(blob);
                    $('#download-file').attr('href', url)
                    $('#download-file').attr('download',response.data.name)
                    document.getElementById('download-file').click()
                    window.URL.revokeObjectURL(url);
                    $('#download-file').attr('href', 'javascript:')
                    $('#download-file').attr('download', '')
                }
                insertFormInput(false);
                $('.cover-spin').hide();
            })
        })
    };

});

function changeEmail(passData, textBtn) {
    verb = "POST";
    who = 'Settings';
    where = 'changeEmail';
    data = passData;
    callNovoCore(verb, who, where, data, function(response) {
        dataResponse = response.data
        insertFormInput(false, form);
        changeBtn.html(textBtn)
    })
}

function changePassword1(passData, textBtn) {
    verb = "POST";
    who = 'Settings';
    where = 'ChangePassword';
    data = passData;
    callNovoCore(verb, who, where, data, function(response) {

        insertFormInput(false, form);
        changeBtn.html(textBtn)
    })
}

function addContact(passData, textBtn) {
    verb = "POST";
    who = 'Settings';
    where = 'addContact';
    data = passData;
    callNovoCore(verb, who, where, data, function(response) {
        dataResponse = response.data
        insertFormInput(false, form);
        changeBtn.html(textBtn)
    })
}

function changeTelephones(passData, textBtn) {
    verb = "POST";
    who = 'Settings';
    where = 'changeTelephones';
    data = passData;
    callNovoCore(verb, who, where, data, function(response) {
        dataResponse = response.data
        insertFormInput(false, form);
        changeBtn.html(textBtn)
    })

}

function selectionBussine(passData) {
    verb = "POST";
    who = 'Settings';
    where = 'obtainNumPosition';
    data = passData;
    callNovoCore(verb, who, where, data, function(response) {
        dataResponse = response.data
        $('.hide-out').addClass("hide");
        var info = dataResponse;
        $('#acrif').val(info.acrif);
        $('#numpos').text(info.numpos);
        $('#idNumberUser').text(info.acrif);
        $('#compNameUser').text(info.nameBusine);
        $('#busiNameUser').text(info.razonSocial);
        $('#contactUser').text(info.contacto);
        $('#addressUser').text(info.ubicacion);
        $('#TempAddressUser').text(info.fact);
        $('#tlf1').val(info.tel1);
        $('#tlf2').val(info.tel2);
        $('#tlf3').val(info.tel3);
        $("#completeForm").removeClass("hide");
    })
}