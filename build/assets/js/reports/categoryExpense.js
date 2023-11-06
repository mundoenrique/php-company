'use strict';
var reportsResults;
$(function () {
  $('#pre-loader').remove();
  $('.hide-out').removeClass('hide');

  $('#resultsAccount').DataTable({
    ordering: false,
    responsive: true,
    pagingType: 'full_numbers',
    language: dataTableLang,
  });
});
