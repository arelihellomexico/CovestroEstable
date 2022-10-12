window.addEventListener("load", start, false);

function start(){
  var soltar = document.getElementById("destinationArea");
  var nombre = document.getElementById("text-drop");
  soltar.addEventListener("dragenter", function(e){
    e.preventDefault();},false);
    soltar.addEventListener("dragover", function(e){
      e.preventDefault();},false);
    soltar.addEventListener("drop",soltado,false);
}
  function soltado(e){
    e.preventDefault();

    var archivo = e.dataTransfer.files;
    var listado = "";

    for(var i=0;i<archivo.lenght;i++){
      listado=archivo[i].name;
    }

    nombre = innerHTML = listado;
  }
