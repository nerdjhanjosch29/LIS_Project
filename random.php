<?php 

    require_once 'connection.php';
    $data = json_decode(file_get_contents('php://input'));
    // $order_details_id=$data->order_details_id;
    // var_dump($data);
    if ( sqlsrv_begin_transaction( $conn ) === false ) {
        die( print_r( sqlsrv_errors(), true ));
    } 


    $FinishProductID = $data->FinishProductID;

          if(isset($data))
            {  
             
              $sql = "SELECT wps.WarehousePartitionStockID,wps.StockingDate,wps.FinishProductID, wps.FinProdQty, wps.RawMaterialID,wps.RawMatsQty,wp.WarehousePartitionID, wp.WarehousePartitionName,w.WarehouseID, w.Warehouse_Name,
              wl.WarehouseLocation, wl.WarehouseLocationID
              FROM WarehousePartitionStock wps 
              LEFT JOIN WarehousePartition wp ON wps.WarehousePartitionID = wp.WarehousePartitionID 
              LEFT JOIN Warehouse w ON w.WarehouseID = wps.WarehouseID
              LEFT JOIN WarehouseLocation wl ON w.WarehouseLocationID = wl.WarehouseLocationID
              WHERE wps.FinishProductID <> 0 AND wps.FinProdQty <> 0 AND wps.FinishProductID = ?
              ";
              $params = array($FinishProductID);
              $stmt = sqlsrv_query($conn,$sql,$params); 
              while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
              {
                
              $Stock = $row['StockingDate'];
              var_dump($Stock);
              }

            }








?>