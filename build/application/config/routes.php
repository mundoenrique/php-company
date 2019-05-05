<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "user";
$route['(:any)/async-call'] = "CallModels";

$route['(:any)/inicio'] = "User/home";
$route['(:any)/inicio/(:any)'] = "User/home";
$route['(:any)/recuperar-clave'] = "User/passwordRecovery";
$route['(:any)/cerrar-sesion'] = "User/finishSession";
$route['(:any)/cambiar-clave'] = "User/changePassword";

$route['(:any)/inf-beneficios'] = "Information/benefits";
$route['(:any)/inf-condiciones'] = "Information/terms";
$route['(:any)/inf-tarifas'] = "Information/rates";

//old routes
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
//$route['(:any)/usuario/notificaciones/buscar'] = "users/Notificaciones/$1";

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

//ROUTE FOR LOTES
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

///////////////////////////////////////////////////////

//ROUTES PARA TEST
/*
$route['(:any)/testSSH'] = "tests/testSSH/$1";
$route['(:any)/testGettext'] = "tests/testGettext/$1";
$route['(:any)/testZip'] = "tests/testZip/$1";
$route['(:any)/testExif'] = "tests/testExif/$1";
$route['(:any)/testFtp'] = "tests/testFtp/$1";
$route['(:any)/test/file'] = "tests/testFile/$1";
$route['(:any)/test'] = "tests/test/$1";
$route['(:any)/test/barra'] = "Tests/testBarra/$1";
$route['(:any)/testaes'] = "tests/test/$1";*/

//


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
$route['api/v1/(:any)/reportes/detalleLoteAuthExpXLS']= "lotes/expdetalleLoteAuthXLS/$1";
$route['api/v1/(:any)/lotes/confirmarPreOSL'] = "lotes/callAutorizarLote/$1";

//RUTAS DE ACCESO DESDE JS
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
$route['api/v1/(:any)/reportes/cuentaConcentradoraExpXLS']= "reportes/expCuentaConcentradoraXLS/$1";
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

//REPORTES
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

//CONSULTAS
$route['(:any)/consulta/ordenes-de-servicio'] = "consultas/ordenesServicio/$1";
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

$route['(:any)/servicios/actualizar-datos'] = "servicios/actualizarDatos/$1";
$route['api/v1/(:any)/servicios/actualizar-datos/cargarArchivo'] = "servicios/cargarArchivo/$1";
$route['api/v1/(:any)/servicios/actualizar-datos/buscar-datos'] = "servicios/buscarDatos/$1";
$route['api/v1/(:any)/servicios/actualizar-datos/downXLS'] = "servicios/downXLS_AD/$1";

//Routes for Combustible
$route['(:any)/trayectos'] = "combustible/home/$1";
$route['(:any)/trayectos/modelo'] = "combustible/callAPImodel/$1";

//Drivers
$route['(:any)/trayectos/conductores'] = "combustible/drivers/$1";
$route['(:any)/trayectos/conductores/perfil'] = "combustible/driversAddEdit/$1";

//vehicle Groups
$route['(:any)/trayectos/gruposVehiculos'] = "combustible/vehicleGroups/$1";
$route['(:any)/trayectos/vehiculos'] = "combustible/vehicles/$1";
$route['(:any)/trayectos/vehiculos/incluir'] = "combustible/carsAdd/$1";

//Accounts
$route['(:any)/trayectos/cuentas'] = "combustible/accounts/$1";
$route['(:any)/trayectos/detalleCuentas'] = "combustible/accountsDetails/$1";

//travels
$route['(:any)/trayectos/viajes'] = "combustible/travels/$1";
$route['(:any)/trayectos/viajes/detalles'] = "combustible/travelAddEdit/$1";

//guides
$route['(:any)/guias'] = "guides/all_guides/$1";
$route['(:any)/guias/(:any)'] = "guides/category_guides/$1/$2";
$route['(:any)/guias-detalle/(:any)'] = "guides/guides_detail/$1/$2";

//controles de pago visa
$route['(:any)/controles/visa'] = "visa/index/$1";
$route['(:any)/card-list'] = "visa/callWsCardList/$1";
$route['(:any)/visa'] = "visa/callWSVisaModel/$1";
$route['(:any)/controles/visa/configurar'] = "visa/setup/$1";
$route['(:any)/pagos'] = "payment/payments/$1";
$route['(:any)/payments'] = "payment/callAPImodel/$1";

$route['404_override'] = '';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
