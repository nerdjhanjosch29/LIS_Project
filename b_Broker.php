
<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
  if(isset($data))
  {
  $BrokerID=$data->BrokerID;
  $Broker=$data->Broker;
  $ContactPerson=$data->ContactPerson; 
  $ContactNumber=$data->ContactNumber; 
  $UserID=$data->UserID;
  //Query 
 $sql = "EXEC [Brokers] @BrokerID = ?, @Broker = ?, @ContactPerson = ?, @ContactNumber = ?, @UserID= ?";
  $params = array($BrokerID,$Broker,$ContactPerson, $ContactNumber, $UserID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  // var_dump($stmt);
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
    sqlsrv_commit($conn);
    echo $row['result']; 
  }
          if($BrokerID == 0 )
          {       
              $SystemLogID = 0;
              $FunctionID = 1; 
              $TableName = "Broker";
              $sql1 = "EXEC [dbo].[SystemLogInsert]
                        @SystemLogID = ?,
                        @UserID = ?,
                        @FunctionID = ?,
                        @TableName = ?";
                $paramss = array($SystemLogID,$UserID,$FunctionID,$TableName);        
                $stmt = sqlsrv_query($conn, $sql1, $paramss);
          }
          else
          {           
              $SystemLogID = 0;
              $FunctionID = 2; 
              $TableName = "Broker";
              $sql1 = "EXEC [dbo].[SystemLogEdit]
                        @SystemLogID = ?,
                        @UserID = ?,
                        @FunctionID = ?,
                        @TableName = ?";
                $paramss = array($SystemLogID,$UserID,$FunctionID,$TableName);        
                $stmt = sqlsrv_query($conn, $sql1, $paramss);
          }
            sqlsrv_commit($conn);   
}
?>
