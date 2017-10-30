<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class vehicleGroups_Model extends CI_Model {

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

    /*---Métodos para grupos de vehículos-----------------------------------------------------------------------------*/
    //Método para obtener la lista de grupos de vehículos o el detallle de un grupo
    public function callAPIvehicleGroups($urlCountry, $dataRequest='')
    {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        $urlAPI = empty($dataRequest) ? 'fleet/company' : 'fleet/' . $dataRequest;
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'GET';

        $info = empty($dataRequest) ? "LISTA DE GRUPOS" : "DATOS DEL GRUPO";
        log_message("INFO", "REQUEST " . $info . ": ===>>" . json_encode($headerAPI));

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE " . $info . ": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        $dataResponse = json_decode($resAPI);
        switch ($httpCode) {
            case 200:
                $data = [];
                if (empty($dataRequest)) {
                    foreach ($dataResponse->fleets as $item => $key) {

                        $data[$item]['idFleet'] = $key->idFlota;
                        $data[$item]['name'] = $key->nombre;
                        $data[$item]['description'] = ucfirst(strtolower($key->descripcion));;
                        $data[$item]['status'] =  $key->estatus;
                    }
                    $response = [
                        'code' => 0,
                        'msg' => $data,
                        'lang' => [
                            'TAG_NAME' => lang('TAG_NAME'),
                            'TAG_DESCRIPTION' => lang('TAG_DESCRIPTION'),
                            'TAG_STATUS' => lang('TAG_STATUS'),
                            'TAG_ACTION' => lang('TAG_ACTION'),
                            'TAG_EDIT' => lang('TAG_EDIT'),
                            'GROUP_VIEW_VEHICLES' => lang('GROUP_VIEW_VEHICLES'),
                            'TAG_SEND' => lang('TAG_SEND'),
                            'TAG_SAVE_CHANGES' => lang('TAG_SAVE_CHANGES'),
                            'GROUP_ADD' => lang('GROUP_ADD'),
                            'GROUP_EDIT' => lang('GROUP_EDIT'),
                            'TAG_WITHOUT_CHANGES' => lang('TAG_WITHOUT_CHANGES'),
                            'TAG_ACTIVE' => lang('TAG_ACTIVE'),
                            'TAG_INACTIVE' => lang('TAG_ACTIVE'),
                            'TAG_ACCEPT' => lang('TAG_ACCEPT')
                        ]
                    ];
                } else {
                    $response = [
                        'code' => 0,
                        'msg' => [
                            'name' => ucfirst(strtolower($dataResponse->nombre)),
                            'description' => ucfirst(strtolower($dataResponse->descripcion)),
                            'idFeet' => $dataResponse->idFlota,
                            'status' => $dataResponse->estatus
                        ]
                    ];
                }
                break;
            case 404:
                if (empty($dataRequest)) {
                    $response = [
                        'code' => 1,
                        'msg' => [],
                        'lang' => [
                            'TAG_NAME' => lang('TAG_NAME'),
                            'TAG_DESCRIPTION' => lang('TAG_DESCRIPTION'),
                            'TAG_STATUS' => lang('TAG_STATUS'),
                            'TAG_ACTION' => lang('TAG_ACTION'),
                            'TAG_EDIT' => lang('TAG_EDIT'),
                            'GROUP_VIEW_VEHICLES' => lang('GROUP_VIEW_VEHICLES'),
                            'TAG_SEND' => lang('TAG_SEND'),
                            'TAG_SAVE_CHANGES' => lang('TAG_SAVE_CHANGES'),
                            'GROUP_ADD' => lang('GROUP_ADD'),
                            'GROUP_EDIT' => lang('GROUP_EDIT'),
                            'TAG_WITHOUT_CHANGES' => lang('TAG_WITHOUT_CHANGES'),
                            'TAG_ACTIVE' => lang('TAG_ACTIVE'),
                            'TAG_INACTIVE' => lang('TAG_ACTIVE'),
                            'TAG_ACCEPT' => lang('TAG_ACCEPT')
                        ]
                    ];
                } else {
                    $response = [
                        'code' => 1,
                        'title' => lang('BREADCRUMB_COMBUSTIBLE'),
                        'msg' => lang('ERROR_NON_FOUND')
                    ];
                }
                break;
            case 401:
                $response = [
                    'code' => 3,
                    'title' => lang('SYSTEM_NAME'),
                    'msg' => lang('ERROR_(-29)'),
                    'lang' => [
                        'TAG_ACCEPT' => lang('TAG_ACCEPT')
                    ]
                ];
                $this->session->sess_destroy();
                $this->session->unset_userdata($this->session->all_userdata());
                break;
            default:
                $response = [
                    'code' => 3,
                    'title' => lang('SYSTEM_NAME'),
                    'msg' => lang('ERROR_GENERICO_USER'),
                    'lang' => [
                        'TAG_ACCEPT' => lang('TAG_ACCEPT')
                    ]
                ];
                $this->session->sess_destroy();
                $this->session->unset_userdata($this->session->all_userdata());
        }

        return $response;
    }

    //Método para registrar o actulizar un grupo
    public function callAPIaddEditGroups ($urlCountry, $dataRequest)
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
        parse_str($dataRequest, $dataGroup);

        //obtiene el id del grupo de vehículos
        $idFlota = isset($dataGroup['idFlota']) ? $dataGroup['idFlota'] : null;

        //Indica si se registra o se actuliza un grupo
        $action = $dataGroup['func'];

        //cuerpo del API
        $body = [
            'nombre' => ($dataGroup['nameGroup']) ? trim($dataGroup['nameGroup']) : "",
            'descripcion' => ($dataGroup['desc']) ? trim($dataGroup['desc']) : ""
        ];

        $urlAPI = $action === 'register' ? 'fleet/' : 'fleet/' . $idFlota;
        $headerAPI = $header;
        $bodyAPI = json_encode($body);
        $method = $action === 'register' ? 'POST' : 'PUT';

        $info = $action === 'register' ? "AGREGAR UN GRUPO" : "ACTUALIZAR GRUPO";

        log_message("INFO", "REQUEST $info: ===>>" . json_encode($headerAPI) . " " . $bodyAPI);

        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE " . $info . ": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        switch ($httpCode) {
            case 200:
                $response = [
                    'code' => 0,
                    'msg' => lang('GROUP_UPDATED')
                ];
                break;
            case 201:
                $response = [
                    'code' => 0,
                    'msg' => lang('GROUP_REGISTERED')
                ];
                break;
            case 404:
                $response = [
                    'code' => 0,
                    'msg' => lang('GROUP_NON_EXISTS')
                ];
                break;
            case 406:
                $response = [
                    'code' => 1,
                    'msg' => lang('GROUP_NAME_EXIST')
                ];
                break;
            case 204:
            case 400:
                $response = [
                    'code' => 2,
                    'title' => lang('BREADCRUMB_COMBUSTIBLE'),
                    'msg' => lang('ERROR_(-39)')
                ];
                break;
            case 401:
                $response = [
                    'code' => 3,
                    'title' => lang('SYSTEM_NAME'),
                    'msg' => lang('ERROR_(-29)')
                ];
                $this->session->sess_destroy();
                $this->session->unset_userdata($this->session->all_userdata());
                break;
            default:
                $response = [
                    'code' => 3,
                    'title' => lang('SYSTEM_NAME'),
                    'msg' => lang('ERROR_GENERICO_USER')
                ];
                $this->session->sess_destroy();
                $this->session->unset_userdata($this->session->all_userdata());
        }

        return $response;

    }
    /*---Fin métodos para grupos de vehículos-------------------------------------------------------------------------*/
}
