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


    $UserID = "";
    if(isset($_GET['id']))
    {
      $UserID = $_GET['id'];
    }


    $date = date("Y-m-d");
    $ULevel = "";
    $sqlss = "SELECT ULevel FROM UserAccount WHERE UserID = ?";
    $paramss = array($UserID);
    $stmtss = sqlsrv_query($conn, $sqlss,$paramss);
    while($AccountsRows = sqlsrv_fetch_array($stmtss, SQLSRV_FETCH_ASSOC))
    {
      $ULevel = $AccountsRows['ULevel'];
    }
    // var_dump($ULevel);
    $sqls = "SELECT TOP 1 (DateRotation)AS DateRotation,PlantID FROM CheckerSchedule WHERE UserID = ? ORDER BY DateRotation DESC";
    $params = array($UserID);
    $stmts = sqlsrv_query($conn,$sqls,$params);
    // $LocationID = "";
    $DateRotation = "";
    $PlantID = "";
    while($AccountsRow = sqlsrv_fetch_array($stmts, SQLSRV_FETCH_ASSOC))
    {
      // $LocationID = $AccountsRow['WarehouseLocationID'];
      $DateRotation = $AccountsRow['DateRotation'];
      $PlantID = $AccountsRow['PlantID']; 
    }
    //    var_dump($stmtss);
     if($PlantID == 1 AND $date <= $DateRotation)
    {
        $sql = "SELECT         
                 br.BinloadRequestID
                ,br.PO
                ,br.BL
                ,br.WarehouseLocationID
                ,wl.WarehouseLocation
				,br.WarehouseID
				,br.WarehousePartitionID
				,wp.WarehousePartitionName
				,br.WarehousePartitionStockID
				,w.Warehouse_Name
                ,br.PlantID
                ,p.PlantName
                ,br.DriverID
                ,d.DriverName
                ,br.TruckID
                ,t.PlateNo
                ,br.RequestDate
                ,br.RawMaterialID
                ,r.RawMaterial
                ,br.Quantity
                ,br.Weight
                ,br.Status
                ,br.UserID
                ,(ISNULL((SELECT SUM(b.Quantity) FROM Binloading b
                    WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BinloadQty
                ,(br.Quantity - ISNULL((SELECT SUM(b.Quantity) FROM Binloading b
                    WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BalanceQty
				,ISNULL((SELECT SUM(b.Weight) FROM Binloading b
					WHERE br.BinloadRequestID = b.BinloadRequestID),0)AS BinloadWeight
				,(br.Weight - ISNULL((SELECT SUM(b.Weight) FROM Binloading b
					WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BalanceWeight
				  FROM BinloadRequest br
                LEFT JOIN WarehouseLocation wl ON br.WarehouseLocationID = wl.WarehouseLocationID
                LEFT JOIN Plant p ON br.PlantID = p.PlantID
                LEFT JOIN Driver d ON br.DriverID = d.DriverID
                LEFT JOIN Truck t ON br.TruckID = t.TruckID
                LEFT JOIN RawMaterial r ON br.RawMaterialID = r.RawMaterialID
				LEFT JOIN Warehouse w ON br.WarehouseID = w.WarehouseID
				LEFT JOIN WarehousePartition wp ON br.WarehousePartitionID = wp.WarehousePartitionID
                WHERE br.PlantID = 1";     
        $stmt1 = sqlsrv_query($conn,$sql);
        $json = array();
        if ($stmt1) {
            while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) 
            {
                $Binload = $row;
                $BinloadRequestID = $row['BinloadRequestID'];
                // Fetch Binloading details for the current Binload
                    $sql = "SELECT          
                            bl.BinloadingDate
                            ,bl.BinloadingID
                            ,bl.BinloadRequestID
                            ,bl.ControlNo
                            ,bl.CheckerID
                            ,c.CheckerName
                            ,bl.IntakeID
                            ,bl.PlantID
                            ,bl.Weight
                            ,bl.Quantity
                            FROM Binloading bl
                            LEFT JOIN Checker c ON bl.CheckerID = c.CheckerID
                            WHERE BinloadRequestID = ?";        
                $params = array($BinloadRequestID);
                $stmt = sqlsrv_query($conn, $sql, $params); 
                $BinloadArrayRow = array();
                if ($stmt) {
                    while ($BinloadingRow = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        $BinloadArrayRow[] = $BinloadingRow;
                    } 
                }
                // Add Binloading to the Binload object
                $Binload['Binloading'] = $BinloadArrayRow;
                // Add the combined Binload object to the json array
                $json[] = $Binload;
            }
        }
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($json);
    }
  
     else if($PlantID == 2 AND $date <= $DateRotation )
    {
                $sql = "SELECT         
				 br.BinloadRequestID
                ,br.PO
                ,br.BL
                ,br.WarehouseLocationID
                ,wl.WarehouseLocation
				,br.WarehouseID
				,br.WarehousePartitionID
				,wp.WarehousePartitionName
				,br.WarehousePartitionStockID
				,w.Warehouse_Name
                ,br.PlantID
                ,p.PlantName
                ,br.DriverID
                ,d.DriverName
                ,br.TruckID
                ,t.PlateNo
                ,br.RequestDate
                ,br.RawMaterialID
                ,r.RawMaterial
                ,br.Quantity
                ,br.Weight
                ,br.Status
                ,br.UserID
                ,(ISNULL((SELECT SUM(b.Quantity) FROM Binloading b
                    WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BinloadQty
                ,(br.Quantity - ISNULL((SELECT SUM(b.Quantity) FROM Binloading b
                    WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BalanceQty
				,ISNULL((SELECT SUM(b.Weight) FROM Binloading b
					WHERE br.BinloadRequestID = b.BinloadRequestID),0)AS BinloadWeight
				,(br.Weight - ISNULL((SELECT SUM(b.Weight) FROM Binloading b
					WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BalanceWeight
				  FROM BinloadRequest br
                LEFT JOIN WarehouseLocation wl ON br.WarehouseLocationID = wl.WarehouseLocationID
                LEFT JOIN Plant p ON br.PlantID = p.PlantID
                LEFT JOIN Driver d ON br.DriverID = d.DriverID
                LEFT JOIN Truck t ON br.TruckID = t.TruckID
                LEFT JOIN RawMaterial r ON br.RawMaterialID = r.RawMaterialID
				LEFT JOIN Warehouse w ON br.WarehouseID = w.WarehouseID
				LEFT JOIN WarehousePartition wp ON br.WarehousePartitionID = wp.WarehousePartitionID
                WHERE br.PlantID = 2";     
        $stmt1 = sqlsrv_query($conn,$sql);
        $json = array();
        if ($stmt1) {
            while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                $Binload = $row;
                $BinloadRequestID = $row['BinloadRequestID'];
                // // Fetch Binloading details for the current Binload
                    $sql = "SELECT          
                             bl.BinloadingDate
                            ,bl.BinloadingID
                            ,bl.BinloadRequestID
                            ,bl.ControlNo
                            ,bl.CheckerID
                            ,c.CheckerName
                            ,bl.IntakeID
                            ,bl.PlantID
                            ,bl.Weight
                            ,bl.Quantity
                            FROM Binloading bl
                            LEFT JOIN Checker c ON bl.CheckerID = c.CheckerID
                            WHERE BinloadRequestID = ?";        
                $params = array($BinloadRequestID);
                $stmt = sqlsrv_query($conn, $sql, $params); 
                $BinloadArrayRow = array();
                if ($stmt) {
                    while ($BinloadingRow = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        $BinloadArrayRow[] = $BinloadingRow;
                    } 
                }
                // Add Binloading to the Binload object
                $Binload['Binloading'] = $BinloadArrayRow;
                // Add the combined Binload object to the json array
                $json[] = $Binload;
            }
        }
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($json);
    }
     else if($PlantID == 3 AND $date <= $DateRotation)
    {
                $sql = "SELECT         
				 br.BinloadRequestID
                ,br.PO
                ,br.BL
                ,br.WarehouseLocationID
                ,wl.WarehouseLocation
				,br.WarehouseID
				,br.WarehousePartitionID
				,wp.WarehousePartitionName
				,br.WarehousePartitionStockID
				,w.Warehouse_Name
                ,br.PlantID
                ,p.PlantName
                ,br.DriverID
                ,d.DriverName
                ,br.TruckID
                ,t.PlateNo
                ,br.RequestDate
                ,br.RawMaterialID
                ,r.RawMaterial
                ,br.Quantity
                ,br.Weight
                ,br.Status
                ,br.UserID
                ,(ISNULL((SELECT SUM(b.Quantity) FROM Binloading b
                    WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BinloadQty
                ,(br.Quantity - ISNULL((SELECT SUM(b.Quantity) FROM Binloading b
                    WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BalanceQty
				,ISNULL((SELECT SUM(b.Weight) FROM Binloading b
					WHERE br.BinloadRequestID = b.BinloadRequestID),0)AS BinloadWeight
				,(br.Weight - ISNULL((SELECT SUM(b.Weight) FROM Binloading b
					WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BalanceWeight
				  FROM BinloadRequest br
                LEFT JOIN WarehouseLocation wl ON br.WarehouseLocationID = wl.WarehouseLocationID
                LEFT JOIN Plant p ON br.PlantID = p.PlantID
                LEFT JOIN Driver d ON br.DriverID = d.DriverID
                LEFT JOIN Truck t ON br.TruckID = t.TruckID
                LEFT JOIN RawMaterial r ON br.RawMaterialID = r.RawMaterialID
				LEFT JOIN Warehouse w ON br.WarehouseID = w.WarehouseID
				LEFT JOIN WarehousePartition wp ON br.WarehousePartitionID = wp.WarehousePartitionID
                WHERE br.PlantID = 3";     
        $stmt1 = sqlsrv_query($conn,$sql);
        $json = array();
        if ($stmt1) {
            while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                $Binload = $row;
                $BinloadRequestID = $row['BinloadRequestID'];
                // Fetch PullOut details for the current Binload
                    $sql = "SELECT          
                             bl.BinloadingDate
                            ,bl.BinloadingID
                            ,bl.BinloadRequestID
                            ,bl.ControlNo
                            ,bl.CheckerID
                            ,c.CheckerName
                            ,bl.IntakeID
                            ,bl.PlantID
                            ,bl.Weight
                            ,bl.Quantity
                            FROM Binloading bl
                            LEFT JOIN Checker c ON bl.CheckerID = c.CheckerID
                            WHERE BinloadRequestID = ?";        
                $params = array($BinloadRequestID);
                $stmt = sqlsrv_query($conn, $sql, $params); 
                $BinloadArrayRow = array();
                if ($stmt) {
                    while ($BinloadingRow = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        $BinloadArrayRow[] = $BinloadingRow;
                    } 
                }
                // Add Binloading to the Binload object
                $Binload['Binloading'] = $BinloadArrayRow;
                // Add the combined Binload object to the json array
                $json[] = $Binload;
            }
        }
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($json);
    }
     else if($PlantID == 4 AND $date <= $DateRotation )
    {
                $sql = "SELECT         
				br.BinloadRequestID
                ,br.PO
                ,br.BL
                ,br.WarehouseLocationID
                ,wl.WarehouseLocation
				,br.WarehouseID
				,br.WarehousePartitionID
				,wp.WarehousePartitionName
				,br.WarehousePartitionStockID
				,w.Warehouse_Name
                ,br.PlantID
                ,p.PlantName
                ,br.DriverID
                ,d.DriverName
                ,br.TruckID
                ,t.PlateNo
                ,br.RequestDate
                ,br.RawMaterialID
                ,r.RawMaterial
                ,br.Quantity
                ,br.Weight
                ,br.Status
                ,br.UserID     
                ,(ISNULL((SELECT SUM(b.Quantity) FROM Binloading b
                    WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BinloadQty
                ,(br.Quantity - ISNULL((SELECT SUM(b.Quantity) FROM Binloading b
                    WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BalanceQty
				,ISNULL((SELECT SUM(b.Weight) FROM Binloading b
					WHERE br.BinloadRequestID = b.BinloadRequestID),0)AS BinloadWeight
				,(br.Weight - ISNULL((SELECT SUM(b.Weight) FROM Binloading b
					WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BalanceWeight
				  FROM BinloadRequest br
                LEFT JOIN WarehouseLocation wl ON br.WarehouseLocationID = wl.WarehouseLocationID
                LEFT JOIN Plant p ON br.PlantID = p.PlantID
                LEFT JOIN Driver d ON br.DriverID = d.DriverID
                LEFT JOIN Truck t ON br.TruckID = t.TruckID
                LEFT JOIN RawMaterial r ON br.RawMaterialID = r.RawMaterialID
				LEFT JOIN Warehouse w ON br.WarehouseID = w.WarehouseID
				LEFT JOIN WarehousePartition wp ON br.WarehousePartitionID = wp.WarehousePartitionID
                WHERE br.PlantID = 4";     
        $stmt1 = sqlsrv_query($conn,$sql);
        $json = array();
        if ($stmt1) {
            while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                $Binload = $row;
                $BinloadRequestID = $row['BinloadRequestID'];
                $Binload['BinloadDetail'] = $pullOutDetails;
                // Fetch Binloading details for the current Binload
                    $sql = "SELECT          
                             bl.BinloadingDate
                            ,bl.BinloadingID
                            ,bl.BinloadRequestID
                            ,bl.ControlNo
                            ,bl.CheckerID
                            ,c.CheckerName
                            ,bl.IntakeID
                            ,bl.PlantID
                            ,bl.Weight
                            ,bl.Quantity
                            FROM Binloading bl
                            LEFT JOIN Checker c ON bl.CheckerID = c.CheckerID
                            WHERE BinloadRequestID = ?";        
                $params = array($BinloadRequestID);
                $stmt = sqlsrv_query($conn, $sql, $params); 
                $BinloadArrayRow = array();
                if ($stmt) {
                    while ($BinloadingRow = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        $BinloadArrayRow[] = $BinloadingRow;
                    } 
                }
                // Add Binloading to the Binload object
                $Binload['Binloading'] = $BinloadArrayRow;
                // Add the combined Binload object to the json array
                $json[] = $Binload;
            }
        }
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($json);
    }
    else if($ULevel == 4)
    {
                $sql = "SELECT         
				 br.BinloadRequestID
                ,br.PO
                ,br.BL
                ,br.WarehouseLocationID
                ,wl.WarehouseLocation
				,br.WarehouseID
				,br.WarehousePartitionID
				,wp.WarehousePartitionName
				,br.WarehousePartitionStockID
				,w.Warehouse_Name
                ,br.PlantID
                ,p.PlantName
                ,br.DriverID
                ,d.DriverName
                ,br.TruckID
                ,t.PlateNo
                ,br.RequestDate
                ,br.RawMaterialID
                ,r.RawMaterial
                ,br.Quantity
                ,br.Weight
                ,br.Status
                ,br.UserID
                ,(ISNULL((SELECT SUM(b.Quantity) FROM Binloading b
                    WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BinloadQty
                ,(br.Quantity - ISNULL((SELECT SUM(b.Quantity) FROM Binloading b
                    WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BalanceQty
				,ISNULL((SELECT SUM(b.Weight) FROM Binloading b
					WHERE br.BinloadRequestID = b.BinloadRequestID),0)AS BinloadWeight
				,(br.Weight - ISNULL((SELECT SUM(b.Weight) FROM Binloading b
					WHERE br.BinloadRequestID = b.BinloadRequestID),0))AS BalanceWeight
				  FROM BinloadRequest br
                LEFT JOIN WarehouseLocation wl ON br.WarehouseLocationID = wl.WarehouseLocationID
                LEFT JOIN Plant p ON br.PlantID = p.PlantID
                LEFT JOIN Driver d ON br.DriverID = d.DriverID
                LEFT JOIN Truck t ON br.TruckID = t.TruckID
                LEFT JOIN RawMaterial r ON br.RawMaterialID = r.RawMaterialID
				LEFT JOIN Warehouse w ON br.WarehouseID = w.WarehouseID
				LEFT JOIN WarehousePartition wp ON br.WarehousePartitionID = wp.WarehousePartitionID";     
        $stmt1 = sqlsrv_query($conn,$sql);
        $json = array();
        if ($stmt1) {
            while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                $Binload = $row;
                $BinloadRequestID = $row['BinloadRequestID'];
                // Fetch PullOut details for the current Binload
                    $sql = "SELECT          
                            bl.BinloadingDate
                            ,bl.BinloadingID
                            ,bl.BinloadRequestID
                            ,bl.ControlNo
                            ,bl.CheckerID
                            ,c.CheckerName
                            ,bl.IntakeID
                            ,bl.PlantID
                            ,bl.Weight
                            ,bl.Quantity
                            FROM Binloading bl
                            LEFT JOIN Checker c ON bl.CheckerID = c.CheckerID
                            WHERE BinloadRequestID = ?";        
                $params = array($BinloadRequestID);
                $stmt = sqlsrv_query($conn, $sql, $params); 
                $BinloadArrayRow = array();
                if ($stmt) {
                    while ($BinloadingRow = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        $BinloadArrayRow[] = $BinloadingRow;
                    } 
                }
                // Add Binloading to the Binload object
                $Binload['Binloading'] = $BinloadArrayRow;
                // Add the combined Binload object to the json array
                $json[] = $Binload;
            }
        }
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($json);
    }

                //Insert Query to save System Log of the User
    //             if($stmt)
    //             {                
    //                 $SystemLogID = 0;
    //                 $FunctionID = 7;
    //                 $$TableName = "BinloadingRequest";
    //                 $sql = "EXEC [dbo].[SystemLogFetch]
    //                         @SystemLogID = ?,
    //                         @UserID = ?,
    //                         @FunctionID = ?";
    //                 $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
    //                 $stmt = sqlsrv_query($conn,$sql,$params);
    //             }
 
    // //EXECUTE InsertQuery for SystemLog Table
    // sqlsrv_commit($conn);
?>