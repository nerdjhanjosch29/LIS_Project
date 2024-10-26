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
               wl.WarehouseLocationID
              ,wl.WarehouseID
              ,wl.WarehousePartitionID
              ,wl.RawMaterialID
              ,wl.InventoryDate
              ,wl.BeginQty
              ,wl.BeginWeight
              ,wl.IncomingQty
              ,wl.IncomingWeight
              ,wl.FromTransferQty
              ,wl.FromTransferWeight
              ,wl.ToTransferQty
              ,wl.ToTransferWeight
              ,wl.BinloadingQty
              ,wl.BinloadingWeight
              ,wl.CondemnedQty
              ,wl.CondemnedWeight
              ,wl.EndingQty
              ,wl.EndingWeight
               FROM WarehouseLocationInventoryRm wl 
               LEFT JOIN RawMaterial r ON wl.RawMaterialID = r.RawMaterialID
               WHERE wl.InventoryDate = @Today";
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