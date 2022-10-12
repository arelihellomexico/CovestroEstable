@extends('Plantilla.plantilla')

@section('header')
@section('title','Tráfico de procesos')
@section('barra-superior')
@section('concentradoMenu','concentrado-active')
@section('sidemenu')
@endsection

@section('contenido')
<!--Encabezado-->
<div class="row">
  <div class="col-8 col-xs-8 col-sm-8 col-md-8 col-lg-8">
    <h2 class="text-left leadv"><i class="far fa-money-bill-alt"></i> TRÁFICO DE PROCESOS</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 container-header">
    <p class="text-muted text-left"><big>Histórico de procesos</big><img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <form id="formProcesos" action="javascript:buscarProcesos();" method="post">
      {{ csrf_field() }}
      <div class="col-md-1 col-lg-3">
        
      </div>
      <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
        <div class="form-group input-field">
          <label for="default-input-date">Buscar procesos del periodo</label>
          <input id="default-input-date" name="inicioPro" type="date" placeholder="dd/mm/yyyy" class="input-date" required>
          <p class="label-error"></p>
        </div>
      </div>
      <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
        <div class="form-group input-field">
          <label for="default-input-date">Al periodo</label>
          <input id="default-input-date" name="finPro" type="date" placeholder="dd/mm/yyyy" class="input-date" required>
          <p class="label-error"></p>
        </div>
      </div>
      <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
        <div class="input-field">
          <button type="submit" class="button btn-blue buscar-tabla" name="button">Buscar</button>
        </div>
      </div>
      <div class="col-md-2 col-lg-3">
        
      </div>
    </form>
    <div id="bodyProcesos">
      
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4 col-xs-offset-4 col-sm-offset-4 col-md-offset-3 col-lg-offset-4 text-center">
      
    </div>
  </div>
  <!-- Table Tesoreria -->
  <!--Encabezado-->
</div>
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 container-header">
    <p class="text-muted text-left"><big>Archivos del proceso generado</big><img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<div class="row">
  <form id="formArchivos" action="javascript:buscarArchivos()" method="post">
    {{ csrf_field() }}
    <div class="col-lg-">
      
    </div>
    <div class="col-12 col-xs-12 col-sm-3 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Por cliente o RFC</label>
        <input id="cliente" name="cliente" type="text" placeholder="Escriba RFC o Nombre del cliente" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-12 col-xs-12 col-sm-3 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Del periodo</label>
        <input id="default-input-date" name="inicioAr" type="date" placeholder="dd/mm/yyyy" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-12 col-xs-12 col-sm-3 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Al periodo</label>
        <input id="default-input-date" name="finAr" type="date" placeholder="dd/mm/yyyy" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-12 col-xs-12 col-sm-3 col-md-3 col-lg-2">
      <input type="hidden" id="proceso" name="proceso" value="{{ Session::get("proceso") }}">
      <div class="input-field">
        <button type="submit" class="button btn-blue buscar-tabla" name="button">Buscar</button>
      </div>
    </div>
    <!--div class="col-12 col-xs-12 col-sm-3 col-md-3 col-lg-2">
      <div class="input-field">
        <button type="button" class="button btn-blue buscar-tabla" name="button" onclick="actualizarStatus();">Actualizar Archivos</button>
      </div>
    </div-->
    <div class="col-lg-1">
      
    </div>
  </form>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="bodyArchivos">
    
  </div>
  <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4 col-xs-offset-4 col-sm-offset-4 col-md-offset-3 col-lg-offset-4 text-center">
      <button type="button" name="button" class="button btn-green" onclick="preliminar();"><small>Finalizar Proceso Actual</small></button>
  </div>
