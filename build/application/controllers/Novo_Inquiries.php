<?php
defined('BASEPATH') or  exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a consultas de ordenes de servicio
 * @author J. Enrique Peñaloza Piñero
 * @date January 09th, 2019
 */
class Novo_Inquiries extends NOVO_Controller
{

  public function __construct()
  {
    parent::__construct();
    writeLog('INFO', 'Inquiries Controller Class Initialized');
  }
  /**
   * @info Método para renderizar la lista de ordenes de servicio
   * @author J. Enrique Peñaloza Piñero
   * @date November 05th, 2019
   */
  public function serviceOrders()
  {
    writeLog('INFO', 'Inquiries: serviceOrders Method Initialized');

    $view = 'serviceOrders';
    array_push(
      $this->includeAssets->cssFiles,
      "third_party/dataTables-1.10.20"
    );
    array_push(
      $this->includeAssets->jsFiles,
      "third_party/dataTables-1.10.20",
      "third_party/jquery.validate",
      "form_validation",
      "third_party/additional-methods",
      "inquiries/serviceOrders"
    );
    $renderOrderList = FALSE;
    $ordersList = $this->session->flashdata('serviceOrdersList');
    $nonBillable = $this->session->flashdata('bulkNotBillable');

    if ($ordersList !== NULL || $nonBillable !== NULL) {
      $renderOrderList = TRUE;
    }

    $this->load->model('Novo_Inquiries_Model', 'Inquiries');
    $responseList = $this->Inquiries->callWs_ServiceOrderStatus_Inquiries();

    if ($this->session->userdata('requestOrdersList')) {
      $requestOrdersList = $this->session->userdata('requestOrdersList');
      $this->session->set_userdata('requestOrdersList', $requestOrdersList);
    }

    if ($this->session->flashdata('download')) {
      $respDownload = $this->session->flashdata('download');
      $this->responseAttr($respDownload);
    } else {
      $this->responseAttr($responseList);
    }

    $this->render->orderStatus = $responseList->data->orderStatus;
    $this->render->renderOrderList = $renderOrderList;
    $this->render->orderList = $ordersList ?? [];
    $this->render->nonBillable = $nonBillable ?? [];
    $this->render->titlePage = lang('GEN_SERVICE_ORDERS_TITLE');
    $this->views = ['inquiries/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para autorizar un lote
   * @author J. Enrique Peñaloza Piñero
   * @date December 25th, 2019
   * @mofied J. Enrique Peñaloza Piñero
   * @date April 19th, 2019
   */
  public function bulkDetail()
  {
    writeLog('INFO', 'Inquiries: bulkDetail Method Initialized');

    if (!isset($this->request->bulkId) && !$this->session->flashdata('download')) {
      redirect(base_url(lang('SETT_LINK_PRODUCT_DETAIL')), 'Location', 302);
      exit;
    }

    $responseAttr = new stdClass();
    $download = FALSE;
    $view = 'bulkDetail';

    if ($this->session->flashdata('download')) {
      $download = $this->session->flashdata('download');
      $this->request = $download->data->request;
      $responseAttr = $download;
    }

    if (isset($this->request->orderList)) {
      $orderList = json_decode(base64_decode($this->request->orderList));
      $orderList = json_decode($this->cryptography->decrypt(
        base64_decode($orderList->plot),
        utf8_encode($orderList->password)
      ));
      $this->session->set_flashdata('serviceOrdersList', $orderList);
      unset($this->request->orderList);
    }

    if (isset($this->request->nonBillable)) {
      $nonBillable = json_decode(base64_decode($this->request->nonBillable));
      $nonBillable = json_decode($this->cryptography->decrypt(
        base64_decode($nonBillable->plot),
        utf8_encode($nonBillable->password)
      ));
      $this->session->set_flashdata('bulkNotBillable', $nonBillable);
      unset($this->request->nonBillable);
    }

    $response = $this->loadModel($this->request);

    if (!$download) {
      $responseAttr = $response;
    }

    $this->responseAttr($responseAttr);
    array_push(
      $this->includeAssets->cssFiles,
      "third_party/dataTables-1.10.20"
    );
    array_push(
      $this->includeAssets->jsFiles,
      "third_party/dataTables-1.10.20",
      "inquiries/bulkDetail"
    );

    foreach ($response->data->bulkInfo as $row => $info) {
      $this->render->$row = $info;
    }

    $this->render->titlePage = lang('GEN_DETAIL_BULK_TITLE');
    $this->render->function = $this->request->bulkfunction;
    $this->views = ['inquiries/' . $view];
    $this->loadView($view);
  }
}
