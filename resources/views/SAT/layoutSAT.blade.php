@extends('Plantilla.plantilla')
@if(Session::get('tipo') != 1)
@section('title','Layout SAP')
@section('usuario','Usuario SAP')
@section('validacionMenu','no-mostrar')
@section('tesoreriaMenu','no-mostrar')
@section('creditoMenu','no-mostrar')
@section('concentradoMenu','no-mostrar')
@section('clientesMenu','no-mostrar')
@section('covestroMenu','no-mostrar')
@else
@section('title','Usuario Administrador')
@endif
@section('sapMenu','sap-active')
@section('contenido')
<!--Encabezado-->
<div class="row row-header">
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <h2 class="tect-left"><i class="far fa-copy"></i> <strong>SAP</strong> Layout</h2>
  </div>
  <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <p class="text-muted lead text-left">Layouts creados <i class="far fa-copy icon-header"></i></p>
    <hr class="underline">
  </div>
</div>
<!-- Fin encabezado -->

<div class="row">
  <div class="col-6 col-xs-6 col-sm-6 col-md-3 col-lg-2 col-xs-offset-6 col-sm-offset-6 col-md-offset-9 col-lg-offset-10">
    <!--button type="button" class="button btn-blue" name="button"><i class="far fa-copy"></i> Crear SAP Layout</button-->
  </div>
</div>
<br>
<form id="formulario" action="javascript:guardarLayout();" method="post">
  {{csrf_field()}}

<div class="row" id="tablaPadre">
  <div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1" id="tablaHijo">
    <table class="display AllDataTable table table-bordered table-hover space table-striped">
      <thead>
        <th class="text-muted text-center"><small>Título del layout</small></th>
        <th class="text-muted text-center"><small>Editar</small></th>
        <th class="text-muted text-center"><small>Eliminar</small></th>
      </thead>
      <tbody id="cuerpo">
        @foreach($layout as $l)
          <tr>
            <td class="text-muted">{{ $l->nombre }}</td>
            <td class="text-muted"><button class="button btn-transparent" type="button" onclick="edito({{$l->id_ls}});"><i class="far fa-edit"></i></button></td>
            <td class="text-muted"><button class="button btn-transparent" type="button" onclick="elimino({{$l->id_ls}});"><i class="far fa-trash-alt"></i></button></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
</form>

