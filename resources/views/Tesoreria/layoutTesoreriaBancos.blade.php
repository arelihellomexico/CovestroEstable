@extends('Plantilla.plantilla')
@if(Session::get('tipo') != 1)
@section('title','Layout Tesoreria Bancos')
@section('usuario','Usuario Tesorería')
@section('validacionMenu','no-mostrar')
@section('creditoMenu','no-mostrar')
@section('sapMenu','no-mostrar')
@section('concentradoMenu','no-mostrar')
@section('clientesMenu','no-mostrar')
@section('covestroMenu','no-mostrar')
@else
@section('title','Usuario Administrador')
@endif
@section('tesoreriaMenu','tesoreria-active')
@section('contenido')
<!--Encabezado-->
<div class="row row-header">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <h2 class="tect-left"><i class="far fa-copy"></i> <strong>Tesorería</strong> Layout Bancos</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <p class="text-muted lead text-left">Layouts creados <i class="far fa-copy icon-header"></i></p>
    <hr class="underline">
  </div>
</div>
<!-- Fin encabezado -->

<!-- Botones -->
<div class="row">
  <div class="col-6 col-xs-6 col-sm-6 col-md-3 col-lg-2 col-xs-offset-6 col-sm-offset-6 col-md-offset-6 col-lg-offset-8">
    <!--button type="button" class="button btn-orange" name="button"><i class="far fa-copy"></i> Layout Bancos</button-->
  </div>
  <div class="col-6 col-xs-6 col-sm-6 col-md-3 col-lg-2">
    <!--button type="button" class="button btn-blue" name="button"><i class="far fa-copy"></i> Layout Forma de pago</button-->
  </div>
</div>
<br>
<!-- Fin botones -->
<form id="formulario" action="javascript:guardarLayout();" method="post">
  {{csrf_field()}}
<!-- Tabla layouts -->
<div class="row" id="tablaPadre">
  <div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1" id="tablaHijo">
    <table class="display AllDataTable table table-bordered table-hover space table-striped">
      <thead>
        <tr>
          <th class="text-muted text-center"><small>Título del layout</small></th>
          <th class="text-muted text-center"><small>Editar</small></th>
          <th class="text-muted text-center"><small>Eliminar</small></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($layout as $l)
          <tr>
            <td class="text-muted">{{ $l->nombre }}</td>
            <td class="text-muted"><button class="button btn-transparent" type="button" onclick="edito({{$l->id_lt}});"><i class="far fa-edit"></i></button></td>
            <td class="text-muted"><button class="button btn-transparent" type="button" onclick="elimino({{$l->id_lt}});"><i class="far fa-trash-alt"></i></button></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
</form>
<!-- Fin tabla layouts -->


