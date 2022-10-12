{{-- Reportes de Complementos (Tablas) --}}
<script type="text/javascript">
  $(document).ready(function () {
      var valorDolares = 0;
      var valorPesos = 0;
      var totalDolares = 0;
      var totalPesos = 0;
      var tablaArchivos = '<div id="contComplementos">';
    tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">ID del cliente</th>'+
          '<th class="text-muted text-center">Cliente</th>'+
          '<th class="text-muted text-center">Clearing Document</th>'+
          '<th class="text-muted text-center">Fecha</th>'+
          '<th class="text-muted text-center">Descargar PDF</th>'+
          //'<th class="text-muted text-center">Monto MXN</th>'+
          '<th class="text-muted text-center">Descargar XML</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
      @foreach ($datos as $p)
      tablaArchivos += '<tr class="text-muted text-center" id="">'+
                '<td id="archivos-recientes-tesoreria">{{$p->id_cliente}}</td>'+
                '<td id="date">{{$p->nombre_c}}</td>'+
                '<td>{{ $p->clearing_document }}</td>'+
                '<td>{{ $p->fechap }}</td>'+
                '<td><a href="downloadPDF/{{$p->id_ar}}" title="Descargar archivo PDF" style="font-size: 26px;"><i class="fa fa-file-pdf"></i></a></td>'+
                '<td><a href="downloadXML/{{$p->id_ar}}" title="Descargar archivo XML" style="font-size: 26px;"><i class="fa fa-file-code" ></i></a></td>'+
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
   if(($("#default-input-date1").val() == "" && $("#default-input-date2").val() != "") || ($("#default-input-date1").val() != "" && $("#default-input-date2").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#default-input-date1").val() == "" && $("#default-input-date2").val() == ""){
      if($("#cliente").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        $.ajax({
          url: 'buscarComplementos',
          type: 'get',
          data: {fechaInicio: $("#fechaInicio").val(), fechaFin:$("#fechaFin").val(), id_cliente:$("#cliente").val(), folio:$("#folio").val(), clearing:$("#clearing").val()},
          success: function (data) {
            document.getElementById("bodyComplementos").removeChild(document.getElementById("contComplementos"));
            var tablaArchivos = '<div id="contComplementos">';
    tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">ID del cliente</th>'+
          '<th class="text-muted text-center">Cliente</th>'+
          '<th class="text-muted text-center">Clearing Document</th>'+
          '<th class="text-muted text-center">Fecha</th>'+
          '<th class="text-muted text-center">Descargar XML</th>'+
          '<th class="text-muted text-center">Descargar PDF</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                '<td id="archivos-recientes-tesoreria">'+data[0].id_cliente+'</td>'+
                '<td id="date"'+data[i].nombre_c+'</td>'+
                '<td>'+data[i].clearing_document+'</td>'+
                '<td>'+data[i].fechap+'</td>'+
                '<td><a href="downloadPDF/'+data[i].id_ar+'" title="Descargar Archivo PDF" style="font-size: 26px;"><i class="fa fa-file-pdf"></i></a></td>'+
                '<td><a href="downloadXML/'+data[i].id_ar+'" title="Descargar Archivo XML" style="font-size: 26px;"><i class="fa fa-file-code"></i></a></td>'+
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
     else{
      $.ajax({
          url: 'buscarImpuestos',
          type: 'get',
          data: {fechaInicio: $("#fechaInicio").val(), fechaFin:$("#fechaFin").val(), id_cliente:$("#cliente").val(), folio:$("#folio").val(), clearing:$("#clearing").val()},
          success: function (data) {
            document.getElementById("bodyComplementos").removeChild(document.getElementById("contComplementos"));
            var tablaArchivos = '<div id="contComplementos">';
    tablaArchivos += '<table class="display table table-bordered table-hover table-striped" id="tablaComplementos">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">ID del cliente</th>'+
          '<th class="text-muted text-center">Cliente</th>'+
          '<th class="text-muted text-center">Clearing Document</th>'+
          '<th class="text-muted text-center">Fecha</th>'+
          '<th class="text-muted text-center">Descargar XML</th>'+
          '<th class="text-muted text-center">Descargar PDF</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
          for (var i = 0; i < data.length; i++) {
            tablaArchivos += '<tr class="text-muted text-center" id="">'+
                '<td id="archivos-recientes-tesoreria">'+data[0].id_cliente+'</td>'+
                '<td id="date"'+data[i].nombre_c+'</td>'+
                '<td>'+data[i].clearing_document+'</td>'+
                '<td>'+data[i].fechap+'</td>'+
                '<td><a href="downloadPDF/'+data[i].id_ar+'" title="Descargar Archivo PDF" style="font-size: 26px;"><i class="fa fa-file-pdf"></i></a></td>'+
                '<td><a href="downloadXML/'+data[i].id_ar+'" title="Descargar Archivo XML" style="font-size: 26px;"><i class="fa fa-file-code"></i></a></td>'+
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
  function descargar() {
    var valorDolares = 0;
      var valorPesos = 0;
      var totalDolares = 0;
      var totalPesos = 0;
   if(($("#default-input-date1").val() == "" && $("#default-input-date2").val() != "") || ($("#default-input-date1").val() != "" && $("#default-input-date2").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#default-input-date1").val() == "" && $("#default-input-date2").val() == ""){
      if($("#cliente").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        $.ajax({
          url: 'descargarpar',
          type: 'get',
          data: {fechaInicio: $("#fechaInicio").val(), fechaFin:$("#fechaFin").val(), id_cliente:$("#cliente").val(), folio:$("#folio").val(), clearing:$("#clearing").val()},
          success: function (data) {
            alert("Descarga hecha")
          },
          error: function () {
            alert("Error");
          }
        });
      }
     }
     else{
      $.ajax({
          url: 'descargarpar',
          type: 'get',
          data: {fechaInicio: $("#fechaInicio").val(), fechaFin:$("#fechaFin").val(), id_cliente:$("#cliente").val(), folio:$("#folio").val(), clearing:$("#clearing").val()},
          success: function (data) {
            alert("Descarga hecha")
          },
          error: function () {
            alert("Error");
          }
        });
     }
   }
  }
</script>
{{-- Reportes de Montos (Tablas) --}}
<script type="text/javascript">
  $(document).ready(function () {
      var valorDolares = 0;
      var valorPesos = 0;
      var totalDolares = 0;
      var totalPesos = 0;
      @foreach ($datos as $p)
        @if($p->monedaP == "MXN")
          @if($p->tipocambioP > 1)
            valorDolares = {{ $p->montoP }} / {{ $p->tipocambioP }};
            totalPesos = totalPesos + {{ $p->montoP }};
            totalDolares = totalDolares + valorDolares;
          @else
            totalPesos = totalPesos + {{ $p->montoP }};
          @endif
        @else
          valorPesos = {{ $p->montoP }}*{{ $p->tipocambioP }};
          totalPesos = totalPesos + valorPesos;
          totalDolares = totalDolares + {{ $p->montoP }};
        @endif
      @endforeach

      $("#monto_total_pesos").html("");
      $("#monto_total_pesos").html(new Intl.NumberFormat().format(totalPesos)+" Pesos Mexicanos");
      $("#monto_total_dolares").html("");
      $("#monto_total_dolares").html(new Intl.NumberFormat().format(totalDolares)+" Dólares");
  })
</script>
<script type="text/javascript">
  function buscar() {
    var valorDolares = 0;
      var valorPesos = 0;
      var totalDolares = 0;
      var totalPesos = 0;
   if(($("#default-input-date1").val() == "" && $("#default-input-date2").val() != "") || ($("#default-input-date1").val() != "" && $("#default-input-date2").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#default-input-date1").val() == "" && $("#default-input-date2").val() == ""){
      if($("#cliente").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        $.ajax({
          url: 'buscarComplementos',
          type: 'get',
          data: {fechaInicio: $("#fechaInicio").val(), fechaFin:$("#fechaFin").val(), id_cliente:$("#cliente").val(), folio:$("#folio").val(), clearing:$("#clearing").val()},
          success: function (data) {
            for (var i = 0; i < data.length; i++) {
              if(data[i].monedaP == "MXN"){
                if(data[i].tipocambioP > 1){
                  valorDolares = data[i].montoP / data[i].tipocambioP;
                  totalPesos = totalPesos + data[i].montoP;
                  totalDolares = totalDolares + valorDolares
                }
                else{
                  totalPesos = totalPesos + data[i].montoP;
                }
              }
              else{
                valorPesos = data[i].montoP * data[i].tipocambioP;
                totalPesos = totalPesos + valorPesos;
                if(data[i].monedaP == "USD"){
                  totalDolares = totalDolares + data[i].montoP;
                }
              }
            }
            $("#monto_total_pesos").html("");
            $("#monto_total_pesos").html(new Intl.NumberFormat().format(totalPesos)+" Pesos Mexicanos");
            $("#monto_total_dolares").html("");
            $("#monto_total_dolares").html(new Intl.NumberFormat().format(totalDolares)+" Dólares");

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
            for (var i = 0; i < data.length; i++) {
              if(data[i].monedaP == "MXN"){
                if(data[i].tipocambioP > 1){
                  valorDolares = data[i].montoP / data[i].tipocambioP;
                  totalPesos = totalPesos + data[i].montoP;
                  totalDolares = totalDolares + valorDolares
                }
                else{
                  totalPesos = totalPesos + data[i].montoP;
                }
              }
              else{
                valorPesos = data[i].montoP * data[i].tipocambioP;
                totalPesos = totalPesos + valorPesos;
                if(data[i].monedaP == "USD"){
                  totalDolares = totalDolares + data[i].montoP;
                }
              }
            }
            $("#monto_total_pesos").html("");
            $("#monto_total_pesos").html(new Intl.NumberFormat().format(totalPesos)+" Pesos Mexicanos");
            $("#monto_total_dolares").html("");
            $("#monto_total_dolares").html(new Intl.NumberFormat().format(totalDolares)+" Dólares");
          },
          error: function () {
            alert("Error");
          }
        });
     }
   }
  }
  function descargar() {
    var valorDolares = 0;
      var valorPesos = 0;
      var totalDolares = 0;
      var totalPesos = 0;
   if(($("#default-input-date1").val() == "" && $("#default-input-date2").val() != "") || ($("#default-input-date1").val() != "" && $("#default-input-date2").val() == "")){
    alert("Debes elegir un inicio y un fin, o no hacer búsqueda por fecha")
   }
   else{
     if($("#default-input-date1").val() == "" && $("#default-input-date2").val() == ""){
      if($("#cliente").val() == ""){
        alert("Debes buscar por fecha o por cliente")
      }
      else{
        $.ajax({
          url: 'descargarpar',
          type: 'get',
          data: {fechaInicio: $("#fechaInicio").val(), fechaFin:$("#fechaFin").val(), id_cliente:$("#cliente").val(), folio:$("#folio").val(), clearing:$("#clearing").val()},
          success: function (data) {
            alert("Descarga hecha")
          },
          error: function () {
            alert("Error");
          }
        });
      }
     }
     else{
      $.ajax({
          url: 'descargarpar',
          type: 'get',
          data: {fechaInicio: $("#fechaInicio").val(), fechaFin:$("#fechaFin").val(), id_cliente:$("#cliente").val(), folio:$("#folio").val(), clearing:$("#clearing").val()},
          success: function (data) {
            alert("Descarga hecha")
          },
          error: function () {
            alert("Error");
          }
        });
     }
   }
  }
</script>