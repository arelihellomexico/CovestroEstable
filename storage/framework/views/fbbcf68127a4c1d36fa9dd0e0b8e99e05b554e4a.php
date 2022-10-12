<?php $__env->startSection('title','Complemento de Pagos'); ?>
<?php $__env->startSection('concentradoMenu','concentrado-active'); ?>
<?php $__env->startSection('contenido'); ?>
<!-- Encabezado -->
<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <h2 class="text-left"><i class="far fa-money-bill-alt"></i>&nbsp;&nbsp;Complemento de pagos</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <p class="text-muted lead text-left">Archivos a timbrar <i class="far fa-money-bill-alt icon-header"></i></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <hr class="underline">
  </div>
</div>

<!-- Fin encabezado -->
<!-- Tabla listos a timbrar -->
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <p class="text-green text-left">LISTAS A TIMBRAR <i class="fas fa-check-circle"></i></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="scroll-payment">
    <table class="display AllDataTable table table-bordered table-hover tabla" id="correctos">
      <thead>
        <tr>
          <td colspan="8">Total de archivos listos a timbrar</td>
          <td colspan="5"><?php echo e($cuantosCorrectos); ?></td>
        </tr>
        <tr>
          <th class="text-muted text-center txt-thead">Clearing Document</th>
          <th class="text-muted text-center txt-thead">RFC Cliente</th>
          <th class="text-muted text-center txt-thead">FECHAPAG</th>
          <th class="text-muted text-center txt-thead">MONEDAP</th>
          <th class="text-muted text-center txt-thead">MONTOP</th>
          <th class="text-muted text-center txt-thead">NUMEROPERP</th>
          <th class="text-muted text-center txt-thead">RFCCTABEN</th>
          <th class="text-muted text-center txt-thead">CATABEN</th>
          <th class="text-muted text-center txt-thead">FORMAP</th>
          <th class="text-muted text-center txt-thead">TIPOCAMBIOP</th>
          <th class="text-muted text-center txt-thead">RFCCTAORD</th>
          <th class="text-muted text-center txt-thead">BANCOORDEXT</th>
          <th class="text-muted text-center txt-thead">CTAORD</th>
          <th class="text-muted text-center txt-thead">USOCFDI</th>
          <th class="text-muted text-center txt-thead">REGIMEN</th>
          <th class="text-muted text-center txt-thead">TASAIVA</th>
          <th class="text-muted text-center txt-thead">TASARETENCION</th>
          <th class="text-muted text-center txt-thead">Observación</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $correctos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cor): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <?php if($cor->timbrado == 1): ?>
        <tr>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->clearing_document); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->rfc_c); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->fechap); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->monedaP); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->montoP); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->numeroperP); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->rfcctaben); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->cataben); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->formap); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->tipocambioP); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->rfcctaord); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->bancoordext); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->ctaord); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->USOCFDI); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->regimen); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->TASAIVA); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->TASARETENCION); ?></td>
          <th class="text-muted text-center txt-thead">Observación</th>
          <td class="text-muted text-center txt-tbody"></td>
        </tr>
        <?php else: ?>
        <tr style="background-color: #ECCB02;">
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->clearing_document); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->rfc_c); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->fechap); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->monedaP); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->montoP); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->numeroperP); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->rfcctaben); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->cataben); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->formap); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->tipocambioP); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->rfcctaord); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->bancoordext); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->ctaord); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->timbrado); ?></td>
        </tr>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
      </tbody>
    </table>
  </div>

  <div class="row">
    <?php if($procesos == 0): ?>
      <div class="col-6 col-xs-6 col-sm-6 col-md-2 col-lg-2 col-md-offset-5 col-lg-offset-5">
        <form action="javascript:generarCorrectos();" method="post" id="formulario">
          <button type="button" class="button btn-link" name="button">No se ha hecho ninguna integración.</button>
        </form>
      </div>
    <?php else: ?>
      <?php if($proceso->integracion != 1): ?>
      <div class="col-6 col-xs-6 col-sm-6 col-md-2 col-lg-2 col-md-offset-5 col-lg-offset-5">
        <form action="javascript:generarCorrectos();" method="post" id="formulario">
          <button type="button" class="button btn-link" name="button">Ya se generaron los archivos de texto de estos complementos.</button>
        </form>
        <form action="<?php echo e(url('/trafico')); ?>" method="get">
          <?php echo e(csrf_field()); ?>

          <br>
          <button type="submit" id="verArchivos" name="verArchivos" class="button btn-blue"><small>Ver archivos</small></button>
        </form>
      </div>
      <?php else: ?>
      <div class="col-6 col-xs-6 col-sm-6 col-md-2 col-lg-2 col-md-offset-5 col-lg-offset-5">
        <form action="javascript:generarCorrectos();" method="post" id="formulario">
          <?php echo e(csrf_field()); ?>

          <br>
          <button type="submit" name="timbrar" class="button btn-green"><small>Generar txt</small></button>
        </form>
        <form action="<?php echo e(url('/trafico')); ?>" method="get">
          <?php echo e(csrf_field()); ?>

          <br>
          <button type="submit" id="verArchivos" name="verArchivos" class="button btn-blue" disabled="true"><small>Ver archivos</small></button>
        </form>
      </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
