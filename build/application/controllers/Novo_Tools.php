<?php
defined('BASEPATH') or  exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a configuraciones
 * @author Luis Molina
 * @date Marz 20Fri, 2020
 */
class Novo_Tools extends NOVO_Controller
{

  public function __construct()
  {
    parent::__construct();
    writeLog('INFO', 'Tools Controller Class Initialized');
  }

  /**
   * @info Método para renderizar el modulo de configuración
   * @author Luis Molina
   * @date Mar 30/04/2020
   * @modified Diego Acosta García
   * @date  02/04/2020
   */
  public function options()
  {
    writeLog('INFO', 'Tools: options Method Initialized');

    $view = 'options';

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
      "tools/options",
      "tools/company",
      "tools/regions",
      "tools/branches",
      "user/changePassword-core",
      "user/passValidate"
    );
    $userType = '';
    $title = '';
    $valuesArr = [];
    $disabled = 'big-modal';

    $enterpriseList = $this->session->enterpriseSelect->list;

    if (lang('SETT_SETTINGS_USER') === 'ON') {
      $this->load->model('Novo_Tools_Model', 'getUser');
      $user = $this->getUser->CallWs_GetUser_Tools();
      $userType = $this->session->userType;

      foreach ($user->data->dataUser as $index => $render) {
        $this->render->$index = $render;
      }
      $this->render->userType = $userType;
      $this->render->emailUpdate = lang('SETT_SETTINGS_EMAIL_UPDATE') === 'OFF' ? 'readonly' : '';
      $this->render->addressCompanyUpdate = lang('SETT_SETTINGS_ADDRESS_ENTERPRICE_UPDATE') === 'OFF' ? 'readonly' : '';
    }

    if (lang('SETT_SETTINGS_ENTERPRISE') === 'ON') {
      $this->render->enterpriseSettList = $enterpriseList;
      $this->render->countEnterpriseList = count($enterpriseList);

      if ($this->render->countEnterpriseList === 1) {
        foreach ((object)lang('TOOLS_RENDER_CONTROLLER_VARIABLES') as $key => $value) {
          $valuesArr[$key] = $this->render->enterpriseSettList[0]->$value;
        }
      }

      foreach ((object)lang('TOOLS_RENDER_CONTROLLER_VARIABLES') as $key => $value) {
        lang('SETT_SETTINGS_ENTERPRISE') === 'ON' ? $this->render->$key = $this->render->countEnterpriseList == 1 ? $valuesArr[$key] : '' : '';
      }

      $this->render->phoneUpdate = lang('SETT_SETTINGS_PHONES_UPDATE') === 'OFF' ? 'readonly' : '';
    }

    if (lang('SETT_SETTINGS_BRANCHES') === 'ON') {
      $this->render->enterpriseSettList = $enterpriseList;
    }

    if (!$this->session->has_userdata('enterpriseInf') && count($enterpriseList) > 1) {
      $title = lang('GEN_BTN_INI');
      $disabled = '';
    }

    $this->render->titlePage = lang('GEN_SETTINGS_TITLE');
    $this->render->titleIniFile = $title;
    $this->render->disabled = $disabled;
    $this->views = ['tools/' . $view];
    $this->loadView($view);
  }
}
