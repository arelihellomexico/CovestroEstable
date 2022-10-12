function expandTesoreria(){
  $('#newFilesTesoreria').removeClass('col-md-6');
  $('#oldFilesTesoreria').removeClass('col-md-6');
  $('#oldFilesTesoreria').removeClass('col-lg-6');
  $('#newFilesTesoreria').removeClass('col-lg-6');

  $('#newFilesTesoreria').addClass('col-md-12');
  $('#oldFilesTesoreria').addClass('col-md-0');
  $('#oldFilesTesoreria').addClass('col-lg-0');
  $('#newFilesTesoreria').addClass('col-lg-12');
}
function reduceTesoreria(){
  $('#newFilesTesoreria').removeClass('col-md-12');
  $('#oldFilesTesoreria').removeClass('col-md-0');
  $('#oldFilesTesoreria').removeClass('col-lg-0');
  $('#newFilesTesoreria').removeClass('col-lg-12');

  $('#newFilesTesoreria').addClass('col-md-6');
  $('#oldFilesTesoreria').addClass('col-md-6');
  $('#oldFilesTesoreria').addClass('col-lg-6');
  $('#newFilesTesoreria').addClass('col-lg-6');
}
function expandSAP(){
  $('#newFilesSAP').removeClass('col-md-6');
  $('#oldFilesSAP').removeClass('col-md-6');
  $('#oldFilesSAP').removeClass('col-lg-6');
  $('#newFilesSAP').removeClass('col-lg-6');

  $('#newFilesSAP').addClass('col-md-12');
  $('#oldFilesSAP').addClass('col-md-0');
  $('#oldFilesSAP').addClass('col-lg-0');
  $('#newFilesSAP').addClass('col-lg-12');
}
function reduceSAP(){
  $('#newFilesSAP').removeClass('col-md-12');
  $('#oldFilesSAP').removeClass('col-md-0');
  $('#oldFilesSAP').removeClass('col-lg-0');
  $('#newFilesSAP').removeClass('col-lg-12');

  $('#newFilesSAP').addClass('col-md-6');
  $('#oldFilesSAP').addClass('col-md-6');
  $('#oldFilesSAP').addClass('col-lg-6');
  $('#newFilesSAP').addClass('col-lg-6');
}
function mostrarMenos(){
  var elemento = document.getElementById('newFilesTesoreria').className;
  var sap = document.getElementById('newFilesSAP').className;
  console.log(elemento);
  if(elemento == "col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6" && sap == "col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6"){
    $('#newFilesTesoreria').fadeOut(700);
    $('#oldFilesTesoreria').fadeOut(700);
    setTimeout(function(){expandTesoreria()},700);
    $('#newFilesTesoreria').fadeIn(700);

    $('#newFilesSAP').fadeOut(700);
    $('#oldFilesSAP').fadeOut(700);
    setTimeout(function(){expandSAP()},700);
    $('#newFilesSAP').fadeIn(700);
  }
}
function mostrarMas(){
  var elemento = document.getElementById('oldFilesTesoreria').className;
  var sap = document.getElementById('oldFilesSAP').className;
  console.log(elemento);
  if(elemento == "col-6 col-xs-6 col-sm-6 col-md-0 col-lg-0" && sap == "col-6 col-xs-6 col-sm-6 col-md-0 col-lg-0"){
    $('#newFilesTesoreria').fadeOut(700);
    $('#oldFilesTesoreria').fadeOut(700);
    $('#newFilesTesoreria').fadeIn(700);
    setTimeout(function(){reduceTesoreria()},700);
    $('#oldFilesTesoreria').fadeIn(700);

    $('#newFilesSAP').fadeOut(700);
    $('#oldFilesSAP').fadeOut(700);
    $('#newFilesSAP').fadeIn(700);
    setTimeout(function(){reduceSAP()},700);
    $('#oldFilesSAP').fadeIn(700);
  }
}
function eliminar(num){
  document.getElementById("mis_archivos").deleteRow(1);
}
var counter = 1;
var eliminados = 0;
//Funcion para agregar row a tabla de archivos a Integrar de la tabla de tesorería new files
function agregarTesoreriaNuevos(opcion){
  cuentaTesoreria(opcion);
  if(opcion == true){
    //Obtenemos datos de la tabla de tesoreria
      var tablaArchivos = $('#tabla1 tbody tr');
      var archivo = tablaArchivos.find("td:first-child").text();
      //obtener los elementos de la tabla para agregar
      var tabla = document.getElementById('mis_archivos');

      var row = tabla.insertRow(counter);
      var celda1 = row.insertCell(0);
      var celda2 = row.insertCell(1);

      var boton = '<button type="button" value="'+counter+'" class="button btn-delete" onclick="eliminar('+counter+')"><i class="far fa-trash-alt"></i></button>';

      celda1.innerHTML = archivo+counter;
      celda2.innerHTML = boton;
      counter++;
  }
}
//Funcion para agregar row a tabla de archivos a Integrar de la tabla de tesorería old files
function agregarTesoreriaUsados(opcion){
  cuentaTesoreria(opcion);
  if(opcion == true){
    //Obtenemos datos de la tabla de tesoreria
      var tablaArchivos = $('#tabla2 tbody tr');
      var archivo = tablaArchivos.find("td:first-child").text();
      //obtener los elementos de la tabla para agregar
      var tabla = document.getElementById('mis_archivos');
      var row = tabla.insertRow(counter);
      var celda1 = row.insertCell(0);
      var celda2 = row.insertCell(1);
      var boton = '<button type="button" class="button btn-delete" onclick="eliminar('+counter+')"><i class="far fa-trash-alt"></i></button>';
      celda1.innerHTML = archivo;
      celda2.innerHTML = boton;
      counter++;
  }
}

