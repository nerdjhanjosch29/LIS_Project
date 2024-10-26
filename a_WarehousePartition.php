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
                 w.WarehouseLocationID 
                ,wl.WarehouseLocation
                ,w.Warehouse_Name
                ,w.WarehouseID
                ,wp.WarehousePartitionID
                ,wp.WarehousePartitionName
                ,wp.MaximumCapacity
                ,ISNULL((SELECT SUM(wps.RawMatsQty) FROM WarehousePartitionStock wps
                   WHERE wps.WarehousePartitionID = wp.WarehousepartitionID),0) AS TotalQuantity
                ,ISNULL((SELECT SUM(wps.RawMatsWeight) FROM WarehousePartitionStock wps
                   WHERE wps.WarehousePartitionID = wp.WarehousepartitionID),0) AS TotalWeight
                ,wp.UserID 
              FROM WarehousePartition wp 
              LEFT JOIN Warehouse w ON w.WarehouseID = wp.WarehouseID
              LEFT JOIN WarehouseLocation wl 
              ON w.WarehouseLocationID = wl.WarehouseLocationID
              WHERE wp.isDel = 'False'
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