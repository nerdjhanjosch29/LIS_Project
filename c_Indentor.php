<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$IndentorID = "";
if(isset($_GET['id']))
{
 $IndentorID = $_GET['id'];
}
  $sql = "UPDATE Indentor SET isDel = 'True' WHERE IndentorID = ?";
  $params = array($IndentorID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>