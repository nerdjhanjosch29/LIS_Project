<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$WarehousePartitionID = $data->WarehousePartitionID;
$WarehouseID = $data->WarehouseID;
$WarehousePartitionName = $data->WarehousePartitionName;
$MaximumCapacity = $data->MaximumCapacity;
$TotalWeight = $data->TotalWeight;
$TotalQuantity = $data->TotalQuantity;
$UserID = $data->UserID;
if(isset($data))
{
  $sql = "EXEC	[dbo].[WarehousePartitions] @WarehousePartitionID = ?, @WarehouseID = ?, @WarehousePartitionName = ?, @MaximumCapacity = ?,
  @TotalWeight = ?, @TotalQuantity = ?, @UserID = ?";
  $params = array($WarehousePartitionID, $WarehouseID, $WarehousePartitionName, $MaximumCapacity, $TotalWeight, $TotalQuantity, $UserID);
  $stmt = sqlsrv_query($conn,$sql,$params);  
  $result = "";
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
      sqlsrv_commit($conn);
      echo $result = $row['result'];
  }
  if($result == 1)
  {
    $sql = "EXEC [dbo].[AddWarehousePartitionRm]";
    $stmt = sqlsrv_query($conn, $sql);
  }
}
?> 