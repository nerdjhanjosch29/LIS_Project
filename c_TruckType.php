<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$TruckTypeID = "";
if(isset($_GET['id']))
{
 $TruckTypeID = $_GET['id'];
}
  $sql="UPDATE TruckType SET isDel = 'True' WHERE TruckTypeID = ?";
  $params=array($TruckTypeID);
  $stmt=sqlsrv_query($conn,$sql,$params);
  sqlsrv_commit($conn);
  if($stmt)
  {
   echo 3;
  }
?>