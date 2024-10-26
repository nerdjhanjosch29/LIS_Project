<?php 

require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
}
$TruckingID = $data->TruckingID;
$TruckingName = $data->TruckingName;
$ContactPerson = $data->ContactPerson;
$ContactNumber = $data->ContactNumber;
$UserID = $data->UserID;
if(isset($data))
{
 $sql = "EXEC [dbo].[Truckings] @TruckingID = ?, @TruckingName = ?, @ContactPerson = ?, @ContactNumber = ?,
 @UserID = ?";
 $params = array($TruckingID, $TruckingName, $ContactPerson, $ContactNumber, $UserID);
 $stmt = sqlsrv_query($conn, $sql, $params);
 while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
 {
   sqlsrv_commit($conn);
   echo $row['result'];
 }
 if($TruckingID = 0)
 {
    $SystemLogID = 0;
    $FunctionID = 0; 
    $TableName = "Trucking";
    $sql = "EXEC [dbo].[SystemLogInsert]
            @SystemLogID = ?,
            @UserID = ?,
            @FunctionID = ?.
            @TableName = ?";
    $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
    $stmt = sqlsrv_query($conn, $sql, $params);
 }
 else
 {
    $SystemLogID = 0;
    $FunctionID = 0;
    $TableName = "Trucking";
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