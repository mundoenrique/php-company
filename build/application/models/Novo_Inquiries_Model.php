<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info clase para obtener información relacionada con los lotes
 * @author
 *
 */
class Novo_Inquiries_Model extends NOVO_Model
{

  public function __construct()
  {
    parent::__construct();
    writeLog('INFO', 'Inquiries Model Class Initialized');
  }
  /**
   * @info Método para obtener la lista de estados de las ordenes de servicio
   * @author J. Enrique Peñaloza Piñero
   * @date Janury 09th, 2019
   */
  public function callWs_ServiceOrderStatus_Inquiries()
  {
    writeLog('INFO', 'Inquiries Model: ServiceOrderStatus Method Initialized');

    $this->dataAccessLog->modulo = 'Consultas';
    $this->dataAccessLog->function = 'Lista de ordenes de servicio';
    $this->dataAccessLog->operation = 'Estados de orden de servicio';

    $this->dataRequest->idOperation = 'estatusLotes';
    $this->dataRequest->className = 'com.novo.objects.MO.EstatusLotesMO';
    $this->dataRequest->tipoEstatus = 'TIPO_B';

    $response = $this->sendToWebServices('callWs_ServiceOrderStatus');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $orderStatus[] = (object) [
          'key' => '',
          'text' => 'Selecciona un estado'
        ];

        foreach ($response->lista as $pos => $types) {
          $type = [];
          $type['key'] = mb_strtoupper($response->lista[$pos]->codEstatus);
          $type['text'] = ucfirst(mb_strtolower($response->lista[$pos]->descEstatus));
          $orderStatus[] = (object) $type;
        }
        break;
    }

    if ($this->isResponseRc != 0) {
      $orderStatus[] = (object) [
        'key' => '',
        'text' => lang('GEN_TRY_AGAIN')
      ];
    }

    $this->response->data->orderStatus = (object) $orderStatus;

