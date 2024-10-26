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
  $Name = "";
  $ContactNo = 0;
  $EmailAdd = "";
  if($data->UserID)
  {
    $UserID = $data->UserID;
  }
  if($data->AvatarUrl)
  {
    $AvatarUrl = $data->AvatarUrl;
  }
  if($data->UName)
  {
    $UName = $data->UName;
  }
  if($data->Name)
  {
    $Name = $data->Name;
  }

  if($data->ContactNo)
  {
    $ContactNo = $data->ContactNo;
  }
  if($data->EmailAdd)
  {
    $EmailAdd = $data->EmailAdd;
  }
  $sql = "EXEC [dbo].[UserProfiles]
  @UserID = ?,
  @AvatarUrl = ?,
  @UName = ?,
  @Name = ?,
  @ContactNo = ?,
  @EmailAdd = ? ";
  $params = array($UserID,$AvatarUrl,$UName,$Name,$ContactNo,$EmailAdd);
  $stmt = sqlsrv_query($conn, $sql, $params);   
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      sqlsrv_commit($conn);
      echo $row['result'];
    }
    if($stmt)
    {
          //  SystemLog for User Profile
          $SystemLogID = 0;
          $FunctionID = 2;
          $TableName = "User Profile";
          $sql = "EXEC [dbo].[SystemLogEdit]
                  @SystemLogID = ?,
                  @UserID = ?,
                  @FunctionID = ?,
                  @TableName = ?";
          $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
          $stmt = sqlsrv_query($conn, $sql, $params);
          // var_dump($stmt);
    }
}
?>