<!-- form datos -->
<form class="" action="javascript:hacerPrueba();" method="post" id="formprueba" name="formprueba">
    {{csrf_field()}}
  <input type="hidden" id="op" name="op" value="0">
  <br><br>
  <!--Encabezado-->
  <div class="row row-header">

    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <p class="text-muted lead text-left">Creación de Layouts <i class="far fa-copy icon-header"></i></p>
      <p class="text-muted lead text-left" style="font-size: 16px;">Los nombres de los datos deben estar escritos en minúscula y solo se permite "_" (guión bajo) para la separación de las palabras. NO DEBES USAR MAYÚSCULAS NI SÍMBOLOS QUE NO SEAN ALFABÉTICOS. </p>
      <hr class="underline">
    </div>
  </div>
  <!-- Fin encabezado -->

  <!-- Nombre Layout -->
  <div class="row">
    <div class="col-6 col-xs-6 col-sm-6 col-md-5 col-lg-4 col-md-offset-1 col-lg-offset-1">
      <div class="form-group newLayout">
        <label for="newLayout" class="text-muted">Título del layout</label>
        <input type="text" class="form-control" id="newLayout" name="nombre" required>
      </div>
      <div class="form-group newLayout">
        <label for="newLayout" class="text-muted">Hoja de Excel donde se encuentra la información de SAP</label>
        <input type="text" class="form-control" id="hojaSap" name="hojaSap" required>
      </div>
      <div class="form-group newLayout">
        <label for="newLayout" class="text-muted">Hoja de Excel donde se encuentra la información de Bancos</label>
        <input type="text" class="form-control" id="hojaBancos" name="hojaBancos" required>
      </div>
    </div>
    <div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1">
      <p class="text-muted">Campos obligatorios</p>
    </div>
  </div>
  <!-- Fin nombre layout -->

  <!-- Tabla -->
    <div class="row">
      <div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1">
        <table class="table table-bordered table-hover space table-striped">
          <thead>
            <th class="text-muted text-center"><small>Datos SAP</small></th>
            <th class="text-muted text-center"><small>Significado</small></th>
            <th class="text-muted text-center"><small>Description</small></th>
            <th class="text-muted text-center"><small>Palabra clave
              <button class="btn-icon-info" type="button" data-toggle="popover" data-placement="top" data-content="En los campos vacios se debe colocar el título de la columna del archivo 'excel' correspondiente a este dato."><i class="fas fa-info-circle"></i></button></small>
            </th>

          </thead>
          <tbody>
            <tr>
              <td class="text-muted text-center"><small>ID_CLIENTE</small></td>
              <td class="text-muted text-center"><small>ID del Cliente</small></td>
              <td class="text-muted text-center"><small>Es el ID que covestro asigna al cliente.</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="idcliente" name="id_cliente" required></small></td>
            </tr>
            <tr>
              <td class="text-muted text-center"><small>FOLIO</small></td>
              <td class="text-muted text-center"><small>Clearing Document</small></td>
              <td class="text-muted text-center"><small>Es el clearing document.</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="folio" name="clearing" required></small></td>
            </tr>
            <tr>
              <td class="text-muted text-center"><small>TIPOCAMBIOP</small></td>
              <td class="text-muted text-center"><small>Tipo de cambio.</small></td>
              <td class="text-muted text-center"><small>Tipo de cambio del pago.</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="tipocambiop" name="tipocambio_pago" required></small></td>

            </tr>
            <tr>
              <td class="text-muted text-center"><small>MONTOPAGO</small></td>
              <td class="text-muted text-center"><small>Monto de pago</small></td>
              <td class="text-muted text-center"><small>Es el monto del pago.</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="montopago" name="monto_pago" required></small></td>

            </tr>
            <tr>
              <td class="text-muted text-center"><small>MONEDAPAGO</small></td>
              <td class="text-muted text-center"><small>Moneda del pago</small></td>
              <td class="text-muted text-center"><small>Es la moneda en la que se realiza el pago</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="monedapago" name="moneda_pago" required></small></td>

            </tr>
            <tr>
              <td class="text-muted text-center"><small>FOLIO (CFDIREL)</small></td>
              <td class="text-muted text-center"><small>Folio de la factura</small></td>
              <td class="text-muted text-center"><small>Número de factura es actualmente de caracteres.</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="cfdirel" name="folios" required></small></td>

            </tr>
            <tr>
              <td class="text-muted text-center"><small>PARCIALIDADES</small></td>
              <td class="text-muted text-center"><small>Parcialidades</small></td>
              <td class="text-muted text-center"><small>Son las parcialidades de las facturas asociadas.</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="parcialidad" name="parcialidad" required></small></td>

            </tr>
            <tr>
              <td class="text-muted text-center"><small>DTIPO</small></td>
              <td class="text-muted text-center"><small>Tipo de Documento</small></td>
              <td class="text-muted text-center"><small>Menciona el tipo de documento (DZ, RV, RW) que se está tratando (Pago, Factura, Parcialidad, Nota de crédito)</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="dtipo" name="dtipo" required></small></td>
            </tr>
            <tr>
              <td class="text-muted text-center"><small>NUMREGIDTRIB</small></td>
              <td class="text-muted text-center"><small>Número de registro Tributario.</small></td>
              <td class="text-muted text-center"><small>Se captura el número de registro de identidad.</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="numregidtrib" name="numregidtrib" required></small></td>

            </tr>
            <tr>
              <td class="text-muted text-center"><small>FECHAP</small></td>
              <td class="text-muted text-center"><small>Fecha del pago</small></td>
              <td class="text-muted text-center"><small>Es la fecha en la que se realiza el pago.</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="fechapago" name="fecha_pago" required></small></td>

            </tr>
            <tr>
              <td class="text-muted text-center"><small>REFERENCE</small></td>
              <td class="text-muted text-center"><small>Reference</small></td>
              <td class="text-muted text-center"><small>Menciona a qué documento hace referencia, si se trata de una nota de crédito</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="reference" name="reference" required></small></td>

            </tr>
            <tr>
              <td class="text-muted text-center"><small>ASSIGNMENT</small></td>
              <td class="text-muted text-center"><small>Assignment</small></td>
              <td class="text-muted text-center"><small>Se utiliza para checar los datos bancarios del pago, si es que no hay relación con los pagos de tesorería.</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="assignment" name="assignment" required></small></td>

            </tr>
            <tr>
              <td class="text-muted text-center"><small>MONTOMXN</small></td>
              <td class="text-muted text-center"><small>Monto de pago en pesos mexicanos</small></td>
              <td class="text-muted text-center"><small>Es el monto del pago en moneda local (peso mexicano).</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="montomxnpago" name="montomxn_pago" required></small></td>

            </tr>
            <tr>
              <td class="text-muted text-center"><small>IMPUESTO</small></td>
              <td class="text-muted text-center"><small>Impuesto</small></td>
              <td class="text-muted text-center"><small>Es el tipo de impuesto que se le aplica a la factura.</small></td>
              <td class="text-muted text-center"><small><input type="text" class="form-control" id="impuesto" name="impuesto" required></small></td>

            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Boton probar -->
    <div class="row">
      <div class="col-6 col-xs-6 col-sm-6 col-md-2 col-lg-2 col-xs-offset-6 col-sm-offset-6 col-md-offset-9 col-lg-offset-9">
          <button type="submit" name="button" id="cambio" class="button btn-green">Probar</button>
      </div>
    </div>
    <!-- Fin boton probar -->

