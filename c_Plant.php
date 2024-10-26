<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$PlantID = "";
if(isset($_GET['id']))
{
 $PlantID = $_GET['id'];
}
  $sql = "UPDATE Plant SET isDel = 'True' WHERE PlantID = ?";
  $params = array($PlantID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
    echo 3;
  }

?>