<form class="" action="javascript:hacerPrueba();" method="post" id="formprueba" name="formprueba">
  {{csrf_field()}}
  <input type="hidden" id="op" name="op" value="0">
  <!--Encabezado-->
  <div class="row row-header">
    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <p class="text-muted lead text-left">Nuevo Layout <i class="far fa-copy icon-header"></i></p>
      <p class="text-muted lead text-left" style="font-size: 16px;">Los nombres de los datos deben estar escritos en minúscula y solo se permite "_" (guión bajo) para la separación de las palabras. NO DEBES USAR MAYÚSCULAS NI SÍMBOLOS QUE NO SEAN ALFABÉTICOS. </p>
      <hr class="underline">
    </div>
  </div>
  <!-- Fin encabezado -->


  <div class="row">
    <div class="col125 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
      <p class="text-muted">Título del layout</p>
    </div>
    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-md-offset-1">
      <div class="form-group">
        <input type="text" class="form-control" id="titulo" name="nombre" required>
      </div>
    </div>
    <div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1">
      <p class="text-muted">Datos obligatorios</p>
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1">

      <table class="table table-bordered table-hover table-striped">
        <thead>
          <th class="text-muted text-center"><small>Datos del SAT</small></th>
          <th class="text-muted text-center"><small>Significado</small></th>
          <th class="text-muted text-center"><small>Descripción</small></th>
          <th class="text-muted text-center"><small>Palabra clave <button class="btn-icon-info" type="button" data-toggle="popover" data-placement="top" data-content="En los campos vacios se debe colocar el título de la columna del archivo 'excel' correspondiente a este dato."><i class="fas fa-info-circle"></i></button></small></th>
        </thead>

        <tbody>
          <tr>
            <td class="text-muted text-center" name="titulo"><small>FORMAP</small></td>
            <td class="text-muted text-center" name="titulo"><small>Forma de pago</small></td>
            <td class="text-muted text-center" name="fecha_creacion"><small>Es el número que indica la forma en la que se realizo el pago(Transferecnia bancaria, cheque, etc.)</small></td>
            <td class="text-muted text-center"><small><input type="text" class="form-control" id="forma_pago" name="forma_pago" required></small></td>

          </tr>
          <tr>
            <td class="text-muted text-center" name="titulo"><small>MONEDAP</small></td>
            <td class="text-muted text-center" name="titulo"><small>Moneda de pago</small></td>
            <td class="text-muted text-center" name="fecha_creacion"><small>Tipo de moneda en la que se hace el pago(Dolares, -USD, Peso Mexicano - MXN, Libra-LBT, etc.)</small></td>
            <td class="text-muted text-center"><small><input type="text" class="form-control" id="moneda_pago" name="moneda_pago" required></small></td>

          </tr>
          <tr>
            <td class="text-muted text-center" name="titulo"><small>MONTOP</small></td>
            <td class="text-muted text-center" name="titulo"><small>Monto a pagar</small></td>
            <td class="text-muted text-center" name="fecha_creacion"><small>Es el monto total a pagar con respecto a la relación entre los tipos de cambio entre el receptor (Cliente) y el emisor  (Covestro).</small></td>
            <td class="text-muted text-center"><small><input type="text" class="form-control" id="monto_pago" name="total_pago" required></small></td>

          </tr>
          <tr>
            <td class="text-muted text-center" name="titulo"><small>NUMEROPERP</small></td>
            <td class="text-muted text-center" name="titulo"><small>Cuenta del cliente</small></td>
            <td class="text-muted text-center" name="fecha_creacion"><small>La cuenta del cliente o el crédito que solicita el cliente. Es la referencia de pago, es un dato que manda tesoreria. Puede ser númerico o alfanumerico según el banco.</small></td>
            <td class="text-muted text-center"><small><input type="text" class="form-control" id="numero_perp" name="operacion_pago" required></small></td>

          </tr>
          <tr>
            <td class="text-muted text-center" name="titulo"><small>RFCCTABEN</small></td>
            <td class="text-muted text-center" name="titulo"><small>RFC del banco del beneficiario</small></td>
            <td class="text-muted text-center" name="fecha_creacion"><small>Es el RFC del banco de covestro</small></td>
            <td class="text-muted text-center"><small><input type="text" class="form-control" id="rfc_ctaben" name="rfc_banco_ben" required></small></td>

          </tr>
          <tr>
            <td class="text-muted text-center" name="titulo"><small>CATABEN</small></td>
            <td class="text-muted text-center" name="titulo"><small>Número de cuenta del receptor</small></td>
            <td class="text-muted text-center" name="fecha_creacion"><small>Número de cuenta de Covestro.</small></td>
            <td class="text-muted text-center"><small><input type="text" class="form-control" id="cataben" name="cuenta_ben" required></small></td>

          </tr>
          <tr>
            <td class="text-muted text-center" name="titulo"><small>FECHAPAG</small></td>
            <td class="text-muted text-center" name="titulo"><small>Fecha de pago</small></td>
            <td class="text-muted text-center" name="fecha_creacion"><small>La fecha en la que se realizó el pago.</small></td>
            <td class="text-muted text-center"><small><input type="text" class="form-control" id="fecha_pago" name="fecha_pago" required></small></td>

          </tr>
      </table>
    </div>

    <div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1">
      <p class="text-muted">Datos opcionales</p>
    </div>

    <div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1">
      <table class="table table-bordered table-hover space table-striped">
        <thead>
          <th class="text-muted text-center"><small>Datos del SAT<small></th>
          <th class="text-muted text-center"><small>Significado<small></th>
          <th class="text-muted text-center"><small>Descripción<small></th>
          <th class="text-muted text-center"><small>Palabra clave <button class="btn-icon-info" type="button" data-toggle="popover" data-placement="top" data-content="En los campos vacios se debe colocar el título de la columna del archivo 'excel' correspondiente a este dato."><i class="fas fa-info-circle"></i></button></small></th>
        </thead>

        <tr>
          <td class="text-muted text-center" name="titulo"><small>RFCCTAORD</small></td>
          <td class="text-muted text-center" name="titulo"><small>RFC del banco del cliente</small></td>
          <td class="text-muted text-center" name="fecha_creacion"><small>Es el RFC del banco del que se realizo el pago</small></td>
          <td class="text-muted text-center"><small><input type="text" class="form-control" id="rfcctaord" name="rfc_banco_cliente" required></small></td>

        </tr>
        <tr>
          <td class="text-muted text-center" name="titulo"><small>BANCOORDEXT</small></td>
          <td class="text-muted text-center" name="titulo"><small>Banco del pago</small></td>
          <td class="text-muted text-center" name="fecha_creacion"><small>Es el nombre del banco del que se realizo el pago.</small></td>
          <td class="text-muted text-center"><small><input type="text" class="form-control" id="bancoordext" name="banco_cliente" required></small></td>

        </tr>
        <tr>
          <td class="text-muted text-center" name="titulo"><small>CTAORD</small></td>
          <td class="text-muted text-center" name="titulo"><small>Número de cuenta</small></td>
          <td class="text-muted text-center" name="fecha_creacion"><small>Es el número de cuenta del que se realiza el pago. </small></td>
          <td class="text-muted text-center"><small><input type="text" class="form-control" id="ctaord" name="cuenta_cliente" required></small></td>

        </tr>
        <tr>
          <td class="text-muted text-center" name="titulo"><small>RFC_C</small></td>
          <td class="text-muted text-center" name="titulo"><small>RFC del cliente</small></td>
          <td class="text-muted text-center" name="fecha_creacion"><small>Contiene el RFC del cliente</small></td>
          <td class="text-muted text-center"><small><input type="text" class="form-control" id="rfc_c" name="rfc_cliente" required></small></td>

        </tr>
      </table>
    </div>
    <!-- Fin tabla campos opcionales -->
    <div class="row">
      <div class="col-4 col-xs-4 col-sm-4 col-md-2 col-lg-2 col-md-offset-9 col-lg-offset-9">
        <button type="submit" class="button btn-green" id="cambio"><small>Probar</small></button>
      </div>
    </div>
  </div>

