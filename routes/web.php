<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/
//Route::resource('ArchviosController', 'archvios');

use App\Usuarios;
use Illuminate\Support\Facades\Route;

Route::get('/clientmanager','ClientesController@index');
Route::get('/archivosIntegrar', 'ArchviosController@index');
Route::get('/gestorEmpresa','EmpresaController@index');
Route::get('/gestorUsuarios','UsuariosController@index');
Route::get('/gestorCorreos','CorreosClientesController@index');
Route::get('/reporteImpuestos','ReporteImpuestosController@index');
Route::get('/reporteComplementos','ReporteComplementosController@index');
Route::get('/ReportePago','ReportePagoController@index');
Route::get('/reportesParcialidades','ReporteParcialidadesController@index');
Route::get('/reportesMontos','ReporteMontosController@index');
// Vista para ComplementoRecepecionPago.php
Route::get('/complementoRecepcionPago','PagesController@complementoRecepcionPago');
Route::get('/complementoRPTabla','PagesController@complementoRPTabla');
//
Route::get('/correccionDeIncidencias','PagesController@correccionDeIncidencias');
Route::get('/integradorDeArchivos','PagesController@integradorDeArchivos');
Route::get('/','PagesController@login');
Route::get('/validacionAdministrador','PagesController@validacionAdministrador');
Route::get('/gestorclientes','GestorClientesController@index');
Route::get('/gestorusuarios','PagesController@gestorUsuarios');
Route::get('/gestorempresa','PagesController@gestorempresa');
Route::get('/userdetails','PagesController@userDetails');
Route::get('/trafico', 'ProcesosController@index');

Route::get('/validacionTesoreria','ValidacionTesoreriaController@index');
Route::get('/validacionSAT','ValidacionSAPController@index');
Route::get('/validacionCredito','ValidacionCreditoController@index');
Route::get('/complementoPagos','PagesController@complementoPagos');

Route::get('/tesoreria','PagesController@tesoreria');
Route::get('/credito','PagesController@credito');
Route::get('/sap','PagesController@sap');
Route::get('/layoutTesoreriaBancos','PagesController@layoutTesoreriaBancos');
//Paginas y funciones pendientes de ordenar en un controlador aparte o lo que se haga

Route::get('/layoutSAT', 'PagesController@layoutSAT');
Route::get('/crealayout', 'PagesController@createlay');
Route::get('/layoutformapago', 'PagesController@pagolay');
Route::get('/layoutCredito', 'PagesController@layoutCredito');
Route::get('/layoutgestbanco' , 'PagesController@layoutgestbanco');
Route::get('/responsables', 'ResponsablesController@index');


Route::get('/valida', 'PagesController@validacion');
Route::get('/complemento', 'ComplementoController@crearComplemento');
Route::get('/login', 'PagesController@inicio');
Route::get('/logout', 'PagesController@logout');
Route::get('/download/{id}', 'ComplementoController@descargar');
Route::get('/downloadPDF/{id}', 'ComplementoController@descargarPDF');
Route::get('/downloadXML/{id}', 'ComplementoController@descargarXML');
Route::post('/generador', 'ExcelController@import');
Route::post('/guardarPrueba', 'ExcelPruebaController@guardar');
Route::post('/generador', 'ExcelController@import');
Route::get('/integracion', 'ArchviosController@verIntegrados');
Route::get('/archivos', 'ComplementoController@checklist');

//Route::get('/archivos', 'ComplementoController@checklist');

Route::post('/incidencias', 'ArchviosController@incidencias');
Route::get('/mostrarIncidencias', 'ArchviosController@mostrarIncidencias');
Route::post('/incidenciasSAP', 'ArchviosController@CrearExcelSAP');
Route::post('/incidenciasTeso', 'ArchviosController@CrearExcelTesoreria');
Route::get('/pagosExcel','ReportePagoController@descargarExcel');
Route::get('/pagosPDF','ReportePagoController@descargarPDF');
Route::get('/impuestosExcel','ReporteImpuestosController@descargarExcel');
Route::get('/impuestosPDF','ReporteImpuestosController@descargarPDF');
Route::get('descargarPar','ReporteParcialidadesController@descargarPar');
Route::get('/parcialidadesPDF','ReporteParcialidadesController@descargarPDF');

