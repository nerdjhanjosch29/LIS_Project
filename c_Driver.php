<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$DriverID = "";
if(isset($_GET['id']))
{
 $DriverID = $_GET['id'];
}
  $sql = "UPDATE Driver SET isDel = 'True' WHERE DriverID = ?";
  $params = array($DriverID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>