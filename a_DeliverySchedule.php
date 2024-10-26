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

    // LEFT JOIN DeliveryScheduleDetails dsd ON ds.DeliveryScheduleID = dsd.DeliveryScheduleID
    //     LEFT JOIN Customer c ON ds.CustomerID = c.CustomerID
    //     LEFT JOIN Truck t ON ds.TruckID = t.TruckID
    //     LEFT JOIN FinishProduct fp ON dsd.FinishProductID = fp.FinishProductID WHERE ds.deleted = 0

        $sql = "SELECT 
                ds.DeliveryScheduleID
               ,ds.DateSchedule
               ,ds.Address
               ,ds.CustomerID
               ,ds.SONumber
               ,ds.TotalQty
               ,ds.TruckID
               ,ds.Status
               ,ds.UserID
                FROM DeliverySchedule ds
                WHERE isDel ='False'";
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