<!-- Fin tabla  listos a timbrar -->
<!-- Tabla archivos en conflicto -->
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <p class="text-red text-left">EN CONFLICTO <i class="fas fa-times-circle"></i></p>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="scroll-payment">
    <table class="display AllDataTable table table-bordered table-hover tabla" id="erroneos">
      <thead>
        <tr>
          <td colspan="8">Total de archivos en conflicto</td>
          <td colspan="6"><?php echo e($cuantosIncidentes); ?></td>
        </tr>
        <tr>
          <th class="text-muted text-center txt-thead">Clearing Document</th>
          <th class="text-muted text-center txt-thead">RFC Cliente</th>
          <th class="text-muted text-center txt-thead">FECHAPAG</th>
          <th class="text-muted text-center txt-thead">MONEDAP</th>
          <th class="text-muted text-center txt-thead">MONTOP</th>
          <th class="text-muted text-center txt-thead">NUMEROPERP</th>
          <th class="text-muted text-center txt-thead">RFCCTABEN</th>
          <th class="text-muted text-center txt-thead">CATABEN</th>
          <th class="text-muted text-center txt-thead">FORMAP</th>
          <th class="text-muted text-center txt-thead">TIPOCAMBIOP</th>
          <th class="text-muted text-center txt-thead">RFCCTAORD</th>
          <th class="text-muted text-center txt-thead">BANCOORDEXT</th>
          <th class="text-muted text-center txt-thead">CTAORD</th>
          <th class="text-muted text-center txt-thead">CONFLICTO</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $incidentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cor): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <tr>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->clearing_document); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->rfc_c); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->fechap); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->monedaP); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->montoP); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->numeroperP); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->rfcctaben); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->cataben); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->formap); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->tipocambioP); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->rfcctaord); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->bancoordext); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->ctaord); ?></td>
          <td class="text-muted text-center txt-tbody"><?php echo e($cor->timbrado); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
      </tbody>
    </table>
  </div>
  <div class="col-6 col-xs-6 col-sm-6 col-md-2 col-lg-2 col-md-offset-5 col-lg-offset-5">
    <form action="<?php echo e(url('/incidencias')); ?>" method="post" id="formulario2">
      <?php echo e(csrf_field()); ?>

      <br>
      <?php if($in > 0): ?>
        <button type="submit" name="timbrar" class="button btn-blue"><small>Detalle de incidencias</small></button>
      <?php endif; ?>
    </form>
  </div>
