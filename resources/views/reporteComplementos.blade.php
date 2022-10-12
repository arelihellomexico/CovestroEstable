@extends('Plantilla.plantilla')

@section('header')
@section('title','Reporte de Complementos')
@section('barra-superior')
@section('reportesMenu','reportes-active')
@section('sidemenu')
@endsection

@section('contenido')
<!--Encabezado-->
<div class="row">
  <div class="col-8 col-xs-8 col-sm-8 col-md-8 col-lg-8">
    <h2 class="text-left leadv"><i class="far fa-money-bill-alt"></i>Reportes</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 container-header">
    <p class="text-muted text-left"><big>Reporte de Complementos</big><img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
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
        <label for="default-input-date">ID cliente o clearing:</label>
        <input id="cliente" type="text" placeholder="Escribe aqui..." class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">fecha del periodo:</label>
        <input id="default-input-fecha1" text="periodo" type="date" placeholder="dd/mm/yyyy" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    
    <div class="col-4 col-xs-4 col-sm-6 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Al periodo</label>
        <input id="default-input-fecha2" text="periodo" type="date" placeholder="dd/mm/yyyy" class="input-date">
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
    <div class="col-4 col-xs-4 col-sm-4 col-md-6 col-lg-4 col-xs-offset-4 col-sm-offset-4 col-md-offset-3 col-lg-offset-4 text-center">
    </div>
  </div>
  <!-- Table Tesoreria -->
  <!--Encabezado-->
</div>
<!-- botones-->
       <div class="col-xs-11" align="center"  class="col-xs-11 " align="center">
         <a href="#" onclick="DescargarPDF();"><button type="button" class="btn btn-danger">Descargar PDF </button></a>
         <a href="#" onclick="Descargar();"><button type="button" class="btn btn-success">Descargar Excel</button></a>
       </div>
