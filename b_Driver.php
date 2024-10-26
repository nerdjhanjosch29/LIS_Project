<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
  if(isset($data))
  {
  $DriverID=$data->DriverID;
  $DriverName=$data->DriverName;
  $ContactNumber=$data->ContactNumber;
  $UserID=$data->UserID;
  //Query 
  $sql = "EXEC [dbo].[Drivers] @DriverID = ?, @DriverName = ?, @ContactNumber = ?, @UserID = ?";
  $params = array($DriverID,$DriverName,$ContactNumber, $UserID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
    sqlsrv_commit($conn);
    echo $row['result']; 
  }

  if($DriverID = 0)
  {
    $SystemLogID = 0;
    $FunctionID = 1;
    $TableName = "Driver";  
    $sql = "EXEC [dbo].[SystemLogInsert]
            @SystemLogID = ?,
            @UserID = ?,
            @FunctionID = ?,
            @TableName = ?";
    $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
    $stmt = sqlsrv_query($conn, $sql,$params);

  }
  else
  {
    $SystemLogID = 0;
    $FunctionID = 2;
    $TableName = "Driver";
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
