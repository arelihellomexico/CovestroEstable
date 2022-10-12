<?php $__env->startSection('title','Gestor de datos de la empresa'); ?>
<?php $__env->startSection('covestroMenu','covestro-active'); ?>
<?php $__env->startSection('usuario','Usuario Administrador'); ?>
<?php $__env->startSection('contenido'); ?>
<!-- Encabezado -->
<div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <h2 class="text-left"><img href="" id="img-covestro" src="<?php echo e(asset('assets/img/planning.svg')); ?>" class="header"> Gestor de datos Covestro</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <p class="text-muted lead text-left">Datos de la empresa <img href="" id="img-covestro" src="<?php echo e(asset('assets/img/planning.svg')); ?>" class="icon-header"></p>
    <hr class="underline">
  </div>
</div>
<!-- Fin encabezado -->

<!-- Formulario de datos de la empresa -->
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-5 col-lg-5 col-lg-offset-1">
    <form action="javascript:actualizarDatos()" id="form_datos">
      <?php echo e(csrf_field()); ?>

      <div class="form-group">
        <label for="rfc_e">RFC del contribuyente</label>
        <input type="text" class="form-control" id="rfc_e" name="rfc_r" placeholder="RFC del contribuyente" required value="<?php echo e($datos->rfc_e); ?>" disabled>
        <p id="p_rfc_e" class="text-light text-center bg-danger"></p>
      </div>
      <div class="form-group">
        <label for="nombre_e">Nombre del emisor</label>
        <input type="text" class="form-control" id="nombre_e" name="nombre_e" placeholder="Nombre del emisor" required value="<?php echo e($datos->nombre_e); ?>" disabled>
        <p id="p_nombre_e" class="text-light text-center bg-danger"></p>
      </div>
      <div class="form-group">
        <label for="metpago">Método de pago</label>
        <input type="text" class="form-control" id="metpago" name="metpago" placeholder="Método de pago" required value="<?php echo e($datos->metpago); ?>" disabled>
        <p id="p_metpago" class="text-light text-center bg-danger"></p>
      </div>
      <div class="form-group">
        <label for="iddoc">Versión Fiscal</label>
        <input type="text" class="form-control" id="version_fiscal" name="version_fiscal" placeholder="Versión Fiscal" required value="<?php echo e($datos->version_fiscal); ?>" disabled>
        <p id="version_fiscal" class="text-light text-center bg-danger"></p>
      </div>
      <div class="form-group">
        <label for="iddoc">Versión Complemento</label>
        <input type="text" class="form-control" id="version_complemento" name="version_complemento" placeholder="Versión Fiscal" required value="<?php echo e($datos->version_complemento); ?>" disabled>
        <p id="version_complemento" class="text-light text-center bg-danger"></p>
      </div>
      <div class="form-group">
        <label for="folio">Regimen</label>
        <input type="text" class="form-control" id="regimen" name="regimen" placeholder="Regimen" required value="<?php echo e($datos->regimen); ?>" disabled>
        <p id="p_folio" class="text-light text-center bg-danger"></p>
      </div>
      <div class="form-group">
        <label for="lugar_expedicion">Número de pago</label>
        <input type="text" class="form-control" id="numpago" name="numpago" placeholder="Lugar de expedición" required disabled value="<?php echo e($datos->numpago); ?>">
        <p id="p_lugar_expedicion" class="text-light text-center bg-danger"></p>
      </div>
      <div class="row">
        <div class="form-group">
          <label for="calle_r" class="col-sm-6 control-label">Usar datos provenientes de Crédito y Cobranza:</label>
          <div class="col-sm-6">
            <?php if($datos->usar_credito == 1): ?>
            <input type="checkbox" id="usar_credito" name="usar_credito" checked data-toggle="toggle" disabled>
            <?php else: ?>
            <input type="checkbox" id="usar_credito" name="usar_credito" data-toggle="toggle" disabled>
            <?php endif; ?>
            <p id="p_usar_credito" class="text-light text-center bg-danger"></p>
          </div>
        </div>
      </div>
      <div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
        <button type="submit" name="button" class="button btn-green"><small>Guardar</small></button>
      </div>
      <div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
        <button type="button" name="button" class="button btn-blue" onclick="editar()"><small>Editar</small></button>
      </div>
      <div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
        <button type="reset" name="button" class="button btn-pink" onclick="cancel()"><small>Cancelar</small></button>
      </div>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-5">
    <p class="text-dark lead text-center">Dirección del emisor</p>
    <div class="form-group">
      <label for="calle_r" class="col-sm-6 control-label">Calle (CALLE_R):</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="calle_r" name="calle_r" placeholder="Calle" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()"  value="<?php echo e($datos->calle_e); ?>" disabled>
        <p id="p_calle" class="text-light text-center bg-danger"></p>
      </div>
    </div>
    <br><br>
    <div class="form-group">
      <label for="next_e" class="col-sm-6 control-label">Número exterior (NEXT_E):</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="next_e" name ="next_e"  placeholder="Numero exterior" value="<?php echo e($datos->numext_e); ?>" disabled>
        <p id="p_next_e" class="text-light text-center bg-danger"></p>
      </div>
    </div>
    <br><br>
    <div class="form-group">
      <label for="nint_e" class="col-sm-6 control-label">Número interior (NINT_E):</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="nint_e" name="nint_e" placeholder="Número interior" value="<?php echo e($datos->numint_e); ?>" disabled>
        <p id="p_nint_e" class="text-light text-center bg-danger"></p>
      </div>
    </div>
    <br><br>
    <div class="form-group">
      <label for="colonia_e" class="col-sm-6 control-label">Nombre de la colonia (COLONIA_E):</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="colonia_e" name="colonia_e" placeholder="Nombre de la colonia" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" value="<?php echo e($datos->colonia_e); ?>" disabled>
        <p id="p_colonia_e" class="text-light text-center bg-danger"></p>
      </div>
    </div>
    <br><br>
    <div class="form-group">
      <label for="local_e" class="col-sm-6 control-label">Nombre de localida/ciudad (LOCALIDAD_R):</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="local_e" name="localidad_r" placeholder="Nombre de la localidad/ciudad" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()"  value="<?php echo e($datos->localidad_e); ?>" disabled>
        <p id="p_local_e" class="text-light text-center bg-danger"></p>
      </div>
    </div>
    <br><br>
    <div class="form-group">
      <label for="ref_e" class="col-sm-6 control-label">Referencias (REF_E):</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="ref_e" name="ref_e" placeholder="Referencias" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" value="<?php echo e($datos->referencia_e); ?>" disabled>
        <p id="p_ref_e" class="text-light text-center bg-danger"></p>
      </div>
    </div>
    <br><br>
    <div class="form-group">
      <label for="municip_e" class="col-sm-6 control-label">Municipio (MUNICIP_E):</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="municip_e" name="municip_e" placeholder="Municipio" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()"  value="<?php echo e($datos->municipio_e); ?>" disabled>
        <p id="p_municip_e" class="text-light text-center bg-danger"></p>
      </div>
    </div>
    <br><br>
    <div class="form-group">
      <label for="estado_e" class="col-sm-6 control-label">Estado (ESTADO_E):</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="estado_e" name="estado_e" placeholder="Estado" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" value="<?php echo e($datos->estado_e); ?>" disabled>
        <p id="p_estado_e" class="text-light text-center bg-danger"></p>
      </div>
    </div>
    <br><br>
    <div class="form-group">
      <label for="pais_e" class="col-sm-6 control-label">Pais (PAIS_E):</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="pais_e" name="pais_e" placeholder="Pais" onKeyUp="document.getElementById(this.id).value=document.getElementById(this.id).value.toUpperCase()" value="<?php echo e($datos->pais_e); ?>" disabled>
        <p id="p_pais_e" class="text-light text-center bg-danger"></p>
      </div>
    </div>
    <br><br>
    <div class="form-group">
      <label for="cp_e" class="col-sm-6 control-label">Código postal (CP_E):</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="cp_e" name="cp_e" placeholder="Código postal" value="<?php echo e($datos->cpostal_e); ?>" disabled>
        <p id="p_cp_e" class="text-light text-center bg-danger"></p>
      </div>
    </div>
    </form>
  </div>
