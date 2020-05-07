$(function() {

    var enterpriseWidgetForm = $('#enterprise-widget-form');
    var WidgetSelcet = $('#enterprise-select');

    switch (client) {
        case 'banco-bog':
            $('#downloads').addClass('active');
            $('#downloadsView').show();
            break;
        case 'pichincha':
        case 'novo':
        case 'banorte':
            $('#user').addClass('active');
            $('#userView').show();
            break;
    }
    // Password Change

    $('#newUserPwd').on('keyup focus', function() {
        var pswd = $(this).val();
        var validatePass = passStrength(pswd);
        if (validatePass == true) {
            $('#confirmUserPwd').on('keyup focus', function() {
                if (($(this).val()) == ($('#newUserPwd').val())) {
                    $('#currentUserPwd').on('keyup focus', function() {
                        if (($(this).val()) != '') {
                            $('#btnChangePass').removeAttr('disabled');
                        }
                    });
                }
            });
        }
    });
    $('#btnChangePass').on('click', function(e) {
        e.preventDefault();
        changeBtn = $(this);
        btnText = changeBtn.text().trim();
        form = $('#formChangePass');
        validateForms(form);

        if (form.valid()) {
            var currentPass = cryptoPass($('#currentUserPwd').val());
            var newPass = cryptoPass($('#newUserPwd').val());
            var confirmPass = newPass;
        }

        if (data.userType == '1') {
            data.currentPass = data.currentPass.toUpperCase();
        }

        var passData = {
            currentPass: currentPass,
            newPass: newPass,
            confirmPass: confirmPass
        };
        insertFormInput(true, form);
        changeBtn.html(loader);
        if (($('#currentUserPwd').val() != '') &&
            ($('#newUserPwd').val() != '') &&
            ($('#confirmUserPwd').val() != '')) {
            $("#formAddContact")[0].reset();
        } else {
            changePassword1(passData, btnText);
        }
    });

    // Password Change End

    // Email Change

    $('#btnChangeEmail').on('click', function(e) {
        e.preventDefault();
        changeBtn = $(this);
        form = $('#formChangeEmail');
        btnText = changeBtn.text().trim();
        validateForms(form)

        if (form.valid()) {
            data = getDataform(form)
            data.email = $('#currentEmail').val();
            insertFormInput(true, form);
            changeBtn.html(loader);
            changeEmail(data, btnText);
        }
    });

    // Email Change End

    // Telephones Change

    $('#btnChangeTelephones').on('click', function(e) {
        e.preventDefault();
        changeBtn = $(this);
        form = $('#formChangeTelephones');
        btnText = changeBtn.text().trim();
        validateForms(form)

        if (form.valid()) {
            data = getDataform(form)
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
    $("#btnLimpiar").click(function(e) {
        $("#formAddContact")[0].reset();
    });

    $('#btnAddContact').on('click', function(e) {
        e.preventDefault();
        changeBtn = $(this);
        form = $('#formAddContact');
        btnText = changeBtn.text().trim();
        validateForms(form)

        if (form.valid()) {
            data = getDataform(form)
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

    $('#enterprise-widget-form').on('change', '#enterprise-select', function() {
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
        $('#completeForm').removeAttr("style");
    });

    // Selector empresas End

});

function changeEmail(passData, textBtn) {
    verb = "POST";
    who = 'User';
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
    who = 'User';
    where = 'ChangePassword';
    data = passData;
    callNovoCore(verb, who, where, data, function(response) {

        insertFormInput(false, form);
        changeBtn.html(textBtn)
    })
}

function addContact(passData, textBtn) {
    verb = "POST";
    who = 'Business';
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
    who = 'Business';
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
    who = 'Business';
    where = 'obtainNumPosition';
    data = passData;
    callNovoCore(verb, who, where, data, function(response) {
        dataResponse = response.data
        $('.hide-out').addClass("hide");
        var info = dataResponse;
        $('#idNumberUser').text(info.acrif);
        $('#compNameUser').text(info.nameBusine);
        $('#busiNameUser').text(info.razonSocial);
        $('#contactUser').text(info.contacto);
        $('#addressUser').text(info.ubicacion);
        $('#TempAddressUser').text(info.fact);
        $('#tlf1').val(info.tel1);
        $('#tlf2').val(info.tel2);
        $('#tlf3').val(info.tel3);
    })
}