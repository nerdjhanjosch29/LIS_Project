
<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
if(isset($data))
{
$CondemID = $data->CondemID;
$DeliveryID = $data->DeliveryID;
$WarehousePartitionStockID  = $data->WarehousePartitionStockID ;
$FinishProductID = $data->FinishProductID;
$Quantity = $data->Quantity;
$CondemDate = $data->CondemDate;
$isTransaction = $data->isTransaction;
$Remarks = $data->Remarks;

$sql = "EXEC [dbo].[Condemned]
@CondemID = ?,
@DeliveryID = ?,
@WarehousePartitionStockID = ?,
@FinishProductID = ?,
@Quantity = ?,
@CondemDate = ?,
@isTransaction = ?,
@Remarks = ?"; 
$params = array($CondemID,$DeliveryID, $WarehousePartitionStockID,$FinishProductID,$Quantity,$CondemDate,$isTransaction,$Remarks);
$stmt = sqlsrv_query($conn, $sql, $params);
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
sqlsrv_commit($conn);
echo $row['result'];
}
}
?>


   