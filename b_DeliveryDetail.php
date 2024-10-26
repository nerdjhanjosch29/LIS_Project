<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
if(isset($data))
{
  $DeliveryDetailID = $data->DeliveryDetailID;
  $DeliveryID = $data->DeliveryID;
  $FinishProductID = $data->FinishProductID;
  $Quantity = $data->Quantity;
  $sql = "EXEC [dbo].[DeliveryDetails]
  @DeliveryDetailID = ?,
  @DeliveryID = ?,
  @FinishProductID = ?,
  @Quantity = ?";
  $params = array($DeliveryDetailID, $DeliveryID, $FinishProductID, $Quantity);
  $stmt = sqlsrv_query($conn,$sql,$params);
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
   sqlsrv_commit($conn);
   echo $row['result'];
  }
}
?>