<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$UserID = "";
if(isset($_GET['id']))
{
 $UserID = $_GET['id'];
//  var_dump($UserID);
}
  $sql= "UPDATE UserAccount SET isDel = 'True' WHERE UserID = ?";
  $params = array($UserID);
  $stmt = sqlsrv_query($conn,$sql,$params);

  sqlsrv_commit($conn);
  if($stmt)
  {
   echo 3;
  }
?>