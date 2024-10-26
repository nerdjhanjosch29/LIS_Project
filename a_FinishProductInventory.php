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
    $date= GETDATE();
        $sql = "DECLARE @Today DATE = GETDATE(); 
         SELECT
         fp.FinishProduct
        ,fp.FinishProductID
        ,fpi.InventoryDate
        ,fpi.BeginQty
        ,fpi.ProductionOutput
        ,fpi.OutgoingQty
        ,fpi.Condemned
        ,fpi.EndingQty  
         FROM FinishProductInventory fpi 
         LEFT JOIN FinishProduct fp ON fpi.FinishProductID = fp.FinishProductID WHERE fpi.InventoryDate =@Today";
         
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