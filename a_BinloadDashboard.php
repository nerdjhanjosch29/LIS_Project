<?php 
    require_once 'connection.php';
    if (sqlsrv_begin_transaction($conn) === false) {
      die( print_r( sqlsrv_errors(), true ));
    }
    $data = json_decode(file_get_contents('php://input'));
    // $validateToken = include('validate_token.php');
    // if(!$validateToken)
    // {
    //   http_response_code(404);
    //   die();
    // }
      
      $sql = "DECLARE @Today date = GETDATE();
                 SELECT         
				 br.BinloadRequestID
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
                ,br.Status
                ,br.UserID
                FROM BinloadRequest br
                LEFT JOIN WarehouseLocation wl ON br.WarehouseLocationID = wl.WarehouseLocationID
                LEFT JOIN Plant p ON br.PlantID = p.PlantID
                LEFT JOIN Driver d ON br.DriverID = d.DriverID
                LEFT JOIN Truck t ON br.TruckID = t.TruckID
                LEFT JOIN RawMaterial r ON br.RawMaterialID = r.RawMaterialID
				LEFT JOIN Warehouse w ON br.WarehouseID = w.WarehouseID
				LEFT JOIN WarehousePartition wp ON br.WarehousePartitionID = wp.WarehousePartitionID
                LEFT JOIN Binloading b ON br.BinloadRequestID = b.BinloadRequestID   
                WHERE b.BinloadingDate = @Today";     
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
?>