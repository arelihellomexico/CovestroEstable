<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>@yield('title')</title>
  <!--Añadimos Bootstrap -->
  <script type="text/javascript" src="{{asset('assets/js/jquery.js')}}"></script>
  <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/bootstrap-toggle.css')}}">
  <script type="text/javascript" src="{{asset('assets/js/bootstrap.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('assets/js/bootstrap-toggle.js')}}"></script>
  <!--Linea para agregar css propio-->
  <link rel="stylesheet" href="{{asset('assets/css/estilos.css')}}">
  <!-- Iconos de FontAwesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <!-- Simple line icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">
  <!-- css datables -->
  <link rel="stylesheet" href="{{asset('assets/css/dataTables.bootstrap.min.css')}}">
  <!--link rel="stylesheet" href="{{asset('assets/css/jquery.dataTables.min.css')}}"-->
</head>




<div class="container-fluid barra-navegacion">
  <div class="row">
    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <nav class="navbar-up">
        <img src="{{asset('assets/img/logo.png')}}" alt="logo covestro" class="img-logo">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-md-8 col-lg-5 col-md-offset-4 col-lg-offset-7">
              <ul id="items-header">
                <li><a>Sistema de complemento de pagos | </a></li>
                <li><a>@yield('usuario')</a></li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
      <div class="colors-line static-line"></div>
    </div>
  </div>
</div>



