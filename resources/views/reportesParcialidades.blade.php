@extends('Plantilla.plantilla')

@section('header')
@section('title','Reporte de Parcialidades')
@section('barra-superior')
@section('reportesMenu','reportes-active')
@section('sidemenu')
@endsection

@section('contenido')

<script src="jsPDF-master/jspdf.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>


<!--Encabezado-->
<div class="row">
  <div class="col-8 col-xs-8 col-sm-8 col-md-8 col-lg-8">
    <h2 class="text-left leadv"><i class="far fa-money-bill-alt"></i> REPORTES </h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 container-header">
    <p class="text-muted text-left"><big>Reporte de Parcialidades</big><img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-1">
      
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-3">
      <div class="form-group input-field">
        <label for="default-input-date">Fecha del Periodo:</label>
        <input id="default-input-date1" type="date" placeholder="dd/mm/yyyy" class="input-date" align="right">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field" >
        <label for="default-input-date">Al Periodo:</label>
        <input id="default-input-date2" type="date" placeholder="dd/mm/yyyy" class="input-date" >
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-3">
      <div class="form-group input-field" align="left">
        <label for="default-input-date">ID cliente o clearing document</label>
        <input id="id_cliente" type="text" placeholder="Escribe aqui..." class="input-date" >
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
    <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4 col-xs-offset-4 col-sm-offset-4 col-md-offset-3 col-lg-offset-4 text-center" align="center">
  </div>
  </div>
  <!-- Table Tesoreria -->
  <!--Encabezado-->
  </div>
  <div class="col-xs-120" align="center" class="col-xs-11" align="center">
   
  <a href="#" onclick="descargarPDF();"><button type="button" class="btn btn-danger">Descargar PDF </button></a>
         <a href="#" onclick="Descargar();"><button type="button" class="btn btn-success">Descargar Excel</button></a>
   </div>

<!-- Fin encabezado -->

<script type="text/javascript">
  $(document).ready(function () {
      var tablaArchivos = '<div id="contComplementos">';
      tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">ID_CLIENTE </th>'+
          '<th class="text-muted text-center">CLIENTE</th>'+
          '<th class="text-muted text-center">CLEARING</th>'+
          '<th class="text-muted text-center">FOLIO FACTURA</th>'+
          '<th class="text-muted text-center">PARCIALIDAD</th>'+
          '<th class="text-muted text-center">SALDO ANTERIOR </th>'+
          '<th class="text-muted text-center">IMPORTE PAGADO</th>'+
          '<th class="text-muted text-center">SALDO INSOLUTO</th>'+
          '<th class="text-muted text-center">TIPO DE CAMBIO</th>'+
          '<th class="text-muted text-center">MONEDA</th>'+
          '<th class="text-muted text-center">ESTATUS</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
      @foreach ($datos as $p)
      tablaArchivos+='<tr><td>{{$p->id_cliente}}</td>'+
                '<td>{{$p->nombre_c}}</td>'+
                '<td>{{ $p->clearing_document}}</td>'+
                '<td>{{ $p->folio}}</td>'+
                '<td>{{ $p->numparcialidad }}</td>'+
                '<td>{{ $p->impsaldoant }}</td>'+
                '<td>{{ $p->imppagado }}</td>'+
                '<td>{{ $p->impsaldoins }}</td>'+
                '<td>{{ $p->tipcambio }}</td>'+
                '<td>{{ $p->moneda  }}</td>'+ 
                @if($p->impsaldoins==0)
                '<td>Pagado</td></tr>';
                @else
                '<td>No liquidado</td></tr>';
                @endif
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
            '"sSearch":         "ID cliente o clearing document:",'+
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
  })


</script>




