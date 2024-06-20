<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Novo_User/sign-in';
$route['404_override'] = ERROR_CONTROLLER;
$route['translate_uri_dashes'] = FALSE;

/*
|--------------------------------------------------------------------------
| TEMPORAL ROUTES
|--------------------------------------------------------------------------
*/
$route['(' . CUSTOMER_OLD_WAY . ')/browsers'] = "Novo_User/browsers";
$route['(' . CUSTOMER_OLD_WAY . ')/inicio'] = "Novo_User/login";
$route['(' . CUSTOMER_OLD_WAY . ')/recuperar-clave'] = "Novo_User/passwordRecovery";
$route['(' . CUSTOMER_OLD_WAY . ')/cambiar-clave'] = "Novo_User/changePass";
$route['(' . CUSTOMER_OLD_WAY . ')/inf-beneficios'] = "Novo_Information/benefits";
$route['(' . CUSTOMER_OLD_WAY . ')/inf-condiciones'] = "Novo_Information/terms";
/*
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| ASYNC ROUTES
|--------------------------------------------------------------------------
*/
$route['(' . CUSTOMER_OLD_WAY . '|' . CUSTUMER_ALLOWED . ')/callCoreApp'] = "Novo_LoadModels/loadModels";
$route['(' . CUSTUMER_ALLOWED . ')/single'] = "Novo_LoadModels/loadModels";
$route['(' . CUSTOMER_OLD_WAY . '|' . CUSTUMER_ALLOWED . ')/async-call'] = "Novo_CallModels";
/*
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| EXTERNAL ROUTES
|--------------------------------------------------------------------------
*/
$route['(' . CUSTUMER_ALLOWED . ')/inicio'] = function ($customer) {
  header('Location: ' . BASE_URL . $customer . '/sign-in', 302);
  exit;
};
$route['(' . CUSTOMER_OLD_WAY . '|' . CUSTUMER_ALLOWED . ')/sign-in'] = function ($customer) {
  if (DENY_WAY) {
    header('Location: ' . BASE_URL . $customer . '/inicio', 302);
    exit;
  }

  return 'Novo_User/signIn';
};
$route['(' . CUSTUMER_ALLOWED . ')/ingresar/(:any)']['GET'] = function ($customer, $sessionId) {
  return SINGLE_SIGNON_GET ? "Novo_User/singleSignOn/$sessionId" : ERROR_CONTROLLER;
};
$route['(' . CUSTUMER_ALLOWED . ')/ingresar']['POST'] = function () {
  $_POST['external'] = TRUE;
  return SINGLE_SIGNON_POST ? "Novo_User/singleSignOn" : ERROR_CONTROLLER;
};
$route['(' . CUSTUMER_ALLOWED . ')/ingress']['POST'] = function () {
  return SINGLE_SIGNON_POST ? "Novo_User/singleSignOn" : ERROR_CONTROLLER;
};
$route['(' . CUSTUMER_ALLOWED . ')/internal/novopayment/signin'] = "Novo_User/signIn";
$route['(' . CUSTUMER_ALLOWED . ')/change-password'] = "Novo_User/changePassword";
$route['(' . CUSTUMER_ALLOWED . ')/recover-password'] = "Novo_User/recoverPass";
$route['(' . CUSTUMER_ALLOWED . ')/recover-access'] = "Novo_User/recoverAccess";
$route['(' . CUSTUMER_ALLOWED . ')/terms'] = "Novo_Information/termsInf";
$route['(' . CUSTUMER_ALLOWED . ')/benefits'] = "Novo_Information/benefitsInf";
$route['(' . CUSTUMER_ALLOWED . ')/suggestion'] = "Novo_User/suggestion";
/*
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| EXTERNAL ROUTES WITH LANGUAGE
|--------------------------------------------------------------------------
*/
$route['(' . CUSTUMER_ALLOWED . ')/sign-in/(es|en)'] = function () {
  return ENGLISH_ACTIVE ? "Novo_User/signIn" : ERROR_CONTROLLER;
};
$route['(' . CUSTUMER_ALLOWED . ')/ingresar/(:any)/(es|en)']['GET'] = function ($customer, $sessionId) {
  return SINGLE_SIGNON_GET && ENGLISH_ACTIVE ? "Novo_User/singleSignOn/$sessionId" : ERROR_CONTROLLER;
};
$route['(' . CUSTUMER_ALLOWED . ')/ingresar/(es|en)']['POST'] = function () {
  $_POST['external'] = TRUE;
  return SINGLE_SIGNON_POST && ENGLISH_ACTIVE ? "Novo_User/singleSignOn" : ERROR_CONTROLLER;
};
$route['(' . CUSTUMER_ALLOWED . ')/ingress/(es|en)']['POST'] = function () {
  return SINGLE_SIGNON_POST && ENGLISH_ACTIVE ? "Novo_User/singleSignOn" : ERROR_CONTROLLER;
};
$route['(' . CUSTUMER_ALLOWED . ')/internal/novopayment/signin/(es/en)'] = function () {
  return ENGLISH_ACTIVE ? "Novo_User/signIn" : ERROR_CONTROLLER;
};
$route['(' . CUSTUMER_ALLOWED . ')/recover-password/(es|en)'] = function () {
  return ENGLISH_ACTIVE ? "Novo_User/recoverPass" : ERROR_CONTROLLER;
};
$route['(' . CUSTUMER_ALLOWED . ')/recover-access/(es|en)'] = function () {
  return ENGLISH_ACTIVE ? "Novo_User/recoverAccess" : ERROR_CONTROLLER;
};
/*
|--------------------------------------------------------------------------
*/

