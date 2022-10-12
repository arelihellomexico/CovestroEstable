<?php
   $serverName = "localhost"; //serverName\instanceName
   $connectionInfo = array( "Database"=>"Complemento_pago", "UID"=>"sa", "PWD"=>"12345");
   $conn = sqlsrv_connect( $serverName, $connectionInfo);
   
   if( $conn ) {
        echo "Conexión establecida.<br />";
   }else{
        echo "Conexión no se pudo establecer.<br />";
        die( print_r( sqlsrv_errors(), true));
   }
   /*$sql='SELECT TOP 5 * FROM usuarios';
   foreach($conn->query($sql)as $row){
       echo $row;
   }*/

?>