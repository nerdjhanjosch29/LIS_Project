<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
if(isset($data))
{
  $IndentorID = $data->IndentorID;
  $Indentor = $data->Indentor;
  $Address = $data->Address;
  $ContactPerson = $data->ContactPerson;
  $ContactNumber = $data->ContactNumber;
  $UserID = $data->UserID;
  $sql = " EXEC [dbo].[Indentors] @IndentorID = ?, @Indentor = ?, @Address = ?, @ContactPerson = ?, 
  @ContactNumber = ?, @UserID = ?";
  $params = array($IndentorID, $Indentor, $Address, $ContactPerson, $ContactNumber, $UserID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
    sqlsrv_commit($conn);
    echo $row['result'];
  }

}
?>