</div>
                <div class="modal fade" id="modal-exito" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/singo1.png')}}">
                          <h3><strong>Éxito</strong></h3>
                          <p>
                            Tu layout se ha guardado exitosamente.
                          </p>
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
                <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/signo2.png')}}">
                          <h3><strong>Error</strong></h3>
                          <p>
                            Hubo un problema al mostrar los datos.
                          </p>
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
                <div class="modal fade" id="modal-exito-actualiza" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/singo1.png')}}">
                          <h3><strong>Éxito</strong></h3>
                          <p>
                            Se han actualizado los status de los archivos exitosamente.
                          </p>
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
                <div class="modal fade" id="modal-error-actualiza" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/signo2.png')}}">
                          <h3><strong>Error</strong></h3>
                          <p>
                            Hubo un problema al actualizar los archivos.
                          </p>
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
                <div class="modal fade" id="modal-exito-finaliza" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/singo1.png')}}">
                          <h3><strong>Éxito</strong></h3>
                          <p>
                            Se finaliza el proceso exitosamente.
                          </p>
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
                <div class="modal fade" id="modal-verifica-eliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/signo.png')}}">
                          <h3><strong>Confirmación de Finalización</strong></h3>
                          <p>¿Estás seguro que deseas finalizar el proceso actual?</p>
                        </center>
                      </div>
                      <div class="modal-footer">
                        <div class="row">
                          <div class="col-xs-6" align="right">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                          </div>
                          <div class="col-xs-6" align="left">
                              <form action="javascript:finalizarProceso();" method="POST" id="finProceso">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary" onclick="eliminarUsuario();">Finalizar</button>
                              </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal fade" id="modal-error-finaliza" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="{{asset('assets/img/signo2.png')}}">
                          <h3><strong>Error</strong></h3>
                          <p>
                            Hubo un problema al finalizar el proceso. Asegúrate de cambiar los status de los archivos por 1 o 2, y no dejar ninguno en 0.
                          </p>
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
<!-- Fin encabezado -->
<script type="text/javascript">
  $(document).ready(function () {
    var tablaProcesos = '<div id="contProcesos">';
    tablaProcesos += '<table class="display table table-bordered table-hover table-striped" id="tablaProcesos">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">Nombre del proceso</th>'+
          '<th class="text-muted text-center">Fecha</th>'+
          '<th class="text-muted text-center">Total de archivos</th>'+
          '<th class="text-muted text-center">Correctos</th>'+
          '<th class="text-muted text-center">Erroneos</th>'+
          '<th class="text-muted text-center">Ver</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
      @foreach ($procesos as $p)
      tablaProcesos += '<tr class="text-muted text-center" id="">'+
                '<td id="archivos-recientes-tesoreria">{{$p->nombre}}</td>'+
                '<td id="date">{{$p->fecha}}</td>'+
                '<td>{{$p->total}}</td>'+
                '<td>{{$p->correctos}}</td>'+
                '<td>{{$p->erroneos}}</td>'+
                '<td><a href="#" onclick="verProceso({{ $p->id_pro }})"><i class="fa fa-eye"></i></a></td>'+
              '</tr>';

      @endforeach
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

      $("#bodyProcesos").html(tablaProcesos);

      var tablaArchivos = '<div id="contArchivos"><form action="javascript:actualizarStatus();" method="post" id="formActualiza">{{ csrf_field() }}<input type="hidden" id="proceso" name="proceso" value="{{ Session::get("proceso") }}"><input type="hidden" id="id_pro" name="id_pro" value="{{ $proceso }}">';
      @foreach ($archivos as $p)
      tablaArchivos += '<input type="hidden" id="id_ar{{ $p->id_ar }}" name="id_ar{{ $p->id_ar }}" value="{{ $p->timbrado }}">';
      @endforeach

      tablaArchivos += '</form>';

    tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaArchivos">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">Clearing Document</th>'+
          '<th class="text-muted text-center">Cliente</th>'+
          '<th class="text-muted text-center">Status de Archivo</th>'+
          '<th class="text-muted text-center">Descargar TXT/PDF/XML</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
      @foreach ($archivos as $p)
      tablaArchivos += '<tr class="text-muted text-center" id="">'+
                '<td id="archivos-recientes-tesoreria">{{$p->clearing}}</td>'+
                '<td id="date">{{$p->cliente}}</td>';
                @if($p->timbrado == 0)
                tablaArchivos += '<td>En Proceso</td>';
                @else
                  @if($p->timbrado == 1)
                  tablaArchivos += '<td>Timbrado</td>';
                  @else
                  tablaArchivos += '<td>Error al timbrar</td>';
                  @endif
                @endif
                @if($p->timbrado == 1)
                tablaArchivos += '<td><a href="../ARCHIVOS/PROCESADOS_TXT/{{ $p->nombre }}" title="Descargar archivo TXT" style="font-size: 26px;" target="_blank"><i class="fa fa-file"></i></a>&nbsp;&nbsp;<a href="../ARCHIVOS/ARCHIVOS_ENTRANTES/PDF/{{ $p->generapdf }}" title="Descargar archivo PDF" style="font-size: 26px;" target="_blank"><i class="fa fa-file-pdf"></i></a>&nbsp;&nbsp;<a href="../ARCHIVOS/ARCHIVOS_ENTRANTES/XML/{{ $p->generaxml }}" title="Descargar archivo XML" style="font-size: 26px;" target="_blank"><i class="fa fa-file-code" ></i></a></td>'+
                @else
                tablaArchivos += '<td></td>'+
                @endif
              '</tr>';

      @endforeach
      tablaArchivos += '</tbody>'+
    '</table>';
    tablaArchivos += '<script type="text/javascript">$("#tablaArchivos").DataTable({'+
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
      $("#bodyArchivos").html(tablaArchivos);
  })
