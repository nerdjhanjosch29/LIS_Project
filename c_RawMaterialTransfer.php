<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 

$RawMaterialTransferID="";
if(isset($_GET['id']))
{
 $RawMaterialTransferID = $_GET['id'];
}
  $sql="UPDATE RawMaterialTransfer SET isDel = 'True' WHERE RawMaterialTransferID = ?";
  $params=array($RawMaterialTransferID);
  $stmt=sqlsrv_query($conn,$sql,$params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>