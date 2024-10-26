<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$TruckingID = "";
if(isset($_GET['id']))
{
 $TruckingID = $_GET['id'];
}
  $sql="UPDATE Trucking SET isDel = 'True' WHERE TruckingID = ?";
  $params=array($TruckingID);
  $stmt=sqlsrv_query($conn,$sql,$params);
  sqlsrv_commit($conn);
  if($stmt)
  {
   echo 3;
  }
?>