    return $this->responseToTheView('callWs_ServiceOrderStatus');
  }
  /**
   * @info Método para obtener la lista de ordenes de servicio en rango de fecha dado
   * @author J. Enrique Peñaloza Piñero
   * @date Janury 09th, 2019
   */
  public function callWs_GetServiceOrders_Inquiries($dataRequest)
  {

    writeLog('INFO', 'Inquiries Model: ServiceOrderStatus Method Initialized');

    $this->dataAccessLog->modulo = 'Consultas';
    $this->dataAccessLog->function = 'Ordenes de servicio';
    $this->dataAccessLog->operation = 'Lista de ordenes de servicio';

    $this->dataRequest->idOperation = 'buscarOrdenServicio';
    $this->dataRequest->className = 'com.novo.objects.MO.ListadoOrdenServicioMO';
    $this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;
    $this->dataRequest->acCodCia = $this->session->enterpriseInf->enterpriseCode;
    $this->dataRequest->idProducto = $this->session->productInf->productPrefix;
    $this->dataRequest->fechaIni = $dataRequest->initialDate;
    $this->dataRequest->fechaFin = $dataRequest->finalDate;
    $this->dataRequest->status = $dataRequest->status;
    $this->dataRequest->statusText = $dataRequest->statusText;
    $statusText = $dataRequest->statusText;

    $response = $this->sendToWebServices('callWs_GetServiceOrders');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $this->response->data = lang('SETT_LINK_SERVICE_ORDERS');

        $serviceOrdersList = [];
        $bulkNonBillable = [];
        foreach ($response->lista as $dataOrder) {
          $serviceOrders = new stdClass();
          $serviceOrders->OrderNumber = $dataOrder->idOrden;
          $serviceOrders->OrderStatus = $dataOrder->estatus;
          $serviceOrders->Orderdate = $dataOrder->fechaGeneracion;
          $serviceOrders->noFactura = $dataOrder->nofactura;
          $serviceOrders->showBill = $dataOrder->nofactura !== '' && $dataOrder->fechafactura !== '';
          $serviceOrders->billing = $response->facturacion;
          $serviceOrders->reprocessing = $response->reproceso;
          $serviceOrders->pagoOS['factura'] = $dataOrder->nofactura;
          $serviceOrders->pagoOS['total'] = $dataOrder->montoDeposito;
          $serviceOrders->OrderDeposit = currencyFormat($dataOrder->montoDeposito);
          $serviceOrders->OrderCommission = currencyFormat($dataOrder->montoComision);
          $serviceOrders->OrderTax = currencyFormat($dataOrder->montoIVA);
          $serviceOrders->OrderAmount = currencyFormat($dataOrder->montoOS);
          $serviceOrders->OrderVoidable = FALSE;
          $serviceOrders->warningEnabled = FALSE;

          if ($dataOrder->estatus == '0' &&  $dataOrder->nofactura === '' && $dataOrder->fechafactura === '') {
            $serviceOrders->OrderVoidable = TRUE;
          }

          foreach ($dataOrder->lotes as $bulk) {
            $bulkList = new stdClass();
            $bulkList->bulkNumber = $bulk->acnumlote;
            $bulkList->bulkLoadDate = $bulk->dtfechorcarga;
            $bulkList->bulkRecords = $bulk->ncantregs;
            $bulkList->bulkId = $bulk->acidlote;
            $bulkList->bulkLoadType = manageString($bulk->acnombre, 'lower', 'first');
            $bulkList->bulkStatus = manageString($bulk->status, 'lower', 'first');
            $bulkList->bulkAmount = currencyFormat($bulk->montoRecarga);
            $bulkList->bulkCommisAmount = currencyFormat($bulk->montoComision);
            $bulkList->bulkTotalAmount = currencyFormat($bulk->montoNeto);
            $bulkList->bulkObservation = '';

            if (isset($bulk->obs)  && $bulk->obs !== '' && $bulk->cestatus === lang('SETT_STATUS_REJECTED')) {
              $bulkList->bulkObservation = $bulk->obs;
              $serviceOrders->warningEnabled = TRUE;
            }

            $serviceOrders->bulk[] = $bulkList;
          }

          $serviceOrdersList[] = $serviceOrders;
        }

        foreach ($response->lotesNF as $nonBillable) {
          $bulkList = new stdClass();
          $bulkList->bulkId = $nonBillable->acidlote;
          $bulkList->bulkNumber = $nonBillable->acnumlote;
          $bulkList->bulkLoadDate = $nonBillable->dtfechorcarga;
          $bulkList->bulkLoadType = $nonBillable->acnombre;
          $bulkList->bulkRecords = $nonBillable->ncantregs;
          $bulkList->bulkStatus = manageString($nonBillable->status, 'lower', 'first');

          $bulkNonBillable[] = $bulkList;
        }

        $this->session->set_flashdata('serviceOrdersList', $serviceOrdersList);
        $this->session->set_flashdata('bulkNotBillable', $bulkNonBillable);
        $this->session->set_userdata('requestOrdersList', $dataRequest);
        break;
      case -5:
        $this->response->title = 'Órdenes de servicio';
        $this->response->msg = lang('INQ_NO_SERVICE_ORDER');
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -150:
        $this->response->title = 'Órdenes de servicio';
        $this->response->msg = novoLang(lang('GEN_SERVICE_ORDES'), $statusText);
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->responseToTheView('callWs_GetServiceOrders');
  }
  /**
   * @info Elimina un lote
   * @author Luis Molina
   * @date febrero 20 th, 2020
   */
  public function callWs_ClearServiceOrders_Inquiries($dataRequest)
  {
    writeLog('INFO', 'Inquiries Model: ClearServiceOrders Method Initialized');

    $this->dataAccessLog->modulo = 'Consultas';
    $this->dataAccessLog->function = 'Ordenes de servicio';
    $this->dataAccessLog->operation = 'Anular orden de servicio';

    $rifEmpresa = $this->session->userdata('enterpriseInf')->idFiscal;

    unset($dataRequest->modalReq);

    $this->dataRequest->idOperation = 'desconciliarOS';
    $this->dataRequest->className = 'com.novo.objects.TOs.OrdenServicioTO';
    $this->dataRequest->idOrden = $dataRequest->OrderNumber;
    $this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;

    $password = isset($dataRequest->pass) ? $this->cryptography->decryptOnlyOneData($dataRequest->pass) : $this->session->passWord;

    if (getSignSessionType() === lang('SETT_COOKIE_SINGN_IN')) {
      $password = $this->session->passWord ?: md5($password);
    }

    $this->dataRequest->usuario = [
      'userName' => $this->userName,
      'password' => $password
    ];

    $response = $this->sendToWebServices('callWs_ClearServiceOrders');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->cod = 0;
        $this->response->title = 'Anular Orden';
        $this->response->msg = 'La orden fue anulada exitosamente';
        $this->response->icon = lang('SETT_ICON_SUCCESS');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -1:
        $this->response->title = 'Anular Orden';
        $this->response->msg = lang('GEN_PASSWORD_NO_VALID');
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->responseToTheView('callWs_ClearServiceOrders');
  }
  /**
   * @info Ver el detalle de un lote
   * @author J. Enrique Peñaloza Piñero
   * @date February 09th, 2020
   * @modified J. Enrique Peñaloza Piñero
   * @date April 17st, 2019
   */
  public function callWs_BulkDetail_Inquiries($dataRequest)
  {
    writeLog('INFO', 'Bulk Model: BulkDetail Method Initialized');

    $this->dataAccessLog->modulo = 'Consultas';
    $this->dataAccessLog->function = $dataRequest->bulkfunction;
    $this->dataAccessLog->operation = 'Ver detalle del lote';

    $this->dataRequest->idOperation = 'detalleLote';
    $this->dataRequest->className = 'com.novo.objects.MO.AutorizarLoteMO';
    $this->dataRequest->acidlote = $dataRequest->bulkId;

    $response = $this->sendToWebServices('callWs_BulkDetail');

    $detailInfo = [
      'fiscalId' => '--',
      'enterpriseName' => '--',
      'bulkType' => '--',
      'bulkTypeText' => '--',
      'bulkNumber' => '--',
      'totalRecords' => '--',
      'loadUserName' => '--',
      'bulkDate' => '--',
      'bulkStatus' => '--',
      'bulkStatusText' => '--',
      'bulkAmount' => '--',
      'bulkHeader' => [],
      'bulkRecords' => [],
    ];
    $tableContent = new stdClass();
    $tableContent->header = [];

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $detailInfo['fiscalId'] = $response->acrif;
        $detailInfo['enterpriseName'] = mb_strtoupper(mb_strtolower($response->acnomcia));
        $detailInfo['bulkType'] = $response->ctipolote;
        $detailInfo['bulkTypeText'] = mb_strtoupper(mb_strtolower($response->acnombre));
        $detailInfo['bulkNumber'] = $response->acnumlote;
        $detailInfo['totalRecords'] = $response->ncantregs;
        $detailInfo['loadUserName'] = trim($response->accodusuarioc);
        $detailInfo['bulkDate'] = $response->dtfechorcarga;
        $detailInfo['bulkStatus'] = $response->cestatus;
        $detailInfo['bulkStatusText'] = ucfirst(mb_strtolower($response->status));
        $detailInfo['bulkAmount'] = currencyFormat($response->nmonto);
        $detailInfo['bulkId'] = $response->acidlote;

        switch ($response->ctipolote) {
          case '1':
          case '10':
            if (isset($response->registrosLoteEmision) && count($response->registrosLoteEmision) > 0) {
              $tableContent = BulkAttrEmissionA();
              $detailInfo['bulkRecords'] = $this->buildEmisionRecords_Bulk($response->registrosLoteEmision, $tableContent->body);
            }
            break;
          case '3':
          case '6':
          case 'A':
            if (isset($response->registrosLoteEmision) && count($response->registrosLoteEmision) > 0) {
              $tableContent = BulkAttrEmissionB();
              $detailInfo['bulkRecords'] = $this->buildEmisionRecords_Bulk($response->registrosLoteEmision, $tableContent->body);
            }
            break;
          case 'V':
            if (isset($response->registrosLoteEmision) && count($response->registrosLoteEmision) > 0) {
              $tableContent = BulkAttrEmissionC();
              $detailInfo['bulkRecords'] = $this->buildEmisionRecords_Bulk($response->registrosLoteEmision, $tableContent->body);
            }
            break;
          case '2':
            if (isset($response->registrosLoteRecarga) && count($response->registrosLoteRecarga) > 0) {
              $tableContent = BulkAttrCreditsA();
              $detailInfo['bulkRecords'] = $this->buildCreditRecords_Bulk($response->registrosLoteRecarga, $tableContent->body);
            }
            break;
          case '5':
          case 'L':
          case 'F':
            if (isset($response->registrosLoteRecarga) && count($response->registrosLoteRecarga) > 0) {
              $tableContent = BulkAttrCreditsB();
              $detailInfo['bulkRecords'] = $this->buildCreditRecords_Bulk($response->registrosLoteRecarga, $tableContent->body);
            }
            break;
          case 'M':
            if (isset($response->registrosLoteRecarga) && count($response->registrosLoteRecarga) > 0) {
              $tableContent = BulkAttrCreditsC();
              $detailInfo['bulkRecords'] = $this->buildCreditRecords_Bulk($response->registrosLoteRecarga, $tableContent->body);
            }
            break;
          case 'E':
            if (isset($response->registrosLoteGuarderia) && count($response->registrosLoteGuarderia) > 0) {
              $tableContent = BulkAttrKindergastenA();
              $detailInfo['bulkRecords'] = $this->buildKindergartenRecords_Bulk($response->registrosLoteGuarderia, $tableContent->body);
            }
            break;
          case 'G':
            if (isset($response->registrosLoteGuarderia) && count($response->registrosLoteGuarderia) > 0) {
              $tableContent = BulkAttrKindergastenB();
              $detailInfo['bulkRecords'] = $this->buildKindergartenRecords_Bulk($response->registrosLoteGuarderia, $tableContent->body);
            }
            break;
          case 'R':
          case 'C':
          case 'N':
            if (isset($response->registrosLoteReposicion) && count($response->registrosLoteReposicion) > 0) {
              $tableContent = BulkAttrReplacementA();
              $detailInfo['bulkRecords'] = $this->buildReplacement_Bulk($response->registrosLoteReposicion, $tableContent->body);
            }
            break;
          default:
            if (isset($response->registros) && count($response->registros->detalle) > 0) {
              array_shift($response->registros->ordenAtributos);
              $attrOrder = $response->registros->ordenAtributos;
              array_shift($response->registros->nombresColumnas);
              $headerName = $response->registros->nombresColumnas;

              foreach ($response->registros->nombresColumnas as $key => $value) {
                $value = ucfirst(mb_strtolower($value));
                array_push(
                  $tableContent->header,
                  $value
                );
              }

              foreach ($response->registros->detalle as $key => $records) {
                $record = new stdClass();
                foreach ($attrOrder as $attr) {
                  if ($attr == 'NUMERO_CUENTA') {
                    $records->$attr = maskString($records->$attr, 6, 4);
                  }

                  $record->$attr = $records->$attr;
                }

                array_push(
                  $detailInfo['bulkRecords'],
                  $record
                );
              }
            }
        }
        break;
    }

    $detailInfo['bulkHeader'] = $tableContent->header;
    $this->response->data->bulkInfo = (object) $detailInfo;

    return $this->responseToTheView('callWs_BulkDetail');
  }
  /**
   * @info Construye el cuerpo de la tabla del detalle de un lote de emisión
   * @author J. Enrique Peñaloza Piñero
   * @date April 17th, 2020
   * @modified J. Enrique Peñaloza Piñero
   * @date October 01st, 2020
   */
  private function buildEmisionRecords_Bulk($emisionRecords, $acceptAttr)
  {
    writeLog('INFO', 'Inquiries Model: buildEmisionRecords Method Initialized');

    $bulkDetail = [];

    foreach ($emisionRecords as $key => $records) {
      $recordDetail = new stdClass();

      foreach ($acceptAttr as $pos => $attr) {
        $recordDetail->$attr = $records->$attr ?? '';

        if ($attr == 'status') {
          $status = [
            '0' => 'En proceso',
            '1' => 'Procesado',
            '5' => 'Rechazado',
            '7' => 'Anulado',
          ];
          $recordDetail->$attr = is_numeric($records->$attr) ? $status[$records->$attr] : $records->$attr;
        }

        if ($attr == 'nombres') {
          $recordDetail->$attr = ucwords(mb_strtolower($recordDetail->$attr));
        }

        if ($attr == 'apellidos') {
          $recordDetail->$attr = ucwords(mb_strtolower($recordDetail->$attr));
        }

        if ($attr == 'typeIdentification') {
          $recordDetail->$attr = lang('GEN_RECOVER_DOC_TYPE')[$recordDetail->$attr];
        }
      }

      if (in_array('nombres', $acceptAttr) && in_array('apellidos', $acceptAttr)) {
        $recordDetail->nombres = $recordDetail->nombres . ' ' . $recordDetail->apellidos;
        unset($recordDetail->apellidos);
      }

      $bulkDetail[] = $recordDetail;
    }

    return $bulkDetail;
  }
  /**
   * @info Construye el cuerpo de la tabla del detalle de un lote de recarga
   * @author J. Enrique Peñaloza Piñero
   * @date April 17th, 2020
   * @modified J. Enrique Peñaloza Piñero
   * @date October 01st, 2020
   */
  private function buildCreditRecords_Bulk($creditRecords, $acceptAttr)
  {
    writeLog('INFO', 'Inquiries Model: buildCreditRecords Method Initialized');

    $bulkDetail = [];

    foreach ($creditRecords as $key => $records) {
      $recordDetail = new stdClass();

      foreach ($acceptAttr as $pos => $attr) {
        $recordDetail->$attr = $records->$attr ?? '';

        if ($attr == 'monto') {
          $recordDetail->$attr = currencyFormat($recordDetail->$attr);
        }

        if ($attr == 'nro_cuenta') {
          $recordDetail->$attr = maskString($recordDetail->$attr, 6, 4);
        }

        if ($attr == 'status') {
          $status = [
            '0' => 'Pendiente',
            '1' => 'Procesado',
            '2' => 'Inválida',
            '3' => 'En proceso',
            '6' => 'Procesado',
            '7' => 'Rechazado',
          ];
          $recordDetail->$attr = is_numeric($records->$attr) ? $status[$records->$attr] : $records->$attr;
        }
      }

      $bulkDetail[] = $recordDetail;
    }

    return $bulkDetail;
  }
  /**
   * @info Construye el cuerpo de la table del detalle de un lote de guardería
   * @author J. Enrique Peñaloza Piñero
   * @date April 17th, 2020
   * @modified
   * @date
   */
  private function buildKindergartenRecords_Bulk($gardenRecords, $acceptAttr)
  {
    writeLog('INFO', 'Inquiries Model: buildKindergartenRecords Method Initialized');
    $bulkDetail = [];

    foreach ($gardenRecords as $key => $records) {
      $recordDetail = new stdClass();

      foreach ($acceptAttr as $pos => $attr) {
        $recordDetail->$attr = $records->$attr ?? '';

        if ($attr == 'nombre') {
          $recordDetail->$attr = ucwords(mb_strtolower($recordDetail->$attr));
        }

        if ($attr == 'apellido') {
          $recordDetail->$attr = ucwords(mb_strtolower($recordDetail->$attr));
        }

        if ($attr == 'nro_cuenta') {
          $recordDetail->$attr = maskString($recordDetail->$attr, 6, 4);
        }
      }

      if (in_array('nombre', $acceptAttr) && in_array('apellido', $acceptAttr)) {
        $recordDetail->nombre = $recordDetail->nombre . ' ' . $recordDetail->apellido;
        unset($recordDetail->apellido);
      }

      $bulkDetail[] = $recordDetail;
    }

    return $bulkDetail;
  }
  /**
   * @info Construir el cuerpo de la tabla del detalle de un lote de reposición
   * @author J. Enrique Peñaloza Piñero
   * @date April 17th, 2020
   * @modified
   * @date
   */
  private function buildReplacement_Bulk($replaceRecords, $acceptAttr)
  {
    writeLog('INFO', 'Inquiries Model: buildreplacement Method Initialized');

    $detailRecords = [];

    foreach ($replaceRecords as $records) {
      $record = new stdClass();
      foreach ($records as $pos => $value) {
        switch ($pos) {
          case 'nocuenta':
            if (in_array($pos, $acceptAttr)) {
              $record->cardHoldAccount = maskString($value, 6, 4);
            }
            break;
          case 'aced_rif':
            if (in_array($pos, $acceptAttr)) {
              $cardHoldId = $value != '' ? $value : '- -';
              $record->cardHoldId = $value;
            }
            break;
        }
      }

      $detailRecords[] = $record;
    }

    return $detailRecords;
  }
  /**
   * @info Exporta factura .pdf de una orden de servicio
   * @author Enrique Peñaloza
   * @date July 01st, 2024
   */
  public function callWs_DeliverInvoice_Inquiries($dataRequest)
  {
    writeLog('INFO', 'Inquiries Model: DeliverInvoice Method Initialized');

    $this->dataAccessLog->modulo = 'Consultas';
    $this->dataAccessLog->function = 'Ordenes de servicio';
    $this->dataAccessLog->operation = 'Descargar factura';

    $this->dataRequest->idOperation = 'descargarFactura';
    $this->dataRequest->className = 'com.novo.objects.TOs.FacturaTO';
    $this->dataRequest->idOrdenS = $dataRequest->OrderNumber;
    $this->dataRequest->dFecha = date("m/d/Y H:i");

    $response = $this->sendToWebServices('callWs_DeliverInvoice');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $file = $response->archivo;
        $name = explode('.', $response->nombre);
        $ext = manageString($response->formatoArchivo, 'lower', 'none');

        $this->response->data->file = $file;
        $this->response->data->name = $name[0] . '_' . time() . '.' . $ext;
        $this->response->data->ext = $ext;
        break;
      case -56:
        $this->response->code = 1;
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->title = lang('GEN_ORDER_TITLE');
        $this->response->msg = lang('INQ_NO_INVOICE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->responseToTheView('callWs_DeliverInvoice');
  }
  /**
   * @info Exporta archivo .pdf de una orden de servicio
   * @author Luis Molina
   * @date March 10th, 2020
   * @mofied J. Enrique Peñaloza Piñero
   * @date March 19th, 2020
   */
  public function callWs_ExportFiles_Inquiries($dataRequest)
  {
    writeLog('INFO', 'Inquiries Model: exportFiles Method Initialized');

    $this->dataAccessLog->modulo = 'Consultas';
    $this->dataAccessLog->function = 'Ordenes de servicio';
    $this->dataAccessLog->operation = 'Descargar pdf orden de servicio';
    $ext = '.pdf';

    $this->dataRequest->idOperation = 'visualizarOS';
    $this->dataRequest->className = 'com.novo.objects.TOs.OrdenServicioTO';
    $this->dataRequest->rifEmpresa = $this->session->userdata('enterpriseInf')->idFiscal;
    $this->dataRequest->acCodCia = $this->session->userdata('enterpriseInf')->enterpriseCode;
    $this->dataRequest->acprefix = $this->session->userdata('productInf')->productPrefix;
    $this->dataRequest->idOrden = $dataRequest->OrderNumber;

    $response = $this->sendToWebServices('callWs_ExportFiles');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $file = $response->archivo;
        $name = str_replace('OS', 'Orden_de_Servicio', $response->nombre);

        $this->response->data->file = $file;
        $this->response->data->name = $name;
        $this->response->data->ext = $ext;
        break;
      case -52:
        $this->response->code = 1;
        $this->response->title = lang('GEN_ORDER_TITLE');
        $this->response->msg = lang('GEN_NO_BULK_AUTHORIZATION');
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      default:
        $requestOrdersList = $this->session->userdata('requestOrdersList');
        $this->load->model('Novo_inquiries_Model', 'getOrders');
        $response = $this->getOrders->callWs_GetServiceOrders_Inquiries($requestOrdersList);
        $this->response->code =  $response->code != 0 ? $response->code : 3;
        $this->response->title = $response->code != 0 ? $response->title : lang('GEN_DOWNLOAD_FILE');
        $this->response->msg = $response->code != 0 ? $response->msg : lang('GEN_WARNING_DOWNLOAD_FILE');
        $this->response->icon =  $response->code != 0 ? $response->icon : lang('SETT_ICON_WARNING');
        $this->response->download =  $response->modalBtn['btn1']['action'] == 'redirect' ? FALSE : TRUE;
        $this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_ACCEPT');
        $this->response->modalBtn['btn1']['action'] = $response->code != 0 ? $response->modalBtn['btn1']['action'] : 'close';
        $this->session->set_flashdata('download', $this->response);
        break;
    }

    return $this->responseToTheView('callWs_ExportFiles');
  }

  public function CallWs_PagoOs_Inquiries($dataRequest)
  {
    writeLog('INFO', 'Services Model: PagoOS Method Initialized');

    $this->dataAccessLog->modulo = 'Pagos';
    $this->dataAccessLog->function = 'Doble Autenticacion';
    $this->dataAccessLog->operation = 'Generar Token Pago Orden de Servicio';

    $this->dataRequest->idOperation = 'dobleAutenticacion';
    $this->dataRequest->className = 'com.novo.objects.TO.UsuarioTO';

    $response = $this->sendToWebServices('CallWs_pagoOs');
    $this->response->title = lang('PAG_OS_TITLE');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->msg = lang('GEN_OTP');
        $this->response->modalBtn['btn1']['action'] = 'none';
        $this->response->modalBtn['btn2']['text'] = lang('GEN_BTN_CANCEL');
        $this->response->modalBtn['btn2']['action'] = 'close';
        $this->session->set_userdata('authToken', $response->bean);
        break;
      default:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_OTP_NO_SENT');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }
    return $this->responseToTheView('CallWs_pagoOs');
  }

  public function CallWs_PagarOS_Inquiries($dataRequest)
  {
    writeLog('INFO', 'Services Model: PagarOS Method Initialized');

    $this->dataAccessLog->modulo = 'Pagos';
    $this->dataAccessLog->function = 'Pagar Orden de servicio';
    $this->dataAccessLog->operation = 'Realizar Pago';

    $this->dataRequest->idOperation = 'pagarOS';
    $this->dataRequest->className = 'com.novo.objects.TOs.OrdenServicioTO';

    $this->dataRequest->authToken = $this->session->userdata('authToken');
    $this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;
    $this->dataRequest->idProducto = $this->session->productInf->productPrefix;
    $this->dataRequest->idOrden = $dataRequest->idOS;
    $this->dataRequest->tokenCliente = $dataRequest->codeToken;
    $this->dataRequest->montoTotal = $dataRequest->totalAmount;
    $this->dataRequest->nofactura = $dataRequest->noFactura;
    $this->dataRequest->acUsuario = $this->userName;

    $response = $this->sendToWebServices('CallWs_PagarOS');
    $this->response->title = lang('PAG_OS_TITLE');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $this->response->dataFormListOS = $this->session->userdata('requestOrdersList');
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->msg = lang('PAG_OS_OK');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        $this->session->unset_userdata('requestOrdersList');
        $this->session->unset_userdata('authToken');
        break;
      case -14:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_MONTHLY_AMOUNT_MAX_EXCEEDED');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -21:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('PAGO_OS_CONECTION_ERROR');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -24:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_DAILY_TRANSACTION_EXCEEDED');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -25:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_DAILY_AMOUNT_EXCEEDED');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -155:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('PAGO_OS_UNAVAILABLE_BALANCE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -220:
      case -275:
      case -468:
      case -470:
      case -471:
      case -474:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_ACCOUNT_NOT_AVAILABLE');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -230:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('PAGO_OS_GENERAL_MSG');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -241:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('PAGO_OS_PARAMS_INVALID');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -281:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('PAGO_OS_ACCOUNT_INVALID');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -285:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('PAGO_OS_ACCOUNT_INACTIV');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -286:
        $this->response->code = 1;
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('PAGO_OS_INVALID_CODE');
        $this->response->modalBtn['btn1']['action'] = 'none';
        $this->response->modalBtn['btn2']['text'] = lang('GEN_BTN_CANCEL');
        $this->response->modalBtn['btn2']['action'] = 'destroy';
        break;
      case -287:
        $this->response->code = 2;
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('PAGO_OS_USED_CODE');
        $this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_RESEND');
        $this->response->modalBtn['btn1']['action'] = 'none';
        $this->response->modalBtn['btn2']['action'] = 'destroy';
        break;
      case -288:
        $this->response->code = 2;
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('PAGO_OS_EXPIRED_CODE');
        $this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_RESEND');
        $this->response->modalBtn['btn1']['action'] = 'none';
        $this->response->modalBtn['btn2']['action'] = 'destroy';
        break;
      case -296:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('PAGO_OS_NOT_REGISTERED');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -297:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_OS_UNREGISTERED_ACCOUNT');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -298:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('PAGO_OS_INVALID_CONCEPT');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -299:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('PAGO_OS_CONCEPT_NOT_EXIST');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -300:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = $response->msg;
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -472:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_INVALID_DOCUMENT');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -473:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_MONTHLY_AMOUNT_EXCEEDED');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -475:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_CONSIGNMENT_AMOUNT_EXCEEDED');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -476:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_DEBITS_AMOUNT_MAX_EXCEEDED');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -477:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_DEBITS_CONSIGNMENT_AMOUNT_MAX_EXCEEDED');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -478:
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_AMOUNT_MAX_EXCEEDED');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }
    return $this->responseToTheView('CallWs_PagarOS');
  }
}
