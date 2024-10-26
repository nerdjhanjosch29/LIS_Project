<?php 
  require 'connection.php';
  $data = json_decode(file_get_contents('php://input'));
  if ( sqlsrv_begin_transaction( $conn ) === false ) {
    die( print_r( sqlsrv_errors(), true ));
  } 
  if(isset($data))
  {
  $PlantID = $data->PlantID;
  $PlantName= $data->PlantName;
  $UserID = $data->UserID;
  $sql = "EXEC [dbo].[Plants] @PlantID = ?, @Plantname = ?, @UserID = ?";
  $params = array($PlantID, $PlantName, $UserID);
  $stmt = sqlsrv_query($conn, $sql, $params);
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      sqlsrv_commit($conn);
      echo $row['result'];
    }
  }
?>