</div>
<!-- Fin formulario de datos de la empresa -->



<!-- Seccion agregar nuevo banco -->
<div class="row" id="seccion_banco">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <br><br>
    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <p class="text-left lead text-dark"><img href="" id="img-covestro" src="<?php echo e(asset('assets/img/planning.svg')); ?>" class="icon-header">Agregar banco</p>
      <hr class="underline">
    </div>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-5 col-lg-4 col-offset-1 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    <form action="javascript:submitBank()" id="form_bancos">
      <?php echo e(csrf_field()); ?>

      <input type="hidden" name="op" id="op" value="0">
      <div class="form-group">
        <label for="rfcctaben">Número de cuenta(numcuenta)</label>
        <input type="text" class="form-control" name="numcuenta" id="numcuenta" placeholder="Número de cuenta" required min="12">
        <p id="p_rfcctaben" class="text-light text-center bg-danger"></p>
      </div>
      <div class="form-group">
        <label for="cataben">Nombre del banco</label>
        <input type="text" name="nombre_banco" class="form-control" id="nombre_banco" placeholder="Nombre del banco" required>
      </div>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-4">
      <div class="form-group">
        <label for="cataben">RFC del banco(RFC_Banco)</label>
        <input type="text" class="form-control" name="rfc_banco" id="rfc_banco" placeholder="RFC del banco" required>
      </div>
      <div class="form-group">
        <label for="tipocambiop">Cuenta clabe</label>
        <input type="text" class="form-control" id="cuenta_clabe" name="cuenta_clabe" placeholder="Cuenta Clabe" required>
      </div>
      <div class="col-12 col-sm-12 col-xs-12 col-md-4 col-lg-4">
        <button type="submit" name="button" class="button btn-green"><small>Guardar</small></button>
      </div>
      <div class="col-12 col-sm-12 col-xs-12 col-md-4 col-lg-4">
        <button type="button" name="button" class="button btn-pink" onclick="cancel()"><small>Cancelar</small></button>
      </div>
    </form>
  </div>
