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
               wp.WarehouseLocationID
              ,wp.WarehouseID
              ,wp.WarehousePartitionID
              ,wp.RawMaterialID
              ,wp.InventoryDate
              ,wp.BeginQty
              ,wp.BeginWeight
              ,wp.IncomingQty
              ,wp.IncomingWeight
              ,wp.FromTransferQty
              ,wp.FromTransferWeight
              ,wp.ToTransferQty
              ,wp.ToTransferWeight
              ,wp.BinloadingQty
              ,wp.BinloadingWeight
              ,wp.CondemnedQty
              ,wp.CondemnedWeight
              ,wp.EndingQty
              ,wp.EndingWeight
              FROM WarehousePartitionInventoryRm wp 
              LEFT JOIN RawMaterial r ON wp.RawMaterialID = r.RawMaterialID
              WHERE wp.InventoryDate = @Today";
              
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