Route::post('guardarLayoutTesoreria', 'LayoutsController@guardarTesoreria')->name('guardarTesoreria');
Route::post('guardarLayoutCredito', 'LayoutsController@guardarCredito')->name('guardarCredito');
Route::post('guardarLayoutSAP', 'LayoutsController@guardarSAP')->name('guardarSAP');
Route::post('cargarTesoreria', 'ValidacionTesoreriaController@guardarPrueba');
Route::post('recargarTesoreria', 'ValidacionTesoreriaController@borrarPrueba');
Route::post('pruebasTesoreria', 'LayoutsController@pruebasTesoreria');
Route::post('probarLayoutTesoreria', 'LayoutsController@probarLayoutTesoreria');
Route::post('cancelarLayoutTesoreria', 'LayoutsController@cancelarLayoutTesoreria');
Route::post('guardarTesoreria', 'ValidacionTesoreriaController@guardarDatos');
Route::get('editoTesoreria', 'LayoutsController@editoTesoreria');
Route::get('eliminoTesoreria', 'LayoutsController@eliminoTesoreria');
Route::post('actualizarLayoutTesoreria', 'LayoutsController@actualizarLayoutTesoreria');

Route::post('cargarCredito', 'ValidacionCreditoController@guardarPrueba');
Route::post('recargarCredito', 'ValidacionCreditoController@borrarPrueba');
Route::post('guardarCredito', 'ValidacionCreditoController@guardarDatos');
Route::post('pruebasCredito', 'LayoutsController@pruebasCredito');
Route::post('probarLayoutCredito', 'LayoutsController@probarLayoutCredito');
Route::post('cancelarLayoutCredito', 'LayoutsController@cancelarLayoutCredito');
Route::post('guardarCredito', 'ValidacionCreditoController@guardarDatos');
Route::get('editoCredito', 'LayoutsController@editoCredito');
Route::get('eliminoCredito', 'LayoutsController@eliminoCredito');
Route::post('actualizarLayoutCredito', 'LayoutsController@actualizarLayoutCredito');
Route::post('obtenerCredito', 'LayoutsController@obtenerCredito');
Route::post('cancelarActualizacionCredito', 'LayoutsController@cancelarActualizacionCredito');

