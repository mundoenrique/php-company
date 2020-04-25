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
})