<div class="container-fluid">
  <div class="row">

    <!-- Comienza menu izquierdo-->
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2 sidemenu" style="height:94%;overflow-y:scroll;">
      <h4 class="text-center text-muted">Menú</h4>
      <hr class="divider">
      <!--div class="container-items-menu"-->
      <div class="dropdown">
        <a href="#" class="@yield('tesoreriaMenu','tesoreria')" id="tesoreria"><small><img id="img-tesoreria" class="menu" src="{{asset('assets/img/currency.svg')}}"> Tesorería <i class="fas fa-chevron-down icon"></i></small></a>
        <div class="dropdown-content" id="DropdownTesoreria">
          @if(Session::get('tipo') == 1)
          <a href="{{url('/tesoreria')}}" class="item-dropdown dropdown-tesoreria"><small><i class="far fa-file"></i> Tesoreria Layouts</small></a>
          @endif
          <a href="{{url('/validacionTesoreria')}}" class="item-dropdown dropdown-tesoreria"><small><i class="far fa-file"></i> Validación Tesoreria</small></a>
        </div>
      </div>

      <div class="dropdown">
        <a class="@yield('creditoMenu','credito')" id="credito"><small><img href="" class="menu" id="img-credito" src="{{asset('assets/img/pay.svg')}}"> Crédito y Cobranza <i class="fas fa-chevron-down icon"></i></small></a>
        <div class="dropdown-content" id="DropdownCredito">
          @if(Session::get('tipo') == 1)
          <a href="{{url('/credito')}}" class="item-dropdown dropdown-credito"><small><i class="far fa-file"></i> Crédito y Cobranza Layout</small></a>
          @endif
          <a href="{{url('/validacionCredito')}}" class="item-dropdown dropdown-credito"><small><i class="far fa-file"></i> Validación Crédito y Cobranza</small></a>
        </div>
      </div>
      <div class="dropdown">
        <a href="#" class="@yield('sapMenu','sap')" id="sap"><small><img href="" class="menu" id="img-sap" src="{{asset('assets/img/bank.svg')}}"> SAP <i class="fas fa-chevron-down icon"></i></small></a>
        <div class="dropdown-content" id="DropdownSAP">
          @if(Session::get('tipo') == 1)
          <a href="{{url('/sap')}}" class="item-dropdown dropdown-sap"><small><i class="far fa-file"></i> SAP Layout</small></a>
          @endif
          <a href="{{url('/validacionSAT')}}" class="item-dropdown dropdown-sap"><small><i class="far fa-file"></i> Validación SAP</small></a>
        </div>
      </div>
      <div class="dropdown">
        <a href="#" class="@yield('concentradoMenu','concentrado')" id="concentrado"><small><i class="far fa-money-bill-alt" ></i>Complemento de <br>pagos<i class="fas fa-chevron-down icon"></i></small></a>
        <div class="dropdown-content" id="DropdownComplemento">
          <a href="{{ url('/archivosIntegrar') }}" class="item-dropdown dropdown-concentrado"><small>Integrador de archivos</small></a>
          <a href="{{ url('/integracion') }}" class="item-dropdown dropdown-concentrado"><small>Complemento de pagos</small></a>
          <a href="{{ url('/mostrarIncidencias') }}" class="item-dropdown dropdown-concentrado"><small>Corrección de incidencias</small></a>
          <a href="{{ url('/trafico') }}" class="item-dropdown dropdown-concentrado"><small>Tráfico de procesos</small></a>
        </div>
      </div>
      <div class="dropdown">
        <a href="#" class="@yield('reportesMenu','reportes')" id="reportes"><small><i class="fa fa-archive"></i>Reportes <i class="fas fa-chevron-down icon"></i></small></a>
        <div class="dropdown-content" id="DropdownReportes">
          <a href="{{ url('/ReportePago') }}" class="item-dropdown dropdown-reportes"><small>Reporte de Pagos</small></a>
          <a href="{{ url('/reportesMontos') }}" class="item-dropdown dropdown-reportes"><small>Reporte de Montos</small></a>
          <a href="{{ url('/reportesParcialidades') }}" class="item-dropdown dropdown-reportes"><small>Reporte de parcialidades</small></a>
          <a href="{{ url('/reporteComplementos') }}" class="item-dropdown dropdown-reportes"><small>Reporte de Complementos</small></a>
          <a href="{{ url('/reporteImpuestos') }}" class="item-dropdown dropdown-reportes"><small>Reporte de Impuestos</small></a>
        </div>
      </div>
      <div class="dropdown">
        <a href="#" class="@yield('clientesMenu','clientes')" id="clientes"><small><i class="icon-people"></i> Clientes</small><i class="fas fa-chevron-down icon"></i></a>
        <div class="dropdown-content" id="DropdownClientes">
          <a href="{{ url('/clientmanager') }}" class="item-dropdown dropdown-clientes"><small>Gestor de clientes</small></a>
          <a href="{{ url('/gestorCorreos') }}" class="item-dropdown dropdown-clientes"><small>Gestor de correos</small></a>
        </div>
      </div>
      <a href="{{ url('/responsables') }}" class="@yield('responsablesMenu','responsables')"><small><img href="" id="img-covestro" src="{{asset('assets/img/planning.svg')}}" class="menu"> Histórico</small></a>
      <a href="{{ url('/gestorEmpresa') }}" class="@yield('covestroMenu','covestro')" id="covestro"><small><img href="" id="img-covestro" src="{{asset('assets/img/planning.svg')}}" class="menu"> Gestor de datos Covestro</small></a>
      <a href="{{ url('/gestorUsuarios') }}" class="@yield('validacionMenu','validacion')"><small><img href="" id="img-covestro" src="{{asset('assets/img/planning.svg')}}" class="menu"> Gestor de Usuarios</small></a>

      <!-- Nuevo Menú -->
      <div class="dropdown">
        <a href="#" class="@yield('formularioComplementos','report')" id="report"><small><i class="far fa-money-bill-alt" ></i> Formulario<br>Complementos</small><i class="fas fa-chevron-down icon"></i></a>
        <div class="dropdown-content" id="DropdownReport">
          @if(Session::get('tipo') == 1)
          <a href="{{url('/complementoRecepcionPago')}}" class="item-dropdown dropdown-report"><i class="fa fa-clipboard menu"></i><small>Recepción de pagos</small></a>
          @endif
          <a href="{{url('/complementoRPTabla')}}" class="item-dropdown dropdown-report"><i class="fa fa-clipboard menu"></i><small>Excel pruebas</small></a>
        </div>
      </div>

      <a href="{{url('/logout')}}" class="@yield('cerrarMenu','cerrar')"><small><i class="icon-login"></i> Cerrar sesión</small></a>
      <!--/div-->
    </div>
  </div>
</div>
<div class="col-8 col-xs-8 col-sm-8 col-md-9 col-lg-10 col-md-offset-3 col-lg-offset-2 main">
  @yield('contenido')
</div>

