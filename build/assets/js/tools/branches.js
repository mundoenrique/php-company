'use strict';

$(function () {
  const TotalEnterpriseList = $('#idFiscalList option').length;
  let regionsList = null;
  let branchesList = null;
  let fiscalID = null;

  const resetForm = function (forms) {
    $.each(forms, function (index, form) {
      let novalidate = $(form).attr('novalidate');

      if (novalidate) {
        $(form).validate().resetForm();
        $(form).find('.help-block').text('');
      }
    });
  };

  const getBranchesList = function () {
    let dataBranchList = null;
    let formBranchList = $('#branchSettListForm');
    validateForms(formBranchList);

    if (formBranchList.valid()) {
      $('#partedSection').hide();
      $('#editAddBranchSection').hide();
      $('#loaderBranches').removeClass('hide');
      resetForm(['#branchInfoForm', '#txtBranchesForm']);
      who = 'Tools';
      where = 'getBranches';
      dataBranchList = getDataForm(formBranchList);

      callNovoCore(who, where, dataBranchList, function (response) {
        fiscalID = dataBranchList.idFiscalList;
        regionsList = response.data.regionsList;
        branchesList = response.data.branchesList;
        $('#tableBranches').dataTable().fnClearTable();

        $.each(branchesList, function (index, value) {
          branchesTable.row.add(value).draw();
        });

        getstates();
        $('#loaderBranches').addClass('hide');
        $('#partedSection').fadeIn(700, 'linear');
      });
    }
  };

  const clearCities = function () {
    $('#cityCodBranch').empty();
    $('#cityCodBranch').append('<option selected disabled>' + lang.GEN_BTN_SELECT + '</option>');
  };

  const getstates = function () {
    $('#countryCod').val(regionsList.codPais);

    $.each(regionsList.listaEstados, function (key, val) {
      $('#stateCodBranch').append('<option value="' + val['codEstado'] + '">' + val['estados'] + '</option>');
    });
  };

  const getCities = function (codState, codCity = null) {
    let indexState = 0;
    clearCities();

    $.each(regionsList.listaEstados, function (index, value) {
      if (value.codEstado === codState) {
        indexState = index;
      }
    });

    if (typeof regionsList.listaEstados === 'object') {
      $.each(regionsList.listaEstados[indexState].listaCiudad, function (index, value) {
        let selected = codCity === value['codCiudad'] ? 'selected' : '';
        $('#cityCodBranch').append(
          '<option value="' + value['codCiudad'] + '"' + selected + '>' + value['ciudad'] + '</option>'
        );
      });
    }
  };

  const sendDataBranch = function (dataBranch, btnContent) {
    who = 'Tools';
    where = dataBranch.action;
    dataBranch.idFiscal = fiscalID;
    delete dataBranch['password-user'];
    delete dataBranch.branch;

    callNovoCore(who, where, dataBranch, function (response) {
      if (response.code === 0) {
        appMessages(response.title, response.msg, response.icon, response.modalBtn);
        $('#accept').addClass('getBranches');
      }

      $('#password-user').val('');
      insertFormInput(false);
      btnContent.btn.html(btnContent.btnText);
    });
  };

  const goBrancheslist = function () {
    $('#btnSaveBranch').removeAttr('action');
    $('#editAddBranchSection').hide();
    $('#stateCodBranch').prop('selectedIndex', 0);
    clearCities();
    $('#editAddBranchText').text('');
    resetForm(['#branchInfoForm', '#branchSettListForm', '#txtBranchesForm']);
    const rows = branchesTable.page.info().recordsTotal;

    if (rows > 0) {
      $('#partedSection').fadeIn(700, 'linear');
    }
  };

  const branchesTable = $('#tableBranches').DataTable({
    autoWidth: false,
    ordering: false,
    searching: true,
    lengthChange: false,
    pagelength: 10,
    pagingType: 'full_numbers',
    language: dataTableLang,
    columnDefs: [
      {
        targets: 0,
        width: '200px',
      },
      {
        targets: 1,
        width: '200px',
      },
      {
        targets: 2,
        width: '200px',
      },
      {
        targets: 3,
        width: 'auto',
      },
      {
        targets: 4,
        width: 'auto',
      },
    ],
    columns: [
      { data: 'branchName' },
      { data: 'branchCode' },
      { data: 'contact' },
      { data: 'phone' },
      {
        data: function () {
          let options = '<button class="edit btn mx-1 px-0" title="' + lang.GEN_EDIT + '" action="update" ';
          options += 'optionsdata-toggle="tooltip">';
          options += '<i class="icon icon-edit"></i>';
          options += '</button>';

          return options;
        },
      },
    ],
  });

  if (TotalEnterpriseList === 1) {
    getBranchesList();
  }

  $('li#branch').on('click', function (e) {
    e.preventDefault();
    goBrancheslist();

    if (TotalEnterpriseList > 1) {
      $('#partedSection').hide();
      $('#idFiscalList').prop('selectedIndex', 0);
      $('#tableBranches').dataTable().fnClearTable();
    }
  });

  $('#idFiscalList').on('change', function (e) {
    e.preventDefault();
    getBranchesList();
  });

  $('#newBranchBtn').on('click', function (e) {
    e.preventDefault();
    $('#btnSaveBranch').attr('action', 'addBranche');

    $('#branchInfoForm input').each(function () {
      $(this).not('#countryCod').val('');
    });

    $('#editAddBranchText').text(lang.GEN_NEW + ' ' + lang.GEN_BRANC_OFFICE.toLowerCase());
    $('#branchCode')
      .prop('readonly', false)
      .removeClass('bg-tertiary border')
      .removeClass('ignore')
      .prop('maxlength', '3');
    $('#partedSection').hide();
    $('#editAddBranchSection').fadeIn(700, 'linear');
  });

  $('#tableBranches tbody').on('click', 'tr button[action=update]', function (e) {
    e.preventDefault();
    $('#btnSaveBranch').attr('action', 'updateBranche');
    const branchData = branchesTable.row(this.closest('tr')).data();

    $.each(branchData, function (index, value) {
      $('#' + index).val(value);
    });

    $('#branchCode').prop('readonly', true).addClass('bg-tertiary border').addClass('ignore').removeAttr('maxlength');
    $('#stateCodBranch  option[value="' + branchData.stateCod + '"]').prop('selected', true);

    getCities(branchData.stateCod, branchData.cityCod);

    $('#editAddBranchText').text(lang.GEN_EDIT + ' ' + lang.GEN_BRANC_OFFICE.toLowerCase());
    $('#partedSection').hide();
    $('#editAddBranchSection').fadeIn(700, 'linear');
  });

  $('#backBranchBtn').on('click', function (e) {
    e.preventDefault();
    goBrancheslist();
  });

  $('#stateCodBranch').on('change', function (e) {
    e.preventDefault();
    getCities(this.value);
  });

  $('#btnSaveBranch').on('click', function (e) {
    e.preventDefault();
    let dataBranch = null;
    let btnContent = null;
    let btn = $(this);
    let formBranch = $('#branchInfoForm');

    validateForms(formBranch);

    if (formBranch.valid()) {
      dataBranch = getDataForm(formBranch);
      dataBranch.pass = cryptoPass(dataBranch['password-user']);
      dataBranch.action = btn.attr('action');
      btnContent = {
        btnText: btn.text().trim(),
        btn,
      };

      btn.html(loader);
      insertFormInput(true);
      sendDataBranch(dataBranch, btnContent);
    }
  });

  $('#system-info').on('click', '.getBranches', function () {
    getBranchesList();
    goBrancheslist();
    modalDestroy(true);
  });
});