</form><!-- Envio de datos formulario -->

<!-- Mostrar datos -->
<div id="nuevoForm">

  <form action="javascript:cargaArchivo();" method="post" id="nuevoFormulario">
    {{ csrf_field() }}
    <!--Encabezado-->
    <div class="row row-header">

      <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <p class="text-muted lead text-left">Prueba de carga/Prueba de SAP <i class="far fa-copy icon-header"></i></p>
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
    <!-- Tabla de resultados -->
    <div class="row" id="tabla">
      <div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1 scrollbar" id="scroll-layout">
        <table class="display table table-bordered table-hover space table-striped" id="tablaT">
          <thead>
            <tr>
              <th class="text-center text-muted"><small>ID CLIENTE</small></th>
              <th class="text-center text-muted"><small>FOLIO</small></th>
              <th class="text-center text-muted"><small>TIPOCAMBIO</small></th>
              <th class="text-center text-muted"><small>MONTOPAGO</small></th>
              <th class="text-center text-muted"><small>MONEDAPAGO</small></th>
              <th class="text-center text-muted"><small>FOLIO (CFDIREL)</small></th>
              <th class="text-center text-muted"><small>PARCIALIDADES</small></th>
              <th class="text-center text-muted"><small>DTIPO</small></th>
              <th class="text-center text-muted"><small>NUMREGIDTRIB</small></th>
              <th class="text-center text-muted"><small>FECHAPAGO</small></th>
              <th class="text-center text-muted"><small>REFERENCE</small></th>
              <th class="text-center text-muted"><small>ASSIGNMENT</small></th>
              <th class="text-center text-muted"><small>MONTOPAGOMXN</small></th>
              <th class="text-center text-muted"><small>IDDOC</small></th>
            </tr>
          </thead>
          <tbody id="cuerpo developers">

          </tbody>
        </table>

      </div>
    </div>
    <br>
    <!-- Fin de tabla de resultados -->

    <!-- Boton probar -->
    <div class="row">
        <div class="col-6 col-xs-6 col-sm-6 col-md-2 col-lg-2 col-md-offset-1 col-lg-offset-1">
          <button type="button" value="Refrescar" name="button" class="button btn-pink" onclick="muestraCancela();"><small>Cancelar</small></button>
        </div>
        <div class="col-6 col-xs-6 col-sm-6 col-md-2 col-lg-2">
          <button type="button" name="button" id="cambio" class="button btn-green" onclick="muestraAlma();"><small>Guardar Layout</small></button>
        </div>
    </div>
    <!-- Fin boton probar -->

