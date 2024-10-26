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
     $ContractID = "";
    if(isset($_GET['id']))
    {
        $ContractID = $_GET['id'];
    }
    if($ContractID == 0)
    {
          $sql = "SELECT 
            S.ShippingTransactionID
           ,S.MBL
           ,S.BL
           ,S.Packaging
           ,S.SupplierID
           ,sp.Supplier
           ,(SELECT COUNT(U.BL) 
                FROM UnloadingTransaction U 
                WHERE S.ShippingTransactionID = U.BL) AS NoofLoaded
           ,(SELECT DISTINCT U.BL 
                FROM UnloadingTransaction U 
                WHERE S.ShippingTransactionID = U.BL) AS uBL
           ,S.ShippingLineID
           ,sl.ShippingLine
           ,S.RawMaterialID
           ,S.Quantity
           ,(SELECT SUM(U.Weight) 
                FROM UnloadingTransaction U
                WHERE S.ShippingTransactionID = U.BL) AS TotalWeight
           ,rm.RawMaterial
         -- Assuming you want to select a field from RawMaterial table
         FROM ShippingTransaction S
		
         LEFT JOIN 
            RawMaterial rm ON S.RawMaterialID = rm.RawMaterialID
         LEFT JOIN 
            ShippingLine sl ON S.ShippingLineID = sl.ShippingLineID
         LEFT JOIN 
            Supplier sp ON S.SupplierID = sp.SupplierID

				
         GROUP BY 
            S.ShippingTransactionID
           ,S.MBL
           ,S.BL
           ,S.SupplierID
           ,sp.Supplier
           ,S.Packaging
           ,S.ShippingLineID
           ,S.RawMaterialID
           ,S.Quantity
           ,rm.RawMaterial
           ,sl.ShippingLine
        HAVING 
            (SELECT COUNT(U.BL) 
                 FROM UnloadingTransaction U 
                 WHERE S.ShippingTransactionID = U.BL) > 0 
				 ";
     
$stmt1 = sqlsrv_query($conn, $sql);

    if ($stmt1) {
        $json = array();
        while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            $shippingTransaction = $row;
            $BL = $row['uBL'];
            // $BL = $row['BL'];
      // Fetch PullOut details for the current ShippingTransaction
      $sqlPullOut = "SELECT 
                     U.DateUnload
                    ,U.TruckID
                    ,t.PlateNo
                    ,U.DrNumber
                    ,U.Weight
                    ,po.TruckingID
                    ,tk.TruckingName
                    ,U.ContainerNumber
                    ,po.ContainerNumber
                     FROM UnloadingTransaction U
                     LEFT JOIN PullOut po ON U.ContainerNumber = po.PullOutID
                     LEFT JOIN Truck t ON U.TruckID = t.TruckID
                     LEFT JOIN Trucking tk ON po.TruckingID = tk.TruckingID
                     CROSS APPLY (SELECT DISTINCT ShippingTransactionID 
                     FROM ShippingTransaction S WHERE S.ShippingTransactionID = U.BL) S
                     WHERE U.BL = ?
                     ORDER BY 
                     S.ShippingTransactionID, U.DateUnload DESC";
            $paramsPullOut = array($BL);
            $stmt2 = sqlsrv_query($conn,$sqlPullOut,$paramsPullOut);
            $pullOutDetails = array();
            if ($stmt2) {
                while ($pullOutRow = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                    $pullOutDetails[] = $pullOutRow;
            }
            $shippingTransaction['UnloadDetail'] = $pullOutDetails;
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
    else
    {
          $sql = "SELECT 
            S.ShippingTransactionID
           ,S.MBL
           ,S.BL
           ,S.Packaging
           ,S.SupplierID
           ,sp.Supplier
           ,(SELECT COUNT(U.BL) 
                FROM UnloadingTransaction U 
                WHERE S.ShippingTransactionID = U.BL) AS NoofLoaded
           ,(SELECT DISTINCT U.BL 
                FROM UnloadingTransaction U 
                WHERE S.ShippingTransactionID = U.BL) AS uBL
           ,S.ShippingLineID
           ,sl.ShippingLine
           ,S.RawMaterialID
           ,S.Quantity
           ,(SELECT SUM(U.Weight) 
                FROM UnloadingTransaction U
                WHERE S.ShippingTransactionID = U.BL) AS TotalWeight
           ,rm.RawMaterial
         -- Assuming you want to select a field from RawMaterial table
         FROM ShippingTransaction S
		
         LEFT JOIN 
            RawMaterial rm ON S.RawMaterialID = rm.RawMaterialID
         LEFT JOIN 
            ShippingLine sl ON S.ShippingLineID = sl.ShippingLineID
         LEFT JOIN 
            Supplier sp ON S.SupplierID = sp.SupplierID
		WHERE  
				 s.ContractPerformaID = ?
         GROUP BY 
            S.ShippingTransactionID
           ,S.MBL
           ,S.BL
           ,S.SupplierID
           ,sp.Supplier
           ,S.Packaging
           ,S.ShippingLineID
           ,S.RawMaterialID
           ,S.Quantity
           ,rm.RawMaterial
           ,sl.ShippingLine
        HAVING 
            (SELECT COUNT(U.BL) 
                 FROM UnloadingTransaction U 
                 WHERE S.ShippingTransactionID = U.BL) > 0 
				 ";
        $params = array($ContractID);
$stmt1 = sqlsrv_query($conn, $sql,$params);

    if ($stmt1) {
        $json = array();
        while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            $shippingTransaction = $row;
            $BL = $row['uBL'];
            // $BL = $row['BL'];
      // Fetch PullOut details for the current ShippingTransaction
      $sqlPullOut = "SELECT 
                     U.DateUnload
                    ,U.TruckID
                    ,t.PlateNo
                    ,U.DrNumber
                    ,U.Weight
                    ,po.TruckingID
                    ,tk.TruckingName
                    ,U.ContainerNumber
                    ,po.ContainerNumber
                     FROM UnloadingTransaction U
                     LEFT JOIN PullOut po ON U.ContainerNumber = po.PullOutID
                     LEFT JOIN Truck t ON U.TruckID = t.TruckID
                     LEFT JOIN Trucking tk ON po.TruckingID = tk.TruckingID
                     CROSS APPLY (SELECT DISTINCT ShippingTransactionID 
                     FROM ShippingTransaction S WHERE S.ShippingTransactionID = U.BL) S
                     WHERE U.BL = ?
                     ORDER BY 
                     S.ShippingTransactionID, U.DateUnload DESC";
            $paramsPullOut = array($BL);
            $stmt2 = sqlsrv_query($conn,$sqlPullOut,$paramsPullOut);
            $pullOutDetails = array();
            if ($stmt2) {
                while ($pullOutRow = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                    $pullOutDetails[] = $pullOutRow;
            }
            $shippingTransaction['UnloadDetail'] = $pullOutDetails;
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
  
?>