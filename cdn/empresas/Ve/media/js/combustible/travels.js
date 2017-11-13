var viewControl = 'count',
    firstDate = 0,
    lastDate = 0,
    lang;
$(function() {
    var typeList = new Object();
    typeList.type = 'count';

    //llamado a la función que solicita la info para de los viajes
    lisTravels(typeList);

    $('#filter-selected').on('click', '#count, #drivers, #vehicles, #statusId, #date', function(e) {
        var container = $('#filter-selected'),
            thisId = e.target.id,
            parentId = e.target.parentNode.id,
            filterList;

        $('#load').text(lang.TRAVELS_LOAD);
        $('#search-option').find('option').not('#load').remove();

        filterList = (thisId) ? thisId : parentId;

        var form = 'form-filter',
            action = 'list';
        clearForm(form, action);

        $('#' + viewControl).removeClass('selected');
        $('#' + filterList)
            .removeClass('item-hover')
            .addClass('selected')
            .mouseout(function() {
                if (filterList !== 'count') {
                    $(this).addClass('item-hover');
                }
            });
        if(filterList !== 'count') {
            $('#count').addClass('item-hover');
            $('#filter-body').removeClass('whith-form');
            $('#container-filter').show();
            $('#footer-filter').show();
        }

        if(!(viewControl === filterList && viewControl === 'count')) {
            $('#search').attr('filterList', filterList);
            prepareList(filterList);
        }

        viewControl = filterList;

    });

    $('#search').on('click', function() {

        var typeList = new Object(),
            searchForm = $('#form-filter');

        validar_campos();

        if(searchForm.valid() == true) {
            typeList.type = $('#search').attr('filterList');
            typeList.option = $('#search-option').val();
            typeList.plate = $('#plate').val();
            typeList.beginDate = $('#first-date').val();
            typeList.finalDate = $('#last-date').val();

            $('#filter-body').addClass('whith-form');
            $('#container-filter').hide();
            $('#footer-filter').hide();

            clearTable ();
            lisTravels(typeList);
        }


    });


    $('#clear-form').on('click', function() {
        var form = 'form-filter',
            action = 'list';
        clearForm(form, action);
    });

    $('#add').on('click', function (){
        var id = 0,
            func = 'register';
        addEdit(id, func);
    });

    //DataPicker
    calendario('first-date', 'list');
    calendario('last-date', 'list');

    $('#table-travels').on('click' , '#edit', function(e){
        var idTravel = $(this).attr('id-travel'),
            func = 'update';
        addEdit(idTravel, func)
    });
});
