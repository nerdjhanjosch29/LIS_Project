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
                 po.PlantID
                ,p.PlantName
                ,po.LineNumber
                ,po.FinishProductID
                ,fp.FinishProduct
                ,po.WarehouseID
                ,w.Warehouse_Name
                ,po.WarehousePartitionID
                ,wp.WarehousePartitionName
                ,po.DateTimeOutput
                ,po.DateOutput
                ,po.Quantity
                ,po.Weight
                ,po.UserID
                FROM ProductionOutput po
                LEFT JOIN Plant p ON po.PlantID = p.PlantID
                LEFT JOIN FinishProduct fp ON po.FinishProductID = fp.FinishProductID
                LEFT JOIN Warehouse w ON po.WarehouseID = w.WarehouseID 
                LEFT JOIN WarehousePartition wp ON po.WarehousePartitionID = wp.WarehousePartitionID";
               
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