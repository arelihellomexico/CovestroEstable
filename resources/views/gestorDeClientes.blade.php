@extends('Plantilla.plantilla')
@section('title','Gestor de clientes')
@section('clientesMenu','clientes-active')

@section('contenido')
<!--Encabezado-->
<div class="first-section">
  <div class="row">
    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <h2 class="tect-left"><i class="far fa-copy"></i> Layouts Clientes</h2>
    </div>
    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <p class="text-muted lead text-left">Layouts creados<i class="far fa-copy icon-header"></i></p>
    </div>
    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <hr class="underline">
    </div>
  </div>
  <!-- Fin encabezado -->

  <!-- Botones -->
  <div class="row">
    <div class="col-6 col-xs-6 col-sm-6 col-md-4 col-lg-2 col-xs-offset-6 col-sm-offset-6 col-md-offset-8 col-lg-offset-10">
      <button type="button" class="button btn-blue" name="button" onclick="showForm()"><small><i class="far fa-copy"></i> Crear Layout Clientes</small></button>
    </div>
  </div>
  <br>
  <!-- Fin botones -->

  <!-- Tabla layouts -->
  <div class="row">
    <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1">
      <table class="display AllDataTable table table-bordered table-hover space table-striped">
        <thead>
          <tr>
            <th class="text-muted text-center"><small>Título del layout</small></th>
            <th class="text-muted text-center"><small>Fecha de creación</small></th>
            <th class="text-muted text-center"><small>Editar</small></th>
            <th class="text-muted text-center"><small>Eliminar</small></th>
          </tr>
        </thead>
        <tbody id="lays">

        </tbody>
      </table>
    </div>
  </div>
  <!-- Fin tabla layouts -->

  <!-- Seccion crear nuevo layout -->
    <div class="row" id="crear-layout">

      <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <p class="text-muted lead text-left">Nuevo Layout <i class="far fa-copy icon-header"></i></p>
      </div>
      <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <hr class="underline">
      </div>

      <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1">
        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <form action="javascript:crearLayout()" method="post" id="create_layout">

            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-4">
              <div class="form-group">
                <label for="t_layout">Titulo del Layout</label>
                <input name="nombre2_c" type="text" class="form-control" id="nombre2_c" placeholder="Titulo del layout" required>
              </div>
            </div>

            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th class="text-center"><small>Datos cliente</small></th>
                    <th class="text-center"><small>Significado</small></th>
                    <th class="text-center"><small>Descripción</small></th>
                    <th class="text-center"><small>Palabra clave
                      <button class="btn-icon-info" type="button" data-toggle="popover" data-placement="top" data-content="En los campos vacios se debe colocar el título de la columna del archivo 'excel' correspondiente a este dato."><i class="fas fa-info-circle"></i></button></small>
                    </th>
                  </tr>
                </thead>
                <label for="">Campos obligatorios</label>
                <tbody id="layouts">
                  <tr>
                    <td class="text-muted text-center"><small>ID_CLIENTE</small></td>
                    <td class="text-muted text-center"><small>Id cliente.</small></td>
                    <td class="text-muted text-center"><small>Es el número de cliente que le asigna covestro.</small></td>
                    <td class="text-muted text-center"><input type="number" class="form-control" name="id_cliente" placeholder="ID del cliente" required></td>
                  </tr>
                  <tr>
                    <td class="text-muted text-center"><small>RFC_C</small></td>
                    <td class="text-muted text-center"><small>RFC del receptor.</small></td>
                    <td class="text-muted text-center"><small>Es el RFC del cliente.</small></td>
                    <td class="text-muted text-center"><input type="text" class="form-control" name="rfc_c" placeholder="RFC del receptor" required></td>
                  </tr>
                  <tr>
                    <td class="text-muted text-center"><small>NOMBRE_C</small></td>
                    <td class="text-muted text-center"><small>Nombre del receptor</small></td>
                    <td class="text-muted text-center"><small>Es el nombre o razón social del receptor.</small></td>
                    <td class="text-muted text-center"><input type="text" class="form-control" name="nombre_c" placeholder="Nombre del cliente" required></td>
                  </tr>
                  <tr>
                    <td class="text-muted text-center"><small>CPOSTAL_C</small></td>
                    <td class="text-muted text-center"><small>Código Postal</small></td>
                    <td class="text-muted text-center"><small>Es el código postal del cliente.</small></td>
                    <td class="text-muted text-center"><input type="text" class="form-control" name="cpostal_c" placeholder="Código Postal" required></td>
                  </tr>
                  <tr>
                    <td class="text-muted text-center"><small>PAIS_C</small></td>
                    <td class="text-muted text-center"><small>Pais</small></td>
                    <td class="text-muted text-center"><small>Es el país del cliente.</small></td>
                    <td class="text-muted text-center"><input type="text" class="form-control" name="pais_c" placeholder="País del cliente" required></td>
                  </tr>
                  <tr>
                    <th colspan="4" class="active">Campos opcionales</th>
                  </tr>

                  <tr>
                    <td class="text-muted text-center"><small>NOMBRE2_C</small></td>
                    <td class="text-muted text-center"><small>Nombre</small></td>
                    <td class="text-muted text-center"><small>Es el nombre o razón social del cliente.</small></td>
                    <td class="text-muted text-center"><input type="text" class="form-control" name="nombre2_c" placeholder="Nombre" onkeyup="upperCase(this)"></td>
                  </tr>
                  <tr>
                    <td class="text-muted text-center"><small>DIRECCIÓN</small></td>
                    <td class="text-muted text-center"><small></small></td>
                    <td class="text-muted text-center"><small>Es el domicilio fiscal del cliente. </small></td>
                    <td class="text-muted text-center"><input type="text" class="form-control" name="direccion_c" placeholder="Dirección" onkeyup="upperCase(this)"></td>
                  </tr>
                  <tr>
                    <td class="text-muted text-center"><small>TELEFONO</small></td>
                    <td class="text-muted text-center"><small></small></td>
                    <td class="text-muted text-center"><small>Es el telefono del cliente. </small></td>
                    <td class="text-muted text-center"><input type="text" class="form-control" name="telefono_c" placeholder="Telefono" onkeyup="upperCase(this)"></td>
                  </tr>
                  <tr>
                    <td class="text-muted text-center"><small>LOCALIDAD_C</small></td>
                    <td class="text-muted text-center"><small></small></td>
                    <td class="text-muted text-center"><small>Es la localidad del cliente. </small></td>
                    <td class="text-muted text-center"><input type="text" class="form-control" name="localidad_c" placeholder="Localidad del cliente" onkeyup="upperCase(this)"></td>
                  </tr>
                  <tr>
                    <td class="text-muted text-center"><small>MUNICIPIO_C</small></td>
                    <td class="text-muted text-center"><small></small></td>
                    <td class="text-muted text-center"><small>Es el nombre dle municipio del cliente. </small></td>
                    <td class="text-muted text-center"><input type="text" class="form-control" name="municipio_c" placeholder="Municipio" onkeyup="upperCase(this)"></td>
                  </tr>
                  <tr>
                    <td class="text-muted text-center"><small>ESTADO_C</small></td>
                    <td class="text-muted text-center"><small></small></td>
                    <td class="text-muted text-center"><small>Es el estado de residencia fiscal del cliente. </small></td>
                    <td class="text-muted text-center"><input type="text" class="form-control" name="estado_c" placeholder="Estado" onkeyup="upperCase(this)"></td>
                  </tr>
                  <tr>
                    <td class="text-muted text-center"><small>RESIDENCIAFISCAL</small></td>
                    <td class="text-muted text-center"><small></small></td>
                    <td class="text-muted text-center"><small>Es la residencia fiscal del cliente. </small></td>
                    <td class="text-muted text-center"><input type="text" class="form-control" name="residenciafiscal" placeholder="Residencia Fiscal" onkeyup="upperCase(this)"></td>
                  </tr>
                  <tr>
                    <td class="text-muted text-center"><small>NUMREGIDTRIB</small></td>
                    <td class="text-muted text-center"><small></small></td>
                    <td class="text-muted text-center"><small>Número de registro tributario. </small></td>
                    <td class="text-muted text-center"><input type="text" class="form-control" name="numregidtrib" placeholder="Número de registro tributario" onkeyup="upperCase(this)"></td>
                  </tr>
                  
                </tbody>
              </table>
            </div>
            <div class="row">
              <div class="col-6 col-sx-6 col-sm-6 col-md-4 col-lg-2 col-xl-2 col-offset-6 col-xs-offset-6 col-sm-offset-6 col-md-offset-8 col-lg-offset-10 col-xl-offset-10">
                <button type="submit" name="button" class="button btn-green"><small>Probar</small></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
