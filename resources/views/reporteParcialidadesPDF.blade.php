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
          <th class="text-muted text-center">ID_CLIENTE </th>
          <th class="text-muted text-center">CLIENTE</th>
          <th class="text-muted text-center">CLEARING</th>
          <th class="text-muted text-center">FOLIO FACTURA</th>
          <th class="text-muted text-center">PARCIALIDAD</th>
          <th class="text-muted text-center">SALDO ANTERIOR </th>
          <th class="text-muted text-center">IMPORTE PAGADO</th>
          <th class="text-muted text-center">SALDO INSOLUTO</th>
          <th class="text-muted text-center">TIPO DE CAMBIO</th>
          <th class="text-muted text-center">MONEDA</th>
          <th class="text-muted text-center">ESTATUS</th>

      </thead>
      <tbody>
      @foreach ($datos as $p)
      			<tr class="text-muted text-center" id="">
				<td>{{$p->id_cliente}}</td>
                <td>{{ $p->nombre_c}}</td>
                <td>{{ $p->clearing_document}}</td>
                <td>{{ $p->folio}}</td>
                <td>{{ $p->numparcialidad }}</td>
                <td>{{ $p->impsaldoant }}</td>
                <td>{{ $p->imppagado }}</td>
                <td>{{ $p->impsaldoins }}</td>
                <td>{{ $p->tipcambio }}</td>
                <td>{{ $p->moneda  }}</td> 
                @if($p->impsaldoins==0)
                <td>Pagado</td></tr>;
                @else
                <td>No liquidado</td></tr>;
                @endif
      @endforeach
          </tbody>
    </table>
</body>
</html>