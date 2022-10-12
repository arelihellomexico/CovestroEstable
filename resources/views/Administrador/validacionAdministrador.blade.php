@extends('Plantilla.plantilla')
@section('title','Validación de archivos administrador')
    <!-- Barra navegacion superior -->
    <div class="container-fluid barra-navegacion">
      <div class="row">
        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <nav class="navbar-up">
            <img src="{{asset('assets/img/logo.png')}}" alt="logo covestro" class="img-logo">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-4 col-md-offset-6 col-lg-offset-8">
                  <ul id="items-header">
                    <li><a>Sistema de complemento de pagos | </a></li>
                    <li><a>Usuario</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </nav>
          <div class="colors-line static-line"></div>
        </div>
      </div>
    </div>
    <!-- Fin Barra navegacion superior -->

    <!-- contenedor -->
    <div class="container-fluid">
      <div class="row">

        <!-- Comienza menu izquierdo-->
        <div class="col-4 col-xs-4 col-sm-4 col-md-2 col-lg-2 sidemenu">
          <h4 class="text-center text-muted">Menu</h4>
          <hr>
          <div class="container-items-menu">
            <a href="{{url('/valida')}}" class="item active"><i class="fas fa-cloud-upload-alt"></i> Validación de archivos</a>
            <!--<div class="dropdown">
              <a href="#" class="tesoreria" id="tesoreria"><i class="fas fa-coins"></i> Tesoreria <i class="fas fa-chevron-down icon"></i></a>
              <div class="dropdown-content" id="DropdownTesoreria">
                <a href="#" class="item-dropdown">Tesoreria Layouts</a>
              </div>
            </div>-->
            <!--<div class="dropdown">
              <a href="#" class="credito" id="credito"><i class="fas fa-hand-holding-usd"></i> Crédito y Cobranza <i class="fas fa-chevron-down icon"></i></a>
              <div class="dropdown-content" id="DropdownCredito">
                <a href="#" class="item-dropdown">Crédito y Cobranza Layout</a>
              </div>
            </div>
            <div class="dropdown">
              <a href="#" class="sap" id="sap"><i class="fas fa-university"></i> SAP <i class="fas fa-chevron-down icon"></i></a>
              <div class="dropdown-content" id="DropdownSAP">
                <a href="#" class="item-dropdown">SAP Layout</a>
              </div>
            </div>-->
            <a href="{{url('/complemento')}}" class="item"><i class="fas fa-money-bill"></i> Concentrado de Complemento de pagos</a>
            <!--<a href="#" class="item"><i class="fas fa-plus-square"></i> Gestor de clientes</a>
            <a href="#" class="item"><i class="fas fa-users"></i> Gestor de usuarios</a>
            <a href="#" class="item"><i class="fas fa-building"></i> Gestor de datos de la empresa</a>-->
            <a href="{{url('/logout')}}" class="item"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
          </div>
        </div>
        <!-- Términa menu izquierdo -->

        <div class="col-8 col-xs-8 col-sm-8 col-md-10 col-lg-10 col-md-offset-2 col-lg-offset-2 main">
          <div class="container-fluid">

            <!--Encabezado-->
            <div class="row">
              <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h2 class="tect-left"><i class="fas fa-cloud-upload-alt"></i>PANEL DE ADMINISTRADOR</h2>
              </div>
              <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <p class="text-muted lead text-left">Administración</p>
              </div>
              <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <p class="text-muted lead text-right"><i class="fas fa-university"></i></p>
              </div>
            </div>
            <hr>
            <!-- Fin encabezado -->
            <form class="form-inline" id="formulario" action="javascript:cargarTabla()" method="post">
              {{csrf_field()}}
            <div class="row"><!-- Seleccion de layout-->
              <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-4">
                  <div class="form-group">
                    <label for="layout" class="text-muted"><small>Selecciona Layout:</small></label>
                    <select id="layout" name="layout" class="form-control layo">

                    </select>
                  </div>
              </div>
            </div>
            <br>

            <div class="row">
              <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-muted text-center"><big><i class="fas fa-cloud-upload-alt"></i></big></h1>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-xs-12 col-sm-12 col-md-2 col-lg-2 col-md-offset-2 col-lg-offset-1">
                <p class="text-right text-muted">Subir archivo:</p>
              </div>
              <div class="col-12 col-xs-12 col-sm-12 col-md-5 col-lg-5">
                <input type="file" class="form-control" id="file" name="excel">
              </div>
              <div class="col-12 col-xs-12 col-sm-12 col-md-2 col-lg-2">
                <button type="submit" name="uploadFile" class="btn btn-morado"><i class="fas fa-upload"></i> Subir</button>
              </div>
            </div>
          </form>

          </div>

          <div class="row">
            <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6">
              <p class="text-muted lead text-left">Resultado de validación</p>
            </div>
            <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6">
              <p class="text-muted lead text-right"><i class="fas fa-university"></i></p>
            </div>
          </div>
          <hr>

          <!--div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-4 col-md-offset-6 col-lg-offset-8">
              <form class="form-inline" action="#" method="get">
                <label for="busqueda" class="text-muted"><small>Busqueda:</small></label>
                <div class="input-group">
                  <input type="text" id="busqueda" class="form-control" aria-describedby="busqueda">
                  <span class="input-group-addon" id="busqueda"><i class="fas fa-search"></i></span>
                </div>
              </form>
            </div>
          </div-->
          <br>

          <div class="row">
            <div class="col-10 col-xs-10 col-sm-10 col-md-11 col-lg-11 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1" id="campos_divider">
              <div class="campos_obligatorios">
                <p class="text-align text-muted lead">Campos obligatorios</p>
              </div>
              <div class="campos_opcionales">
                <p class="text-align text-muted lead">Campos opcionales</p>
              </div>
            </div>
          </div>
          <br>

          <div class="row">
            <div class="col-10 col-xs-10 col-sm-10 col-md-11 col-lg-11 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1" id="scroll">
              <table class="table table-bordered table-hover space table-striped">
                <thead>
                  <tr>
                      <th class="text-muted text-center txt-thead">Folio<br><span class="dato">(FOLIO)</span></th>
                      <th class="text-muted text-center txt-thead">Régimen Fiscal<br><span class="dato">(REGIMEN)</span></th>
                      <th class="text-muted text-center txt-thead">RFC del emisor<br><span class="dato">(RFC_E)</span></th>
                      <th class="text-muted text-center txt-thead">Nombre del emisor<br><span class="dato">(NOMBRE_E)</span></th>
                      <th class="text-muted text-center txt-thead">Dirección del emisor<br><span class="dato">(DIRECCION_E)</span></th>
                      <th class="text-muted text-center txt-thead">RFC del receptor<br><span class="dato">(RFC_R)</span></th>
                      <th class="text-muted text-center txt-thead">Nombre del receptor<br><span class="dato">(NOMBRE_R)</span></th>
                      <th class="text-muted text-center txt-thead">Dirección del receptor<br><span class="dato">(DIRECCION_R)</span></th>
                      <th class="text-muted text-center txt-thead">Número de Pago<br><span class="dato">(NUMPAGO)</span></th>
                      <th class="text-muted text-center txt-thead">Moneda de Pago<br><span class="dato">(MONEDAPAGO)</span></th>
                      <th class="text-muted text-center txt-thead">Tipo de Cambio<br><span class="dato">(TIPOCAMBIOP)</span></th>
                      <th class="text-muted text-center txt-thead">Monto de Pago<br><span class="dato">(MONTOPAGO)</span></th>
                      <th class="text-muted text-center txt-thead">Residencia fiscal<br><span class="dato">(RESIDENCIAFISCAL)</span></th>
                      <th class="text-muted text-center txt-thead">Número de registro tributario<br><span class="dato">(NUMREGIDTRIB)</span></th>
                      <th class="text-muted text-center txt-thead">Lugar de expedición<br><span class="dato">(LUGAREXPEDICION)</span></th>
                      <th class="text-muted text-center txt-thead">Confirmación<br><span class="dato">(CONFIRMACION)</span></th>
                      <th class="text-muted text-center txt-thead">TIPOCAD<br><span class="dato">(TIPOCAD)</span></th>
                      <th class="text-muted text-center txt-thead">CERTP<br><span class="dato">(CERTP)</span></th>
                      <th class="text-muted text-center txt-thead">CADENAP<br><span class="dato">(CADENAP)</span></th>
                      <th class="text-muted text-center txt-thead">SELLO<br><span class="dato">(SELLOP)</span></th>
                      <th class="text-muted text-center txt-thead">Folio de la factura<br><span class="dato">(FOLIO)</span></th>
                      <th class="text-muted text-center txt-thead">ID del documento<br><span class="dato">(ID_DOC)</span></th>
                      <th class="text-muted text-center txt-thead">Parcialidades<br><span class="dato"></span></th>
                    </tr>
                </thead>
                <tbody id="cuerpo">
                </tbody>
              </table>
            </div>
          </div>
          <br>

          <!--div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-3 col-md-offset-8 col-lg-offset-9">
              <nav aria-label="#">
                <ul class="pagination pagination-sm">
                  <li><a href="#" aria-label="Previous">Anterior</a></li>
                  <li class="active"><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">Siguiente</a></li>
                </ul>
              </nav>
            </div>
          </div-->
          <br>

          <div class="row">
          <div class="col-4 col-sm-4 col-md-4 col-lg-3 col-md-offset-1 col-lg-offset-1">
            <!--form class="" action="#" method="get"-->
            <form action="javascript:guardarDatosVerificar();" method="post" id="cosas2"><!--Este es tu form solo descomenta-->
              {{csrf_field()}}
              <button type="submit" class="btn btn-green"><small>Enviar a integración</small></button>
            </form>
          </div>
          <div class="col-4 col-sm-4 col-md-3 col-lg-2">
            <!-- Este form no sirve solo es para ver front -->
            <!--form class="" action="#" method="get"-->
            <form action="javascript:eliminarDatos();" method="post" id="cosas"><!--Este es tu form solo descomenta-->
              {{csrf_field()}}
              <button type="submit" class="btn btn-pink"><small>Volver a cargar</small></button>
            </form>
          </div>
          <!--div class="col-4 col-sm-4 col-md-2 col-lg-2">
            <button type="submit" class="btn btn-blue"><small>Descargar el reporte de insidencias</small></button>
          </div-->
        </div>

          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
          <div class="modal fade" id="modal-falta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <img src="{{asset('assets/img/signo2.png')}}">
                    <h3><strong>Archivo Incompleto</strong></h3>
                    <p>¡Ups!, no hemos podido encontrar una columna. Asegurese de que todas las columnas estén bien escritas o se hayan incluido en el archivo según el LayOut elegido.</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin Modal -->
          <!-- Modal -->
          <div class="modal fade" id="modal-verifica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <img src="{{asset('assets/img/signo.png')}}">
                    <h3><strong>Datos incorrectos</strong></h3>
                    <p>Algunos datos que ha cargado son incorrectos. ¿Desea de todos modos enviarlos a integración de archivos? Es probable que esto genere un error.</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-5" align="right">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div class="col-xs-7" align="left">
                      <form id="cosas3" action="javascript:guardarDatos();">
                        {{csrf_field()}}
                        <button type="submit" class="btn btn-primary">Si, enviar de todos modos.</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin Modal -->
          <!-- Modal -->
          <div class="modal fade" id="modal-exito" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <img src="{{asset('assets/img/singo1.png')}}">
                    <h3><strong>Exito</strong></h3>
                    <p>Se han subido tus datos correctamente.</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin Modal -->
          <!-- Modal -->
          <div class="modal fade" id="modal-no-carga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <img src="{{asset('assets/img/signo2.png')}}">
                    <h3><strong>Error</strong></h3>
                    <p>No hay nada que enviar.</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin Modal -->
          <!-- Modal -->
          <div class="modal fade" id="modal-no-archivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <img src="{{asset('assets/img/signo2.png')}}">
                    <h3><strong>Error</strong></h3>
                    <p>No hay ningun archivo para cargar.</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin Modal -->
          <!-- Modal -->
          <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <img src="{{asset('assets/img/signo2.png')}}">
                    <h3><strong>Error</strong></h3>
                    <p>Hubo un error al subir tus datos.</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin modal -->
          <div class="modal fade" id="modal-cargando" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <h3><strong>Cargando</strong></h3>
                    <img src="http://192.168.0.3/complemento_de_pago_1.0.0/Covestro/covestro/covestro/public/assets/img/cargando-loading-039.gif" width="500">
                    <p>Espera un momento. Se están cargando tus archivos.</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin modal -->
        </div>
      </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/js/dropmenu.js')}}"></script>
    <script type="text/javascript">
    var may = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
    var min = "abcdefghijklmnñopqrstuvwxyz";
    var num = "1234567890.,^`+-*/_=¨´~{}[]:;$%&()#@";
    var rfccaracter = ".,^`+-*/_=¨´~{}[]:;$%&()#@ ";
    var letras = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
    var cantidad = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ^`+-*/_=¨´~{}[]:;$%&()#@";
    var incidencias = false;

    function cargarTabla(){
      var form = new FormData(document.getElementById('formulario'));
      var contenido = "";
      var reporte = "";
      if($("#layout").val() != ""){
        console.log("El valor de archivo es "+$("#file").val())
        if($("#file").val() != null && $("#file").val() != ""){
          $.ajax({
            url: 'cargarSAP',
            type: 'post',
            data: form,
            processData: false,
            contentType: false,
            beforeSend: function(){
              $("#modal-cargando").modal('show');
              $("#subir").attr("disabled", true);
              $('#cuerpo').html("");
            },
            success: function(data){
              if(data.length < 1){
                console.log("No habia datos en tu archivo de excel.");
              }
              else{
                for(var i=0; i<data.length; i++){
                  contenido+='<tr>';
                  if(data[i].FOLIO != null && soloNumeros(data[i].FOLIO) == false && data[i].FOLIO.length == 10){
                    contenido+='<td class="text-muted">'+data[i].FOLIO+'</td>';
                  }
                  else{
                    if(data[i].FOLIO != null){
                      contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].FOLIO+'</td>';
                    }
                    else{
                      contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;"></td>';
                    }
                    reporte +="-> El clearing document no puede quedar vacio. Debe tener 10 caracteres.<br>";
                    incidencias = true;
                  }
                  if(data[i].REGIMEN != null){
                      contenido+='<td class="text-muted">'+data[i].REGIMEN+'</td>';
                    }
                    else{
                      contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;"></td>';
                      incidencias = true;
                    }
                  if(data[i].RFC_E != null && data[i].RFC_E.length == 12 && esRFC(data[i].RFC_E) == false){
                    contenido+='<td class="text-muted">'+data[i].RFC_E+'</td>';
                  }
                  else{
                    if(data[i].RFC_E != null){
                      contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].RFC_E+'</td>';
                    }
                    else{
                      contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;"></td>';
                    }
                    incidencias = true;
                  }
                  if(data[i].NOMBRE_E.indexOf("El cliente") == -1){
                    contenido+='<td class="text-muted">'+data[i].NOMBRE_E+'</td>';
                  }
                  else{
                    contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].NOMBRE_E+'</td>';
                    incidencias = true;
                  }
                  if(data[i].DIRECCION_E.indexOf("El cliente") == -1){
                    contenido+='<td class="text-muted">'+data[i].DIRECCION_E+'</td>';
                  }
                  else{
                    contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].DIRECCION_E+'</td>';
                    incidencias = true;
                  }
                  if(data[i].RFC_R.indexOf("El cliente") == -1){
                    contenido+='<td class="text-muted">'+data[i].RFC_R+'</td>';
                  }
                  else{
                    contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].RFC_R+'</td>';
                    incidencias = true;
                  }
                  if(data[i].NOMBRE_R.indexOf("El cliente") == -1){
                    if(data[i].NOMBRE_R == null){
                      contenido+='<td class="text-muted"></td>';
                    }
                    else{
                      contenido+='<td class="text-muted">'+data[i].NOMBRE_R+'</td>';
                    }
                  }
                  else{
                    contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].NOMBRE_R+'</td>';
                    incidencias = true;
                  }
                  if(data[i].DIRECCION_R.indexOf("El cliente") == -1){
                    contenido+='<td class="text-muted">'+data[i].DIRECCION_R+'</td>';
                  }
                  else{
                    contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].DIRECCION_R+'</td>';
                    incidencias = true;
                  }
                  if(data[i].NUMPAGO != null){
                      contenido+='<td class="text-muted">'+data[i].NUMPAGO+'</td>';
                    }
                    else{
                      contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;"></td>';
                    }
                  if(data[i].MONEDAPAGO != null && data[i].MONEDAPAGO.length == 3 && soloLetras(data[i].MONEDAPAGO) == false){
                    contenido+='<td class="text-muted">'+data[i].MONEDAPAGO+'</td>';
                  }
                  else{
                    if(data[i].MONEDAPAGO != null){
                      contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].MONEDAPAGO+'</td>';
                    }
                    else{
                      contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;"></td>';
                    }
                    reporte +="-> La moneda de Pago NO puede estar vacía, debe ser una palabra de 3 letras.<br>";
                    incidencias = true;
                  }
                  if(data[i].TIPOCAMBIOP != null && monto(data[i].TIPOCAMBIOP) == false){
                    contenido+='<td class="text-muted">'+data[i].TIPOCAMBIOP+'</td>';
                  }
                  else{
                    if(data[i].TIPOCAMBIOP != null){
                      contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].TIPOCAMBIOP+'</td>';
                    }
                    else{
                      contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;"></td>';
                    }
                    reporte +="-> El monto de pago NO puede estar vacío. Debe ser solo un número, con o sin decimales (17.63, 17.00, 17)<br>";
                    incidencias = true;
                  }
                  if(data[i].MONTOPAGO != null && monto(data[i].MONTOPAGO) == false){
                    contenido+='<td class="text-muted">'+data[i].MONTOPAGO+'</td>';
                  }
                  else{
                    if(data[i].MONTOPAGO != null){
                      contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].MONTOPAGO+'</td>';
                    }
                    else{
                      contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;"></td>';
                    }
                    reporte +="-> El monto de pago NO puede estar vacío. Debe ser solo un número, con o sin decimales (17.63, 17.00, 17)<br>";
                    incidencias = true;
                  }
                  contenido+='<td class="text-muted">'+data[i].RESIDENCIAFISCAL+'</td>';
                  if((data[i].RESIDENCIAFISCAL != null && data[i].NUMREGIDTRIB != null) || (data[i].RESIDENCIAFISCAL == null && data[i].NUMREGIDTRIB == null)){
                    contenido+='<td class="text-muted">'+data[i].NUMREGIDTRIB+'</td>';
                  }
                  else{
                    contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].NUMREGIDTRIB+'</td>';
                    incidencias = true;
                  }
                  contenido+='<td class="text-muted">'+data[i].LUGAREXPEDICION+'</td>';
                  if (data[i].CONFIRMACION != null){
                    contenido+='<td class="text-muted">'+data[i].CONFIRMACION+'</td>';
                  }
                  else{
                    contenido+='<td class="text-muted"></td>';
                  }
                  if (data[i].TIPOCADP != null){
                    contenido+='<td class="text-muted">'+data[i].TIPOCADP+'</td>';
                  }
                  else{
                    contenido+='<td class="text-muted"></td>';
                  }
                  if (data[i].CERTP != null){
                    contenido+='<td class="text-muted">'+data[i].CERTP+'</td>';
                  }
                  else{
                    contenido+='<td class="text-muted"></td>';
                  }
                  if (data[i].CADENAP != null){
                    contenido+='<td class="text-muted">'+data[i].CADENAP+'</td>';
                  }
                  else{
                    contenido+='<td class="text-muted"></td>';
                  }
                  if (data[i].SELLOP != null){
                    contenido+='<td class="text-muted">'+data[i].SELLOP+'</td>';
                  }
                  else{
                    contenido+='<td class="text-muted"></td>';
                  }
                  if(data[i].TIPODOC == "DZ"){
                    if(data[i].FOLIOS == "" || data[i].FOLIOS == null){
                      contenido+='<td class="text-muted"></td>';
                    }
                    else{
                      if(data[i].FOLIOS != null && (data[i].FOLIOS == "0" || data[i].FOLIOS == 0 || data[i].FOLIOS == "#")){
                        contenido+='<td class="text-muted"></td>';
                      }
                      else{
                        contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].FOLIOS+'</td>';
                      }
                    }
                  }
                  else{
                    if(data[i].FOLIOS != null && soloNumeros(data[i].FOLIOS) == false && data[i].FOLIOS.length == 7 && data[i].FOLIOS == data[i].ID_DOC){
                      contenido+='<td class="text-muted">'+data[i].FOLIOS+'</td>';
                    }
                    else{
                      if(data[i].FOLIOS != null){
                        contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].FOLIOS+'</td>';
                      }
                      else{
                        contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;"></td>';
                      }
                      incidencias = true;
                    }
                  }
                 if(data[i].TIPODOC == "DZ"){
                    if(data[i].ID_DOC == "" || data[i].ID_DOC == null){
                      contenido+='<td class="text-muted"></td>';
                    }
                    else{
                      if(data[i].ID_DOC != null && (data[i].ID_DOC == "0" || data[i].ID_DOC == 0 || data[i].ID_DOC == "#")){
                        contenido+='<td class="text-muted"></td>';
                      }
                      else{
                        contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].ID_DOC+'</td>';
                      }
                    }
                  }
                  else{
                    if(data[i].ID_DOC != null && soloNumeros(data[i].ID_DOC) == false && data[i].ID_DOC.length == 7 && data[i].ID_DOC == data[i].FOLIOS){
                      contenido+='<td class="text-muted">'+data[i].ID_DOC+'</td>';
                    }
                    else{
                      if(data[i].ID_DOC != null && data[i].ID_DOC != "" && data[i].ID_DOC != 0 && data[i].ID_DOC != "0" && data[i].ID_DOC != "#"){
                        contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;">'+data[i].ID_DOC+'</td>';
                      }
                      else{
                        contenido+='<td class="text-muted" style="background-color: #FF0000; color: FFF;"></td>';
                      }
                      incidencias = true;
                    }
                  }
                  contenido+='<td class="text-muted">'+data[i].PARCIAL+'</td>';
                  contenido+='<tr>';

                  $('#cuerpo').html("");
                  $('#cuerpo').append(contenido);
                }
              }
              $("#modal-cargando").modal('hide');
              $("#subir").attr("disabled", false);
            },
            error: function(){
              console.log("Error al guardar la prueba")
              $("#modal-cargando").modal('hide');
              $("#modal-falta").modal('show');
<<<<<<< HEAD
              $("#subir").attr("disabled", false);
=======
              $("#subir").attr("disabled", false);
>>>>>>> eb0d17ed2c5b7034e30551c178397edd4f664ca3
            }
          });
        }
        else{
          $("#modal-no-archivo").modal("show");
        }
      }
      else{
        $("#modal-cargando").modal('hide');
        $("#modal-layout").modal("show");
        $("#subir").attr("disabled", false);      }
    }

    function eliminarDatos(){
      var form = new FormData(document.getElementById('cosas'));
      var contenido = "";
      $.ajax({
        url: 'recargarSAP',
        type: 'post',
        data: form,
        processData: false,
        contentType: false,
        success: function(data){
          contenido+='<td class="text-muted" name="FRCCTAORD"></td>'+
                  '<td class="text-muted" name="BANCOORDEXT"></td>'+
                  '<td class="text-muted" name="CTAORD"></td>'+
                  '<td class="text-muted" name="FORMAP"></td>'+
                  '<td class="text-muted" name="MONEDAP"></td>'+
                  '<td class="text-muted" name="MONTOP"></td>'+
                  '<td class="text-muted" name="NUMEROPERP"></td>'+
                  '<td class="text-muted" name="Complemento"></td>'+
                  '<td class="text-muted" name="RFCCTABEN"></td>'+
                  '<td class="text-muted" name="CATABEN"></td>'+
                  '<td class="text-muted" name="RFCCTABEN"></td>'+
                  '<td class="text-muted" name="CATABEN"></td>';

          $('#cuerpo').html("");
          $('#cuerpo').append(contenido);
        },
        error: function(){
          console.log("Error al guardar la prueba")
        }
      });
    }

    function guardarDatosVerificar(){
      var form = new FormData(document.getElementById('cosas3'));
      var contenido = "";
      if(incidencias == true){
        $("#modal-verifica").modal("show");
      }
      else{
        $.ajax({
          url: 'guardarSAP',
          type: 'post',
          data: form,
          processData: false,
          contentType: false,
          success: function(data){
            contenido+='<td class="text-muted" name="FRCCTAORD"></td>'+
                    '<td class="text-muted" name="BANCOORDEXT"></td>'+
                    '<td class="text-muted" name="CTAORD"></td>'+
                    '<td class="text-muted" name="FORMAP"></td>'+
                    '<td class="text-muted" name="MONEDAP"></td>'+
                    '<td class="text-muted" name="MONTOP"></td>'+
                    '<td class="text-muted" name="NUMEROPERP"></td>'+
                    '<td class="text-muted" name="Complemento"></td>'+
                    '<td class="text-muted" name="RFCCTABEN"></td>'+
                    '<td class="text-muted" name="CATABEN"></td>'+
                    '<td class="text-muted" name="RFCCTABEN"></td>'+
                    '<td class="text-muted" name="CATABEN"></td>';

            $('#cuerpo').html("");
            $('#cuerpo').append(contenido);
            if(data.respuesta == "2" || data.respuesta == 2){
              $("#modal-no-carga").modal("show");
            }
            else{
              $("#modal-exito").modal("show");
            }
          },
          error: function(){
            $("#modal-error").modal("show");
            console.log("Error al guardar la prueba")
          }
        });
      }
    }

    function guardarDatos(){
      var form = new FormData(document.getElementById('cosas2'));
      var contenido = "";
      $.ajax({
        url: 'guardarSAP',
        type: 'post',
        data: form,
        processData: false,
        contentType: false,
        success: function(data){
          contenido+='<td class="text-muted" name="FRCCTAORD"></td>'+
                  '<td class="text-muted" name="BANCOORDEXT"></td>'+
                  '<td class="text-muted" name="CTAORD"></td>'+
                  '<td class="text-muted" name="FORMAP"></td>'+
                  '<td class="text-muted" name="MONEDAP"></td>'+
                  '<td class="text-muted" name="MONTOP"></td>'+
                  '<td class="text-muted" name="NUMEROPERP"></td>'+
                  '<td class="text-muted" name="Complemento"></td>'+
                  '<td class="text-muted" name="RFCCTABEN"></td>'+
                  '<td class="text-muted" name="CATABEN"></td>'+
                  '<td class="text-muted" name="RFCCTABEN"></td>'+
                  '<td class="text-muted" name="CATABEN"></td>';

          $('#cuerpo').html("");
          $('#cuerpo').append(contenido);
          $("#modal-verifica").modal("hide");
          $("#modal-exito").modal("show");
        },
        error: function(){
          console.log("Error al guardar la prueba")
          $("#modal-verifica").modal("hide");
          $("#modal-error").modal("show");
        }
      });
    }

    function soloNumeros(texto){
      console.log(texto);
      var bandera = false;
      if(texto != null){
        for(var i = 0; i<letras.length; i++){
          if(texto.indexOf(letras.charAt(i)) != -1){
            bandera = true;
          }
        }
      }

      return bandera;
    }
<<<<<<< HEAD

=======

>>>>>>> eb0d17ed2c5b7034e30551c178397edd4f664ca3
    function soloLetras(texto){
      console.log(texto);
      var bandera = false;
      if(texto != null){
        for(var i = 0; i<num.length; i++){
          if(texto.indexOf(num.charAt(i)) != -1){
            bandera = true;
          }
        }
      }

      return bandera;
    }

    function monto(texto){
      console.log(texto);
      var bandera = false;
      if(texto != null){
        for(var i = 0; i<cantidad.length; i++){
          if(texto.indexOf(cantidad.charAt(i)) != -1){
            bandera = true;
          }
        }
      }

      return bandera;
    }

    function esRFC(texto){
      var bandera = false;
      if(texto != null){
        for(var i = 0; i<rfccaracter.length; i++){
          if(texto.indexOf(rfccaracter.charAt(i)) != -1){
            bandera = true;
          }
        }
      }

      return bandera;
    }
  </script>
  </body>
</html>
