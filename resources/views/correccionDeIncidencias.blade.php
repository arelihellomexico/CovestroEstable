@extends('Plantilla.plantilla')

@section('header')
@section('title','Corrección de incidencias')
@section('barra-superior')

@section('sidemenu')
@endsection

@section('contenido')
<!--Encabezado-->
<div class="row">
  <div class="col-8 col-xs-8 col-sm-8 col-md-8 col-lg-8">
    <h2 class="text-left leadv"><i class="far fa-money-bill-alt"></i> Detalle de incidencias</h2>
  </div>
  <div class="col-2 col-xs-2 col-sm-2 col-md-2 col-lg-2">
    <!--a href="#" class="button btn-blue enlace"><i class="fas fa-chevron-left"></i> Regresar</a-->
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 container-header">
    <p class="text-muted text-left"><big>SAP</big><img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
     <form id="formInciSAP" action="javascript:buscarIncidenciasSAP();" method="post">
      {{ csrf_field() }}
    <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-3">
      <div class="form-group">
        <label for="nombre-archivo">Nombre del archivo</label>
        <input type="text" id="nombre-archivo" name="archivo" class="form-control input" placeholder="Escribe el nombre de tu archivo...">
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Del periodo</label>
        <input id="default-input-date" name="inicio" type="date" placeholder="dd/mm/yyyy" class="input-date" required>
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Al periodo</label>
        <input id="default-input-date" name="fin" type="date" placeholder="dd/mm/yyyy" class="input-date" required>
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
        <div class="input-field">
          <button type="submit" class="button btn-blue buscar-tabla" name="button">Buscar</button>
        </div>
      </div>
    </form>
    <div id="bodyProcesos">
      <div id="cuerpo">
        <table class="display AllDataTable table table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th class="text-muted text-center">Nombre del archivo</th>
              <th class="text-muted text-center">Clearing Document</th>
              <th class="text-muted text-center">Incidencias</th>
            </tr>
          </thead>
          <tbody>
            @foreach($iSAP as $i)
            <tr>
              <th class="text-muted text-center"><small>{{ $i->nombre_archivo }}</small></th>
              <th class="text-muted text-center"><small>{{ $i->clearing }}</small></th>
              <th class="text-muted text-center"><small>{{ $i->incidencias }}</small></th>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    
    <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4 col-xs-offset-4 col-sm-offset-4 col-md-offset-3 col-lg-offset-4 text-center">
      <form action="{{ url('/incidenciasSAP') }}" method="POST">
        {{ csrf_field() }}
        <button type="submit" name="button" class="button btn-pink-big"><small>Descargar reporte de incidencias</small></button>
      </form>
    </div>
  </div>
  <!-- Table Tesoreria -->
  <!--Encabezado-->
</div>
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 container-header">
    <p class="text-muted text-left"><big>Tesoreria</big><img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<div class="row">
  <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-3">
    <div class="form-group">
      <label for="nombre-archivo">Nombre del archivo</label>
      <input type="text" id="nombre_archivo" class="form-control input" placeholder="Escribe el nombre de tu archivo...">
    </div>
  </div>
  <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
    <div class="form-group input-field">
      <label for="default-input-date">Del periodo</label>
      <input id="default-input-date" type="date" placeholder="dd/mm/yyyy" class="input-date">
      <p class="label-error"></p>
    </div>
  </div>
  <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
    <div class="form-group input-field">
      <label for="default-input-date">Al periodo</label>
      <input id="default-input-date" type="date" placeholder="dd/mm/yyyy" class="input-date">
      <p class="label-error"></p>
    </div>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <table class="display AllDataTable table table-bordered table-hover table-striped">
      <thead>
        <tr>
          <th class="text-muted text-center"><small>Nombre del archivo</small></th>
          <th class="text-muted text-center"><small>Linea de error</small></th>
          <th class="text-muted text-center"><small>Incidencias</small></th>
        </tr>
      </thead>
      <tbody>
        
      </tbody>
    </table>
  </div>
  <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4 col-xs-offset-4 col-sm-offset-4 col-md-offset-3 col-lg-offset-4 text-center">
    <form action="{{ url('/incidenciasTeso') }}" method="POST">
      {{ csrf_field() }}
      <!--button type="submit" name="button" class="button btn-pink-big"><small>Descargar reporte de incidencias</small></button-->
    </form>
  </div>
</div>
<!-- Fin encabezado -->
<script type="text/javascript">
  function buscarIncidenciasSAP() {
    var formulario = new FormData(document.getElementById("formInciSAP"));
    $.ajax({
      url: "buscarIncidenciasSAP",
      type: "post",
      data: formulario,
      processData: false,
      contentType: false,
      success: function(data){
        document.getElementById('bodyProcesos').removeChild(document.getElementById('cuerpo'))
        var tablaProcesos = '<div id="cuerpo">';
        tablaProcesos += '<table class="display table table-bordered table-hover table-striped" id="tablaProcesos">'+
          '<thead>'+
            '<tr>'+
              '<th class="text-muted text-center">Nombre del archivo</th>'+
              '<th class="text-muted text-center">Clearing_document</th>'+
              '<th class="text-muted text-center">Fecha</th>'+
              '<th class="text-muted text-center">Incidencias</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for(var i = 0; i < data.length; i++){
          tablaProcesos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">'+data[i].nombre_archivo+'</td>'+
                    '<td id="date">'+data[i].clearing+'</td>'+
                    '<td>'+data[i].fecha+'</td>'+
                    '<td>'+data[i].incidencias+'</td>'+
                  '</tr>';

          }
          tablaProcesos += '</tbody>'+
        '</table>';
        tablaProcesos += '<script type="text/javascript">$("#tablaProcesos").DataTable({'+
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

          $("#bodyProcesos").append(tablaProcesos);
      },
      error: function() {
        $("#modal-exito").modal("show");
      }
    })
  }
</script>
@endsection
@section('footer')
@endsection
