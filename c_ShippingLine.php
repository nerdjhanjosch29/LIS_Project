<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$ShippingLineID = "";
if(isset($_GET['id']))
{
 $ShippingLineID = $_GET['id'];
}
  $sql="UPDATE ShippingLine SET isDel = 'True' WHERE ShippingLineID = ?";
  $params=array($ShippingLineID);
  $stmt=sqlsrv_query($conn,$sql,$params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }
?>