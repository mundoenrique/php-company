function passStrength(pswd) {
    var valid;

    if (pswd.length < 8 && pswd.length > 15) {
        $('.pass-config #length').removeClass('valid').addClass('invalid');
        valid = false;
    } else {
        $('.pass-config #length').removeClass('invalid').addClass('valid');
        valid = true;
    }

    if (pswd.match(/[a-z]/)) {
        $('.pass-config #letter').removeClass('invalid').addClass('valid');
        valid = !valid ? valid : true;
    } else {
        $('.pass-config #letter').removeClass('valid').addClass('invalid');
        valid = false;
    }

    if (pswd.match(/[A-Z]/)) {
        $('.pass-config #capital').removeClass('invalid').addClass('valid');
        valid = !valid ? valid : true;
    } else {
        $('.pass-config #capital').removeClass('valid').addClass('invalid');
        valid = false;
    }

    if (pswd.split(/[0-9]/).length - 1 >= 1 && pswd.split(/[0-9]/).length - 1 <= 3) {
        $('.pass-config #number').removeClass('invalid').addClass('valid');
        valid = !valid ? valid : true;
    } else {
        $('.pass-config #number').removeClass('valid').addClass('invalid');
        valid = false;
    }

    if ((pswd.length > 0) && !pswd.match(/(.)\1{2,}/)) {
        $('.pass-config #consecutivo').removeClass('invalid').addClass('valid');
        valid = !valid ? valid : true;
    } else {
        $('.pass-config #consecutivo').removeClass('valid').addClass('invalid');
        valid = false;
    }

    if (pswd.match(/([!@\*\-\?¡¿+\/.,_#])/)) {
        $('.pass-config #especial').removeClass('invalid').addClass('valid');
        valid = !valid ? valid : true;
    } else {
        $('.pass-config #especial').removeClass('valid').addClass('invalid');
        valid = false;
    }

    valid = true;

    return valid;
}

function changePassword(passData) {
    verb = "POST";
    who = 'User';
    where = 'ChangePassword';
    data = passData;

    callNovoCore(verb, who, where, data, function(response) {
        dataResponse = response.data
        switch (response.code) {
            case 0:
            case 1:
                notiSystem(response.title, response.msg, response.icon, response.data)
                break;
        }
    })

}