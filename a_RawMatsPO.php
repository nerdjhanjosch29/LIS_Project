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
           po.PurchaseOrderID
          ,po.PONo
          ,po.PODate
          ,po.DeliveryDate
          ,po.Terms
          ,po.PRNumber
          ,po.SupplierID
          ,s.Supplier
          ,po.SupplierAddress
          ,po.TotalQuantity
          ,po.TotalAmount
          ,po.deleted
          ,po.UserID 
          FROM PurchaseOrder po
          LEFT JOIN Supplier s ON  po.SupplierID = s.SupplierID
          WHERE po.isDel = 'False'";
          $stmt1 = sqlsrv_query($conn, $sql);
          $json = array();
          if ($stmt1) {
              while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                  $shippingTransaction = $row;
                  $PurchaseOrderID = $row['PurchaseOrderID'];
                  // Fetch PullOut details for the current ShippingTransaction
                  $sqlPullOut = "SELECT
                                  pod.PurchaseOrderDetailID
                                 ,pod.PurchaseOrderID
                                 ,pod.MaterialCode
                                 ,pod.RawMaterialID
                                 ,rm.RawMaterial
                                 ,pod.Quantity
                                 ,pod.UnitPrice
                                 ,pod.Amount
                                 ,pod.deleted
                                  FROM PurchaseOrderDetail pod 
                                  LEFT JOIN RawMaterial rm ON pod.RawMaterialID = rm.RawMaterialID
                                  WHERE PurchaseOrderID = ?";
                  $params= array($PurchaseOrderID);
                  $stmt2 = sqlsrv_query($conn, $sqlPullOut, $params);
                  $OrderDetails = array();
                  if ($stmt2) {
                      while ($pullOutRow = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                          $OrderDetails[] = $pullOutRow;
                      }
                  }
                  $shippingTransaction['OrderDetail'] = $OrderDetails;
                  $json[] = $shippingTransaction;
              }
          }
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($json);

        



?>