<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$RawMaterialID = "";
if(isset($_GET['id']))
{
 $RawMaterialID = $_GET['id'];
}
  $sql = "UPDATE RawMaterial SET isDel = 'True' WHERE RawMaterialID = ?";
  $params = array($RawMaterialID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>