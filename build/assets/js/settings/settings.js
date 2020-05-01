$(function() {
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

    $('#new-pass').on('keyup focus', function() {
        var pswd = $(this).val();
        passStrength(pswd);
    });
    $('#btnChangePass').on('click', function(e) {
        e.preventDefault();
        form = $('#formChangePass');
        validateForms(form);
        if (form.valid()) {
            var currentPass = cryptoPass($('#currentUserPwd').val());
            var newPass = cryptoPass($('#newUserPwd').val());
            var confirmPass = newPass;
        }

        console.log("current", currentPass);
        console.log("new", newPass);
        console.log("confirm", confirmPass);
        var passData = {
            currentPass: currentPass,
            newPass: newPass,
            confirmPass: confirmPass
        };
        changePassword(passData);
    });

    // Password Change End

    // Email Change

    $('#btnChangeEmail').on('click', function(e) {
        e.preventDefault();
        form = $('#formChangeEmail');
        validateForms(form);
        if (form.valid()) {
            var email = $('#currentEmail').val();
        }
        var passData = {
            email: email
        };
        changeEmail(passData);
    });
});

function changeEmail(passData) {
    verb = "POST";
    who = 'User';
    where = 'changeEmail';
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