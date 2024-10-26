<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$TruckID = "";
if(isset($_GET['id']))
{
 $TruckID = $_GET['id'];
}
  $sql="UPDATE Truck SET isDel = 'True' WHERE TruckID = ?";
  $params=array($TruckID);
  $stmt=sqlsrv_query($conn,$sql,$params);
  sqlsrv_commit($conn);
  if($stmt)
  {
   echo 3;
  }
?>