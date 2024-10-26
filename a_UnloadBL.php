<?php 

    require_once 'connection.php';
    if (sqlsrv_begin_transaction($conn) === false) {
      die( print_r( sqlsrv_errors(), true ));
    }
    $today = date('Y-m-d');
    // $validateToken = include('validate_token.php');
    // if(!$validateToken)
    // {
    //   http_response_code(404);
    //   die();
    // }
        $sql = "SELECT
                 ub.UnloadedID
                ,ub.Packaging
                ,ub.BL
                ,ub.MBL
                ,ub.ContainerNumber AS ContainerID
                ,po.ContainerNumber
                ,t.PlateNo AS TruckID
                ,ub.TruckID AS Truck
                ,ub.DateUnload
                ,ub.NoOfUnloaded
                FROM UnloadedBL ub
                LEFT JOIN PullOut po ON ub.ContainerNumber = po.PullOutID
                LEFT JOIN Truck t ON ub.TruckID = t.TruckID
                WHERE ub.DateUnload = ?";           
        $params = array($today);
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
?>