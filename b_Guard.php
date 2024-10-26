<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
  if(isset($data))
  {
  $GuardID=$data->GuardID;
  $GuardName=$data->GuardName;
  $UserID=$data->UserID;
  //Query 
  $sql = "EXEC [dbo].[Guards] @GuardID = ?, @GuardName = ?, @UserID = ?";
  $params = array($GuardID,$GuardName, $UserID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
    sqlsrv_commit($conn);
    echo $row['result']; 
  }
}
?>
