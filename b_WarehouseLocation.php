<?php
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));

if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
 if(isset($data))
 {
  $WarehouseLocationID=$data->WarehouseLocationID;
  $WarehouseLocation=$data->WarehouseLocation;
  // $MaximumCapacity=$data->MaximumCapacity; 
  // $TotalWeight=$data->TotalWeight;
  // $TotalQuantity=$data->TotalQuantity;
  $UserID=$data->UserID;
  //Query 
 $sql = "EXEC [dbo].[WarehouseLocations] @WarehouseLocationID = ?, @WarehouseLocation = ?,
 @UserID = ?";
  $params = array($WarehouseLocationID,$WarehouseLocation,$UserID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  $result ="";
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
    sqlsrv_commit($conn);
    echo $result = $row['result']; 
  }
  if($result == 1)
  {
    $sql = "EXEC [dbo].[AddWarehouseLocationRm]";
    $stmt = sqlsrv_query($conn, $sql);
    sqlsrv_commit($conn);
    // var_dump($stmt);
  }
 }
?>