</form>
<!-- Fin primer formulario -->
<div id="nuevoForm">
  <form action="javascript:cargaArchivo();" method="post" id="nuevoFormulario">
    {{ csrf_field() }}
    <!--Encabezado-->
    <div class="row row-header">
      <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <p class="text-muted lead text-left">Prueba de carga/Prueba de Tesorería <i class="far fa-copy icon-header"></i></p>
        <hr class="underline">
      </div>
    </div>
    <!-- Fin encabezado -->

    <!-- Subir archivo -->
    <div class="row">
      <div class="col-12 col-xs-12 col-sm-12 col-md-11 col-lg-11 col-md-offset-1 col-lg-offset-1">
        <p class="text-muted">Cargar archivo nuevo:</p>
      </div>
      <div class="col-12 col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-1 col-lg-offset-1">
        <div class="">
           <input type="file" name="archivo" id="archivo">
        </div>
      </div>
      <div class="col-2 col-xs-2 col-sm-2 col-md-2 col-lg-2">
        <button class="upload-field button btn-blue" type="submit"><small>Probar Archivo</small></button>
      </div>
    </div>
    <br>
    <!-- Subir archivo -->
  </form>
    <br>

  <!-- Tabla de resultados -->
  <div class="row" id="tabla">
    <div class="col-12 col-xs-12 col-sm-12 col-md-11 col-lg-11 col-md-offset-1 col-lg-offset-1" id="scroll-layout">
      <table class="display table table-bordered table-hover space table-striped" id="tablaT">
        <thead>
          <tr>
            <th class="text-muted text-center" colspan="7"><small>Campos obligatorios</small></th>
            <th class="text-muted text-center" colspan="4"><small>Campos opcionales</small></th>
          </tr>
          <tr>
            <th class="text-center text-muted"><small>RFC del cliente</small></th>
            <th class="text-center text-muted"><small>Forma de pago</small></th>
            <th class="text-center text-muted"><small>Moneda de pago</small></th>
            <th class="text-center text-muted"><small>Monto a pagar</small></th>
            <th class="text-center text-muted"><small>Cuenta de cliente</small></th>
            <th class="text-center text-muted"><small>Fecha de pago</small></th>
            <th class="text-center text-muted"><small>RFC de la cuenta del receptor</small></th>
            <th class="text-center text-muted"><small>Número de cuenta de receptor</small></th>
            <th class="text-center text-muted"><small>RFC del banco del cliente</small></th>
            <th class="text-center text-muted"><small>Banco del pago</small></th>
            <th class="text-center text-muted"><small>Número de cuenta</small></th>
          </tr>
        </thead>
        <tbody id="cuerpo developers">
          <tr>
            <td class="text-center text-muted" name="RFC_C"></td>
            <td class="text-center text-muted" name="FORMAP"><small></small></td>
            <td class="text-center text-muted" name="MONEDAP"><small></small></td>
            <td class="text-center text-muted" name="MONTOP"><small></small></td>
            <td class="text-center text-muted" name="NUMEROPERP"><small></small></td>
            <td class="text-center text-muted" name="FECHAPAG"><small></small></td>
            <td class="text-center text-muted" name="RFCCTABEN"><small></small></td>
            <td class="text-center text-muted" name="CATABEN"><small></small></td>
            <td class="text-center text-muted" name="RFCCTAORD"><small></small></td>
            <td class="text-center text-muted" name="BANCOORDEXT"><small></small></td>
            <td class="text-center text-muted" name="CTAORD"><small></small></td>
          </tr>
        </tbody>
      </table>

    </div>
    <br>

  </div>
  <br>
  <div class="row">
    <div class="col-6 col-xs-6 col-sm-6 col-md-2 col-lg-2 col-md-offset-1 col-lg-offset-1">
      <button type="button" value="Refrescar" name="button" class="button btn-pink" onclick="muestraCancela();"><small>Cancelar</small></button>
    </div>
    <div class="col-6 col-xs-6 col-sm-6 col-md-2 col-lg-2">
      <button type="button" name="button" id="cambio" class="button btn-green" onclick="muestraAlma();"><small>Guardar Layout</small></button>
    </div>
  </div>
