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
        $sql = "DECLARE @Today DATE = GETDATE(); 
         SELECT 
         ri.RawMaterialID
        ,r.RawMaterial
        ,InventoryDate
        ,BeginQty
        ,BeginWeight
        ,IncomingQty
        ,IncomingWeight
        ,FromTransferQty
        ,FromTransferWeight
        ,ToTransferQty
        ,ToTransferWeight
        ,BinloadingQty
        ,BinloadingWeight
        ,CondemQty
        ,CondemWeight
        ,EndingQty
        ,EndingWeight
         FROM RawMaterialInventory ri 
         LEFT JOIN RawMaterial r ON ri.RawMaterialID = r.RawMaterialID WHERE ri.isDel = 'False' AND ri.InventoryDate = @Today
        ";
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