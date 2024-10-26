<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 

$SalesAgentID="";
if(isset($_GET['id']))
{
 $SalesAgentID = $_GET['id'];
}
  $sql="UPDATE SalesAgent SET isDel = 'True' WHERE SalesAgentID = ?";
  $params=array($SalesAgentID);
  $stmt=sqlsrv_query($conn,$sql,$params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>