$route['(' . CUSTUMER_ALLOWED . ')/rates'] = "Novo_Information/ratesInf";
$route['(' . CUSTOMER_OLD_WAY . '|' . CUSTUMER_ALLOWED . ')/sign-out/(:any)'] = "Novo_User/finishSession/$2";
$route['(' . CUSTUMER_ALLOWED . ')/users-management'] = "Novo_User/usersManagement";
$route['(' . CUSTUMER_ALLOWED . ')/user-permissions'] = "Novo_User/userPermissions";
$route['(' . CUSTUMER_ALLOWED . ')/user-accounts'] = "Novo_User/userAccounts";
$route['(' . CUSTUMER_ALLOWED . ')/enterprises'] = "Novo_Business/getEnterprises";
$route['(' . CUSTUMER_ALLOWED . ')/products'] = "Novo_Business/getProducts";
$route['(' . CUSTUMER_ALLOWED . ')/product-detail'] = "Novo_Business/getProductDetail";
$route['(' . CUSTUMER_ALLOWED . ')/bulk-load'] = "Novo_Bulk/getPendingBulk";
$route['(' . CUSTUMER_ALLOWED . ')/bulk-detail'] = "Novo_Bulk/getDetailBulk";
$route['(' . CUSTUMER_ALLOWED . ')/bulk-confirm'] = "Novo_Bulk/confirmBulk";
$route['(' . CUSTUMER_ALLOWED . ')/bulk-authorize'] = "Novo_Bulk/authorizeBulkList";
$route['(' . CUSTUMER_ALLOWED . ')/unnamed-request'] = "Novo_Bulk/unnamedRequest";
$route['(' . CUSTUMER_ALLOWED . ')/unnamed-affiliation'] = "Novo_Bulk/unnamedAffiliate";
$route['(' . CUSTUMER_ALLOWED . ')/unnmamed-detail'] = "Novo_Bulk/unnmamedDetail";
$route['(' . CUSTUMER_ALLOWED . ')/calc-serv-order'] = "Novo_Bulk/calculateServiceOrder";
$route['(' . CUSTUMER_ALLOWED . ')/service-orders'] = "Novo_Inquiries/serviceOrders";
$route['(' . CUSTUMER_ALLOWED . ')/inquiry-bulk-detail'] = "Novo_Inquiries/bulkDetail";
$route['(' . CUSTUMER_ALLOWED . ')/transf-master-account'] = "Novo_Services/transfMasterAccount";
$route['(' . CUSTUMER_ALLOWED . ')/cards-inquiry'] = "Novo_Services/cardsInquiry";
$route['(' . CUSTUMER_ALLOWED . ')/transactional-limits'] = "Novo_Services/transactionalLimits";
$route['(' . CUSTUMER_ALLOWED . ')/commercial-twirls'] = "Novo_Services/commercialTwirls";
$route['(' . CUSTUMER_ALLOWED . ')/download-files'] = "Novo_DownloadFiles/exportFiles";
$route['(' . CUSTUMER_ALLOWED . ')/reports'] = "Novo_Reports/getReportsList";
$route['(' . CUSTUMER_ALLOWED . ')/account-status'] = "Novo_Reports/accountStatus";
$route['(' . CUSTUMER_ALLOWED . ')/replacement'] = "Novo_Reports/replacement";
$route['(' . CUSTUMER_ALLOWED . ')/extended-account-status'] = "Novo_Reports/extendedAccountStatus";
$route['(' . CUSTUMER_ALLOWED . ')/closing-balance'] = "Novo_Reports/closingBalance";
$route['(' . CUSTUMER_ALLOWED . ')/user-activity'] = "Novo_Reports/userActivity";
$route['(' . CUSTUMER_ALLOWED . ')/users-activity'] = "Novo_Reports/usersActivity";
$route['(' . CUSTUMER_ALLOWED . ')/recharge-made'] = "Novo_Reports/rechargeMade";
$route['(' . CUSTUMER_ALLOWED . ')/issued-cards'] = "Novo_Reports/issuedCards";
$route['(' . CUSTUMER_ALLOWED . ')/category-expense'] = "Novo_Reports/categoryExpense";
$route['(' . CUSTUMER_ALLOWED . ')/master-account'] = "Novo_Reports/masterAccount";
$route['(' . CUSTUMER_ALLOWED . ')/extended-master-account'] = "Novo_Reports/extendedMasterAccount";
$route['(' . CUSTUMER_ALLOWED . ')/status-master-account'] = "Novo_Reports/statusMasterAccount";
$route['(' . CUSTUMER_ALLOWED . ')/status-bulk'] = "Novo_Reports/statusBulk";
$route['(' . CUSTUMER_ALLOWED . ')/card-holders'] = "Novo_Reports/cardHolders";
$route['(' . CUSTUMER_ALLOWED . ')/tools'] = "Novo_Tools/options";
$route['(' . CUSTUMER_ALLOWED . ')/closing-budgets'] = "Novo_Reports/closingBudgets";
$route['(' . CUSTUMER_ALLOWED . ')/closing-budgets-excel'] = "Novo_Reports/exportToExcel";
$route['(' . CUSTUMER_ALLOWED . ')/master-account-excel'] = "Novo_Reports/exportToExcelMasterAccount";
$route['(' . CUSTUMER_ALLOWED . ')/master-account-pdf'] = "Novo_Reports/exportToPDFMasterAccount";
$route['(' . CUSTUMER_ALLOWED . ')/master-account-excel-consolid'] = "Novo_Reports/exportToExcelMasterAccountConsolid";
$route['(' . CUSTUMER_ALLOWED . ')/master-account-pdf-consolid'] = "Novo_Reports/exportToPDFMasterAccountConsolid";
$route['(' . CUSTUMER_ALLOWED . ')/empresa'] = "Novo_Tools/getEnterprise";
$route['(' . CUSTUMER_ALLOWED . ')/Contact'] = "Novo_Tools/addContact";
$route['(' . CUSTUMER_ALLOWED . ')/cambiar-email'] = "Novo_Tools/changeEmail";
$route['(' . CUSTUMER_ALLOWED . ')/cambiar-telefonos'] = "Novo_Tools/changeTelephones";
$route['(' . CUSTUMER_ALLOWED . ')/id-empresa-ob'] = "Novo_Reports/obtenerIdEmpresa";
$route['(' . CUSTUMER_ALLOWED . ')/user-activity-excel'] = "Novo_Reports/exportToExcelUserActivity";
$route['(' . CUSTUMER_ALLOWED . ')/user-activity-pdf'] = "Novo_Reports/exportToPDFUserActivity";

