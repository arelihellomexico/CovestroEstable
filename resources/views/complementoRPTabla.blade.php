@extends('Plantilla.plantilla')
@section('title','Reporte de Pagos tabla')
@section('barra-superior')
@section('sidemenu')

@section('contenido')
<!-- Inicio del Encabezado -->
<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <h2><b>Complemento para recepción de pagos</b></h2>
  <br />
  <h4><b>Tesorería</b></h4>
  <hr class="underline">
</div>

<!-- Inicio formulario -->
<div class="col-12 col-xs-12 col-sm-12 col-md-11 col-lg-12">
  <form id="formulario" action="javascript:cargarExcelEspecial();" method="post">
    {{csrf_field()}}
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
          <label>Selecciona el archivo de excel de tesorería a comparar</label>
          <select id="layout" name="layout" class="form-control layo">

            @foreach($layout as $l)

            <option value="{{$l->id_et}}">{{$l->nombre}}</option>
            @endforeach
          </select>
        </div>
      </div>



      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
          <label for="FormatoDePagoP">Forma de pago</label>
          <select class="form-control" id="FormatoDePagoP" name="FormatoDePagoP" required>
            <!-- <option selected>Seleccione</option> -->
            <option value="01">01- Efectivo</option>
            <option value="02">02- Cheque nominativo</option>
            <option value="03" selected>03- Tranferencia electrónica de fondos</option>
            <option value="12">12- Dación en pago</option>
            <option value="15">15- Condonación</option>
            <option value="17">17- Compensación</option>
            <option value="25">25- Remisión de deuda</option>
            <option value="99">99- Por defir</option>
          </select>
        </div>
      </div>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <div class="form-group">
    <label for="MetodoDePagoDR">Método de pago DR</label>
    <select class="form-control" id="MetodoDePagoDR" name="MetodoDePagoDR" required>
      <!-- <option selected>Catálogo c_MetodoPago</option> -->
      <option value="PUE">PUE - Pago en una sola exhibición</option>
      <option value="PIP">PIP - Pago inicial y parcialidades</option>
      <option value="PPD" selected>PPD - Pago en parcialidades o diferido</option>
    </select>
  </div>
</div>

<!-- Inicio del Encabezado 2 -->
<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <h4><b>SAP</b></h4>
  <hr class="underline">
</div>

<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <label for="file" class="br_dropzone layo2">
      <input type="file" id="file" accept=".xlsx" name="excel[]" onchange="if(this.files.length == 1){this.form.fileName.value = this.files[0].name;}if(this.files.length == 2){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name}if(this.files.length == 3){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name+'-'+this.files[2].name}if(this.files.length == 4){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name+'-'+this.files[2].name+'-'+this.files[3].name}if(this.files.length == 5){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name+'-'+this.files[2].name+'-'+this.files[3].name+'-'+this.files[4].name} if(this.files.length > 5){this.form.fileName.value = 'No puedes subir mas de 5 archivos'}" required multiple>
      <input type="text" id="fileName" name="fileName" placeholder="Arrastra tu archivo aquí"  readonly>
    </label>
  </div>
</div>
<br />
<center>
  <button type="submit" name="cargarArchivo" class="btn btn-primary ">&nbsp;Integrar&nbsp;</button>

</center>




</form>
@endsection

<!-- MODALES DE CONTROL PARA EL USUARIO -->
<!-- Modal -->
<div class="modal fade" id="modal-falta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/signo2.png')}}">
        <h3><strong>Archivo Incompleto</strong></h3>
        <p>¡Ups!, no hemos podido encontrar una columna. Asegurese de que todas las columnas estén bien escritas o se hayan incluido en el archivo según el LayOut elegido.</p>
        <p>
        <div id="mens"></div>
        </p>
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
<div class="modal fade" id="modal-exito" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/singo1.png')}}">
          <h3><strong>Exito</strong></h3>
          <p>Se han subido tus datos correctamente.</p>
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
<div class="modal fade" id="modal-no-carga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/signo2.png')}}">
        <h3><strong>Error</strong></h3>
        <p>No hay nada que enviar.</p>
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
<div class="modal fade" id="modal-no-archivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/signo2.png')}}">
        <h3><strong>Error</strong></h3>
        <p>No hay ningun archivo para cargar.</p>
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
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/signo2.png')}}">
        <h3><strong>Error</strong></h3>
        <p>Hubo un error al subir tus datos.</p>
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
      <div class="modal-body text-center">
        <h3><strong>Cargando</strong></h3>
        <img src="{{asset('assets/img/cargando-loading-039.gif')}}" width="500">
        <p>Espera un momento. Se están cargando tus archivos.</p>
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
<!-- Fin modal -->

