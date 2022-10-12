<?php if(Session::get('tipo') != 1): ?>
<?php $__env->startSection('title','Validacion de Archivos Tesorería'); ?>
<?php $__env->startSection('usuario','Usuario Tesorería'); ?>
<?php $__env->startSection('validacionMenu','no-mostrar'); ?>
<?php $__env->startSection('sapMenu','no-mostrar'); ?>
<?php $__env->startSection('creditoMenu','no-mostrar'); ?>
<?php $__env->startSection('concentradoMenu','no-mostrar'); ?>
<?php $__env->startSection('clientesMenu','no-mostrar'); ?>
<?php $__env->startSection('covestroMenu','no-mostrar'); ?>
<?php else: ?>
<?php $__env->startSection('title','Usuario Administrador'); ?>
<?php endif; ?>
<?php $__env->startSection('tesoreriaMenu','tesoreria-active'); ?>
<?php $__env->startSection('contenido'); ?>
<!--Encabezado-->
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <h2 class="text-left leadv"><i class="icon-cloud-upload"></i>&nbsp;&nbsp;Validación de archivos</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 container-header">
    <p class="text-muted lead text-left"><big>Tesorería</big> <img href="" src="<?php echo e(asset('assets/img/currency.svg')); ?>" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<!-- Fin encabezado -->
<!-- Seleccion de layout -->
<div class="row"><!-- Seleccion de layout-->
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <form id="formulario" action="javascript:cargarTabla()" method="post">
      <?php echo e(csrf_field()); ?>

      <div class="form-group">
        <div class="col-6 col-xs-6 col-sm-6 col-md-3 col-lg-2">
          <label for="layout" class="text-muted control-label layout-select"><small>Selecciona un Layout:</small></label>
        </div>
        <div class="col-6 col-xs-6 col-sm-6 col-md-2 col-lg-2">
          <select id="layout" name="layout" class="form-control layo">
            <?php $__currentLoopData = $layout; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
              <option value="<?php echo e($l->id_lt); ?>"><?php echo e($l->nombre); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
          </select>
        </div>
        <!--div class="col-12 col-xs-12 col-sm-12 col-md-2 col-lg-2 col-md-offset-5 col-lg-offset-6">
          <button type="layout" name="layout" class="button btn-orange"><i class="far fa-file"></i> <small>Layout</small></button>
        </div-->
      </div>
  </div>
</div>
<br>
<!-- Fin de seleccion de layout -->

<!--form id="formulario" action="javascript:cargarTabla()" method="post"-->
<!-- Drop area -->
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">


      <label for="file" class="br_dropzone layo2">
        <input type="file" id="file" name="excel[]" onchange="if(this.files.length == 1){this.form.fileName.value = this.files[0].name;}if(this.files.length == 2){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name}if(this.files.length == 3){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name+'-'+this.files[2].name}if(this.files.length == 4){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name+'-'+this.files[2].name+'-'+this.files[3].name}if(this.files.length == 5){this.form.fileName.value = this.files[0].name+'-'+this.files[1].name+'-'+this.files[2].name+'-'+this.files[3].name+'-'+this.files[4].name} if(this.files.length > 5){this.form.fileName.value = 'No puedes subir mas de 5 archivos'}" required multiple>
        <input type="text" id="fileName" name="fileName" placeholder="Arrastra tu archivo aqu&iacute;" readonly>
      </label>


  </div>
</div>
<!-- fin drop area -->
<div class="col-12 col-xs-12 col-sm-12 col-md-1 col-lg-1 col-md-offset-5 col-lg-offset-5">
  <button type="submit" name="cargarArchivo" class="button btn-blue"><small>Subir</small></button>
</div>

</form>
<!-- fin drop area -->
<!-- Resultados de validacion texto -->
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 container-header">
    <p class="text-muted lead text-left">Resultados de validación <img href="" src="<?php echo e(asset('assets/img/currency.svg')); ?>" class="icon-header"></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>
<!-- Fin resultado de validacion texto -->

