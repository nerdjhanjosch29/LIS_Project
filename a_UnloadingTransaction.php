<?php 

    require_once 'connection.php';
    $data = json_decode(file_get_contents('php://input'));
    if (sqlsrv_begin_transaction($conn) === false) {
      die( print_r( sqlsrv_errors(), true ));
    }
    // $validateToken = include('validate_token.php');
    // if(!$validateToken)
    // {
    //   http_response_code(404);
    //   die();
    // }
        $isTransaction = "";
      if(isset($_GET['id']))
      {
        $isTransaction = $_GET['id'];
      }

       if($isTransaction == 1)
       {
         $sql = "SELECT 
         ut.UnloadingTransactionID
        ,ut.isTransactionID
        ,ut.DateUnload
        ,ut.PO
        ,ut.DrNumber
        ,po.PONo
        ,ut.CheckerID
        ,c.CheckerName
        ,t.TruckID
        ,t.PlateNo
        ,ut.SupplierID
        ,s.Supplier
        ,rm.RawMaterialID
        ,RawMaterial
        ,ut.WarehouseLocationID
        ,wl.WarehouseLocation
        ,ut.Status
        ,wh.WarehouseID
        ,Warehouse_Name
        ,wp.WarehousePartitionID
        ,ut.DateTimeUnload
        ,wp.WarehousePartitionName
        ,ut.Quantity
        ,ut.Weight
        ,ut.UserID
        FROM UnloadingTransaction ut
        LEFT JOIN Truck t ON ut.TruckID = t.TruckID
        LEFT JOIN Checker c ON ut.CheckerID = c.CheckerID 
        LEFT JOIN RawMaterial rm ON ut.RawMaterialID = rm.RawMaterialID 
        LEFT JOIN Warehouse wh ON ut.WarehouseID = wh.WarehouseID 
        LEFT JOIN WarehousePartition wp ON ut.WarehousePartitionID = wp.WarehousePartitionID
        LEFT JOIN Supplier s ON ut.SupplierID = s.SupplierID
        LEFT JOIN PurchaseOrder po ON  ut.PO = po.PurchaseOrderID
		    LEFT JOIN WarehouseLocation wl ON  ut.WarehouseLocationID = wl.WarehouseLocationID
        WHERE ut.isDel ='False' AND ut.isTransactionID = 1";
          $stmt1 = sqlsrv_query($conn, $sql);
          $json = array();
          if ($stmt1) {
              while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                  $ImageRow = $row;
                  $UnloadingID = $row['UnloadingTransactionID'];
                  // Fetch PullOut details for the current ShippingTransaction
                  $sqlImage = "SELECT
                                 it.ImageUrl
                                ,it.ImageID
                                ,it.TableID
                                ,it.TableName
                                FROM ImageTable it
                                LEFT JOIN UnloadingTransaction ut ON it.TableID = ut.UnloadingTransactionID
                                WHERE it.TableID = ?";
                  $params= array($UnloadingID);
                  $stmt2 = sqlsrv_query($conn, $sqlImage, $params);
                  $ImageDetail = array();
                  if ($stmt2) {
                      while ($pullOutRow = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                          $ImageDetail[] = $pullOutRow;
                      }
                  }
                  $ImageRow['ImageDetail'] = $ImageDetail;
                  $json[] = $ImageRow;
              }
          }
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($json);
       }
       else if($isTransaction == 2)
       {
                $sql = "SELECT 
                 ut.UnloadingTransactionID
                ,ut.isTransactionID
                ,ut.ContainerNumber AS ContainerID
                ,st.Packaging
                ,po.ContainerNumber
                ,ut.DateUnload
                ,ut.BL
                ,sts.MBL
                ,ut.DrNumber
                ,c.CheckerID
                ,c.CheckerName
                ,t.TruckID
                ,t.PlateNo
                ,ut.SupplierID
                ,s.Supplier
                ,rm.RawMaterialID
                ,RawMaterial
                ,ut.WarehouseLocationID
                ,wl.WarehouseLocation
                ,ut.WarehouseID
                ,wh.Warehouse_Name
                ,wp.WarehousePartitionID
                ,ut.DateTimeUnload
                ,wp.WarehousePartitionName
                ,ut.Status
                ,ut.Quantity
                ,ut.Weight
                ,ut.UserID
                FROM UnloadingTransaction ut
                LEFT JOIN Truck t ON ut.TruckID = t.TruckID
                LEFT JOIN Checker c ON ut.CheckerID = c.CheckerID 
                LEFT JOIN RawMaterial rm ON ut.RawMaterialID = rm.RawMaterialID 
                LEFT JOIN Warehouse wh ON ut.WarehouseID = wh.WarehouseID 
                LEFT JOIN WarehousePartition wp ON ut.WarehousePartitionID = wp.WarehousePartitionID
                LEFT JOIN WarehouseLocation wl ON ut.WarehouseLocationID = wl.WarehouseLocationID
                LEFT JOIN Supplier s ON ut.SupplierID = s.SupplierID
                LEFT JOIN ShippingTransaction st ON ut.BL = st.ShippingTransactionID 
                LEFT JOIN PullOut po  ON ut.ContainerNumber = po.PullOutID
                LEFT JOIN (SELECT MBL,ShippingTransactionID FROM ShippingTransaction WHERE MBL <> '0' 
                UNION ALL SELECT BL,ShippingTransactionID FROM ShippingTransaction WHERE BL <> '0')sts ON ut.BL = sts.ShippingTransactionID
                WHERE ut.isDel ='False' AND ut.isTransactionID = 2";

          $stmt1 = sqlsrv_query($conn, $sql);
          $json = array();
          if ($stmt1) {
              while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                  $ImageRow = $row;
                  $UnloadingID = $row['UnloadingTransactionID'];
                  // Fetch PullOut details for the current ShippingTransaction
                  $sqlImage = "SELECT
                               it.ImageUrl
                              ,it.ImageID
                              ,it.TableID
                              ,it.TableName
                              FROM ImageTable it
                              LEFT JOIN UnloadingTransaction ut 
                              ON it.TableID = ut.UnloadingTransactionID
                              WHERE it.TableID = ?";
                  $params= array($UnloadingID);
                  $stmt2 = sqlsrv_query($conn, $sqlImage, $params);
                  $ImageDetail = array();
                  if ($stmt2) {
                      while ($pullOutRow = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                          $ImageDetail[] = $pullOutRow;
                      }
                  }
                  $ImageRow['ImageDetail'] = $ImageDetail;
                  $json[] = $ImageRow;
              }
          }
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($json);
      }
?>