<!-- Modal -->
<div class="modal fade" id="modal-errorintegracion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/signo2.png')}}">
        <h3><strong>Error</strong></h3>
        <p>Lo sentimos, los datos no pudieron hacer match o el <b>clearing document ya ha sido integrado anteriormente</b>.</p>
        <p>Por favor intentelo con otro archivo que no se haya integrado anteriormente</p>
        <p>O revise que los <b>campos dtipo DZ en ambas hojas del excel sean del mismo tipo de moneda</b></p>
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

<!-- Modal -->
<div class="modal fade" id="modal-exitoCasiFinal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <h3 class="modal-title text-center" style="padding-top: 10px;"><strong>Exito</strong></h3>
      <hr class="underline" />
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/singo1.png')}}">
        <p>Se han subido tus datos correctamente.</p>
        <p>Por favor, selecciona el número de decimales que se <br /> incluirán en el txt, en los datos de montos</p>
        <br />
        <form id="info" action="javascript:generarElTxt();" method="post">
          {{csrf_field()}}

          <select class="form-control col-md-11" id="decimales" name="decimales">
            <!-- <option selected>Catálogo c_MetodoPago</option> -->
            <option value="2" selected>2 decimales</option>
            <option value="3">3 decimales</option>
            <option value="4">4 decimales</option>
            <option value="5">5 decimales</option>
            <option value="6">6 decimales</option>
            <option value="7">7 decimales</option>
            <option value="8">8 decimales</option>
            <option value="9">9 decimales</option>
            <option value="10">10 decimales</option>
          </select>
          <p >&nbsp;</p>
        
            <button type="submit" class="btn btn-primary">Generar txt.</button>
         
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-errortxt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/img/signo2.png')}}">
        <h3><strong>Error</strong></h3>
        <p>Hubo un error al crear los archivos.</p>
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
<!-- Fin modal -->

