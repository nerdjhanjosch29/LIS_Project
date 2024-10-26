<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 

$RawMaterialInventoryID="";
if(isset($_GET['id']))
{
 $RawMaterialInventoryID = $_GET['id'];
}
  $sql = "UPDATE RawMaterialInventory SET isDel = 'True' WHERE RawMaterialInventoryID = ?";
  $params=array($RawMaterialInventoryID);
  $stmt=sqlsrv_query($conn,$sql,$params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>