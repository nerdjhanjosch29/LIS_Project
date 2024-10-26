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
                 wps.WarehousePartitionStockID
                ,wp.WarehousePartitionID
                ,wp.WarehousePartitionName
                ,ISNULL(r.RawMaterialID,0) AS RawMaterialID
                ,r.RawMaterial
                ,wps.RawMatsQty
                ,wps.RawMatsWeight
                ,fp.FinishProductID
                ,fp.FinishProduct
                ,wps.FinProdQty
                ,wps.StockingDate
                 FROM WarehousePartitionStock wps
                 LEFT JOIN WarehousePartition wp ON wps.WarehousePartitionID = wp.WarehousePartitionID
                 LEFT JOIN RawMaterial r ON wps.RawMaterialID = r.RawMaterialID
                 LEFT JOIN FinishProduct fp ON wps.FinishProductID = fp.FinishProductID";
        $stmt1 = sqlsrv_query($conn,$sql); 
        if($stmt1)
        {
          $json = array();
          do {
            while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) 
            {
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