</div>
  <!-- Fin de tabla de resultados -->

<!-- Modal -->
<div class="modal fade" id="modal-exito" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/singo1.png')}}">
          <h3><strong>Éxito</strong></h3>
          <p>
            Tu layout se ha guardado exitosamente.
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
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-error-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo2.png')}}">
          <h3><strong>Error</strong></h3>
          <p>
            Hubo un problema al guardar tu layout.
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
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-exito-actualiza" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/singo1.png')}}">
          <h3><strong>Éxito</strong></h3>
          <p>
            Tu layout se ha actualizado exitosamente.
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
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-error-actualiza" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo2.png')}}">
          <h3><strong>Error</strong></h3>
          <p>
            Hubo un problema al actualizar tu layout.
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
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-exito-elimina" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/singo1.png')}}">
          <h3><strong>Éxito</strong></h3>
          <p>
            Tu layout se ha eliminado exitosamente.
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
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-error-elimina" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo2.png')}}">
          <h3><strong>Error</strong></h3>
          <p>
            Hubo un problema al eliminar tu layout.
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
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-verifica-guardar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo.png')}}">
          <h3><strong>Guardar Layout</strong></h3>
          <p>¿Estás seguro que deseas guardar tu layout?</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-5" align="right">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-xs-7" align="left">
            <form action="javascript:guardarLayout();">
              <button type="submit" class="btn btn-primary">Aceptar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-verifica-eliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo.png')}}">
          <h3><strong>Eliminar Layout</strong></h3>
          <p>¿Estás seguro que deseas eliminar tu layout?</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-5" align="right">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-xs-7" align="left">
            <form action="javascript:eliminarLayout();">
              <input type="hidden" name="idls" id="idls" value="0">
              <button type="submit" class="btn btn-primary">Aceptar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-verifica-actualiza" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo.png')}}">
          <h3><strong>Actualizar Layout</strong></h3>
          <p>¿Estás seguro que deseas actualizar tu layout?</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-5" align="right">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-xs-7" align="left">
            <form action="javascript:guardarLayout();">
              <button type="submit" class="btn btn-primary">Aceptar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-cancela-guardar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo.png')}}">
          <h3><strong>Cancelar</strong></h3>
          <p>¿Estás seguro que deseas cancelar tu layout?</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-5" align="right">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-xs-7" align="left">
            <form action="javascript:cancelarLayout();">
              <button type="submit" class="btn btn-primary">Aceptar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-cancela-actualiza" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo.png')}}">
          <h3><strong>Cancelar</strong></h3>
          <p>¿Estás seguro que deseas cancelar la actualización de tu layout?</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-5" align="right">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-xs-7" align="left">
            <form action="javascript:cancelarLayout();">
              <input type="hidden" name="nombre_excel" id="archivo2" value="">
              <button type="submit" class="btn btn-primary">Aceptar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal -->
