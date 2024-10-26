<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
}
$TruckTypeID = $data->TruckTypeID;
$TruckType = $data->TruckType;
$UserID = $data->UserID;

if(isset($data))
{
   $sql = "EXEC [dbo].[TruckTypes] @TruckTypeID = ?, @TruckType = ?, @UserID = ?";
   $params = array($TruckTypeID, $TruckType, $UserID);
    $stmt = sqlsrv_query($conn, $sql, $params);

    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      sqlsrv_commit($conn);
      echo $row['result'];
    }
 
}





?>