Route::post('obtenerTesoreria', 'LayoutsController@obtenerTesoreria');
Route::post('cancelarActualizacionTesoreria', 'LayoutsController@cancelarActualizacionTesoreria');
Route::post('cargarSAP', 'ValidacionSAPController@guardarPrueba');
// cargar nuevo excel
Route::post('cargarSAP2', 'ValidacionSAPController@guardarPrueba2');
Route::post('cargarSAPForm', 'ValidacionSAPController@guardarPruebaForm');
Route::post('cargarSAPEsp', 'ValidacionSAPController@guardarPruebaEsp');
Route::post('recargarSAP', 'ValidacionSAPController@borrarPrueba');
Route::post('pruebasSAP', 'LayoutsController@pruebasSAP');
Route::post('probarLayoutSAP', 'LayoutsController@probarLayoutSAP');
Route::post('cancelarLayoutSAP', 'LayoutsController@cancelarLayoutSAP');
Route::post('guardarSAP', 'ValidacionSAPController@guardarDatos');
// guardar datos nuevo formulario
Route::post('guardarSAPEsp', 'ValidacionSAPController@guardarDatosEsp');
Route::post('guardarSAPForm', 'ValidacionSAPController@guardarDatosForm');
Route::get('editoSAP', 'LayoutsController@editoSAP');
Route::get('eliminoSAP', 'LayoutsController@eliminoSAP');
Route::post('actualizarLayoutSAP', 'LayoutsController@actualizarLayoutSAP');
Route::post('obtenerSAP', 'LayoutsController@obtenerSAP');
Route::post('cancelarActualizacionSAP', 'LayoutsController@cancelarActualizacionSAP');
//Route::post('cargarCredito', 'ValidacionCreditoController@guardarPrueba');
Route::post('guardarClientes', 'ClientesController@guardar');
Route::post('loggear', 'PagesController@loggear');
Route::post('generarTxtCorrectos', 'ComplementoController@generarTxtCorrectos');
// Generar txt especiales
Route::post('generarTxtCorrectosEsp', 'ComplementoController@generarTxtCorrectosEsp');
Route::post('generarTxtErroneos', 'ComplementoController@generarTxtErroneos');
Route::get('archivosTeso', 'ArchviosController@archivosTeso');
Route::get('eliminarArchivo', 'ArchviosController@eliminarArchivo');
Route::get('verProceso', 'ProcesosController@mostrarProceso');
Route::get('verProcesoH', 'ResponsablesController@buscar');
Route::post('buscarProcesos', 'ProcesosController@buscarProcesos');
Route::post('buscarArchivos', 'ProcesosController@buscarArchivos');
Route::post('actualizarStatus', 'ProcesosController@actualizarStatus');
Route::post('finalizarProceso', 'ProcesosController@finalizarProceso');
Route::post('archivosSAP', 'ArchviosController@archivosSAP');
Route::post('buscarIncidenciasSAP', 'ArchviosController@buscarIncidenciasSAP');
Route::post('historialTeso', 'ArchviosController@historialTeso');
Route::post('historialSAP', 'ArchviosController@historialSAP');
Route::post('integrando', 'ArchviosController@integrarComplemento');
// crear complemento especial
Route::post('integrandoEsp', 'ArchviosController@integrarComplementoEsp');
Route::post('borrarForm', 'ArchviosController@borrarFormEsp');
Route::post('crearExcel', 'ArchviosController@crearExcel');
Route::post('guardarBanco','EmpresaController@agregarBanco');
Route::post('actualizarDatos', 'EmpresaController@actualizarDatos');
Route::get('editarBanco','EmpresaController@editarBanco');
Route::post('actualizarBancos','EmpresaController@actualizarBancos');
Route::get('eliminarBanco','EmpresaController@eliminarBanco');
Route::post('agregarUsuario','UsuariosController@agregarUsuario');
Route::get('eliminarUsuario','UsuariosController@eliminarUsuario');
Route::get('editarUsuario','UsuariosController@editarUsuario');
Route::post('actualizarUsuario', 'UsuariosController@actualizarUsuario');

Route::get('seleccionarCliente','CorreosClientesController@seleccionarCliente');
Route::post('buscarClientes', 'CorreosClientesController@buscarClientes');
Route::post('agregarCorreos','CorreosClientesController@agregarCorreos');
Route::get('eliminarCorreos','CorreosClientesController@eliminarCorreos');
Route::get('buscarImpuestos', 'ReporteImpuestosController@buscar');
Route::get('buscarComplementos', 'ReporteComplementosController@buscar');
Route::get('buscarPagos', 'ReportePagoController@buscar');
Route::get('buscarMontos', 'ReporteMontosController@buscar');
Route::get('busquedaRepotepar','ReporteParcialidadesController@buscar');
Route::get('descargarpar','ReporteParcialidadesController@descargarPar');
Route::get('descargarpdf','ReporteParcialidadesController@descargarPDF');
//Route::get('pagosExcel','ReportePagoController@descargarExcel');
Route::get('parcialidadesExcel','ReporteParcialidadesController@descargarExcel');
//Route::get('impuestosExcel','ReporteImpuestosController@descargarExcel');
Route::get('complementosExcel','ReporteComplementosController@descargarExcel');
//Route::get('pagosPDF','ReportePagoController@descargarPDF');
