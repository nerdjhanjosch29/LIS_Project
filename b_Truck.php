<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if (sqlsrv_begin_transaction($conn) === false) {
  die( print_r( sqlsrv_errors(), true ));
}
$TruckID = $data->TruckID;
$TruckingID = $data->TruckingID;
$TruckTypeID = $data->TruckTypeID;
$PlateNo = $data->PlateNo;
$Description = $data->Description;
$UserID = $data->UserID;
  if(isset($data))
  {
   $sql = "EXEC [dbo].[Trucks] @TruckID = ?, @TruckingID = ?, @TruckTypeID = ?, @PlateNo= ?, @Description = ?, @UserID = ?";
   $params = array($TruckID, $TruckingID, $TruckTypeID, $PlateNo, $Description, $UserID);
   $stmt = sqlsrv_query($conn,$sql, $params);
   while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
   {
     sqlsrv_commit($conn);
     echo $row['result'];      
   }
  }
?>