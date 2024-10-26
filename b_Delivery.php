<?php
require 'connection.php';
// Get JSON data from request body
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
if (isset($data)) {
    $DeliveryID = $data->DeliveryID;
    $DeliveryNo = $data->DeliveryNo;
    $PurchaseOrderNo = $data->PurchaseOrderNo;
    $DeliveryDate = $data->DeliveryDate;
    $CustomerID = $data->CustomerID;
    $UserID = $data->UserID;
    $sql = "EXEC [dbo].[Deliverys] @DeliveryID = ?,@DeliveryNo = ?,@PurchaseOrderNo = ?,@DeliveryDate = ?,
    @CustomerID = ?,@UserID = ?";
    $params = array($DeliveryID,$DeliveryNo,$PurchaseOrderNo,$DeliveryDate,$CustomerID,$UserID);
    $stmt = sqlsrv_query($conn, $sql, $params);
    $result = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    sqlsrv_commit($conn);
    echo $row['result'];
    }
    if($DeliveryID == 0)
    {
      $SystemLogID = 0;
      $FunctionID = 1;
      $TableName = "Delivery";
      $sql = "EXEC [dbo].[SystemLogInsert]
              @SystemLogID = ?,
              @UserID = ?, 
              @FunctionID = ?,
              @TableName = ?";
      $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
      $stmt = sqlsrv_query($conn, $sql, $params);
    }
    else
    {
      $SystemLogID = 0;
      $FunctionID = 2;
      $TableName = "Delivery";
      $sql = "EXEC [dbo].[SystemLogEdit]
              @SystemLogID = ?,
              @UserID = ?,
              @FunctionID = ?,
              @TableName = ?";
      $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
      $stmt = sqlsrv_query($conn,$sql,$params);
    }
}
?>