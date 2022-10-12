@extends('Plantilla.plantilla')

@section('header')
@section('title','Reporte de Impuestos')
@section('barra-superior')
@section('reportesMenu','reportes-active')
@section('sidemenu')
@endsection
@endsection

@section('contenido')
<!--Encabezado-->
<div class="row">
  <div class="col-8 col-xs-8 col-sm-8 col-md-8 col-lg-8">
    <h2 class="text-left leadv"><i class="far fa-money-bill-alt"></i> REPORTES</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 container-header">
    <p class="text-muted text-left"><big>Reportes de Impuestos</big><img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-1">
      <a href="/descargarpar"></a>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Del periodo</label>
        <input id="fechaInicio" name="fechaInicio" type="date" placeholder="dd/mm/yyyy" class="input-date">
        <p class="label-error"></p>
      </div>
      <div class="form-group input-field">
        <label for="default-input-date">Al periodo</label>
        <input id="fechaFin" name="fechaFin" type="date" placeholder="dd/mm/yyyy" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">ID Cliente</label>
        <input id="cliente" name="cliente" type="text" placeholder="Escriba ID del cliente" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Factura</label>
        <input id="folio" name="folio" type="text" placeholder="Escriba número de factura" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Clearing Document</label>
        <input id="clearing" name="clearing" type="text" placeholder="Escriba Clearing document" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <button type="button" class="button btn-blue buscar-tabla" name="button" onclick="buscar();">Buscar</button>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-1">
      
    </div>
    <div id="bodyComplementos">
      
    </div>
    <div class="col-xs-11" align="center"  class="col-xs-11 " align="center">
       
         <a href="#" onclick="descargarPDF();"><button type="button" class="btn btn-danger">Descargar PDF </button></a>
         <a href="#" onclick="Descargar();"><button type="button" class="btn btn-success">Descargar Excel</button></a>
       </div>
    <div class="row" align="center">
      <center>
        <div class="col-8 col-xs-8 col-sm-8 col-md-8 col-lg-8">
          <h2 class="text-left leadv" id="monto_total_pesos"> </h2>
        </div>
      </center>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4 col-xs-offset-4 col-sm-offset-4 col-md-offset-3 col-lg-offset-4 text-center">
      
    </div>
  </div>
  <!-- Table Tesoreria -->
  <!--Encabezado-->