</div>
<!-- Fin tabla archivos en conflicto -->
        <?php $__env->stopSection(); ?>
    <!-- Modal -->
          <div class="modal fade" id="modal-falta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <img src="<?php echo e(asset('assets/img/signo2.png')); ?>">
                    <h3><strong>Archivo Incompleto</strong></h3>
                    <p>Falta una columna. Asegúrate de que esté bien escrita o la hayas incluido en tu archivo.</p>
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
          <!-- Fin Modal -->
          <!-- Modal -->
          <div class="modal fade" id="modal-verifica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <img src="<?php echo e(asset('assets/img/signo.png')); ?>">
                    <h3><strong>Datos incorrectos</strong></h3>
                    <p>Algunos de los datos que subiste están erroneos. ¿Deseas subir tus datos así?</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-6" align="left">
                      <form id="cosas3" action="javascript:guardarDatos();">
                        <?php echo e(csrf_field()); ?>

                        <button type="submit" class="btn btn-warninf">Aceptar</button>
                      </form>
                    </div>
                    <div class="col-xs-6" align="left">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
                    <p>Se han creado los archivos para timbrar. Para verlos, haz click en "Ver archivos"</p>
                  </center>
                </div>
                <div class="modal-footer">
                  <div class="row">
                    <div class="col-xs-12" align="center">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
                    <p>Hubo un error al crear los archivos.</p>
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
          <div class="modal fade" id="modal-no-carga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-body">
                  <center>
                    <img src="<?php echo e(asset('assets/img/signo2.png')); ?>">
                    <h3><strong>Error</strong></h3>
                    <p>No hay complementos de los cuales generar txt.</p>
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
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $('#correctos').DataTable({
                         "dom": 'T<"clear">lfrtip',
                         "tableTools": {
                             "sRowSelect": "multi",
                             "aButtons": [
                                 {
                                     "sExtends": "select_none",
                                     "sButtonText": "Borrar selección"
                                 }]
                         },
                         "pagingType": "simple_numbers",
//Actualizo las etiquetas de mi tabla para mostrarlas en español
                         "language": {
                             "lengthMenu": "Mostrar _MENU_ registros por página.",
                             "zeroRecords": "No se encontró registro.",
                             "info": "  _START_ de _END_ (_TOTAL_ registros totales).",
                             "infoEmpty": "0 de 0 de 0 registros",
                             "infoFiltered": "(Encontrado de _MAX_ registros)",
                             "search": "Buscar: ",
                             "processing": "Procesando la información",
                             "paginate": {
                                 "first": " |< ",
                                 "previous": "Ant.",
                                 "next": "Sig.",
                                 "last": " >| "
                             }
                         }
                     });
      $('#erroneos').DataTable({
                         "dom": 'T<"clear">lfrtip',
                         "tableTools": {
                             "sRowSelect": "multi",
                             "aButtons": [
                                 {
                                     "sExtends": "select_none",
                                     "sButtonText": "Borrar selección"
                                 }]
                         },
                         "pagingType": "simple_numbers",
//Actualizo las etiquetas de mi tabla para mostrarlas en español
                         "language": {
                             "lengthMenu": "Mostrar _MENU_ registros por página.",
                             "zeroRecords": "No se encontró registro.",
                             "info": "  _START_ de _END_ (_TOTAL_ registros totales).",
                             "infoEmpty": "0 de 0 de 0 registros",
                             "infoFiltered": "(Encontrado de _MAX_ registros)",
                             "search": "Buscar: ",
                             "processing": "Procesando la información",
                             "paginate": {
                                 "first": " |< ",
                                 "previous": "Ant.",
                                 "next": "Sig.",
                                 "last": " >| "
                             }
                         }
                     });
    </script>
    <script type="text/javascript" src="<?php echo e(asset('assets/js/dropmenu.js')); ?>"></script>
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
    </script>
    <script type="text/javascript">
      function generarCorrectos(){
        var form = new FormData(document.getElementById('formulario'));
        var contenido = "";
        $.ajax({
          url: 'generarTxtCorrectos',
          type: 'post',
          data: form,
          processData: false,
          contentType: false,
          beforeSend: function(){
            $("#modal-cargando").modal('show');          },
          success: function(data){
            console.log(data.respuesta);
            console.log(data);
            if(data.respuesta == "si"){
              $("#modal-cargando").modal('hide'); 
              $("#modal-exito").modal("show");
              $("#verArchivos").prop("disabled", false);
              console.log(data.mensaje);
              console.log(data.otrmens);
            }
            else{
              if(data.respuesta == "2" || data.respuesta == 2){
                $("#modal-no-carga").modal("show");
                console.log(data.mensaje);
                console.log(data.otrmens);
              }
              else{
                console.log(data.respuesta);
                $("#modal-error").modal("show");
                console.log(data.mensaje);
                console.log(data.otrmens);
              }
            }
          },
          error: function(){
            $("#modal-error").modal("show");
          }
        });
      }

    /*function generarErroneos(){
      var form = new FormData(document.getElementById('formulario2'));
      var contenido = "";
      $.ajax({
        url: 'generarTxtErroneos',
        type: 'post',
        data: form,
        processData: false,
        contentType: false,
        success: function(data){
          if(data.respuesta == "si"){
            $("#modal-exito").modal("show");
          }
          else{
            $("#modal-error").modal("show");
          }
        },
        error: function(){
          $("#modal-error").modal("show");
        }
      });
    }*/
    </script>
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
  <!-- Tablas Pagination -->
  <script type="text/javascript" src="<?php echo e(asset('assets/js/jquery.dataTables.min.js')); ?>"></script>
  <script type="text/javascript" src="<?php echo e(asset('assets/js/dataTables.bootstrap.min.js')); ?>"></script>

  <script type="text/javascript">
  $(document).ready( function () {
    $('.AllDataTable').DataTable({
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
  } );
  </script>
  </body>
</html>

<?php echo $__env->make('Plantilla.plantilla', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>