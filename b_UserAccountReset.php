<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
if(isset($data))
{
  $UserID = $data->UserID;
  $UName = $data->UName;
  $PWord = $UName . "@feedmixlis";
  $encrypt = md5($PWord);
  $sql = "EXEC [dbo].[UserAccountReset] @UserID = ?, @Uname = ?,@PWord = ?";
  $params = array($UserID,$UName,$encrypt);
  $stmt = sqlsrv_query($conn, $sql, $params);   
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      sqlsrv_commit($conn);
      echo $row['result'];
    }
}
?>