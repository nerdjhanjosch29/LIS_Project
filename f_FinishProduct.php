<?php

require_once 'connection.php';
if ( sqlsrv_begin_transaction( $conn ) === false ) {
    die( print_r( sqlsrv_errors(), true ));
  }
 $Today = GETDATE();
    $date = "";
    if(isset($_GET['date']))
    {
        $date = $_GET['date'];
    }
    $date1 = "";
    if(isset($_GET['date1']))
    {
        $date1 = $_GET['date1'];
    }
    // $Month= date('Y-m-d', strtotime($date)); 
    // $Month = "";
    // if(isset($_GET['month']))
    // {
    //     $Month = $_GET['month'];
    // }

    if($date != "" && $date1 != "" )
    {
        $sql = "SELECT f.FinishProduct, * FROM FinishProductInventory fi LEFT JOIN FinishProduct f ON fi.FinishProductID = f.FinishProductID
        WHERE fi.InventoryDate BETWEEN ? AND ?";
        $params = array($date,$date1);
        $stmt = sqlsrv_query($conn,$sql,$params);
        if($stmt)
        {
        $json = array();
        do {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $json[] = $row;     	
        }
          }while ( sqlsrv_next_result($stmt) );
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($json);
        }
      else
        {
        sqlsrv_rollback($conn);
        echo "Rollback";
        }
    }
    else 
    {
        $sql = "SELECT * FROM FinishProductInventory WHERE InventoryDate = ?";
        $params = array($Today);
        $stmt = sqlsrv_query($conn,$sql, $params);
        if($stmt)
        {
        $json = array();
        do {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $json[] = $row;     	
        }
          }while ( sqlsrv_next_result($stmt) );
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($json);
        }
      else
        {
        sqlsrv_rollback($conn);
        echo "Rollback";
        }
    }

     


?>