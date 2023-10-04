import { cryptography } from '../common/encrypt_decrypt.js';
import { uiMdalClose, uiModalMessage } from '../modal/ui_modal.js';
import { toggleDisableActions } from '../utils.js';

export const calledCoreApp = function (module, section, request, _response_ = false) {
  const uri = request.route || 'callCoreApp';
  delete request.route;
  const formData = new FormData();
  let dataRequest = {
    module: module,
    section: section,
    data: request,
  };
  dataRequest = cryptography.encrypt(dataRequest);
  formData.append('payload', dataRequest);

  if (activeSafety) {
    formData.append(novoName, novoValue);
  }

  if (request.files) {
    request.files.forEach(function (element) {
      formData.append(element.name, element.file);
    });

    delete request.files;
  }

  if (logged || userId) {
    sessionControl();
  }

  $.ajax({
    method: 'POST',
    url: baseURL + uri,
    data: formData,
    context: document.body,
    cache: false,
    contentType: false,
    processData: false,
    dataType: 'json',
  })
    .done(function (data, status, jqXHR) {
      const response = cryptography.decrypt(data.payload);
      const modalClose = response.keepModal ? false : true;
      redirectLink = response.redirectLink;
      uiMdalClose(modalClose);

      if (activeSafety) {
        novoName = response.novoName;
        novoValue = response.novoValue;
      }

      if (response.code === lang.SETT_DEFAULT_CODE) {
        uiModalMessage(response);
      }

      if (_response_) {
        _response_(response);
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      toggleDisableActions(false);
      uiMdalClose(true);
      const response = {
        code: lang.SETT_DEFAULT_CODE,
        icon: lang.SETT_ICON_DANGER,
        title: lang.GEN_SYSTEM_NAME,
        msg: lang.GEN_SYSTEM_MESSAGE,
        data: {},
        modalBtn: {
          btn1: {
            link: redirectLink,
            action: 'redirect',
          },
        },
      };

      uiModalMessage(response);

      if (_response_) {
        _response_(response);
      }
    });
};

export const calledCoreAppForm = function (request) {
  let formData = cryptography.encrypt({ request });

  let form = document.createElement('form');
  form.setAttribute('id', 'payloadForm');
  form.setAttribute('name', 'payloadForm');
  form.setAttribute('method', 'post');
  form.setAttribute('enctype', 'multipart/form-data');
  form.setAttribute('action', baseURL + 'sign-in');

  let inputData = document.createElement('input');
  inputData.setAttribute('type', 'hidden');
  inputData.setAttribute('id', 'payload');
  inputData.setAttribute('name', 'payload');
  inputData.setAttribute('value', formData);
  form.appendChild(inputData);

  if (activeSafety) {
    let inputNovo = document.createElement('input');
    inputNovo.setAttribute('type', 'hidden');
    inputNovo.setAttribute('id', novoName);
    inputNovo.setAttribute('name', novoName);
    inputNovo.setAttribute('value', novoValue);
    form.appendChild(inputNovo);
  }

  document.getElementById('calledCoreApp').appendChild(form);
  form.submit();
};
