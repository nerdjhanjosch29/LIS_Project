<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));

if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$FinishProductInventoryID = $data->FinishProductInventoryID;
$FinishProductID = $data->FinishProductID;
$ProductionOutput = $data->ProductionOutput;
$OutgoingQty = $data->OutgoingQty;
$Condemned = $data->Condemned;
if(isset($data))
{
  $sql = "EXEC [dbo].[FinishProductInventorys] @FinishProductInventoryID = ?,
  @FinishProductID = ?,@ProductionOutput = ?,@OutgoingQty = ?,@Condemned = ?";
  $params = array($FinishProductInventoryID, $FinishProductID, $ProductionOutput,$OutgoingQty,$Condemned);
  $stmt = sqlsrv_query($conn,$sql,$params);
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
    sqlsrv_commit($conn);
    echo $row['result'];
  }
}

?>