<!-- Funcion para cambio de atributo sidemenu -->
<script type="text/javascript">
  window.addEventListener('load', icontesoreria, false);

  function icontesoreria() {
    var contenedorTesoreria = document.getElementById('tesoreria');
    contenedorTesoreria.addEventListener('mouseover', cambiarTesoreria, false);
    contenedorTesoreria.addEventListener('mouseout', restaurarTesoreria, false);
  }

  function restaurarTesoreria() {
    var imagen = document.getElementById('img-tesoreria').src = "{{asset('assets/img/currency.svg')}}";
  }

  function cambiarTesoreria() {
    var imagen = document.getElementById('img-tesoreria').src = "{{asset('assets/img/currency-white.svg')}}";
  }
</script>
<script type="text/javascript">
  window.addEventListener('load', iconcredito, false);

  function iconcredito() {
    var contenedorCredito = document.getElementById('credito');
    contenedorCredito.addEventListener('mouseover', cambiarCredito, false);
    contenedorCredito.addEventListener('mouseout', restaurarCredito, false);
  }

  function restaurarCredito() {
    var imagen = document.getElementById('img-credito').src = "{{asset('assets/img/pay.svg')}}";
  }

  function cambiarCredito() {
    var imagen = document.getElementById('img-credito').src = "{{asset('assets/img/pay-white.svg')}}";
  }
</script>
<script type="text/javascript">
  window.addEventListener('load', iconsap, false);

  function iconsap() {
    var contenedorSAP = document.getElementById('sap');
    contenedorSAP.addEventListener('mouseover', cambiarSAP, false);
    contenedorSAP.addEventListener('mouseout', restaurarSAP, false);
  }

  function restaurarSAP() {
    var imagen = document.getElementById('img-sap').src = "{{asset('assets/img/bank.svg')}}";
  }

  function cambiarSAP() {
    var imagen = document.getElementById('img-sap').src = "{{asset('assets/img/bank-white.svg')}}";
  }
</script>
<script type="text/javascript">
  window.addEventListener('load', iconcovestro, false);

  function iconcovestro() {
    var contenedorCovestro = document.getElementById('covestro');
    contenedorCovestro.addEventListener('mouseover', cambiarCovestro, false);
    contenedorCovestro.addEventListener('mouseout', restaurarCovestro, false);
  }

  function restaurarCovestro() {
    var imagen = document.getElementById('img-covestro').src = "{{asset('assets/img/planning.svg')}}";
  }

  function cambiarCovestro() {
    var imagen = document.getElementById('img-covestro').src = "{{asset('assets/img/planning-white.svg')}}";
  }
</script>
<!-- Scripts para funcionalidad el sidemenu dropdown -->
<script type="text/javascript">
  //Tesoreria
  document.getElementById("tesoreria").onclick = function() {
    tesoreria()
  };

  function tesoreria() {
    document.getElementById("DropdownTesoreria").classList.toggle("show");
  }
  //Credito y cobranza
  document.getElementById("credito").onclick = function() {
    credito()
  };

  function credito() {
    document.getElementById("DropdownCredito").classList.toggle("show");
  }
  //SAP
  document.getElementById("sap").onclick = function() {
    sap()
  };

  function sap() {
    document.getElementById("DropdownSAP").classList.toggle("show");
  }

  //complemento
  document.getElementById("concentrado").onclick = function() {
    concentrado()
  };

  function concentrado() {
    document.getElementById("DropdownComplemento").classList.toggle("show");
  }
  //clientes
  document.getElementById("clientes").onclick = function() {
    clientes()
  };

  function clientes() {
    document.getElementById("DropdownClientes").classList.toggle("show");
  }
  //reportes
  document.getElementById("reportes").onclick = function() {
    reportes()
  };

  function reportes() {
    document.getElementById("DropdownReportes").classList.toggle("show");
  }
  // Complemento Recepcion de Pago
  document.getElementById("report").onclick = function() {
    report()
  };

  function report() {
    document.getElementById("DropdownReport").classList.toggle("show");
  }
</script>

<!-- Tablas Pagination -->
<script type="text/javascript" src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/dataTables.bootstrap.min.js')}}"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('.AllDataTable').DataTable({
      //"bSort" : false,
      "ordering": false,
      language: {

        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst": "Primero",
          "sLast": "Último",
          "sNext": "Siguiente",
          "sPrevious": "Anterior"
        },
        "oAria": {
          "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
      }
    });



  });
</script>

</html>