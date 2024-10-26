
<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
    if(isset($data))
    {
      $CheckerID=$data->CheckerID;
      $CheckerName=$data->CheckerName;
      $CheckerTypeID=$data->CheckerTypeID;
      $UserID=$data->UserID;
      //Query 
      $sql = "EXEC [dbo].[Checks]
      @CheckerID = ?,
      @CheckerName = ?,
      @CheckerTypeID = ?,
      @UserID = ?";
      $params = array($CheckerID,$CheckerName,$CheckerTypeID, $UserID);
      $stmt = sqlsrv_query($conn, $sql, $params);
      // var_dump($stmt);
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
      {
        sqlsrv_commit($conn);
        echo $row['result']; 
      }
      if($CheckerID == 0)
      {
        $SystemLogID = 0;
        $FunctionID = 1;
        $TableName = "Checker";
        $sql = "EXEC [dbo].[SystemLogInsert]
                @SystemLogID = ?,
                @UserID = ?,
                @FunctionID = ?,
                @TableName = ?";
        $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
        $stmt = sqlsrv_query($conn, $sql, $params);
      }
      else
      {
        $SystemLogID = 0;
        $FunctionID = 2;
        $TableName = "Checker";
        $sql = "EXEC [dbo].[SystemLogEdit]
                @SystemLogID = ?,
                @UserID = ?,
                @FunctionID = ?,
                @TableName = ?";
       $params = array($SystemLogID,$UserID, $FunctionID,$TableName);
       $stmt = sqlsrv_query($conn, $sql, $params);
      }
    }
?>