<!-- Fin encabezado -->
<script type="text/javascript">
  $(document).ready(function () {
      var tablaArchivos = '<div id="contComplementos">';
    tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">ID_Cliente</th>'+
          '<th class="text-muted text-center">Cliente</th>'+
          '<th class="text-muted text-center">Clearing</th>'+
          '<th class="text-muted text-center">Fecha</th>'+
          '<th class="text-muted text-center">Descargar PDF</th>'+
          '<th class="text-muted text-center">Descargar XML</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
      @foreach ($datos as $p)
      tablaArchivos+='<tr><td>{{ $p->id_cliente}}</td>'+
      '<td>{{ $p->nombre_c}}</td>'+
      '<td>{{ $p->clearing_document}}</td>'+
      '<td>{{ $p->fechabus}}</td>'+
      '<td><a href="downloadPDF/{{ $p->id_ar}}" title="Descargar archivo PDF" style="font-size: 26px;"><i class="fa fa-file-pdf"></i></td>'+
      '<td><a href="downloadXML/{{ $p->id_ar}}" title="Descargar archivo XML" style="font-size: 26px;"><i class="fa fa-file-code" ></i></td></tr>';
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
   if(($("#default-input-fecha1").val() == "" && $("#default-input-fecha2").val() != "") || ($("#default-input-fecha1").val() != "" && $("#default-input-fecha2").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#default-input-fecha1").val() == "" && $("#default-input-fecha2").val() == ""){
      if($("#cliente").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        $.ajax({
          url:'buscarComplementos',
          type: 'get',
          data: {fecha_inicio:$("#default-input-fecha1").val(), fecha_final:$("#default-input-fecha2").val(), Acliente:$("#cliente").val()},
          success: function (data) {
            document.getElementById("bodyComplementos").removeChild(document.getElementById("contComplementos"));
            var tablaArchivos = '<div id="contComplementos">';
        tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
          '<thead>'+
            '<tr>'+
          '<th class="text-muted text-center">ID_Cliente</th>'+
          '<th class="text-muted text-center">Cliente</th>'+
          '<th class="text-muted text-center">Clearing</th>'+
          '<th class="text-muted text-center">Fecha</th>'+
          '<th class="text-muted text-center">Descargar PDF</th>'+
          '<th class="text-muted text-center">Descargar XML</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                    '<td id="archivos-recientes-Pago">'+data[i].id_cliente+'</td>'+
                    '<td>'+data[i].nombre_c+'</td>'+
                    '<td>'+data[i].clearing_document+'</td>'+
                    '<td>'+data[i].fechabus+'</td>'+
                    '<td><a href="downloadPDF/'+data[0].id_ar+'" title="Descargar archivo PDF" style="font-size: 26px;"><i class="fa fa-file-pdf"></i></td>'+
                    '<td><a href="downloadXML/'+data[0].id_ar+'" title="Descargar archivo XML" style="font-size: 26px;"><i class="fa fa-file-code" ></i></td>'+
                    
                  '</tr>' ;        
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
        url:'buscarComplementos',
          type: 'get',
          data: {fecha_inicio:$("#default-input-fecha1").val(), fecha_final:$("#default-input-fecha2").val(), Acliente:$("#cliente").val()},
          success: function (data) {
            document.getElementById("bodyComplementos").removeChild(document.getElementById("contComplementos"));
            var tablaArchivos = '<div id="contComplementos">';
        tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
          '<thead>'+
            '<tr>'+
          '<th class="text-muted text-center">ID_Cliente</th>'+
          '<th class="text-muted text-center">Cliente</th>'+
          '<th class="text-muted text-center">Clearing</th>'+
          '<th class="text-muted text-center">Fecha</th>'+
          '<th class="text-muted text-center">Descargar PDF</th>'+
          '<th class="text-muted text-center">Descargar XML</th>'+
           '</tr>'+
          '</thead>'+
          '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
            '<td id="archivos-recientes-Pago">'+ data[i].id_cliente+'</td>'+
                    '<td>'+data[i].nombre_c+'</td>'+
                    '<td>'+data[i].clearing_document+'</td>'+
                    '<td>'+data[i].fechabus+'</td>'+
                    '<td><a href="downloadPDF/'+data[0].id_ar+'" title="Descargar archivo PDF" style="font-size: 26px;"><i class="fa fa-file-pdf"></i></td>'+
                    '<td><a href="downloadXML/'+data[0].id_ar+'" title="Descargar archivo XML" style="font-size: 26px;"><i class="fa fa-file-code" ></i></td>'+
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

  function Descargar() {
   if(($("#default-input-fecha1").val() == "" && $("#default-input-fecha2").val() != "") || ($("#default-input-fecha1").val() != "" && $("#default-input-fecha2").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#default-input-fecha1").val() == "" && $("#default-input-fecha2").val() == ""){
      if($("#Acliente").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        window.location.href = "pagosExcel?fecha_inicio="+$("#default-input-fecha1").val()+"&fecha_final="+$("#default-input-fecha2").val()+"&Acliente="+$("#cliente").val();
      }
     }
     else{
      window.location.href = "pagosExcel?fecha_inicio="+$("#default-input-fecha1").val()+"&fecha_final="+$("#default-input-fecha2").val()+"&Acliente="+$("#cliente").val();
     }
   }
  }

  function DescargarPDF() {
   if(($("#default-input-fecha1").val() == "" && $("#default-input-fecha2").val() != "") || ($("#default-input-fecha1").val() != "" && $("#default-input-fecha2").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#default-input-fecha1").val() == "" && $("#default-input-fecha2").val() == ""){
      if($("#Acliente").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        window.location.href = "pagosPDF?fecha_inicio="+$("#default-input-fecha1").val()+"&fecha_final="+$("#default-input-fecha2").val()+"&Acliente="+$("#cliente").val();
      }
     }
     else{
      window.location.href = "pagosPDF?fecha_inicio="+$("#default-input-fecha1").val()+"&fecha_final="+$("#default-input-fecha2").val()+"&Acliente="+$("#cliente").val();
     }
   }
  }
</script>
@endsection
@section('footer')
@endsection
