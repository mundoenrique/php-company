<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para
 * @author
 *
 */
class Novo_Business_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Business Model Class Initialized');
	}
	/**
	 * @info Obtiene la lista de empresas para un usuario
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 1st, 2019
	 *
	 */
	public function callWs_getEnterprises_Business($select = FALSE)
	{
		log_message('INFO', 'NOVO Business Model: getEnterprises method Initialized');

		$menu = [
			'user_access'
		];
		$this->session->unset_userdata($menu);
		$this->className = "com.novo.objects.MO.ListadoEmpresasMO";

		$this->dataAccessLog->modulo = 'Negocios';
		$this->dataAccessLog->function = 'Empresas';
		$this->dataAccessLog->operation = 'lista de empresas';

		$this->dataRequest->idOperation = 'listaEmpresas';
		$this->dataRequest->accodusuario = $this->session->userdata('userName');
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->paginar = FALSE;
		$this->dataRequest->tamanoPagina = 10;
		$this->dataRequest->filtroEmpresas = '';

		$response = $this->sendToService('getEnterprises');

		switch($this->isResponseRc) {
			case 0:
				$enterpriseList = $response->listadoEmpresas->lista;
				$enterpriseList = json_decode('[{"accodcia":"20131","acnomcia":"A-NOVO PERU S.A.C.                                                                                  ","acrif":"20501424774","acdirubica":"","actel":"6156464       ","acpercontac":"LEANDRO ALBITEZ","acemail":"LIDIA-VALDIVIA@ANOVO.PE                      ","acdirenvio":"AV. OSCAR R. BENAVIDES 366, URB. EL PINO, SAN LUIS, LIMA","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"24\/06\/2010 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA239              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"A-NOVO PERU S.A.C.        ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"10","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"CORREOS Y TELECOMUNICACIONES"},{"accodcia":"78","acnomcia":"ADECCO CONSULTING                                                                                   ","acrif":"20503980216","acdirubica":"","actel":"5116114444    ","acpercontac":"EDUARDO ERAUSQUIN \/ ROMY ROMERO ","acemail":"xxx                                          ","acdirenvio":"CALLE AMADOR MERINO REINA 285 PISO 3 SAN ISIDRO","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"10\/08\/2009 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA049              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"ADECCO CONSULTING         ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20392","acnomcia":"ADRA PERU                                                                                           ","acrif":"20138861300","acdirubica":"","actel":"5117127704    ","acpercontac":"SAMUEL MARTINEZ","acemail":"","acdirenvio":"AV. ANGAMOS OESTE 770","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"21\/03\/2011 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA497              ","actel2":"","actel3":"","actipocomision":"","acnil":"","acivss":"0","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"ADRA PERU                 ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"19","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"OTRAS OCUPACIONES INDIVIDUALES NO CLASIFICADOS EN OTRA PARTE"},{"accodcia":"20085","acnomcia":"ADUAMERICA S.A.                                                                                     ","acrif":"20172023089","acdirubica":"","actel":"5116255000    ","acpercontac":"JHONNY PANIZO CUARESMA","acemail":"jpanizo@aduamerica.net                       ","acdirenvio":"AV. FEDERICO FERNANDINI 253, URB. SANTA MARINA SUR, CALLAO","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"26\/04\/2010 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA192              ","actel2":"5116255000","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"ADUAMERICA S.A.           ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20125","acnomcia":"AFP INTEGRA S.A.                                                                                    ","acrif":"20157036794","acdirubica":"","actel":"5114119191    ","acpercontac":"ALBERTO GARCIA HAAKER","acemail":"","acdirenvio":"AV. CANALVAL Y MOREYRA 522, INTERIOR P6Y5, URB. EL PALOMAR, SAN ISIDRO, LIMA","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"18\/06\/2010 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA232              ","actel2":"","actel3":"","actipocomision":"","acnil":"","acivss":"0","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"AFP INTEGRA S.A.          ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20373","acnomcia":"ALEMFER C.G. S.A.C.                                                                                 ","acrif":"20298742714","acdirubica":"","actel":"5114478730    ","acpercontac":"CECILIA PERCCA","acemail":"","acdirenvio":"AV. TOMAS MARSANO 1396","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"28\/02\/2011 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA478              ","actel2":"5114479725","actel3":"","actipocomision":"","acnil":"","acivss":"0","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"ALEMFER C.G. S.A.C.       ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"19","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"OTRAS OCUPACIONES INDIVIDUALES NO CLASIFICADOS EN OTRA PARTE"},{"accodcia":"41","acnomcia":"BANCO FINANCIERO DEL PERU                                                                           ","acrif":"20100105862","acdirubica":"","actel":"0516122000    ","acpercontac":"JUAN PABLO CASTILLO MATIENZO","acemail":"JCASTILLO                                    ","acdirenvio":"AV. RICARDO PALMA N\u00b0 278 MIRAFLORES","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"27\/05\/2009 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA 0013            ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"BANCO FINANCIERO DEL PERU ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20397","acnomcia":"BURO PARTNERS S.A.C.                                                                                ","acrif":"20479107140","acdirubica":"","actel":"5117125600    ","acpercontac":"PAMELA VALDIVIA ALLEMAN","acemail":"","acdirenvio":"AV. LARCO 930 DPTO 801","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"24\/03\/2011 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA502              ","actel2":"","actel3":"","actipocomision":"","acnil":"","acivss":"0","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"BURO PARTNERS S.A.C.      ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"19","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"OTRAS OCUPACIONES INDIVIDUALES NO CLASIFICADOS EN OTRA PARTE"},{"accodcia":"20153","acnomcia":"CALZADO ATLAS S.A.                                                                                  ","acrif":"20185788599","acdirubica":"","actel":"5113612235    ","acpercontac":"NILO MENDOZA \/ SONIA FLORES","acemail":"NMENDOZA@OMEGA.PE \/ SFLORES@OMEGA.PE         ","acdirenvio":"AV. C DE PERALTA 112, SANTIAGO DE SURCO, LIMA","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"14\/07\/2010 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA261              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"CALZADO ATLAS S.A.        ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20595","acnomcia":"CENTRAL DE ORGANIZACIONES PRODUCTORAS DE CAFE Y CACAO DEL PERU - CAFE PERU                          ","acrif":"20506370346","acdirubica":"","actel":"(511) 265-5392","acpercontac":"CECILIA PUESCAS \/ ENRIQUE ALVAREZ","acemail":"cpuescas@cafeperu.org                        ","acdirenvio":"cal. enrique villar 104, urb. santa beatriz","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"05\/12\/2011 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA701              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"CENTRAL DE ORGANIZACIONES ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"19","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"OTRAS OCUPACIONES INDIVIDUALES NO CLASIFICADOS EN OTRA PARTE"},{"accodcia":"1021","acnomcia":"COLGATE-PALMOLIVE PERU S.A.                                                                         ","acrif":"20100919002","acdirubica":"","actel":"5112137970    ","acpercontac":"RICARDO VELARDE FRIEDL","acemail":"xx                                           ","acdirenvio":"AV. 28 DE JULIO NRO. 1011 INT. 1201 - MIRAFLORES","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"01\/12\/2009 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA091              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"COLGATE-PALMOLIVE PERU S.A","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"76","acnomcia":"SERVITEBCA                                                                                          ","acrif":"20517372294","acdirubica":"","actel":"5116198900    ","acpercontac":"JORGE CABELLO","acemail":"fruggiero@tebca.com                          ","acdirenvio":"AV RIVERA NAVARRETE 791 PISO 8 SAN ISIDRO","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"07\/08\/2009 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA050              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"SRTEBCA COMPA\u00d1IA ANONIMA  ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"1","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20354","acnomcia":"SERVITEBCA1","acrif":"20000000002","acdirubica":"","actel":"5116198900    ","acpercontac":"FERNANDO CACERES BUENO","acemail":"jorojas@novopayment.com                      ","acdirenvio":"AV. RIVERA NAVARRETE 791 PISO 8 SAN ISIDRO","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"15\/02\/2011 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA461              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"SERVITEBCA1               ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"A","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"5","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"1","acnomcia":"TEBCA PERU,TRANSFERENCIA ELECTRONICA DE BENEFICIOS                                                  ","acrif":"20517372707","acdirubica":"","actel":"5116198900    ","acpercontac":"FERNANDO CACERES BUENO","acemail":"PCHUNGA@TEBCA.COM.PE                         ","acdirenvio":"AV RIVERA NAVARRETE 791, PISO 8, DISTRITO SAN ISIDRO","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"29\/11\/2008 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"TPA0001             ","actel2":"","actel3":"","actipocomision":"","acnil":"","acivss":"0","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"TEBCA PERU                ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20279","acnomcia":"ZENDER S.A.C.                                                                                       ","acrif":"20510256361","acdirubica":"","actel":"5114224556    ","acpercontac":"CHRISTIAN VILLA MOSCAYZA","acemail":"","acdirenvio":"AV. GUARDIA CIVIL 342 URB. LA CAMPI\u00d1A","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"02\/12\/2010 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA387              ","actel2":"","actel3":"","actipocomision":"","acnil":"","acivss":"0","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"ZENDER S.A.C.             ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"19","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"OTRAS OCUPACIONES INDIVIDUALES NO CLASIFICADOS EN OTRA PARTE"},{"accodcia":"20131","acnomcia":"A-NOVO PERU S.A.C.                                                                                  ","acrif":"20501424774","acdirubica":"","actel":"6156464       ","acpercontac":"LEANDRO ALBITEZ","acemail":"LIDIA-VALDIVIA@ANOVO.PE                      ","acdirenvio":"AV. OSCAR R. BENAVIDES 366, URB. EL PINO, SAN LUIS, LIMA","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"24\/06\/2010 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA239              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"A-NOVO PERU S.A.C.        ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"10","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"CORREOS Y TELECOMUNICACIONES"},{"accodcia":"78","acnomcia":"ADECCO CONSULTING                                                                                   ","acrif":"20503980216","acdirubica":"","actel":"5116114444    ","acpercontac":"EDUARDO ERAUSQUIN \/ ROMY ROMERO ","acemail":"xxx                                          ","acdirenvio":"CALLE AMADOR MERINO REINA 285 PISO 3 SAN ISIDRO","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"10\/08\/2009 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA049              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"ADECCO CONSULTING         ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20392","acnomcia":"ADRA PERU                                                                                           ","acrif":"20138861300","acdirubica":"","actel":"5117127704    ","acpercontac":"SAMUEL MARTINEZ","acemail":"","acdirenvio":"AV. ANGAMOS OESTE 770","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"21\/03\/2011 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA497              ","actel2":"","actel3":"","actipocomision":"","acnil":"","acivss":"0","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"ADRA PERU                 ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"19","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"OTRAS OCUPACIONES INDIVIDUALES NO CLASIFICADOS EN OTRA PARTE"},{"accodcia":"20085","acnomcia":"ADUAMERICA S.A.                                                                                     ","acrif":"20172023089","acdirubica":"","actel":"5116255000    ","acpercontac":"JHONNY PANIZO CUARESMA","acemail":"jpanizo@aduamerica.net                       ","acdirenvio":"AV. FEDERICO FERNANDINI 253, URB. SANTA MARINA SUR, CALLAO","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"26\/04\/2010 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA192              ","actel2":"5116255000","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"ADUAMERICA S.A.           ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20125","acnomcia":"AFP INTEGRA S.A.                                                                                    ","acrif":"20157036794","acdirubica":"","actel":"5114119191    ","acpercontac":"ALBERTO GARCIA HAAKER","acemail":"","acdirenvio":"AV. CANALVAL Y MOREYRA 522, INTERIOR P6Y5, URB. EL PALOMAR, SAN ISIDRO, LIMA","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"18\/06\/2010 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA232              ","actel2":"","actel3":"","actipocomision":"","acnil":"","acivss":"0","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"AFP INTEGRA S.A.          ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20373","acnomcia":"ALEMFER C.G. S.A.C.                                                                                 ","acrif":"20298742714","acdirubica":"","actel":"5114478730    ","acpercontac":"CECILIA PERCCA","acemail":"","acdirenvio":"AV. TOMAS MARSANO 1396","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"28\/02\/2011 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA478              ","actel2":"5114479725","actel3":"","actipocomision":"","acnil":"","acivss":"0","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"ALEMFER C.G. S.A.C.       ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"19","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"OTRAS OCUPACIONES INDIVIDUALES NO CLASIFICADOS EN OTRA PARTE"},{"accodcia":"41","acnomcia":"BANCO FINANCIERO DEL PERU                                                                           ","acrif":"20100105862","acdirubica":"","actel":"0516122000    ","acpercontac":"JUAN PABLO CASTILLO MATIENZO","acemail":"JCASTILLO                                    ","acdirenvio":"AV. RICARDO PALMA N\u00b0 278 MIRAFLORES","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"27\/05\/2009 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA 0013            ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"BANCO FINANCIERO DEL PERU ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20397","acnomcia":"BURO PARTNERS S.A.C.                                                                                ","acrif":"20479107140","acdirubica":"","actel":"5117125600    ","acpercontac":"PAMELA VALDIVIA ALLEMAN","acemail":"","acdirenvio":"AV. LARCO 930 DPTO 801","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"24\/03\/2011 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA502              ","actel2":"","actel3":"","actipocomision":"","acnil":"","acivss":"0","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"BURO PARTNERS S.A.C.      ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"19","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"OTRAS OCUPACIONES INDIVIDUALES NO CLASIFICADOS EN OTRA PARTE"},{"accodcia":"20153","acnomcia":"CALZADO ATLAS S.A.                                                                                  ","acrif":"20185788599","acdirubica":"","actel":"5113612235    ","acpercontac":"NILO MENDOZA \/ SONIA FLORES","acemail":"NMENDOZA@OMEGA.PE \/ SFLORES@OMEGA.PE         ","acdirenvio":"AV. C DE PERALTA 112, SANTIAGO DE SURCO, LIMA","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"14\/07\/2010 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA261              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"CALZADO ATLAS S.A.        ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20595","acnomcia":"CENTRAL DE ORGANIZACIONES PRODUCTORAS DE CAFE Y CACAO DEL PERU - CAFE PERU                          ","acrif":"20506370346","acdirubica":"","actel":"(511) 265-5392","acpercontac":"CECILIA PUESCAS \/ ENRIQUE ALVAREZ","acemail":"cpuescas@cafeperu.org                        ","acdirenvio":"cal. enrique villar 104, urb. santa beatriz","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"05\/12\/2011 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA701              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"CENTRAL DE ORGANIZACIONES ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"19","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"OTRAS OCUPACIONES INDIVIDUALES NO CLASIFICADOS EN OTRA PARTE"},{"accodcia":"1021","acnomcia":"COLGATE-PALMOLIVE PERU S.A.                                                                         ","acrif":"20100919002","acdirubica":"","actel":"5112137970    ","acpercontac":"RICARDO VELARDE FRIEDL","acemail":"xx                                           ","acdirenvio":"AV. 28 DE JULIO NRO. 1011 INT. 1201 - MIRAFLORES","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"01\/12\/2009 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA091              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"COLGATE-PALMOLIVE PERU S.A","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"76","acnomcia":"SERVITEBCA                                                                                          ","acrif":"20517372294","acdirubica":"","actel":"5116198900    ","acpercontac":"JORGE CABELLO","acemail":"fruggiero@tebca.com                          ","acdirenvio":"AV RIVERA NAVARRETE 791 PISO 8 SAN ISIDRO","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"07\/08\/2009 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA050              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"SRTEBCA COMPA\u00d1IA ANONIMA  ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"1","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20354","acnomcia":"SERVITEBCA1","acrif":"20000000002","acdirubica":"","actel":"5116198900    ","acpercontac":"FERNANDO CACERES BUENO","acemail":"jorojas@novopayment.com                      ","acdirenvio":"AV. RIVERA NAVARRETE 791 PISO 8 SAN ISIDRO","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"15\/02\/2011 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA461              ","actel2":"          ","actel3":"          ","actipocomision":"","acnil":"","acivss":"","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"SERVITEBCA1               ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"A","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"5","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"1","acnomcia":"TEBCA PERU,TRANSFERENCIA ELECTRONICA DE BENEFICIOS                                                  ","acrif":"20517372707","acdirubica":"","actel":"5116198900    ","acpercontac":"FERNANDO CACERES BUENO","acemail":"PCHUNGA@TEBCA.COM.PE                         ","acdirenvio":"AV RIVERA NAVARRETE 791, PISO 8, DISTRITO SAN ISIDRO","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"29\/11\/2008 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"TPA0001             ","actel2":"","actel3":"","actipocomision":"","acnil":"","acivss":"0","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"TEBCA PERU                ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"11","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"COMERCIO NO ESPECIFICADO"},{"accodcia":"20279","acnomcia":"ZENDER S.A.C.                                                                                       ","acrif":"20510256361","acdirubica":"","actel":"5114224556    ","acpercontac":"CHRISTIAN VILLA MOSCAYZA","acemail":"","acdirenvio":"AV. GUARDIA CIVIL 342 URB. LA CAMPI\u00d1A","acobservacion":"","accomision":"","cstatus":"A","dtfechorcrea":"02\/12\/2010 00:00:00","dtfechoracti":"","dtfechorelim":"","acnit":"CPA387              ","actel2":"","actel3":"","actipocomision":"","acnil":"","acivss":"0","npriva":"0","npislr":"0","cpagoiva":"","cembozo":"","cfacturapago":"","cvencimiento1a":"","accodgrupoe":"0101010101","acrazonsocial":"ZENDER S.A.C.             ","ncedula_re":"","accodunidad_re":"","nprotras":"","acnumprov":"","acordencom":"","cpagocomision":"","npotros":"","acucodgrupo":"","acuusuario":"","cestatusonline":"","mrecargaomaximo":"","mrecargaominimo":"","dtutimestamp":"","actividadeconm":"19","nprfuente":"","nprica":"","cdetraccion":"","resumenProductos":"0","resumenTarjetaHabiente":"","resumenSucursal":"","acdesc":"OTRAS OCUPACIONES INDIVIDUALES NO CLASIFICADOS EN OTRA PARTE"}]
');

				$item = 1; $page = 1; $cat = FALSE;
				$itemAlphaBeA = 1; $itemAlphaBeD = 1; $itemAlphaBeH = 1;  $itemAlphaBeL = 1; $itemAlphaBeP = 1;
				$itemAlphaBeT = 1; $itemAlphaBeX = 1;
				$pageAlphaBeA = 1; $pageAlphaBeD = 1; $pageAlphaBeH = 1;  $pageAlphaBeL = 1; $pageAlphaBeP = 1;
				$pageAlphaBeT = 1; $pageAlphaBeX = 1;
				foreach($enterpriseList AS $pos => $enterprises) {
					foreach($enterprises AS $key => $value) {
						$enterpriseList[$pos]->$key = trim($value);

						if($item > $this->dataRequest->tamanoPagina) {
							$item = 1;
							$page++;
						}

						$enterpriseList[$pos]->page = 'page_'.$page;

						if($key === 'resumenProductos') {
							$enterpriseList[$pos]->resumenProductos = $enterpriseList[$pos]->resumenProductos == 1 ?
							$enterpriseList[$pos]->resumenProductos.' '.lang('GEN_PRODUCT') :
							$enterpriseList[$pos]->resumenProductos.' '.lang('GEN_PRODUCTS');
						}

						if($key === 'acpercontac') {
							$enterpriseList[$pos]->acpercontac = ucwords(mb_strtolower($enterpriseList[$pos]->acpercontac));
						}

						if($key === 'acnomcia') {
							$cat = substr($enterpriseList[$pos]->$key, 0, 1);
							$enterpriseList[$pos]->category = $cat;

							switch ($cat) {
								case strpos('ABC', $cat) !== FALSE:
									if($itemAlphaBeA > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeA = 1; 	$pageAlphaBeA++;
									}
									$enterpriseList[$pos]->albeticalPage = 'A-C_'.$pageAlphaBeA;
									$itemAlphaBeA++;
									break;
								case strpos('DEFG', $cat) !== FALSE:
									if($itemAlphaBeD > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeD = 1; 	$pageAlphaBeD++;
									}
									$enterpriseList[$pos]->albeticalPage = 'D-G_'.$pageAlphaBeD;
									$itemAlphaBeD++;
									break;
								case strpos('HIJK', $cat) !== FALSE:
									if($itemAlphaBeH > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeH = 1; 	$pageAlphaBeH++;
									}

									$enterpriseList[$pos]->albeticalPage = 'H-K_'.$pageAlphaBeH;
									$itemAlphaBeH++;
									break;
								case strpos('LMNO', $cat) !== FALSE:
									if($itemAlphaBeL > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeL = 1; 	$pageAlphaBeL++;
									}
									$enterpriseList[$pos]->albeticalPage = 'L-O_'.$pageAlphaBeL;
									$itemAlphaBeL++;
									break;
								case strpos('PQRS', $cat) !== FALSE:
									if($itemAlphaBeP > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeP = 1; 	$pageAlphaBeP++;
									}
									$enterpriseList[$pos]->albeticalPage = 'P-S_'.$pageAlphaBeP;
									$itemAlphaBeP++;
									break;
								case strpos('TUVW', $cat) !== FALSE:
									if($itemAlphaBeT > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeT = 1; 	$pageAlphaBeT++;
									}
									$enterpriseList[$pos]->albeticalPage = 'T-W_'.$pageAlphaBeT;
									$itemAlphaBeT++;
									break;
								case strpos('XYZ', $cat) !== FALSE:
									if($itemAlphaBeX > $this->dataRequest->tamanoPagina) {
										$itemAlphaBeX = 1; 	$pageAlphaBeX++;
									}
									$enterpriseList[$pos]->albeticalPage = 'X-Z_'.$pageAlphaBeX;
									$itemAlphaBeX++;
									break;
							}
						}

					}
					$item++;
				}
		}

			log_message('DEBUG', 'NOVO ['.$this->userName.'] RESPONSE getEnterprises: '.json_encode($enterpriseList));

			$responseList = new stdClass();
			$responseList->list = $enterpriseList;
			$responseList->curretPage = trim($response->listadoEmpresas->paginaActual);
			$responseList->totalPages = trim($response->listadoEmpresas->totalPaginas);
			$responseList->enterprisesTotal = $response->listadoEmpresas->totalRegistros;
			$responseList->enterprisesTotal = 30;
			$responseList->recordsPage = $this->dataRequest->tamanoPagina;


			$this->response->code = 0;
			$this->response->data = $responseList;



		return $this->response;
	}

	public function callWs_getProducts_Business($params)
	{
		log_message('INFO', 'NOVO Business Model: Enterprises method Initialized');
		$menu = [
			'user_access'
		];
		$this->session->unset_userdata($menu);
		$this->className = "com.novo.objects.TOs.UsuarioTO";

		$this->dataAccessLog->modulo = 'dashboard';
		$this->dataAccessLog->function = 'dashboard';
		$this->dataAccessLog->operation = 'menuEmpresa';

		$this->dataRequest->userName = $this->session->userdata('userName');
		$this->dataRequest->ctipo = "A";
		$this->dataRequest->idEmpresa = $params['acrifS'];

		log_message('DEBUG', 'NOVO ['.$this->session->userdata('userName').'] RESPONSE: Business: ' . json_encode($this->dataRequest));
		$this->response = $this->sendToService('Business');

		switch($this->isResponseRc) {
			case -5000:
				$this->response->code = 1;
				$this->response->title = lang('GETENTERPRISES_TITLE-'.$this->isResponseRc);
				$this->response->className = 'error-login-2';
				$this->response->msg = lang('GETENTERPRISES_MSG-'.$this->isResponseRc);
				break;
			case -6000:
				$this->response->code = 3;
				$this->response->msg = lang('GETENTERPRISES_MSG-'.$this->isResponseRc);
				$this->response->icon = 'ui-icon-info';
				break;
		}
		return $this->response;
	}

	public function callWs_listEnterprises_Business()
	{
		log_message('INFO', 'NOVO Business Model: Enterprises method Initialized');
		$menu = [
			'user_access'
		];
		$this->session->unset_userdata($menu);
		$this->className = "com.novo.objects.MO.ListadoEmpresasMO";

		$this->dataAccessLog->modulo = 'dashboard';
		$this->dataAccessLog->function = 'dashboard';
		$this->dataAccessLog->operation = 'getPaginar';

		$this->dataRequest->accodusuario = $this->session->userdata('userName');
		$this->dataRequest->paginaActual = NULL;
		$this->dataRequest->tamanoPagina = NULL;
		$this->dataRequest->paginar = FALSE;
		$this->dataRequest->filtroEmpresas = NULL;

		log_message('DEBUG', 'NOVO ['.$this->session->userdata('userName').'] RESPONSE: Business: ' . json_encode($this->dataRequest));
		$this->response = $this->sendToService('Business');

		switch($this->isResponseRc) {
			case -5000:
				$this->response->code = 1;
				$this->response->title = lang('GETENTERPRISES_TITLE-'.$this->isResponseRc);
				$this->response->className = 'error-login-2';
				$this->response->msg = lang('GETENTERPRISES_MSG-'.$this->isResponseRc);
				break;
			case -6000:
				$this->response->code = 3;
				$this->response->msg = lang('GETENTERPRISES_MSG-'.$this->isResponseRc);
				$this->response->icon = 'ui-icon-info';
				break;
		}
		return $this->response;
	}
}
