<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Libreria para la inccorporación y versionamiento de los archivos css, js e imágenes
 * @author J. Enrique Peñaloza Piñero
 *
 */
class Account_model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Account_model Model Class Initialized');
		$this->lang->load('dashboard');
		$this->lang->load('combustible');
		$this->lang->load('users');
		$this->lang->load('erroreseol');
	}
	public function callAPIaccounts($urlCountry, $dataRequest) {

        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');
        $prefix = $this->session->userdata('idProductoS');
        $detail = '';
        if($dataRequest == '' || $dataRequest == 'allocated'){
            $detail = 'account?status=allocated';
        }
        if($dataRequest == 'available'){
            $detail = 'account?status=available';
        }
        if($dataRequest != '' && $dataRequest != 'allocated' && $dataRequest != 'available'){
            log_message("INFO", "Numero de tarjeta" .": ==>> : " . $dataRequest );
            $detail = 'account/'.$dataRequest;
        }

        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc,
            'x-product:' . $prefix
        ];

        $urlAPI = $detail;
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'GET';

        log_message("INFO", "Listado de cuentas REQUEST : ===>>" . json_encode($headerAPI));

        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE CUENTAS" .": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        $response = [];
        $dataResponse = $resAPI;
				$response = $resAPI;
				$dataResponse = json_decode($resAPI);
        switch ($httpCode) {
            case 200:
								$response = [
									'resp' => $resAPI,
									'lang' => lang('TAG_ACCEPT')
								];
                if (empty($dataRequest)) {
                    $data = [];
                    $response = [
                        'code' => 0,
                        'msg' => $dataResponse,
                    ];
                }
                break;
            case 400:
                $code = 2;
                $title = lang( 'BREADCRUMB_COMBUSTIBLE' );
                $msg = lang( 'ERROR_(-39)' );

								$rc = $dataResponse->{'rc'};

                if( $rc == -238 || $rc == -241){
									$msg = lang('ERROR_(-39)');
                }

                $response = [
                  'code' => $code,
                  'title' => $title,
                  'msg' => $msg,
                  // 'back' => '',
                  'language' => [
                    'TAG_ACCEPT' => lang('TAG_ACCEPT')
                  ]
								];
								break;
            case 403:
                $code = 2;
                $title = lang( 'BREADCRUMB_COMBUSTIBLE' );
                $msg = lang( 'ERROR_(-900)' );

								$rc = $dataResponse->{'rc'};
								log_message('INFO', 'El RC es --->>> '.$rc);

                if( $rc == -197 || $rc == 8 || $rc == -307 || $rc == -900){
                  $msg = lang('ERROR_('.$rc.')');
                }

                $response = [
                  'code' => $code,
                  'title' => $title,
                  'msg' => $msg,
                  // 'back' => '',
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
                break;
            case 404:
                $response = [
                    'code' => 1,
                    'msg' => lang('ERROR_ACCOUNTS'),
                    'language' => [
                        'TAG_ACCEPT' => lang('TAG_ACCEPT')
                    ]
                ];
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
        }
        return $response;
    }

    public function callAPIdeallocateAccounts($urlCountry, $dataRequest) {

        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        $urlAPI = 'account/deallocate/'.$dataRequest;
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'PUT';

        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "RESPONSE DEVOLVER CUENTAS" .": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        $response = [];
        $dataResponse = $resAPI;
        $response = $resAPI;

        switch ($httpCode) {
            case 200:
                $dataResponse = json_decode($resAPI);
                if (empty($dataRequest)) {
                    $data = [];
                    $response = [
                        'code' => 0,
                        'msg' => $dataResponse,
                    ];
                }
                break;
            case 400:
                $code = 2;
                $title = lang( 'BREADCRUMB_COMBUSTIBLE' );
                $msg = lang( 'ERROR_(-39)' );

								$rc = $dataResponse->{'rc'};

                if( $rc == -238 || $rc == -241){
                  $msg = lang('ERROR_(-39)');
                }

                $response = [
                  'code' => $code,
                  'title' => $title,
                  'msg' => $msg,
                  // 'back' => '',
                  'language' => [
                    'TAG_ACCEPT' => lang('TAG_ACCEPT')
                  ]
								];
								break;
							case 403:
                $code = 2;
                $title = lang( 'BREADCRUMB_COMBUSTIBLE' );
                $msg = lang( 'ERROR_(-900)' );

								$rc = $dataResponse->{'rc'};
								log_message('INFO', 'El RC es --->>> '.$rc);

                if( $rc == -197 || $rc == 8 || $rc == -307 || $rc == -900){
                  $msg = lang('ERROR_('.$rc.')');
                }

                $response = [
                  'code' => $code,
                  'title' => $title,
                  'msg' => $msg,
                  // 'back' => '',
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
                break;
            case 404:
                $response = [
                    'code' => 1,
                    'msg' => lang('ERROR_ACCOUNTS'),
                    'language' => [
                        'TAG_ACCEPT' => lang('TAG_ACCEPT')
                    ]
                ];
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
        }
        return $response;
    }

    public function callAPIavailableDrivers($urlCountry, $dataRequest) {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');

        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc
        ];

        $urlAPI = 'account/available_drivers';
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'GET';

        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "LISTADO DE CONDUCTORES" .": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        $response = [];
        $dataResponse = $resAPI;
        $response = $resAPI;

        switch ($httpCode) {
            case 200:
                $dataResponse = json_decode($resAPI);
                if (empty($dataRequest)) {
                    $data = [];
                    $response = [
                        'code' => 0,
                        'msg' => $dataResponse,
                    ];
                }
                break;
            case 400:
                $response = [
                    'code' => 403,
                    'msg' => [
                        'code' => 403,
                        'msg' => 'Error en la solicitud'

                    ],
                ];
                break;
            case 401:
                $response = [
                    'code' => 401,
                    'msg' => [
                        'code' => 401,
                        'msg' => 'Cierre de sessión'
                    ],
                ];
                break;
            case 403:
                $response = [
                    'code' => 403,
                    'msg' => [
                        'code' => 403,
                        'msg' => 'Error general'
                    ],
                ];
                break;
            case 503:
                $response = [
                    'code' => 503,
                    'msg' => [
                        'code' => 503,
                        'msg' => 'Error general'
                    ],
                ];
                break;
            default:
//                $response = [
//                    'code' => 2,
//                    'title' => lang('SYSTEM_NAME'),
//                    'msg' => lang('ERROR_GENERICO_USER')
//                ];

        }
        return $response;
    }

    public function callAPIallocatingDriver($urlCountry, $dataRequest) {
        $ruc = $this->session->userdata('acrifS');
        $token = $this->session->userdata('token');
        $prefix = $this->session->userdata('idProductoS');
        log_message("INFO","Data recibida*********>>>>>" . json_encode($dataRequest) );

        $header = [
            'x-country: ' . $urlCountry,
            'x-token: ' . $token,
            'x-company: ' . $ruc,
            'x-product:' . $prefix
        ];

        $urlAPI = 'account/allocate/'.$dataRequest['card'].'/'.$dataRequest['user'];
        $headerAPI = $header;
        $bodyAPI = '';
        $method = 'PUT';

        $jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);
        log_message( "INFO", "GET-APPI ".json_encode($header) );

        $httpCode = $jsonResponse->httpCode;
        $resAPI = $jsonResponse->resAPI;

        log_message("INFO", "Asociar a conductor" .": ==>> httpCode: " . $httpCode . " resAPI: " . $resAPI);

        $response = [];
        $dataResponse = $resAPI;
        $response = $resAPI;

        switch ($httpCode) {
            case 200:
                $dataResponse = json_decode($resAPI);
                if (empty($dataRequest)) {
                    $data = [];
                    $response = [
                        'code' => 0,
                        'msg' => $dataResponse,
                    ];
                }
                break;
            case 400:
                $response = [
                    'code' => 403,
                    'msg' => [
                        'code' => 403,
                        'msg' => 'Error en la solicitud'
                    ],
                ];
                break;
            case 401:
                $response = [
                    'code' => 401,
                    'msg' => [
                        'code' => 401,
                        'msg' => 'Cierre de sessión'
                    ],
                ];
                break;
            case 403:
	            $dataResponse = json_decode($resAPI);
                $response = [
                    'code' => 403,
                    'msg' => [
                        'code' => $dataResponse->rc == -197 ? 1 : 403,
                        'msg' => $dataResponse->rc == -197 ? 'La tarjeta está vencida, por favor pruebe con otra' :'Error general'
                    ],
                ];
                break;
            case 503:
                $response = [
                    'code' => 503,
                    'msg' => [
                        'code' => 503,
                        'msg' => 'Error general'
                    ],
                ];
                break;
            default:
//                $response = [
//                    'code' => 2,
//                    'title' => lang('SYSTEM_NAME'),
//                    'msg' => lang('ERROR_GENERICO_USER')
//                ];
        }
        return $response;
    }
}
