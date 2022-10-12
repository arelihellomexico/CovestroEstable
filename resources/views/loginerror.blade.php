<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <!--Añadimos Bootstrap -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <script type="text/javascript" src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/jquery.js')}}"></script>
    <!--Linea para agregar css propio-->
    <link rel="stylesheet" href="{{asset('assets/css/index.css')}}">
  </head>
  <body class="bg-login">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-sm-12 col-md-4 col-lg-4"></div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
          <img src="{{asset('assets/img/logo.png')}}" alt="" class="img-logo">
          <p class="wtext lead text-center">Sistema de complementos de pagos</p>
          <div class="colors-line"></div>
          <form class="form-background" method="post" action="{{url('/login')}}">
            {{csrf_field()}}
            <p class="text-muted lead text-center">Iniciar sesión</p>
            <div class="form-group">
              <p class="text-muted small">Correo electrónico</p>
              <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" required>
            </div>
            <div class="form-group">
              <p class="text-muted small">Contraseña</p>
              <input type="password" class="form-control" name="contrasenia" id="exampleInputPassword1" required>
            </div>
            <p class="text-muted small forgotText">¿Olvide mi contraseña?</p>
            <button type="submit" class="btn btn-primary btn-align">Iniciar sesión</button>
          </form>
          <p class="text-muted small forgotText">Error al iniciar sesión. No existe usuario o contraseña</p>
        </div>
      </div>
    </div>
  </body>
</html>
