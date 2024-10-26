<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if (sqlsrv_begin_transaction($conn) === false) {
  die( print_r( sqlsrv_errors(), true ));
}
if(isset($data))
{
$WarehouseID = $data->WarehouseID;
$WarehouseLocationID = $data->WarehouseLocationID;
$Warehouse_Name = $data->Warehouse_Name;
$MaximumCapacity = 0;
$MinimumCapacity = $data->MinimumCapacity;
$TotalQuantity = $data->TotalQuantity;
$TotalWeight = $data->TotalWeight;
$Remarks = $data->Remarks;
$UserID = $data->UserID;
 $sql = "EXEC [dbo].[Warehouses]  @WarehouseID = ?, @WarehouseLocationID = ?, @Warehouse_Name = ?, @MaximumCapacity = ?, @MinimumCapacity = ?,
 @TotalQuantity = ?, @TotalWeight = ?, @Remarks = ?, @UserID = ? ";
 $params = array($WarehouseID,$WarehouseLocationID, $Warehouse_Name, $MaximumCapacity, $MinimumCapacity, $TotalQuantity, $TotalWeight, $Remarks,$UserID);
 $stmt = sqlsrv_query($conn, $sql, $params);
 $result ="";
 while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
 {  sqlsrv_commit($conn);
    echo $result = $row['result'];
 }
    if($result == 1)
    {                                                                                                                                                                                                                                              
      $sql ="EXEC	[dbo].[AddWarehouse]";
      $stmt = sqlsrv_query($conn, $sql);
      $sql1 ="EXEC	[dbo].[AddWarehouseRm]";
      $stmt1 = sqlsrv_query($conn, $sql1);

    }
    
    // if($WarehouseID == 0)
    // {
    //  $SystemLogID = 0;

    // }
}
?>