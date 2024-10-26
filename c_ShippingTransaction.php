<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$ShippingTransactionID = "";
if(isset($_GET['id']))
{
 $ShippingTransactionID = $_GET['id'];
}
  $sql="UPDATE ShippingTransaction SET isDel = 'True' WHERE ShippingTransactionID = ?";
  $params=array($ShippingTransactionID);
  $stmt=sqlsrv_query($conn,$sql,$params);
  sqlsrv_commit($conn);
  if($stmt)
  {
   echo 3;
  }
?>