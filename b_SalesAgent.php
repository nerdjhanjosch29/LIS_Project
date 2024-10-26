<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
if(isset($data))
{
  $SalesAgentID = $data->SalesAgentID;
  $SalesAgent = $data->SalesAgent;
  $ContactNo = $data->ContactNo;
  $UserID =$data->UserID;
  $sql = "EXEC [dbo].[SaleAgents] @SalesAgentID = ?, @SalesAgent = ?, @ContactNo = ?, @UserID = ?";
  $params = array($SalesAgentID, $SalesAgent, $ContactNo, $UserID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {  sqlsrv_commit($conn);
    echo $row['result'];
  }
}
?>