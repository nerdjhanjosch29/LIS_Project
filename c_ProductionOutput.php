<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 

$ProductionOutputID="";
if(isset($_GET['id']))
{
 $ProductionOutputID = $_GET['id'];
}
  $sql="UPDATE ProductionOutput SET isDel = 'True' WHERE ProductionOutputID = ?";
  $params=array($ProductionOutputID);
  $stmt=sqlsrv_query($conn,$sql,$params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>