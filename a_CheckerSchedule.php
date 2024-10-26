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
        $sql = "SELECT 
                 cs.ScheduleRotationID
                ,cs.UserID
                ,ua.UName
                ,ua.Name
                ,cs.WarehouseLocationID
                ,wl.WarehouseLocation
                ,cs.DateRotation
                ,cs.AdminUserID 
                FROM CheckerSchedule cs
                LEFT JOIN UserAccount ua ON cs.UserID = ua.UserID
                LEFT JOIN WarehouseLocation wl ON cs.WarehouseLocationID = wl.WarehouseLocationID
                WHERE cs.isDel = 'False'";
        $stmt1 = sqlsrv_query($conn,$sql); 
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
?>