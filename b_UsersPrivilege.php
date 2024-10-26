<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
$UsersPrivilegeID = $data->UsersPrivilegeID;
$UserID = $data->UserID;
$ModuleID = $data->ModuleID;
$isAdd = $data->isAdd;
$isEdit = $data->isEdit;
$isView = $data->isView;
$AdminUserID = $data->AdminUserID;
if(isset($data))
{
  $sql = "EXEC [dbo].[UsersPrivileges] @UsersPrivilegeID = ?,@UserID = ?,@ModuleID = ?, @isAdd = ?,@isEdit = ?,
  @isView = ?,@AdminUserID = ?";
  $params = array($UsersPrivilegeID, $UserID, $ModuleID, $isAdd,$isEdit,$isView,$AdminUserID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
   sqlsrv_commit($conn);
   echo $row['result'];
  }
}
?>