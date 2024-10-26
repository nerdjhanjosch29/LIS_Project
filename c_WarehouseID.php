<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$WarehouseID = "";
if(isset($_GET['id']))
{
 $WarehouseID = $_GET['id'];
}
  $sql = "UPDATE Warehouse SET isDel = 'True' WHERE WarehouseID = ?";
  $params = array($WarehouseID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>