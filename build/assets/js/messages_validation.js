$(function () {
  (function (factory) {
    if (typeof define === 'function' && define.amd) {
      define(['jquery', '../jquery.validate'], factory);
    } else if (typeof module === 'object' && module.exports) {
      module.exports = factory(require('jquery'));
    } else {
      factory(jQuery);
    }
  })(function ($) {
    /*
     * Translated default messages for the jQuery validation plugin.
     * Locale: ES (Spanish; Español)
     */
    $.extend($.validator.messages, {
      required: lang.VALID_REQUIRED,
      remote: 'Por favor, rellena este campo.',
      email: 'Por favor, escribe una dirección de correo válida.',
      url: 'Por favor, escribe una URL válida.',
      date: 'Por favor, escribe una fecha válida.',
      dateISO: 'Por favor, escribe una fecha (ISO) válida.',
      number: 'Por favor, escribe un número válido.',
      digits: 'Por favor, escribe sólo dígitos.',
      creditcard: 'Por favor, escribe un número de tarjeta válido.',
      equalTo: 'Por favor, escribe el mismo valor de nuevo.',
      extension: 'Por favor, escribe un valor con una extensión aceptada.',
      staticlength: $.validator.format(lang.VALID_STATIC_LENGTH),
      maxlength: $.validator.format(lang.VALID_MAX_LENGTH),
      minlength: $.validator.format(lang.VALID_MIN_LENGTH),
      rangelength: $.validator.format(lang.VALID_RANGE_LENGTH),
      range: $.validator.format('Por favor, escribe un valor entre {0} y {1}.'),
      max: $.validator.format('Por favor, escribe un valor menor o igual a {0}.'),
      min: $.validator.format('Por favor, escribe un valor mayor o igual a {0}.'),
      nifES: 'Por favor, escribe un NIF válido.',
      nieES: 'Por favor, escribe un NIE válido.',
      cifES: 'Por favor, escribe un CIF válido.',
    });

    return $;
  });
});
