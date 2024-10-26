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
        $TransferIDToday = "";
            $sql = "DECLARE @Today date = GETDATE();
                    SELECT
                    rt.RawMaterialTransferID
                    FROM RawMaterialTransfer rt
                    WHERE rt.DateTransfer = @Today";
        $stmt = sqlsrv_query($conn, $sql);  
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) 
        {
            $TransferIDToday = $row['RawMaterialTransferID'];
        }
            if($TransferIDToday == "")
            {
                $sql = "SELECT 
				TOP(1)rt.RawMaterialTransferID
                ,rt.TransferTypeID
                ,rt.DateTransfer
                ,rt.WarehousePartitionStockID
                ,wll_from.WarehouseLocationID AS FromWarehouseLocationID
                ,wll_from.WarehouseLocation AS FromWarehouseLocation
                ,rt.FromWarehouseID
                ,wl_from.Warehouse_Name AS FromWarehouse
                ,rt.FromWarehousePartitionID
                ,wp_from.WarehousePartitionName AS FromWarehousePartitionName
                ,wll_to.WarehouseLocationID AS ToWarehouseLocationID
                ,wll_to.WarehouseLocation AS ToWarehouseLocation
                ,rt.ToWarehouseID
                ,rt.ArrivalWeight
                ,rt.WeigherOut
                ,rt.WeigherIn
                ,rt.TransferCode
                ,wl_to.Warehouse_Name AS ToWarehouse
                ,rt.ToWarehousePartitionID
                ,wp_to.WarehousePartitionName AS ToWarehousePartitionName
                ,rt.FeedmixDeparture
                ,rt.SourceArrival
                ,rt.SourceDeparture
                ,rt.FeedmixArrival
                ,rt.DispatcherID
                ,rt.DispatcherRequestID
                ,dp.DispatcherName
                ,rt.GuardID
                ,g.GuardName,
                CASE 
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s) ', (DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                END AS [TimeTravel1],
                CASE 
                    WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s) ', (DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                END AS [LoadingorUnloadingTime], 
                CASE 
                    WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', (DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                END AS [TimeTravel2], 
                CASE 
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', (DATEDIFF(SECOND, rt.FeedmixDeparture,rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                END AS [IntervalTime]
                ,rt.RawMaterialID
                ,rm.RawMaterial
                ,rt.Status
                ,t.TruckID
                ,rt.DriverID
                ,d.DriverName
                ,t.PlateNo, 
                rt.Quantity, 
                rt.Weight, 
                c.CheckerID, 
                c.CheckerName,
                rt.LossQuantity,
                rt.OverQuantity,
                rt.LossWeight,
                rt.OverWeight,
                rt.UserID 
                FROM dbo.RawMaterialTransfer AS rt
                LEFT OUTER JOIN dbo.WarehousePartition AS wp_from ON wp_from.WarehousePartitionID = rt.FromWarehousePartitionID 
                LEFT OUTER JOIN dbo.WarehousePartition AS wp_to ON wp_to.WarehousePartitionID = rt.ToWarehousePartitionID 
                LEFT OUTER JOIN dbo.RawMaterial AS rm ON rm.RawMaterialID = rt.RawMaterialID 
                LEFT OUTER JOIN dbo.Warehouse AS wl_from ON wl_from.WarehouseID = rt.FromWarehouseID 
                LEFT OUTER JOIN dbo.Warehouse AS wl_to ON wl_to.WarehouseID = rt.ToWarehouseID 
                LEFT OUTER JOIN dbo.Checker AS c ON c.CheckerID = rt.CheckerID 
                LEFT OUTER JOIN dbo.Truck AS t ON rt.TruckID = t.TruckID 
                LEFT OUTER JOIN dbo.Driver AS d ON rt.DriverID = d.DriverID
                LEFT JOIN WarehouseLocation wll_from ON wll_from.WarehouseLocationID = rt.FromWarehouseLocationID
                LEFT JOIN WarehouseLocation wll_to ON wll_to.WarehouseLocationID = rt.ToWarehouseLocationID
                LEFT JOIN Dispatcher dp ON rt.DispatcherID= dp.DispatcherID
                LEFT JOIN Guard g ON rt.GuardID = g.GuardID
                WHERE rt.isDel = 'False' ORDER BY rt.RawMaterialTransferID DESC"; 
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
            }
            else
            {
                            $sql = "DECLARE @Today date = GETDATE();
                                     SELECT 
                                     rt.RawMaterialTransferID
                                    ,rt.TransferTypeID
                                    ,rt.DateTransfer
                                    ,rt.WarehousePartitionStockID
                                    ,wll_from.WarehouseLocationID AS FromWarehouseLocationID
                                    ,wll_from.WarehouseLocation AS FromWarehouseLocation
                                    ,rt.FromWarehouseID
                                    ,wl_from.Warehouse_Name AS FromWarehouse
                                    ,rt.FromWarehousePartitionID
                                    ,wp_from.WarehousePartitionName AS FromWarehousePartitionName
                                    ,wll_to.WarehouseLocationID AS ToWarehouseLocationID
                                    ,wll_to.WarehouseLocation AS ToWarehouseLocation
                                    ,rt.ToWarehouseID
                                    ,rt.ArrivalWeight
                                    ,rt.WeigherOut
                                    ,rt.WeigherIn
                                    ,rt.TransferCode
                                    ,wl_to.Warehouse_Name AS ToWarehouse
                                    ,rt.ToWarehousePartitionID
                                    ,wp_to.WarehousePartitionName AS ToWarehousePartitionName
                                    ,rt.FeedmixDeparture
                                    ,rt.SourceArrival
                                    ,rt.SourceDeparture
                                    ,rt.FeedmixArrival
                                    ,rt.DispatcherID
                                    ,rt.DispatcherRequestID
                                    ,dp.DispatcherName
                                    ,rt.GuardID
                                    ,g.GuardName,
                                    CASE 
                                        WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) < 3600 THEN 
                                            CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                                        WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600 = 0 THEN 
                                            CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s)')
                                        ELSE 
                                            CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s) ', (DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                                    END AS [TimeTravel1],
                                    CASE 
                                        WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) < 3600 THEN 
                                            CONCAT((DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                                        WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600 = 0 THEN 
                                            CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s)')
                                        ELSE 
                                            CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s) ', (DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                                    END AS [LoadingorUnloadingTime], 
                                    CASE 
                                        WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) < 3600 THEN 
                                            CONCAT((DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                                        WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                                            CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                                        ELSE 
                                            CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', (DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                                    END AS [TimeTravel2], 
                                                CASE 
                                        WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) < 3600 THEN 
                                            CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                                        WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                                            CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                                        ELSE 
                                            CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', (DATEDIFF(SECOND, rt.FeedmixDeparture,rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                                    END AS [IntervalTime]
                                    ,rt.RawMaterialID
                                    ,rm.RawMaterial
                                    ,rt.Status
                                    ,t.TruckID
                                    ,rt.DriverID
                                    ,d.DriverName
                                    ,t.PlateNo
                                    ,rt.Quantity
                                    ,rt.Weight
                                    ,c.CheckerID 
                                    ,c.CheckerName
                                    ,rt.LossQuantity
                                    ,rt.OverQuantity
                                    ,rt.LossWeight
                                    ,rt.OverWeight
                                    ,rt.UserID 
                                    FROM dbo.RawMaterialTransfer AS rt
                                        LEFT OUTER JOIN dbo.WarehousePartition AS wp_from ON wp_from.WarehousePartitionID = rt.FromWarehousePartitionID 
                                        LEFT OUTER JOIN dbo.WarehousePartition AS wp_to ON wp_to.WarehousePartitionID = rt.ToWarehousePartitionID 
                                        LEFT OUTER JOIN dbo.RawMaterial AS rm ON rm.RawMaterialID = rt.RawMaterialID 
                                        LEFT OUTER JOIN dbo.Warehouse AS wl_from ON wl_from.WarehouseID = rt.FromWarehouseID 
                                        LEFT OUTER JOIN dbo.Warehouse AS wl_to ON wl_to.WarehouseID = rt.ToWarehouseID 
                                        LEFT OUTER JOIN dbo.Checker AS c ON c.CheckerID = rt.CheckerID 
                                        LEFT OUTER JOIN dbo.Truck AS t ON rt.TruckID = t.TruckID 
                                        LEFT OUTER JOIN dbo.Driver AS d ON rt.DriverID = d.DriverID
                                        LEFT JOIN WarehouseLocation wll_from ON wll_from.WarehouseLocationID = rt.FromWarehouseLocationID
                                        LEFT JOIN WarehouseLocation wll_to ON wll_to.WarehouseLocationID = rt.ToWarehouseLocationID
                                        LEFT JOIN Dispatcher dp ON rt.DispatcherID= dp.DispatcherID
                                        LEFT JOIN Guard g ON rt.GuardID = g.GuardID
                                        WHERE rt.isDel = 'False' AND rt.DateTransfer = @Today AND Status = 3 ORDER BY rt.RawMaterialTransferID DESC"; 
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
            }
    ?>