<!-- Tabla de resultados -->
<div class="row" id="contenedor">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollbar" id="scroll">
    <table class="display table table-bordered table-hover space table-striped" id="tableTesoreria">
      <thead>
        <tr>
          <th class="text-muted text-center txt-cabecera" colspan="7"><small>Campos obligatorios</small></th>
          <th class="text-muted text-center txt-cabecera" colspan="4"><small>Campos opcionales</small></th>
        </tr>
        <tr>
          <th class="text-center text-muted txt-thead"><small>Fecha de pago</small></th>
          <th class="text-center text-muted txt-thead"><small>Forma de pago</small></th>
          <th class="text-center text-muted txt-thead"><small>Moneda de pago</small></th>
          <th class="text-center text-muted txt-thead"><small>Monto a pagar</small></th>
          <th class="text-center text-muted txt-thead"><small>Número de Operación</small></th>
          <th class="text-center text-muted txt-thead"><small>RFC de la cuenta del receptor</small></th>
          <th class="text-center text-muted txt-thead"><small>Número de cuenta de receptor</small></th>
          <th class="text-center text-muted txt-thead"><small>RFC del Cliente</small></th>
          <th class="text-center text-muted txt-thead"><small>RFC del banco del cliente</small></th>
          <th class="text-center text-muted txt-thead"><small>Banco del pago</small></th>
          <th class="text-center text-muted txt-thead"><small>Número de cuenta</small></th>
        </tr>
      </thead>
      <tbody id="cuerpo">
        <tr>
          <td class="text-center text-muted txt-tbody" name="FECHAPAG"><small></small></td>
          <td class="text-center text-muted txt-tbody" name="FORMAP"><small></small></td>
          <td class="text-center text-muted txt-tbody" name="MONEDAP"><small></small></td>
          <td class="text-center text-muted txt-tbody" name="MONTOP"><small></small></td>
          <td class="text-center text-muted txt-tbody" name="NUMEROPERP"><small></small></td>
          <td class="text-center text-muted txt-tbody" name="RFCCTABEN"><small></small></td>
          <td class="text-center text-muted txt-tbody" name="CATABEN"><small></small></td>
          <td class="text-center text-muted txt-tbody" name="RFCC"><small></small></td>
          <td class="text-center text-muted txt-tbody" name="RFCCTAORD"><small></small></td>
          <td class="text-center text-muted txt-tbody" name="BANCOORDEXT"><small></small></td>
          <td class="text-center text-muted txt-tbody" name="CTAORD"><small></small></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<br>
<!-- Fin de tabla de resultados -->


<!-- Boton Enviar -->
<div class="col-4 col-sm-4 col-md-2 col-lg-2 col-md-offset-3 col-lg-offset-3">
<form action="javascript:guardarDatosVerificar();" method="post" id="cosas2"><!--Este es tu form solo descomenta-->
  <?php echo e(csrf_field()); ?>

  <input type="hidden" name="nombre_excel" id="archivo1" value="">
  <button type="submit" class="btn btn-green"><small>Enviar a integración</small></button>
</form>
</div>
<!-- Fin boton enviar -->

<!-- Boton volver a cargar -->
<div class="col-4 col-sm-4 col-md-2 col-lg-2 ">
  <form action="javascript:eliminarDatos();" method="post" id="cosas">
    <!--form action="javascript:guardarDatosVerificar();" method="post" id="cosas2"--><!--Este es tu form solo descomenta-->
    <?php echo e(csrf_field()); ?>

    <button type="submit" class="btn btn-pink"><small>Volver a cargar</small></button>

  </form>
</div>

<!-- fin boton volver a cargar -->
</form>
<?php $__env->stopSection(); ?>
  <!-- Modal -->
  <div class="modal fade" id="modal-falta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <center>
            <img src="<?php echo e(asset('assets/img/signo2.png')); ?>">
            <h3><strong>Archivo Incompleto</strong></h3>
            <p>¡Ups!, no hemos podido encontrar una columna. Asegurese de que todas las columnas estén bien escritas o se hayan incluido en el archivo según el LayOut elegido.</p>
            <p><div id="mens"></div></p>
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
  <div class="modal fade" id="modal-verifica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <center>
            <img src="<?php echo e(asset('assets/img/signo.png')); ?>">
            <h3><strong>Datos incorrectos</strong></h3>
            <p>Algunos datos que ha cargado son incorrectos. ¿Desea de todos modos enviarlos a integración de archivos? Es probable que esto genere un error.</p>
          </center>
        </div>
        <div class="modal-footer">
          <div class="row">
            <div class="col-xs-5" align="right">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
            <div class="col-xs-7" align="left">
              <form id="cosas3" action="javascript:guardarDatos();">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="nombre_excel" id="archivo2" value="">
                <button type="submit" class="btn btn-primary">Si, enviar de todos modos.</button>
              </form>
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
            <img src="<?php echo e(asset('assets/img/singo1.png')); ?>">
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
        <div class="modal-body">
          <center>
            <img src="<?php echo e(asset('assets/img/signo2.png')); ?>">
            <h3><strong>Error</strong></h3>
            <p>No hay nada que enviar.</p>
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
  <div class="modal fade" id="modal-no-archivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <center>
            <img src="<?php echo e(asset('assets/img/signo2.png')); ?>">
            <h3><strong>Error</strong></h3>
            <p>No hay ningun archivo para cargar.</p>
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
            <img src="<?php echo e(asset('assets/img/signo2.png')); ?>">
            <h3><strong>Error</strong></h3>
            <p>Hubo un error al subir tus datos.</p>
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
  <!-- Fin modal -->
  <!-- Modal -->
  <div class="modal fade" id="modal-cargando" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <center>
            <h3><strong>Cargando</strong></h3>
            <img src="<?php echo e(asset('assets/img/cargando-loading-039.gif')); ?>" width="500">
            <p>Espera un momento. Se están cargando tus archivos.</p>
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
<!-- Fin modal -->