<!--div class="second-section">
  <div class="first-section">
    <div class="row">
      <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h2 class="text-left"><i class="icon-people"></i> Gestor Clientes</h2>
      </div>
      <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <p class="text-muted lead text-left">Subir archivo <i class="icon-people icon-header"></i></p>
      </div>
      <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <hr class="underline">
      </div>
    </div>

</div-->
<!-- Fin seccion crear nuevo layout -->
@endsection
<script type="text/javascript">
  function upperCase(e){
    e.value = e.value.toUpperCase();
  }
  function guardarClientes(){
    var form = new FormData(document.getElementById('formulario'));
    var contenido = "";
    $.ajax({
      url: 'guardarClientes',
      type: 'post',
      data: form,
      processData: false,
      contentType: false,
      success: function(data){
        if(data.length < 1){
          console.log("No habia datos en tu archivo de excel.");
        }
        else{
          for(var i=0; i<data.length; i++){
            contenido+='<tr>';
            if(data[i].RFCCTAORD == null || (data[i].RFCCTAORD.length == 12 && esRFC(data[i].RFCCTAORD) == false)){
              contenido+='<td class="text-muted">'+data[i].RFCCTAORD+'</td>';
            }
            else{
              contenido+='<td class="text-muted" style="background-color: #FA9A85; color: red;">'+data[i].RFCCTAORD+'</td>';
            }
            if(data[i].RFC_R == null || data[i].RFC_R.length == 12 ){
              contenido+='<td class="text-muted">'+data[i].RFC_R+'</td>';
            }
            else{
              contenido+='<td class="text-muted" style="background-color: #FA9A85; color: red;">'+data[i].RFC_R+'</td>';
            }
            contenido+='<td class="text-muted">'+data[i].BANCOORDEXT+'</td>';
            if(data[i].FECHAPAG != null){
              contenido+='<td class="text-muted">'+data[i].FECHAPAG+'</td>';
            }
            else{
              contenido+='<td class="text-muted" style="background-color: #FA9A85; color: red;">'+data[i].FECHAPAG+'</td>';
            }
            if(data[i].CTAORD == null || soloNumeros(data[i].CTAORD) == false){
              contenido+='<td class="text-muted">'+data[i].CTAORD+'</td>';
            }
            else{
              contenido+='<td class="text-muted" style="background-color: #FA9A85; color: red;">'+data[i].CTAORD+'</td>';
            }
            contenido+='<td class="text-muted">'+data[i].FORMAP+'</td>';
            if(data[i].MONEDAP != null && data[i].MONEDAP.length == 3 && soloLetras(data[i].MONEDAP) == false){
              contenido+='<td class="text-muted">'+data[i].MONEDAP+'</td>';
            }
            else{
              contenido+='<td class="text-muted" style="background-color: #FA9A85; color: red;">'+data[i].MONEDAP+'</td>';
            }
            if(data[i].MONTOP != null && monto(data[i].MONTOP) == false){
              contenido+='<td class="text-muted">'+data[i].MONTOP+'</td>';
            }
            else{
              contenido+='<td class="text-muted" style="background-color: #FA9A85; color: red;">'+data[i].MONTOP+'</td>';
            }
            contenido+='<td class="text-muted">'+data[i].NUMEROPERP+'</td>';
            if(data[i].CATABEN != null && soloNumeros(data[i].CATABEN) == false){
              contenido+='<td class="text-muted">'+data[i].CATABEN+'</td>';
            }
            else{
              contenido+='<td class="text-muted" style="background-color: #FA9A85; color: red;">'+data[i].CATABEN+'</td>';
            }
            contenido+='<tr>';

            $('#cuerpo').html("");
            $('#cuerpo').append(contenido);
          }
        }
      },
      error: function(){
        console.log("Error al guardar la prueba")
        $("#modal-falta").modal('show');
      }
    });
  }
  function showForm(){
    $('#crear-layout').fadeIn('2000');
  }
</script>
