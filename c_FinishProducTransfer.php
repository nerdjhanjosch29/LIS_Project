<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$FinishProductTransferID = "";
if(isset($_GET['id']))
{
 $FinishProductTransferID = $_GET['id'];
}
  $sql = "UPDATE FinishProductTransfer SET isDel = 'True' WHERE FinishProductTransferID = ?";
  $params = array($FinishProductTransferID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }
?>