</div>


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
<!--modal -->
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

<!-- Scritps -->
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
      ruta = 'guardarLayoutSAP';
    }
    else{
      ruta = 'actualizarLayoutSAP';
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
          lays+='<td class="text-muted"><button class="button btn-transparent" type="button" onclick="edito('+data[i].id_ls+');"><i class="far fa-edit"></i></button></td>'+
            '<td class="text-muted"><button class="button btn-transparent" type="button" onclick="elimino('+data[i].id_ls+');"><i class="far fa-trash-alt"></i></button></td>';
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
        $("#modal-error").modal('show');
      }
    });
  }

  function desplegar(){
    alert("Entra");
    $('#prue').modal("show");
  }

  function hacerPrueba() {
    var form = new FormData(document.getElementById('formprueba'));
    if($("#op").val() == "0"){
      $.ajax({
        url: 'pruebasSAP',
        type: 'post',
        data: form,
        processData: false,
        contentType: false,
        success: function(data){
          if(data.layout != 0){
            document.getElementById('tabla').removeChild(document.getElementById('scroll-layout'));
            var tabla = '<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1 scrollbar" id="scroll-layout">'+
            '<table class="display table table-bordered table-hover space table-striped" id="tablaT">'+
              '<thead>'+
                '<tr>'+
                  '<th class="text-center text-muted"><small>ID CLIENTE<br/>'+$("#idcliente").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>CLEARING<br/>'+$("#folio").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>TIPOCAMBIO<br/>'+$("#tipocambiop").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>MONTOPAGO<br/>'+$("#montopago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>MONEDAPAGO<br/>'+$("#monedapago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>FOLIO (CFDIREL)<br/>'+$("#cfdirel").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>PARCIALIDADES<br/>'+$("#parcialidad").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>DTIPO<br/></small>'+$("#dtipo").val()+'</th>'+
                  '<th class="text-center text-muted"><small>NUMREGIDTRIB<br/>'+$("#numregidtrib").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>FECHAPAGO<br/>'+$("#fechapago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>REFERENCE<br/>'+$("#assignment").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>ASSIGNMENT<br/>'+$("#reference").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>MONTOPAGOMXN<br/>'+$("#montomxnpago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>IMPUESTO<br/>'+$("#impuesto").val()+'</small></th>'+
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
      url: 'obtenerSAP',
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      success: function(data){
        if(data.layout != 0){
          document.getElementById('tabla').removeChild(document.getElementById('scroll-layout'));
           var tabla = '<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1 scrollbar" id="scroll-layout">'+
            '<table class="display table table-bordered table-hover space table-striped" id="tablaT">'+
              '<thead>'+
                '<tr>'+
                  '<th class="text-center text-muted"><small>ID CLIENTE<br/>'+$("#idcliente").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>CLEARING<br/>'+$("#folio").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>TIPOCAMBIO<br/>'+$("#tipocambiop").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>MONTOPAGO<br/>'+$("#montopago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>MONEDAPAGO<br/>'+$("#monedapago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>FOLIO (CFDIREL)<br/>'+$("#cfdirel").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>PARCIALIDADES<br/>'+$("#parcialidad").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>DTIPO<br/></small>'+$("#dtipo").val()+'</th>'+
                  '<th class="text-center text-muted"><small>NUMREGIDTRIB<br/>'+$("#numregidtrib").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>FECHAPAGO<br/>'+$("#fechapago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>REFERENCE<br/>'+$("#assignment").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>ASSIGNMENT<br/>'+$("#reference").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>MONTOPAGOMXN<br/>'+$("#montomxnpago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>IMPUESTO<br/>'+$("#impuesto").val()+'</small></th>'+
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
      ruta = 'cancelarLayoutSAP';
    }
    else{
      ruta = 'cancelarActualizacionSAP';
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
    var tabla = '<div class="col-12 col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1 scrollbar" id="scroll-layout">'+
            '<table class="display table table-bordered table-hover space table-striped" id="tablaT">'+
              '<thead>'+
                '<tr>'+
                  '<th class="text-center text-muted"><small>ID CLIENTE<br/>'+$("#idcliente").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>CLEARING<br/>'+$("#folio").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>TIPOCAMBIO<br/>'+$("#tipocambiop").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>MONTOPAGO<br/>'+$("#montopago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>MONEDAPAGO<br/>'+$("#monedapago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>FOLIO (CFDIREL)<br/>'+$("#cfdirel").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>PARCIALIDADES<br/>'+$("#parcialidad").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>DTIPO<br/></small>'+$("#dtipo").val()+'</th>'+
                  '<th class="text-center text-muted"><small>NUMREGIDTRIB<br/>'+$("#numregidtrib").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>FECHAPAGO<br/>'+$("#fechapago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>REFERENCE<br/>'+$("#assignment").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>ASSIGNMENT<br/>'+$("#reference").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>MONTOPAGOMXN<br/>'+$("#montomxnpago").val()+'</small></th>'+
                  '<th class="text-center text-muted"><small>IMPUESTO<br/>'+$("#impuesto").val()+'</small></th>'+
                '</tr>'+
              '</thead>'+
              '<tbody id="cuerpo developers">';
    if($("#archivo").val() == null || $("#archivo").val() == ""){
      $("#modal-no-archivo").modal("show");
    }
    else{
      $.ajax({
      url: 'probarLayoutSAP',
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
          if(cod_error[0] == "Undefined index"){
            $("#mens").html("No se reconoce la columna "+cod_error[1]+" en la hoja "+$("#hojaSap")+" de tu excel. Asegúrate de que los nombres de tu layout NO TENGAN MAYÚSCULAS y NO TENGAN NINGUN CARACTER DIFERENTE A _, y que coincidan los nombres en ambas hojas");
          }
          else{
            $("#mens").html(dm);
          }
          $("#modal-cargando").modal('hide');
          $("#modal-error").modal('show');
        }
        else{
          document.getElementById('tabla').removeChild(document.getElementById('scroll-layout'));
          //alert(data.length);
          for (var i = 0; i < data.length; i++) {
            //alert(data);
            tabla += '<tr>'+
              '<td class="text-muted">'+data[i].id_cliente+'</td>'+
              '<td class="text-muted">'+data[i].FOLIO+'</td>'+
              '<td class="text-muted">'+data[i].TIPOCAMBIOP+'</td>'+
              '<td class="text-muted">'+data[i].MONTOPAGO+'</td>'+
              '<td class="text-muted">'+data[i].MONEDAPAGO+'</td>'+
              '<td class="text-muted">'+data[i].FOLIOS+'</td>'+
              '<td class="text-muted">'+data[i].PARCIAL+'</td>'+
              '<td class="text-muted">'+data[i].TIPODOC+'</td>'+
              '<td class="text-muted">'+data[i].NUMREGIDTRIB+'</td>'+
              '<td class="text-muted">'+data[i].FECHADOC+'</td>'+
              '<td class="text-muted">'+data[i].REFERENCE+'</td>'+
              '<td class="text-muted">'+data[i].ASSIGNMENT+'</td>'+
              '<td class="text-muted">'+data[i].MONTOPAGOMXN+'</td>'+
              '<td class="text-muted">'+data[i].TAX+'</td>'+
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
      error: function(respuesta){
        console.log(respuesta);
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
      url: 'editoSAP',
      type: 'get',
      data: {id: id_ls},
      success: function (data) {
          $("#op").val(data.id_ls);
          $("#newLayout").val(data.nombre);
          $("#hojaSap").val(data.hoja_sap);
          $("#hojaBancos").val(data.hoja_bancos);
          $("#idcliente").val(data.ID)
          $("#folio").val(data.FOLIO)
          $("#tipocambiop").val(data.TIPOCAMBIOP)
          $("#montopago").val(data.MONTOPAGO)
          $("#monedapago").val(data.MONEDAPAGO)
          $("#cfdirel").val(data.FOLIOS)
          $("#parcialidad").val(data.PARCIAL)
          $("#dtipo").val(data.TIPODOC)
          $("#numregidtrib").val(data.NUMREGIDTRIB)
          $("#fechapago").val(data.FECHAPAGO)
          $("#assignment").val(data.ASSIGNMENT)
          $("#reference").val(data.REFERENCE)
          $("#montomxnpago").val(data.MONTOPAGOMXN)
          $("#impuesto").val(data.IMPUESTO)

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
    var opciones = ""
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
      url: 'eliminoSAP',
      type: 'get',
      data: {id: $("#idls").val()},
      success: function (data) {
        //$("#modal-exito").modal('show');
        for(var i = 0; i < data.length; i++){
          opciones+='<option value="'+data[i].id_lt+'">'+data[i].nombre+'</option>'

          lays+="<tr>"
          lays+="<td>"+data[i].nombre+"</td>"
          lays+='<td class="text-muted"><button class="button btn-transparent" type="button" onclick="edito('+data[i].id_ls+');"><i class="far fa-edit"></i></button></td>'+
            '<td class="text-muted"><button class="button btn-transparent" type="button" onclick="elimino('+data[i].id_ls+');"><i class="far fa-trash-alt"></i></button></td>';
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
      error: function (res) {
        console.log(res);
        $("#modal-verifica-eliminar").modal("hide");
        $("#modal-error-elimina").modal("show");
      }
    })
  }
</script>
<!-- Funcion para cambio de atributo sidemenu -->
<!--script type="text/javascript">
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

</script-->
<!--script type="text/javascript">
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
</script-->
<script type="text/javascript">
  window.addEventListener('load', iconsap, false);

  function iconsap(){
    var contenedorSAP = document.getElementById('sap');
    contenedorSAP.addEventListener('mouseover', cambiarSAP, false);
    contenedorSAP.addEventListener('mouseout', restaurarSAP, false);
  }

  function restaurarSAP(){
    var imagen = document.getElementById('img-sap').src = "{{asset('assets/img/bank-white.svg')}}";
  }

  function cambiarSAP() {
    var imagen = document.getElementById('img-sap').src = "{{asset('assets/img/bank-white.svg')}}";
  }
</script>
<!--script type="text/javascript">
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
</script-->
<!-- Scripts para funcionalidad el sidemenu dropdown -->
<script type="text/javascript">
    //Tesoreria
    /*document.getElementById("tesoreria").onclick = function() {tesoreria()};
    function tesoreria() {
      document.getElementById("DropdownTesoreria").classList.toggle("show");
    }*/
    //Credito y cobranza
    /*document.getElementById("credito").onclick = function() {credito()};
    function credito(){
      document.getElementById("DropdownCredito").classList.toggle("show");
    }*/
    //SAP
    document.getElementById("sap").onclick = function() {sap()};
    function sap(){
      document.getElementById("DropdownSAP").classList.toggle("show");
    }
</script>

<script type="text/javascript">

  function validarcampos(){

    var id_cliente = document.getElementById('idcliente');
    var folio = document.getElementById('folio');
    var tipo_cambio = document.getElementById('tipocambiop');
    var monto_pago = document.getElementById('montopago');
    var moneda_pago = document.getElementById('monedapago');
    var folio_cfdirel = document.getElementById('cfdirel');
    var parcialidad = document.getElementById('parcialidad');

    if(id_cliente.value == ""|| folio.value == "" || tipo_cambio.value== ""  || monto_pago.value =="" || moneda_pago.value =="" || folio_cfdirel.value =="" || parcialidad.value ==""){

    }else{
      $(document).ready(function(){
        $("#cambio").click(function(){
          $("#cambioFormulario").fadeOut(1000);
          $("#nuevoFormulario").fadeIn(4000);

          console.log('Si estoy funcionando');
          });
        });
    }
  }

  /*popover*/
  $(function () {
    $('[data-toggle="popover"]').popover()
  })
</script>

<!-- Tablas Pagination -->
<!--script type="text/javascript">
$(document).ready( function () {
  $('').DataTable({
    language:{
      "ordering": false,
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
</script-->
<!-- Fin Scripts -->
@endsection