<!-- Modal -->
<div class="modal fade" id="modal-verifica-elimina" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo.png')}}">
          <h3><strong>Eliminar Layout</strong></h3>
          <p>¿Estás seguro que deseas eliminar tu layout?</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-xs-5" align="right">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-xs-7" align="left">
            <form id="cosas3" action="javascript:guardarDatos();">
              {{csrf_field()}}
              <input type="hidden" name="nombre_excel" id="archivo2" value="">
              <button type="submit" class="btn btn-primary">Aceptar</button>
            </form>
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
          <img src="{{asset('assets/img/cargando-loading-039.gif')}}" width="500">
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
<!-- Modal -->
<div class="modal fade" id="modal-no-archivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center>
          <img src="{{asset('assets/img/signo2.png')}}">
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
          <img src="{{asset('assets/img/signo2.png')}}">
          <h3><strong>Error</strong></h3>
          <p>Hubo un error. Revisa los datos de tu layout.</p>
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
<!-- Fin modal -->
<!-- Modal -->

<!-- Fin modal -->

<!-- Scripts -->
<script type="text/javascript">
  layout = 0;
  function guardarLayout(){
    var opciones = "";
    var lays = '<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1" id="tablaHijo">';
    lays += '<table class="display table table-bordered table-hover space table-striped" id="tablaLayouts">'+
        '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">Título del Layout</th>'+
          '<th class="text-muted text-center">Editar</th>'+
          '<th class="text-muted text-center">Eliminar</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
    var form = new FormData(document.getElementById('formprueba'));
    var ruta = "";
    if($("#op").val() == "0"){
      ruta = 'guardarLayoutTesoreria';
    }
    else{
      ruta = 'actualizarLayoutTesoreria';
    }
    $.ajax({
      url: ruta,
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      success: function(data){
        console.log(data);
        //$("#modal-exito").modal('show');
        for(var i = 0; i < data.length; i++){
          opciones+='<option value="'+data[i].id_lt+'">'+data[i].nombre+'</option>'

          lays+="<tr>"
          lays+="<td>"+data[i].nombre+"</td>"
          lays+='<td class="text-muted"><button class="button btn-transparent" type="button" onclick="edito('+data[i].id_lt+');"><i class="far fa-edit"></i></button></td>'+
            '<td class="text-muted"><button class="button btn-transparent" type="button" onclick="elimino('+data[i].id_lt+');"><i class="far fa-trash-alt"></i></button></td>';
          lays+="</tr>"
        }
        lays += '</tbody>'+
    '</table>';
    lays += '<script type="text/javascript">$("#tablaLayouts").DataTable({'+
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

            $("#layout").html("");
            $("#layout").append(opciones);

            document.getElementById("tablaPadre").removeChild(document.getElementById("tablaHijo"));
            $("#tablaPadre").append(lays);

        $("#nuevoForm").fadeOut(1000);
        $("#formprueba").fadeIn(4000);

        if($("#op").val() == "0"){
          //alert("Se guardo")
            $("#modal-verifica-guardar").modal("hide")
            $("#modal-exito").modal("show");
          }
          else{
            //alert("Se actualizo")
            $("#modal-verifica-actualiza").modal("hide");
            $("#modal-exito-actualiza").modal("show");
            $("#op").val(0);
          }

          $('#formprueba')[0].reset();
      },
      error: function(){
        if($("#op").val() == "0"){
          //alert("Se guardo")
            $("#modal-verifica-guardar").modal("hide")
            $("#modal-error").modal("show");
          }
          else{
            //alert("Se actualizo")
            $("#modal-verifica-actualiza").modal("hide");
            $("#modal-error-actualiza").modal("show");
          }
      }
    });
  }

  function desplegar(){
    alert("Entra");
    $('#prue').modal("show");
  }

  function hacerPrueba() {
    var form = new FormData(document.getElementById('formprueba'));
    if($("#op").val() == 0){
      $.ajax({
        url: 'pruebasTesoreria',
        type: 'post',
        data: form,
        processData: false,
        contentType: false,
        success: function(data){
          if(data.layout != 0){
            document.getElementById('tabla').removeChild(document.getElementById('scroll-layout'));
   var tabla = '<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1" id="scroll-layout">'+
                '<table class="display table table-bordered table-hover space table-striped" id="tablaT">'+
              '<thead>'+
                '<tr>'+
                  '<th class="text-muted text-center" colspan="7"><small>Campos obligatorios</small></th>'+
                  '<th class="text-muted text-center" colspan="4"><small>Campos opcionales</small></th>'+
                '</tr>'+
                '<tr>'+
                  '<th class="text-center text-muted"><small>RFC del cliente</br>'+$("#rfc_c").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Forma de pago</br>'+$("#forma_pago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Moneda de pago</br>'+$("#moneda_pago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Monto a pagar</br>'+$("#total_pago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Número de Operación</br>'+$("#numero_perp").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Fecha de pago</br>'+$("#fecha_pago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>RFC de la cuenta del beneficiario</br>'+$("#rfc_ctaben").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Número de cuenta del beneficiario</br>'+$("#cataben").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>RFC del banco del cliente</br>'+$("#rfcctaord").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Banco del pago</br>'+$("#bancoordext").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Número de cuenta</br>'+$("#ctaord").val()+'</small></th>'+
                '</tr>'+
              '</thead>'+
              '<tbody id="cuerpo developers">';
              tabla += '</tbody></table>';
        tabla += '<script type="text/javascript">'+
                '$("#tablaT").DataTable({'+
                  '"bFilter": false,'+
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
            '<\/script></div>';
            $("#tabla").append(tabla);
            $("#formprueba").fadeOut(1000);
            $("#nuevoForm").fadeIn(4000);

            console.log('Si estoy funcionando');
          }
          else{
            $("#modal-error").modal('show');
          }
        },
        error: function(){
          $("#modal-error").modal('show');
        }
      });
      }
    else{
      $.ajax({
        url: 'obtenerTesoreria',
        type: 'post',
        data: form,
        processData: false,
        contentType: false,
        success: function(data){
          if(data.layout != 0){
            document.getElementById('tabla').removeChild(document.getElementById('scroll-layout'));
    var tabla = '<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1" id="scroll-layout">'+
                '<table class="display table table-bordered table-hover space table-striped" id="tablaT">'+
              '<thead>'+
                '<tr>'+
                  '<th class="text-muted text-center" colspan="7"><small>Campos obligatorios</small></th>'+
                  '<th class="text-muted text-center" colspan="4"><small>Campos opcionales</small></th>'+
                '</tr>'+
                '<tr>'+
                  '<th class="text-center text-muted"><small>RFC del cliente</br>'+$("#rfc_c").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Forma de pago</br>'+$("#forma_pago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Moneda de pago</br>'+$("#moneda_pago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Monto a pagar</br>'+$("#total_pago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Número de Operación</br>'+$("#numero_perp").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Fecha de pago</br>'+$("#fecha_pago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>RFC de la cuenta del beneficiario</br>'+$("#rfc_ctaben").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Número de cuenta del beneficiario</br>'+$("#cataben").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>RFC del banco del cliente</br>'+$("#rfcctaord").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Banco del pago</br>'+$("#bancoordext").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Número de cuenta</br>'+$("#ctaord").val()+'</small></th>'+
                '</tr>'+
              '</thead>'+
              '<tbody id="cuerpo developers">';
              tabla += '</tbody></table>';
        tabla += '<script type="text/javascript">'+
                '$("#tablaT").DataTable({'+
                  '"bFilter": false,'+
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
            '<\/script></div>';
            $("#tabla").append(tabla);
            $("#formprueba").fadeOut(1000);
            $("#nuevoForm").fadeIn(4000);

            console.log('Si estoy funcionando');
          }
          else{
            $("#modal-error").modal('show');
          }
        },
        error: function(){
          $("#modal-error").modal('show');
        }
      });
    }
  }

  function cancelarLayout() {
   var form = new FormData(document.getElementById('formulario'));
    var ruta = "";
    if($("#op").val() == "0"){
      ruta = 'cancelarLayoutTesoreria';
    }
    else{
      ruta = 'cancelarActualizacionTesoreria';
    }
    $.ajax({
      url: ruta,
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      success: function(data){
        if(data.layout != 0){
          $("#nuevoForm").fadeOut(1000);
          $("#formprueba").fadeIn(4000);
          if($("#op").val() != "0"){
            $("#op").val(0);
            $("#modal-cancela-actualiza").modal("hide");
          }
          else{
            $("#modal-cancela-guardar").modal("hide");
          }

          $('#formprueba')[0].reset();
          $('#formprueba')[0].reset();
          console.log('Si estoy funcionando');
        }
        else{
          $("#modal-error").modal('show');
        }
      },
      error: function(){
        $("#modal-error").modal('show');
      }
    });
  }

  function cargaArchivo() {
    var form = new FormData(document.getElementById('nuevoFormulario'));
    var tabla = '<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1" id="scroll-layout">'+
                '<table class="display table table-bordered table-hover space table-striped" id="tablaT">'+
              '<thead>'+
                '<tr>'+
                  '<th class="text-muted text-center" colspan="7"><small>Campos obligatorios</small></th>'+
                  '<th class="text-muted text-center" colspan="4"><small>Campos opcionales</small></th>'+
                '</tr>'+
                '<tr>'+
                  '<th class="text-center text-muted"><small>RFC del cliente</br>'+$("#rfc_c").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Forma de pago</br>'+$("#forma_pago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Moneda de pago</br>'+$("#moneda_pago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Monto a pagar</br>'+$("#monto_pago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Número de Operación</br>'+$("#numero_perp").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Fecha de pago</br>'+$("#fecha_pago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>RFC de la cuenta del beneficiario</br>'+$("#rfc_ctaben").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Número de cuenta del beneficiario</br>'+$("#cataben").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>RFC del banco del cliente</br>'+$("#rfcctaord").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Banco del pago</br>'+$("#bancoordext").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>Número de cuenta</br>'+$("#ctaord").val()+'</small></th>'+
                '</tr>'+
              '</thead>'+
              '<tbody id="cuerpo developers">';
    if($("#archivo").val() == null || $("#archivo").val() == ""){
      $("#modal-no-archivo").modal("show");
    }
    else{
      $.ajax({
      url: 'probarLayoutTesoreria',
      type: 'post',
      data: form,
      dataType: "json",
      processData: false,
      contentType: false,
      beforeSend: function(){
        $("#modal-cargando").modal('show');
        $("#subir").attr("disabled", true);
        $('#cuerpo').html("");
      },
      success: function(data){
        var cod_error = "";
        var dm = "";
        if(data.respuesta == 2){
          dm = data.mensaje;
          cod_error = dm.split(":");
          if(cod_error[0] == "Undefined index")
          $("#mens").html("No se reconoce la columna "+cod_error[1]+" en el archivo probado. Asegúrate de que los nombres de tu layout NO TENGAN MAYÚSCULAS y NO TENGAN NINGUN CARACTER DIFERENTE A _");
          $("#modal-cargando").modal('hide');
          $("#modal-error").modal('show');
        }
        else{
          document.getElementById('tabla').removeChild(document.getElementById('scroll-layout'));
          //alert(data.length);
          for (var i = 0; i < data.length; i++) {
            //alert(data);
            tabla += '<tr>'+
              '<td class="text-muted">'+data[i].RFC_R+'</td>'+
              '<td class="text-muted">'+data[i].FORMAP+'</td>'+
              '<td class="text-muted">'+data[i].MONEDAP+'</td>'+
              '<td class="text-muted">'+data[i].MONTOP+'</td>'+
              '<td class="text-muted">'+data[i].NUMEROPERP+'</td>'+
              '<td class="text-muted">'+data[i].FECHAPAG+'</td>'+
              '<td class="text-muted">'+data[i].RFCCTABEN+'</td>'+
              '<td class="text-muted">'+data[i].CATABEN+'</td>'+
              '<td class="text-muted">'+data[i].RFCCTAORD+'</td>'+
              '<td class="text-muted">'+data[i].BANCOORDEXT+'</td>'+
              '<td class="text-muted">'+data[i].CTAORD+'</td>'+
              '</tr>';
          }
          tabla += '</tbody></table>';
          tabla += '<script type="text/javascript">'+
                  '$("#tablaT").DataTable({'+
                    '"bFilter": false,'+
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
              '<\/script></div>';
          $("#modal-cargando").modal('hide');
          $("#tabla").append(tabla);
        }
        
      },
      error: function(){
        $("#modal-cargando").modal('hide');
        $("#modal-error").modal('show');
      }
    });
    }
  }
  function muestraAlma() {
    if($("#op").val() == 0){
      $("#modal-verifica-guardar").modal("show");
    }
    else{
      $("#modal-verifica-actualiza").modal("show");
    }
  }
  function muestraCancela() {
    if($("#op").val() == 0){
      $("#modal-cancela-guardar").modal("show");
    }
    else{
      $("#modal-cancela-actualiza").modal("show");
    }
  }
  function edito(id_ls) {
    $.ajax({
      url: "editoTesoreria",
      type: 'get',
      data: {id: id_ls},
      success: function (data) {
        $("#op").val(data.id_lt);
        $("#titulo").val(data.nombre);
        $("#rfc_c").val(data.RFC_R)
        $("#forma_pago").val(data.FORMAP)
        $("#moneda_pago").val(data.MONEDAP)
        $("#monto_pago").val(data.MONTOP)
        $("#numero_perp").val(data.NUMEROPERP)
        $("#fecha_pago").val(data.FECHAPAG)
        $("#rfc_ctaben").val(data.RFCCTABEN)
        $("#cataben").val(data.CATABEN)
        $("#rfcctaord").val(data.RFCCTAORD)
        $("#bancoordext").val(data.BANCOORDEXT)
        $("#ctaord").val(data.CTAORD)

      },
      error: function () {
        alert("error");
      }
    })
  }
  function elimino(id_ls) {
    $("#idls").val(id_ls);
    $("#modal-verifica-eliminar").modal("show");
  }
  function eliminarLayout(){
    var lays = '<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1" id="tablaHijo">';
    lays += '<table class="display table table-bordered table-hover space table-striped" id="tablaLayouts">'+
      '<thead>'+
        '<tr>'+
          '<th class="text-muted text-center">Título del Layout</th>'+
          '<th class="text-muted text-center">Editar</th>'+
          '<th class="text-muted text-center">Eliminar</th>'+
        '</tr>'+
      '</thead>'+
      '<tbody>';
    $.ajax({
      url: 'eliminoTesoreria',
      type: 'get',
      data: {id: $("#idls").val()},
      success: function (data) {
        for(var i = 0; i < data.length; i++){
          //opciones+='<option value="'+data[i].id_lt+'">'+data[i].nombre+'</option>'

          lays+="<tr>"
          lays+="<td>"+data[i].nombre+"</td>"
          lays+='<td class="text-muted"><button class="button btn-transparent" type="button" onclick="edito('+data[i].id_lt+');"><i class="far fa-edit"></i></button></td>'+
            '<td class="text-muted"><button class="button btn-transparent" type="button" onclick="elimino('+data[i].id_lt+');"><i class="far fa-trash-alt"></i></button></td>';
          lays+="</tr>"
        }
        lays += '</tbody>'+
    '</table>';
    lays += '<script type="text/javascript">$("#tablaLayouts").DataTable({'+
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

            $("#layout").html("");
            //$("#layout").append(opciones);

            document.getElementById("tablaPadre").removeChild(document.getElementById("tablaHijo"));
            $("#tablaPadre").append(lays);
        $("#modal-verifica-eliminar").modal("hide");
        $("#modal-exito-elimina").modal("show");
      },
      error: function () {
        $("#modal-verifica-eliminar").modal("hide");
        $("#modal-error-elimina").modal("show");
      }
    })
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
    var imagen = document.getElementById('img-tesoreria').src = "{{asset('assets/img/currency-white.svg')}}";
  }

  function cambiarTesoreria() {
    var imagen = document.getElementById('img-tesoreria').src = "{{asset('assets/img/currency-white.svg')}}";
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
    var imagen = document.getElementById('img-credito').src = "{{asset('assets/img/pay.svg')}}";
  }

  function cambiarCredito() {
    var imagen = document.getElementById('img-credito').src = "{{asset('assets/img/pay-white.svg')}}";
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
    var imagen = document.getElementById('img-sap').src = "{{asset('assets/img/bank.svg')}}";
  }

  function cambiarSAP() {
    var imagen = document.getElementById('img-sap').src = "{{asset('assets/img/bank-white.svg')}}";
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
    var imagen = document.getElementById('img-covestro').src = "{{asset('assets/img/planning.svg')}}";
  }

  function cambiarCovestro() {
    var imagen = document.getElementById('img-covestro').src = "{{asset('assets/img/planning-white.svg')}}";
  }
</script>
<!-- Scripts para funcionalidad el sidemenu dropdown -->
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

  function validarcampos(){

    var forma_pago = document.getElementById('forma_pago');
    var moneda_pago = document.getElementById('moneda_pago');
    var monto_pago = document.getElementById('monto_pago');
    var numero_perp = document.getElementById('numero_perp');
    var rfc_ctaben = document.getElementById('rfc_ctaben');
    var cataben = document.getElementById('cataben');
    var fecha_pago = document.getElementById('fecha_pago');

    if(forma_pago.value == ""|| moneda_pago.value == "" || monto_pago.value== ""  || numero_perp.value =="" || rfc_ctaben.value =="" || cataben.value =="" || fecha_pago.value ==""){

    }else{
      $(document).ready(function(){
        $("#cambio").click(function(){
          $("#formulario").fadeOut(1000);
          $("#nuevoFormulario").fadeIn(4000);
          });
        });
    }
  }
</script>
<!-- Tablas Pagination -->
<script type="text/javascript" src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/dataTables.bootstrap.min.js')}}"></script>

<script type="text/javascript">
    $(document).ready( function () {
      $('#tablaT').DataTable({
        "bFilter": false,
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
      $("#nuevoForm").hide();
      $("#formprueba").show();
    } );
  </script>
@endsection