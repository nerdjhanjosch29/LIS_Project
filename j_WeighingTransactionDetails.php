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
                 wtd.WeighingTransDetialID
                ,wtd.WeighingTransactionID
                ,fp.FinishProduct
                ,ISNULL(fp.FinishProductID, 0) AS FinishProductID
                ,ISNULL(rm.RawMaterialID,0)AS RawMaterialID, rm.RawMaterial
                ,ISNULL(c.CustomerID, 0)AS CustomerID, c.CustomerName
                ,wtd.NoofBags
                ,wtd.isTransaction 
                 FROM WeighingTransactionDetail wtd 
                 LEFT JOIN FinishProduct fp ON wtd.FinishProductID = fp.FinishProductID
                 LEFT JOIN RawMaterial rm ON wtd.RawMaterialID = rm.RawMaterialID
                 LEFT JOIN Customer c ON wtd.CustomerID = c.CustomerID";
                 
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
?> 