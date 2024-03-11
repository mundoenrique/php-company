<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info clase para obtener información relacionada con los reportes
 * @author
 *
 */
class Novo_Reports_Model extends NOVO_Model
{
  public function __construct()
  {
    parent::__construct();
    writeLog('INFO', 'Reports Model Class Initialized');
  }
  /**
   * @info Método para obtener la lista de reportes
   * @author J. Enrique Peñaloza Piñero
   * @date March 02nd, 2020
   */
  public function callWs_GetReportsList_Reports()
  {
    writeLog('INFO', 'Reports Model: ReporstList Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Lista de reportes';
    $this->dataAccessLog->operation = 'Obtener lista de reportes';

    $this->dataRequest->idOperation = 'listadoReportesCEO';
    $this->dataRequest->className = 'com.novo.objects.TOs.EmpresaTO';
    $this->dataRequest->enterpriseGroup = $this->session->enterpriseInf->enterpriseGroup;
    $this->dataRequest->rif = $this->session->enterpriseInf->idFiscal;
    $this->dataRequest->accodcia = $this->session->enterpriseInf->enterpriseCode;
    $this->dataRequest->nombre = $this->session->enterpriseInf->enterpriseName;

    $response = $this->sendToWebServices('callWs_GetReportsList');
    $headerCardsRep = [];

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $reportsList[] = (object) [
          'key' => '',
          'text' => 'Selecciona un reporte'
        ];
        $IdTypeList[] = (object) [
          'key' => '',
          'text' => 'Selecciona el tipo de identificación'
        ];

        foreach ($response->listaConfigReportesCEO as $reports) {
          $report = [];
          foreach ($reports as $key => $value) {
            switch ($key) {
              case 'idOperation':
                $report['key'] = $value;
                break;
              case 'description':
                $report['text'] = $value;
                break;
              case 'result':
                $report['type'] = $value;
                if (count($reports->listFilter) > 0 && $value === 'DOWNLOAD') {
                  $report['type'] = 'FILTER';
                }
                break;
              case 'listFilter':
                if (count($value) > 0 && $value[0]->idFilter == '3' && isset($value[0]->listDataSelection)) {
                  foreach ($value[0]->listDataSelection as $IdTypeObject) {
                    $idType = [];
                    $idType['key'] = $IdTypeObject->codData;
                    $idType['text'] = $IdTypeObject->description;
                    $IdTypeList[] = (object) $idType;
                  }
                }

                if (count($value) > 0 && $value[0]->idFilter == '4') {
                  $minDate = explode('/', $value[0]->minValue);
                  $maxDate = explode('/', $value[0]->maxValue);
                  $this->response->data->params['minYear'] = (float)$minDate[2];
                  $this->response->data->params['minMonth'] = (float)$minDate[1];
                  $this->response->data->params['minDay'] = (float)$minDate[0];
                  $this->response->data->params['maxYear'] = (float)$maxDate[2];
                  $this->response->data->params['maxMonth'] = (float)$maxDate[1];
                  $this->response->data->params['maxDay'] = (float)$maxDate[0];
                }

                if (count($value) > 0 && $value[0]->idFilter == '7') {
                  $mindateGmfReport = $value[0]->minValue;
                }
                break;
              case 'listTableHeader':
                if (count($value) > 0 && $reports->idReporte == '5') {
                  foreach ($value as $tableHeader) {
                    $headerCardsRep[] = $tableHeader->description;
                  }
                }
                break;
            }
          }

          $reportsList[] = (object) $report;
        }
        break;
    }

    if ($this->isResponseRc != 0) {
      $reportsList[] = (object) [
        'key' => '',
        'text' => lang('GEN_TRY_AGAIN')
      ];
      $IdTypeList[] = (object) [
        'key' => '',
        'text' => lang('GEN_TRY_AGAIN')
      ];
      $mindateGmfReport = '';
    }

    $this->response->data->reportsList = (object) $reportsList;
    $this->response->data->IdTypeList = (object) $IdTypeList;
    $this->response->data->mindateGmfReport = $mindateGmfReport;
    $this->response->data->headerCardsRep = $headerCardsRep;

    return $this->responseToTheView('callWs_GetReportsList');
  }
  /**
   * @info Método para obtener un reporte selecionado por el usuario
   * @author J. Enrique Peñaloza Piñero
   * @date Janury 04th, 2020
   */
  public function callWs_GetReport_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: GetReport Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';

    $this->dataRequest->idOperation = $dataRequest->operation;
    $this->dataRequest->className = 'ReporteCEOTO.class';
    $this->dataRequest->rutaArchivo = DOWNLOAD_ROUTE;

    switch ($dataRequest->operation) {
      case 'repListadoTarjetas':
        $this->cardsList($dataRequest);
        break;
      case 'repMovimientoPorEmpresa':
        $this->movementsByEnterprise($dataRequest);
        break;
      case 'repTarjeta':
        $this->cardReport($dataRequest);
        break;
      case 'repTarjetasPorPersona':
        $this->cardsPeople($dataRequest);
        break;
      case 'repMovimientoPorTarjeta':
        $this->movementsByCards($dataRequest);
        break;
      case 'repComprobantesVisaVale':
        $this->VISAproofpayment($dataRequest);
        break;
      case 'repExtractoCliente':
        $this->clientStatement($dataRequest);
        break;
      case 'repCertificadoGmf':
        $this->GMPCertificate($dataRequest);
        break;
    }

