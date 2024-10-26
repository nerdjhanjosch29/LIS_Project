<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$DeliveryID = "";
if(isset($_GET['id']))
{
 $DeliveryID = $_GET['id'];
}
  $sql = "UPDATE Delivery SET isDel = 'True' WHERE DeliveryID = ?";
  $params = array($DeliveryID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>