<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login Covestro</title>
    <!--Añadimos Bootstrap -->
    <script type="text/javascript" src="<?php echo e(asset('assets/js/jquery.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>">
    <script type="text/javascript" src="<?php echo e(asset('assets/js/bootstrap.min.js')); ?>"></script>
    <!--Linea para agregar css propio-->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/estilos.css')); ?>">
  </head>
  <body background="<?php echo e(asset('assets/img/background.png')); ?>">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="col-10 col-xs-10 col-sm-10 col-md-2 col-lg-2 col-xs-offset-1 col-sm-offset-1 col-md-offset-5 col-lg-offset-5">
            <img src="<?php echo e(asset('assets/img/logo.png')); ?>" alt="" class="img-responsive logo">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-10 col-xs-10 col-sm-10 col-md-4 col-lg-4 col-xs-offset-1 col-sm-offset-1 col-md-offset-4 col-lg-offset-4">
          <h4 class="text-center txt-white">Sistema de</h4>
          <h4 class="text-center txt-white">complemento de pagos</h4>
        </div>
      </div>
      <div class="row">
        <div class="col-10 col-xs-10 col-sm-10 col-md-4 col-lg-4 col-xs-offset-1 col-sm-offset-1 col-md-offset-4 col-lg-offset-4">
          <div class="colors-line"></div>
          <form class="login" action="javascript:login();" method="post" id="formulario">
            <?php echo e(csrf_field()); ?>

            <div class="row">
              <div class="col-10 col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
                <p class="text-center text-login">Iniciar sesión</p>
              </div>
              <div class="col-10 col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
                <div class="form-group">
                  <label for="email" class="txt-input-login">Usuario</label>
                  <input type="text" class="form-control" id="email" name="email" required>
                </div>
              </div>
              <div class="col-10 col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
                <div class="form-group">
                  <label for="password" class="txt-input-login">Contraseña</label>
                  <input type="password" class="form-control" id="password" name="contrasenia" required>
                </div>
              </div>
              <div class="container-fluid">
                <div class="row">
                  <div class="col-10 col-xs-10 col-sm-10 col-md-10 col-lg-4 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">

                  </div>
                  <div class="col-10 col-xs-10 col-sm-10 col-md-10 col-lg-5 col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
                    <button type="submit"class="button btn-blue" name="button"><small>Iniciar sesión</small></button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal fade" id="modal-exito" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-body">
                        <center>
                          <img src="<?php echo e(asset('assets/img/singo1.png')); ?>">
                          <h3><strong>Éxito</strong></h3>
                          <p>
                            Has iniciado sesión correctamente.
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
                            Error al iniciar sesión. Verfica que tus datos estén correctos.
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
    </div>
  </body>
  <script type="text/javascript">
    function login() {
      var formulario = new FormData(document.getElementById('formulario'));
      $.ajax({
        url: 'loggear',
        type: 'post',
        data: formulario,
        processData: false,
        contentType: false,
        success: function(data){
          if(data.respuesta == "no"){
            $("#modal-error").modal('show');
          }
          else{
            window.location.href = "login";
          }
        },
        error: function(){
          $("#modal-error").modal('show');
        }
      })
    }
  </script>
</html>
