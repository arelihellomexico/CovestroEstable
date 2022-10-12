
@extends('Plantilla.plantilla')

@section('header')
@section('title','Reporte de Montos')
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
    <p class="text-muted text-left"><big>Reporte de Montos</big><img href="" src="{{asset('assets/img/bank.svg')}}" class="icon-header"></p>
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
        <label for="default-input-date">ID del cliente:</label>
        <input id="cliente" type="text" placeholder="Escribe el ID" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">fecha del periodo:</label>
        <input id="default-input-date1" text="periodo" type="date" placeholder="dd/mm/yyyy" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    
    <div class="col-4 col-xs-4 col-sm-6 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <label for="default-input-date">Al periodo</label>
        <input id="default-input-date2" text="periodo" type="date" placeholder="dd/mm/yyyy" class="input-date">
        <p class="label-error"></p>
      </div>
    </div>
    
    <div class="col-4 col-xs-4 col-sm-4 col-md-3 col-lg-2">
      <div class="form-group input-field">
        <button type="button" class="button btn-primary buscar-tabla" name="button" onclick="buscar();">Buscar</button>
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
  <div id="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-5" align="left"  class="col-xs-5 " align="center"> 
      <div class="panel-danger">
        <div class="panel-heading">
            <div align="center"  >
               <h2><b><center> Peso Mexicano (MXN):</b></h2>
               <h2 class="text-left leadv" ><i class="far fa-money-bill-alt"></i><div id="monto_total_pesos"></div></h2>

            </div >
              <!--<h4><b><center>Peso Mexicano (MXN):</b></h4>-->
              <!-- <i style="font-size:26px" class="fa">&#xf0d6;</i>
              <img src="imagen/1.jpg" width="52" height="29"-->
        </div>
      </div>
    </div>

    <div class="col-xs-5" align="left"  class="col-xs-5" align="center">
      <div class=" panel-primary">
        <div class="panel panel-heading">
          <div align="center"  >
             <h2><b><center> Dólares (USD):</b></h2>
             <h2 class="text-left leadv" ><i class="far fa-money-bill-alt"></i><div id="monto_total_dolares"></div></h2>
            </div >
          <!--  <i style="font-size:26px" class="fa">&#xf0d6;</i>-->
        </div>
      </div>
    </div>
    <div class="col-xs-1"></div>
  </div>


  

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
          url: 'buscarMontos',
          type: 'get',
          data: {fechaInicio: $("#default-input-date1").val(), fechaFin:$("#default-input-date2").val(), id_cliente:$("#cliente").val()},
          success: function (data) {
            for (var i = 0; i < data.length; i++) {
              //alert(data[i].nombre_c + " " + data[i].montoP + " " + data[i].tipocambioP);
              //alert("Total en Dólares: "+valorDolares)
              if(data[i].monedaP == "MXN"){
                if(data[i].tipocambioP > 1){
                  valorDolares = data[i].montoP / data[i].tipocambioP;
                  totalPesos = parseFloat(totalPesos) + parseFloat(data[i].montoP);
                  totalDolares = parseFloat(totalDolares) + parseFloat(valorDolares)
                }
                else{
                  totalPesos = parseFloat(totalPesos) + parseFloat(data[i].montoP);
                }
              }
              else{
                valorPesos = data[i].montoP * data[i].tipocambioP;
                totalPesos = totalPesos + valorPesos;
                if(data[i].monedaP == "USD"){
                  totalDolares = parseFloat(totalDolares) + parseFloat(data[i].montoP);
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
          url: 'buscarMontos',
          type: 'get',
          data: {fechaInicio: $("#default-input-date1").val(), fechaFin:$("#default-input-date2").val(), id_cliente:$("#cliente").val()},
          success: function (data) {
            for (var i = 0; i < data.length; i++) {
              //alert(data[i].nombre_c + " " + data[i].montoP + " " + data[i].tipocambioP);

              if(data[i].monedaP == "MXN"){
                if(data[i].tipocambioP > 1){
                  valorDolares = data[i].montoP / data[i].tipocambioP;
                  totalPesos = parseFloat(totalPesos) + parseFloat(data[i].montoP);
                  totalDolares = parseFloat(totalDolares) + parseFloat(valorDolares)
                }
                else{
                  totalPesos = parseFloat(totalPesos) + parseFloat(data[i].montoP);
                }
              }
              else{
                valorPesos = data[i].montoP * data[i].tipocambioP;
                totalPesos = totalPesos + valorPesos;
                if(data[i].monedaP == "USD"){
                  totalDolares = parseFloat(totalDolares) + parseFloat(data[i].montoP);
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
<!-- botones-->
   
@endsection
@section('footer')
@endsection
