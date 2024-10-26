<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$BrokerID = "";
if(isset($_GET['id']))
{
 $BrokerID = $_GET['id'];
}
  $sql = "UPDATE Broker SET isDel = 'True' WHERE BrokerID = ?";
  $params = array($BrokerID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>