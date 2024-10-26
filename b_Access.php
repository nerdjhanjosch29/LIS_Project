<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
  if(isset($data))
  {
    $UserID = "";
    if($data->UserID)
    {
      $UserID=$data->UserID;
    }
    $AdminID = "";
    if($data->AdminID)
    {
      $AdminID=$data->AdminID;
    }
        $array=$data->AccessDetail;
        $length=count($array); // count array 
        $sql1 = "SELECT COUNT(AccessID) as count FROM Access WHERE UserID = ?";
        $params1 = array($UserID);
        $stmt1 = sqlsrv_query($conn, $sql1, $params1);
        $row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);
        if ($row['count'] > 0) {
            // UserID exists, delete existing records
            $sql = "DELETE FROM Access WHERE UserID = ?";
            $params = array($UserID);
            $stmt = sqlsrv_query($conn, $sql, $params);
        for($i=0; $i<=$length-1; $i++)
        { 
             $AccessRight=$array[$i]->AccessRight;
              $sql="INSERT INTO Access(UserID,AccessRight,AdminID)
              VALUES(?,?,?)";
              $params = array($UserID, $AccessRight, $AdminID);
              $stmt1 = sqlsrv_query($conn,$sql,$params);
              sqlsrv_commit($conn);
          }
          sqlsrv_commit($conn);
          echo 1;
        }
        else
        {
          for($i=0; $i<=$length-1; $i++)
          {
                $AccessRight=$array[$i]->AccessRight;
                $sql="INSERT INTO Access (UserID,AccessRight,AdminID)
                VALUES(?,?,?)";
                $params = array($UserID, $AccessRight, $AdminID);
                $stmt1 = sqlsrv_query($conn,$sql,$params);
                sqlsrv_commit($conn);
            }
            sqlsrv_commit($conn);
            echo 1;
        }
             
             $SystemLogID = 0;
             $FunctionID = 2; 
             $TableName = "User Access";
              $sql1 = "EXEC [dbo].[SystemLogEdit]
                      @SystemLogID = ?,
                      @UserID = ?,
                      @FunctionID = ?,
                      @TableName = ?";
                 sqlsrv_commit($conn);   
                        $paramss = array($SystemLogID,$UserID,$FunctionID,$TableName);        
              $stmt = sqlsrv_query($conn, $sql1, $paramss);
          
  }
?>