<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$SupplierID = "";
if(isset($_GET['id']))
{
 $SupplierID = $_GET['id'];
}
  $sql = "UPDATE Supplier SET isDel = 'True' WHERE SupplierID = ?";
  $params = array($SupplierID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>