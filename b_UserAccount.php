<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
if(isset($data))
{
  $UserID = "0";
  $UName = "";
  $AvatarUrl = 0;
  $ULevel = 0;
  $Name = "";
  $DepartmentID = 0;
  $ContactNo = 0;
  $EmailAdd = "";
  if($data->UserID)
  {
    $UserID = $data->UserID;
  }
  // if($data->AvatarUrl)
  // {
  //   $AvatarUrl = $data->AvatarUrl;
  // }
  if($data->UName)
  {
    $UName = $data->UName;
  }
  $PWord = $UName . "@feedmixlis";
  if($data->ULevel)
  {
    $ULevel = $data->ULevel;
  }
  if($data->Name)
  {
    $Name = $data->Name;
  }
  if($data->DepartmentID)
  {
    $DepartmentID =$data->DepartmentID;
  }
  if($data->ContactNo)
  {
    $ContactNo = $data->ContactNo;
  }
  if($data->EmailAdd)
  {
    $EmailAdd = $data->EmailAdd;
  }
  $encrypt = md5($PWord);
  $sql = "EXEC [dbo].[UserAccounts]
  @UserID = ?,
  @AvatarUrl = ?,
  @UName = ?,
  @PWord = ?,
  @ULevel = ?,
  @Name = ?,
  @DepartmentID = ?,
  @ContactNo = ?,
  @EmailAdd = ? ";
  $params = array($UserID,$AvatarUrl,$UName,$encrypt,$ULevel,$Name,$DepartmentID,$ContactNo,$EmailAdd);
  $stmt = sqlsrv_query($conn, $sql, $params);   
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      sqlsrv_commit($conn);
      echo $row['result'];
    }
}
?>