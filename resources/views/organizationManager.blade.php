<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Gestor de datos de la empresa</title>
    <!--Añadimos Bootstrap -->
    <script type="text/javascript" src="{{asset('assets/js/jquery.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <script type="text/javascript" src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <!--Linea para agregar css propio-->
    <link rel="stylesheet" href="{{asset('assets/estilos/index.css')}}">
    <!-- Font awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
  </head>
  <body>
    <!--Creacion del header de Covestro -->
    <header class="header">
      <img src="{{asset('assets/img/logo.png')}}" alt="Covestro Logotipo" class="logo-header"/>
      <ul class="menu-header">
        <li class=" item-menu">Sistema de complementos de pagos |</li>
        <li class="dropdown item-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Usuarios<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
        <li class=" item-menu"><i class="fas fa-bell"></i></li>
      </ul>
    </header>
    <div class="colors-line"></div>
    <!-- contenido y sidemenu -->
    <div class="container-fluid">
      <div class="row">
        <!-- Start Sidemenu -->
        <div class="col-2 col-sm-2 col-md-2 col-lg-2 ctn-sidemenu">
          <p class="text-muted lead text-center">Menu</p>
          <hr class="divider">
          <ul class="ctn-sidemenu">
            <li class="item-sidemenu text-left text-muted"><i class="fas fa-tachometer-alt"></i> Dashboard</li>
            <li class="item-sidemenu text-left text-muted"><i class="fas fa-cloud-upload-alt"></i> Validación de archivos</li>
            <li class="item-sidemenu text-left text-muted"><i class="far fa-copy"></i> Consultas</li>
            <li class="item-sidemenu text-left text-muted"><i class="far fa-money-bill-alt"></i> Concentrado de complementos de pagos</li>
            <li class="item-sidemenu text-left text-muted"><i class="fas fa-users"></i> Gestor de clientes</li>
            <li class="item-sidemenu text-left text-muted"><img src="{{asset('assets/img/team.png')}}" alt="" class="icon-sidemenu"> Gestor de usuarios</li>
            <li class="item-sidemenu text-left text-muted active-item-yellow"><img src="{{asset('assets/img/analysis.png')}}" alt="" class="icon-sidemenu"> Gestor de datos de la empresa</li>
            <li class="item-sidemenu text-left text-muted"><img src="{{asset('assets/img/bars.png')}}" alt="" class="icon-sidemenu"> Reportes</li>
          </ul>
          <div class="sidemenu">
            <!--<a class="item" href="{{url('/')}}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>-->
            <a class="item" href="{{url('/valida')}}"><i class="fas fa-cloud-upload-alt"></i> Validación de archivos</a>
            <!--<div class="dropdown">
              <a class="item-consulta" id="myBtn" href="{{url('/')}}"><i class="fas fa-donate"></i> Consultas <i class="fas fa-chevron-circle-down droptoggle"></i></a>
              <div class="dropdown-content" id="myDropdown">
                <a class="item-consulta item-dropdown" id="myBtn" href="{{url('/')}}"><i class="fas fa-balance-scale"></i> Tesorería</a>
              </div>
            </div>-->
            <a class="item" href="{{url('/complemento')}}"><i class="far fa-money-bill-alt"></i> Concentrado de complementos de pago</a>
            
            <a class="item" href="{{url('/clientmanager')}}"><i class="fas fa-users"></i> Gestor de clientes</a>
            <!--<a class="item" href="{{url('/gestorusuarios')}}"><i class="fas fa-user-tie"></i> Gestor de usuarios</a>-->
            <a class="item" href="{{url('/organizationmanager')}}"><i class="fas fa-building"></i> Gestor de datos de la empresa</a>
            <!--<a class="item" href="{{url('/')}}"><i class="fas fa-chart-pie"></i> Reportes</a>-->
          </div>
        </div>
        <!-- End Sidemenu -->
        <!-- Contenido principal -->
        <div class="col-10 col-sm-10 col-md-10 col-lg-10 ctn-index">
          <h2 class="text-left text-muted title"><img src="{{asset('assets/img/analysis.png')}}" alt="" class="img-header"> Gestor de datos de la empresa</h2>
          <p class="text-left text-muted subtitle Pdivider">Detalles del cliente <img src="{{asset('assets/img/analysis.png')}}" alt="" class="icon-sidemenu right"></p>
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
          <button type="button" name="button" class="btn btn-purple float-right">Agregar al banco</button>
          </div>
          <form class="" action="index.html" method="post">
            <div class="col-9 col-sm-9 col-md-9 col-lg-9"></div>
            <div class="col-2 col-sm-2 col-md-2 col-lg-2">
              <button type="button" name="button" class="btn btn-purple">Agregar al banco</button>
            </div>
          </div>
          <form class="" action="" method="post">
          <div class="col-4 col-sm-4 col-md-4 col-lg-4">
            <div class="form-group">
              <p for="name_r" class="text-muted">RFC de contribuyente (RFC_E)</p>
              <input type="text" class="form-control" id="rfc_e" value="{{$covestro->rfc_e}}" required>
            </div>
            <div class="form-group">
              <p for="name_r" class="text-muted">Nombre(NOMBRE_E)</p>
              <input type="text" class="form-control" id="nombre_e" value="{{$covestro->nombre_e}}" required>
            </div>
            <div class="form-group">
              <p for="name_r" class="text-muted">Número de pago(NUMPAGO)</p>
              <input type="number" class="form-control" id="numpago" value="{{$covestro->numpago}}" required>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              <button type="submit" name="button" class="btn btn-success">Guardar</button>
              <button type="edit" name="button" class="btn btn-primary">Editar</button>
              <button type="reset" name="button" class="btn btn-danger">Cancelar</button>
            </div>
          </div>
          <div class="col-6 col-sm-6 col-md-6 col-lg-6">
            <div class="col-4 col-sm-4 col-md-4 col-md-4 col-lg-4">
              <p class="text-muted">Calle(CALLE_E):</p>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-md-8 col-lg-8">
              <input type="text" class="form-control" value="{{$covestro->calle_e}}" name="calle_e"><br>
            </div>
            <div class="col-4 col-sm-4 col-md-4 col-md-4 col-lg-4">
              <p class="text-muted">Número exterior(NEXT_E):</p>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-md-8 col-lg-8">
              <input type="text" class="form-control" value="{{$covestro->numext_e}}" name="calle_r"><br>
            </div>
            <div class="col-4 col-sm-4 col-md-4 col-md-4 col-lg-4">
              <p class="text-muted">Número interior(NINT_E):</p>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-md-8 col-lg-8">
              <input type="text" class="form-control" value="{{$covestro->numint_e}}" name="nint_e"><br>
            </div>
            <div class="col-4 col-sm-4 col-md-4 col-md-4 col-lg-4">
              <p class="text-muted">Colonia(COLONIA_E):</p>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-md-8 col-lg-8">
              <input type="text" class="form-control" value="{{$covestro->colonia_e}}" name="colonia_e"><br>
            </div>
            <div class="col-4 col-sm-4 col-md-4 col-md-4 col-lg-4">
              <p class="text-muted">Ciudad o localidad(LOCAL_E):</p>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-md-8 col-lg-8">
              <input type="text" class="form-control" value="{{$covestro->localidad_e}}" name="local_e"><br>
            </div>
            <div class="col-4 col-sm-4 col-md-4 col-md-4 col-lg-4">
              <p class="text-muted">Referencias(REF_E):</p>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-md-8 col-lg-8">
              <input type="text" class="form-control" value="{{$covestro->referencia_e}}" name="ref_e"><br>
            </div>
            <div class="col-4 col-sm-4 col-md-4 col-md-4 col-lg-4">
              <p class="text-muted">Municipio(MUNICIP_E):</p>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-md-8 col-lg-8">
              <input type="text" class="form-control" value="{{$covestro->municipio_e}}" name="municip_e"><br>
            </div>
            <div class="col-4 col-sm-4 col-md-4 col-md-4 col-lg-4">
              <p class="text-muted">Estado(ESTADO_E):</p>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-md-8 col-lg-8">
              <input type="text" class="form-control" value="{{$covestro->estado_e}}" name="estado_e"><br>
            </div>
            <div class="col-4 col-sm-4 col-md-4 col-md-4 col-lg-4">
              <p class="text-muted">País(PAIS_E):</p>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-md-8 col-lg-8">
              <input type="text" class="form-control" value="{{$covestro->rfc_e}}" name="pais_e"><br>
            </div>
            <div class="col-4 col-sm-4 col-md-4 col-md-4 col-lg-4">
              <p class="text-muted">Código Postal(CP_E):</p>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-md-8 col-lg-8">
              <input type="text" class="form-control" value="{{$covestro->pais_e}}" name="cp_e"><br>
            </div>
          </div>
          </form>
          <form class="" action="index.html" method="post">
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <p class="text-left text-muted subtitle Pdivider">Agregar Banco <img src="{{asset('assets/img/analysis.png')}}" alt="" class="icon-sidemenu right"></p>
            <div class="col-5 col-sm-5 col-md-5 col-lg-5">
              <div class="form-group">
                <p for="name_r" class="text-muted">RFC de la cuenta del receptor(RFCCTABEN)</p>
                <input type="text" class="form-control" id="iddoc" required>
              </div>
              <div class="form-group">
                <p for="name_r" class="text-muted">Número de cuenta del receptor(CATABEN)</p>
                <input type="text" class="form-control" id="iddoc" required>
              </div>
            </div>
            <div class="col-5 col-sm-5 col-md-5 col-lg-5">
              <div class="form-group">
                <p for="name_r" class="text-muted">Moneda de pago(MONEDAP)</p>
                <select class="form-control" name="monedap" required>
                  <option>Pesos Mexicanos - MXN</option>
                  <option>Dolares - USD</option>
                  <option>Libra - LBT</option>
                </select>
              </div>
              <div class="form-group">
                <p for="name_r" class="text-muted">Tipo de cambio(TIPOCAMBIOP)</p>
                <select class="form-control" name="tipocambiop" required>
                  <option>Pesos Mexicanos $1.0</option>
                  <option>Dolares $17.04</option>
                  <option>Libra $ 0.0</option>
                </select>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <button type="submit" name="button" class="btn btn-success">Guardar</button>
                <button type="edit" name="button" class="btn btn-primary">Editar</button>
                <button type="reset" name="button" class="btn btn-danger">Cancelar</button>
              </div>
            </div>
          </div>
          </form>
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <p class="text-left text-muted subtitle Pdivider">Tabla Bancos <img src="{{asset('assets/img/analysis.png')}}" alt="" class="icon-sidemenu right"></p>
            <table class="table table-bordered table-hover space table-striped">
              <th>RFCCTABEN</th>
              <th>CATABEN</th>
              <th>MONEDAP</th>
              <th>TIPOCAMBIOP</th>
              <th>Editar</th>
              <th>Eliminar</th>
              <tr>
                <td class="text-muted"></td>
                <td class="text-muted"></td>
                <td class="text-muted"></td>
                <td class="text-muted"></td>
                <td class="text-muted"><i class="fas fa-edit"></i></td>
                <td class="text-muted"><i class="fas fa-trash-alt"></i></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/js/dropmenu.js')}}"></script>
  </body>
</html>
