<?php 

    $serverName = "(local)"; //server name/of MS SQL
    $user = "sa";
    $pass = "celsun";
    $database = "LIS_db";

    $connectionInfo = array( "Database"=>"LIS_db", "UID"=>"sa", "PWD"=>"celsun");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);
    // if(!$conn)
    // {  
    //     echo "Not";
    // }
    // else
    // {
    //     echo "successful";
    // }
?>