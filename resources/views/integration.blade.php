<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Complemento de pagos</title>
    <!--Añadimos Bootstrap -->
    <script type="text/javascript" src="{{asset('assets/js/jquery.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <script type="text/javascript" src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <!--Linea para agregar css propio-->
    <link rel="stylesheet" href="{{asset('assets/css/estilos.css')}}">
    <!-- Iconos de FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  </head>
  <body>
    <div class="container-fluid barra-navegacion">
      <div class="row">
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


    <div class="container-fluid">
      <div class="row">
        <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2 sidemenu">
          <h3 class="text-center text-muted">Menu</h3>
          <hr>
          <div class="container-items-menu">
            <a href="{{url('/valida')}}" class="item"><i class="fas fa-cloud-upload-alt"></i> Validación de archivos</a>
            <!--<div class="dropdown">
              <a href="#" class="tesoreria" id="tesoreria"><i class="fas fa-coins"></i> Tesoreria <i class="fas fa-chevron-down icon"></i></a>
              <div class="dropdown-content" id="DropdownTesoreria">
                <a href="#" class="item-dropdown">Tesoreria Layouts</a>
              </div>
            </div>
            <div class="dropdown">
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
            <a href="{{url('/complemento')}}" class="item active"><i class="fas fa-money-bill"></i> Integración de Archivos</a>
            <!--<a href="#" class="item"><i class="fas fa-plus-square"></i> Gestor de clientes</a>
            <a href="#" class="item"><i class="fas fa-users"></i> Gestor de usuarios</a>
            <a href="#" class="item"><i class="fas fa-building"></i> Gestor de datos de la empresa</a>-->
            <a href="{{url('/logout')}}" class="item"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
          </div>
        </div>
        <div class="col-8 col-xs-8 col-sm-8 col-md-10 col-lg-10 col-md-offset-3 col-lg-offset-2 main">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1><i class="fas fa-money-bill"></i> Archivos Cargados</h1>
              </div>
              <div class="col-6 col-xs-6 col-sm-6 col-md-5 col-lg-6">
                <h3 class="text-muted">Selecciona los archivos que se enviarán al proceso de integración.</h3>
              </div>
              <div class="col-6 col-xs-6 col-sm-6 col-md-5 col-lg-6">
                <h1 class="text-muted text-right"><i class="fas fa-money-bill"></i></h1>
              </div>
            </div>
            <hr>
          </div>
          <br>
          <form action="{{url('/integracion')}}" method="post" id="formulario">
            {{csrf_field()}}
            <div class="row">
              <div class="col-6 col-xs-6 col-sm-6 col-md-5 col-lg-6">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th class="text-muted text-center txt-thead" colspan="2">Archivos de Tesorería</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($archivosTeso as $teso)
                    <tr>
                      <td><input type="checkbox" name="teso{{$teso->id_et}}"></td>
                      <td>{{$teso->nombre}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="col-6 col-xs-6 col-sm-6 col-md-5 col-lg-6">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th class="text-muted text-center txt-thead" colspan="2">Archivos de SAP</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($archivosSAP as $sap)
                    <tr>
                      <td><input type="checkbox" name="sap{{$sap->id_es}}"></td>
                      <td>{{$sap->nombre}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="col-6 col-xs-6 col-sm-6 col-md-3 col-lg-2">
                  <button type="submit" name="timbrar" class="btn btn-green">Integrar archivos</button>
              </div>
              <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-4 col-md-offset-2 col-lg-offset-6">
                <!--div class="btn-group" role="group" aria-label="...">
                  <button type="button" class="btn btn-pagination">Anterior</button>
                  <button type="button" class="btn btn-pagination active-pagination">1</button>
                  <button type="button" class="btn btn-pagination">2</button>
                  <button type="button" class="btn btn-pagination">3</button>
                  <button type="button" class="btn btn-pagination">Siguiente</button>
                </div-->
              </div>
            </div>
          </form>
          <br>
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
                    <p>Falta una columna. Asegúrate de que esté bien escrita o la hayas incluido en tu archivo.</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
                    <p>Algunos de los datos que subiste están erroneos. ¿Deseas subir tus datos así?</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                      <form id="cosas3" action="javascript:guardarDatos();">
                        {{csrf_field()}}
                        <button type="submit" class="btn btn-warninf">Aceptar</button>
                      </form>
                    </div>
                    <div class="col-xs-6" align="left">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
                    <p>Se han creado los archivos para timbrar. Para verlos, haz click en "Ver archivos"</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                    <div class="col-xs-6" align="right">
                      <a href="{{url('/archivos')}}"><button type="button" class="btn btn-primary">Ver Archivos</button></a>
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
                    <p>Hubo un error al crear los archivos.</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
        //Tesoreria
        document.getElementById("tesoreria").onclick = function() {tesoreria()};
        function tesoreria() {
          document.getElementById("DropdownTesoreria").classList.toggle("show");
        }
        //Credito y cobranza
        document.getElementById("credito").onclick = function() {credito()};
        function credito(){
          document.getElementById("DropdownCredito").classList.toggle("show");
        }
        //SAP
        document.getElementById("sap").onclick = function() {sap()};
        function sap(){
          document.getElementById("DropdownSAP").classList.toggle("show");
        }
    </script>
    <script type="text/javascript">
      function generarCorrectos(){
        var form = new FormData(document.getElementById('formulario'));
        var contenido = "";
        $.ajax({
          url: 'generarTxtCorrectos',
          type: 'post',
          data: form,
          processData: false,
          contentType: false,
          success: function(data){
            if(data.respuesta == "si"){
              $("#modal-exito").modal("show");
            }
            else{
              $("#modal-error").modal("show");
            }
          },
          error: function(){
            $("#modal-error").modal("show");
          }
        });
      }

    function generarErroneos(){
      var form = new FormData(document.getElementById('formulario2'));
      var contenido = "";
      $.ajax({
        url: 'generarTxtErroneos',
        type: 'post',
        data: form,
        processData: false,
        contentType: false,
        success: function(data){
          if(data.respuesta == "si"){
            $("#modal-exito").modal("show");
          }
          else{
            $("#modal-error").modal("show");
          }
        },
        error: function(){
          $("#modal-error").modal("show");
        }
      });
    }
    </script>
  </body>
</html>
