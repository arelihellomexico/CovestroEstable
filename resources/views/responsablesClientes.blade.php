@extends('Plantilla.plantilla')

@section('header')
@section('title','Responsable')
@section('barra-superior')

@section('sidemenu')
@if(Session::get('tipo') != 1)
@if(Session::get('tipo') == 2)
@section('usuario','Usuario Tesoreria')
@section('validacionMenu','no-mostrar')
@section('creditoMenu','no-mostrar')
@section('sapMenu','no-mostrar')
@else
  @if(Session::get('tipo') == 4)
  @section('usuario','Usuario SAP')
  @section('validacionMenu','no-mostrar')
  @section('tesoreriaMenu','no-mostrar')
  @section('creditoMenu','no-mostrar')
  @else
    @if(Session::get('tipo') == 3)
    @section('usuario','Usuario Credito y C.')
    @section('validacionMenu','no-mostrar')
    @section('tesoreriaMenu','no-mostrar')
    @section('sapMenu','no-mostrar')
    @else
    @section('usuario','Usuario Consulta')
    @section('validacionMenu','no-mostrar')
    @section('tesoreriaMenu','no-mostrar')
    @section('sapMenu','no-mostrar')
    @section('creditoMenu','no-mostrar')
    @endif
  @endif
@endif
@section('clientesMenu','no-mostrar')
@section('concentradoMenu','no-mostrar')
@section('covestroMenu','no-mostrar')
@section('reportesMenu','no-mostrar')
@section('responsablesMenu','responsables-active')
@else
@section('title','Administrador')
@endif
@endsection

@section('contenido')
<!--Encabezado-->
<div class="row">
  <div class="col-8 col-xs-8 col-sm-8 col-md-8 col-lg-8">
    <h2 class="text-left leadv"><i class="far fa-money-bill-alt"></i> HISTÓRICO DE COMPLEMENTOS</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 container-header">
    <p class="text-muted text-left"><big>Histórico de complementos</big><img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-1">
      
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Buscar complementos del periodo</label>
        <input id="default-input-date1" type="date" placeholder="dd/mm/yyyy" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Al periodo</label>
        <input id="default-input-date2" type="date" placeholder="dd/mm/yyyy" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Por cliente o RFC</label>
        <input id="cliente" type="text" placeholder="Escriba RFC o Nombre del cliente" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Status de Complemento</label>
        <select id="status" name="status" class="form-control layo">
          <option value="1">Timbrado</option>
          <option value="2">No timbrado</option>
          <option value="3">Todos</option>
        </select>
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
    <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4 col-xs-offset-4 col-sm-offset-4 col-md-offset-3 col-lg-offset-4 text-center">
      
    </div>
  </div>
  <!-- Table Tesoreria -->
  <!--Encabezado-->
</div>
<!-- Fin encabezado -->
<script type="text/javascript">
  $(document).ready(function () {
      var tablaArchivos = '<div id="contComplementos">';
    tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">Clearing Document</th>'+
          '<th class="text-muted text-center">Cliente</th>'+
          '<th class="text-muted text-center">RFC del Cliente</th>'+
          '<th class="text-muted text-center">Fecha del Documento</th>'+
          '<th class="text-muted text-center">Fecha de Timbrado</th>'+
          '<th class="text-muted text-center">Descargar PDF/XML</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
      @foreach ($archivos as $p)
      tablaArchivos += '<tr class="text-muted text-center" id="">'+
                '<td id="archivos-recientes-tesoreria">{{$p->clearing}}</td>'+
                '<td id="date">{{$p->cliente}}</td>'+
                '<td>{{ $p->rfc_cliente }}</td>'+
                '<td>{{ $p->fechabus }}</td>'+
                '<td>{{ $p->fecha }}</td>';
                @if($p->timbrado == 1)
                tablaArchivos += '<td><a href="../ARCHIVOS/PROCESADOS_TXT/{{ $p->nombre }}" title="Descargar archivo TXT" style="font-size: 26px;" target="_blank"><i class="fa fa-file"></i></a>&nbsp;&nbsp;<a href="../ARCHIVOS/ARCHIVOS_ENTRANTES/PDF/{{ $p->generapdf }}" title="Descargar archivo PDF" style="font-size: 26px;" target="_blank"><i class="fa fa-file-pdf"></i></a>&nbsp;&nbsp;<a href="../ARCHIVOS/ARCHIVOS_ENTRANTES/XML/{{ $p->generaxml }}" title="Descargar archivo XML" style="font-size: 26px;" target="_blank"><i class="fa fa-file-code" ></i></a></td>';
                @else
                tablaArchivos += '<td></td>'+
                @endif
                '</tr>';
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
  })
