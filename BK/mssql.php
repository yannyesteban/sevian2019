<?php
// Server in the this format: <computer>\<instance name> or 
// <server>,<port> when using a non default port number

//dl('php_pdo_sqlsrv_7_ts.dll');  
$server = 'DV7-YANNY\SQLEXPRESS';

// Connect to MSSQL
$connectionInfo = array( "Database"=>"java", "UID"=>"panda", "PWD"=>"123");
$conn = sqlsrv_connect( $server, $connectionInfo);

if (!$conn) {
    die('Something went wrong while connecting to MSSQL');
}




$sql = "SELECT * FROM personas";
$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) );
}

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
      echo $row['cedula'].", ".$row['nombre']."<br />";
}
?> 