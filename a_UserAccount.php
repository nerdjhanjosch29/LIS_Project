<?php 

    require_once 'connection.php';
    if (sqlsrv_begin_transaction($conn) === false) {
      die( print_r( sqlsrv_errors(), true ));
    }
    // $validateToken = include('validate_token.php');
    // if(!$validateToken)
    // {
    //   http_response_code(404);
    //   die();
    // }

    // $UserID = "";
    // if(isset($_GET['UserID']))
    // {
    //   $UserID = $_GET['UserID'];
    // }
        $sql = "SELECT 
                ua.UserID,
                ua.UName,
                ua.ULevel,
                m.ModuleID,
                m.ModuleName,
                ua.Name,
                ua.DepartmentID,
                d.Department,
                ua.ContactNo,
                ua.EmailAdd
            FROM UserAccount ua
            LEFT JOIN Module m ON ua.ULevel = m.ModuleID
            LEFT JOIN Department d ON ua.DepartmentID = d.DepartmentID
            GROUP BY 
                ua.UserID,
                ua.UName,
                ua.ULevel,
                m.ModuleID,
                m.ModuleName,
                ua.Name,
                ua.DepartmentID,
                d.Department,
                ua.ContactNo,
                ua.EmailAdd
            ORDER BY ua.DepartmentID ASC";
        $stmt1 = sqlsrv_query($conn,$sql); 

        if($stmt1)
        {
          $json = array();
          do {
              $UserID= "";
              while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
              $UserID = $row['UserID'];
              $json[] = $row; 
              
            }
          } while (sqlsrv_next_result($stmt1));
            


          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($json);

            //  $SystemLogID = 0;
            //  $FunctionID = 7; 
            //  $TableName = "User Account";
            //   $sql1 = "EXEC [dbo].[SystemLogFetch]
            //           @SystemLogID = ?,
            //           @UserID = ?,
            //           @FunctionID = ?,
            //           @TableName = ?";
            //   $paramss = array($SystemLogID,$UserID,$FunctionID,$TableName);        
            //   $stmt = sqlsrv_query($conn, $sql1, $paramss);
        }
        else
        {
        sqlsrv_rollback($conn);
        echo "Rollback";
        }
        sqlsrv_commit($conn);
?>