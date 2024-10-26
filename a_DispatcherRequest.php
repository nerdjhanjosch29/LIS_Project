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
         dr.DispatcherRequestID
        ,dr.RequestDate
        ,dr.FromWarehouseLocationID
        ,wl.WarehouseLocation AS FromWarehouseLocation
        ,dr.ToWarehouseLocationID
        ,wlt.WarehouseLocation AS ToWarehouseLocation
        ,dr.RawMaterialID
        ,rm.RawMaterial
        ,dr.RequestWeight
        ,ISNULL((SELECT SUM(Weight) FROM RawMaterialTransfer WHERE DispatcherRequestID = dr.DispatcherRequestID),0)AS Served
        ,ISNULL((SELECT dr.RequestWeight - SUM(Weight) FROM RawMaterialTransfer WHERE DispatcherRequestID = dr.DispatcherRequestID),0)AS Balance
        ,dr.Status
        ,dr.UserID
        ,dr.isDel
        FROM DispatcherRequest dr
        LEFT JOIN WarehouseLocation wl ON dr.FromWarehouseLOcationID = wl.WarehouseLocationID 
        LEFT JOIN WarehouseLocation wlt ON dr.ToWarehouseLocationID = wlt.WarehouseLocationID
        LEFT JOIN RawMaterial rm ON dr.RawMaterialID = rm.RawMaterialID
        WHERE dr.isDel = 'False' AND dr.Status <> 3";
        $stmt1 = sqlsrv_query($conn, $sql);
        if ($stmt1) {
            $json = array();
            while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                $shippingTransaction = $row;
                $RequestID = $row['DispatcherRequestID'];
          // Fetch PullOut details for the current ShippingTransaction
          $sqlPullOut = "SELECT 
                         rt.RawMaterialTransferID
                        ,rt.TransferCode
                        ,rt.DateTransfer
                        ,rt.DispatcherRequestID
                        ,rt.TransferTypeID
                        ,rt.TruckID
                        ,t.PlateNo
                        ,rt.DriverID
                        ,d.DriverName
                        ,rt.DispatcherID
                        ,dp.DispatcherName 
                        FROM RawMaterialTransfer rt
                        LEFT JOIN Truck t ON rt.TruckID = t.TruckID
                        LEFT JOIN Driver d ON rt.DriverID = d.DriverID
                        LEFT JOIN Dispatcher dp ON rt.DispatcherID = dp.DispatcherID WHERE rt.DispatcherRequestID = ?";  
          $params = array($RequestID);
          $stmt2 = sqlsrv_query($conn,$sqlPullOut, $params);
          $pullOutDetails = array();
          if ($stmt2) {
              while ($pullOutRow = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                  $pullOutDetails[] = $pullOutRow;
          }
          $shippingTransaction['TransferDetail'] = $pullOutDetails;
          $json[] = $shippingTransaction;
                }
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($json);
        } else {
            sqlsrv_rollback($conn);
            echo "Rollback";
        }
?>