</div>
<!-- Fin seccion agregar nuevo banco -->



<!-- Seccion tabla de bancos -->
<div class="row">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <br><br>
    <!-- Encabezado -->
    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <p class="text-left lead"><img href="" id="img-covestro" src="<?php echo e(asset('assets/img/planning.svg')); ?>" class="icon-header">Tabla de bancos</p>
      <hr class="underline">
    </div>
    <!-- Fin encabezado -->

    <!-- Tabla -->
    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12" id="tabla">
      <div id="contabla">
        <table class="display table table-bordered table-hover space table-striped" id="tableTesoreria">
          <thead>
            <tr>
              <th class="text-center"><small>numcuenta</small></th>
              <th class="text-center"><small>nombrebanco</small></th>
              <th class="text-center"><small>RFC_Banco</small></th>
              <th class="text-center"><small>cuenta_clabe</small></th>
              <th class="text-center"><small>Editar</small></th>
              <th class="text-center"><small>Eliminar</small></th>
            </tr>
          </thead>
          <tbody id="cuerpo">
            <?php $__currentLoopData = $cuentas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
            <tr>
              <td class="text-center" name="numcuenta"><small><?php echo e($c->numcuenta); ?></small></td>
              <td class="text-center" name="nombrebanco"><small><?php echo e($c->nombrebanco); ?></small></td>
              <td class="text-center" name="RFC_Banco"><small><?php echo e($c->RFC_Banco); ?></small></td>
              <td class="text-center" name="cuenta_clabe"><small><?php echo e($c->cuenta_clabe); ?></small></td>
              <td class="text-center" name="EDITAR"><button class="button btn-transparent" type="button" onclick="editarBanco('<?php echo e($c->numcuenta); ?>')"><i class="far fa-edit"></i></button></td>
              <td class="text-center" name="ELIMINAR"><button class="button btn-transparent" type="button" onclick="preliminar('<?php echo e($c->numcuenta); ?>')"><i class="far fa-trash-alt"></i></button></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
          </tbody>
        </table>
        <script type="text/javascript">
          $(document).ready( function () {
                      $('#tableTesoreria').DataTable({
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
      </div>
    </div>
    <!-- fin tabla -->
  </div>
</div>
<!-- Fin seccion tabla de bancos -->
<div class="modal fade" id="modal-exito" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <center>
                <img src="<?php echo e(asset('assets/img/singo1.png')); ?>">
                <h3><strong>Éxito</strong></h3>
                <p>
                  Los datos de tu banco se han guardado exitosamente.
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
                <img src="<?php echo e(asset('assets/img/singo1.png')); ?>">
                <h3><strong>Éxito</strong></h3>
                <p>
                  Los datos de tu banco se han actualizado exitosamente.
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
<div class="modal fade" id="modal-exito-actualiza-e" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <center>
                <img src="<?php echo e(asset('assets/img/singo1.png')); ?>">
                <h3><strong>Éxito</strong></h3>
                <p>
                  Los datos de la empresa se han actualizado exitosamente.
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
<div class="modal fade" id="modal-exito-elimina" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <center>
                <img src="<?php echo e(asset('assets/img/singo1.png')); ?>">
                <h3><strong>Éxito</strong></h3>
                <p>
                  Se ha eliminado exitosamente.
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
                <img src="<?php echo e(asset('assets/img/signo2.png')); ?>">
                <h3><strong>Error</strong></h3>
                <p>
                  Hubo un problema al guardar los datos de tu banco.
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
              <img src="<?php echo e(asset('assets/img/signo.png')); ?>">
              <h3><strong>Confirmación de Eliminación</strong></h3>
              <p>¿Estás seguro que deseas eliminar este banco?</p>
            </center>
          </div>
          <div class="modal-footer">
            <div class="row">
              <div class="col-xs-5" align="right">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
              </div>
              <div class="col-xs-7" align="left">
                  <input type="hidden" name="nombre_excel" id="archivo2">
                  <button type="button" class="btn btn-primary" onclick="eliminarBanco();">Eliminar.</button>
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
                <img src="<?php echo e(asset('assets/img/signo2.png')); ?>">
                <h3><strong>Error</strong></h3>
                <p>
                  Hubo un problema al actualizar los datos de tu banco.
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
      <div class="modal fade" id="modal-error-actualiza-e" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <center>
                <img src="<?php echo e(asset('assets/img/signo2.png')); ?>">
                <h3><strong>Error</strong></h3>
                <p>
                  Hubo un problema al actualizar los datos de la empresa.
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
      <div class="modal fade" id="modal-error-elimina" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-body">
              <center>
                <img src="<?php echo e(asset('assets/img/signo2.png')); ?>">
                <h3><strong>Error</strong></h3>
                <p>
                  Hubo un problema al eliminar tu banco.
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
      <script>
    $('#usar_credito').bootstrapToggle({
      on: 'Usar Datos',
      off: 'No Usar Datos'
    });
</script>
<?php $__env->stopSection(); ?>


<script>
  var arrastrar = 0;
function editar(){

  var rfc_e = document.getElementById('rfc_e');
  var nombre_e = document.getElementById('nombre_e');
  var calle_r = document.getElementById('calle_r');
  var next_e = document.getElementById('next_e');
  var nint_e = document.getElementById('nint_e');
  var colonia_e = document.getElementById('colonia_e');
  var local_e = document.getElementById('local_e');
  var ref_e = document.getElementById('ref_e');
  var municip_e = document.getElementById('municip_e');
  var estado_e = document.getElementById('estado_e');
  var pais_e = document.getElementById('pais_e');
  var cp_e = document.getElementById('cp_e');
  var metpago = document.getElementById('metpago');
  var version_fiscal = document.getElementById('version_fiscal');
  var regimen = document.getElementById('regimen');
  var numero_pago = document.getElementById('numpago');
  var version_complemento = document.getElementById('version_complemento');
  var usar_credito = document.getElementById('usar_credito');

  version_complemento.disabled=false;
  numero_pago.disabled=false;
  regimen.disabled=false;
  version_fiscal.disabled=false;
  metpago.disabled=false;
  rfc_e.disabled=false;
  nombre_e.disabled=false;
  calle_r.disabled=false;
  next_e.disabled=false;
  nint_e.disabled=false;
  colonia_e.disabled=false;
  local_e.disabled=false;
  ref_e.disabled=false;
  municip_e.disabled=false;
  estado_e.disabled=false;
  estado_e.disabled=false;
  pais_e.disabled=false;
  cp_e.disabled=false;
  usar_credito.disabled=false;
}

  function actualizarDatos(){

    var formulario = new FormData(document.getElementById('form_datos'));

    $.ajax({
      url:'actualizarDatos',
      type:'post',
      data:formulario,
      processData: false,
      contentType: false,
      success: function(data){
        if(data.respuesta=='si'){
          var tabla = '<div id="contabla">'+
        '<table class="display table table-bordered table-hover space table-striped" id="tableTesoreria">'+
          '<thead>'+
            '<tr>'+
              '<th class="text-center"><small>numcuenta</small></th>'+
              '<th class="text-center"><small>nombrebanco</small></th>'+
              '<th class="text-center"><small>RFC_Banco</small></th>'+
              '<th class="text-center"><small>cuenta_clabe</small></th>'+
              '<th class="text-center"><small>Editar</small></th>'+
              '<th class="text-center"><small>Eliminar</small></th>'+
            '</tr>'+
          '</thead>'+
          '<tbody id="cuerpo">';
            for(var i = 0; i < data.length; i++){
            tabla+='<tr>'+
              '<td class="text-center" name="numcuenta"><small>'+data[i].numcuenta+'</small></td>'+
              '<td class="text-center" name="nombrebanco"><small>'+data[i].nombrebanco+'</small></td>'+
              '<td class="text-center" name="RFC_Banco"><small>'+data[i].RFC_Banco+'</small></td>'+
              '<td class="text-center" name="cuenta_clabe"><small>'+data[i].cuenta_clabe+'</small></td>'+
              '<td class="text-center" name="EDITAR"><button class="button btn-transparent" type="button" onclick="editarBanco(\''+data[i].numcuenta+'\')"><i class="far fa-edit"></i></button></td>'+
              '<td class="text-center" name="ELIMINAR"><button class="button btn-transparent" type="button" onclick="preliminar(\''+data[i].numcuenta+'\')"><i class="far fa-trash-alt"></i></button></td>'+
            '</tr>';
            }
          tabla+='</tbody>'+
        '</table>'+
        '<script type="text/javascript">'+
                      '$("#tableTesoreria").DataTable({'+
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
                    '} );'+
        '<\/script>'+
      '</div>';
            //$("#tabla").append(tabla);
          $('#modal-exito-actualiza-e').modal('show');
        }else{
            $('#modal-error-actualiza-e').modal('show');
        }
      },
      error:function(){
        $('#modal-error-actualiza-e').modal('show');
      }
    });
  }
  function submitBank(){
    var formulario = new FormData(document.getElementById('form_bancos'));

    var opcion = '';
    if($('#op').val()==0){
      opcion = 'guardarBanco';
    }else{
      opcion = 'actualizarBancos';
    }
    $.ajax({
      url:opcion,
      type:'post',
      data:formulario,
      processData: false,
      contentType: false,
      success: function(data){
        if(data.respuesta=='no'){
          $('#modal-error').modal('show');
        }else{
          document.getElementById('tabla').removeChild(document.getElementById('contabla'));
            var tabla = '<div id="contabla">'+
        '<table class="display table table-bordered table-hover space table-striped" id="tableTesoreria">'+
          '<thead>'+
            '<tr>'+
              '<th class="text-center"><small>numcuenta</small></th>'+
              '<th class="text-center"><small>nombrebanco</small></th>'+
              '<th class="text-center"><small>RFC_Banco</small></th>'+
              '<th class="text-center"><small>cuenta_clabe</small></th>'+
              '<th class="text-center"><small>Editar</small></th>'+
              '<th class="text-center"><small>Eliminar</small></th>'+
            '</tr>'+
          '</thead>'+
          '<tbody id="cuerpo">';
            for(var i = 0; i < data.length; i++){
            tabla+='<tr>'+
              '<td class="text-center" name="numcuenta"><small>'+data[i].numcuenta+'</small></td>'+
              '<td class="text-center" name="nombrebanco"><small>'+data[i].nombrebanco+'</small></td>'+
              '<td class="text-center" name="RFC_Banco"><small>'+data[i].RFC_Banco+'</small></td>'+
              '<td class="text-center" name="cuenta_clabe"><small>'+data[i].cuenta_clabe+'</small></td>'+
              '<td class="text-center" name="EDITAR"><button class="button btn-transparent" type="button" onclick="editarBanco(\''+data[i].numcuenta+'\')"><i class="far fa-edit"></i></button></td>'+
              '<td class="text-center" name="ELIMINAR"><button class="button btn-transparent" type="button" onclick="preliminar(\''+data[i].numcuenta+'\')"><i class="far fa-trash-alt"></i></button></td>'+
            '</tr>';
            }
          tabla+='</tbody>'+
        '</table>'+
        '<script type="text/javascript">'+
                      '$("#tableTesoreria").DataTable({'+
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
                    '} );'+
        '<\/script>'+
      '</div>';
            $("#tabla").append(tabla);
            if($('#op').val()==0){
              $('#modal-exito').modal('show');
            }else{
              $('#op').val(0)
              $('#modal-exito-actualiza').modal('show');
            }
          }
      },
      error:function(){
        if($('#op').val()==0){
              $('#modal-error').modal('show');
            }else{
              $('#modal-error-actualiza').modal('show');
            }
        $('#modal-error').modal('show');
      }
    });
  }
  function validar(){
      //Obtenemos campos y parrafos de campos
      var rfc_e = document.getElementById('rfc_e').value;
      var p_rfc_e = document.getElementById('p_rfc_e');


      var nombre_e = document.getElementById('nombre_e').value;
      var p_nombre_e = document.getElementById('p_nombre_e');


      var metpago = document.getElementById('metpago').value;//Debe ser int para validar
      var p_metpago = document.getElementById('p_metpago');


      var iddoc = document.getElementById('iddoc').value;
      var p_iddoc = document.getElementById('p_iddoc');


      var folio = document.getElementById('folio').value;
      var p_folio = document.getElementById('p_folio');


      var lugar_expedicion = document.getElementById('lugar_expedicion').value;
      var p_lugar_expedicion = document.getElementById('p_lugar_expedicion');


      var calle = document.getElementById('calle_r').value;
      var p_calle = document.getElementById('p_calle_r');


      var next_e = parseInt(document.getElementById('next_e').value);
      var p_next_e = document.getElementById('p_next_e');


      var nint_e = parseInt(document.getElementById('nint_e').value);
      var p_nint_e = document.getElementById('p_nint_e');


      var colonia = document.getElementById('colonia_e').value;
      var p_colonia_e = document.getElementById('p_colonia_e');


      var local_e = document.getElementById('local_e').value;
      var p_local_e = document.getElementById('p_local_e');


      var ref_e = document.getElementById('ref_e').value;
      var p_ref_e = document.getElementById('p_ref_e');


      var municip_e = document.getElementById('municip_e').value;
      var p_municip_e = document.getElementById('p_municip_e');


      var estado_e = document.getElementById('estado_e').value;
      var p_estado_e = document.getElementById('p_estado_e');


      var pais_e = document.getElementById('pais_e').value;
      var p_pais_e = document.getElementById('p_pais_e');


      var cp_e = parseInt(document.getElementById('cp_e').value);
      var p_cp_e = document.getElementById(p_cp_e);


      var errores = 0;
      //Validacion de campos
      if(rfc_e.length == 12 || rfc_e.length ==13){
        console.log("Tamaño correcto del rfc_e");
      }else{
        p_rfc_e.innerHTML ="El campo RFC debe tener una longitud de entre 12 y 13 caracteres.";
        errores++;
      }
      if(iddoc != folio){
        p_iddoc.innerHTML ="El campo IDDOC debe ser el mismo que el folio";
        p_folio.innerHTML ="El campo Folio debe ser el mismo que IDDOC";
        errores++;
        errores++;
      }else if(calle.length != 0){

      }

      if(errores == 0){
        //Limpiar campos y enviar
        p_rfc_e.innerHTML = " ";
        p_nombre_e.innerHTML = " ";
        p_iddoc.innerHTML =" ";
        p_folio.innerHTML = " ";
        p_lugar_expedicion.innerHTML = " ";

        console.log("Se valido de forma correcta");
      }
  };


  function cancel(){
    location.reload();
  }


  function editarBanco(numcuenta){
    var texto = numcuenta;
    $.ajax({
      url:'editarBanco',
      type:'get',
      data:{numcuenta:texto},
      success: function(data){
        if(data.respuesta=='no'){
          $('#modal-error').modal('show');
        }else{
          $('#rfc_banco').val(data.RFC_Banco);
          $('#numcuenta').val(data.numcuenta);
          $('#nombre_banco').val(data.nombrebanco);
          $('#cuenta_clabe').val(data.cuenta_clabe);

          $('#op').val(1);
        }
      },
      error:function(){
        $('#modal-error').modal('show');
      }
    });
  }

  function preliminar(banco){
    arrastrar = banco;
    $("#modal-verifica-eliminar").modal("show");
  }

  function eliminarBanco(){
    var texto = arrastrar;
    $.ajax({
      url:'eliminarBanco',
      type:'get',
      data:{numcuenta:texto},
      success: function(data){
        if(data.respuesta=='no'){
          /*$('#rfc_banco').val(data.RFC_Banco);
          $('#numcuenta').val(data.numcuenta);
          $('#nombre_banco').val(data.nombrebanco);
          $('#cuenta_clabe').val(data.cuenta_clabe);*/
          $("#modal-verifica-eliminar").modal('hide');
          $('#modal-error-elimina').modal('show');
        }else{
          document.getElementById('tabla').removeChild(document.getElementById('contabla'));
            var tabla = '<div id="contabla">'+
        '<table class="display table table-bordered table-hover space table-striped" id="tableTesoreria">'+
          '<thead>'+
            '<tr>'+
              '<th class="text-center"><small>numcuenta</small></th>'+
              '<th class="text-center"><small>nombrebanco</small></th>'+
              '<th class="text-center"><small>RFC_Banco</small></th>'+
              '<th class="text-center"><small>cuenta_clabe</small></th>'+
              '<th class="text-center"><small>Editar</small></th>'+
              '<th class="text-center"><small>Eliminar</small></th>'+
            '</tr>'+
          '</thead>'+
          '<tbody id="cuerpo">';
            for(var i = 0; i < data.length; i++){
            tabla+='<tr>'+
              '<td class="text-center" name="numcuenta"><small>'+data[i].numcuenta+'</small></td>'+
              '<td class="text-center" name="nombrebanco"><small>'+data[i].nombrebanco+'</small></td>'+
              '<td class="text-center" name="RFC_Banco"><small>'+data[i].RFC_Banco+'</small></td>'+
              '<td class="text-center" name="cuenta_clabe"><small>'+data[i].cuenta_clabe+'</small></td>'+
              '<td class="text-center" name="EDITAR"><button class="button btn-transparent" type="button" onclick="editarBanco(\''+data[i].numcuenta+'\')"><i class="far fa-edit"></i></button></td>'+
              '<td class="text-center" name="ELIMINAR"><button class="button btn-transparent" type="button" onclick="preliminar(\''+data[i].numcuenta+'\')"><i class="far fa-trash-alt"></i></button></td>'+
            '</tr>';
            }
          tabla+='</tbody>'+
        '</table>'+
        '<script type="text/javascript">'+
                      '$("#tableTesoreria").DataTable({'+
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
                    '} );'+
        '<\/script>'+
      '</div>';
            $("#tabla").append(tabla);
            $("#modal-verifica-eliminar").modal('hide');
          $('#modal-exito-elimina').modal('show');
        }
      },
      error:function(){
        $('#modal-error-elimina').modal('show');
      }
    });
  }
</script>


<?php echo $__env->make('Plantilla.plantilla', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>