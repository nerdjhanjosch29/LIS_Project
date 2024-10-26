<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$ContainerTypeID = "";
if(isset($_GET['id']))
{
 $ContainerTypeID = $_GET['id'];
}
  $sql = "UPDATE ContainerType SET isDel = 'True' WHERE ContainerTypeID = ?";
  $params = array($ContainerTypeID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>