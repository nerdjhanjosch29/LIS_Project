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
    $FinishProductID = "";
    if(isset($_GET['id']))
    {
    $FinishProductID = $_GET['id'];
    }
        $sql = "SELECT
                 wps.WarehousePartitionStockID
                ,wps.StockingDate
                ,wps.FinishProductID
                ,wps.FinProdQty
                ,wps.FinProdWeight
                ,wps.RawMaterialID
                ,wps.RawMatsQty
                ,wp.WarehousePartitionID
                ,wp.WarehousePartitionName
                ,w.WarehouseID
                ,w.Warehouse_Name
                ,wl.WarehouseLocation
                ,wl.WarehouseLocationID
                FROM WarehousePartitionStock wps 
                LEFT JOIN WarehousePartition wp ON wps.WarehousePartitionID = wp.WarehousePartitionID 
                LEFT JOIN Warehouse w ON w.WarehouseID = wps.WarehouseID
                LEFT JOIN WarehouseLocation wl ON w.WarehouseLocationID = wl.WarehouseLocationID
                WHERE wps.FinishProductID <> 0 AND wps.FinProdQty <> 0 AND wps.FinishProductID = ?";
        $params = array($FinishProductID);
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