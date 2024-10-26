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
               w.WarehouseID
              ,w.WarehouseLocationID
              ,wl.WarehouseLocation
              ,w.Warehouse_Name
              ,ISNULL((SELECT SUM(MaximumCapacity) 
                    FROM WarehousePartition 
                    WHERE WarehouseID = w.WarehouseID),0) AS MaximumCapacity
              ,w.MinimumCapacity
              ,(SELECT SUM(RawMatsQty) FROM WarehousePartitionStock wps WHERE w.WarehouseID = wps.WarehouseID) AS TotalQuantity
			        ,(SELECT SUM(RawMatsWeight) FROM WarehousePartitionStock wps WHERE w.WarehouseID = wps.WarehouseID) AS TotalWeight
              ,w.Remarks
                FROM 
                    Warehouse w 
                LEFT JOIN 
                    WarehouseLocation wl ON w.WarehouseLocationID = wl.WarehouseLocationID 
                WHERE  w.isDel = 'False'
				         ORDER BY w.WarehouseLocationID, w.WarehouseID ASC";
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