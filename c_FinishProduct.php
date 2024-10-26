<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$FinishProductID = "";
if(isset($_GET['id']))
{
 $FinishProductID = $_GET['id'];
}
  $sql = "UPDATE FinishProduct SET isDel = 'True' WHERE FinishProductID = ?";
  $params = array($FinishProductID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>