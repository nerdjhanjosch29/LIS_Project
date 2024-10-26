<?php
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if (sqlsrv_begin_transaction($conn) === false) {
  die( print_r( sqlsrv_errors(), true ));
}
$SupplierID = $data->SupplierID;
$Supplier = $data->Supplier;
$Address = $data->Address;
$ContactPerson = $data->ContactPerson;
$ContactNumber = $data->ContactNumber;
$UserID = $data->UserID;
if(isset($data))
{
 $sql = "EXEC [dbo].[Suppliers] @SupplierID = ?, @Supplier = ?, @Address = ?, @ContactPerson = ?, @ContactNumber = ?, @UserID = ?";
 $params = array($SupplierID, $Supplier, $Address, $ContactPerson, $ContactNumber, $UserID);
 $stmt = sqlsrv_query($conn, $sql, $params);
 while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
 {
   sqlsrv_commit($conn);
   echo $row['result'];
 }
}
?>