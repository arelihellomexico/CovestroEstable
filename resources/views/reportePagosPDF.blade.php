<!DOCTYPE html>
<html>

  <head>
    <title>Tabla de Pagos</title>
    <!--Añadimos Bootstrap -->
    <script type="text/javascript" src="{{asset('assets/js/jquery.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-toggle.css')}}">
    <script type="text/javascript" src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/bootstrap-toggle.js')}}"></script>
    <!--Linea para agregar css propio-->
  </head>
<body>
	<table class="table table-bordered">
      <thead>
        <tr>
           <th class="text-muted text-center">Clearing_Document</th>
          <th class="text-muted text-center">Fecha</th>
          <th class="text-muted text-center">Cliente</th>
          <th class="text-muted text-center">ID_Cliente</th>
          <th class="text-muted text-center">Monto</th>
          <th class="text-muted text-center">Moneda</th>
        </tr>
      </thead>
      <tbody>
      @foreach($datos as $p)
      <tr><td>{{ $p->clearing_document}}</td>
      <td>{{ $p->fechabus}}</td>
      <td>{{ $p->nombre_c}}</td>
      <td>{{ $p->id_cliente}}</td>
      <td>{{ $p->montoP}}</td>
      <td>{{ $p->monedaP}}</td></tr>;
          @endforeach
          </tbody>
    </table>
</body>
</html>