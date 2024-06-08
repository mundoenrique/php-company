$(function () {
  dataTableLang = {
    sLengthMenu: lang.GEN_TABLE_SLENGTHMENU,
    sZeroRecords: lang.GEN_TABLE_SZERORECORDS,
    sEmptyTable: lang.GEN_TABLE_SEMPTYTABLE,
    sInfo: lang.GEN_TABLE_SINFO,
    sInfoEmpty: lang.GEN_TABLE_SINFOEMPTY,
    sInfoFiltered: lang.GEN_TABLE_SINFOFILTERED,
    sInfoPostFix: lang.GEN_TABLE_SINFOPOSTFIX,
    slengthMenu: lang.GEN_TABLE_SLENGTHMENU,
    sSearch: lang.GEN_TABLE_SSEARCH,
    sSearchPlaceholder: lang.GEN_TABLE_SSEARCHPLACEHOLDER,
    sUrl: lang.GEN_TABLE_SSEARCH,
    sInfoThousands: lang.GEN_TABLE_SINFOTHOUSANDS,
    sProcessing: lang.GEN_TABLE_SPROCESSING,
    sloadingrecords: lang.SLOADINGRECORDS,
    oPaginate: {
      sFirst: lang.GEN_TABLE_SFIRST,
      sLast: lang.GEN_TABLE_SLAST,
      sNext: lang.SETT_TABLE_SNEXT,
      sPrevious: lang.SETT_TABLE_SPREVIOUS,
    },
    oAria: {
      sSortAscending: lang.GEN_TABLE_SSORTASCENDING,
      sSortDescending: lang.GEN_TABLE_SSORTDESCENDING,
    },
    select: {
      rows: {
        _: lang.GEN_TABLE_ROWS_SELECTED,
        0: lang.GEN_TABLE_ROWS_NO_SELECTED,
        1: lang.GEN_TABLE_ROW_SELECTED,
      },
    },
  };

  currentDate = new Date();
  $.datepicker.regional['es'] = {
    changeMonth: lang.SETT_DATEPICKER_CHANGEMONTH,
    changeYear: lang.SETT_DATEPICKER_CHANGEYEAR,
    dateFormat: lang.SETT_DATEPICKER_DATEFORMAT,
    firstDay: lang.SETT_DATEPICKER_FIRSTDATE,
    isRTL: lang.SETT_DATEPICKER_ISRLT,
    maxDate: currentDate,
    minDate: lang.SETT_DATEPICKER_MINDATE,
    showAnim: lang.SETT_DATEPICKER_SHOWANIM,
    showMonthAfterYear: lang.SETT_DATEPICKER_SHOWMONTHAFTERYEAR,
    yearRange: lang.SETT_DATEPICKER_YEARRANGE + currentDate.getFullYear(),
    yearSuffix: lang.SETT_DATEPICKER_YEARSUFFIX,
    closeText: lang.GEN_DATEPICKER_CLOSETEXT,
    currentText: lang.GEN_DATEPICKER_CURRENTTEXT,
    dayNames: lang.GEN_DATEPICKER_DAYNAMES,
    dayNamesMin: lang.GEN_DATEPICKER_DAYNAMESMIN,
    dayNamesShort: lang.GEN_DATEPICKER_DAYNAMESSHORT,
    monthNames: lang.GEN_DATEPICKER_MONTHNAMES,
    monthNamesShort: lang.GEN_DATEPICKER_MONTHNAMESSHORT,
    nextText: lang.GEN_DATEPICKER_NEXTTEXT,
    prevText: lang.GEN_DATEPICKER_PREVTEXT,
    weekHeader: lang.GEN_DATEPICKER_WEEKHEADER,
  };
  $.datepicker.setDefaults($.datepicker.regional['es']);
});