</div>
<!-- Fin encabezado -->
<script type="text/javascript">
  $(document).ready(function () {
      var valorDolares = 0;
      var valorPesos = 0;
      var totalDolares = 0;
      var totalPesos = 0;
      var tablaArchivos = '<div id="scroll-payment">';
    tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">ID del cliente</th>'+
          '<th class="text-muted text-center">Cliente</th>'+
          '<th class="text-muted text-center">País</th>'+
          '<th class="text-muted text-center">Clearing Document</th>'+
          '<th class="text-muted text-center">Factura</th>'+
          '<th class="text-muted text-center">Moneda</th>'+
          '<th class="text-muted text-center">Tipo de Cambio</th>'+
          '<th class="text-muted text-center">Fecha</th>'+
          '<th class="text-muted text-center">Parcialidad</th>'+
          '<th class="text-muted text-center">Saldo Anterior</th>'+
          '<th class="text-muted text-center">Importe Pagado</th>'+
          '<th class="text-muted text-center">Saldo Insoluto</th>'+
          '<th class="text-muted text-center">Tipo de Impuesto</th>'+
          '<th class="text-muted text-center">Base para Impuesto (MXN)</th>'+
          '<th class="text-muted text-center">Impuesto (MXN)</th>'+
          '<th class="text-muted text-center">Total (MXN)</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
      @foreach ($datos as $p)
      tablaArchivos += '<tr class="text-muted text-center" id="">'+
                '<td id="archivos-recientes-tesoreria">{{$p->id_cliente}}</td>'+
                '<td id="date">{{$p->nombre_c}}</td>'+
                '<td id="date">{{$p->residencia}}</td>'+
                '<td>{{ $p->clearing_document }}</td>'+
                '<td>{{ $p->folio }}</td>'+
                '<td>{{ $p->moneda }}</td>'+
                '<td>{{ $p->tipo_cambio_bancos }}</td>'+
                '<td>{{ $p->fechabus }}</td>'+
                '<td>{{ $p->numparcialidad }}</td>'+
                '<td>{{ $p->impsaldoant }}</td>'+
                '<td>{{ $p->imppagado }}</td>'+
                '<td>{{ $p->impsaldoins }}</td>'+
                '<td>{{ $p->tipo_impuesto }}%</td>'+
                '<td>{{ $p->base_impuesto }}</td>'+
                '<td>{{ $p->impuesto }}</td>'+
                '<td>{{ $p->total_impuesto }}</td>'+
              '</tr>';
              
                totalPesos = totalPesos + {{ $p->impuesto }};
                
      @endforeach
      tablaArchivos += '</tbody>'+
    '</table>';
    tablaArchivos += '<script type="text/javascript">$("#tablaComplementos").DataTable({'+
          'language:{'+
            '"sProcessing":     "Procesando...",'+
            '"sLengthMenu":     "Mostrar _MENU_ registros",'+
            '"sZeroRecords":    "No se encontraron resultados",'+
            '"sEmptyTable":     "Ningún dato disponible en esta tabla",'+
            '"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",'+
            '"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",'+
            '"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",'+
            '"sInfoPostFix":    "",'+
            '"sSearch":         "Buscar:",'+
            '"sUrl":            "",'+
            '"sInfoThousands":  ",",'+
            '"sLoadingRecords": "Cargando...",'+
            '"oPaginate": {'+
                '"sFirst":    "Primero",'+
                '"sLast":     "Último",'+
                '"sNext":     "Siguiente",'+
                '"sPrevious": "Anterior"'+
            '},'+
            '"oAria": {'+
                '"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",'+
                '"sSortDescending": ": Activar para ordenar la columna de manera descendente"'+
            '}'+
          '}'+
        '});<\/script></div>';
      $("#bodyComplementos").html(tablaArchivos);
      $("#monto_total_pesos").html("");
      $("#monto_total_pesos").html(new Intl.NumberFormat().format(totalPesos)+" Pesos Mexicanos");
  })
