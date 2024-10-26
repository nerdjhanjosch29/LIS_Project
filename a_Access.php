<?php 

  //  $data = json_decode(file_get_contents('php://input'));
    require_once 'connection.php';
    if (sqlsrv_begin_transaction($conn) === false) {
      die( print_r( sqlsrv_errors(), true ));
    }
 

    // $data = json_decode(file_get_contents('php://input'));
    // $validateToken = include('validate_token.php');
    // if(!$validateToken)
    // {
    //   http_response_code(404);
    //   die();
    // }
                
           $UserID = "";
          if(isset($_GET['UserID']))
          {
            $UserID = $_GET['UserID'];
          }
            $sql = "SELECT 
            a.UserID
            ,a.AccessRight
            ,ma.AccessName
            ,ma.Category
            ,a.AdminID
            FROM Access a
            LEFT JOIN ModuleAccess ma ON a.AccessRight = ma.AccessRight WHERE a.UserID = ?";
            $params = array($UserID);
            $stmt1 = sqlsrv_query($conn,$sql,$params); 
            if($stmt1)
            { 
              $json = array();
              do {
                while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                $json[] = $row;     	
                }
              } while (sqlsrv_next_result($stmt1));
              header('Content-Type: application/json; charset=utf-8');
              echo json_encode($json);
            }
            else
            {
            sqlsrv_rollback($conn);
            echo "Rollback";
            }
          // if($stmt1)
          // {
          //     $SystemLogID = 0;
          //    $FunctionID = 7; 
          //    $TableName = "User Access";
          //     $sql1 = "EXEC [dbo].[SystemLogFetch]
          //             @SystemLogID = ?,
          //             @UserID = ?,
          //             @FunctionID = ?,
          //             @TableName = ?";
          //        sqlsrv_commit($conn);   
          //               $paramss = array($SystemLogID,$UserID,$FunctionID,$TableName);        
          //     $stmt = sqlsrv_query($conn, $sql1, $paramss);
          // }
      
             
          
?>