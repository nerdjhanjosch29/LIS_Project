<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
    if(isset($data))
    {
        $BankID = $data->BankID;
        $Bank= $data->Bank;
        $BankName= $data->BankName;
        $UserID = $data->UserID;
        $sql = "EXEC [dbo].[Banks]
        @BankID = ?,
        @Bank =?,
        @BankName = ?,
        @UserID = ?";
        $params = array($BankID, $Bank, $BankName, $UserID);
        $stmt = sqlsrv_query($conn, $sql, $params);
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
        {
        sqlsrv_commit($conn);
        echo $row['result'];
        }
            if($BankID == 0 )
            {
                      
                $SystemLogID = 0;
                $FunctionID = 1; 
                $TableName = "Bank";
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
                $TableName = "Bank";
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