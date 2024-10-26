<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$FinishProductTransferID = $data->FinishProductTransferID;
$DateTransfer = $data->DateTransfer;
$FromWarehouseID = $data->FromWarehouseID;
$FromWarehousePartitionID = $data->FromWarehousePartitionID;
$ToWarehouseID = $data->ToWarehouseID;
$ToWarehousePartitionID = $data->ToWarehousePartitionID;
$FinishProductID = $data->FinishProductID;
$Quantity = $data->Quantity;
$CheckerID = $data->CheckerID;
$UserID = $data->UserID;
if(isset($data))
{
$sql = "EXEC [dbo].[FinishProductTransfers]
@FinishProductTransferID = ?,
@DateTransfer = ?,
@FromWarehouseID = ?,
@FromWarehousePartitionID = ?,
@ToWarehouseID = ?,
@ToWarehousePartitionID = ?,
@FinishProductID = ?,
@Quantity = ?, 
@CheckerID = ?,
@UserID = ?";
$params = array($FinishProductTransferID, $DateTransfer, $FromWarehouseID, $FromWarehousePartitionID,$ToWarehouseID, $ToWarehousePartitionID,
$FinishProductID, $Quantity, $CheckerID, $UserID);
$stmt = sqlsrv_query($conn, $sql, $params);
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
 sqlsrv_commit($conn);
 echo $row['result'];
}
}



?>