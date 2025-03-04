import { baseURL, lang, logged, novo, novoName, redirect, userId } from '../common/useful_data.js';
import { uiMdalClose, uiModalMessage } from '../modal/ui_modal.js';
import { toggleDisableActions } from '../utils.js';
import { apiRequest } from './api_request.js';
import { apiResponse } from './api_response.js';

export const calledCoreApp = function (module, section, request, _response_ = false) {
  const uri = request.route || 'callCoreApp';
  delete request.route;
  const formData = new FormData();
  let dataRequest = {
    module: module,
    section: section,
    data: request,
  };
  let ajaxResponse;
  dataRequest = apiRequest(dataRequest);
  formData.append('payload', dataRequest);

  if (activeSafety) {
    formData.append(novoName, novo.value);
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
      ajaxResponse = apiResponse(data.payload);
      const modalClose = ajaxResponse.keepModal ? false : true;
      redirect.link = ajaxResponse.redirectLink;
      uiMdalClose(modalClose);

      if (activeSafety) {
        novo.value = ajaxResponse.novoValue;
      }

      if (ajaxResponse.code === lang.SETT_DEFAULT_CODE) {
        uiModalMessage(ajaxResponse);
      }

      if (_response_) {
        _response_(ajaxResponse);
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      toggleDisableActions(false);
      uiMdalClose(true);
      ajaxResponse = {
        code: lang.SETT_DEFAULT_CODE,
        icon: lang.SETT_ICON_DANGER,
        title: lang.GEN_SYSTEM_NAME,
        msg: lang.GEN_SYSTEM_MESSAGE,
        data: {},
        modalBtn: {
          btn1: {
            link: redirect.link,
            action: 'redirect',
          },
        },
      };

      uiModalMessage(ajaxResponse);

      if (_response_) {
        _response_(ajaxResponse);
      }
    });
};

export const calledCoreAppForm = function (request) {
  const uri = request.uri;
  delete request.uri;
  const data = request;
  const formData = apiRequest({ data });

  const form = document.createElement('form');
  form.setAttribute('id', 'payloadForm');
  form.setAttribute('name', 'payloadForm');
  form.setAttribute('method', 'post');
  form.setAttribute('enctype', 'multipart/form-data');
  form.setAttribute('action', baseURL + uri);

  const inputData = document.createElement('input');
  inputData.setAttribute('type', 'hidden');
  inputData.setAttribute('id', 'payload');
  inputData.setAttribute('name', 'payload');
  inputData.setAttribute('value', formData);
  form.appendChild(inputData);

  if (activeSafety) {
    const inputNovo = document.createElement('input');
    inputNovo.setAttribute('type', 'hidden');
    inputNovo.setAttribute('id', novoName);
    inputNovo.setAttribute('name', novoName);
    inputNovo.setAttribute('value', novo.value);
    form.appendChild(inputNovo);
  }

  document.getElementById('calledCoreApp').appendChild(form);
  form.submit();
};
