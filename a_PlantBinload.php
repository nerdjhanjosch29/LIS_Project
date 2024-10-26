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
          $sql = "   DECLARE @Today date = GETDATE();
                     SELECT 
                     p.PlantID 
                    ,p.PlantName
                    ,ISNULL((SELECT SUM(b.Quantity) FROM Binloading b
                        WHERE p.PlantID = b.PlantID AND b.BinloadingDate = @Today),0) AS TodayBinloadQty
                    ,ISNULL((SELECT SUM(b.Weight) FROM Binloading b
                        WHERE p.PlantID = b.PlantID AND b.BinloadingDate = @Today),0) AS TodayBinloadWeight
                    FROM Plant p";
          $stmt1 = sqlsrv_query($conn, $sql);
          $json = array();
          if ($stmt1) {
              while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
                  $shippingTransaction = $row;
                  $PlantID = $row['PlantID'];
                  // Fetch PullOut details for the current ShippingTransaction
                  $sqlPullOut = "DECLARE @Today date = GETDATE();
                                 SELECT 
                                 b.BinloadingDate 
                                ,b.BinloadingID
                                ,b.BinloadRequestID
                                ,b.CheckerID
                                ,b.ControlNo
                                ,b.IntakeID
                                ,b.PlantID
                                ,b.Quantity
                                ,b.RawMaterialID
                                ,b.Status
                                ,b.Weight
                                FROM Binloading b 
                                WHERE b.PlantID = ? AND b.BinloadingDate = @Today";
                  $params= array($PlantID);
                  $stmt2 = sqlsrv_query($conn, $sqlPullOut, $params);
                  $OrderDetails = array();
                  if ($stmt2) {
                      while ($pullOutRow = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                          $OrderDetails[] = $pullOutRow;
                      }
                  }
                  $shippingTransaction['Binload'] = $OrderDetails;
                  $json[] = $shippingTransaction;
              }
          }
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($json);
?>