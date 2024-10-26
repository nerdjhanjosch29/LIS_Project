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
                    $sql = "SELECT 
                            wl.WarehouseLocationID,
                            wl.WarehouseLocation,
                            COALESCE((SELECT SUM(vw.WarehouseMaximumCapacity) 
                                    FROM View_WarehouseMaximum vw 
                                    WHERE vw.WarehouseLocationID = wl.WarehouseLocationID), 0) AS MaximumCapacity,
                            COALESCE((SELECT SUM(wps.RawMatsQty) 
                                    FROM WarehousePartitionStock wps 
                                    WHERE wps.WarehouseLocationID = wl.WarehouseLocationID), 0) AS TotalQuantity,
                            COALESCE((SELECT SUM(wps.RawMatsWeight) 
                                    FROM WarehousePartitionStock wps 
                                    WHERE wps.WarehouseLocationID = wl.WarehouseLocationID), 0) AS TotalWeight,
                            wl.UserID,
                            wl.isDel
                        FROM 
                            WarehouseLocation wl ";
                $stmt1 = sqlsrv_query($conn, $sql);
                $json = array();

    if ($stmt1) {
        while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            $Warehouses = $row;
            // Fetch Warehouses for the current WarehouseLocation
         $sqlWarehouses = "SELECT 
                    w.WarehouseID,
                    w.WarehouseLocationID,
                    wl.WarehouseLocation,
                    w.Warehouse_Name,
                    COALESCE((SELECT SUM(wp.MaximumCapacity) 
                            FROM WarehousePartition wp 
                            WHERE wp.WarehouseID = w.WarehouseID), 0) AS MaximumCapacity,
                    w.MinimumCapacity,
                    w.TotalQuantity,
                    w.TotalWeight,
                    w.Remarks
                FROM 
                    Warehouse w
                LEFT JOIN 
                    WarehouseLocation wl ON w.WarehouseLocationID = wl.WarehouseLocationID 
                WHERE 
                    w.WarehouseLocationID = ? 
                    AND w.isDel = 'False'";
            // $WarehousePartition = "";
            $params2 = array($row['WarehouseLocationID']);
            $stmt2 = sqlsrv_query($conn, $sqlWarehouses, $params2);
            // while($row = sqlsrv_fetch_array($stmt2,SQLSRV_FETCH_ASSOC))
            // {
            //     $WarehousePartition = $row['WarehouseID']; 
            // }
            


            $WarehousesDetails = array();
            if ($stmt2) {
                while ($WarehousesRow = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) { 
                    
                    // Fetch WarehousePartition details for the current Warehouse
                    $sqlWarehousePartition = "SELECT 
                         wp.WarehousePartitionID
                        ,wp.WarehousePartitionName
                        ,wp.WarehouseID
                        ,w.Warehouse_Name
                        FROM WarehousePartition wp
                        LEFT JOIN Warehouse w ON wp.WarehouseID =  w.WarehouseID";
                    $params3 = array($WarehousesRow['WarehouseID']);
                    $stmt3 = sqlsrv_query($conn, $sqlWarehousePartition, $params3);
                    $WarehousePartitionDetails = array();
                    if ($stmt3) {
                        while ($WarehousePartitionRow = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
                            $WarehousePartitionDetails[] = $WarehousePartitionRow;
                        }
                    }
                    // Add the WarehousePartition details to the current Warehouse
                    $WarehousesRow['WarehousePartitionDetail'] = $WarehousePartitionDetails;
                    $WarehousesDetails[] = $WarehousesRow;
                }
            }
            // Add the Warehouses details to the current WarehouseLocation
            $Warehouses['WarehouseDetail'] = $WarehousesDetails;
            $json[] = $Warehouses;
        }
    }
    // Return JSON response
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($json);


?>