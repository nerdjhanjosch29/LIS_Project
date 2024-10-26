<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
if(isset($data))
{
  $ShippingLineID = $data->ShippingLineID;
  $ShippingLine = $data->ShippingLine;
  $ContactPerson = $data->ContactPerson;
  $ContactNumber = $data->ContactNumber;
  $UserID = $data->UserID;
$sql = "EXEC [dbo].[ShippingLines] @ShippingLineID = ?, @ShippingLine = ?, @ContactPerson = ?, 
@ContactNumber = ?, @UserID = ?";
$params = array($ShippingLineID, $ShippingLine, $ContactPerson, $ContactNumber, $UserID);
$stmt = sqlsrv_query($conn, $sql, $params);
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
  sqlsrv_commit($conn);
  echo $row['result'];
  }
}
?>