<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class driver_model extends CI_Model
{

    //Método constructor
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

    /*---Métodos para conductores constructor-------------------------------------------------------------------------*/

    //Método para obtener la lista de conductores o el perfil de un conductor
    public function callAPIdrivers($urlCountry, $dataRequest='')
    {

        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        $urlAPI = empty($dataRequest) ? 'driver' : 'driver/' . strtoupper($dataRequest);
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'GET';

        $info = empty($dataRequest) ? "LISTA DE CONDUCTORES" : "PERIL DEL CONDUCTOR";
        log_message("INFO", "REQUEST " . $info . ": ===>>" . json_encode($headerAPI));

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE " . $info . ": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);
        switch ($httpCode) {
            case 200 :
                $dataResponse = json_decode($resAPI);
                $data = [];
                if (empty($dataRequest)) {
                    foreach ($dataResponse->lista as $item => $row) {

                        $data[$item]['userName'] = strtolower($row->userName);
                        $data[$item]['estatus'] = $row->estatus;
                        $data[$item]['nombreCompleto'] = ucwords(strtolower($row->primerNombre)) . ' ' . ucwords(strtolower($row->primerApellido));
                        $data[$item]['sexo'] = $row->sexo;
                        $data[$item]['id_ext_per'] = $row->id_ext_per;
                    }

                    $response = [
                        'code' => 0,
                        'title' => '',
                        'msg' => $data,
                        'language' => [
                            'DRIVER_SEARCH_DNI' => lang('DRIVER_SEARCH_DNI'),
                            'TAG_ACCEPT' => lang('TAG_ACCEPT'),
                            'TAG_SEND' => lang('TAG_SEND'),
                            'TAG_ADD' => lang('MENU_ADD'),
                            'TAG_CANCEL' => lang('TAG_CANCEL'),
                            'DRIVER_INCLUDE' => lang('DRIVER_INCLUDE')
                        ]
                    ];
                } else {
                    $response = [
                        'code' => 0,
                        'msg' => [
                            'id_ext_per' => $dataResponse->user->id_ext_per,
                            'fechaNacimiento' => $dataResponse->user->fechaNacimiento,
                            'primerNombre' => ucwords(strtolower($dataResponse->user->primerNombre)),
                            'segundoNombre' => ucwords(strtolower($dataResponse->user->segundoNombre)),
                            'primerApellido' => ucwords(strtolower($dataResponse->user->primerApellido)),
                            'segundoApellido' => ucwords(strtolower($dataResponse->user->segundoApellido)),
                            'sexo' => $dataResponse->user->sexo,
                            'userName' => $dataResponse->user->userName,
                            'email' => $dataResponse->user->email,
                            'numero' => $dataResponse->listaTelefonos[0]->numero,
                            'estatusConductor' => $dataResponse->user->estatusConductor
                        ]
                    ];
                }
                break;
            case 404:
                if (empty($dataRequest)) {
                    $response = [
                        'code' => 1,
                        'msg' => [],
                        'language' => [
                            'DRIVER_SEARCH_DNI' => lang('DRIVER_SEARCH_DNI'),
                            'TAG_ACCEPT' => lang('TAG_ACCEPT'),
                            'TAG_SEND' => lang('TAG_SEND'),
                            'TAG_ADD' => lang('MENU_ADD'),
                            'TAG_CANCEL' => lang('TAG_CANCEL'),
                            'DRIVER_INCLUDE' => lang('DRIVER_INCLUDE')
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
            case 400:
                    $response = [
                        'code' => 2,
                        'title' => lang('BREADCRUMB_COMBUSTIBLE'),
                        'msg' => lang('ERROR_(-39)'),
                        'language' => [
                            'TAG_ACCEPT' => lang('TAG_ACCEPT')
                        ]
                    ];
                break;
            case 401:
                $response = [
                    'code' => 3,
                    'title' => lang('SYSTEM_NAME'),
                    'msg' => lang('ERROR_(-29)'),
                    'language' => [
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
                    'language' => [
                        'TAG_ACCEPT' => lang('TAG_ACCEPT')
                    ]
                ];
                $this->session->sess_destroy();
                $this->session->unset_userdata($this->session->all_userdata());
        }

        return $response;

    }

    //Método para verificar si el conductor se encuentra registrado en CPO y asociarlo a la empresa
    public function callAPIcheckUSER ($urlCountry, $dataRequest)
    {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        //dni o username del conductor
        $driver = $dataRequest[0]['user'];
        //Nombre del conductor
        $name = isset($dataRequest[0]['name']) ? $dataRequest[0]['name'] : '';
        //Indica si se busca o asocia al conductor
        $action = $dataRequest[0]['action'];

        $urlAPI = $action == 'search' ? 'driver/dni/' . $driver : 'driver/' . $driver;
        $headerAPI = $header;
        $bodyAPI = '';
        $method = $action == 'search' ? 'GET' : 'POST';

        $info = $action == 'search' ? 'BUSCAR DNI': 'ASOCIAR CONDUCTOR';
        log_message("INFO", "REQUEST $info: ===>>" . json_encode($headerAPI));

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE $info: ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        //Arreglo para la respuesta a la vista
        $response = [];
        switch ($httpCode) {
            case 200:
                $dataResponse = json_decode($resAPI);
                $user = [
                    'name' => ucwords(strtolower($dataResponse->user->primerNombre)) . ' ' . ucwords(strtolower($dataResponse->user->primerApellido)),
                    'user' => strtoupper($dataResponse->user->userName)
                ];
                $response = [
                    'code' => $dataResponse->user->isDriver,
                    'msg' => $user,
                    'language' => [
                        'DRIVER_ALREADY_REG' => lang('DRIVER_ALREADY_REG'),
                        'DRIVER_WISH_REG' => lang('DRIVER_WISH_REG'),
                        'DRIVER_LIST' => lang('DRIVER_LIST')
                    ]
                ];
                break;
            case 201:
                $response = [
                    'code' => 2,
                    'msg' => [
                        'driver' => $name
                    ],
                    'language' => [
                        'DRIVER_REGISTER_OK' => lang('DRIVER_REGISTER_OK')
                    ]
                ];
                break;
            case 404:
                $response = [
                    'code' => 3,
                    'msg' => [
                        'dni' => trim($driver)
                    ],
                    'language' => [
                        'DRIVER_NON_EXISTS' => lang('DRIVER_NON_EXISTS')
                    ]
                ];
                break;
            case 400:
                $response = [
                    'code' => 4,
                    'title' => lang('BREADCRUMB_COMBUSTIBLE'),
                    'msg' => lang('ERROR_(-39)'),
                    'language' => [
                        'TAG_ACCEPT' => lang('TAG_ACCEPT')
                    ]
                ];
                break;
            case 401:
                $response = [
                    'code' => 5,
                    'title' => lang('SYSTEM_NAME'),
                    'msg' => lang('ERROR_(-29)'),
                    'language' => [
                        'TAG_ACCEPT' => lang('TAG_ACCEPT')
                    ]
                ];
                break;
            default:
                $response = [
                    'code' => 5,
                    'title' => lang('SYSTEM_NAME'),
                    'msg' => lang('ERROR_GENERICO_USER'),
                    'language' => [
                        'TAG_ACCEPT' => lang('TAG_ACCEPT')
                    ]
                ];
        }

        if($response['code'] == 5) {
            $this->session->sess_destroy();
            $this->session->unset_userdata($this->session->all_userdata());
        }

        return $response;

    }

    //Método para registrar o actulizar un conductor
    public function callAPIaddEditDriver ($urlCountry, $dataRequest)
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
        parse_str($dataRequest, $dataDriver);

        //Indica si se registra o se actuliza un conductor
        $action = $dataDriver['function'];
        //variables para respuesta a la vista
        $msgOK = lang('DRIVER_PERFIL_OK');
        $msgFail = lang('DRIVER_PERFIL_FAIL');
        if ($action === 'register') {
            $msgOK = $dataDriver['name1'] . ' ' . $dataDriver['ape1'] . ', ' . lang('DRIVER_REGISTER_OK');
            $msgFail = lang('DRIVER_REGISTER_FAIL');
        }

        //cuerpo del API
        $body = [
            "user" => [
                "primerNombre" => isset($dataDriver['name1']) ? ucfirst(strtolower($dataDriver['name1'])) : "",
                "segundoNombre" => isset($dataDriver['name2']) ? ucfirst(strtolower($dataDriver['name2'])) : "",
                "primerApellido" => isset($dataDriver['ape1']) ? ucfirst(strtolower($dataDriver['ape1'])) : "",
                "segundoApellido" => isset($dataDriver['ape2']) ? ucfirst(strtolower($dataDriver['ape2'])) : "",
                "email" => isset($dataDriver['mail']) ? strtolower($dataDriver['mail']) : "",
                "sexo" => isset($dataDriver['sex']) ? $dataDriver['sex'] : "",
                "userName" => isset($dataDriver['user']) ? strtoupper($dataDriver['user']) : "",
                "fechaNacimiento" => isset($dataDriver['birthDay']) ? $dataDriver['birthDay'] : "",
                "codPais" => $urlCountry,
                "id_ext_per" => isset($dataDriver['dniDriver']) ? $dataDriver['dniDriver'] : ""
            ],
            "listaTelefonos" => [
                [
                    "tipo" => "CEL",
                    "numero" => isset($dataDriver['telf_mov']) ? $dataDriver['telf_mov'] : "",
                    "codPais" => $urlCountry
                ]
            ],
            "pais" => $urlCountry
        ];

        $urlAPI = $action === 'register' ? 'driver' : 'driver/update/' . strtoupper($dataDriver['user']);
        $headerAPI = $header;
        $bodyAPI = json_encode($body);
        $method = ($action == 'register') ? 'POST' : 'PUT';

        $info = $action == 'register' ? "AGREGAR CONDUCTOR" : "ACTUALIZAR CONDUCTOR";
        log_message("INFO", "REQUEST $info: ===>>" . json_encode($headerAPI) . " " . $bodyAPI);

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE $info: ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        $dataResponse = json_decode($resAPI);
        switch ($httpCode) {
            case 200:
            case 201:
                if ($dataResponse) {
                    $response = [
                        'code' => 0,
                        'msg' => $msgOK,
                        'back' => $httpCode == 201 ? 'b' : 'u',
                        'lang' => [
                            'TAG_ACCEPT' => lang('TAG_ACCEPT')
                        ]
                    ];
                } else {
                    $response = [
                        'code' => 1,
                        'msg' => $msgFail,
                        'lang' => [
                            'TAG_ACCEPT' => lang('TAG_ACCEPT')
                        ]
                    ];
                }
                break;
            case 404:
                $response = [
                    'code' => 1,
                    'msg' => lang('DRIVER_NON_EXISTS'),
                    'lang' => [
                        'TAG_ACCEPT' => lang('TAG_ACCEPT')
                    ]
                ];
                break;
            case 400:
                $rc = $dataResponse->rc;
                $codeError = [-181, -284, -193];
                $code = 2;
                $title = lang('BREADCRUMB_COMBUSTIBLE');
                $msg = lang('ERROR_(-39)');
                if(in_array($rc, $codeError)) {
                    $code = 0;
                    $title = '';
                    $msg = lang('ERROR_('.$rc.')');
                }

                $response = [
                    'code' => $code,
                    'title' => $title,
                    'msg' => $msg,
                    'back' => '',
                    'lang' => [
                        'TAG_ACCEPT' => lang('TAG_ACCEPT')
                    ]
                ];
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

    //Modelos de deshabilitar conductor
    public function callAPIdisabledDriver($urlCountry, $dataRequest)
    {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        //cabecera del REQUEST al API
        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        //estado del conductor
        $statusDriver = $dataRequest[0]['status'];
        $status = 'active';
        //varibles para mensajes a la vista
        $lang = lang('MENU_DISABLED_DRIVER');
        $msgOK = lang('DRIVER_WAS') . ' ';
        $msgFail = lang('DRIVER_DISABLED_FAIL') . ' ';
        if ($statusDriver == 1) {
            $status = 'inactive';
            $lang = lang('MENU_AVAILABLE_DRIVER');
            $msgOK .=  lang('DRIVER_DISABLED_OK');
            $msgFail .= lang('TAG_DISABLED') . ' ';
        } else {
            $msgOK .= lang('DRIVER_AVAILABLE_OK');
            $msgFail .= lang('TAG_AVAILABLE') . ' ';
        }

        $msgFail .= lang('DRIVER_DEFAULT');

        //Username del conductor
        $user = $dataRequest[0]['user'];

        $urlAPI = 'driver/' . strtoupper($user) . '?status=' . $status;
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'PUT';

        //llamado al API
        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE INHABILITAR CONDUCTOR: ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        switch ($httpCode) {
            case 200:
                $dataResponse = json_decode($resAPI);
                if ($dataResponse) {
                    $response = [
                        'code' => 0,
                        'msg' => $msgOK,
                        'button' => $lang,
                        'status' => $statusDriver == 1 ? 0 : 1,
                        'lang' => [
                            'TAG_ACCEPT' => lang('TAG_ACCEPT')
                        ]
                    ];
                } else {
                    $response = [
                        'code' => 1,
                        'msg' => $msgFail,
                        'lang' => [
                            'TAG_ACCEPT' => lang('TAG_ACCEPT')
                        ]
                    ];
                }
                break;
            case 404:
                $response = [
                    'code' => 1,
                    'msg' => lang('DRIVER_NON_EXISTS'),
                    'lang' => [
                        'TAG_ACCEPT' => lang('TAG_ACCEPT')
                    ]
                ];
                break;
            case 400:
                $response = [
                    'code' => 2,
                    'title' => lang('BREADCRUMB_COMBUSTIBLE'),
                    'msg' => lang('ERROR_(-39)'),
                    'lang' => [
                        'TAG_ACCEPT' => lang('TAG_ACCEPT')
                    ]
                ];
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
    /*---Fin métodos para conductores---------------------------------------------------------------------------------*/

}