<script type="text/javascript">
  var may = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
  var min = "abcdefghijklmnñopqrstuvwxyz";
  var num = "1234567890.,^`+-*/_=¨´~{}[]:;$%&()#@";
  var rfccaracter = ".,^`+-*/_=¨´~{}[]:;$%&()#@ ";
  var letras = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
  var cantidad = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ^`+-*/_=¨´~{}[]:;$%&()#@";
  var incidencias = false;

  function cargarExcelEspecial() {
    console.log("Si entra a la función")
    var form = new FormData(document.getElementById('formulario'));
    console.log("id de layout" + $("#layout").val())
    console.log("id de método de pago" + $("#MetodoDePagoDR").val())
    console.log("id de forma de pago" + $("#FormatoDePagoP").val())
    var reporte = "";
    if ($("#layout").val() != "") {
      //console.log("El valor de archivo es " + $("#file").val())
      if ($("#file").val() != null && $("#file").val() != "") {
        $("#archivo1").val($("#file").val());
        $("#archivo2").val($("#file").val());
        $.ajax({
          url: 'cargarSAP2',
          type: 'post',
          data: form,
          processData: false,
          contentType: false,
          beforeSend: function() {
            $("#modal-cargando").modal('show');
            $("#subir").attr("disabled", true);
            $('#cuerpo').html("");
          },
          success: function(data) {
            console.log(data)
            //console.log(data.mensaje.FOLIO)
            if (data.respuesta == 1) {
              //console.log("Si se guardaron los datos chido");
              guardarTablasChidas();
            } else {
              $("#modal-cargando").modal('hide');
              console.log("No se guardan los datos del excel "+data.mensaje)
              $("#modal-falta").modal('show');
            }
            // $("#modal-cargando").modal('hide');
            // $("#subir").attr("disabled", false);
          },
          error: function() {
            console.log("Error al guardar la prueba")
            $("#modal-cargando").modal('hide');
            $("#modal-falta").modal('show');
            $("#subir").attr("disabled", false);
          }
        });
      } else {
        $("#modal-no-archivo").modal("show");
      }
    } else {
      $("#modal-cargando").modal('hide');
      $("#modal-layout").modal("show");
      $("#subir").attr("disabled", false);
    }
  }

  function guardarTablasChidas() {
    console.log("Entra en la segunda función")
    var form = new FormData(document.getElementById('formulario'));
    var contenido = "";
    if (incidencias == true) {
      $("#modal-verifica").modal("show");
      incidencias = false;
    } else {
      $.ajax({
        url: 'guardarSAPEsp',
        type: 'post',
        data: form,
        processData: false,
        contentType: false,
        beforeSend: function() {
          $("#modal-cargando").modal('show');
        },
        success: function(data) {
          console.log(data)
          if (data.respuesta == "2" || data.respuesta == 2) {
            $("#modal-cargando").modal('hide');
            $("#modal-no-carga").modal("show");
            console.log("que es: " + data.quees);
            console.log("registros: " + data.sihay);
            // console.log("actaliza tabla creditlocaldr: " + data.unoup);
            // console.log("actaliza tabla facturas_liquidadas: " + data.dosup);
            // console.log("actaliza tabla parcialidades: " + data.tresup);
            // console.log("actaliza tabla factura: " + data.cuatroup);
            // console.log("actaliza tabla parcialidades: " + data.cincoup);
            // console.log("actaliza tabla parcialidades con diferente moneda: " + data.seisup);
            // console.log("actaliza tabla Bancos_Sap: " + data.sieteup);
            // console.log("actaliza tabla Excel_Sap: " + data.ochoup);
            console.log("obtiene el correo de quien se logueo: " + data.user);
            console.log("mensaje: " + data.mensajito);
          } else {
            $("#modal-cargando").modal('show');
            // $("#modal-exito").modal("show");
            console.log("registros: " + data.sihay);
            // console.log("se elimino la tabla temporal_SAP: " + data.elimina1);
            // console.log("actaliza tabla creditlocaldr: " + data.unoup);
            // console.log("actaliza tabla facturas_liquidadas: " + data.dosup);
            // console.log("actaliza tabla parcialidades: " + data.tresup);
            // console.log("actaliza tabla factura: " + data.cuatroup);
            // console.log("actaliza tabla parcialidades: " + data.cincoup);
            // console.log("actaliza tabla parcialidades con diferente moneda: " + data.seisup);
            // console.log("actaliza tabla Bancos_Sap: " + data.sieteup);
            // console.log("actaliza tabla Excel_Sap: " + data.ochoup);
            // console.log("obtiene el correo de quien se logueo: " + data.user);
            // console.log("mensaje: " + data.mensajito);
            // console.log("id de excel ")
            integrar();
          }
        },
        error: function() {
          $("#modal-cargando").modal('hide');
          $("#modal-error").modal("show");
          console.log("Error al guardar la prueba posible error en el archivo ValidacionSapcontroler@guardarDatosEsp");
          //console.log("no se conecta a la base de datos puede ser los drivers");
        }
      });
    }
  }

  function integrar() {
    //window.location.href = "integracion";
    var form = new FormData(document.getElementById('formulario'));
    // console.log("id de layout" + $("#layout").val())
    // console.log("id de método de pago" + $("#MetodoDePagoDR").val())
    // console.log("id de forma de pago" + $("#FormatoDePagoP").val())
    $.ajax({
      url: 'integrandoEsp',
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $("#modal-cargando").modal('show');
      },
      success: function(data) {
        $("#modal-cargando").modal('hide');
        if (data.respuesta == "1" || data.respuesta == 1) {
          if (data.numero == 0 || data.numero == "0") {
            $("#modal-errorintegracion").modal("show");
          } else {
            $("#modal-exitoCasiFinal").modal("show");
          }
        } else {
          $("#modal-errorintegracion").modal("show");
        }
        // window.location.href = "integracion";
        console.log(data);
      },
      error: function() {
        $("#modal-cargando").modal('hide');
        $("#modal-error").modal('show');
      }
    });
  }

  function generarElTxt() {
    $("#modal-exitoCasiFinal").modal('hide');
    console.log("Si detecta la función de creación de txt");
    var form = new FormData(document.getElementById('formulario'));
    form.append("decimales", $("#info").serialize());
    $.ajax({
      url: 'generarTxtCorrectosEsp',
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $("#modal-cargando").modal('show');
      },
      success: function(data) {
        // $("#modal-cargando").modal('hide');
        if (data.respuesta == "si") {
          finalizarProceso();
        } else {
          $("#modal-cargando").modal('hide');
          $("#modal-errortxt").modal("show");
        }
        // window.location.href = "integracion";
        console.log(data);
      },
      error: function() {
        $("#modal-cargando").modal('hide');
        $("#modal-errortxt").modal('show');
      }
    });
  }
  function finalizarProceso() {
    var formulario = new FormData(document.getElementById("formulario"));
    $.ajax({
      url: "finalizarProceso",
      type: "post",
      data: formulario,
      processData: false,
      contentType: false,
      success: function(data){
        $("#modal-cargando").modal('hide');
        $("#modal-exito").modal("show");
      },
      error: function() {
        $("#modal-cargando").modal('hide');
        $("#modal-errortxt").modal('show');
      }
    })
  }
</script>