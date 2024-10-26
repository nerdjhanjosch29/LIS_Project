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
        $status = "";
        $UserID = "";
        if(isset($_GET['status']))
        {
            $status = $_GET['status'];
        }
        if(isset($_GET['UserID']))
        {
            $UserID = $_GET['UserID'];
        }

        $date = date("Y-m-d");
        if($status == 0)
        {
          
            $sql = "SELECT rt.RawMaterialTransferID, 
			rt.TransferTypeID,
            rt.DateTransfer, 
            rt.PO,
            rt.BL,
            rt.WarehousePartitionStockID,
            wll_from.WarehouseLocationID AS FromWarehouseLocationID,
            wll_from.WarehouseLocation AS FromWarehouseLocation,
            rt.FromWarehouseID, 
            wl_from.Warehouse_Name AS FromWarehouse, 
            rt.FromWarehousePartitionID, 
            wp_from.WarehousePartitionName AS FromWarehousePartitionName, 
            wll_to.WarehouseLocationID AS ToWarehouseLocationID, 
            wll_to.WarehouseLocation AS ToWarehouseLocation,
            rt.ToWarehouseID, 
            rt.ArrivalWeight,
            rt.WeigherOut,
            rt.WeigherIn,
            rt.TransferCode,
            wl_to.Warehouse_Name AS ToWarehouse, 
            rt.ToWarehousePartitionID, 
            wp_to.WarehousePartitionName AS ToWarehousePartitionName, 
            rt.FeedmixDeparture, 
            rt.SourceArrival,
            rt.SourceDeparture, 
            rt.FeedmixArrival,
            rt.DispatcherRequestID,
            rt.DispatcherID,
            dp.DispatcherName,
            rt.GuardID,
            g.GuardName,
            CASE 
                WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) < 3600 THEN 
                    CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600 = 0 THEN 
                    CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s)')
                ELSE 
                    CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s) ', 
                    (DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
            END AS [TimeTravel1],
            CASE 
                WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) < 3600 THEN 
                    CONCAT((DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600 = 0 THEN 
                    CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s)')
                ELSE 
                    CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s) ', 
                    (DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
            END AS [LoadingorUnloadingTime], 
            CASE 
                WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) < 3600 THEN 
                    CONCAT((DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                    CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                ELSE 
                    CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                    (DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
            END AS [TimeTravel2], 
			            CASE 
                WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) < 3600 THEN 
                    CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                    CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                ELSE 
                    CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                    (DATEDIFF(SECOND, rt.FeedmixDeparture,rt.FeedmixArrival) % 3600) / 60, 'min(s)')
            END AS [IntervalTime],
            rt.RawMaterialID, 
            rm.RawMaterial, 
            rt.Status,
            t.TruckID, 
            rt.DriverID,
            d.DriverName,
            t.PlateNo, 
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
            WHERE rt.isDel = 'False' AND rt.Status = 0"; 
            $params = array($status);
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
        }
        else if($status == 1)
        {
            $ULevel = "";
            $sqlss = "SELECT ULevel FROM UserAccount WHERE UserID = ?";
            $paramss = array($UserID);
            $stmtss = sqlsrv_query($conn, $sqlss,$paramss);
            while($AccountsRows = sqlsrv_fetch_array($stmtss, SQLSRV_FETCH_ASSOC))
            {
              $ULevel = $AccountsRows['ULevel'];
            }
            $sqls = "SELECT TOP 1 (DateRotation)AS DateRotation, WarehouseLocationID FROM CheckerSchedule WHERE UserID = ? ORDER BY DateRotation DESC";
            $params = array($UserID);
            $stmts = sqlsrv_query($conn, $sqls,$params);
            $LocationID = "";
            $DateRotation = "";
            while($AccountsRow = sqlsrv_fetch_array($stmts, SQLSRV_FETCH_ASSOC))
            {
              $LocationID = $AccountsRow['WarehouseLocationID'];
              $DateRotation = $AccountsRow['DateRotation'];
            }
            if($LocationID == 1 AND $date <= $DateRotation)
            {
                $sql = "SELECT rt.RawMaterialTransferID, 
                rt.TransferTypeID,
                rt.DateTransfer, 
                rt.PO,
                rt.BL,
                rt.WarehousePartitionStockID,
                wll_from.WarehouseLocationID AS FromWarehouseLocationID,
                wll_from.WarehouseLocation AS FromWarehouseLocation,
                rt.FromWarehouseID, 
                wl_from.Warehouse_Name AS FromWarehouse, 
                rt.FromWarehousePartitionID, 
                wp_from.WarehousePartitionName AS FromWarehousePartitionName, 
                wll_to.WarehouseLocationID AS ToWarehouseLocationID, 
                wll_to.WarehouseLocation AS ToWarehouseLocation,
                rt.ToWarehouseID, 
                rt.ArrivalWeight,
                rt.WeigherOut,
                rt.WeigherIn,
                rt.TransferCode,
                wl_to.Warehouse_Name AS ToWarehouse, 
                rt.ToWarehousePartitionID, 
                wp_to.WarehousePartitionName AS ToWarehousePartitionName, 
                rt.FeedmixDeparture, 
                rt.SourceArrival,
                rt.SourceDeparture, 
                rt.FeedmixArrival,
                rt.DispatcherRequestID,
                rt.DispatcherID,
                rt.DispatcherRequestID,
                dp.DispatcherName,
                rt.GuardID,
                g.GuardName,
                CASE 
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                END AS [TimeTravel1],
                CASE 
                    WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                END AS [LoadingorUnloadingTime], 
                CASE 
                    WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                END AS [TimeTravel2], 
                            CASE 
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.FeedmixDeparture,rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                END AS [IntervalTime],
                rt.RawMaterialID, 
                rm.RawMaterial, 
                rt.Status,
                t.TruckID, 
                rt.DriverID,
                d.DriverName,
                t.PlateNo, 
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
                WHERE rt.isDel = 'False' AND (rt.Status = 0 OR rt.Status = 1) AND rt.FromWarehouseLocationID = 1 ORDER BY rt.Status DESC"; 
                $params = array($status);
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
            }
            else if($LocationID == 2 AND $date <= $DateRotation)
            {
                $sql = "SELECT rt.RawMaterialTransferID, 
                rt.TransferTypeID,
                rt.DateTransfer, 
                rt.PO,
                rt.BL,
                rt.WarehousePartitionStockID,
                wll_from.WarehouseLocationID AS FromWarehouseLocationID,
                wll_from.WarehouseLocation AS FromWarehouseLocation,
                rt.FromWarehouseID, 
                wl_from.Warehouse_Name AS FromWarehouse, 
                rt.FromWarehousePartitionID, 
                wp_from.WarehousePartitionName AS FromWarehousePartitionName, 
                wll_to.WarehouseLocationID AS ToWarehouseLocationID, 
                wll_to.WarehouseLocation AS ToWarehouseLocation,
                rt.ToWarehouseID, 
                rt.ArrivalWeight,
                rt.WeigherOut,
                rt.WeigherIn,
                rt.TransferCode,
                wl_to.Warehouse_Name AS ToWarehouse, 
                rt.ToWarehousePartitionID, 
                wp_to.WarehousePartitionName AS ToWarehousePartitionName, 
                rt.FeedmixDeparture, 
                rt.SourceArrival,
                rt.SourceDeparture, 
                rt.FeedmixArrival,
                rt.DispatcherID,
                rt.DispatcherRequestID,
                dp.DispatcherName,
                rt.GuardID,
                g.GuardName,
                CASE 
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                END AS [TimeTravel1],
                CASE 
                    WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                END AS [LoadingorUnloadingTime], 
                CASE 
                    WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                END AS [TimeTravel2], 
                            CASE 
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.FeedmixDeparture,rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                END AS [IntervalTime],
                rt.RawMaterialID, 
                rm.RawMaterial, 
                rt.Status,
                t.TruckID, 
                rt.DriverID,
                d.DriverName,
                t.PlateNo, 
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
                WHERE rt.isDel = 'False' AND (rt.Status = 0 OR rt.Status = 1) AND rt.FromWarehouseLocationID = 2 ORDER BY rt.Status DESC"; 
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
            else if($LocationID == 3 AND $date <= $DateRotation)
            {
                $sql = "SELECT rt.RawMaterialTransferID, 
                rt.TransferTypeID,
                rt.DateTransfer, 
                rt.PO,
                rt.BL,
                rt.WarehousePartitionStockID,
                wll_from.WarehouseLocationID AS FromWarehouseLocationID,
                wll_from.WarehouseLocation AS FromWarehouseLocation,
                rt.FromWarehouseID, 
                wl_from.Warehouse_Name AS FromWarehouse, 
                rt.FromWarehousePartitionID, 
                wp_from.WarehousePartitionName AS FromWarehousePartitionName, 
                wll_to.WarehouseLocationID AS ToWarehouseLocationID, 
                wll_to.WarehouseLocation AS ToWarehouseLocation,
                rt.ToWarehouseID, 
                rt.ArrivalWeight,
                rt.WeigherOut,
                rt.WeigherIn,
                rt.TransferCode,
                wl_to.Warehouse_Name AS ToWarehouse, 
                rt.ToWarehousePartitionID, 
                wp_to.WarehousePartitionName AS ToWarehousePartitionName, 
                rt.FeedmixDeparture, 
                rt.SourceArrival,
                rt.SourceDeparture, 
                rt.FeedmixArrival,
                rt.DispatcherID,
                rt.DispatcherRequestID,
                dp.DispatcherName,
                rt.GuardID,
                g.GuardName,
                CASE 
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                END AS [TimeTravel1],
    
                CASE 
                    WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                END AS [LoadingorUnloadingTime], 
                CASE 
                    WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                END AS [TimeTravel2], 
                            CASE 
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.FeedmixDeparture,rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                END AS [IntervalTime],
                rt.RawMaterialID, 
                rm.RawMaterial, 
                rt.Status,
                t.TruckID, 
                rt.DriverID,
                d.DriverName,
                t.PlateNo, 
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
                WHERE rt.isDel = 'False' AND (rt.Status = 0 OR rt.Status = 1) AND rt.FromWarehouseLocationID = 3 ORDER BY rt.Status DESC"; 
                $params = array($status);
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
            }
            else if($LocationID == 4 AND $date <= $DateRotation)
            {
                $sql = "SELECT rt.RawMaterialTransferID, 
                rt.TransferTypeID,
                rt.DateTransfer, 
                rt.PO,
                rt.BL,
                rt.WarehousePartitionStockID,
                wll_from.WarehouseLocationID AS FromWarehouseLocationID,
                wll_from.WarehouseLocation AS FromWarehouseLocation,
                rt.FromWarehouseID, 
                wl_from.Warehouse_Name AS FromWarehouse, 
                rt.FromWarehousePartitionID, 
                wp_from.WarehousePartitionName AS FromWarehousePartitionName, 
                wll_to.WarehouseLocationID AS ToWarehouseLocationID, 
                wll_to.WarehouseLocation AS ToWarehouseLocation,
                rt.ToWarehouseID, 
                rt.ArrivalWeight,
                rt.WeigherOut,
                rt.WeigherIn,
                rt.TransferCode,
                wl_to.Warehouse_Name AS ToWarehouse, 
                rt.ToWarehousePartitionID, 
                wp_to.WarehousePartitionName AS ToWarehousePartitionName, 
                rt.FeedmixDeparture, 
                rt.SourceArrival,
                rt.SourceDeparture, 
                rt.FeedmixArrival,
                rt.DispatcherID,
                rt.DispatcherRequestID,
                dp.DispatcherName,
                rt.GuardID,
                g.GuardName,
                CASE 
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                END AS [TimeTravel1],
    
                CASE 
                    WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                END AS [LoadingorUnloadingTime], 
                CASE 
                    WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                END AS [TimeTravel2], 
                            CASE 
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.FeedmixDeparture,rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                END AS [IntervalTime],
                rt.RawMaterialID, 
                rm.RawMaterial, 
                rt.Status,
                t.TruckID, 
                rt.DriverID,
                d.DriverName,
                t.PlateNo, 
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
                WHERE rt.isDel = 'False' AND (rt.Status = 0 OR rt.Status = 1) AND rt.FromWarehouseLocationID = 4 ORDER BY rt.Status DESC"; 
                $params = array($status);
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
            }
            else if($ULevel == 4)
            {
                $sql = "SELECT rt.RawMaterialTransferID, 
                rt.TransferTypeID,
                rt.DateTransfer, 
                rt.PO,
                rt.BL,
                rt.WarehousePartitionStockID,
                wll_from.WarehouseLocationID AS FromWarehouseLocationID,
                wll_from.WarehouseLocation AS FromWarehouseLocation,
                rt.FromWarehouseID, 
                wl_from.Warehouse_Name AS FromWarehouse, 
                rt.FromWarehousePartitionID, 
                wp_from.WarehousePartitionName AS FromWarehousePartitionName, 
                wll_to.WarehouseLocationID AS ToWarehouseLocationID, 
                wll_to.WarehouseLocation AS ToWarehouseLocation,
                rt.ToWarehouseID, 
                rt.ArrivalWeight,
                rt.WeigherOut,
                rt.WeigherIn,
                rt.TransferCode,
                wl_to.Warehouse_Name AS ToWarehouse, 
                rt.ToWarehousePartitionID, 
                wp_to.WarehousePartitionName AS ToWarehousePartitionName, 
                rt.FeedmixDeparture, 
                rt.SourceArrival,
                rt.SourceDeparture, 
                rt.FeedmixArrival,
                rt.DispatcherID,
                rt.DispatcherRequestID,
                dp.DispatcherName,
                rt.GuardID,
                g.GuardName,
                CASE 
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                END AS [TimeTravel1],
    
                CASE 
                    WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                END AS [LoadingorUnloadingTime], 
                CASE 
                    WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                END AS [TimeTravel2], 
                            CASE 
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) < 3600 THEN 
                        CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                    WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                    ELSE 
                        CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                        (DATEDIFF(SECOND, rt.FeedmixDeparture,rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                END AS [IntervalTime],
                rt.RawMaterialID, 
                rm.RawMaterial, 
                rt.Status,
                t.TruckID, 
                rt.DriverID,
                d.DriverName,
                t.PlateNo, 
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
                WHERE rt.isDel = 'False' AND (rt.Status = 0 OR rt.Status = 1) ORDER BY rt.Status DESC"; 
                $params = array($status);
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
            }
        }
        else if($status == 2)
        {
            $sql = "SELECT rt.RawMaterialTransferID, 
			rt.TransferTypeID,
            rt.DateTransfer, 
            rt.PO,
            rt.BL,
            rt.WarehousePartitionStockID,
            wll_from.WarehouseLocationID AS FromWarehouseLocationID,
            wll_from.WarehouseLocation AS FromWarehouseLocation,
            rt.FromWarehouseID, 
            wl_from.Warehouse_Name AS FromWarehouse, 
            rt.FromWarehousePartitionID, 
            wp_from.WarehousePartitionName AS FromWarehousePartitionName, 
            wll_to.WarehouseLocationID AS ToWarehouseLocationID, 
            wll_to.WarehouseLocation AS ToWarehouseLocation,
            rt.ToWarehouseID, 
            rt.ArrivalWeight,
            rt.WeigherOut,
            rt.WeigherIn,
            rt.TransferCode,
            wl_to.Warehouse_Name AS ToWarehouse, 
            rt.ToWarehousePartitionID, 
            wp_to.WarehousePartitionName AS ToWarehousePartitionName, 
            rt.FeedmixDeparture, 
            rt.SourceArrival,
            rt.SourceDeparture, 
            rt.FeedmixArrival,
            rt.DispatcherID,
            rt.DispatcherRequestID,
            dp.DispatcherName,
            rt.GuardID,
            g.GuardName,
            CASE 
                WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) < 3600 THEN 
                    CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
                WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600 = 0 THEN 
                    CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s)')
                ELSE 
                    CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) / 3600, 'hr(s) ', 
                    (DATEDIFF(SECOND, rt.FeedmixDeparture, rt.SourceArrival) % 3600) / 60, 'min(s)')
            END AS [TimeTravel1],
            CASE 
                WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) < 3600 THEN 
                    CONCAT((DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
                WHEN DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600 = 0 THEN 
                    CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s)')
                ELSE 
                    CONCAT(DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) / 3600, 'hr(s) ', 
                    (DATEDIFF(SECOND, rt.SourceArrival, rt.SourceDeparture) % 3600) / 60, 'min(s)')
            END AS [LoadingorUnloadingTime], 
            CASE 
                WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) < 3600 THEN 
                    CONCAT((DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                WHEN DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                    CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                ELSE 
                    CONCAT(DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                    (DATEDIFF(SECOND, rt.SourceDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
            END AS [TimeTravel2], 
			            CASE 
                WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) < 3600 THEN 
                    CONCAT((DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600) / 60, 'min(s)')
                WHEN DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) % 3600 = 0 THEN 
                    CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s)')
                ELSE 
                    CONCAT(DATEDIFF(SECOND, rt.FeedmixDeparture, rt.FeedmixArrival) / 3600, 'hr(s) ', 
                    (DATEDIFF(SECOND, rt.FeedmixDeparture,rt.FeedmixArrival) % 3600) / 60, 'min(s)')
            END AS [IntervalTime],
            rt.RawMaterialID, 
            rm.RawMaterial, 
            rt.Status,
            t.TruckID, 
            rt.DriverID,
            d.DriverName,
            t.PlateNo, 
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
            WHERE rt.isDel = 'False' AND rt.Status = 2"; 
            $params = array($status);
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
        }
        else 
        {   
            $sql = "SELECT dr.DispatcherRequestID, dr.RequestDate, dr.FromWarehouseLocationID, 
            wl.WarehouseLocation AS FromWarehouseLocation, dr.ToWarehouseLocationID, 
            wlt.WarehouseLocation AS ToWarehouseLocation, dr.RawMaterialID, rm.RawMaterial, 
            dr.RequestWeight, ISNULL(SUM(rmt.Weight), 0) AS Served,
            ISNULL(dr.RequestWeight - SUM(rmt.Weight), 0) AS Balance,
            dr.Status, dr.UserID, dr.isDel 
            FROM DispatcherRequest dr
            LEFT JOIN WarehouseLocation wl ON dr.FromWarehouseLocationID = wl.WarehouseLocationID 
            LEFT JOIN WarehouseLocation wlt ON dr.ToWarehouseLocationID = wlt.WarehouseLocationID
            LEFT JOIN RawMaterial rm ON dr.RawMaterialID = rm.RawMaterialID
            LEFT JOIN RawMaterialTransfer rmt ON dr.DispatcherRequestID = rmt.DispatcherRequestID
            AND rmt.Status = 3
            WHERE dr.isDel = 'False'
            GROUP BY dr.DispatcherRequestID, dr.RequestDate, dr.FromWarehouseLocationID, 
              wl.WarehouseLocation, dr.ToWarehouseLocationID, wlt.WarehouseLocation, 
              dr.RawMaterialID, rm.RawMaterial, dr.RequestWeight, dr.Status, dr.UserID, dr.isDel
             HAVING COUNT(rmt.DispatcherRequestID) > 0"; 
            $stmt1 = sqlsrv_query($conn, $sql);
            if ($stmt1) {
                $json = array();
                while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                        $shippingTransaction = $row;
                        $RequestID = $row['DispatcherRequestID'];
              // Fetch PullOut details for the current ShippingTransaction
              $sqlPullOut = "SELECT rt.RawMaterialTransferID, 
              rt.TransferTypeID,
              rt.DateTransfer, 
              rt.PO,
              rt.BL,
              rt.WarehousePartitionStockID,
              wll_from.WarehouseLocationID AS FromWarehouseLocationID,
              wll_from.WarehouseLocation AS FromWarehouseLocation,
              rt.FromWarehouseID, 
              wl_from.Warehouse_Name AS FromWarehouse, 
              rt.FromWarehousePartitionID, 
              wp_from.WarehousePartitionName AS FromWarehousePartitionName, 
              wll_to.WarehouseLocationID AS ToWarehouseLocationID, 
              wll_to.WarehouseLocation AS ToWarehouseLocation,
              rt.ToWarehouseID, 
              rt.ArrivalWeight,
              rt.WeigherOut,
              rt.WeigherIn,
              rt.TransferCode,
              wl_to.Warehouse_Name AS ToWarehouse, 
              rt.ToWarehousePartitionID, 
              wp_to.WarehousePartitionName AS ToWarehousePartitionName, 
              rt.FeedmixDeparture, 
              rt.SourceArrival,
              rt.SourceDeparture, 
              rt.FeedmixArrival,
              rt.DispatcherID,
              rt.DispatcherRequestID,
              dp.DispatcherName,
              rt.GuardID,
              g.GuardName,
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
              END AS [IntervalTime],
              rt.RawMaterialID, 
              rm.RawMaterial, 
              rt.Status,
              t.TruckID, 
              rt.DriverID,
              d.DriverName,
              t.PlateNo, 
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
              WHERE rt.isDel = 'False' AND rt.Status = 3 AND rt.DispatcherRequestID = ?";
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
        }

        //Insert Query to save the system log of the User
        // $SystemLogID = 0;
      
        // $FunctionID = 7;
        // $TableName = "User Access";
        // $sql = "EXEC [dbo].[SystemLogFetch]
        //         @SystemLogID = ?,
        //         @UserID = ?,
        //         @FunctionID = ?,
        //         @TableName = ?";
        // $params =  array($SystemLogID, $UserID, $FunctionID, $TableName);
        // $stmt = sqlsrv_query($conn, $sql, $params);
        // var_dump($stmt);
        // sqlsrv_commit($conn);
?>