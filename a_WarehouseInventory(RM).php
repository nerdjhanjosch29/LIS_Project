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
        $sql = "DECLARE @Today DATE = GETDATE(); 
               SELECT 
               wi.WarehouseLocationID
              ,wl.WarehouseLocation
              ,wi.WarehouseID
              ,w.Warehouse_Name
              ,wi.WarehousePartitionID
              ,wp.WarehousePartitionName
              ,wl.WarehouseLocation
              ,wi.RawMaterialID
              ,r.RawMaterial
              ,wi.InventoryDate
              ,wi.BeginQty
              ,wi.BeginWeight
              ,wi.IncomingQty
              ,wi.IncomingWeight
              ,wi.FromTransferQty
              ,wi.FromTransferWeight
              ,wi.ToTransferQty
              ,wi.ToTransferWeight
              ,wi.BinloadingQty
              ,wi.BinloadingWeight
              ,wi.CondemnedQty
              ,wi.CondemnedWeight
              ,wi.EndingQty
              ,wi.EndingWeight
               FROM WarehouseInventoryRm wi 
              LEFT JOIN RawMaterial r ON wi.RawMaterialID = r.RawMaterialID
              LEFT JOIN Warehouse w ON wi.WarehouseID = w.WarehouseID 
              LEFT JOIN WarehouseLocation wl ON wi.WarehouseLocationID = wl.WarehouseLocationID
              LEFT JOIN WarehousePartition wp ON wi.WarehousePartitionID = wp.WarehousePartitionID
              WHERE wi.InventoryDate = @Today";
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