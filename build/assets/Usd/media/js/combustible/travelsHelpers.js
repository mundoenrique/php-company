function calendario(input, action)
{
    $('#' + input).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        dateFormat:"dd/mm/yy",
        onSelect: function(selectDate) {
            var inputSelect = input === 'first-date' ? 'last-date' : 'first-date',
                minMax1 = input === 'first-date' ? 'minDate' : 'maxDate',
                minMax2 = input === 'last-date' ? 'minDate' : 'maxDate',
                altDate = formater(selectDate);
            switch (action) {
                case 'list':
                    if(input === 'first-date') {
                        altDate.setMonth(altDate.getMonth() + 1);
                        altDate.setDate (altDate.getDate() - 1);
                    } else {
                        altDate.setDate (altDate.getDate() + 1);
                        altDate.setMonth(altDate.getMonth() - 1);
                    }
                    $('#' + inputSelect).datepicker('option', minMax1, selectDate);
                    $('#' + inputSelect).datepicker('option', minMax2, altDate);
                    break;
                case 'add':
                    input === 'first-date' ? $('#' + inputSelect).datepicker('option', 'minDate', selectDate) : '';
                    input === 'last-date' ? $('#' + inputSelect).datepicker('option', 'maxDate', selectDate) : '';
                    break;
                default:
            }
        }
    });
    switch (action) {
        case 'list':
            $('#first-date').datepicker('option', 'minDate', '');
            $('#last-date').datepicker('option', 'minDate', '');
            break;
        case 'add':
            $('#first-date').datepicker('option', 'minDate', '0');
            $('#last-date').datepicker('option', 'minDate', '0');
            break;
    }
}

function formater(selectDate) {
    var dateArray = selectDate.split('/'),
        dateStr = dateArray[1] + '/' + dateArray[0] + '/' + dateArray[2],
        altDate = new Date(dateStr);

    return altDate;
}

function clearForm (form = '', action)
{
    var minDay = action === 'list' ? '' : '0',
        maxDay = '';
    $('#first-date').datepicker('option', 'minDate', minDay);
    $('#last-date').datepicker('option', 'minDate', minDay);
    $('#first-date').datepicker('option', 'maxDate', maxDay);
    $('#last-date').datepicker('option', 'maxDate', maxDay);
    form !== '' ? $('#' + form)[0].reset(): '';
}

function notiSystem (title, url)
{
    $('#msg-system').dialog({
        title: title,
        modal: 'true',
        width: '210px',
        draggable: false,
        rezise: false,
        open: function(event, ui) {
            $('.ui-dialog-titlebar-close', ui.dialog).hide();
        }
    });
    $('#close-info').on('click', function(e){
        e.preventDefault();
        var finish = $(this).attr('finish');
        $('#msg-info').empty();
        $('#msg-system').dialog('close');
        switch (finish) {
            case 'u':
                location.reload(true);
                break;
            case 'b':
                window.location.replace(baseURL + '/' + isoPais + url);
                break;
            case 'c':
                window.location.replace(baseURL + '/' + isoPais + '/logout');
                break;
        }

    });
}
