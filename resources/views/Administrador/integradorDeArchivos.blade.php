@extends('Plantilla.plantilla')
@section('title','Integración de Archivos')
@section('concentradoMenu','concentrado-active')
@section('contenido')
    <form class="" action="javascript:integrar();" method="post" id="formInt">
        {{csrf_field()}}
    <div class="row">
      <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-7">
        <h2 class="text-left"><i class="fas fa-archive"></i> Integrador de archivos</h2>
      </div>
      <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-5 text-center">
          @if($c == 0)
            <ul class='timeline'>
                <li class=''>Carga de archivos</li>
                <li class="active">Integración de archivos</li>
                <li class="">Generando TXT</li>
                <li class="">Timbrado de archivos</li>
              </ul>
          @else
            @if ($proceso->integracion == 1)
              <ul class='timeline'>
                <li class=''>Carga de archivos</li>
                <li class="active">Integración de archivos</li>
                <li class="">Generando TXT</li>
                <li class="">Timbrado de archivos</li>
              </ul>
            @elseif($proceso->obtencion == 1)
              <ul class='timeline'>
                <li class=''>Carga de archivos</li>
                <li class="non">Integración de archivos</li>
                <li class="non">Generando TXT</li>
                <li class="active">Timbrado de archivos</li>
              </ul>
            @elseif($proceso->timbrado == 1)
            <ul class='timeline'>
                <li class=''>Carga de archivos</li>
                <li class="non">Integración de archivos</li>
                <li class="active">Generando TXT</li>
                <li class="">Timbrado de archivos</li>
              </ul>
            @else
              <ul class='timeline'>
                <li class='active'>Carga de archivos</li>
                <li class="">Integración de archivos</li>
                <li class="">Generando TXT</li>
                <li class="">Timbrado de archivos</li>
              </ul>
            @endif
          @endif
      </div>
        <div class="col-12-col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <br>
          <div class="row">
            <div class="col-5 col-xs-5 col-sm-5 col-md-5 col-lg-5">
              <p class="text-muted text-center"><big>Archivos recientes</big></p>
            </div>
            <div class="col-5 col-xs-5 col-sm-5 col-md-5 col-lg-5">
              <p class="text-muted text-center"><big>Historial de archivos</big></p>
            </div>
            <div class="col-2 col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
              <button type="button" name="button-more" class="btn-round more" onClick="mostrarMas()"><i class="fas fa-plus"></i></button>
              <button type="button" name="button-minus" class="btn-round minus" onClick="mostrarMenos()"><i class="fas fa-minus"></i></button>
            </div>
          </div>
        </div>
        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <hr class="underline">
        </div>
        </div>
        <div class="row">
          <!-- Tesorería -->
          <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <p class="text-left"><big>Tesorer&iacute;a</big></p>
          </div>
            <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6" id="newFilesTesoreria">
                <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4">
                  <div class="input-field">
                    <label class="periodos">Del periodo</label>
                    <input id="default-input-date1" name="default-input-date1" type="date" placeholder="dd/mm/yyyy">
                    <p class="label-error"></p>
                  </div>
                </div>
                <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4">
                  <div class="input-field">
                    <label class="periodos">Al periodo</label>
                    <input id="default-input-date2" name="default-input-date2" type="date" placeholder="dd/mm/yyyy">
                    <p class="label-error"></p>
                  </div>
                </div>
                <div class="col-4 col-xs-4 col-sm-4 col-md-12 col-lg-4">
                  <div class="input-field">
                    <button type="button" class="button btn-blue buscar-tabla" name="button" onclick="buscarTeso(1);">Buscar</button>
                  </div>
                </div>
              <div id="cont1">

              </div>
            </div>
            <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6" id="oldFilesTesoreria">
              <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4">
                  <div class="input-field">
                    <label class="periodos">Del periodo</label>
                    <input id="default-input-date3" name="default-input-date3" type="date" placeholder="dd/mm/yyyy">
                    <p class="label-error"></p>
                  </div>
                </div>
                <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4">
                  <div class="input-field">
                    <label class="periodos">Al periodo</label>
                    <input id="default-input-date4" name="default-input-date4" type="date" placeholder="dd/mm/yyyy">
                    <p class="label-error"></p>
                  </div>
                </div>
                <div class="col-4 col-xs-4 col-sm-4 col-md-12 col-lg-4">
                  <div class="input-field">
                    <button type="button" class="button btn-blue buscar-tabla" name="button" onclick="buscarTeso(2);">Buscar</button>
                  </div>
                </div>
              <div id="cont2">

              </div>
            </div>
            <!-- SAP -->
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <hr class="underline">
            </div>
            <div id="results">
              
            </div>
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <p class="text-left"><big>SAP</big></p>
            </div>
              <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6" id="newFilesSAP">
                <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4">
                  <div class="input-field">
                    <label class="periodos">Del periodo</label>
                    <input id="default-input-date5" name="default-input-date5" type="date" placeholder="dd/mm/yyyy">
                    <p class="label-error"></p>
                  </div>
                </div>
                <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4">
                  <div class="input-field">
                    <label class="periodos">Al periodo</label>
                    <input id="default-input-date6" name="default-input-date6" type="date" placeholder="dd/mm/yyyy">
                    <p class="label-error"></p>
                  </div>
                </div>
                <div class="col-4 col-xs-4 col-sm-4 col-md-12 col-lg-4">
                  <div class="input-field">
                    <button type="button" class="button btn-blue buscar-tabla" name="button" onclick="buscarTeso(3);">Buscar</button>
                  </div>
                </div>
                <div id="cont3">

                </div>
              </div>
              <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6" id="oldFilesSAP">
                <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4">
                  <div class="input-field">
                    <label class="periodos">Del periodo</label>
                    <input id="default-input-date7" name="default-input-date7" type="date" placeholder="dd/mm/yyyy">
                    <p class="label-error"></p>
                  </div>
                </div>
                <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4">
                  <div class="input-field">
                    <label class="periodos">Al periodo</label>
                    <input id="default-input-date8" name="default-input-date8" type="date" placeholder="dd/mm/yyyy">
                    <p class="label-error"></p>
                  </div>
                </div>
                <div class="col-4 col-xs-4 col-sm-4 col-md-12 col-lg-4">
                  <div class="input-field">
                    <button type="button" class="button btn-blue buscar-tabla" name="button" onclick="buscarTeso(4);">Buscar</button>
                  </div>
                </div>
                <div id="cont4">

                </div>
              </div>
            @if($covestro->usar_credito == 1)
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <hr class="underline">
            </div>
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <p class="text-left"><big>Crédito y Cobranza</big></p>
            </div>
              <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6" id="newFilesSAP">
                <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4">
                  <div class="input-field">
                    <label class="periodos">Del periodo</label>
                    <input id="default-input-date9" name="default-input-date9" type="date" placeholder="dd/mm/yyyy">
                    <p class="label-error"></p>
                  </div>
                </div>
                <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4">
                  <div class="input-field">
                    <label class="periodos">Al periodo</label>
                    <input id="default-input-date10" name="default-input-date10" type="date" placeholder="dd/mm/yyyy">
                    <p class="label-error"></p>
                  </div>
                </div>
                <div class="col-4 col-xs-4 col-sm-4 col-md-12 col-lg-4">
                  <div class="input-field">
                    <button type="button" class="button btn-blue buscar-tabla" name="button" onclick="buscarTeso(5);">Buscar</button>
                  </div>
                </div>
                <div id="cont5">

                </div>
              </div>
              <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6" id="oldFilesSAP">
                <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4">
                  <div class="input-field">
                    <label class="periodos">Del periodo</label>
                    <input id="default-input-date11" name="default-input-date11" type="date" placeholder="dd/mm/yyyy">
                    <p class="label-error"></p>
                  </div>
                </div>
                <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4">
                  <div class="input-field">
                    <label class="periodos">Al periodo</label>
                    <input id="default-input-date12" name="default-input-date12" type="date" placeholder="dd/mm/yyyy">
                    <p class="label-error"></p>
                  </div>
                </div>
                <div class="col-4 col-xs-4 col-sm-4 col-md-12 col-lg-4">
                  <div class="input-field">
                    <button type="button" class="button btn-blue buscar-tabla" name="button" onclick="buscarTeso(6);">Buscar</button>
                  </div>
                </div>
                <div id="cont6">

                </div>
              </div>
            @endif
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <hr class="underline">
            </div>
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <p class="text-left"><big>Archivos a integrar</big></p>
            </div>

            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="contMisArchivos">
              <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="contMis">
                <table class="display AllDataTable table table-bordered table-hover table-striped" id="mis_archivos">
                  <thead>
                    <tr>
                      <th class="text-muted text-center"><small>Nombre del archivo</small></th>
                      <th class="text-muted text-center"><small>Eliminar</small></th>
                    </tr>
                  </thead>
                  <tbody id="archivos-a-usar">

                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-4 col-xs-4 col-sm-4 col-md-2 col-lg-2 col-md-offset-5 col-lg-offset-5">
          @if($c == 0)
            <button type="submit" class="button btn-blue" name="button">Integrar</button>
          @else
            @if ($proceso->integracion == 1)
              <button type="submit" class="button btn-blue" name="button">Integrar</button>
            @elseif($proceso->obtencion == 1)
              <a href="{{ url("/trafico") }}"><button type="button" class="button btn-link" name="button">Hay un proceso actual corriendo. Click aquí para continuar con el.</button></a>
            @elseif($proceso->timbrado == 1)
            <a href="{{ url("/integracion
            ") }}"><button type="button" class="button btn-link" name="button">Hay un proceso actual corriendo. Click aquí para continuar con el.</button></a>
            @else
              <button type="submit" class="button btn-blue" name="button">Integrar</button>
            @endif
          @endif
            </div>
        </div>
      </form>
      <!-- Modal -->
          <div class="modal fade" id="modal-no-archivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <img src="{{asset('assets/img/signo2.png')}}">
                    <h3><strong>Error</strong></h3>
                    <p>No seleccionaste archivos.</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-4" align="left">
                    </div>
                    <div class="col-xs-4" align="center">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                    <div class="col-xs-4" align="left">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin Modal -->
          <!-- Modal -->
          <div class="modal fade" id="modal-no-archivos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <img src="{{asset('assets/img/signo2.png')}}">
                    <h3><strong>Error</strong></h3>
                    <p>Debes seleccionar archivos de ambas categorías (SAP y Tesorería).</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-4" align="left">
                    </div>
                    <div class="col-xs-4" align="center">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                    <div class="col-xs-4" align="left">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin Modal -->
          <!-- Modal -->
          <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <img src="{{asset('assets/img/signo2.png')}}">
                    <h3><strong>Error</strong></h3>
                    <p>Hubo un error al integrar tus archivos.</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin modal -->
          <div class="modal fade" id="modal-cargando" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <h3><strong>Cargando</strong></h3>
                    <img src="{{asset('assets/img/cargando-loading-039.gif')}}" width="500">
                    <p>Espera un momento. Se están integrando tus archivos.</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

    @endsection

    <script type="text/javascript" src="{{asset('assets/js/Administrador/index.js')}}"></script>

    <script type="text/javascript" src="{{asset('assets/js/jquery.js')}}"></script>
    <script type="text/javascript">
      var contadorSAP = 0;
      var contadorTeso = 0;
      var contadorCred = 0;
      var tabla = "";
      var arrayTesoreria = [];
      var arrayTesoreriaNombres = [];
      var arraySAP = [];
      var arraySAPNombres = [];
      var arrayCredito = [];
      var arrayCreditoNombres = [];
      var usadosTesoreria = [];
      var usadosSAP = [];
      var usadosCredito = [];
      var usadosTesoreriaNombres = [];
      var usadosSAPNombres = [];
      var usadosCreditoNombres = [];
      $(document).ready(function(){
        tabla += '<div id="t1"><table class="display table table-bordered table-hover table-striped" id="tabla1">'+
                '<thead>'+
                  '<tr>'+
                    '<th class="text-muted text-center"><small>Nombre del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Fecha del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Seleccionar</small></th>'+
                  '</tr>'+
                '</thead>'+
                '<tbody>';
                  @foreach($archivosTeso as $teso)
                  arrayTesoreria.push({{ $teso->id_et }});
                  arrayTesoreriaNombres.push('{{ $teso->nombre }}');
                  tabla += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">{{$teso->nombre}}</td>'+
                    '<td id="date">{{$teso->fecha}}</td>'+
                    '<td><input type="checkbox" id="teso{{$teso->id_et}}" value="teso{{$teso->id_et}}" onchange="tablaConstruida(1, this.value)"><button type="button" class="button btn-delete" onclick="eliminarTeso(1, {{$teso->id_et}})"><i class="far fa-trash-alt"></i></button></td>'+
                  '</tr>'+
                  @endforeach
                '</tbody>'+
              '</table>';

        tabla += '<script type="text/javascript">$("#tabla1").DataTable({'+
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

        $("#cont1").append(tabla);

        tabla = "";
        tabla += '<div id="t2"><table class="display table table-bordered table-hover table-striped" id="tabla2">'+
                '<thead>'+
                  '<tr>'+
                    '<th class="text-muted text-center"><small>Nombre del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Fecha del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Seleccionar</small></th>'+
                  '</tr>'+
                '</thead>'+
                '<tbody>';
                  @foreach($historialTeso as $teso)
                  arrayTesoreria.push({{ $teso->id_et }});
                  arrayTesoreriaNombres.push('{{ $teso->nombre }}');
                  tabla += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">{{$teso->nombre}}</td>'+
                    '<td id="date">{{$teso->fecha}}</td>'+
                    '<td><input type="checkbox" id="teso{{$teso->id_et}}" value="teso{{$teso->id_et}}" onchange="tablaConstruida(1, this.value)"></td>'+
                  '</tr>'+
                  @endforeach
                '</tbody>'+
              '</table>';

        tabla += '<script type="text/javascript">$("#tabla2").DataTable({'+
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

        $("#cont2").append(tabla);

        tabla = "";
        tabla += '<div id="t3"><table class="display table table-bordered table-hover table-striped" id="tabla3">'+
                '<thead>'+
                  '<tr>'+
                    '<th class="text-muted text-center"><small>Nombre del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Fecha del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Seleccionar</small></th>'+
                  '</tr>'+
                '</thead>'+
                '<tbody>';
                  @foreach($archivosSAP as $teso)
                  arraySAP.push({{ $teso->id_es }});
                  arraySAPNombres.push('{{ $teso->nombre }}');
                  tabla += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">{{$teso->nombre}}</td>'+
                    '<td id="date">{{$teso->fecha}}</td>'+
                    '<td><input type="checkbox" id="sap{{$teso->id_es}}" value="sap{{$teso->id_es}}" onchange="tablaConstruida(2, this.value)"><button type="button" class="button btn-delete" onclick="eliminarTeso(3, {{$teso->id_es}})"><i class="far fa-trash-alt"></i></button></td>'+
                  '</tr>'+
                  @endforeach
                '</tbody>'+
              '</table>';

        tabla += '<script type="text/javascript">$("#tabla3").DataTable({'+
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

        $("#cont3").append(tabla);
        tabla = "";
        tabla += '<div id="t4"><table class="display table table-bordered table-hover table-striped" id="tabla4">'+
                '<thead>'+
                  '<tr>'+
                    '<th class="text-muted text-center"><small>Nombre del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Fecha del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Seleccionar</small></th>'+
                  '</tr>'+
                '</thead>'+
                '<tbody>';
                  @foreach($historialSAP as $teso)
                  arraySAP.push({{ $teso->id_es }});
                  arraySAPNombres.push('{{ $teso->nombre }}');
                  tabla += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">{{$teso->nombre}}</td>'+
                    '<td id="date">{{$teso->fecha}}</td>'+
                    '<td><input type="checkbox" id="sap{{$teso->id_es}}" value="sap{{$teso->id_es}}" onchange="tablaConstruida(2, this.value)"></td>'+
                  '</tr>'+
                  @endforeach
                '</tbody>'+
              '</table>';

        tabla += '<script type="text/javascript">$("#tabla4").DataTable({'+
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

        $("#cont4").append(tabla);

        tabla = "";
        tabla += '<div id="t5"><table class="display table table-bordered table-hover table-striped" id="tabla5">'+
                '<thead>'+
                  '<tr>'+
                    '<th class="text-muted text-center"><small>Nombre del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Fecha del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Seleccionar</small></th>'+
                  '</tr>'+
                '</thead>'+
                '<tbody>';
                  @foreach($archivosCred as $teso)
                  arrayCredito.push({{ $teso->id_ec }});
                  arrayCreditoNombres.push('{{ $teso->nombre }}');
                  tabla += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">{{$teso->nombre}}</td>'+
                    '<td id="date">{{$teso->fecha}}</td>'+
                    '<td><input type="checkbox" id="cred{{$teso->id_ec}}" value="cred{{$teso->id_ec}}" onchange="tablaConstruida(3, this.value)"><button type="button" class="button btn-delete" onclick="eliminarTeso(5, {{$teso->id_ec}})"><i class="far fa-trash-alt"></i></button></td>'+
                  '</tr>'+
                  @endforeach
                '</tbody>'+
              '</table>';

        tabla += '<script type="text/javascript">$("#tabla5").DataTable({'+
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

        $("#cont5").append(tabla);

        tabla = "";
        tabla += '<div id="t6"><table class="display table table-bordered table-hover table-striped" id="tabla6">'+
                '<thead>'+
                  '<tr>'+
                    '<th class="text-muted text-center"><small>Nombre del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Fecha del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Seleccionar</small></th>'+
                  '</tr>'+
                '</thead>'+
                '<tbody>'+
                  @foreach($historialCred as $teso)
                  arrayCredito.push({{ $teso->id_ec }});
                  arrayCreditoNombres.push('{{ $teso->nombre }}');
                  tabla += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">{{$teso->nombre}}</td>'+
                    '<td id="date">{{$teso->fecha}}</td>'+
                    '<td><input type="checkbox" id="cred{{$teso->id_ec}}" value="cred{{$teso->id_ec}}" onchange="tablaConstruida(3, this.value)"></td>'+
                  '</tr>'+
                  @endforeach
                '</tbody>'+
              '</table>';

        tabla += '<script type="text/javascript">$("#tabla6").DataTable({'+
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

        $("#cont6").append(tabla);
      });
      function cuentaTesoreria(checado){
        if(checado == true){
          contadorTeso++;
          //alert("hay "+contadorTeso+ " archivos seleccionados de Tesorería");
        }
        else{
          contadorTeso--;
          //alert("hay "+contadorTeso+ " archivos seleccionados de Tesorería");
        }
      }
      function cuentaSAP(checado){
        if(checado == true){
          contadorSAP++;
          //alert("hay "+contadorSAP+ " archivos seleccionados de SAP");
        }
        else{
          contadorSAP--;
          //alert("hay "+contadorSAP+ " archivos seleccionados de SAP");
        }
      }
      function cuentaCredito(checado){
        if(checado == true){
          contadorCred++;
          //alert("hay "+contadorCred+ " archivos seleccionados de Crédito");
        }
        else{
          contadorCred--;
          //alert("hay "+contadorCred+ " archivos seleccionados de Crédito");
        }
      }
      function buscarTeso(op){
        //var form = new FormData(document.getElementById('formTeso'));
        var inputs = "";
        var fecha1 = "";
        var fecha2 = "";
        var t = "";
        var func = "";
        switch(op){
          case 1:
            fecha1 = $("#default-input-date1").val();
            fecha2 = $("#default-input-date2").val();
            t = "teso";
            func = "tablaConstruida(1, this.value)";
            break;

          case 2:
            fecha1 = $("#default-input-date3").val();
            fecha2 = $("#default-input-date4").val();
            t = "teso";
            func = "tablaConstruida(1, this.value)";
            break;

          case 3:
            fecha1 = $("#default-input-date5").val();
            fecha2 = $("#default-input-date6").val();
            t = "sap";
            func = "tablaConstruida(2, this.value)";
            break;

          case 4:
            fecha1 = $("#default-input-date7").val();
            fecha2 = $("#default-input-date8").val();
            t = "sap";
            func = "tablaConstruida(2, this.value)";
            break;

          case 5:
            fecha1 = $("#default-input-date9").val();
            fecha2 = $("#default-input-date10").val();
            t = "cred";
            func = "tablaConstruida(3, this.value)";
            break;

          case 6:
            fecha1 = $("#default-input-date11").val();
            fecha2 = $("#default-input-date12").val();
            t = "cred";
            func = "tablaConstruida(3, this.value)";
            break;

        }
        $.ajax({
          url: 'archivosTeso',
          type: 'get',
          data: {inicio:fecha1,fin:fecha2,opcion:op},
          success: function (data) {
            tabla = "";
            tabla += '<div id="t'+op+'"><table class="display table table-bordered table-hover table-striped" id="tabla'+op+'">'+
                '<thead>'+
                  '<tr>'+
                    '<th class="text-muted text-center"><small>Nombre del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Fecha del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Seleccionar</small></th>'+
                  '</tr>'+
                '</thead>'+
                '<tbody>';
                  for(var i = 0; i < data.length; i++){
                    tabla += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">'+data[i].nombre+'</td>'+
                    '<td id="date">'+data[i].fecha+'</td>';
                    if(t == "teso"){
                      tabla += '<td><input type="checkbox" id="'+t+data[i].id_et+'" value="'+t+data[i].id_et+'" onchange="'+func+'">';
                      if(op == 1){
                        tabla += '<button type="button" class="button btn-delete" onclick="eliminarTeso(1, '+data[i].id_et+')"><i class="far fa-trash-alt"></i></button>';
                      }
                      tabla += '</td>'
                    }else{
                      if(t ==  "sap"){
                        tabla += '<td><input type="checkbox" id="'+t+data[i].id_es+'" value="'+t+data[i].id_es+'" onchange="'+func+'">';
                        if(op == 3){
                          tabla += '<button type="button" class="button btn-delete" onclick="eliminarTeso(3, '+data[i].id_es+')"><i class="far fa-trash-alt"></i></button>';
                        }
                        tabla += '</td>'
                      }
                      else{
                        if(t == "cred"){
                          tabla += '<td><input type="checkbox" id="'+t+data[i].id_ec+'" value="'+t+data[i].id_ec+'" onchange="'+func+'">';
                          if(op == 3){
                            tabla += '<button type="button" class="button btn-delete" onclick="eliminarTeso(5, '+data[i].id_ec+')"><i class="far fa-trash-alt"></i></button>';
                          }
                          tabla += '</td>'
                        }
                      }
                    }
                    tabla += '</tr>';
                  }
                tabla += '</tbody>'+
              '</table>';

              tabla += '<script type="text/javascript">$("#tabla'+op+'").DataTable({'+
                '"bDestroy": true,'+
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
              document.getElementById("cont"+op).removeChild(document.getElementById("t"+op));
              $("#cont"+op).append(tabla);
              for (var i = 0; i < usadosTesoreria.length; i++) {
                $("#"+usadosTesoreria[i]).prop("checked", true);
                $("#"+usadosTesoreria[i]).attr("disabled", true);
                inputs += '<input type="hidden" name="'+usadosTesoreria[i]+'" value="'+usadosTesoreria[i]+'">';
              }
              for (var i = 0; i < usadosSAP.length; i++) {
                $("#"+usadosSAP[i]).prop("checked", true);
                $("#"+usadosSAP[i]).attr("disabled", true);
                inputs += '<input type="hidden" name="'+usadosSAP[i]+'" value="'+usadosSAP[i]+'">';
              }
              for (var i = 0; i < usadosCredito.length; i++) {
                $("#"+usadosCredito[i]).prop("checked", true);
                $("#"+usadosCredito[i]).attr("disabled", true);
                inputs += '<input type="hidden" name="'+usadosCredito[i]+'" value="'+usadosCredito[i]+'">';
              }
              $("#results").html("");
              $("#results").html(inputs);

          },
          error: function () {
            alert("Error");
          }
        });
      }
      function eliminarTeso(op, id_archivo){
        //var form = new FormData(document.getElementById('formTeso'));
        var t = "";
        if(op == 1 || op == 2){
          t = "teso";
        }
        else{
          if(op == 3 || op == 4){
            t = "sap";
          }
          else{
            if(op == 5 || op == 6){
              t = "cred";
            }
          }
        }
        $.ajax({
          url: 'eliminarArchivo',
          type: 'get',
          data: {archivo:id_archivo,opcion:op},
          success: function (data) {
            tabla = "";
            tabla += '<div id="t'+op+'"><table class="display table table-bordered table-hover table-striped" id="tabla'+op+'">'+
                '<thead>'+
                  '<tr>'+
                    '<th class="text-muted text-center"><small>Nombre del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Fecha del archivo</small></th>'+
                    '<th class="text-muted text-center"><small>Seleccionar</small></th>'+
                  '</tr>'+
                '</thead>'+
                '<tbody>';
                  //alert("Datos de "+t+": "+data.length);
                  for(var i = 0; i < data.length; i++){
                    tabla += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-tesoreria">'+data[i].nombre+'</td>'+
                    '<td id="date">'+data[i].fecha+'</td>';
                    if(t == "teso"){
                      tabla += '<td><input type="checkbox" id="'+t+data[i].id_et+'" value="'+t+data[i].id_et+'" onchange="tablaConstruida(1, this.value)">';
                      if(op == 1){
                        tabla += '<button type="button" class="button btn-delete" onclick="eliminarTeso(1, '+data[i].id_et+')"><i class="far fa-trash-alt"></i></button>';
                      }
                      tabla += '</td>'
                    }else{
                      if(t ==  "sap"){
                        tabla += '<td><input type="checkbox" id="'+t+data[i].id_es+'" value="'+t+data[i].id_es+'" onchange="tablaConstruida(2, this.value)">';
                        if(op == 3){
                          tabla += '<button type="button" class="button btn-delete" onclick="eliminarTeso(3, '+data[i].id_es+')"><i class="far fa-trash-alt"></i></button>';
                        }
                        tabla += '</td>'
                      }
                      else{
                        if(t == "cred"){
                          tabla += '<td><input type="checkbox" id="'+t+data[i].id_ec+'" value="'+t+data[i].id_ec+'" onchange="tablaConstruida(3, this.value)">';
                          if(op == 3){
                            tabla += '<button type="button" class="button btn-delete" onclick="eliminarTeso(5, '+data[i].id_ec+')"><i class="far fa-trash-alt"></i></button>';
                          }
                          tabla += '</td>'
                        }
                      }
                    }
                    tabla += '</tr>';
                  }
                tabla += '</tbody>'+
              '</table>';

              tabla += '<script type="text/javascript">$("#tabla'+op+'").DataTable({'+
                '"bDestroy": true,'+
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
              document.getElementById("cont"+op).removeChild(document.getElementById("t"+op));
              $("#cont"+op).append(tabla);
          },
          error: function () {
            alert("Error");
          }
        });
      }
      function tablaConstruida(tipodoc, iddoc){
        //var encuentra = false;
        var inputs = "";
        var tabla = '<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="contMis"><table class="display table table-bordered table-hover table-striped" id="mis_archivos">'+
                  '<thead>'+
                    '<tr>'+
                      '<th class="text-muted text-center"><small>Nombre del archivo</small></th>'+
                      '<th class="text-muted text-center"><small>Eliminar</small></th>'+
                    '</tr>'+
                  '</thead>'+
                  '<tbody id="archivos-a-usar">';
        switch(tipodoc){
          case 1:
            usadosTesoreria.push(iddoc);
            for(var i = 0; i < arrayTesoreria.length; i++){
              if("teso"+arrayTesoreria[i] == iddoc){
                usadosTesoreriaNombres.push(arrayTesoreriaNombres[i]);
                $("#"+iddoc).attr("disabled", true);
              }
            }
            contadorTeso++;

            break;

          case 2:
            usadosSAP.push(iddoc);
            for(var i = 0; i < arraySAP.length; i++){
              if("sap"+arraySAP[i] == iddoc){
                usadosSAPNombres.push(arraySAPNombres[i]);
                $("#"+iddoc).attr("disabled", true);
              }
            }
            contadorSAP++;
            break;

          case 3:
            usadosCredito.push(iddoc);
            for(var i = 0; i < arrayCredito.length; i++){
              if("teso"+arrayCredito[i] == iddoc){
                usadosCreditoNombres.push(arrayCreditoNombres[i]);
                $("#"+iddoc).attr("disabled", true);
              }
            }
            contadorCred++;
            break;
        }

        for (var i = 0; i < usadosTesoreria.length; i++) {
          tabla += '<tr><td>'+usadosTesoreriaNombres[i]+'</td><td><button type="button" class="button btn-delete" onclick="eliminarSeleccion(1, \''+usadosTesoreria[i]+'\')"><i class="far fa-trash-alt"></i></button></td></tr>';
          inputs += '<input type="hidden" name="'+usadosTesoreria[i]+'" value="'+usadosTesoreria[i]+'">';
        }
        for (var i = 0; i < usadosSAP.length; i++) {
          tabla += '<tr><td>'+usadosSAPNombres[i]+'</td><td><button type="button" class="button btn-delete" onclick="eliminarSeleccion(2, \''+usadosSAP[i]+'\')"><i class="far fa-trash-alt"></i></button></td></tr>';
          inputs += '<input type="hidden" name="'+usadosSAP[i]+'" value="'+usadosSAP[i]+'">';
        }
        for (var i = 0; i < usadosCredito.length; i++) {
          tabla += '<tr><td>'+usadoCreditoNombres[i]+'</td><td><button type="button" class="button btn-delete" onclick="eliminarSeleccion(3, \''+usadosCredito[i]+'\')"><i class="far fa-trash-alt"></i></button></td></tr>';
          inputs += '<input type="hidden" name="'+usadosCredito[i]+'" value="'+usadosCredito[i]+'">';
        }
        tabla +='</tbody>'+
                '</table>';

        tabla += '<script type="text/javascript">$("#mis_archivos").DataTable({'+
                '"bDestroy": true,'+
                'language:{'+
                  '"sProcessing":     "Procesando...",'+
                  '"sLengthMenu":     "Mostrar _MENU_ registros",'+
                  '"sZeroRecords":    "No se encontraron resultados",'+
                  '"sEmptyTable":     "Ningún archivo para integrar seleccionado",'+
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
              document.getElementById("contMisArchivos").removeChild(document.getElementById("contMis"));
              $("#contMisArchivos").append(tabla); 
              $("#results").html("");
              $("#results").html(inputs);
        
      }
      function eliminarSeleccion(tipodoc, iddoc) {
        var inputs = "";
        var tabla = '<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="contMis"><table class="display table table-bordered table-hover table-striped" id="mis_archivos">'+
                  '<thead>'+
                    '<tr>'+
                      '<th class="text-muted text-center"><small>Nombre del archivo</small></th>'+
                      '<th class="text-muted text-center"><small>Eliminar</small></th>'+
                    '</tr>'+
                  '</thead>'+
                  '<tbody id="archivos-a-usar">';
        switch(tipodoc){
          case 1:
            for(var i = 0; i < usadosTesoreria.length; i++){
              if(usadosTesoreria[i] == iddoc){
                $("#"+usadosTesoreria[i]).attr("disabled", false);
                //alert("#"+iddoc);
                //$("#"+iddoc).attr("checked", true);
                $("#"+iddoc).prop("checked", false);
                usadosTesoreria.splice(i, 1);
                usadosTesoreriaNombres.splice(i, 1);
              }
            }
            //alert("Hay en tesoreria: "+usadosTesoreria.length+" archivos");
            contadorTeso--;

            break;

          case 2:
            for(var i = 0; i < usadosSAP.length; i++){
              if(usadosSAP[i] == iddoc){
                $("#"+usadosSAP[i]).removeAttr("disabled");
                $("#"+iddoc).prop("checked", false);
                usadosSAP.splice(i, 1);
                usadosSAPNombres.splice(i, 1);
              }
            }
            //alert("Hay en SAP: "+usadosSAP.length+" archivos");
            contadorSAP--;
            break;

          case 3:
            for(var i = 0; i < usadosCredito.length; i++){
              if(usadosCredito[i] == iddoc){
                $("#"+usadosCredito[i]).removeAttr("disabled");
                $("#"+iddoc).prop("checked", false);
                usadosCredito.splice(i, 1);
                usadosCreditoNombres.splice(i, 1);
              }
            }
            contadorCred--;
            break;
        }

        for (var i = 0; i < usadosTesoreria.length; i++) {
          tabla += '<tr><td>'+usadosTesoreriaNombres[i]+'</td><td><button type="button" class="button btn-delete" onclick="eliminarSeleccion(1, \''+usadosTesoreria[i]+'\')"><i class="far fa-trash-alt"></i></button></td></tr>';
          inputs += '<input type="hidden" name="'+usadosTesoreria[i]+'" value="'+usadosTesoreria[i]+'">';
        }
        for (var i = 0; i < usadosSAP.length; i++) {
          tabla += '<tr><td>'+usadosSAPNombres[i]+'</td><td><button type="button" class="button btn-delete" onclick="eliminarSeleccion(2, \''+usadosSAP[i]+'\')"><i class="far fa-trash-alt"></i></button></td></tr>';
          inputs += '<input type="hidden" name="'+usadosSAP[i]+'" value="'+usadosSAP[i]+'">';
        }
        for (var i = 0; i < usadosCredito.length; i++) {
          tabla += '<tr><td>'+usadoCreditoNombres[i]+'</td><td><button type="button" class="button btn-delete" onclick="eliminarSeleccion(3, \''+usadosCredito[i]+'\')"><i class="far fa-trash-alt"></i></button></td></tr>';
          inputs += '<input type="hidden" name="'+usadosCredito[i]+'" value="'+usadosCredito[i]+'">';
        }

        tabla +='</tbody>'+
                '</table>';

        tabla += '<script type="text/javascript">$("#mis_archivos").DataTable({'+
                '"bDestroy": true,'+
                'language:{'+
                  '"sProcessing":     "Procesando...",'+
                  '"sLengthMenu":     "Mostrar _MENU_ registros",'+
                  '"sZeroRecords":    "No se encontraron resultados",'+
                  '"sEmptyTable":     "Ningún archivo para integrar seleccionado",'+
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
              document.getElementById("contMisArchivos").removeChild(document.getElementById("contMis"));
              $("#contMisArchivos").append(tabla); 
              $("#results").html(inputs);
      }
      function integrar(){

        if(contadorTeso == 0 && contadorSAP == 0){
          $("#modal-no-archivo").modal('show');
        }
        else{
          if(contadorTeso == 0 || contadorSAP == 0){
            $("#modal-no-archivos").modal('show');
          }
          else{
            //window.location.href = "integracion";
            var form = new FormData(document.getElementById('formInt'));
            $.ajax({
              url: 'integrando',
              type: 'post',
              data: form,
              processData: false,
              contentType: false,
              beforeSend: function(){
                $("#modal-cargando").modal('show');
              },
              success: function (data) {
                console.log(data);
                if(data.actulizoDB = "actualizado"){
                  window.location.href = "integracion";
                } else {
                  $("#modal-error").modal('show');
                }
              },
              error: function () {
                $("#modal-cargando").modal('hide');
                $("#modal-error").modal('show');
              }
            });

          }
        }
      }
    </script>
    <script type="text/javascript">
    $(function () {
        $(document).on('click', '.borrar', function (event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });
    });
    </script>
  @section('footer')
  @endsection
