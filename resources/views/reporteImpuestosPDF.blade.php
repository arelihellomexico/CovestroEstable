<!DOCTYPE html>
<html>

  <head>
    <title>Tabla de Impuestos</title>
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
          <th class="text-muted text-center">ID del cliente</th>
          <th class="text-muted text-center">Cliente</th>
          <th class="text-muted text-center">País</th>
          <th class="text-muted text-center">Clearing Document</th>
          <th class="text-muted text-center">Factura</th>
          <th class="text-muted text-center">Monto de la Factura</th>
          <th class="text-muted text-center">Moneda de la factura</th>
          <th class="text-muted text-center">Fecha</th>
          <th class="text-muted text-center">Tipo de Impuesto</th>
          <th class="text-muted text-center">Base para Impuesto (MXN)</th>
          <th class="text-muted text-center">Impuesto (MXN)</th>
          <th class="text-muted text-center">Total (MXN)</th>
          <th class="text-muted text-center">Tipo de Cambio</th>
        </tr>
      </thead>
      <tbody>
      @foreach ($datos as $p)
      			<tr class="text-muted text-center" id="">
                <td id="archivos-recientes-tesoreria">{{$p->id_cliente}}</td>
                <td id="date">{{$p->nombre_c}}</td>
                @if($p->residencia == "")
                <td id="date">MEX</td>
                @else
                <td id="date">{{$p->residencia}}</td>
                @endif
                <td>{{ $p->clearings }}</td>
                <td>{{ $p->folio }}</td>
                <td>{{ $p->monto }}</td>
                <td>{{ $p->moneda }}</td>
                <td>{{ $p->fecha }}</td>
                <td>{{ $p->tipo_impuesto }}%</td>
                <td>{{ $p->sin_impuesto }}</td>
                <td>{{ $p->impuesto }}</td>
                <td>{{ $p->monto_mxn }}</td>
                <td>{{ $p->tipo_cambio }}</td>
              </tr>
          @endforeach
          </tbody>
    </table>
</body>
</html>