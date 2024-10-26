<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 

$ShippingDocumentsID="";
if(isset($_GET['id']))
{
 $ShippingDocumentsID = $_GET['id'];
}
  $sql=" UPDATE
   ShippingDocument SET isDel = 'True' WHERE ShippingDocumentsID = ?";
  $params=array($ShippingDocumentsID);
  $stmt=sqlsrv_query($conn,$sql,$params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>