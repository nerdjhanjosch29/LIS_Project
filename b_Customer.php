<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$CustomerID = $data->CustomerID;
$SalesAgentID = $data->SalesAgentID;
$CustomerName = $data->CustomerName;
$Address = $data->Address;
$ContactNo = $data->ContactNo;
$UserID = $data->UserID;
if(isset($data))
{
 $sql = "EXEC [dbo].[Customers] ?,?,?,?,?,?";
 $params = array($CustomerID, $SalesAgentID, $CustomerName, $Address, $ContactNo, $UserID);
 $stmt = sqlsrv_query($conn, $sql, $params);
 while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
 {
   sqlsrv_commit($conn);
   echo $row['result'];
 }
    if($CustomerID == 0)
    {
      $SystemLogID = 0;
      $FunctionID = 1;
      $TableName = "Customer";
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
      $TableName = "Customer";
      $sql = "EXEC [dbo].[SystemLogEdit]
              @SystemLogID = ?,
              @UserID = ?,
              @FunctionID = ?,
              @TableName = ?";
      $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
      $stmt = sqlsrv_query($conn, $sql, $params);

    }
    sqlsrv_commit($conn);

}

?>