</script>
<script type="text/javascript">
  var id_proceso = {{ $proceso }};
  function verProceso(id) {
    id_proceso = id;
    $.ajax({
      url: 'verProceso',
      type: 'get',
      data: {id_pro: id},
      success: function (data) {
        $("#proceso").val(id);
        document.getElementById("bodyArchivos").removeChild(document.getElementById("contArchivos"));
        var tablaArchivos = '<div id="contArchivos"><form action="javascript:actualizarStatus();" method="post" id="formActualiza">{{ csrf_field() }}<input type="hidden" id="proceso" name="proceso" value="'+id+'">';
        for (var i = 0; i < data.length; i++) {
      tablaArchivos += '<input type="hidden" id="id_ar'+data[i].id_ar+'" name="id_ar'+data[i].id_ar+'" value="'+data[i].timbrado+'">';
      }

      tablaArchivos += '</form>';
        tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaArchivos">'+
          '<thead>'+
            '<tr>'+
              '<th class="text-muted text-center">Clearing Document</th>'+
              '<th class="text-muted text-center">Cliente</th>'+
              '<th class="text-muted text-center">Status de Archivo</th>'+
              '<th class="text-muted text-center">Descargar TXT/PDF/XML</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">'+data[i].clearing+'</td>'+
                    '<td id="date">'+data[i].cliente+'</td>';
                    if(data[i].timbrado == 0){
                      tablaArchivos += '<td>En Proceso</td>';
                    }
                    else{
                      if(data[i].timbrado == 1){
                        tablaArchivos += '<td>Timbrado</td>';
                      }
                      else{
                        tablaArchivos += '<td>Error al timbrar</td>';
                      }
                    }
            if(data[i].timbrado == 1){
              tablaArchivos += '<td><a href="../ARCHIVOS/PROCESADOS_TXT/'+data[i].nombre+'" title="Descargar archivo TXT" style="font-size: 26px;" target="_blank"><i class="fa fa-file"></i></a>&nbsp;&nbsp;<a href="../ARCHIVOS/ARCHIVOS_ENTRANTES/PDF/'+data[i].generapdf+'" title="Descargar archivo PDF" style="font-size: 26px;" target="_blank"><i class="fa fa-file-pdf"></i></a>&nbsp;&nbsp;<a href="../ARCHIVOS/ARCHIVOS_ENTRANTES/XML/'+data[i].generaxml+'" title="Descargar archivo XML" style="font-size: 26px;" target="_blank"><i class="fa fa-file-code" ></i></a></td>';
            }
            else{
              tablaArchivos += '<td></td></tr>';
            }
          }
          tablaArchivos += '</tbody>'+
        '</table>';
        tablaArchivos += '<script type="text/javascript">$("#tablaArchivos").DataTable({'+
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
          $("#bodyArchivos").html(tablaArchivos);
      },
      error: function () {
        $("#modal-exito").modal("show");
      }
    })
  }

  function buscarProcesos(argument) {
    var formulario = new FormData(document.getElementById("formProcesos"));
    $.ajax({
      url: "buscarProcesos",
      type: "post",
      data: formulario,
      processData: false,
      contentType: false,
      success: function(data){
        document.getElementById('bodyProcesos').removeChild(document.getElementById('contProcesos'))
        var tablaProcesos = '<div id="contProcesos">';
        tablaProcesos += '<table class="display table table-bordered table-hover table-striped" id="tablaProcesos">'+
          '<thead>'+
            '<tr>'+
              '<th class="text-muted text-center">Nombre del proceso</th>'+
              '<th class="text-muted text-center">Fecha</th>'+
              '<th class="text-muted text-center">Total de archivos</th>'+
              '<th class="text-muted text-center">Correctos</th>'+
              '<th class="text-muted text-center">Erroneos</th>'+
              '<th class="text-muted text-center">Ver</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for(var i = 0; i < data.length; i++){
          tablaProcesos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">'+data[i].nombre+'</td>'+
                    '<td id="date">'+data[i].fecha+'</td>'+
                    '<td>'+data[i].total+'</td>'+
                    '<td>'+data[i].correctos+'</td>'+
                    '<td>'+data[i].erroneos+'</td>'+
                    '<td><a href="#" onclick="verProceso('+data[i].id_pro+')"><i class="fa fa-eye"></i></a></td>'+
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

  function buscarArchivos() {
    var formulario = new FormData(document.getElementById('formArchivos'));
    $.ajax({
      url: "buscarArchivos",
      type: "post",
      data: formulario,
      processData: false,
      contentType: false,
      success: function(data){
        document.getElementById("bodyArchivos").removeChild(document.getElementById("contArchivos"));
        var tablaArchivos = '<div id="contArchivos"><form action="javascript:actualizarStatus();" method="post" id="formActualiza">{{ csrf_field() }}<input type="hidden" id="proceso" name="proceso" value="'+id_proceso+'">';
        for (var i = 0; i < data.length; i++) {
      tablaArchivos += '<input type="hidden" id="id_ar'+data[i].id_ar+'" name="id_ar'+data[i].id_ar+'" value="'+data[i].timbrado+'">';
      }

      tablaArchivos += '</form>';
        tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaArchivos">'+
          '<thead>'+
            '<tr>'+
              '<th class="text-muted text-center">Clearing Document</th>'+
              '<th class="text-muted text-center">Cliente</th>'+
              '<th class="text-muted text-center">Status de Archivo</th>'+
              '<th class="text-muted text-center">Descargar TXT/PDF/XML</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">'+data[i].clearing+'</td>'+
                    '<td id="date">'+data[i].cliente+'</td>';
                    if(data[i].timbrado == 0){
                      tablaArchivos += '<td>En Proceso</td>';
                    }
                    else{
                      if(data[i].timbrado == 1){
                        tablaArchivos += '<td>Timbrado</td>';
                      }
                      else{
                        tablaArchivos += '<td>Error al timbrar</td>';
                      }
                    }
            if(data[i].timbrado == 1){
              tablaArchivos += '<td><a href="../ARCHIVOS/PROCESADOS_TXT/'+data[i].nombre+'" title="Descargar archivo TXT" style="font-size: 26px;" target="_blank"><i class="fa fa-file"></i></a>&nbsp;&nbsp;<a href="../ARCHIVOS/ARCHIVOS_ENTRANTES/PDF/'+data[i].generapdf+'" title="Descargar archivo PDF" style="font-size: 26px;" target="_blank"><i class="fa fa-file-pdf"></i></a>&nbsp;&nbsp;<a href="../ARCHIVOS/ARCHIVOS_ENTRANTES/XML/'+data[i].generaxml+'" title="Descargar archivo XML" style="font-size: 26px;" target="_blank"><i class="fa fa-file-code" ></i></a></td>';
            }
            
            else{
              tablaArchivos += '<td></td></tr>';
            }
          }
          tablaArchivos += '</tbody>'+
        '</table>';
        tablaArchivos += '<script type="text/javascript">$("#tablaArchivos").DataTable({'+
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
          $("#bodyArchivos").html(tablaArchivos);
      },
      error: function(){
        // body...
      }
    })
  }

  function actualizarStatus() {
    var formulario = new FormData(document.getElementById("formActualiza"));
    $.ajax({
      url: "actualizarStatus",
      type: "post",
      data: formulario,
      processData: false,
      contentType: false,
      success: function(data){
        $("#modal-exito-actualiza").modal("show");
      },
      error: function() {
        $("#modal-error-actualiza").modal("show");
      }
    })
  }

  function preliminar() {
    $("#modal-verifica-eliminar").modal("show");
  }

  function finalizarProceso() {
    var formulario = new FormData(document.getElementById("finProceso"));
    $.ajax({
      url: "finalizarProceso",
      type: "post",
      data: formulario,
      processData: false,
      contentType: false,
      success: function(data){
        if(data.respuesta == 2){
          $("#modal-verifica-eliminar").modal("hide");
          $("#modal-error-finaliza").modal("show");
        }
        else{
          $("#modal-verifica-eliminar").modal("hide");
          $("#modal-exito-finaliza").modal("show");
        }
      },
      error: function() {
        $("#modal-verifica-eliminar").modal("hide");
        $("#modal-error-finaliza").modal("show");
      }
    })
  }

  function pasarTimbre(status, archivo) {
    $("#id_ar"+archivo).val(status);
  }
</script>
@endsection
@section('footer')
@endsection