</script>
<script type="text/javascript">
  function buscar() {
    var valorDolares = 0;
      var valorPesos = 0;
      var totalDolares = 0;
      var totalPesos = 0;
   if(($("#fechaInicio").val() == "" && $("#fechaFin").val() != "") || ($("#fechaInicio").val() != "" && $("#fechaFin").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#default-input-date1").val() == "" && $("#default-input-date2").val() == ""){
      if($("#cliente").val() == "" && $("#folio").val() == "" && $("#clearing").val() == ""){
        alert("Debes buscar por fecha o por alguno de los otros filtros")
      }
      else{
        $.ajax({
          url: 'buscarImpuestos',
          type: 'get',
          data: {fechaInicio: $("#fechaInicio").val(), fechaFin:$("#fechaFin").val(), id_cliente:$("#cliente").val(), folio:$("#folio").val(), clearing:$("#clearing").val()},
          success: function (data) {
            document.getElementById("bodyComplementos").removeChild(document.getElementById("scroll-payment"));
            var tablaArchivos = '<div id="scroll-payment">';
    tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">ID del cliente</th>'+
          '<th class="text-muted text-center">Cliente</th>'+
          '<th class="text-muted text-center">País</th>'+
          '<th class="text-muted text-center">Clearing Document</th>'+
          '<th class="text-muted text-center">Factura</th>'+
          '<th class="text-muted text-center">Moneda</th>'+
          '<th class="text-muted text-center">Tipo de Cambio</th>'+
          '<th class="text-muted text-center">Fecha</th>'+
          '<th class="text-muted text-center">Parcialidad</th>'+
          '<th class="text-muted text-center">Saldo Anterior</th>'+
          '<th class="text-muted text-center">Importe Pagado</th>'+
          '<th class="text-muted text-center">Saldo Insoluto</th>'+
          '<th class="text-muted text-center">Tipo de Impuesto</th>'+
          '<th class="text-muted text-center">Base para Impuesto (MXN)</th>'+
          '<th class="text-muted text-center">Impuesto (MXN)</th>'+
          '<th class="text-muted text-center">Total (MXN)</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                '<td id="archivos-recientes-tesoreria">'+data[i].id_cliente+'</td>'+
                '<td id="date">'+data[i].nombre_c+'</td>'+
                '<td id="date">'+data[i].residencia+'</td>'+
                '<td>'+data[i].clearing_document +'</td>'+
                '<td>'+data[i].folio +'</td>'+
                '<td>'+data[i].moneda +'</td>'+
                '<td>'+data[i].tipo_cambio_bancos +'</td>'+
                '<td>'+data[i].fechabus +'</td>'+
                '<td>'+data[i].numparcialidad +'</td>'+
                '<td>'+data[i].impsaldoant +'</td>'+
                '<td>'+data[i].imppagado +'</td>'+
                '<td>'+data[i].impsaldoins +'</td>'+
                '<td>'+data[i].tipo_impuesto +'%</td>'+
                '<td>'+data[i].base_impuesto +'</td>'+
                '<td>'+data[i].impuesto +'</td>'+
                '<td>'+data[i].total_impuesto +'</td>'+
              '</tr>';

            
              totalPesos = parseFloat(totalPesos) + parseFloat(data[i].impuesto)
          }
          tablaArchivos += '</tbody>'+
        '</table>';
        tablaArchivos += '<script type="text/javascript">$("#tablaComplementos").DataTable({'+
              'language:{'+
                '"sProcessing":     "Procesando...",'+
                '"sLengthMenu":     "Mostrar _MENU_ registros",'+
                '"sZeroRecords":    "No se encontraron resultados",'+
                '"sEmptyTable":     "Ningún dato disponible en esta tabla",'+
                '"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",'+
                '"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",'+
                '"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",'+
                '"sInfoPostFix":    "",'+
                '"sSearch":         "Buscar:",'+
                '"sUrl":            "",'+
                '"sInfoThousands":  ",",'+
                '"sLoadingRecords": "Cargando...",'+
                '"oPaginate": {'+
                    '"sFirst":    "Primero",'+
                    '"sLast":     "Último",'+
                    '"sNext":     "Siguiente",'+
                    '"sPrevious": "Anterior"'+
                '},'+
                '"oAria": {'+
                    '"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",'+
                    '"sSortDescending": ": Activar para ordenar la columna de manera descendente"'+
                '}'+
              '}'+
            '});<\/script></div>';
          $("#bodyComplementos").html(tablaArchivos);
          $("#monto_total_pesos").html("");
          $("#monto_total_pesos").html(new Intl.NumberFormat().format(totalPesos)+" Pesos Mexicanos");
          },
          error: function () {
            alert("Error");
          }
        });
      }
     }
     else{
      $.ajax({
          url: 'buscarImpuestos',
          type: 'get',
          data: {fechaInicio: $("#fechaInicio").val(), fechaFin:$("#fechaFin").val(), id_cliente:$("#cliente").val(), folio:$("#folio").val(), clearing:$("#clearing").val()},
          success: function (data) {
            document.getElementById("bodyComplementos").removeChild(document.getElementById("scroll-payment"));
            var tablaArchivos = '<div id="scroll-payment">';
    tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">ID del cliente</th>'+
          '<th class="text-muted text-center">Cliente</th>'+
          '<th class="text-muted text-center">País</th>'+
          '<th class="text-muted text-center">Clearing Document</th>'+
          '<th class="text-muted text-center">Factura</th>'+
          '<th class="text-muted text-center">Moneda</th>'+
          '<th class="text-muted text-center">Tipo de Cambio</th>'+
          '<th class="text-muted text-center">Fecha</th>'+
          '<th class="text-muted text-center">Parcialidad</th>'+
          '<th class="text-muted text-center">Saldo Anterior</th>'+
          '<th class="text-muted text-center">Importe Pagado</th>'+
          '<th class="text-muted text-center">Saldo Insoluto</th>'+
          '<th class="text-muted text-center">Tipo de Impuesto</th>'+
          '<th class="text-muted text-center">Base para Impuesto (MXN)</th>'+
          '<th class="text-muted text-center">Impuesto (MXN)</th>'+
          '<th class="text-muted text-center">Total (MXN)</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                '<td id="archivos-recientes-tesoreria">'+data[i].id_cliente+'</td>'+
                '<td id="date">'+data[i].nombre_c+'</td>'+
                '<td id="date">'+data[i].residencia+'</td>'+
                '<td>'+data[i].clearing_document +'</td>'+
                '<td>'+data[i].folio +'</td>'+
                '<td>'+data[i].moneda +'</td>'+
                '<td>'+data[i].tipo_cambio_bancos +'</td>'+
                '<td>'+data[i].fechabus +'</td>'+
                '<td>'+data[i].numparcialidad +'</td>'+
                '<td>'+data[i].impsaldoant +'</td>'+
                '<td>'+data[i].imppagado +'</td>'+
                '<td>'+data[i].impsaldoins +'</td>'+
                '<td>'+data[i].tipo_impuesto +'%</td>'+
                '<td>'+data[i].base_impuesto +'</td>'+
                '<td>'+data[i].impuesto +'</td>'+
                '<td>'+data[i].total_impuesto +'</td>'+
              '</tr>';

              totalPesos = parseFloat(totalPesos) + parseFloat(data[i].impuesto);
          }
          tablaArchivos += '</tbody>'+
        '</table>';
        tablaArchivos += '<script type="text/javascript">$("#tablaComplementos").DataTable({'+
              'language:{'+
                '"sProcessing":     "Procesando...",'+
                '"sLengthMenu":     "Mostrar _MENU_ registros",'+
                '"sZeroRecords":    "No se encontraron resultados",'+
                '"sEmptyTable":     "Ningún dato disponible en esta tabla",'+
                '"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",'+
                '"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",'+
                '"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",'+
                '"sInfoPostFix":    "",'+
                '"sSearch":         "Buscar:",'+
                '"sUrl":            "",'+
                '"sInfoThousands":  ",",'+
                '"sLoadingRecords": "Cargando...",'+
                '"oPaginate": {'+
                    '"sFirst":    "Primero",'+
                    '"sLast":     "Último",'+
                    '"sNext":     "Siguiente",'+
                    '"sPrevious": "Anterior"'+
                '},'+
                '"oAria": {'+
                    '"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",'+
                    '"sSortDescending": ": Activar para ordenar la columna de manera descendente"'+
                '}'+
              '}'+
            '});<\/script></div>';
          $("#bodyComplementos").html(tablaArchivos);
          $("#monto_total_pesos").html("");
          $("#monto_total_pesos").html(new Intl.NumberFormat().format(totalPesos)+" Pesos Mexicanos");
          },
          error: function () {
            alert("Error");
          }
        });
     }
   }
  }
  function Descargar() {
   if(($("#fechaInicio").val() == "" && $("#fechaFin").val() != "") || ($("#fechaInicio").val() != "" && $("#fechaFin").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#fechaInicio").val() == "" && $("#fechaFin").val() == ""){
      if($("#cliente").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        window.location.href = "impuestosExcel?fechaInicio="+$("#fechaInicio").val()+"&fechaFin="+$("#fechaFin").val()+"&id_cliente="+$("#cliente").val()+"&folio="+$("#folio").val()+"&clearing="+$("#clearing").val();
      }
     }
     else{
      window.location.href = "impuestosExcel?fechaInicio="+$("#fechaInicio").val()+"&fechaFin="+$("#fechaFin").val()+"&id_cliente="+$("#cliente").val()+"&folio="+$("#folio").val()+"&clearing="+$("#clearing").val();
     }
   }
  }
  function descargarPDF() {
    if(($("#fechaInicio").val() == "" && $("#fechaFin").val() != "") || ($("#fechaInicio").val() != "" && $("#fechaFin").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#fechaInicio").val() == "" && $("#fechaFin").val() == ""){
      if($("#cliente").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        window.location.href = "impuestosPDF?fechaInicio="+$("#fechaInicio").val()+"&fechaFin="+$("#fechaFin").val()+"&id_cliente="+$("#cliente").val()+"&folio="+$("#folio").val()+"&clearing="+$("#clearing").val();
      }
     }
     else{
      window.location.href = "impuestosPDF?fechaInicio="+$("#fechaInicio").val()+"&fechaFin="+$("#fechaFin").val()+"&id_cliente="+$("#cliente").val()+"&folio="+$("#folio").val()+"&clearing="+$("#clearing").val();
     }
   }
  }
</script>
@endsection
@section('footer')
@endsection