</script>
<script type="text/javascript">
  function buscar() {
   if(($("#default-input-date1").val() == "" && $("#default-input-date2").val() != "") || ($("#default-input-date1").val() != "" && $("#default-input-date2").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#default-input-date1").val() == "" && $("#default-input-date2").val() == ""){
      if($("#cliente").val() == "" && $("#status").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        $.ajax({
          url: 'verProcesoH',
          type: 'get',
          data: {inicio: $("#default-input-date1").val(), fin:$("#default-input-date2").val(), cliente:$("#cliente").val(), status: $("#status").val()},
          success: function (data) {
            document.getElementById("bodyComplementos").removeChild(document.getElementById("contComplementos"));
            var tablaArchivos = '<div id="contComplementos">';
        tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
          '<thead>'+
            '<tr>'+
              '<th class="text-muted text-center">Clearing Document</th>'+
              '<th class="text-muted text-center">Cliente</th>'+
              '<th class="text-muted text-center">RFC</th>'+
              '<th class="text-muted text-center">Fecha del Documento</th>'+
              '<th class="text-muted text-center">Fecha de Timbrado</th>'+
              '<th class="text-muted text-center">Descargar TXT/PDF/XML</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">'+data[i].clearing+'</td>'+
                    '<td id="date">'+data[i].cliente+'</td>'+
                    '<td>'+data[i].rfc_cliente+'</td>'+
                    '<td id="date">'+data[i].fechabus+'</td>'+
                    '<td id="date">'+data[i].fecha+'</td>';
                    if(data[i].timbrado == 1){
                      tablaArchivos += '<td><a href="../ARCHIVOS/PROCESADOS_TXT/'+data[i].nombre+'" title="Descargar archivo TXT" style="font-size: 26px;" target="_blank"><i class="fa fa-file"></i></a>&nbsp;&nbsp;<a href="../ARCHIVOS/ARCHIVOS_ENTRANTES/PDF/'+data[i].generapdf+'" title="Descargar archivo PDF" style="font-size: 26px;" target="_blank"><i class="fa fa-file-pdf"></i></a>&nbsp;&nbsp;<a href="../ARCHIVOS/ARCHIVOS_ENTRANTES/XML/'+data[i].generaxml+'" title="Descargar archivo XML" style="font-size: 26px;" target="_blank"><i class="fa fa-file-code" ></i></a></td>';
                    }
                    else{
                      tablaArchivos += '<td></td>';
                    }
                    tablaArchivos += '</tr>';
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
          },
          error: function () {
            alert("Error");
          }
        });
      }
     }
     else{
      $.ajax({
          url: 'verProcesoH',
          type: 'get',
          data: {inicio: $("#default-input-date1").val(), fin:$("#default-input-date2").val(), cliente:$("#cliente").val(), status: $("#status").val()},
          success: function (data) {
            document.getElementById("bodyComplementos").removeChild(document.getElementById("contComplementos"));
            var tablaArchivos = '<div id="contComplementos">';
        tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
          '<thead>'+
            '<tr>'+
              '<th class="text-muted text-center">Clearing Document</th>'+
              '<th class="text-muted text-center">Cliente</th>'+
              '<th class="text-muted text-center">RFC</th>'+
              '<th class="text-muted text-center">Fecha</th>'+
              '<th class="text-muted text-center">Descargar TXT/PDF/XML</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">'+data[i].clearing+'</td>'+
                    '<td id="date">'+data[i].cliente+'</td>'+
                    '<td>'+data[i].rfc_cliente+'</td>'+
                    '<td id="date">'+data[i].fechabus+'</td>';
                    if(data[i].timbrado == 1){
                      tablaArchivos += '<td><a href="../ARCHIVOS/PROCESADOS_TXT/'+data[i].nombre+'" title="Descargar archivo TXT" style="font-size: 26px;" target="_blank"><i class="fa fa-file"></i></a>&nbsp;&nbsp;<a href="../ARCHIVOS/ARCHIVOS_ENTRANTES/PDF/'+data[i].generapdf+'" title="Descargar archivo PDF" style="font-size: 26px;" target="_blank"><i class="fa fa-file-pdf"></i></a>&nbsp;&nbsp;<a href="../ARCHIVOS/ARCHIVOS_ENTRANTES/XML/'+data[i].generaxml+'" title="Descargar archivo XML" style="font-size: 26px;" target="_blank"><i class="fa fa-file-code" ></i></a></td>';
                    }
                    else{
                      tablaArchivos += '<td></td>';
                    }
                    tablaArchivos += '</tr>';
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
          },
          error: function () {
            alert("Error");
          }
        });
     }
   }
  }
</script>
@endsection
@section('footer')
@endsection
