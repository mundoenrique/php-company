<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class travels_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        //Add languages
        $this->lang->load('dashboard');
        $this->lang->load('combustible');
        $this->lang->load('erroreseol');
    }
    /*---Fin método constructor-----------------------------------------------*/

    /*---Métodos para viajes--------------------------------------------------*/
    //Método para obtener la lista de viajes
    public function callAPItravels($urlCountry, $dataRequest)
    {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        $typeList = json_decode($dataRequest);

        $info = 'LISTA DE VIAJES POR ';
        $urlAPI = 'travel?';

        $beginDate ='';
        $finalDate='';
        if(isset($typeList->beginDate) && isset($typeList->finalDate)) {
            $date = explode('/', $typeList->beginDate);
            $beginDate = $date[2] . '-' . $date[1] . '-' . $date[0];
            $date = explode('/', $typeList->finalDate);
            $finalDate = $date[2] . '-' . $date[1] . '-' . $date[0];
        }

        if ($typeList->type === 'count') {
            $info .= 'ULTIMOS 30';
            $urlAPI .= 'quantity=30';
        } else {
            switch($typeList->type) {
                case 'vehicles':
                case 'drivers':
                case 'statusId':
                    $inf = [
                        'vehicles'  => 'VEHICULOS',
                        'drivers' => 'CONDUCTORES',
                        'statusId' => 'ESTADO'
                    ];
                    $info .= $inf[$typeList->type];

                    $filter = [
                        'vehicles'  => 'vehicleReg',
                        'drivers' => 'driver',
                        'statusId' => 'status'
                    ];

                    $param = $typeList->type === 'vehicles' ? $typeList->plate : $typeList->option;

                    $urlAPI .= $filter[$typeList->type] . '=' . strtoupper($param) . '&from=' . $beginDate
                        . '&to=' . $finalDate;
                    break;
                case 'date':
                    $info .= 'FECHA';
                    $urlAPI .= 'from=' . $beginDate . '&to=' . $finalDate;
                    break;
                default:
                    $info .= 'ULTIMOS 30';
                    $urlAPI .= 'quantity=30';
            }
        }

        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'GET';

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
            'TAG_STATUS' => lang('TAG_STATUS'),
            'TAG_ACTION' => lang('TAG_ACTION'),
            'TAG_EDIT' => lang('TAG_EDIT'),
            'TAG_ACCEPT' => lang('TAG_ACCEPT'),
            'TAG_CANCEL' => lang('TAG_CANCEL'),
            'TRAVELS_VIEW' => lang('TRAVELS_VIEW'),
            'TAG_SEND' => lang('TAG_SEND'),
            'TAG_SAVE_CHANGES' => lang('TAG_SAVE_CHANGES'),
            'TAG_WITHOUT_CHANGES' => lang('TAG_WITHOUT_CHANGES'),
            'TRAVEL_START_DATE' => lang('TRAVEL_START_DATE'),
            'TRAVEL_END_DATE' => lang('TRAVEL_END_DATE'),
            'TRAVEL_ORIGIN' => lang('TRAVEL_ORIGIN'),
            'TRAVEL_DESTINATION' => lang('TRAVEL_DESTINATION'),
            'TRAVEL_VEHICLE' => lang('TRAVEL_VEHICLE'),
            'TRAVEL_DRIVER' => lang('TRAVEL_DRIVER'),
            'TRAVELS_LOAD' => lang('TRAVELS_LOAD'),
            'TRAVELS_SELECT' => lang('TRAVELS_SELECT'),
            'TRAVELS_SELECT_DRIVER' => lang('TRAVELS_SELECT_DRIVER'),
            'TRAVELS_SELECT_STATUS' => lang('TRAVELS_SELECT_STATUS'),
            'TRAVELS_IN_PLATE' => lang('TRAVELS_IN_PLATE'),
            'TRAVELS_LABEL_SEARCH' => lang('TRAVELS_LABEL_SEARCH'),
            'TRAVELS_LABEL_IN' => lang('TRAVELS_LABEL_IN')
        ];
        $dataResponse = json_decode($resAPI);
        switch ($httpCode) {
            case 200:
                $code = 0;
                $title = 'lista';
                foreach ($dataResponse->viajes as $travelObject => $row) {
                    $data[$travelObject]['idTravel'] = $row->idViaje;
                    $date = new DateTime($row->fechaInicio);
                    $data[$travelObject]['startDate'] = $date->format('d/m/Y H:i:s');
                    $date = new DateTime($row->fechaFin);
                    $data[$travelObject]['endDate'] = $date->format('d/m/Y H:i:s');
                    $data[$travelObject]['origin'] = substr($row->origen, 0, 55);
                    $data[$travelObject]['destination'] = substr($row->destino, 0, 55);
                    $data[$travelObject]['vehicle'] = strtoupper($row->vehiculo->modelo) . ' - ' . strtoupper($row->vehiculo->matricula);
                    $data[$travelObject]['driver'] = strtolower($row->conductor->login);
                    $data[$travelObject]['status'] = lang('TRAVELS_' . $row->estatus);
                }
                break;
            case 404:
                $code = 1;
                $title = 'lista';
                break;
            case 400:
                $msg = $dataResponse->rc;
                $code = 2;
                $title = lang('BREADCRUMB_TRAVELS');
                if ($msg == -20 || $msg == -21) {
                    $msg = lang('TRAVELS_ERROR_DATE');
                } else {
                    $msg = lang('ERROR_(-39)');
                }
                $data = $msg;
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

    //Método para obtener el detalle de un viaje
    public function callAPItravelDetail($urlCountry, $dataRequest)
    {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        $travelID = json_decode($dataRequest);

        $info = 'DETALLE DE UN VIAJE ';
        $urlAPI = 'travel/' . $travelID;
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'GET';

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
            'TRAVELS_CANCEL' => lang('TRAVELS_CANCEL'),
            'TAG_ACCEPT' => lang('TAG_ACCEPT')
        ];
        $dataResponse = json_decode($resAPI);
        switch ($httpCode) {
            case 200:
                $code = 0;
                $title = 'detail';
                $date = new DateTime($dataResponse->fechaInicio);
                $data['beginDate'] =  $date->format('d/m/Y H:i:s');
                $date = new DateTime($dataResponse->fechaFin);
                $data['finalDate'] =  $date->format('d/m/Y H:i:s');
                $data['driver'] = ucfirst($dataResponse->conductor->primerNombre) . ' ' . ucfirst($dataResponse->conductor->primerApellido) . ' - ' . $dataResponse->conductor->id_ext_per;
                $data['vehicle'] = strtoupper($dataResponse->vehiculo->modelo) . ' - ' . strtoupper($dataResponse->vehiculo->matricula);
                $data['origin'] = $dataResponse->origen;
                $data['orgL'] = $dataResponse->posicionOrigen->latitud . ',' . $dataResponse->posicionOrigen->longitud;
                $data['destination'] = $dataResponse->destino;
                $data['desL'] = $dataResponse->posicionDestino->latitud . ',' . $dataResponse->posicionDestino->longitud;
                $data['status'] = $dataResponse->estatus;
                $data['travelID'] = $dataResponse->idViaje;
                break;
            case 404:
                $code = 2;
                $title = lang('BREADCRUMB_TRAVELS');
                $data = lang('TRAVELS_NON_EXISTS');
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

    //Método para obtener lita de conductores, estado,
    public function callAPIgetList($urlCountry, $dataRequest)
    {
        $dataRequest;
        $getModel = [
            'drivers' => 'driver',
            'statusId' => 'travels'
        ];

        $getMethod = [
            'drivers' => 'callAPIdrivers',
            'statusId' => 'callAPIstatusTravels'
        ];

        $model = $getModel[$dataRequest];
        $method = $getMethod[$dataRequest];

        $this->load->model($model . '_model', 'listado');

        $responseList = $this->listado->$method($urlCountry);

        $listObject = '';
        $val = '';
        $text = '';
        $dni = '';
        $code = 0;
        $title ='';

        switch ($dataRequest) {
            case 'drivers':
                $code = $responseList['code'];
                $title = $responseList['title'];
                $listObject = $responseList['msg'];
                $val = 'userName';
                $text = 'nombreCompleto';
                $dni = 'id_ext_per';
                break;
            case 'statusId':
                $listObject = $responseList['msg'];
                $val = 'id';
                $text = 'value';
                break;
        }

        $data = [];
        if($code == 0) {
            foreach ($listObject as $items => $value) {
                $data[$items]['val'] = $value[$val];
                if ($dataRequest === 'drivers') {
                    $data[$items]['text'] = $value[$text] . ' - ' . $value[$dni];
                } else {
                    $data[$items]['text'] = $value[$text];
                }

            }
        } else {
            $data = $responseList['msg'];
        }

        return $response = [
            'code' => $code,
            'title' => $title,
            'msg' => $data
        ];
    }

    //Obtener estado de los viajes
    public function callAPIstatusTravels($urlCountry)
    {
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token
        ];

        $urlAPI = 'travel/status';
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'GET';

        $info = "LISTA DE ESTADOS DE LOS VIAJES";
        log_message("INFO", "REQUEST " . $info . ": ===>>" . json_encode($headerAPI));

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE " . $info . ": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        if ($httpCode == 200) {
            $dataResponse = json_decode($resAPI);
            $data = [];
            foreach ($dataResponse->status as $status => $row) {
                $data[$status]['id'] = $row->nombre;
                $data[$status]['value'] = lang('TRAVELS_'.$row->nombre);
            }

        } else {
            $data = [
                [
                    'id' => 'PRECREATED',
                    'value' => lang('TRAVELSI_ACTIVE')
                ],
                [
                    'id' => 'CREATED',
                    'value' => lang('TRAVELS_GARAGE')
                ],
                [
                    'id' => 'STARTED',
                    'value' => lang('TRAVELS_DISASSOCIATE')
                ],
                [
                    'id' => 'FINISHED',
                    'value' => lang('TRAVELS_DISASSOCIATE')
                ],
                [
                    'id' => 'CANCELLED',
                    'value' => lang('TRAVELS_DISASSOCIATE')
                ],
            ];
        }

        return $response = [
            'code' => 0,
            'title' => '',
            'msg' => $data
        ];

    }

    //obtener listado de conductores y vehículos disponobles
    public function callAPIgetDrivVehi($urlCountry, $dataRequest)
    {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        $data = json_decode($dataRequest);
        $date = new DateTime(str_replace('/', '-', $data->firstDate));
        $beginDate = $date->format('Y-m-d');
        $date = new DateTime(str_replace('/', '-', $data->lastDate));
        $finalDate = $date->format('Y-m-d');

        $urlAPI = 'travel/driver/available?from=' . $beginDate . '&to=' . $finalDate;
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'GET';

        $info = "CONDUCTORES DISPONIBLES PARA VIAJE";
        log_message("INFO", "REQUEST " . $info . ": ===>>" . json_encode($headerAPI));

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE " . $info . ": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        $listDrivers = '';
        $listVehicles = '';
        $nonAvailable = '';
        if($httpCode == 200) {
            $driversObject = json_decode($resAPI);
            foreach ($driversObject->listaUsuarios as $drivers => $value) {
                $listDrivers[$drivers]['user'] = strtolower($value->userName);
                $listDrivers[$drivers]['driver'] = ucfirst($value->primerNombre) . ' ' . ucfirst($value->primerApellido)
                    . ' - ' . $value->id_ext_per;
            }
        } elseif($httpCode == 404) {
            $nonAvailable = lang('TRAVELS_NON_DRIVER');
        }

        $urlAPI = 'travel/vehicle/available?from=' . $beginDate . '&to=' . $finalDate;

        $info = "VEHICULOS DISPONIBLES PARA VIAJE";
        log_message("INFO", "REQUEST " . $info . ": ===>>" . json_encode($headerAPI));

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE " . $info . ": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        if($httpCode == 200) {
            $vehiclesObject = json_decode($resAPI);
            foreach ($vehiclesObject->vehiculos as $vehicles => $value) {
                $listVehicles[$vehicles]['idVehicle'] = $value->idVehiculo;
                $listVehicles[$vehicles]['vehicle'] = strtoupper($value->modelo) . ' - ' . strtoupper($value->matricula);
            }
        } elseif($httpCode == 404) {
            $nonAvailable = lang('TRAVELS_NON_VEHICLE');
        }

        $code = '';
        $data = [];
        $title = '';
        $lang = [
            'TAG_SEND' => lang('TAG_SEND'),
            'TAG_END' => lang('TAG_END'),
            'TAG_RETURN' => lang('TAG_RETURN'),
            'TRAVELS_SELECT' => lang('TRAVELS_SELECT'),
            'TRAVELS_HIDE_INFO' => lang('TRAVELS_HIDE_INFO'),
            'TRAVELS_VIEW_INFO' => lang('TRAVELS_VIEW_INFO'),
            'TAG_FOLLOW' => lang('TAG_FOLLOW'),
            'TAG_CLEAR_FORM' => lang('TAG_CLEAR_FORM'),
            'TAG_ACCEPT' => lang('TAG_ACCEPT')
        ];

        if($listDrivers == '' || $listVehicles == '') {
            $httpCode = 404;
        }
        switch ($httpCode) {
            case 200:
                $code = 0;
                $title = 'list';
                $data = [
                    'driverList' => $listDrivers,
                    'vehiclesList' => $listVehicles
                ];
                break;
            case 404:
                $code = 2;
                $title = lang('BREADCRUMB_TRAVELS');
                if($listDrivers == '' && $listVehicles == '') {
                    $nonAvailable = lang('TRAVELS_NON_DRIV_VEHI');
                }
                $data = $nonAvailable;
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

    //Agregar viaje
    public function callAPIaddTravel ($urlCountry, $dataRequest)
    {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        $travel = json_decode($dataRequest);
        $date = new DateTime(str_replace('/', '-', $travel->firstDate));
        //Fecha de inicio
        $beginDate = $date->format('Y-m-d H:i:s');
        $date = new DateTime(str_replace('/', '-', $travel->lastDate));
        //Fecha de final
        $finalDate = $date->format('Y-m-d H:i:s');
        //Coordenadas origen
        $coordinates = explode(',', $travel->pStart);
        $latitudeOrg = $coordinates[0];
        $longitudeOrg = $coordinates[1];
        //Coordenadas destino
        $coordinates = explode(',', $travel->pEnd);
        $latitudeDes = $coordinates[0];
        $longitudeDes = $coordinates[1];
        log_message("INFO", "RESPONSE de hora:---->>> ". $beginDate . " resAPI: " . $finalDate);

        $body = [
            'conductor' => [
                'login' => strtoupper($travel->driverId),
            ],
            'origen' => $travel->origin,
            'destino' => $travel->destination,
            'fechaInicio' => $beginDate,
            'fechaFin' => $finalDate,
            'tipoViaje' => '1',
            'posicionOrigen' => [
                'latitud' => $latitudeOrg,
                'longitud' => $longitudeOrg
            ],
            'posicionDestino' => [
                'latitud' => $latitudeDes,
                'longitud' => $longitudeDes
            ],
            'vehiculo' => [
                'idVehiculo' => $travel->vehicleId
            ]
        ];

        $urlAPI = 'travel';
        $headerAPI = $header;
        $bodyAPI = json_encode($body);
        $method = 'POST';

        $info = "CREAR VIAJE";
        log_message("INFO", "REQUEST " . $info . ": ===>>" . json_encode($headerAPI) . " " . $bodyAPI);

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE " . $info . ": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        $code = '';
        $data = [];
        $title = '';
        $lang = [
            'TAG_ACCEPT' => lang('TAG_ACCEPT'),
            'BREADCRUMB_TRAVELS' => lang('BREADCRUMB_TRAVELS')
        ];
        switch ($httpCode) {
            case 201:
                $code = 0;
                $title = 'created';
                $data = lang('TRAVELS_WAS_CREATE');
                break;
            case 400:
                $code = 1;
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

    //Cancelar un viaje
    public function callAPIcancelTravel ($urlCountry, $dataRequest)
    {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        $travelID = json_decode($dataRequest);

        $urlAPI = 'travel/' . $travelID . '?status=cancelled';
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'PUT';

        $info = "CANCELAR UN VIAJE";
        log_message("INFO", "REQUEST " . $info . ": ===>>" . json_encode($headerAPI) . " " . $bodyAPI);

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE " . $info . ": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        $code = '';
        $data = [];
        $title = '';
        $lang = [
            'TAG_ACCEPT' => lang('TAG_ACCEPT'),
            'BREADCRUMB_TRAVELS' => lang('BREADCRUMB_TRAVELS')
        ];
        switch ($httpCode) {
            case 200:
                $code = 0;
                $title = 'cancelled';
                $data = lang('TRAVELS_WAS_CANCEL');
                break;
            case 400:
                $code = 1;
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

        if ($code === 3) {
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

}