/*
|--------------------------------------------------------------------------
| OLD ROUTES
|--------------------------------------------------------------------------
*/
$route['(:any)/login'] = "users/login/$1";
$route['(:any)/validation'] = "users/validationAuth/$1";
$route['(:any)/terminos'] = "users/terminosCondiciones/$1";
$route['(:any)/usuario/config'] = "users/configUsuario/$1";
$route['(:any)/finsesion'] = "users/pantallaLogout/$1";
$route['(:any)/users/pass_recovery'] = "users/pass_recovery/$1";
$route['(:any)/users/recuperar-pass'] = "users/PassRecovery/$1";
$route['api/v1/(:any)/usuario/config/empresas'] = "users/getListaEmpresasUser/$1";
$route['api/v1/(:any)/usuario/config/infoEmpresa'] = "users/getInfoEmpresaUser/$1";
$route['api/v1/(:any)/usuario/config/perfilUsuario'] = "users/getPerfilUser/$1";
$route['api/v1/(:any)/usuario/config/ActualizarPerfilUsuario'] = "users/getActualizarPerfilUser/$1";
$route['api/v1/(:any)/usuario/config/agregarContacto'] = "users/getAgregarContactoEmpresa/$1";
$route['api/v1/(:any)/usuario/config/ActualizarContacto'] = "users/getActualizarContactoEmpresa/$1";
$route['api/v1/(:any)/usuario/config/eliminarContacto'] = "users/getEliminarContactoEmpresa/$1";
$route['api/v1/(:any)/usuario/config/InfoContactoEmpresa'] = "users/getContactoEmpresa/$1";
$route['api/v1/(:any)/usuario/config/ActualizarTlfEmpresa'] = "users/getActualizarTlfEmpresa/$1";
$route['api/v1/(:any)/usuario/config/consultarSucursales'] = "users/getConsultarSucursales/$1";
$route['api/v1/(:any)/usuario/config/agregarSucursales'] = "users/getAgregarSucursales/$1";
$route['api/v1/(:any)/usuario/config/actualizarSucursales'] = "users/getActualizarSucursales/$1";
$route['api/v1/(:any)/usuario/config/cargarSucursales'] = "users/cargarSucursales/$1";
$route['api/v1/(:any)/usuario/notificaciones/buscar'] = "users/Notificaciones/$1";
$route['api/v1/(:any)/usuario/notificaciones/envio'] = "users/NotificacionesEnvio/$1";
$route['(:any)/empresas/config'] = "users/configEmpresa/$1";
$route['(:any)/empresas/configsuc'] = "users/configSucursal/$1";
$route['(:any)/empresas/configdesc'] = "users/configDescargas/$1";
$route['(:any)/empresas/confignoti'] = "users/configNotificaciones/$1";
$route['(:any)/beneficios'] = "footer/pantallaBeneficios/$1";
$route['(:any)/condiciones'] = "footer/pantallaCondiciones/$1";
$route['(:any)/tarifas'] = "footer/pantallaTarifas/$1";
$route['(:any)/clave'] = "users/changePassNewUser/$1";
$route['(:any)/changePassNewUserAuth'] = "users/changePassNewUserAuth/$1";
$route['(:any)/logout'] = "users/logout/$1";
$route['(:any)/dashboard'] = "dashboard/index/$1";
$route['(:any)/dashboard/productos'] = "dashboard/dashboardProductos/$1";
$route['(:any)/dashboard/productos/detalle'] = "dashboard/dashboardProductosDetalle/$1";
$route['(:any)/dashboard/programas'] = "dashboard/programas/$1";
$route['(:any)/lotes'] = "lotes/pantallaCarga/$1";
$route['(:any)/lotes/carga'] = "lotes/pantallaCarga/$1";
$route['(:any)/lotes/detalle'] = "lotes/pantallaDetalleLote/$1";
$route['(:any)/lotes/confirmacion'] = "lotes/pantallaConfirmacion/$1";
$route['(:any)/lotes/autorizacion'] = "lotes/pantallaAutorizacion/$1";
$route['(:any)/lotes/reproceso'] = "lotes/pantallaReproceso/$1";
$route['(:any)/lotes/upload'] = "lotes/cargarLotes/$1";
$route['(:any)/lotes/innominada'] = "lotes_innominada/pantallaInno/$1";
$route['(:any)/lotes/innominada/afiliacion'] = "lotes_innominada/pantallaInnoInven/$1";
$route['(:any)/lotes/innominada/createCuentasInnominadas'] = "lotes_innominada/createCuentasInnominadas/$1";
$route['(:any)/lotes/innominada/listaCuentasInnominadas'] = "lotes_innominada/listaCuentasInnominadas/$1";
$route['(:any)/lotes/innominada/listaSucursalesInnominadas'] = "lotes_innominada/listaSucursalesInnominadas/$1";
$route['(:any)/lotes/innominada/generarReporteTarjetasInnominadas'] = "lotes_innominada/generarReporteTarjetasInnominadas/$1";
$route['(:any)/lotes/innominada/listaTarjetasInnominadas'] = "lotes_innominada/listaTarjetasInnominadas/$1";
$route['(:any)/lotes/innominada/eliminarLotesInnominadas'] = "lotes_innominada/eliminarLotesInnominadas/$1";
$route['(:any)/lotes/innominada/detalle'] = "lotes_innominada/pantallaDetalleInnoLote/$1";
$route['(:any)/getListaEmpresasJSON'] = "dashboard/getListaEmpresasJSON/$1";
$route['(:any)/getListaProductosJSON'] = "dashboard/getListaProductosJSON/$1";
$route['(:any)/getLotesPorConfirmarJSON'] = "lotes/getLotesPorConfirmarJSON/$1";
$route['(:any)/postCambiarEmpresaProducto'] = "dashboard/postCambiarEmpresaProducto/$1";
$route['(:any)/sucursales'] = "sucursales/VconfigSucursales/$1";
$route['(:any)/lotes/confirmacion/confirmar'] = "lotes/confirmarLote/$1";
$route['(:any)/lotes/autorizacion/firmar'] = "lotes/firmarLote/$1";
$route['(:any)/lotes/autorizacion/desasociar'] = "lotes/desasociarFirma/$1";
$route['(:any)/lotes/preliminar'] = "lotes/preliminarOS/$1";
$route['(:any)/lotes/calculo'] = "lotes/pantallaCalculoOSLote/$1";
$route['(:any)/lotes/autorizacion/eliminarAuth'] = "lotes/eliminarLotesPorAutorizar/$1";
$route['(:any)/lotes/autorizacion/detalle'] = "lotes/detalleLoteAuth/$1";
$route['api/v1/(:any)/reportes/detalleLoteAuthExpPDF'] = "lotes/expdetalleLoteAuthPDF/$1";
$route['api/v1/(:any)/reportes/detalleLoteAuthExpXLS'] = "lotes/expdetalleLoteAuthXLS/$1";
$route['api/v1/(:any)/lotes/confirmarPreOSL'] = "lotes/callAutorizarLote/$1";
$route['api/v1/(:any)/lotes/reproceso/crear'] = "lotes/crearBeneficiario/$1";
$route['api/v1/(:any)/lotes/reproceso/cargarMasivo'] = "lotes/cargarMasivoReproceso/$1";
$route['api/v1/(:any)/lotes/reproceso/buscar'] = "lotes/buscarListaBeneficiarios/$1";
$route['api/v1/(:any)/lotes/reproceso/modificar'] = "lotes/modificarBeneficiario/$1";
$route['api/v1/(:any)/lotes/reproceso/eliminar'] = "lotes/eliminarBeneficiario/$1";
$route['api/v1/(:any)/lotes/reproceso/reprocesar'] = "lotes/reprocesar/$1";
$route['api/v1/(:any)/lotes/reproceso/reprocesarMasivo'] = "lotes/reprocesarMasivo/$1";
$route['api/v1/(:any)/lotes/eliminar'] = "lotes/eliminarLotes/$1";
$route['api/v1/(:any)/lotes/detalle'] = "lotes/verDetalleBandeja/$1";
$route['api/v1/(:any)/lotes/lista/pendientes'] = "lotes/getLotesPorConfirmarJSON/$1";
$route['api/v1/(:any)/empresas/lista'] = "dashboard/getListaEmpresasJSON/$1";
$route['api/v1/(:any)/producto/lista'] = "dashboard/getListaProductosJSON/$1";
$route['api/v1/(:any)/empresas/cambiar'] = "dashboard/postCambiarEmpresaProducto/$1";
$route['api/v1/(:any)/login'] = "users/validationAuth/$1";
$route['api/v1/(:any)/reportes/cuentaConcentradora'] = "reportes/getCuentaConcentradora/$1";
$route['api/v1/(:any)/reportes/graficoCuentaConcentradora'] = "reportes/graficoCuentaConcentradora/$1";
$route['api/v1/(:any)/reportes/cuentaConcentradoraExpPDF'] = "reportes/expCuentaConcentradoraPDF/$1";
$route['api/v1/(:any)/reportes/cuentaConcentradoraExpXLS'] = "reportes/expCuentaConcentradoraXLS/$1";
$route['api/v1/(:any)/reportes/cuentaConcentradoraConsolidadoExpXLS'] = "reportes/expCuentaConcentradoraConsolidadoXLS/$1";
$route['api/v1/(:any)/reportes/cuentaConcentradoraConsolidadoExpPDF'] = "reportes/expCuentaConcentradoraConsolidadoPDF/$1";
$route['api/v1/(:any)/reportes/tarjetasEmitidasExpXLS'] = "reportes/expTarjetasEmitidasXLS/$1";
$route['api/v1/(:any)/reportes/tarjetasEmitidasExpPDF'] = "reportes/expTarjetasEmitidasPDF/$1";
$route['api/v1/(:any)/reportes/tarjetasemitidas'] = "reportes/getTarjetasEmitidas/$1";
$route['api/v1/(:any)/empresas/consulta-empresa-usuario'] = "dashboard/getListaEmpresasUsuariosJSON/$1";
$route['api/v1/(:any)/reportes/consulta-producto-empresa'] = "dashboard/callWSListaProductosUsuarioJSON/$1";
$route['api/v1/(:any)/reportes/estatusTarjetashabientes'] = "reportes/getEstatusTarjetasHabientes/$1";
$route['api/v1/(:any)/reportes/estatustarjetashabientesExpXLS'] = "reportes/expEstatusTarjetasHabientesXLS/$1";
$route['api/v1/(:any)/reportes/estatustarjetashabientesExpPDF'] = "reportes/expEstatusTarjetasHabientesPDF/$1";
$route['api/v1/(:any)/reportes/saldosamanecidos'] = "reportes/getSaldosAmanecidos/$1";
$route['api/v1/(:any)/reportes/estatuslotes'] = "reportes/getEstatusLotes/$1";
$route['api/v1/(:any)/reportes/reposiciones'] = "reportes/getReposiciones/$1";
$route['api/v1/(:any)/reportes/reposicionesExpXLS'] = "reportes/reposicionesExpXLS/$1";
$route['api/v1/(:any)/reportes/recargasrealizadas'] = "reportes/getRecargasRealizadas/$1";
$route['api/v1/(:any)/reportes/recargasRealizadasXLS'] = "reportes/expRecargasrealizadasXLS/$1";
$route['api/v1/(:any)/reportes/recargasRealizadasPDF'] = "reportes/expRecargasrealizadasPDF/$1";
$route['api/v1/(:any)/reportes/actividadporusuario'] = "reportes/getactividadporusuario/$1";
$route['api/v1/(:any)/reportes/estadosdecuenta'] = "reportes/getestadosdecuenta/$1";
$route['api/v1/(:any)/reportes/EstadosdeCuentaComp'] = "reportes/EstadosdeCuentaComprobante/$1";
$route['api/v1/(:any)/reportes/EstadosdeCuentaXLS'] = "reportes/expEstadosdeCuentaXLS/$1";
$route['api/v1/(:any)/reportes/EstadosdeCuentaPDF'] = "reportes/expEstadosdeCuentaPDF/$1";
$route['api/v1/(:any)/reportes/EstadosdeCuentaGrafico'] = "reportes/GraficoEstadosdeCuenta/$1";
$route['api/v1/(:any)/reportes/EstadosdeCuentaMasivo'] = "reportes/expEstadosdeCuentaComprobanteMasivo/$1";
$route['api/v1/(:any)/reportes/gastosporcategorias'] = "reportes/getgastosporcategorias/$1";
$route['api/v1/(:any)/reportes/gastosporcategoriasExpXLS'] = "reportes/expGastosporCategoriasXLS/$1";
$route['api/v1/(:any)/reportes/gastosporcategoriasExpPDF'] = "reportes/expGastosporCategoriasPDF/$1";
$route['api/v1/(:any)/reportes/estatuslotesExpXLS'] = "reportes/expEstatusLotesXLS/$1";
$route['api/v1/(:any)/reportes/estatuslotesExpPDF'] = "reportes/expEstatusLotesPDF/$1";
$route['api/v1/(:any)/reportes/saldosamanecidosExpXLS'] = "reportes/expSaldosAmanecidosXLS/$1";
$route['api/v1/(:any)/reportes/downPDFactividadUsuario'] = "reportes/downPDFactividadUsuario/$1";
$route['api/v1/(:any)/reportes/downXLSactividadUsuario'] = "reportes/downXLSactividadUsuario/$1";
$route['api/v1/(:any)/reportes/guarderiaExpPDF'] = "reportes/guarderiaExpPDF/$1";
$route['api/v1/(:any)/reportes/guarderiaExpXLS'] = "reportes/guarderiaExpXLS/$1";
$route['(:any)/reportes/cuenta-concentradora'] = "reportes/cuentaConcentradora/$1";
$route['(:any)/reportes/tarjetas-emitidas'] = "reportes/tarjetasemitidas/$1";
$route['(:any)/reportes/saldos-al-cierre'] = "reportes/saldosamanecidos/$1";
$route['(:any)/reportes/estatus-lotes'] = "reportes/estatuslotes/$1";
$route['(:any)/reportes/reposiciones'] = "reportes/reposiciones/$1";
$route['(:any)/reportes/recargas-realizadas'] = "reportes/recargasrealizadas/$1";
$route['(:any)/reportes/actividad-por-usuario'] = "reportes/actividadporusuario/$1";
$route['(:any)/reportes/estados-de-cuenta'] = "reportes/estadosdecuenta/$1";
$route['(:any)/reportes/gastos-por-categorias'] = "reportes/gastosporcategorias/$1";
$route['(:any)/reportes/tarjetahabientes'] = "reportes/tarjetahabientes/$1";
$route['(:any)/reportes/guarderia'] = "reportes/guarderia/$1";
$route['(:any)/reportes/comisiones'] = "reports_additional/ReportRecharWithComm/";
$route['(:any)/reportes/GuarderiaResult'] = "reportes/getGuarderiaResult/";
$route['(:any)/reportes/comisiones'] = "reports_additional/ReportRecharWithComm/$1";
$route['(:any)/reportes/comisiones-recarga'] = "reports_additional/callSystem/$1";
$route['(:any)/reportes/eliminar'] = "reports_additional/deleteReport/$1";
$route['(:any)/consulta/ordenes-de-servicio'] = "consultas/ordenesServicio/$1";
$route['(:any)/consulta/embozado'] = "consultas/embozado/$1";
$route['(:any)/consulta/lotes-por-facturar'] = "additional_inquiries/batchesByInvoice/$1";
$route['(:any)/consulta/servicio'] = "additional_inquiries/callWebService/$1";
$route['(:any)/consulta/tarjetahabientes'] = "consultas/tarjetahabientes/$1";
$route['api/v1/(:any)/consulta/tarjetaH'] = "consultas/getTarjetaHabiente/$1";
$route['api/v1/(:any)/consulta/downloadOS'] = "consultas/downloadOS/$1";
$route['api/v1/(:any)/consulta/downloadFacturacionOS'] = "consultas/downloadFacturacionOS/$1";
$route['api/v1/(:any)/consulta/anularos'] = "consultas/anularOS/$1";
$route['api/v1/(:any)/consulta/facturar'] = "consultas/facturar/$1";
$route['api/v1/(:any)/consulta/PagoOS'] = "consultas/PagoOS/$1";
$route['api/v1/(:any)/consulta/PagoOSProcede'] = "consultas/PagoOSProcede/$1";
$route['(:any)/servicios/transferencia-maestra'] = "servicios/transferenciaMaestra/$1";
$route['api/v1/(:any)/servicios/transferencia-maestra/buscar'] = "servicios/buscarTM/$1";
$route['api/v1/(:any)/servicios/transferencia-maestra/consultar'] = "servicios/consultar/$1";
$route['api/v1/(:any)/servicios/transferencia-maestra/abonar'] = "servicios/abonarAtarjeta/$1";
$route['api/v1/(:any)/servicios/transferencia-maestra/cargar'] = "servicios/cargarAtarjeta/$1";
$route['api/v1/(:any)/servicios/transferencia-maestra/consultarSaldo'] = "servicios/consultarSaldo/$1";
$route['api/v1/(:any)/servicios/transferencia-maestra/pagoTM'] = "servicios/RegargaTM/$1";
$route['api/v1/(:any)/servicios/transferencia-maestra/RegargaTMProcede'] = "servicios/RegargaTMProcede/$1";
$route['(:any)/servicios/consulta-tarjetas'] = "servicios/consultaTarjetas/$1";
$route['api/v1/(:any)/servicios/transferencia-maestra/buscarTarjetas'] = "servicios/buscarTarjetas/$1";
$route['api/v1/(:any)/servicios/consultaTarjetasExpXLS'] = "servicios/expConsultaTarejtasXLS/$1";
$route['api/v1/(:any)/servicios/cambiarEstadoemision'] = "servicios/cambiarEstadoemision/$1";
$route['api/v1/(:any)/servicios/cambiarEstadotarjeta'] = "servicios/cambiarEstadotarjeta/$1";
$route['(:any)/servicios/actualizar-datos'] = "servicios/actualizarDatos/$1";
$route['api/v1/(:any)/servicios/actualizar-datos/cargarArchivo'] = "servicios/cargarArchivo/$1";
$route['api/v1/(:any)/servicios/actualizar-datos/buscar-datos'] = "servicios/buscarDatos/$1";
$route['api/v1/(:any)/servicios/actualizar-datos/downXLS'] = "servicios/downXLS_AD/$1";
$route['(:any)/trayectos'] = "combustible/home/$1";
$route['(:any)/trayectos/modelo'] = "combustible/callAPImodel/$1";
$route['(:any)/trayectos/conductores'] = "combustible/drivers/$1";
$route['(:any)/trayectos/conductores/perfil'] = "combustible/driversAddEdit/$1";
$route['(:any)/trayectos/gruposVehiculos'] = "combustible/vehicleGroups/$1";
$route['(:any)/trayectos/vehiculos'] = "combustible/vehicles/$1";
$route['(:any)/trayectos/vehiculos/incluir'] = "combustible/carsAdd/$1";
$route['(:any)/trayectos/cuentas'] = "combustible/accounts/$1";
$route['(:any)/trayectos/detalleCuentas'] = "combustible/accountsDetails/$1";
$route['(:any)/trayectos/viajes'] = "combustible/travels/$1";
$route['(:any)/trayectos/viajes/detalles'] = "combustible/travelAddEdit/$1";
$route['(:any)/guias'] = "guides/all_guides/$1";
$route['(:any)/guias/(:any)'] = "guides/category_guides/$1/$2";
$route['(:any)/guias-detalle/(:any)'] = "guides/guides_detail/$1/$2";
$route['(:any)/controles/visa'] = "visa/index/$1";
$route['(:any)/card-list'] = "visa/callWsCardList/$1";
$route['(:any)/visa'] = "visa/callWSVisaModel/$1";
$route['(:any)/controles/visa/configurar'] = "visa/setup/$1";
$route['(:any)/pagos'] = "payment/payments/$1";
$route['(:any)/payments'] = "payment/callAPImodel/$1";