<!-- Scripts -->
  <script type="text/javascript" src="<?php echo e(asset('assets/js/dropmenu.js')); ?>"></script>
  <script type="text/javascript">
    var may = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
    var min = "abcdefghijklmnñopqrstuvwxyz";
    var num = "1234567890.,^`+-*/_=¨´~{}[]:;$%&()#@ ";
    var alfanum = ".,^`+-*/_=¨´~{}[]:;$%&()#@ "
    var rfccaracter = ".,^`+-*/_=¨´~{}[]:;$%&()#@ ";
    var letras = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ^`+-*/_=¨´~{}[]:;$%&()#@., ";
    var cantidad = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ^`+-*/_=¨´~{}[]:;%&()#@";
    var numeros = "1234567890";
    var incidencias = false;

    function cargarTabla(){
      var form = new FormData(document.getElementById('formulario'));
      var contenido = '<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 scrollbar" id="scroll">'+
                '<table class="display table table-bordered table-hover space table-striped" id="tableTesoreria2">'+
                  '<thead>'+
                    '<tr>'+
                      '<th class="text-center text-muted txt-thead"><small>Fecha de pago</small></th>'+
                      '<th class="text-center text-muted txt-thead"><small>Forma de pago</small></th>'+
                      '<th class="text-center text-muted txt-thead"><small>Moneda de pago</small></th>'+
                      '<th class="text-center text-muted txt-thead"><small>Monto a pagar</small></th>'+
                      '<th class="text-center text-muted txt-thead"><small>Número de Operación</small></th>'+
                      '<th class="text-center text-muted txt-thead"><small>RFC de la cuenta del receptor</small></th>'+
                      '<th class="text-center text-muted txt-thead"><small>Número de cuenta de receptor</small></th>'+
                      '<th class="text-center text-muted txt-thead"><small>RFC del Cliente</small></th>'+
                      '<th class="text-center text-muted txt-thead"><small>RFC del banco del cliente</small></th>'+
                      '<th class="text-center text-muted txt-thead"><small>Banco del pago</small></th>'+
                      '<th class="text-center text-muted txt-thead"><small>Número de cuenta</small></th>'+
                      '<th class="text-center text-muted txt-thead"><small>Errores</small></th>'+
                    '</tr>'+
                  '</thead>'+
                  '<tbody id="cuerpo">';
      var incidentes = "";
      var reporte = "";
      var inci = false;
      if($("#layout").val() != ""){
        console.log("El valor de archivo es "+$("#file").val())
        if($("#file").val() != null && $("#file").val() != ""){
          $("#archivo1").val($("#file").val());
          $("#archivo2").val($("#file").val());
          $.ajax({
          url: 'cargarTesoreria',
          type: 'post',
          data: form,
          processData: false,
          contentType: false,
          beforeSend: function(){
            $("#modal-cargando").modal('show');
            $("#subir").attr("disabled", true);
            $('#cuerpo').html("");
          },
          success: function(data){
            document.getElementById("contenedor").removeChild(document.getElementById("scroll"))
            if(data.length < 1){
              console.log("No habia datos en tu archivo de excel.");
            }
            else{
              if(data.respuesta == 2){
                dm = data.mensaje;
                cod_error = dm.split(":");
                $("#mens").html("");
                if(cod_error[0] == "Undefined index"){
                  $("#mens").html("No se reconoce la columna "+cod_error[1]+" en las hojas de tu archivo de excel con nombre "+data.archivo+".");
                }
                else{
                  $("#mens").html(data.mensaje+". Este error se muestra en el archivo "+data.archivo+".");
                }
                $("#modal-cargando").modal('hide');
                $("#modal-falta").modal('show');
              }
              else{
                
              for(var i=0; i<data.length; i++){
                reporte = "";
                inci = false;
                contenido+='<tr>';
                if(data[i].FECHAPAG != null && data[i].FECHAPAG != "" && esFecha(data[i].FECHAPAG) == true){
                  contenido+='<td class="text-center text-muted txt-tbody">'+data[i].FECHAPAG+'</td>';
                }
                else{
                  console.log("Tiene de longitud "+data[i].FECHAPAG)
                  if(data[i].FECHAPAG != null && data[i].FECHAPAG != ""){
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">'+data[i].FECHAPAG+'</td>';
                    incidencias = true;
                    inci = true;
                  }
                  else{
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;"></td>';
                    incidencias = true;
                    inci = true;
                  }
                  reporte +="-> La fecha de Pago NO puede estar vacía y debe seguir un formato correspondiente<br>";
                  incidencias = true;
                }
                if(data[i].FORMAP != null && (data[i].FORMAP.length == 2 || data[i].FORMAP.length == 1) && soloNumeros(data[i].FORMAP) == false){
                  contenido+='<td class="text-center text-muted txt-tbody">'+data[i].FORMAP+'</td>';
                }
                else{
                  if(data[i].FORMAP != null && data[i].FORMAP !=  ""){
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">'+data[i].FORMAP+'</td>';
                    reporte +="-> La forma de Pago NO puede estar vacía. Debe ser un número de 1 o 2 dígitos.<br>";
                    incidencias = true;
                    inci = true;
                  }
                  else{
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;"></td>';
                    reporte +="-> La forma de Pago NO puede estar vacía. Debe ser un número de 1 o 2 dígitos.<br>";
                    incidencias = true;
                    inci = true;
                  }
                }
                if(data[i].MONEDAP != null && data[i].MONEDAP.length == 3 && soloLetras(data[i].MONEDAP) == false){
                  contenido+='<td class="text-center text-muted txt-tbody">'+data[i].MONEDAP+'</td>';
                }
                else{
                  if(data[i].MONEDAP != null && data[i].MONEDAP != ""){
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">'+data[i].MONEDAP+'</td>';
                    incidencias = true;
                    inci = true;
                  }
                  else{
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;"></td>';
                    incidencias = true;
                    inci = true;
                  }
                  reporte +="-> La moneda de Pago NO puede estar vacía, debe ser una palabra de 3 letras.<br>";
                  incidencias = true;
                }
                if(data[i].MONTOP != null && monto(data[i].MONTOP) == false){
                  contenido+='<td class="text-center text-muted txt-tbody">'+data[i].MONTOP+'</td>';
                }
                else{
                  if(data[i].MONTOP != null && data[i].MONTOP != ""){
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">'+data[i].MONTOP+'</td>';
                    incidencias = true;
                    inci = true;
                  }
                  else{
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;"></td>';
                    incidencias = true;
                    inci = true;
                  }
                  reporte +="-> El monto de pago NO puede estar vacío. Debe ser solo un número, con o sin decimales (17.63, 17.00, 17)<br>";
                  incidencias = true;
                }
                if(data[i].NUMEROPERP != null && esAlfanumerico(data[i].NUMEROPERP) == false){
                  contenido+='<td class="text-center text-muted txt-tbody">'+data[i].NUMEROPERP+'</td>';
                }
                else{
                  if(data[i].NUMEROPERP != null && data[i].NUMEROPERP != ""){
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">'+data[i].NUMEROPERP+'</td>';
                    incidencias = true;
                    inci = true;
                  }
                  else{
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;"></td>';
                    incidencias = true;
                    inci = true;
                  }
                }
                if(data[i].RFCCTABEN != null && data[i].RFCCTABEN.length == 12 && esRFC(data[i].RFCCTABEN) == false){
                  contenido+='<td class="text-center text-muted txt-tbody">'+data[i].RFCCTABEN+'</td>';
                }
                else{
                  if(data[i].RFCCTABEN != null && data[i].RFCCTABEN != ""){
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">'+data[i].RFCCTABEN+'</td>';
                    reporte +="-> El RFC de la cuenta del receptor(beneficiario) no puede quedar vacío<br>";
                    incidencias = true;
                  }
                  else{
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;"></td>';
                    reporte +="-> El RFC de la cuenta del receptor(beneficiario) debe contener 12 caracteres<br>";
                    incidencias = true;
                  }
                }
                if(data[i].CATABEN != null && soloNumeros(data[i].CATABEN) == false){
                  contenido+='<td class="text-center text-muted txt-tbody">'+data[i].CATABEN+'</td>';
                }
                else{
                  if(data[i].CATABEN != null && data[i].CATABEN != ""){
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">'+data[i].CATABEN+'</td>';
                  }
                  else{
                    contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;"></td>';
                  }
                  reporte +="-> La cuenta del beneficiario NO puede estar vacía, debe ser una cadena de solo números<br>";
                  incidencias = true;
                  inci = true;
                }
                if(data[i].RFC_R == null || (data[i].RFC_R.length == 12 && esRFC(data[i].RFC_R) == false)){
                  if(data[i].RFC_R != null && data[i].RFC_R  != ""){
                    contenido+='<td class="text-center text-muted txt-tbody">'+data[i].RFC_R+'</td>';
                  }
                  else{
                    contenido+='<td class="text-center text-muted txt-tbody"></td>';
                  }
                }
                else{
                  contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">'+data[i].RFC_R+'</td>';
                  reporte +="-> El RFC del Cliente debe tener 12 caracteres alfanuméricos<br>";
                  incidencias = true;
                  inci = true;
                }
                if(data[i].RFCCTAORD == null || (data[i].RFCCTAORD.length == 12 && esRFC(data[i].RFCCTAORD) == false)){
                  if(data[i].RFCCTAORD != null && data[i].RFCCTAORD != ""){
                    contenido+='<td class="text-center text-muted txt-tbody">'+data[i].RFCCTAORD+'</td>';
                  }
                  else{
                    contenido+='<td class="text-center text-muted txt-tbody"></td>';
                  }

                }
                else{
                  contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">'+data[i].RFCCTAORD+'</td>';
                  reporte +="-> El RFC del banco de la cuenta del ordenante debe tener 12 caracteres alfanuméricos<br>";
                  incidencias = true;
                  inci = true;
                }
                if(data[i].BANCOORDEXT != null && data[i].BANCOORDEXT != ""){
                    contenido+='<td class="text-center text-muted txt-tbody">'+data[i].BANCOORDEXT+'</td>';
                  }
                  else{
                    contenido+='<td class="text-center text-muted txt-tbody"></td>';
                  }

                if(data[i].CTAORD == null || soloNumeros(data[i].CTAORD) == false){
                  if(data[i].CTAORD != null && data[i].CTAORD != ""){
                    contenido+='<td class="text-center text-muted txt-tbody">'+data[i].CTAORD+'</td>';
                  }
                  else{
                    contenido+='<td class="text-center text-muted txt-tbody"></td>';
                  }
                }
                else{
                  contenido+='<td class="text-center text-muted txt-tbody" style="background-color: #FF0000; color: #FFF;">'+data[i].CTAORD+'</td>';
                  reporte +="-> Debe ser una cadena de solo números<br>";
                  incidencias = true;
                  inci = true;
                }
                contenido+='<td class="text-center text-muted txt-tbody">'+reporte+'</td>';
                contenido+='</tr>';
                if(inci == true){
                  incidentes+='<input type="hidden" name="teso'+data[i].id_tt+'" value="1"><br>'
                }
                else{
                  incidentes+='<input type="hidden" name="teso'+data[i].id_tt+'" value="0"><br>'
                }

              }
              contenido+='</tbody></table>'+
              '<script type="text/javascript">'+
                    '$("#tableTesoreria2").dataTable({'+
                      '"bDestroy": true,'+
                      '"ordering": false,'+
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
                    '});'+
                  '<\/script>'+
              '</div>';


                $('#contenedor').append(contenido);

                /*datableble = $('#tableTesoreria').dataTable({
                  "destroy":true,
                  language:{
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                  }
                });*/
              }
            }
            $("#modal-cargando").modal('hide');
            $("#subir").attr("disabled", false);
          },
          error: function(){
            console.log("Error al guardar la prueba")
            $("#modal-cargando").modal('hide');
            $("#modal-falta").modal('show');
          }
        });
        }
        else{
          $("#modal-no-archivo").modal("show");
        }
      }
      else{
        $("#modal-cargando").modal('hide');
        $("#modal-layout").modal("show");
        $("#subir").attr("disabled", false);
      }
    }

    function eliminarDatos(){
      var form = new FormData(document.getElementById('cosas'));
      var contenido = "";
      $.ajax({
        url: 'recargarTesoreria',
        type: 'post',
        data: form,
        processData: false,
        contentType: false,
        success: function(data){
          contenido+='<td class="text-center text-muted" name="FRCCTAORD"></td>'+
                  '<td class="text-center text-muted" name="BANCOORDEXT"></td>'+
                  '<td class="text-center text-muted" name="CTAORD"></td>'+
                  '<td class="text-center text-muted" name="FORMAP"></td>'+
                  '<td class="text-center text-muted" name="MONEDAP"></td>'+
                  '<td class="text-center text-muted" name="MONTOP"></td>'+
                  '<td class="text-center text-muted" name="NUMEROPERP"></td>'+
                  '<td class="text-center text-muted" name="FECHAPAG"></td>'+
                  '<td class="text-center text-muted" name="RFCCTABEN"></td>'+
                  '<td class="text-center text-muted" name="CATABEN"></td>'+
                  '<td class="text-center text-muted" name="RFCCTABEN"></td>'+
                  '<td class="text-center text-muted" name="CATABEN"></td>';

          $('#cuerpo').html("");
          $('#cuerpo').append(contenido);
          $('#file').val("");
          incidencias = false;
          $('#fileName').val("Arrastra tu archivo aquí")
        },
        error: function(){
          console.log("Error al guardar la prueba")
        }
      });
    }

    function guardarDatosVerificar(){
      var form = new FormData(document.getElementById('cosas2'));
      var contenido = "";
      if(incidencias == true){
        $("#modal-verifica").modal("show");
        incidencias = false;
      }
      else{
        $.ajax({
          url: 'guardarTesoreria',
          type: 'post',
          data: form,
          processData: false,
          contentType: false,
          beforeSend: function(){
              $("#modal-cargando").modal('show');
            },
          success: function(data){
            contenido+='<td class="text-center text-muted" name="FRCCTAORD"></td>'+
                    '<td class="text-center text-muted" name="BANCOORDEXT"></td>'+
                    '<td class="text-center text-muted" name="CTAORD"></td>'+
                    '<td class="text-center text-muted" name="FORMAP"></td>'+
                    '<td class="text-center text-muted" name="MONEDAP"></td>'+
                    '<td class="text-center text-muted" name="MONTOP"></td>'+
                    '<td class="text-center text-muted" name="NUMEROPERP"></td>'+
                    '<td class="text-center text-muted" name="FECHAPAG"></td>'+
                    '<td class="text-center text-muted" name="RFCCTABEN"></td>'+
                    '<td class="text-center text-muted" name="CATABEN"></td>'+
                    '<td class="text-center text-muted" name="RFCCTABEN"></td>'+
                    '<td class="text-center text-muted" name="CATABEN"></td>';

            $('#cuerpo').html("");
            $('#cuerpo').append(contenido);
            $("#modal-cargando").modal("hide");
            if(data.respuesta == "2" || data.respuesta == 2){
              $("#modal-no-carga").modal("show");
            }
            else{
              $("#modal-exito").modal("show");
            }
          },
          error: function(){
            $("#modal-verifica").modal("hide");
            $("#modal-error").modal("show");
            console.log("Error al guardar la prueba")
          }
        });
      }
    }

    function guardarDatos(){
      var form = new FormData(document.getElementById('cosas2'));
      var contenido = "";
      $.ajax({
        url: 'guardarTesoreria',
        type: 'post',
        data: form,
        processData: false,
        contentType: false,
        beforeSend: function(){
              $("#modal-verifica").modal("hide");
              $("#modal-cargando").modal('show');
            },
        success: function(data){
          contenido+='<td class="text-center text-muted" name="FRCCTAORD"></td>'+
                  '<td class="text-center text-muted" name="BANCOORDEXT"></td>'+
                  '<td class="text-center text-muted" name="CTAORD"></td>'+
                  '<td class="text-center text-muted" name="FORMAP"></td>'+
                  '<td class="text-center text-muted" name="MONEDAP"></td>'+
                  '<td class="text-center text-muted" name="MONTOP"></td>'+
                  '<td class="text-center text-muted" name="NUMEROPERP"></td>'+
                  '<td class="text-center text-muted" name="FECHAPAG"></td>'+
                  '<td class="text-center text-muted" name="RFCCTABEN"></td>'+
                  '<td class="text-center text-muted" name="CATABEN"></td>'+
                  '<td class="text-center text-muted" name="RFCCTABEN"></td>'+
                  '<td class="text-center text-muted" name="CATABEN"></td>';

          $('#cuerpo').html("");
          $('#cuerpo').append(contenido);
          $("#modal-cargando").modal("hide");
          $("#modal-exito").modal("show");
          incidencias = false;
        },
        error: function(){
          console.log("Error al guardar la prueba")
          $("#modal-verifica").modal("hide");
          $("#modal-error").modal("show");
        }
      });
    }

    function soloNumeros(texto){
      console.log(texto);
      var bandera = false;
      if(texto != null){
        for(var i = 0; i<letras.length; i++){
          if(texto.indexOf(letras.charAt(i)) != -1){
            bandera = true;
          }
        }
      }

      return bandera;
    }

    function soloLetras(texto){
      console.log(texto);
      var bandera = false;
      if(texto != null){
        for(var i = 0; i<num.length; i++){
          if(texto.indexOf(num.charAt(i)) != -1){
            bandera = true;
          }
        }
      }

      return bandera;
    }

    function monto(texto){
      console.log(texto);
      var bandera = false;
      if(texto != null){
        for(var i = 0; i<cantidad.length; i++){
          if(texto.indexOf(cantidad.charAt(i)) != -1){
            bandera = true;
          }
        }
      }

      return bandera;
    }

    function esRFC(texto){
      var bandera = false;
      if(texto != null){
        for(var i = 0; i<rfccaracter.length; i++){
          if(texto.indexOf(rfccaracter.charAt(i)) != -1){
            bandera = true;
          }
        }
      }

      return bandera;
    }

    function esAlfanumerico(texto){
      var bandera = false;
      if(texto != null){
        for(var i = 0; i<alfanum.length; i++){
          if(texto.indexOf(alfanum.charAt(i)) != -1){
            bandera = true;
          }
        }
      }

      return bandera;
    }

    function esFecha(texto){
      var bandera = true;
      if(texto != null && texto != ""){
        if(texto.length == 23){
          console.log("La unción dice "+texto.length)
          for(var i = 0; i<texto.length; i++){
            switch(i){
              case 0:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                  console.log("Error en caracter "+i+", ya que no es numero")
                }
                break;

              case 1:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                  console.log("Error en caracter "+i+", ya que no es numero")
                }
                break;

              case 2:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                  console.log("Error en caracter "+i+", ya que no es numero")
                }
                break;

              case 3:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                  console.log("Error en caracter "+i+", ya que no es numero")
                }
                break;

              case 4:
                if(texto.charAt(i) != "/" && texto.charAt(i) != "-"){
                  bandera = false;
                  console.log("Error en caracter "+i+", ya que no es / o -")
                }
                break;

              case 5:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                  console.log("Error en caracter "+i+", ya que no es numero")
                }
                break;

              case 6:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                  console.log("Error en caracter "+i+", ya que no es numero")
                }
                break;

              case 7:
                if(texto.charAt(i) != "/" && texto.charAt(i) != "-"){
                  bandera = false;
                  console.log("Error en caracter "+i+", ya que no es / o -")
                }
                break;

              case 8:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                  console.log("Error en caracter "+i+", ya que no es numero")
                }
                break;

              case 9:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                  console.log("Error en caracter "+i+", ya que no es numero")
                }
                break;

              case 10:
                if(texto.charAt(i) != " "){
                  bandera = false;
                }
                break;

              case 11:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                }
                break;

              case 12:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                }
                break;

              case 13:
                if(texto.charAt(i) != ":"){
                  bandera = false;
                }
                break;

              case 14:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                }
                break;

              case 15:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                }
                break;

              case 16:
                if(texto.charAt(i) != ":"){
                  bandera = false;
                }
                break;

              case 17:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                }
                break;

              case 18:
                if(numeros.indexOf(texto.charAt(i)) == -1){
                  bandera = false;
                }
                break;
            }
          }
        }
        else{
          bandera = false;
        }
      }
      return bandera;
    }
  </script>
  <script type="text/javascript" src="<?php echo e(asset('assets/js/busqueda.js')); ?>"></script>
  <script type="text/javascript">
      //Tesoreria
      document.getElementById("tesoreria").onclick = function() {tesoreria()};
      function tesoreria() {
        document.getElementById("DropdownTesoreria").classList.toggle("show");
      }
      //Credito y cobranza
      document.getElementById("credito").onclick = function() {credito()};
      function credito(){
        document.getElementById("DropdownCredito").classList.toggle("show");
      }
      //SAP
      document.getElementById("sap").onclick = function() {sap()};
      function sap(){
        document.getElementById("DropdownSAP").classList.toggle("show");
      }
      $(document).ready(function() {

    // get the name of uploaded file
   $('input[type="file"]').change(function(){
    var value = $("input[type='file']").val();
     $('.js-value').text(value);
     });
    });

  </script>
  <!-- Funcion para cambio de atributo sidemenu -->
  <script type="text/javascript">
    window.addEventListener('load', icontesoreria, false);
    function icontesoreria() {
      var contenedorTesoreria = document.getElementById('tesoreria');
      contenedorTesoreria.addEventListener('mouseover', cambiarTesoreria, false);
      contenedorTesoreria.addEventListener('mouseout', restaurarTesoreria, false);
    }

    function restaurarTesoreria(){
      var imagen = document.getElementById('img-tesoreria').src = "<?php echo e(asset('assets/img/currency.svg')); ?>";
    }

    function cambiarTesoreria() {
      var imagen = document.getElementById('img-tesoreria').src = "<?php echo e(asset('assets/img/currency-white.svg')); ?>";
    }

  </script>
  <script type="text/javascript">
    window.addEventListener('load', iconcredito, false);

    function iconcredito(){
      var contenedorCredito = document.getElementById('credito');
      contenedorCredito.addEventListener('mouseover', cambiarCredito, false);
      contenedorCredito.addEventListener('mouseout', restaurarCredito, false);
    }

    function restaurarCredito(){
      var imagen = document.getElementById('img-credito').src = "<?php echo e(asset('assets/img/pay.svg')); ?>";
    }

    function cambiarCredito() {
      var imagen = document.getElementById('img-credito').src = "<?php echo e(asset('assets/img/pay-white.svg')); ?>";
    }
  </script>
  <script type="text/javascript">
    window.addEventListener('load', iconsap, false);

    function iconsap(){
      var contenedorSAP = document.getElementById('sap');
      contenedorSAP.addEventListener('mouseover', cambiarSAP, false);
      contenedorSAP.addEventListener('mouseout', restaurarSAP, false);
    }

    function restaurarSAP(){
      var imagen = document.getElementById('img-sap').src = "<?php echo e(asset('assets/img/bank.svg')); ?>";
    }

    function cambiarSAP() {
      var imagen = document.getElementById('img-sap').src = "<?php echo e(asset('assets/img/bank-white.svg')); ?>";
    }
  </script>
  <script type="text/javascript">
    window.addEventListener('load', iconcovestro, false);

    function iconcovestro(){
      var contenedorCovestro = document.getElementById('covestro');
      contenedorCovestro.addEventListener('mouseover', cambiarCovestro, false);
      contenedorCovestro.addEventListener('mouseout', restaurarCovestro, false);
    }

    function restaurarCovestro(){
      var imagen = document.getElementById('img-covestro').src = "<?php echo e(asset('assets/img/planning.svg')); ?>";
    }

    function cambiarCovestro() {
      var imagen = document.getElementById('img-covestro').src = "<?php echo e(asset('assets/img/planning-white.svg')); ?>";
    }
  </script>
  <script type="text/javascript">
      /*input file*/
      $(document).on('click', '.upload-field', function(){
        var file = $(this).parent().parent().parent().find('.input-file');
      file.trigger('click');
      });

      $(document).on('change', '.input-file', function(){
        $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
      });
  </script>
  <!-- Tablas Pagination -->



  <!-- Subir archivo -->
  <script type="text/javascript">

      var onDragEnter = function (event) {
        $(".br_dropzone").addClass("dragover");
      },

      onDragOver = function (event) {
        event.preventDefault();
        if (!$(".br_dropzone").hasClass("dragover"))
            $(".br_dropzone").addClass("dragover");
      },

      onDragLeave = function (event) {
        event.preventDefault();
        $(".br_dropzone").removeClass("dragover");
      },

      onDrop = function (event) {
        $(".br_dropzone").removeClass("dragover");
        $(".br_dropzone").addClass("dragdrop");
        console.log(event.originalEvent.dataTransfer.files);
      };

      $(".br_dropzone")
      .on("dragenter", onDragEnter)
      .on("dragover", onDragOver)
      .on("dragleave", onDragLeave)
      .on("drop", onDrop);
  </script>