//Funcion para agregar row a tabla de archivos a Integrar de la tabla de SAP new files
function agregarSAPNuevos(opcion){
  cuentaSAP(opcion);
  if(opcion == true){
    //Obtenemos datos de la tabla de tesoreria
      var tablaArchivos = $('#tabla3 tbody tr');
      var archivo = tablaArchivos.find("td:first-child").text();
      //obtener los elementos de la tabla para agregar
      var tabla = document.getElementById('mis_archivos');
      var row = tabla.insertRow(counter);
      var celda1 = row.insertCell(0);
      var celda2 = row.insertCell(1);
      var boton = '<button type="button" class="button btn-delete" onclick="eliminar('+counter+')"><i class="far fa-trash-alt"></i></button>';
      celda1.innerHTML = archivo;
      celda2.innerHTML = boton;
      counter++;
  }
}
//Funcion para agregar row a tabla de archivos a Integrar de la tabla de SAP old files
function agregarSAPUsados(opcion){
  cuentaSAP(opcion);
  if(opcion == true){
    //Obtenemos datos de la tabla de tesoreria
      var tablaArchivos = $('#tabla4 tbody tr');
      var archivo = tablaArchivos.find("td:first-child").text();
      //obtener los elementos de la tabla para agregar
      var tabla = document.getElementById('mis_archivos');
      var row = tabla.insertRow(counter);
      var celda1 = row.insertCell(0);
      var celda2 = row.insertCell(1);

      var boton = '<button type="button" class="button btn-delete" onclick="eliminar('+counter+')"><i class="far fa-trash-alt"></i></button>';

      celda1.innerHTML = archivo;
      celda2.innerHTML = boton;
      counter++;
  }
}

function agregarCreditoNuevos(opcion){
  cuentaCredito(opcion);
  if(opcion == true){
    //Obtenemos datos de la tabla de tesoreria
      var tablaArchivos = $('#tabla5 tbody tr');
      var archivo = tablaArchivos.find("td:first-child").text();
      //obtener los elementos de la tabla para agregar
      var tabla = document.getElementById('mis_archivos');
      var row = tabla.insertRow(counter);
      var celda1 = row.insertCell(0);
      var celda2 = row.insertCell(1);
      var boton = '<button type="button" class="button btn-delete" onclick="eliminar('+counter+')"><i class="far fa-trash-alt"></i></button>';
      celda1.innerHTML = archivo;
      celda2.innerHTML = boton;
      counter++;
  }
}
//Funcion para agregar row a tabla de archivos a Integrar de la tabla de SAP old files
function agregarCreditoUsados(opcion){
  cuentaCredito(opcion);
  if(opcion == true){
    //Obtenemos datos de la tabla de tesoreria
      var tablaArchivos = $('#tabla6 tbody tr');
      var archivo = tablaArchivos.find("td:first-child").text();
      //obtener los elementos de la tabla para agregar
      var tabla = document.getElementById('mis_archivos');
      var row = tabla.insertRow(counter);
      var celda1 = row.insertCell(0);
      var celda2 = row.insertCell(1);

      var boton = '<button type="button" class="button btn-delete" onclick="eliminar('+counter+')"><i class="far fa-trash-alt"></i></button>';

      celda1.innerHTML = archivo;
      celda2.innerHTML = boton;
      counter++;
  }
}
/*
*Calendario
*/
$("#custom-input-date").datepicker({ dateFormat:'dd/mm/yy'});

// ACTIONS
$("input").on("change", function(e) {
  $(this).siblings(".label-error").text("");
  $(this).removeClass("error");
})

$("#custom-input-date").on("focusout", function(e) {
  if($(this).val() != '') {
    dateValidation($(this));
  }
})

// CHECK
function dateValidation(input) {
  var errorLabel = input.siblings(".label-error");
  var date = input.val();

  input.removeClass("error");
  errorLabel.text("");

  var matches = /^(\d{1,2})[/\/](\d{1,2})[/\/](\d{4})$/.exec(date);
  if (matches == null) {
    input.addClass("error");
    errorLabel.text("Date not valid.");
  };

  var d = matches[1];
  var m = matches[2] - 1;
  var y = matches[3];
  var composedDate = new Date(y, m, d);

  if(composedDate.getDate() == d && composedDate.getMonth() == m && composedDate.getFullYear() == y) {} else {
    input.addClass("error");
    errorLabel.text("Date not valid.");
  }
}
