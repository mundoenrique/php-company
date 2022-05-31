<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Librería para crear el menú de l apalicación
 * @author J. Enrique Peñaloza Piñero
 * @date October 31th, 2019
 */
class Create_Menu {
	private $CI;
	private $requestServ;
	private $responseDefect;

	public function __construct()
	{
		log_message('INFO', 'NOVO Create_Menu Library Class Initialized');

		$this->CI = &get_instance();
		$this->requestServ = new stdClass();
		$this->responseDefect = new stdClass();
	}
	/**
	 * @info método insertar el menu principal
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 3rd, 2019
	 */
	public function mainMenu($firstLevel)
	{
		log_message('INFO', 'NOVO Create_Menu: mainMenu method initialized');

		switch ($firstLevel) {
			case 'GESLOT':
				$mainMenuLang = lang('GEN_MENU_LOTS');
			break;
			case 'CONSUL':
				$mainMenuLang = lang('GEN_MENU_CONSULTATIONS');
			break;
			case 'SERVIC':
				$mainMenuLang = lang('GEN_MENU_SERVICES');
			break;
			case 'GESREP':
				$mainMenuLang = lang('GEN_MENU_REPORTS');
			break;
			case 'COMBUS':
				$mainMenuLang = lang('GEN_MENU_TRAJECTS');
			break;
			case 'GESUSR':
				$mainMenuLang = lang('GEN_MENU_USERS');
			break;
			default:
				$mainMenuLang = '';
		}

		return $mainMenuLang;
	}
	/**
	 * @info método insertar el sub menu
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 3rd, 2019
	 */
	public function secondaryMenu($firstLevel)
	{
		log_message('INFO', 'NOVO Create_Menu: secondaryMenu method initialized');

		$level = new stdClass();
		$level->second = [];
		$level->third = [];
		$control = 1;
		foreach($firstLevel->modulos AS $module) {
			if($module->idModulo === 'TICARG' || $module->idModulo === 'TIINVN') {
				$levelThird = new stdClass();
				if($control === 1) {
					$levelThird->title = lang('GEN_MENU_BULK_UNNAMED');
					$level->third[] = $levelThird;
					$level->second[] = $this->menulang('UNNAMED');
				}
				$control++;
				$level->third[] = $this->menulang($module->idModulo);
				continue;
			}
			$level->second[] = $this->menulang($module->idModulo);
		}

		log_message('INFO', 'NOVO secondaryMenu: '.json_encode($level, JSON_UNESCAPED_UNICODE));

		return $level;
	}
	/**
	 * @info método insertar el texto y el link al submenu
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 3rd, 2019
	 */
	public function menulang($subMenu)
	{
		log_message('INFO', 'NOVO Create_Menu: menulang method initialized');

		$subMenuLang = new stdClass();

		switch ($subMenu) {
			case 'TEBCAR':
				$subMenuLang->text = lang('GEN_MENU_BULK_LOAD');
				$subMenuLang->link = lang('CONF_LINK_BULK_LOAD');
			break;
			case 'TEBAUT':
				$subMenuLang->text = lang('GEN_MENU_BULK_AUTH');
				$subMenuLang->link = lang('CONF_LINK_BULK_AUTH');
			break;
			case 'TICARG':
				$subMenuLang->text = lang('GEN_MENU_BULK_UNNAMED_REQ');
				$subMenuLang->link = lang('CONF_LINK_BULK_UNNAMED_REQ');
			break;
			case 'TIINVN':
				$subMenuLang->text = lang('GEN_MENU_BULK_UNNAMED_AFFIL');
				$subMenuLang->link = lang('CONF_LINK_BULK_UNNAMED_AFFIL');
			break;
			case 'TEBGUR':
				$subMenuLang->text = lang('GEN_MENU_LOT_REPROCESS');
				$subMenuLang->link = 'javascript:';
			break;
			case 'TEBORS':
				$subMenuLang->text = lang('GEN_MENU_CONS_ORDERS_SERV');
				$subMenuLang->link = lang('CONF_LINK_SERVICE_ORDERS');
			break;
			case 'TEBPOL':
				$subMenuLang->text = lang('GEN_MENU_CONS_DATA_UPGRADE');
				$subMenuLang->link = 'javascript:';
			break;
			case 'TRAMAE':
				$subMenuLang->text = lang('GEN_MENU_SERV_MASTER_ACCOUNT');
				$subMenuLang->link = lang('CONF_LINK_TRANSF_MASTER_ACCOUNT');
			break;
			case 'COPELO':
				$subMenuLang->text = lang('GEN_MENU_SERV_CARD_INQUIRY');
				$subMenuLang->link = lang('CONF_LINK_CARDS_INQUIRY');
			break;
			case 'GIRCOM':
				$subMenuLang->text = lang('GEN_MENU_SERV_COMM_MONEY_ORDERS');
				$subMenuLang->link = lang('CONF_LINK_COMMERCIAL_TWIRLS');
			break;
			case 'LIMTRX':
				$subMenuLang->text = lang('GEN_MENU_SERV_TRANS_LIMITS');
				$subMenuLang->link = lang('CONF_LINK_TRANSACTIONAL_LIMITS');
			break;
			case 'CONVIS':
				$subMenuLang->text = lang('GEN_MENU_SERV_CONTROLS_PAY');
				$subMenuLang->link = 'javascript:';
			break;
			case 'PAGPRO':
				$subMenuLang->text = lang('GEN_MENU_SERV_PROV_PAY');
				$subMenuLang->link = 'javascript:';
			break;
			case 'CMBCON':
				$subMenuLang->text = lang('GEN_MENU_WAY_DRIVERS');
				$subMenuLang->link = 'javascript:';
			break;
			case 'CMBVHI':
				$subMenuLang->text = lang('GEN_MENU_WAY_VEHICLES');
				$subMenuLang->link = 'javascript:';
			break;
			case 'CMBCTA':
				$subMenuLang->text = lang('GEN_MENU_WAY_ACCOUNTS');
				$subMenuLang->link = 'javascript:';
			break;
			case 'CMBVJE':
				$subMenuLang->text = lang('GEN_MENU_WAY_TRAVELS');
				$subMenuLang->link = 'javascript:';
			break;
			case 'TEBTHA':
				$subMenuLang->text = lang('GEN_MENU_REP_CARDHOLDERS');
				$subMenuLang->link = lang('CONF_LINK_CARD_HOLDERS');
			break;
			case 'REPREP':
				$subMenuLang->text = lang('GEN_MENU_REP_CARD_REPLACE');
				$subMenuLang->link = lang('CONF_LINK_REPLACEMENT');
			break;
			case 'REPEDO':
				$subMenuLang->text = lang('GEN_MENU_REP_ACCOUNT_STATUS');
				$subMenuLang->link = lang('CONF_LINK_ACCOUNT_STATUS');
			break;
			case 'REPEDC':
				$subMenuLang->text = lang('GEN_MENU_REP_ACCOUNT_STATUS');
				$subMenuLang->link = lang('CONF_LINK_EXTENDED_ACCOUNT_STATUS');
			break;
			case 'REPSAL':
				$subMenuLang->text = lang('GEN_MENU_REP_CLOSING_BAKANCE');
				$subMenuLang->link = lang('CONF_LINK_CLOSING_BALANCE');
			break;
			case 'REPUSU':
				$subMenuLang->text = lang('GEN_MENU_REP_USER_ACT');
				$subMenuLang->link = lang('CONF_LINK_USER_ACTIVITY');
			break;
			case 'REPPRO':
				$subMenuLang->text = lang('GEN_MENU_REP_RECHARGE_MADE');
				$subMenuLang->link = lang('CONF_LINK_RECHARGE_MADE');
			break;
			case 'REPTAR':
				$subMenuLang->text = lang('GEN_MENU_REP_ISSUED_CARDS');
				$subMenuLang->link = lang('CONF_LINK_ISSUED_CARDS');
			break;
			case 'REPLOT':
				$subMenuLang->text = lang('GEN_MENU_REP_STATUS_BULK');
				$subMenuLang->link = lang('CONF_LINK_STATUS_BULK');
			break;
			case 'REPCAT':
				$subMenuLang->text = lang('GEN_MENU_REP_CATEGORY_EXPENSE');
				$subMenuLang->link = lang('CONF_LINK_CATEGORY_EXPENSE');
			break;
			case 'REPCON':
				$subMenuLang->text = lang('GEN_MENU_REP_MASTER_ACCOUNT');
				$subMenuLang->link = lang('CONF_LINK_MASTER_ACCOUNT');
			break;
			case 'REPCMT':
				$subMenuLang->text = lang('GEN_MENU_REP_MASTER_ACCOUNT');
				$subMenuLang->link = lang('CONF_LINK_EXTENDED_MASTER_ACCOUNT');
			break;
			case 'REPPGE':
				$subMenuLang->text = lang('GEN_MENU_REP_KIDGARDEN_PAY');
				$subMenuLang->link = 'javascript:';
			break;
			case 'REPRTH':
				$subMenuLang->text = lang('GEN_MENU_REP_RECHARGE_FEE');
				$subMenuLang->link = 'javascript:';
			break;
			case 'LOTFAC':
				$subMenuLang->text = lang('GEN_MENU_REP_LOTS_BILLED');
				$subMenuLang->link = 'javascript:';
			break;
			case 'REPALL':
				$subMenuLang->text = lang('GEN_MENU_REP_ALL');
				$subMenuLang->link = lang('CONF_LINK_REPORTS');
			break;
			case 'USEREM':
				$subMenuLang->text = lang('GEN_MENU_USERS_MANAGEMENT');
				$subMenuLang->link = lang('CONF_LINK_USERS_MANAGEMENT');
			break;
			case 'UNNAMED':
				$subMenuLang->text = lang('GEN_MENU_BULK_UNNAMED');
				$subMenuLang->link = 'javascript:';
			break;
			case 'REPECT':
				$subMenuLang->text = lang('GEN_MENU_REP_STATUS_MASTER_ACCOUNT');
				$subMenuLang->link = lang('CONF_LINK_STATUS_MASTER_ACCOUNT');
			break;
			default:
				$subMenuLang->text = '---';
				$subMenuLang->link = 'javascript:';
		}

		return $subMenuLang;
	}
}