<script type="text/javascript">
  function buscar() {
   if(($("#default-input-date1").val() == "" && $("#default-input-date2").val() != "") || ($("#default-input-date1").val() != "" && $("#default-input-date2").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#default-input-date1").val() == "" && $("#default-input-date2").val() == ""){
      if($("#id_cliente").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        $.ajax({
          url: 'busquedaRepotepar',
          type: 'get',
          data: {fechainicio: $("#default-input-date1").val(), fechafin:$("#default-input-date2").val(), Ncliente:$("#id_cliente").val()},
          success: function (data) {
            document.getElementById("bodyComplementos").removeChild(document.getElementById("contComplementos"));
            var tablaArchivos = '<div id="contComplementos">';
            tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
          '<thead>'+
            '<tr>'+
            '<th class="text-muted text-center">ID_CLIENTE </th>'+
          '<th class="text-muted text-center">CLIENTE</th>'+
          '<th class="text-muted text-center">CLEARING</th>'+
          '<th class="text-muted text-center">FOLIO FACTURA</th>'+
          '<th class="text-muted text-center">PARCIALIDAD</th>'+
          '<th class="text-muted text-center">SALDO ANTERIOR </th>'+
          '<th class="text-muted text-center">IMPORTE PAGADO</th>'+
          '<th class="text-muted text-center">SALDO INSOLUTO</th>'+
          '<th class="text-muted text-center">TIPO DE CAMBIO</th>'+
          '<th class="text-muted text-center">MONEDA</th>'+
          '<th class="text-muted text-center">ESTATUS</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-reporte">'+data[i].id_cliente+'</td>'+
                    '<td>'+data[i].nombre_c+'</td>'+
                    '<td>'+data[i].clearing_document+'</td>'+
                    '<td>'+data[i].folio+'</td>'+
                    '<td>'+data[i].numparcialidad+'</td>'+
                    '<td>'+data[i].impsaldoant +'</td>'+
                    '<td>'+data[i].imppagado +'</td>'+
                    '<td>'+data[i].impsaldoins+'</td>'+
                    '<td>'+data[i].tipcambio +'</td>'+
                    '<td>'+data[i].moneda+'</td>';
                    if(data[i].impsaldoins==0){
                      tablaArchivos += '<td>Pagado</td></tr>';
                  } else{
                    tablaArchivos += '<td>No liquidado</td></tr>';
                }
            '</tr>';
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
                '"sSearch":         "ID cliente o clearing document:",'+
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
          },
          error: function () {
            alert("Error");
          }
        });
      }
     }
     else{
      $.ajax({
          url: 'busquedaRepotepar',
          type: 'get',
          data: {fechainicio: $("#default-input-date1").val(), fechafin:$("#default-input-date2").val(), Ncliente:$("#id_cliente").val()},
          success: function (data) {
            document.getElementById("bodyComplementos").removeChild(document.getElementById("contComplementos"));
            var tablaArchivos = '<div id="contComplementos">';
        tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
          '<thead>'+
            '<tr>'+
          '<th class="text-muted text-center">ID_CLIENTE </th>'+
          '<th class="text-muted text-center">CLIENTE</th>'+
          '<th class="text-muted text-center">CLEARING</th>'+
          '<th class="text-muted text-center">FOLIO FACTURA</th>'+
          '<th class="text-muted text-center">PARCIALIDAD</th>'+
          '<th class="text-muted text-center">SALDO ANTERIOR </th>'+
          '<th class="text-muted text-center">IMPORTE PAGADO</th>'+
          '<th class="text-muted text-center">SALDO INSOLUTO</th>'+
          '<th class="text-muted text-center">TIPO DE CAMBIO</th>'+
          '<th class="text-muted text-center">MONEDA</th>'+
          '<th class="text-muted text-center">ESTATUS</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-reporte">'+data[i].id_cliente+'</td>'+
                    '<td>'+data[i].nombre_c+'</td>'+
                    '<td>'+data[i].clearing_document+'</td>'+
                    '<td>'+data[i].folio+'</td>'+
                    '<td>'+data[i].numparcialidad+'</td>'+
                    '<td>'+data[i].impsaldoant +'</td>'+
                    '<td>'+data[i].imppagado +'</td>'+  
                    '<td>'+data[i].impsaldoins+'</td>'+
                    '<td>'+data[i].tipcambio +'</td>'+
                    '<td>'+data[i].moneda+'</td>';
                    if(data[i].impsaldoins==0){
                      tablaArchivos +='<td>Pagado</td></tr>';
                  } else{
                    tablaArchivos +=    '<td>No liquidado</td></tr>';
                }
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
                
                '"sSearch":         "ID cliente o clearing document:",'+
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
          },
          error: function () {
            alert("Error");
          }
        });
     }
   }
  }

function Descargar() {
   if(($("#fechainicio").val() == "" && $("#fechafin").val() != "") || ($("#fechainicio").val() != "" && $("#fechafin").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#fechainicio").val() == "" && $("#fechafin").val() == ""){
      if($("#id_cliente").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        window.location.href = "descargarPar?fechainicio="+$("#default-input-date1").val()+"&fechafin="+$("#default-input-date2").val()+"&Ncliente="+$("#id_cliente").val();
      }
     }
     else{
      window.location.href = "descargarPar?fechainicio="+$("#default-input-date1").val()+"&fechafin="+$("#default-input-date2").val()+"&Ncliente="+$("#id_cliente").val();
     }
   }
}
  
  function descargarPDF() {
    if(($("#fechaInicio").val() == "" && $("#fechafin").val() != "") || ($("#fechainicio").val() != "" && $("#fechafin").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
    if($("#fechainicio").val() == "" && $("#fechafin").val() == ""){
      if($("#id_cliente").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        window.location.href = "parcialidadesPDF?fechainicio="+$("#default-input-date1").val()+"&fechafin="+$("#default-input-date2").val()+"&Ncliente="+$("#id_cliente").val();
      }
     }
     else{
        window.location.href = "parcialidadesPDF?fechainicio="+$("#default-input-date1").val()+"&fechafin="+$("#default-input-date2").val()+"&Ncliente="+$("#id_cliente").val();
     }
   }
  }

</script>

@endsection
@section('footer')
@endsection
