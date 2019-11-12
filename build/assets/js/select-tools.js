'use strict';
$(function () {
  /* Select
     ========================================================================== */
  $(".custom-select").each(function () {
    var classes = $(this).attr("class"),
      id = $(this).attr("id"),
      name = $(this).attr("name");
    var template = '<div class="' + classes + '">';
    template +=
      '<span class="custom-select-trigger">' +
      $(this).attr("placeholder") +
      "</span>";
    template += '<div class="custom-options">';
    $(this)
      .find("option")
      .each(function () {
        template +=
          '<span class="custom-option ' +
          $(this).attr("class") +
          '" data-value="' +
          $(this).attr("value") +
          '">' +
          $(this).html() +
          "</span>";
      });
    template += "</div></div>";

    $(this).wrap('<div class="custom-select-wrapper"></div>');
    $(this).hide();
    $(this).after(template);
  });

  // Custom Option Hover
  $(".custom-option:first-of-type").hover(
    function () {
      $(this)
        .parents(".custom-options")
        .addClass("option-hover");
    },
    function () {
      $(this)
        .parents(".custom-options")
        .removeClass("option-hover");
    }
  );

  // Custom Select Trigger
  $(".custom-select-trigger").on("click", function () {
    $("html").on("click", function () {
      $(".custom-select").removeClass("opened");
    });
    $(".custom-select-trigger").not(this)
      .parents(".custom-select")
      .removeClass("opened");
    $(this)
      .parents(".custom-select")
      .toggleClass("opened");
    event.stopPropagation();
  });

  $(".custom-select option[value='X']").each(function () {
    $(this).remove();
  });

  // Custom Option
  $(".custom-option").on("click", function () {
    $(this)
      .parents(".custom-select-wrapper")
      .find("select")
      .val($(this).data("value"));
    $(this)
      .parents(".custom-options")
      .find(".custom-option")
      .removeClass("selection");
    $(this).addClass("selection");
    $(this)
      .parents(".custom-select")
      .removeClass("opened");
    $(this)
      .parents(".custom-select")
      .find(".custom-select-trigger")
      .text($(this).text());
  });

  $(".custom-select-wrapper:not(.form-group .custom-select-wrapper)").css('width', '100%').css('width', '+=30px');

  /* Input File
     ========================================================================== */

  $('.input-file').each(function () {
    var $input = $(this),
      $label = $input.next('.js-label-file'),
      labelVal = $label.html();

    $input.on('change', function (element) {
      var fileName = '';
      if (element.target.value) fileName = element.target.value.split('\\').pop();
      fileName ? $label.addClass('has-file').find('.js-file-name').html(fileName) : $label.removeClass('has-file').html(labelVal);
    });
  });

  // $("#datepicker").datepicker();
  // $('#table_id').DataTable();

  var set = $('.card, .products');
  remove_style(set);

});

function remove_style(all) {
  var i = all.length;
  while (i--) { all[i].removeAttribute('style'); }
}