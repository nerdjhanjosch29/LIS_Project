<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 

$ModuleID = "";
if(isset($_GET['id']))
{
 $ModuleID = $_GET['id'];
}
  $sql = "UPDATE Module SET isDel = 'True' WHERE ModuleID = ?";
  $params = array($ModuleID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>