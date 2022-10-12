function getFile(){
  document.getElementById("upfile").click();
}
function sub(obj){
   var file = obj.value;
   var fileName = file.split("\\");
   document.getElementById("yourBtn").innerHTML = fileName[fileName.length-1];
   document.myForm.submit();
   event.preventDefault();
 }
 /*Area drag & drop*/
function getfileDrop(){
  document.getElementById("fileDrop").drop();
}
function sub(obj){
   var fileDrop = obj.value;
   var fileNameDrop = fileDrop.split("\\");
   document.getElementById("yourBtn").innerHTML = fileNameDrop[fileNameDrop.length-1];
   document.myForm.submit();
   event.preventDefault();
 }
