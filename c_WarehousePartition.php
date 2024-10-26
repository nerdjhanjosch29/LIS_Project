<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$WarehousePartitionID = "";
if(isset($_GET['id']))
{
 $WarehousePartitionID = $_GET['id'];
}
  $sql = "UPDATE WarehousePartition SET isDel = 'True' WHERE WarehousePartitionID = ?";
  $params = array($WarehousePartitionID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>
