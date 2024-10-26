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

    $BinloadRequestID = "";
    if(isset($_GET['id']))
    {
      $BinloadRequestID = $_GET['id'];
    }
        $sql = "SELECT 
        bl.BinloadingID
        ,bl.ControlNo 
        ,p.PlantID
        ,p.PlantName
        ,c.CheckerID 
        ,c.CheckerName 
        ,d.DriverID
        ,d.DriverName
        ,t.TruckID
        ,t.PlateNo,
        ,w.WarehouseID
        ,w.Warehouse_Name
        ,bl.IntakeID
        ,wp.WarehousePartitionID
        ,bl.WarehouseLocationID
        ,wl.WarehouseLocationID
        ,bl.Status
        ,wp.WarehousePartitionName
        ,bl.WarehousePartitionStockID
        ,bl.BinloadingDate
        ,bl.BinloadingDateTime
        ,rm.RawMaterialID
        ,rm.RawMaterial
        ,bl.Quantity
        ,bl.Weight
         bl.UserID
        FROM Binloading bl 
        LEFT JOIN Plant p ON bl.PlantID = p.PlantID
        LEFT JOIN Checker c ON bl.CheckerID = c.CheckerID
        LEFT JOIN Driver d ON bl.DriverID = d.DriverID
        LEFT JOIN Truck t ON bl.TruckID = t.TruckID
        LEFT JOIN Warehouse w ON bl.WarehouseID = w.WarehouseID
        LEFT JOIN WarehousePartition wp ON bl.WarehousePartitionID = wp.WarehousePartitionID
        LEFT JOIN RawMaterial rm ON bl.RawMaterialID = rm.RawMaterialID 
        LEFT JOIN WarehouseLocation wl ON bl.WarehouseLocationID = wl.WarehouseLocationID 
        WHERE bl.isDel = 'False' AND bl.Status <> 3 AND BinloadRequestID = ? ORDER BY bl.PlantID ASC";
        $stmt1 = sqlsrv_query($conn,$sql); 
        $params = array($BinloadRequestID);
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