    return $this->responseToTheView('GetReport: ' . $dataRequest->operation);
  }
  /**
   * @info Método para obtener el listado de tarjetas de una empresa
   * @author J. Enrique Peñaloza Piñero
   * @date Janury 05th, 2020
   */
  private function cardsList($dataRequest)
  {
    writeLog('INFO', 'Reports Model: repListadoTarjetas Method Initialized');

    $this->dataAccessLog->function = 'Listado de tarjetas';
    $this->dataAccessLog->operation = 'Descargar archivo';

    $this->dataRequest->empresaCliente = [
      'rif' => $this->session->enterpriseInf->idFiscal,
      'accodcia' => $this->session->enterpriseInf->enterpriseCode
    ];

    $response = $this->sendToWebServices('GetReport: ' . $dataRequest->operation);

    switch ($this->isResponseRc) {
      case 0:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';

        if (file_exists(assetPath('downloads/' . $response->bean))) {
          $this->response->code = 0;
          $this->response->msg = lang('GEN_MSG_RC_0');
          $this->response->data = [
            'file' => assetUrl('downloads/' . $response->bean),
            'name' => $response->bean
          ];
        }
        break;
      case -30:
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_CARDS');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }
  /**
   * @info Método para obtener el de movimientos por empresa
   * @author J. Enrique Peñaloza Piñero
   * @date Janury 08th, 2020
   */
  private function movementsByEnterprise($dataRequest)
  {
    writeLog('INFO', 'Reports Model: movementsByEnterprise Method Initialized');

    $this->dataAccessLog->function = 'Moviminetos por empresa';
    $this->dataAccessLog->operation = 'Descargar archivo';

    $this->dataRequest->movPorEmpresa = [
      'fechaDesde' => convertDate($dataRequest->enterpriseDateBegin),
      'fechaHasta' => convertDate($dataRequest->enterpriseDateEnd)
    ];
    $this->dataRequest->empresaCliente = [
      'rif' => $this->session->enterpriseInf->idFiscal,
      'accodcia' => $this->session->enterpriseInf->enterpriseCode
    ];

    $response = $this->sendToWebServices('GetReport: ' . $dataRequest->operation);

    switch ($this->isResponseRc) {
      case 0:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';

        if (file_exists(assetPath('downloads/' . $response->bean))) {
          $this->response->code = 0;
          $this->response->msg = lang('GEN_MSG_RC_0');
          $this->response->data = [
            'file' => assetUrl('downloads/' . $response->bean),
            'name' => $response->bean
          ];
        }
        break;
      case -30:
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_MOVES_ENTERPRISE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -466:
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_DATE_RANGE_ERROR');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -467:
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_DATE_RANGE_NOT_ALLOWED');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }
  /**
   * @info Método para obtener el listado de tarjetas de una empresa
   * @author J. Enrique Peñaloza Piñero
   * @date Janury 05th, 2020
   */
  private function VISAproofpayment($dataRequest)
  {
    writeLog('INFO', 'Reports Model: VISAproofpayment Method Initialized');

    $this->dataAccessLog->function = 'Comprobante de pago VISA';
    $this->dataAccessLog->operation = 'Descargar archivo';

    $date = explode('/', $dataRequest->date);
    $this->dataRequest->movPorEmpresa = [
      'mes' => $date[0],
      'anio' => $date[1]
    ];
    $this->dataRequest->empresaCliente = [
      'rif' => $this->session->enterpriseInf->idFiscal,
      'accodcia' => $this->session->enterpriseInf->enterpriseCode
    ];

    $response = $this->sendToWebServices('GetReport: ' . $dataRequest->operation);

    switch ($this->isResponseRc) {
      case 0:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';

        if (file_exists(assetPath('downloads/' . $response->bean))) {
          $this->response->code = 0;
          $this->response->msg = lang('GEN_MSG_RC_0');
          $this->response->data = [
            'file' => assetUrl('downloads/' . $response->bean),
            'name' => $response->bean
          ];
        }
        break;
      case -30:
      case -150:
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_MOVES_ENTERPRISE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }
  /**
   * @info Método para obtener el listado de tarjetas de una empresa
   * @author J. Enrique Peñaloza Piñero
   * @date Janury 05th, 2020
   */
  private function clientStatement($dataRequest)
  {
    writeLog('INFO', 'Reports Model: clientStatement Method Initialized');

    $this->dataAccessLog->function = 'Extracto del cliente';
    $this->dataAccessLog->operation = 'Descargar archivo';

    $date = explode('/', $dataRequest->dateEx);
    $this->dataRequest->extractoEmpresa = [
      'mes' => $date[0],
      'anio' => $date[1],
      'producto' => $this->session->productInf->productPrefix
    ];
    $this->dataRequest->empresaCliente = [
      'rif' => $this->session->enterpriseInf->idFiscal,
      'accodcia' => $this->session->enterpriseInf->enterpriseCode
    ];

    $response = $this->sendToWebServices('GetReport: ' . $dataRequest->operation);

    switch ($this->isResponseRc) {
      case 0:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';

        if (file_exists(assetPath('downloads/' . $response->bean))) {
          $this->response->code = 0;
          $this->response->msg = lang('GEN_MSG_RC_0');
          $this->response->data = [
            'file' => assetUrl('downloads/' . $response->bean),
            'name' => $response->bean
          ];
        }
        break;
      case -423:
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_CLIENT_STATEMENT');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }
  /**
   * @info Método para obtener el detalle de una tarjeta
   * @author J. Enrique Peñaloza Piñero
   * @date March 10th, 2020
   */
  private function cardReport($dataRequest)
  {
    writeLog('INFO', 'Reports Model: cardReport Method Initialized');

    $this->dataAccessLog->function = 'Reporte de tarjetas';
    $this->dataAccessLog->operation = 'Lista de tarjetas';

    $this->dataRequest->className = 'TarjetaTO.class';
    $this->dataRequest->noTarjeta = $dataRequest->cardNumber;
    $this->dataRequest->rif = $this->session->enterpriseInf->idFiscal;
    $this->dataRequest->acCodCia = $this->session->enterpriseInf->enterpriseCode;

    $response = $this->sendToWebServices('GetReport: ' . $dataRequest->operation);

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $cardsReport = is_array($response) ? $response : array($response);
        $cardsMove = [];

        foreach ($response as $key => $value) {
          switch ($key) {
            case 'id_ext_per':
              $value = explode('_', $value);
              $cardsMove['idType'] = $value[0];
              $cardsMove['idNumber'] = $value[1];
              break;
            case 'NombreCliente':
              $cardsMove['userName'] = $value;
              break;
            case 'noTarjeta':
              $cardsMove['cardNumber'] = $value;
              break;
            case 'nombre_producto':
              $cardsMove['product'] = $value;
              break;
            case 'fechaAsignacion':
              $cardsMove['createDate'] = $value;
              break;
            case 'fechaExp':
              $cardsMove['Expirydate'] = $value;
              break;
            case 'estatus':
              $cardsMove['currentState'] = $value;
              break;
            case 'fechaRegistro':
              $cardsMove['activeDate'] = $value;
              break;
            case 'bloque':
              $cardsMove['reasonBlock'] = $value;
              break;
            case 'fechaBloqueo':
              $cardsMove['dateBlock'] = $value;
              break;
            case 'saldos':
              $cardsMove['currentBalance'] = $value->actual;
              break;
            case 'fechaUltimoCargue':
              $cardsMove['lastCredit'] = $value;
              break;
            case 'montoUltimoCargue':
              $cardsMove['lastAmoutn'] = $value;
              break;
            case 'gmf':
              $cardsMove['chargeGMF'] = $value;
              break;
          }
        }

        $this->response->data = $cardsMove;
        break;
      case -30:
      case -150:
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_FOUND_CARD');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }
  /**
   * @info Método para obtener el listado de tarjetas por persona
   * @author J. Enrique Peñaloza Piñero
   * @date March 10th, 2020
   */
  private function cardsPeople($dataRequest)
  {
    writeLog('INFO', 'Reports Model: cardsPeople Method Initialized');

    $this->dataAccessLog->function = 'Tarjetas por persona';
    $this->dataAccessLog->operation = 'Lista de tarjetas';

    $this->dataRequest->tarjetaHabiente = [
      'id_ext_per' => $dataRequest->idType . '_' . $dataRequest->idNumber,
      'id_ext_emp' => $this->session->enterpriseInf->idFiscal,
      'acCodCia' => $this->session->enterpriseInf->enterpriseCode
    ];

    $response = $this->sendToWebServices('GetReport: ' . $dataRequest->operation);

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $count = 0;
        $cardsPeople = [];

        foreach ($response->lista as $cardsList) {
          foreach ($cardsList as $key => $value) {
            switch ($key) {
              case 'noTarjeta':
                $cards = [
                  'key' => $count,
                  'cardMask' => maskString($value, 6, 4)
                ];
                array_push(
                  $cardsPeople,
                  $value
                );
                break;
            }
          }
          $count++;
          $cardsToView[] = $cards;
        }

        if (count($cardsToView) > 1) {
          $cards = [
            'key' => '',
            'cardMask' => 'Selecciona una tarjeta'
          ];
          array_unshift(
            $cardsToView,
            $cards
          );
        }

        $this->session->set_flashdata('cardsPeople', $cardsPeople);
        $this->response->data = $cardsToView;
        break;
      case -30:
      case -150:
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_CARDS_PEOPLE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }
  /**
   * @info Método para obtener el listado de tarjetas por persona
   * @author J. Enrique Peñaloza Piñero
   * @date March 10th, 2020
   */
  private function movementsByCards($dataRequest)
  {
    writeLog('INFO', 'Reports Model: movementsByCards Method Initialized');

    $this->dataAccessLog->function = 'Movimientos por tarjeta';
    $this->dataAccessLog->operation = 'Descargar archivo';

    if (isset($dataRequest->cardNumber)) {
      $cardNumber = $dataRequest->cardNumber;
      $numberId = '';
      $fechaInicio = convertDate($dataRequest->cardDateBegin);
      $fechaFin = convertDate($dataRequest->cardDateEnd);
    } else {
      $cardNumber = $this->session->flashdata('cardsPeople')[$dataRequest->cardNumberId];
      $numberId = $dataRequest->idType . '_' . $dataRequest->idNumber;
      $fechaInicio = convertDate($dataRequest->peopleDateBegin);
      $fechaFin = convertDate($dataRequest->peopleDateEnd);
    }

    $this->dataRequest->movTarjeta = [
      'tarjeta' => [
        'noTarjeta' => $cardNumber,
        'id_ext_per' => $numberId,
        'rif' => $this->session->enterpriseInf->idFiscal,
        'acCodCia' => $this->session->enterpriseInf->enterpriseCode
      ],
      'fechaInicio' => $fechaInicio,
      'fechaFin' => $fechaFin
    ];

    $response = $this->sendToWebServices('GetReport: ' . $dataRequest->operation);

    switch ($this->isResponseRc) {
      case 0:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';

        if (file_exists(assetPath('downloads/' . $response->bean))) {
          $this->response->code = 0;
          $this->response->msg = lang('GEN_MSG_RC_0');
          $this->response->data = [
            'file' => assetUrl('downloads/' . $response->bean),
            'name' => $response->bean
          ];
        }
        break;
      case -30:
      case -150:
        $this->session->set_flashdata('cardsPeople', $this->session->flashdata('cardsPeople'));
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_MOVES_ENTERPRISE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -466:
        $this->session->set_flashdata('cardsPeople', $this->session->flashdata('cardsPeople'));
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_DATE_RANGE_ERROR');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -467:
        $this->session->set_flashdata('cardsPeople', $this->session->flashdata('cardsPeople'));
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_DATE_RANGE_NOT_ALLOWED');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -430:
        $this->session->set_flashdata('cardsPeople', $this->session->flashdata('cardsPeople'));
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_FOUND_CARD');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }
  /**
   * @info Método para obtener el certificado GMF
   * @author J. Enrique Peñaloza Piñero
   * @date March 10th, 2020
   */
  private function GMPCertificate($dataRequest)
  {
    writeLog('INFO', 'Reports Model: GMPCertificate Method Initialized');

    $this->dataAccessLog->function = 'Obtener certificado GMF';
    $this->dataAccessLog->operation = 'Descargar archivo';

    $this->dataRequest->certificadoGmf = [
      'anio' => $dataRequest->dateG
    ];
    $this->dataRequest->empresaCliente = [
      'rif' => $this->session->enterpriseInf->idFiscal,
      'accodcia' => $this->session->enterpriseInf->enterpriseCode
    ];

    $response = $this->sendToWebServices('GetReport: ' . $dataRequest->operation);

    switch ($this->isResponseRc) {
      case 0:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';

        if (file_exists(assetPath('downloads/' . $response->bean))) {
          $this->response->code = 0;
          $this->response->msg = lang('GEN_MSG_RC_0');
          $this->response->data = [
            'file' => assetUrl('downloads/' . $response->bean),
            'name' => $response->bean
          ];
        }
        break;
      case -30:
      case -150:
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = novoLang(lang('REPORTS_NO_GMF_FOR_YEAR'), $dataRequest->dateG);
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }
  /**
   * @info Método para obtener la lista de reportes
   * @author J. Enrique Peñaloza Piñero
   * @date March 02nd, 2020
   */
  public function callWs_StatusBulk_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: StatusBulk Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Estado de lote';
    $this->dataAccessLog->operation = 'Obtener lista lotes por estado';

    $this->dataRequest->idOperation = 'buscarEstatusLotes';
    $this->dataRequest->className = 'com.novo.objects.TOs.EmpresaTO';
    $this->dataRequest->acCodCia = $dataRequest->enterpriseCode;
    $this->dataRequest->idProducto = $dataRequest->productCode;
    $this->dataRequest->dtfechorcargaIni = $dataRequest->initialDate;
    $this->dataRequest->dtfechorcargaFin = $dataRequest->finalDate;

    $response = $this->sendToWebServices('callWs_StatusBulk');
    $statusBulkList = [];


    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;

        foreach ($response->lista as $statusBulk) {
          $record = new stdClass();
          $record->bulkType = ucfirst(mb_strtolower($statusBulk->acnombre));
          $record->bulkNumber = $statusBulk->acnumlote;
          $record->bulkStatus = ucfirst(mb_strtolower($statusBulk->status));
          $record->uploadDate = $statusBulk->dtfechorcarga;
          $record->valueDate = $statusBulk->dtfechorvalor;
          $record->records = $statusBulk->ncantregs;
          $record->amount = $statusBulk->nmonto;

          array_push(
            $statusBulkList,
            $record
          );
        }

        break;
      case -150:
        $this->response->code = 0;
        break;
    }

    $this->response->data->statusBulkList = $statusBulkList;

    return $this->responseToTheView('callWs_StatusBulk');
  }
  /**
   * @info Método para obtener la lista de reposiciones
   * @param object $dataRequest
   */
  public function callWs_Replacement_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: Replacement Method Initialized');

    $type = $dataRequest->type === 'list' ? 'Listado' : 'Archivo';
    $replaceType = lang('REPORTS_TYPE')[$dataRequest->replaceType];
    $operation = $type . ' de reposición de ' . $replaceType;
    $idOperation = [
      'list' => 'buscarReposicionesDetalle',
      'xls' => 'reposicionesGeneraArchivo',
      'pdf' => 'reposicionesGeneraArchivoPdf'
    ];

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Reposiciones';
    $this->dataAccessLog->operation = $operation;

    $this->dataRequest->idOperation = $idOperation[$dataRequest->type];
    $this->dataRequest->className = 'com.novo.objects.MO.ReposicionesMO';
    $this->dataRequest->idExtEmp = $dataRequest->enterpriseCode;
    $this->dataRequest->nombreEmpresa = $dataRequest->enterpriseName ?? '';
    $this->dataRequest->idExtPer = $dataRequest->idDocument;
    $this->dataRequest->producto = $dataRequest->productCode;
    $this->dataRequest->descProducto = $dataRequest->productName ?? '';
    $this->dataRequest->tipoRep = $dataRequest->replaceType;
    $this->dataRequest->fechaIni = $dataRequest->initialDate;
    $this->dataRequest->fechaFin = $dataRequest->finalDate;
    $this->dataRequest->paginar = $dataRequest->type === 'list';
    $this->dataRequest->tamanoPagina = $dataRequest->length;
    $this->dataRequest->paginaActual = (int) ($dataRequest->start / $dataRequest->length) + 1;

    $response = $this->sendToWebServices('callWs_Replacement');

    $this->response->data->recordsTotal = 0;
    $this->response->data->recordsFiltered = 0;
    $this->response->draw = $dataRequest->draw;
    $this->response->data->data = [];
    $this->response->data->file = [];
    $this->response->data->ext = $dataRequest->type;
    $this->response->data->name = 'reposición-de-' . $replaceType . '-' . time() . '.' . $this->response->data->ext;

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        if ($dataRequest->type === 'list') {
          $this->response->data->recordsTotal = $response->totalRegistros;
          $this->response->data->recordsFiltered = $response->totalRegistros;

          foreach ($response->listadoReposiciones as $replace) {
            $replacement = new stdClass();
            $replacement->cardNumber = $replace->tarjeta ?? '';
            $replacement->cardholder = $replace->tarjetahabiente ?? '';
            $replacement->documentId = $replace->idExtPer ?? '';
            $replacement->issueDate = $replace->fechaExp ?? '';
            $replacement->bulkId = $replace->idLote ?? '';
            $replacement->servOrder = $replace->idOrden ?? '';
            $replacement->invNumber = $replace->numFactura ?? '';
            $replacement->fiscalId = $replace->rif ?? '';

            array_push($this->response->data->data, $replacement);
          }
        } else {
          $this->response->data->file = $response->bean->archivo ?? $response->archivo;
        }
        break;
      case -115:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = 'No fue posible obtener las reposiciones';
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -150:
        $this->response->code = 1;
        break;
    }

    return $this->responseToTheView('callWs_Replacement');
  }
  /**
   * @info Método para obtener la lista de saldos amanecidos
   * @author Diego Acosta García
   * @date May 21, 2020
   */
  public function callWs_closingBudgets_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: closingBudgets Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Saldos Amanecidos';
    $this->dataAccessLog->operation = 'Obtener saldos';

    $this->dataRequest->idOperation = 'saldosAmanecidos';
    $this->dataRequest->className = 'com.novo.objects.MO.SaldosAmanecidosMO';
    $this->dataRequest->idExtPer = $dataRequest->idExtPer;
    $this->dataRequest->producto =  $dataRequest->product;
    $this->dataRequest->idExtEmp =  $dataRequest->idExt;
    $this->dataRequest->tamanoPagina = 10;
    $this->dataRequest->paginar = TRUE;
    $this->dataRequest->paginaActual = (int) ($dataRequest->start / 10) + 1;


    $response = $this->sendToWebServices('callWs_closingBudgets');
    $this->response->recordsTotal = 0;
    $this->response->recordsFiltered = 0;

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $user = $response->saldo->lista;
        $this->response->data =  (array)$user;
        $this->response->access = [
          'RESPSAL' => $this->verify_access->verifyAuthorization('REPSAL'),
        ];
        $this->response->draw = (int) $dataRequest->draw;
        $this->response->recordsTotal = $response->totalSaldos;
        $this->response->recordsFiltered =  $response->totalSaldos;

        break;
      case -150:
        $this->response->code = 1;
        break;
    }

    return $this->responseToTheView('callWs_closingBudgets');
  }

  /**
   * @info Método para obtener reportes de tabla saldos al cierre
   * @author Yelsyns Lopez
   * @date May 21, 2020
   */
  public function callWs_exportToClosingBalance_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: exportTo' . $dataRequest->type . ' Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Saldos amanecidos';
    $this->dataAccessLog->operation = 'Obtener ' . $dataRequest->type . ' de tabla';

    $this->dataRequest->idOperation = 'generarClosingBalance' . $dataRequest->type;
    $this->dataRequest->className = 'com.novo.objects.MO.SaldosAmanecidosMO';
    $this->dataRequest->producto =  $dataRequest->product;
    $this->dataRequest->idExtEmp =  $dataRequest->identificationCard;
    $this->dataRequest->tamanoPagina = $dataRequest->pageLenght;
    $this->dataRequest->paginar = $dataRequest->paged;
    $this->dataRequest->paginaActual = $dataRequest->actualPage;
    $this->dataRequest->descProd =  $dataRequest->descProd;
    $this->dataRequest->nombreEmpresa =  $this->session->enterpriseInf->enterpriseName;
    $this->dataRequest->descProd =  $this->session->productInf->productName;
    $this->dataRequest->ruta = DOWNLOAD_ROUTE;

    $response = $this->sendToWebServices('callWs_exportToClosingBalance');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';

        if (file_exists(assetPath('downloads/' . $response->bean))) {
          $this->response->code = 0;
          $this->response->msg = lang('GEN_MSG_RC_0');
          $this->response->data = [
            'file' => assetUrl('downloads/' . $response->bean),
            'name' => $response->bean
          ];
        }
        break;
      default:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }

  /**
   * @info Método para obtener excel de tabla saldos al cierre
   * @author Diego Acosta García
   * @date May 21, 2020
   */
  public function callWs_exportToExcel_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: exportToExcel Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Saldos amanecidos';
    $this->dataAccessLog->operation = 'Obtener excel de tabla';

    $this->dataRequest->idOperation = 'generaArchivoXls';
    $this->dataRequest->className = 'com.novo.objects.MO.SaldosAmanecidosMO';
    $this->dataRequest->producto =  $dataRequest->product;
    $this->dataRequest->idExtEmp =  $dataRequest->identificationCard;
    $this->dataRequest->tamanoPagina = $dataRequest->pageLenght;
    $this->dataRequest->paginar = $dataRequest->paged;
    $this->dataRequest->paginaActual = $dataRequest->actualPage;
    $this->dataRequest->descProd = $dataRequest->descProd;
    $this->dataRequest->idExtPer = $dataRequest->idExtPer;

    $response = $this->sendToWebServices('callWs_exportToExcel');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $user = $response;
        $this->response->data =  (array)$user;
        break;
      default:
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }

  /**
   * @info Método para obtener resultados de cuenta maestra
   * @author Diego Acosta García
   * @date May 26, 2020
   */
  public function callWs_masterAccount_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: masterAccount Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Obtener resultados de busqueda';
    $this->dataAccessLog->operation = 'Cuenta maestra';

    $this->dataRequest->idOperation = 'buscarDepositoGarantia';
    $this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
    $this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
    $this->dataRequest->fechaIni = $dataRequest->dateStart;
    $this->dataRequest->fechaFin =  $dataRequest->dateEnd;
    $this->dataRequest->tipoNota =  $dataRequest->typeNote;
    $this->dataRequest->filtroFecha = $dataRequest->dateFilter;
    $this->dataRequest->tamanoPagina = $dataRequest->pageSize;
    $this->dataRequest->paginaActual = 1;

    $response = $this->sendToWebServices('callWs_masterAccount');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $user = $response;
        $this->response->data =  (array)$user;
        break;
      case -150:
        $this->response->code = 1;
        break;
    }

    return $this->response;
  }
  /**
   * @info Método para obtener resultados de cuenta maestra
   * @author Jennifer Cádiz / Luis Molina
   * @date May 26, 2020
   */
  public function callWs_extendedMasterAccount_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: extendedMasterAccount Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Obtener resultados de busqueda';
    $this->dataAccessLog->operation = 'Cuenta maestra extendido';

    $this->dataRequest->idOperation = 'buscarDepositoGarantia';
    $this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
    $this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
    $this->dataRequest->fechaIni = $dataRequest->initialDate;
    $this->dataRequest->fechaFin =  $dataRequest->finalDate;
    $this->dataRequest->tipoNota =  $dataRequest->typeNote;
    $this->dataRequest->filtroFecha = $dataRequest->filterDate;

    $this->dataRequest->tamanoPagina = 10;
    $this->dataRequest->paginar = TRUE;
    $this->dataRequest->paginaActual = (int) ($dataRequest->start / 10) + 1;

    $response = $this->sendToWebServices('callWs_extendedMasterAccount');

    $listMasterAccount = [];

    switch ($this->isResponseRc) {
      case 0:

        $this->response->code = 0;

        $this->response->idExtEmp = $response->idExtEmp;
        $this->response->initialDate = $response->fechaIni;
        $this->response->finalDate = $response->fechaFin;
        $this->response->filterDate = $response->filtroFecha;
        $this->response->nameEnterprise = $response->depositoGMO->lista[0]->nombreCliente;

        foreach ($response->depositoGMO->lista as $list) {
          $record = new stdClass();
          $record->fechaRegDep = $list->fechaRegDep;
          $record->idPersona = $list->idPersona;
          $record->nombrePersona = $list->nombrePersona;
          $record->descripcion = $list->descripcion;
          $record->referencia = $list->referencia;
          $record->montoDeposito = $list->montoDeposito;
          $record->tipoNota = $list->tipoNota;
          $record->saldoDisponible = $list->saldoDisponible;
          array_push(
            $listMasterAccount,
            $record
          );
        }
        break;
      case -150:
        $this->response->code = 1;
        break;
    }

    $this->response->draw = (int)$dataRequest->draw;
    $this->response->recordsTotal = $response->totalRegistros ?? '0';
    $this->response->recordsFiltered = $response->totalRegistros ?? '0';
    $this->response->data = $listMasterAccount;

    return $this->responseToTheView('callWs_searchExtendedAccountStatus');
  }

  /**
   * @info Método para obtener reporte de estatus cuenta maestra
   * @author Luis Molina
   * @date August 31, 2021
   */
  public function callWs_statusMasterAccount_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: statusMasterAccount Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'edoCuentaMaestra';
    $this->dataAccessLog->operation = 'edoCuentaMaestra';

    $this->dataRequest->opcion = 'getReporte';
    $this->dataRequest->nombreReporte = 'edoCuentaMaestra';
    $this->dataRequest->idOperation = 'genericBusiness';
    $this->dataRequest->className = 'com.novo.business.parametros.bos.Opciones';

    $this->dataRequest->idEmpresa = $dataRequest->enterpriseCode;
    $lastDayMonyh = date("t-m-Y", strtotime(str_replace('/', '-', "1/" . $dataRequest->initialDateAct)));
    $this->dataRequest->fechaFin = str_replace('-', '/', $lastDayMonyh);
    $this->dataRequest->fechaIni = "1/" . $dataRequest->initialDateAct;
    $this->dataRequest->prefix = $this->session->productInf->productPrefix;
    $this->dataRequest->ruta = DOWNLOAD_ROUTE;

    $response = $this->sendToWebServices('callWs_statusMasterAccount');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';

        if (file_exists(assetPath('downloads/' . $response->bean))) {
          $this->response->code = 0;
          $this->response->msg = lang('GEN_MSG_RC_0');
          $this->response->data = [
            'file' => assetUrl('downloads/' . $response->bean),
            'name' => $response->bean
          ];
        }
        break;
      default:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }
  /**
   * @info Método para obtener excel de tabla cuenta maestra
   * @author Diego Acosta García
   * @date May 21, 2020
   */
  public function callWs_exportToExcelMasterAccount_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: exportToExcelMasterAccount Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'cuenta maestra';
    $this->dataAccessLog->operation = 'Obtener excel de tabla cuenta maestra';

    $this->dataRequest->idOperation = 'generarDepositoGarantia';
    $this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
    $this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
    $this->dataRequest->fechaIni =  $dataRequest->dateStart;
    $this->dataRequest->fechaFin =  $dataRequest->dateEnd;
    $this->dataRequest->filtroFecha = $dataRequest->dateFilter;
    $this->dataRequest->nombreEmpresa = $dataRequest->nameEnterprise;
    $this->dataRequest->paginaActual = $dataRequest->actualPage;
    $this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
    $this->dataRequest->tamanoPagina =  $dataRequest->pageSize;

    $response = $this->sendToWebServices('callWs_exportToExcelMasterAccount');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $user = $response;
        $this->response->data =  (array)$user;
        break;
      default:
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }

  /**
   * @info Método para obtener excel de tabla cuenta maestra extendido
   * @author Jennifer Cadiz / Luis Molina
   * @date April 07, 2022
   * @update Yelsyns Lopez July 13, 2023
   */
  public function callWs_exportToTxtExtendedMasterAccount_Reports($dataRequest)
  {
    return $this->downLoadFileReportMasterAccount($dataRequest, 'Txt', 'generarDepositoGarantiaTxt');
  }

  public function callWs_exportToExcelExtendedMasterAccount_Reports($dataRequest)
  {
    return $this->downLoadFileReportMasterAccount($dataRequest, 'Excel', 'generarDepositoGarantia');
  }

  public function downLoadFileReportMasterAccount($dataRequest, $fileType, $operation)
  {
    log_message('INFO', 'NOVO Reports Model: exportTo' . $fileType . 'ExtendedMasterAccount Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'cuenta maestra';
    $this->dataAccessLog->operation = 'Obtener ' . $fileType . ' de tabla cuenta maestra extendido';

    $this->dataRequest->idOperation = $operation;
    $this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
    $this->dataRequest->idExtEmp = $dataRequest->idExtEmpXls;
    $this->dataRequest->fechaIni =  $dataRequest->initialDateXls;
    $this->dataRequest->fechaFin =  $dataRequest->finalDateXls;
    $this->dataRequest->tipoNota =  $dataRequest->typeNote;
    $this->dataRequest->filtroFecha = $dataRequest->filterDateXls;
    $this->dataRequest->nombreEmpresa = $dataRequest->nameEnterpriseXls;
    $this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
    $this->dataRequest->paginar = false;
    $this->dataRequest->ruta = DOWNLOAD_ROUTE;

    $response = $this->sendToWebServices('callWs_exportTo' . $fileType . 'ExtendedMasterAccount');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';

        if (file_exists(assetPath('downloads/' . $response->bean))) {
          $this->response->code = 0;
          $this->response->msg = lang('GEN_MSG_RC_0');
          $this->response->data = [
            'file' => assetUrl('downloads/' . $response->bean),
            'name' => $response->bean
          ];
        }
        break;
      default:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }
  /**
   * @info Método para obtener excel de tabla cuenta maestra
   * @author Diego Acosta García
   * @date May 21, 2020
   */
  public function callWs_exportToPDFMasterAccount_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: exportToPDFMasterAccount Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'cuenta maestra';
    $this->dataAccessLog->operation = 'Obtener pdf de tabla cuenta maestra';

    $this->dataRequest->idOperation = 'generarDepositoGarantiaPdf';
    $this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
    $this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
    $this->dataRequest->fechaIni =  $dataRequest->dateStart;
    $this->dataRequest->fechaFin =  $dataRequest->dateEnd;
    $this->dataRequest->filtroFecha = $dataRequest->dateFilter;
    $this->dataRequest->nombreEmpresa = $dataRequest->nameEnterprise;
    $this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;

    $response = $this->sendToWebServices('callWs_exportToPDFMasterAccount');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $user = $response;
        $this->response->data =  (array)$user;
        break;
      default:
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }
  /**
   * @info Método para obtener excel de tabla cuenta maestra consolidado
   * @author Diego Acosta García
   * @date May 21, 2020
   */
  public function callWs_exportToExcelMasterAccountConsolid_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: exportToExcelMasterAccountConsolid Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'cuenta maestra';
    $this->dataAccessLog->operation = 'Obtener excel de tabla cuenta maestra';

    $this->dataRequest->idOperation = 'generaArchivoXlsConcil';
    $this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
    $this->dataRequest->anio = $dataRequest->year;
    $this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
    $this->dataRequest->fechaIni =  $dataRequest->dateStart;
    $this->dataRequest->fechaFin =  $dataRequest->dateEnd;
    $this->dataRequest->filtroFecha = $dataRequest->dateFilter;
    $this->dataRequest->nombreEmpresa = $dataRequest->nameEnterprise;
    $this->dataRequest->paginaActual = $dataRequest->actualPage;
    $this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
    $this->dataRequest->tamanoPagina =  $dataRequest->pageSize;

    $response = $this->sendToWebServices('callWs_exportToExcelMasterAccountConsolid');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $user = $response;
        $this->response->data =  (array)$user;
        break;
      default:
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }

  /**
   * @info Método para obtener excel de tabla cuenta maestra consolidado
   * @author Diego Acosta García
   * @date May 21, 2020
   */
  public function callWs_exportToPDFMasterAccountConsolid_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: exportToPDFMasterAccountConsolid Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'cuenta maestra';
    $this->dataAccessLog->operation = 'Obtener pdf de tabla cuenta maestra consolidado';

    $this->dataRequest->idOperation = 'generaArchivoConcilPdf';
    $this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
    $this->dataRequest->anio = $dataRequest->year;
    $this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
    $this->dataRequest->fechaIni =  $dataRequest->dateStart;
    $this->dataRequest->fechaFin =  $dataRequest->dateEnd;
    $this->dataRequest->filtroFecha = $dataRequest->dateFilter;
    $this->dataRequest->nombreEmpresa = $dataRequest->nameEnterprise;
    $this->dataRequest->paginaActual = $dataRequest->actualPage;
    $this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
    $this->dataRequest->tamanoPagina =  $dataRequest->pageSize;

    $response = $this->sendToWebServices('callWs_exportToPDFMasterAccountConsolid');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $user = $response;
        $this->response->data =  (array)$user;
        break;
      default:
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }

  /**
   * Método para descargar reporte excel y pdf cuenta maestra extendido
   * @author Jennifer Cadiz / Luis Molina
   * @date April 18, 2022
   */
  public function callWs_extendedDownloadMasterAccountCon_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: extendedDownloadMasterAccountCon Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Cuenta maestra extendida';

    if ($dataRequest->downloadFormat == 'Excel') {
      $this->dataAccessLog->operation = 'Obtener excel de tabla cuenta maestra';
      $this->dataRequest->idOperation = 'generaArchivoXlsConcil';
      $ext =  '.xls';
    } else {
      $this->dataAccessLog->operation = 'Obtener pdf de tabla cuenta maestra consolidado';
      $this->dataRequest->idOperation = 'generaArchivoConcilPdf';
      $ext =  '.pdf';
    }

    $this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';

    $this->dataRequest->anio = $dataRequest->year;
    $this->dataRequest->idExtEmp = $dataRequest->idExtEmpXls;
    $this->dataRequest->fechaIni =  $dataRequest->initialDateXls;
    $this->dataRequest->fechaFin =  $dataRequest->finalDateXls;
    $this->dataRequest->filtroFecha = $dataRequest->filterDateXls;
    $this->dataRequest->nombreEmpresa = $dataRequest->nameEnterpriseXls;
    //$this->dataRequest->paginaActual = $dataRequest->actualPage;
    $this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
    //$this->dataRequest->tamanoPagina =  $dataRequest->pageSize;

    $response = $this->sendToWebServices('callWs_extendedDownloadMasterAccountCon');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $file = $response->archivo;
        $name = $response->nombre ?? 'cuentaMaestraConsolidado';

        $this->response->data->file = $file;
        $this->response->data->name = $name . $ext;
        $this->response->data->ext = $ext;
        break;
      default:
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->responseToTheView('callWs_exportReportUserActivity');
  }

  /**
   * @info Método para renderizar tabla de Tarjetahabiente
   * @author Jhonnatan Vega
   * @date September 24th, 2020
   */
  public function callWs_CardHolders_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: CardHolders Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'TarjetaHabientes';
    $this->dataAccessLog->operation = 'Lista TarjetaHabientes';

    $this->dataRequest->idOperation = 'getConsultarTarjetaHabientes';
    $this->dataRequest->className = 'com.novo.objects.MO.TarjetaHabientesMO';
    $this->dataRequest->paginaActual = 1;
    $this->dataRequest->tamanoPagina = 10;
    $this->dataRequest->paginar = FALSE;
    $this->dataRequest->rifEmpresa = $dataRequest->enterpriseCode;
    $this->dataRequest->idProducto = $dataRequest->productCode;
    $response = $this->sendToWebServices('callWS_StatusCardHolders');
    $cardHoldersList = [];

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        foreach ($response->listadoTarjetaHabientes as $cardHolders) {
          $record = new stdClass();
          $record->cardHoldersId = $cardHolders->idExtPer;
          $record->cardHoldersNum = $cardHolders->nroTarjeta ?? '';
          $record->cardHoldersName = ucwords(mb_strtolower($cardHolders->Tarjetahabiente));
          array_push(
            $cardHoldersList,
            $record
          );
        }
        break;
      case -150:
        $this->response->code = 0;
        break;
    }

    $this->response->data->cardHoldersList = $cardHoldersList;

    return $this->responseToTheView('callWS_StatusCardHolders');
  }

  /**
   * @info Método para descargar reporte de Tarjetahabiente
   * @author Jhonnatan Vega
   * @date March 23th, 2021
   */
  public function callWs_exportReportCardHolders_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: exportReportCardHolders Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Reportes Tarjetahabiente';

    if ($dataRequest->downloadFormat == 'Excel') {
      $this->dataAccessLog->operation = 'Descargar reporte en excel';
      $this->dataRequest->idOperation = 'consultarTarjetaHabientesExcel';
      $ext =  '.xls';
    } else {
      $this->dataAccessLog->operation = 'Descargar reporte en pdf';
      $this->dataRequest->idOperation = 'consultarTarjetaHabientesPDF';
      $ext =  '.pdf';
    }

    $this->dataRequest->className = 'com.novo.objects.MO.ListadoTarjetaHabientesMO';
    $this->dataRequest->paginaActual = 1;
    $this->dataRequest->tamanoPagina = 10;
    $this->dataRequest->paginar = false;
    $this->dataRequest->nombreEmpresa = $dataRequest->enterpriseName;
    $this->dataRequest->rifEmpresa = $dataRequest->enterpriseCode;
    $this->dataRequest->nombreProducto = $dataRequest->productName;
    $this->dataRequest->idProducto = $dataRequest->productCode;

    $response = $this->sendToWebServices('callWs_exportReportCardHolders');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $file = $response->archivo;
        $name = 'Tarjetahabientes';

        $this->response->data->file = $file;
        $this->response->data->name = $name . $ext;
        $this->response->data->ext = $ext;
        break;
      default:
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }


    return $this->responseToTheView('callWs_exportReportCardHolders');
  }

  public function callWs_RechargeMade_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: RechargeMade Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Reportes Recargas Realizadas';
    $this->dataAccessLog->operation = 'Recargas Realizadas';

    $this->dataRequest->idOperation = 'recargasRealizadas';
    $this->dataRequest->className = 'com.novo.objects.TOs.RecargasRealizadasTO';
    $this->dataRequest->paginaActual = 1;
    $this->dataRequest->tamanoPagina = 10;
    $this->dataRequest->fecha = '';
    $this->dataRequest->fecha1 = '';
    $this->dataRequest->fecha2 = '';
    $this->dataRequest->accodcia = $dataRequest->enterpriseCode;
    $fecha = $dataRequest->initialDatemy;
    $arreglo = explode("/", $fecha);
    $mes = $arreglo[0];
    $anio = $arreglo[1];
    $this->dataRequest->mesSeleccionado = $mes;
    $this->dataRequest->anoSeleccionado = $anio;
    $response = $this->sendToWebServices('callWs_RechargeMadeReport');
    $rechargeMadeList = [];

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $record = new stdClass();
        $record->monthRecharge1 = $response->mesRecarga1;
        $record->monthRecharge2 = $response->mesRecarga2;
        $record->monthRecharge3 = $response->mesRecarga3;
        $record->totalRecharge1 = $response->totalRecargas1;
        $record->totalRecharge2 = $response->totalRecargas2;
        $record->totalRecharge3 = $response->totalRecargas3;
        $record->totalRecharge = $response->totalRecargas;
        $record->recharge = $response->recargas;
        array_push(
          $rechargeMadeList,
          $record
        );

        break;
      case -150:
        $this->response->code = 0;
        break;
    }

    $this->response->data->rechargeMadeList = $rechargeMadeList;

    return $this->responseToTheView('callWS_RechargeMadeReport');
  }

  public function callWs_IssuedCards_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: IssuedCards Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Buscar tarjetas emitidas';
    $this->dataAccessLog->operation = $dataRequest->type === 'info' ? 'Información de tarjetas' : 'Descarga de archivo';

    $operationId = $dataRequest->format ?? $dataRequest->type;
    $operationIdTem = [
      'info' => '',
      'xls' => 'Excel',
      'pdf' => 'PDF',
    ];

    $this->dataRequest->idOperation = 'buscarTarjetasEmitidas' . $operationIdTem[$operationId];
    $this->dataRequest->className = 'com.novo.objects.MO.ListadoEmisionesMO';
    $this->dataRequest->accodcia = $dataRequest->enterpriseCode;
    $this->dataRequest->fechaMes = $dataRequest->monthYear ?? '';
    $this->dataRequest->fechaIni = $dataRequest->initDate ?? '';
    $this->dataRequest->fechaFin = $dataRequest->finalDate ?? '';
    $this->dataRequest->tipoConsulta = $dataRequest->queryType;

    if ($dataRequest->type === 'download') {
      $this->dataRequest->opcion = 'CARD_EMI';
      $this->dataRequest->idExtEmp = $dataRequest->fiscalId;
      $this->dataRequest->nombreEmpresa = $dataRequest->enterpriseName;
      $this->dataRequest->posicionDetalle = $dataRequest->detailIndex ?? '';
      $this->dataRequest->tipoDetalle = $dataRequest->detailType ?? '';
    }

    $response = $this->sendToWebServices('callWs_IssuedCardsReport');
    $record = [''];

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;

        if ($dataRequest->type === 'info') {
          $record = $response->lista ?? $record;
        }

        if ($dataRequest->type === 'download') {
          $this->response->data->file = $response->archivo;
          $this->response->data->name = trim($response->nombre) . '.' . $dataRequest->format;
          $this->response->data->ext = $dataRequest->format;

          if ($dataRequest->detailType != '') {
            $this->response->keepModal = TRUE;
          }
        }
        break;
      case -150:
        $this->response->code = 0;
        break;
    }

    $this->response->data->issuedCardsList = $record;
    $this->response->data->queryType = $dataRequest->queryType;

    return $this->responseToTheView('callWS_IssuedCardsReport');
  }

  public function callWs_CategoryExpense_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: CategoryExpense Method Initialized');

    $operation = $dataRequest->type === 'list' ? 'Movimientos ' : 'Archivo ' . $dataRequest->type;
    $idOperation = [
      'list' => 'buscarListadoGastosRepresentacion',
      'xls' => 'generarArchivoXlsGastosRepresentacion',
      'pdf' => 'generarArchivoPDFGastosRepresentacion'
    ];

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Gastos por categoria';
    $this->dataAccessLog->operation = $operation . ' ' . $dataRequest->annual ? 'anual' : 'rango';

    $initialDate = $dataRequest->initialDate;
    $finalDate = $dataRequest->finalDate;
    $querytype = "1";

    if ($dataRequest->annual) {
      $initialDate = '01/01/' . $dataRequest->yearDate;
      $finalDate = '31/12/' . $dataRequest->yearDate;
      $querytype = "0";
    }

    $this->dataRequest->idOperation = $idOperation[$dataRequest->type];
    $this->dataRequest->className = 'com.novo.objects.MO.GastosRepresentacionMO';
    $this->dataRequest->idExtEmp = $dataRequest->enterpriseCode;
    $this->dataRequest->producto = $dataRequest->productCode;
    $this->dataRequest->nroTarjeta = $dataRequest->cardNumber;
    $this->dataRequest->idPersona = $dataRequest->idDocument;
    $this->dataRequest->fechaIni = $initialDate;
    $this->dataRequest->fechaFin = $finalDate;
    $this->dataRequest->tipoConsulta = $querytype;

    $response = $this->sendToWebServices('callWs_CategoryExpense');
    $tableData = [];
    $file = [];
    $name = '';
    $ext = '';

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;

        if ($dataRequest->type === 'list') {
          if ($querytype === '0') {
            foreach (lang('GEN_DATEPICKER_MONTHNAMES') as $monthName) {
              $tableData[$monthName] = [];
            }

            foreach (lang('REPORTS_CATEG_GROUP') as $key => $value) {
              $key = strval($key);

              foreach ($response->listaGrupo as $group) {
                if ($group->idGrupo === $key) {
                  foreach ($group->gastoMensual as $expense) {
                    $tableData[manageString($expense->mes, 'lower', 'first')][] = $expense->monto;
                  }

                  $tableData['Total'][] = $group->totalCategoria;
                }
              }
            }

            foreach ($response->totalesAlMes as $expense) {
              $tableData[manageString($expense->mes, 'lower', 'first')][] = $expense->monto;
            }

            $tableData['Total'][] = $response->totalGeneral;
          }

          if ($querytype === '1') {
            foreach (lang('REPORTS_CATEG_GROUP') as $key => $value) {
              $key = strval($key);

              foreach ($response->listaGrupo as $group) {

                if ($group->idGrupo === $key) {
                  foreach ($group->gastoDiario as $expense) {
                    $tableData[$expense->fechaDia][] = $expense->monto;
                  }

                  $tableData['Total'][] = $group->totalCategoria;
                }
              }
            }

            foreach ($response->totalesPorDia as $expense) {
              $tableData[$expense->fechaDia][] = $expense->monto;
            }

            $tableData['Total'][] = $response->totalGeneral;
          }
        } else {
          $file = $response->bean->archivo ?? $response->archivo;
          $name = $response->bean->nombre ?? $response->nombre . '.' . $dataRequest->type;
          $ext = $dataRequest->type;
        }
        break;
      case -150:
        $this->response->code = 1;
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    $this->response->data->tableData = $tableData;
    $this->response->data->file = $file;
    $this->response->data->name = $name;
    $this->response->data->ext = $ext;

    return $this->responseToTheView('callWS_CategoryExpense');
  }

  /**
   * @info Método para obtener actividad por ususario
   * @author Diego Acosta García
   * @date May 27, 2020
   * @modified Jhonnatan Vega
   * @date March 10th, 2021
   */
  public function callWs_userActivity_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: userActivity Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Actividad por usuario';
    $this->dataAccessLog->operation = 'Obtener actividades por usuario';

    $this->dataRequest->idOperation = 'buscarActividadesXUsuario';
    $this->dataRequest->className = 'com.novo.objects.MO.ListadoEmpresasMO';
    $this->dataRequest->fechaIni =  $dataRequest->initialDate;
    $this->dataRequest->fechaFin =  $dataRequest->finalDate;
    $this->dataRequest->acCodCia = $dataRequest->enterpriseCode;

    $response = $this->sendToWebServices('callWs_userActivity');
    $usersActivity = [];

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;

        foreach ($response->lista as $userActivity) {
          $record = new stdClass();
          $record->user = $userActivity->userName;
          $record->userStatus = $userActivity->estatus;
          $record->lastConnectionDate = $userActivity->fechaUltimaConexion;
          $lastActions = [];

          foreach ($userActivity->actividades->lista as $lastActionsList) {
            array_push(
              $lastActions,
              $lastActionsList
            );
          }

          $record->lastActions = $lastActions;
          $enabledFunctions = [];

          foreach ($userActivity->funciones->lista as $enabledFunctionsList) {
            array_push(
              $enabledFunctions,
              $enabledFunctionsList
            );
          }

          $record->enabledFunctions = $enabledFunctions;
          array_push(
            $usersActivity,
            $record
          );
        }
        break;
      case -150:
        $this->response->code = 1;
        break;
    }

    $this->response->data->usersActivity = $usersActivity;

    return $this->responseToTheView('callWs_userActivity');
  }

  /**
   * Método para descargar reporte de actividad por usuario (Global)
   * @author Diego Acosta García
   * @date May 27, 2020
   * @modified Jhonnatan Vega
   * @date March 12th, 2021
   */
  public function callWs_exportReportUserActivity_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: exportReportUserActivity Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Actividad por usuario';

    if ($dataRequest->downloadFormat == 'Excel') {
      $this->dataAccessLog->operation = 'Descargar reporte en excel';
      $this->dataRequest->idOperation = 'generarArchivoXlsActividadesXUsuario';
      $ext =  '.xls';
    } else {
      $this->dataAccessLog->operation = 'Descargar reporte en pdf';
      $this->dataRequest->idOperation = 'generarPdfActividadesXUsuario';
      $ext =  '.pdf';
    }

    $this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
    $this->dataRequest->rifEmpresa = $dataRequest->rifEnterprise;
    $this->dataRequest->fechaIni =  $dataRequest->initialDate;
    $this->dataRequest->fechaFin =  $dataRequest->finalDate;
    $this->dataRequest->acCodCia = $dataRequest->enterpriseCode;
    $this->dataRequest->downloadFormat = $dataRequest->downloadFormat;

    $response = $this->sendToWebServices('callWs_exportReportUserActivity');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $file = $response->archivo;
        $name = $response->nombre;

        $this->response->data->file = $file;
        $this->response->data->name = $name . $ext;
        $this->response->data->ext = $ext;
        break;
      default:
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->responseToTheView('callWs_exportReportUserActivity');
  }

  /**
   * @info Método para obtener reportes de activades por usario
   * @author Yelsyns Lopez
   * @date Dic 15, 2023
   */
  public function callWs_exportToActivityUser_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: exportTo' . $dataRequest->type . ' Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Actividad por usuario';
    $this->dataAccessLog->operation = 'Descarga reporte ' . $dataRequest->type;

    $this->dataRequest->idOperation = $dataRequest->operation;
    $this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
    $this->dataRequest->rifEmpresa = $dataRequest->rifEnterprise;
    $this->dataRequest->fechaIni =  $dataRequest->initialDate;
    $this->dataRequest->fechaFin =  $dataRequest->finalDate;
    $this->dataRequest->acCodCia = $dataRequest->enterpriseCode;
    $this->dataRequest->ruta = DOWNLOAD_ROUTE;

    $response = $this->sendToWebServices('callWs_exportToActivityUser');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';

        if (file_exists(assetPath('downloads/' . $response->bean))) {
          $this->response->code = 0;
          $this->response->msg = lang('GEN_MSG_RC_0');
          $this->response->data = [
            'file' => assetUrl('downloads/' . $response->bean),
            'name' => $response->bean
          ];
        }
        break;
      default:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }


  /**
   * @info Método para obtener actividad por usuario (Produbanco)
   * @author Jhonnatan Vega
   * @date October 13, 2020
   */
  public function callWs_usersActivity_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: usersActivity Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Actividad por usuario';
    $this->dataAccessLog->operation = 'Obtener actividades por usuario';

    $this->dataRequest->idOperation = 'genericBusiness';
    $this->dataRequest->className = 'com.novo.objects.MO.GenericBusinessObject';
    $this->dataRequest->opcion = 'reporteLogAcceso';
    $this->dataRequest->userName = $this->userName;
    $this->dataRequest->accodcia = $dataRequest->enterpriseCode;
    $this->dataRequest->acprefix = $this->session->productInf->productPrefix;
    $this->dataRequest->fechaInicio =  $dataRequest->initialDate;
    $this->dataRequest->fechaFin =  $dataRequest->finalDate;

    $response = $this->sendToWebServices('callWs_usersActivity');
    $usersActivity = [];

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;

        foreach ($response->bean as $userActivity) {
          $record = new stdClass();
          $record->user = $userActivity->usuario;
          $record->userStatus = $userActivity->estadoUsuario;
          $record->lastConnectionDate = $userActivity->fechaUltimaConexion;
          $lastActions = [];

          foreach ($userActivity->opciones->ultimasAcciones as $lastActionsList) {
            array_push(
              $lastActions,
              $lastActionsList
            );
          }

          $record->lastActions = $lastActions;
          $enabledFunctions = [];

          foreach ($userActivity->opciones->funcionesHabilitadas as $enabledFunctionsList) {
            array_push(
              $enabledFunctions,
              $enabledFunctionsList
            );
          }

          $record->enabledFunctions = $enabledFunctions;
          array_push(
            $usersActivity,
            $record
          );
        }
        break;
      case -104:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('REPORTS_REQUEST_NO_RESULTS');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    $this->response->data->usersActivity = $usersActivity;

    return $this->responseToTheView('callWs_usersActivity');
  }

  /**
   * @info Método para descargar reporte de actividad por usuario (Produbanco)
   * @author Jhonnatan Vega
   * @date October 22, 2020
   */
  public function callWs_exportExcelUsersActivity_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: exportExcelUsersActivity Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'Actividad por usuario';
    $this->dataAccessLog->operation = 'Descarga reporte';

    $this->dataRequest->idOperation = 'genericBusiness';
    $this->dataRequest->className = 'com.novo.objects.MO.GenericBusinessObject';
    $this->dataRequest->opcion = 'reporteLogAccesoExcel';
    $this->dataRequest->userName = $this->userName;
    $this->dataRequest->accodcia = $dataRequest->enterpriseCode;
    $this->dataRequest->acprefix = $this->session->productInf->productPrefix;
    $this->dataRequest->fechaInicio =  $dataRequest->initialDate;
    $this->dataRequest->fechaFin =  $dataRequest->finalDate;
    $this->dataRequest->nombreUsuario =  $dataRequest->userToDownload;

    $response = $this->sendToWebServices('callWs_exportExcelUsersActivity');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $file = $response->bean->archivo;
        $name = $response->bean->nombreArchivo;
        $ext =  '.xlsx';
        $this->response->data->file = $file;
        $this->response->data->name = $name;
        $this->response->data->ext = $ext;
        break;
      default:
        $this->response->code = 4;
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->responseToTheView('callWs_exportExcelUsersActivity');
  }

  /**
   * @info Método para obtener busqueda de estado de cuenta
   * @author Diego Acosta García
   * @date Aug 18, 2020
   */
  public function callWs_searchStatusAccount_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: searchStatusAccount Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'movimientoEstadoCuentaDetalle';
    $this->dataAccessLog->operation = 'movimientoEstadoCuentaDetalle';

    $this->dataRequest->idOperation = 'movimientoEstadoCuentaDetalle';
    $this->dataRequest->className = 'com.novo.objects.MO.EstadoCuentaMO';

    $typeSearch = $dataRequest->resultSearch;

    $this->dataRequest->idExtPer = strtoupper($dataRequest->resultByNITInput ?? '');
    $this->dataRequest->card = strtoupper($dataRequest->resultByCardInput ?? '');

    $this->dataRequest->idExtEmp = $dataRequest->enterpriseCode;
    $lastDayMonyh = date("t-m-Y", strtotime(str_replace('/', '-', "1/" . $dataRequest->initialDateAct)));
    $this->dataRequest->fechaFin = str_replace('-', '/', $lastDayMonyh);
    $this->dataRequest->fechaIni = "1/" . $dataRequest->initialDateAct;
    $this->dataRequest->tamanoPagina = '10';
    $this->dataRequest->tipoConsulta = $typeSearch;
    $this->dataRequest->pagActual = '2';
    $this->dataRequest->prefix = $dataRequest->productCode;
    $this->dataRequest->paginar = false;

    $response = $this->sendToWebServices('callWs_searchStatusAccount');
    $listStatesAccounts = '';

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $listStatesAccounts = [];
        $listadoCuentas = $response->listadoEstadosCuentas;

        foreach ($listadoCuentas as $key => $val) {
          $listStatesAccounts[$key]['account'] = $listadoCuentas[$key]->cuenta;
          $listStatesAccounts[$key]['client'] = $listadoCuentas[$key]->cliente;
          $listStatesAccounts[$key]['id'] = $listadoCuentas[$key]->idExtPer;

          foreach ($listadoCuentas[$key]->listaMovimientos as $key1 => $val) {
            $listStatesAccounts[$key]['listMovements'][$key1]['card'] = $listadoCuentas[$key]->listaMovimientos[$key1]->tarjeta ?? '';
            $listStatesAccounts[$key]['listMovements'][$key1]['fid'] = $listadoCuentas[$key]->listaMovimientos[$key1]->fid ?? '';
            $listStatesAccounts[$key]['listMovements'][$key1]['secuence'] = $listadoCuentas[$key]->listaMovimientos[$key1]->secuencia ?? '';
            $listStatesAccounts[$key]['listMovements'][$key1]['terminal'] = $listadoCuentas[$key]->listaMovimientos[$key1]->terminalTransaccion ?? '';
            $listStatesAccounts[$key]['listMovements'][$key1]['reference'] = $listadoCuentas[$key]->listaMovimientos[$key1]->referencia;
            $listStatesAccounts[$key]['listMovements'][$key1]['description'] = $listadoCuentas[$key]->listaMovimientos[$key1]->descripcion;
            $listStatesAccounts[$key]['listMovements'][$key1]['date'] = $listadoCuentas[$key]->listaMovimientos[$key1]->fecha;
            $listStatesAccounts[$key]['listMovements'][$key1]['typeTransaction'] = $listadoCuentas[$key]->listaMovimientos[$key1]->tipoTransaccion;
            $listStatesAccounts[$key]['listMovements'][$key1]['amount'] = $listadoCuentas[$key]->listaMovimientos[$key1]->monto;
          }
        }
        break;
      case -444:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_REGISTRY_FOUND');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -150:
        $listStatesAccounts = [''];
        $this->response->code = 0;
        break;
      case 504:
        $this->response->msg = lang('GEN_TIMEOUT');
        break;
    }

    $this->response->data->listStatesAccountsNew = $listStatesAccounts != '' ? array_chunk($listStatesAccounts, lang('SETT_DATATABLE_ARRAY_CHUNK'), true) : '';

    return $this->responseToTheView('callWs_searchStatusAccount');
  }

  /**
   * @info Método para obtener busqueda de estado de cuenta extendido
   * @author Luis Molina / Jennifer Cádiz
   * @date Feb 16th, 2022
   */
  public function callWs_searchExtendedAccountStatus_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: searchExtendedAccountStatus Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'movimientoEstadoCuentaDetalle';
    $this->dataAccessLog->operation = 'movimientoEstadoCuentaDetalle';

    $this->dataRequest->idOperation = 'movimientoEstadoCuentaDetalle';
    $this->dataRequest->className = 'com.novo.objects.MO.EstadoCuentaMO';

    $typeSearch = $dataRequest->resultSearch;

    $this->dataRequest->idExtPer = strtoupper($dataRequest->resultByNITInput ?? '');
    $this->dataRequest->fullName = strtoupper($dataRequest->resultByNameInput ?? '');

    $this->dataRequest->idExtEmp = $dataRequest->enterpriseCode;
    $lastDayMonyh = date("t-m-Y", strtotime(str_replace('/', '-', "1/" . $dataRequest->initialDateAct)));
    $this->dataRequest->fechaFin = str_replace('-', '/', $lastDayMonyh);
    $this->dataRequest->fechaIni = "1/" . $dataRequest->initialDateAct;
    $this->dataRequest->prefix = $dataRequest->productCode;
    $this->dataRequest->tipoConsulta = $typeSearch;
    $this->dataRequest->tamanoPagina = 10;
    $this->dataRequest->paginar = TRUE;
    $this->dataRequest->pagActual = (int) ($dataRequest->start / 10) + 1;

    $response = $this->sendToWebServices('callWs_searchExtendedAccountStatus');

    $listStatesAccounts = [];

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;

        foreach ($response->listadoEstadosCuentas as $listStatesAcc) {
          foreach ($listStatesAcc->listaMovimientos as $listMov) {
            $record = new stdClass();
            $record->fecha = $listMov->fecha;
            $record->cuenta = $listMov->cuenta;
            $record->tarjeta = $listMov->tarjeta;
            $record->cliente = $listMov->cliente;
            $record->idExtPer = $listMov->idExtPer;
            $record->referencia = $listMov->referencia;
            $record->descripcion = $listMov->descripcion;
            $record->codigo = $listMov->codigo;
            $record->tipoTransaccion = $listMov->tipoTransaccion;
            $record->monto = $listMov->monto;
            $record->status = isset($listMov->estadoTransaccion) ? $listMov->estadoTransaccion : '';
            array_push(
              $listStatesAccounts,
              $record
            );
          }
        }
        break;
      case -444:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_REGISTRY_FOUND');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -150:
        $this->response->code = 0;
        break;
      case 504:
        $this->response->msg = lang('GEN_TIMEOUT');
        break;
    }

    $this->response->draw = (int)$dataRequest->draw;
    $this->response->recordsTotal = $response->totalRegistros ?? '0';
    $this->response->recordsFiltered = $response->totalRegistros ?? '0';
    $this->response->data = $listStatesAccounts;

    return $this->responseToTheView('callWs_searchExtendedAccountStatus');
  }

  /**
   * @info Método para obtener EXCEL de estado de cuenta
   * @author Diego Acosta García
   * @date Aug 18, 2020
   */
  public function callWs_statusAccountExcelFile_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: statusAccountExcelFile Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'generaArchivoXlsEdoCta';
    $this->dataAccessLog->operation = 'generaArchivoXlsEdoCta';

    $this->dataRequest->idOperation = 'generaArchivoXlsEdoCta';
    $this->dataRequest->className = 'com.novo.objects.MO.EstadoCuentaMO';

    if ($dataRequest->resultByNIT === 'all') {
      $dataRequest->resultByNIT = '';
      $typeSearch = '0';
    } else {
      $typeSearch = '1';
    }

    if (isset($dataRequest->resultByCardInput)) {
      $this->dataRequest->card = $dataRequest->resultByCardInput;
    }

    $this->dataRequest->idExtEmp = $dataRequest->enterpriseCode;
    $this->dataRequest->idExtPer = $dataRequest->resultByNIT;
    $lastDayMonyh = date("t-m-Y", strtotime(str_replace('/', '-', "1/" . $dataRequest->initialDateAct)));
    $this->dataRequest->fechaFin = str_replace('-', '/', $lastDayMonyh);
    $this->dataRequest->fechaIni = "1/" . $dataRequest->initialDateAct;
    $this->dataRequest->tamanoPagina = '5';
    $this->dataRequest->tipoConsulta = $typeSearch;
    $this->dataRequest->pagActual = '1';
    $this->dataRequest->prefix = $dataRequest->productCode;
    $this->dataRequest->paginar = false;
    $this->dataRequest->nombreEmpresa = $dataRequest->enterpriseName;
    $this->dataRequest->descProducto = $dataRequest->descProduct;

    $response = $this->sendToWebServices('callWs_statusAccountExcelFile');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $this->response->data = (array)$response;
        break;
      case 504:
        $this->response->msg = lang('GEN_TIMEOUT');
        break;
      default:
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->responseToTheView('callWs_statusAccountExcelFile');
  }
  /**
   * @info Método para obtener PDF de estado de cuenta
   * @author Diego Acosta García
   * @date Aug 18, 2020
   */
  public function callWs_statusAccountpdfFile_Reports($dataRequest)
  {
    writeLog('INFO', 'Reports Model: statusAccountpdfFile Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'generarComprobante';
    $this->dataAccessLog->operation = 'generarComprobante';

    $this->dataRequest->idOperation = 'generarComprobante';
    $this->dataRequest->className = 'com.novo.objects.MO.EstadoCuentaMO';

    if ($dataRequest->resultByNIT === 'all') {
      $dataRequest->resultByNIT = '';
      $typeSearch = '0';
    } else {
      $typeSearch = '1';
    }

    $this->dataRequest->idExtEmp = $dataRequest->enterpriseCode;
    $this->dataRequest->idExtPer = $dataRequest->resultByNIT;
    $lastDayMonyh = date("t-m-Y", strtotime(str_replace('/', '-', "1/" . $dataRequest->initialDateAct)));
    $this->dataRequest->fechaFin = str_replace('-', '/', $lastDayMonyh);
    $this->dataRequest->fechaIni = "1/" . $dataRequest->initialDateAct;
    $this->dataRequest->tamanoPagina = '5';
    $this->dataRequest->tipoConsulta = $typeSearch;
    $this->dataRequest->pagActual = '1';
    $this->dataRequest->prefix = $dataRequest->productCode;
    $this->dataRequest->paginar = false;
    $this->dataRequest->nombreEmpresa = $dataRequest->enterpriseName;
    $this->dataRequest->descProducto = $dataRequest->descProduct;

    $response = $this->sendToWebServices('callWs_statusAccountPdfFile');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $this->response->data = (array)$response;
        break;
      default:
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->responseToTheView('callWs_statusAccountpdfFile');
  }

  /**
   * @info Método para descargar nuevo reporte excel de Estado de Cuenta Extendido
   * @author Luis Molina
   * @date Mar 02, 2022
   * @update Yelsyns Lopez
   * @date Jun 26 2023
   */
  public function callWs_exportToExcelExtendedAccountStatus_Reports($dataRequest)
  {
    return $this->downLoadFileReportStatusAccount($dataRequest, 'Xls');
  }

  public function callWs_exportToTxtExtendedAccountStatus_Reports($dataRequest)
  {
    return $this->downLoadFileReportStatusAccount($dataRequest, 'Txt');
  }

  public function callWs_exportToPdfExtendedAccountStatus_Reports($dataRequest)
  {
    return $this->downLoadFileReportStatusAccount($dataRequest, 'Pdf');
  }

  /**
   * @info Método para descargar nuevo reporte de Estado de Cuenta Extendido
   * @author Yelsyns Lopez
   * @date Jun 26, 2023
   */
  public function downLoadFileReportStatusAccount($dataRequest, $typeFile)
  {
    log_message('INFO', 'NOVO Reports Model: exportTo' . $typeFile . 'ExtendedAccountStatus Method Initialized');

    $this->dataAccessLog->modulo = 'Reportes';
    $this->dataAccessLog->function = 'generaArchivo' . $typeFile . 'EdoCta';
    $this->dataAccessLog->operation = 'generaArchivo' . $typeFile . 'EdoCta';

    $this->dataRequest->idOperation = 'generaArchivo' . $typeFile . 'EdoCta';
    $this->dataRequest->className = 'com.novo.objects.MO.EstadoCuentaMO';

    $this->dataRequest->idExtEmp = $dataRequest->enterpriseCodeFileDownload;
    $this->dataRequest->fullName = strtoupper($dataRequest->resultByNameFileDownload ?? '');
    $this->dataRequest->idExtPer = strtoupper($dataRequest->resultByNITFileDownload ?? '');
    $lastDayMonyh = date("t-m-Y", strtotime(str_replace('/', '-', "1/" . $dataRequest->initialDateActFileDownload)));
    $this->dataRequest->fechaFin = str_replace('-', '/', $lastDayMonyh);
    $this->dataRequest->fechaIni = "1/" . $dataRequest->initialDateActFileDownload;
    $this->dataRequest->tamanoPagina = '5';
    $this->dataRequest->tipoConsulta = $dataRequest->resultSearchFileDownload;
    $this->dataRequest->pagActual = '1';
    $this->dataRequest->prefix = $dataRequest->productCodeFileDownload;
    $this->dataRequest->paginar = false;
    $this->dataRequest->nombreEmpresa = $dataRequest->enterpriseNameFileDownload;
    $this->dataRequest->descProducto = $dataRequest->descProductFileDownload;
    $this->dataRequest->ruta = DOWNLOAD_ROUTE;

    $response = $this->sendToWebServices('callWs_exportTo' . $typeFile . 'ExtendedAccountStatus');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->icon = lang('SETT_ICON_DANGER');
        $this->response->title = lang('REPORTS_TITLE');
        $this->response->msg = lang('REPORTS_NO_FILE_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';

        if (file_exists(assetPath('downloads/' . $response->bean))) {
          $this->response->code = 0;
          $this->response->msg = lang('GEN_MSG_RC_0');
          $this->response->data = [
            'file' => assetUrl('downloads/' . $response->bean),
            'name' => $response->bean
          ];
        }
        break;
      default:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->response;
  }
}
