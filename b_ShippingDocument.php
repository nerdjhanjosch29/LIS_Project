<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$ShippingDocumentID = $data->ShippingDocumentID;
$ShippingDocument = $data->ShippingDocument;
$UserID = $data->UserID;
if(isset($data))
{ 
  $sql = "EXEC [dbo].[ShippingDocuments] @ShippingDocumentID = ?, @ShippingDocument = ?, @UserID = ?";
  $params = array($ShippingDocumentID, $ShippingDocument, $UserID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
   sqlsrv_commit($conn);
   echo $row['result'];
  }
}

?>