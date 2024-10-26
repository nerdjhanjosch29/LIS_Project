<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$CheckerID = "";
if(isset($_GET['id']))
{
 $CheckerID = $_GET['id'];
}
  $sql = "UPDATE Checker SET isDel = 'True' WHERE CheckerID = ?";
  $params = array($CheckerID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>