</html>
<script type="text/javascript">
    window.addEventListener('load', iconcredito, false);

    function iconcredito(){
      var contenedorCredito = document.getElementById('credito');
      contenedorCredito.addEventListener('mouseover', cambiarCredito, false);
      contenedorCredito.addEventListener('mouseout', restaurarCredito, false);
    }

    function restaurarCredito(){
      var imagen = document.getElementById('img-credito').src = "<?php echo e(asset('assets/img/pay.svg')); ?>";
    }

    function cambiarCredito() {
      var imagen = document.getElementById('img-credito').src = "<?php echo e(asset('assets/img/pay-white.svg')); ?>";
    }
  </script>
  <script type="text/javascript">
    window.addEventListener('load', iconsap, false);

    function iconsap(){
      var contenedorSAP = document.getElementById('sap');
      contenedorSAP.addEventListener('mouseover', cambiarSAP, false);
      contenedorSAP.addEventListener('mouseout', restaurarSAP, false);
    }

    function restaurarSAP(){
      var imagen = document.getElementById('img-sap').src = "<?php echo e(asset('assets/img/bank.svg')); ?>";
    }

    function cambiarSAP() {
      var imagen = document.getElementById('img-sap').src = "<?php echo e(asset('assets/img/bank-white.svg')); ?>";
    }
  </script>
  <script type="text/javascript">
    window.addEventListener('load', iconcovestro, false);

    function iconcovestro(){
      var contenedorCovestro = document.getElementById('covestro');
      contenedorCovestro.addEventListener('mouseover', cambiarCovestro, false);
      contenedorCovestro.addEventListener('mouseout', restaurarCovestro, false);
    }

    function restaurarCovestro(){
      var imagen = document.getElementById('img-covestro').src = "<?php echo e(asset('assets/img/planning.svg')); ?>";
    }

    function cambiarCovestro() {
      var imagen = document.getElementById('img-covestro').src = "<?php echo e(asset('assets/img/planning-white.svg')); ?>";
    }
  </script>
  <script type="text/javascript">
      /*input file*/
      $(document).on('click', '.upload-field', function(){
        var file = $(this).parent().parent().parent().find('.input-file');
      file.trigger('click');
      });

      $(document).on('change', '.input-file', function(){
        $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
      });
  </script>
  <!-- Tablas Pagination -->



  <!-- Subir archivo -->
  <script type="text/javascript">

      var onDragEnter = function (event) {
        $(".br_dropzone").addClass("dragover");
      },

      onDragOver = function (event) {
        event.preventDefault();
        if (!$(".br_dropzone").hasClass("dragover"))
            $(".br_dropzone").addClass("dragover");
      },

      onDragLeave = function (event) {
        event.preventDefault();
        $(".br_dropzone").removeClass("dragover");
      },

      onDrop = function (event) {
        $(".br_dropzone").removeClass("dragover");
        $(".br_dropzone").addClass("dragdrop");
        console.log(event.originalEvent.dataTransfer.files);
      };

      $(".br_dropzone")
      .on("dragenter", onDragEnter)
      .on("dragover", onDragOver)
      .on("dragleave", onDragLeave)
      .on("drop", onDrop);
  </script>
  <script type="text/javascript">
      $('#tableTesoreria').dataTable({
        "paging":false,
        "ordering": false,
        language:{
          "sProcessing":     "Procesando...",
          "sLengthMenu":     "Mostrar _MENU_ registros",
          "sZeroRecords":    "No se encontraron resultados",
          "sEmptyTable":     "Ningún dato disponible en esta tabla",
          "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
          "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
          "sInfoPostFix":    "",
          "sSearch":         "Buscar:",
          "sUrl":            "",
          "sInfoThousands":  ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
              "sFirst":    "Primero",
              "sLast":     "Último",
              "sNext":     "Siguiente",
              "sPrevious": "Anterior"
          },
          "oAria": {
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
        }
      });
  </script>

<?php echo $__env->make('Plantilla.plantilla', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>