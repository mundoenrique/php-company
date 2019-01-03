<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class vehicles_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        //Add languages
        $this->lang->load('dashboard');
        $this->lang->load('combustible');
        $this->lang->load('users');
        $this->lang->load('erroreseol');
    }
    /*---Fin método constructor---------------------------------------------------------------------------------------*/


    /*---Métodos para vehículos---------------------------------------------------------------------------------------*/
    //Método para obtener la lista de vehículos o el detallle de un vehículo
    public function callAPIvehicles($urlCountry, $dataRequest)
    {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        $action = $dataRequest[0]['action'];
        $idFlota = $dataRequest[0]['idFlota'];
        $idVehicle = $dataRequest[0]['idVehicle'];

        $urlAPI = $action === 'lista' ? 'fleet/' . $idFlota .'/vehicle' : 'fleet/' . $idFlota .'/vehicle/' . $idVehicle;
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'GET';

        $info = $action === 'lista' ? "LISTA DE VEHICULOS" : "DATOS DEL VEHICULO";
        log_message("INFO", "REQUEST " . $info . ": ===>>" . json_encode($headerAPI));

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE " . $info . ": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        $code = '';
        $data = [];
        $title = '';
        $lang = [
            'VEHI_PLATE' => lang('VEHI_PLATE'),
            'VEHI_BRAND' => lang('VEHI_BRAND'),
            'VEHI_MODEL' => lang('VEHI_MODEL'),
            'VEHI_YEAR' => lang('VEHI_YEAR'),
            'TAG_STATUS' => lang('TAG_STATUS'),
            'TAG_ACTION' => lang('TAG_ACTION'),
            'TAG_EDIT' => lang('TAG_EDIT'),
            'TAG_ACCEPT' => lang('TAG_ACCEPT'),
            'TAG_CANCEL' => lang('TAG_CANCEL'),
            'VEHI_ADD' => lang('VEHI_ADD'),
            'VEHI_EDIT' => lang('VEHI_EDIT'),
            'TAG_SEND' => lang('TAG_SEND'),
            'TAG_SAVE_CHANGES' => lang('TAG_SAVE_CHANGES'),
            'TAG_WITHOUT_CHANGES' => lang('TAG_WITHOUT_CHANGES'),
            'VEHI_CHANGE_STATUS' => lang('VEHI_CHANGE_STATUS'),
            'VEHI_CHANGE_MSG' => lang('VEHI_CHANGE_MSG'),
            'VEHI_DISASSOCIATE_OK' => lang('VEHI_DISASSOCIATE_OK')
        ];
        $dataResponse = json_decode($resAPI);

        switch ($httpCode) {
            case 200:

                $code = 0;
                if ($action === 'lista') {
                    foreach ($dataResponse->vehiculos as $vehicle => $row) {
                        $data[$vehicle]['idVehicle'] = $row->idVehiculo;
                        $data[$vehicle]['plate'] = strtoupper($row->matricula);
                        $data[$vehicle]['brand'] = strtoupper($row->marca);
                        $data[$vehicle]['model'] = strtoupper($row->modelo);
                        $data[$vehicle]['year'] = $row->anio;
                        $data[$vehicle]['status'] =  lang('VEHI_'.$row->idEstatus);
                    }
                    $title = 'lista';
                } else {
                    $data = [
                        'plate' => strtoupper($dataResponse->matricula),
                        'brand' => strtoupper($dataResponse->marca),
                        'model' => strtoupper($dataResponse->modelo),
                        'year' => $dataResponse->anio,
                        'odometer' => $dataResponse->odometro,
                        'capacity' => $dataResponse->combustibleMax,
                        'status' => $dataResponse->idEstatus
                    ];

                    $title = 'vehicle';
                }
                break;
            case 404:
                if ($action === 'lista') {
                    $code = 1;
                    $title = 'lista';
                } else {
                    $code = 2;
                    $title = lang('BREADCRUMB_VEHICLES');
                    $data = lang('VEHI_NON_EXISTS');
                }
                break;
            case 401:
                $code = 3;
                $title = lang('SYSTEM_NAME');
                $data = lang('ERROR_(-29)');
                break;
            default:
                $code = 3;
                $title = lang('SYSTEM_NAME');
                $data = lang('ERROR_GENERICO_USER');
        }

        if ($code === 3){
            $this->session->sess_destroy();
            $this->session->unset_userdata($this->session->all_userdata());
        }

        return $response = [
            'code' => $code,
            'title' => $title,
            'msg' => $data,
            'lang' => $lang,
        ];

    }

    //Método para cambiar el estado de uin vehículo
    public function callAPIchangeStatus($urlCountry, $dataRequest)
    {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        $idFlota = $dataRequest[0]['idFlota'];
        $idVehicle = $dataRequest[0]['idVehicle'];
        $status = $dataRequest[0]['status'];

        $urlAPI = 'fleet/' . $idFlota .'/vehicle/' . $idVehicle . '?status=' . strtolower($status);
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'PUT';

        $info = "CAMBIO DE ESTADO";
        log_message("INFO", "REQUEST " . $info . ": ===>>" . json_encode($headerAPI));

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE " . $info . ": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        $code = '';
        $data = [];
        $title = '';
        $lang = [];

        switch ($httpCode) {
            case 200:
                $code = 0;
                $data = lang('VEHI_CHANGE_STATUS_OK');
                break;
            case 404:
                $code = 2;
                $title = lang('BREADCRUMB_COMBUSTIBLE');
                $data = lang('ERROR_(-39)');
                break;
            case 401:
                $code = 3;
                $title = lang('SYSTEM_NAME');
                $data = lang('ERROR_(-29)');
                break;
            default:
                $code = 3;
                $title = lang('SYSTEM_NAME');
                $data = lang('ERROR_GENERICO_USER');
        }

        if ($code === 3){
            $this->session->sess_destroy();
            $this->session->unset_userdata($this->session->all_userdata());
        }

        return $response = [
            'code' => $code,
            'title' => $title,
            'msg' => $data,
            'lang' => $lang,
        ];

    }

    public function callAPIaddEditVehicles($urlCountry, $dataRequest)
    {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        //Convierte en objeto php datos serializados del formulario
        parse_str($dataRequest, $dataVehicle);
        //Indica si se registra o edita un vehículo
        $action = $dataVehicle['func'];
        //variables para respuesta a la vista
        $msgOK = lang('VEHI_DETAIL_OK');
        $msgFail = lang('VEHI_DETAIL_FAIL');
        if ($action === 'register') {
            $msgOK = lang('VEHI_REGISTER_OK');
            $msgFail = lang('VEHI_REGISTER_FAIL');
        }

        $body = [
            'matricula' => isset($dataVehicle['plate']) ? strtoupper($dataVehicle['plate']) : '',
            'modelo' => isset($dataVehicle['model']) ? strtoupper($dataVehicle['model']) : '',
            'combustibleMax' => isset($dataVehicle['capacity']) ? $dataVehicle['capacity'] : '',
            'marca' => isset($dataVehicle['brand']) ? strtoupper($dataVehicle['brand']) : '',
            'anio' => isset($dataVehicle['year']) ? $dataVehicle['year']: '',
            'odometro' => isset($dataVehicle['odometer']) ? $dataVehicle['odometer'] : ''
        ];

        $InserVehi = 'fleet/' . $dataVehicle['idFlota'] . '/vehicle';

        $urlAPI = $action === 'register' ? $InserVehi : $InserVehi . '/update/' . $dataVehicle['idVehicle'];
        $headerAPI = $header;
        $bodyAPI = json_encode($body);
        $method = $action ==='register' ? 'POST' : 'PUT';

        $info = $action ==='register' ? 'REGISTRAR VEHICULO' : 'ACTUALIZAR VEHICULO';
        log_message("INFO", "REQUEST " . $info . ": ===>>" . json_encode($headerAPI) . ' BODY==>> ' . $bodyAPI);

        //llamado al API
         $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

         $httpCode = $jsonResponse->httpCode;
         $resAPI = $jsonResponse->resAPI;

         log_message("INFO", "RESPONSE " . $info . ": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

         $code = '';
         $data = [];
         $title = '';
         $lang = [];

         switch ($httpCode) {
             case 200:
             case 201:
                 $code = 0;
                 $data = $msgOK;
                 break;
             case 406:
                $code = 1;
                $data = lang('VEHI_PLATE_EXIST');
                break;
             case 404:
                 $code = 2;
                 $title = lang('BREADCRUMB_VEHICLES');
                 $data = lang('VEHI_NON_EXISTS');
                 break;
             case 401:
                 $code = 3;
                 $title = lang('SYSTEM_NAME');
                 $data = lang('ERROR_(-29)');
                 break;
             default:
                 $code = 3;
                 $title = lang('SYSTEM_NAME');
                 $data = lang('ERROR_GENERICO_USER');
         }

         if ($code === 3){
             $this->session->sess_destroy();
             $this->session->unset_userdata($this->session->all_userdata());
         }

         return $response = [
             'code' => $code,
             'title' => $title,
             'msg' => $data,
             'lang' => $lang
         ];
    }

    public function callAPIstatusVehicle ($urlCountry)
    {
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token
        ];

        $urlAPI = 'fleet/vehicle/status';
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'GET';

        $info = "LISTA DE ESTADOS DE LOS VEHICULOS";
        log_message("INFO", "REQUEST " . $info . ": ===>>" . json_encode($headerAPI));

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE " . $info . ": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        if ($httpCode == 200) {
            $dataResponse = json_decode($resAPI);
            $data = [];
            foreach ($dataResponse->status as $status => $row){
                $data[$status]['id'] = $row->nombre;
                $data[$status]['value'] = lang('VEHI_'.$row->nombre);
            }
        } else {
            $data = [
              [
                  'id' => 'ACTIVE',
                  'value' => lang('VEHI_ACTIVE')
              ],
              [
                  'id' => 'GARAGE',
                  'value' => lang('VEHI_GARAGE')
              ],
              [
                  'id' => 'DISASSOCIATE',
                  'value' => lang('VEHI_DISASSOCIATE')
              ],
            ];
        }

        return $data;

    }

}
