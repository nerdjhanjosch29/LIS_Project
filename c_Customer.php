<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$CustomerID = "";
if(isset($_GET['id']))
{
 $CustomerID = $_GET['id'];
}
  $sql = "UPDATE Customer SET isDel = 'True' WHERE CustomerID = ?";
  $params = array($CustomerID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>