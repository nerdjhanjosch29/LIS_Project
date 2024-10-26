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
                FROM PurchaseOrder po
                WHERE isDel = 'False'";
        $stmt1 = sqlsrv_query($conn,$sql); 
        if($stmt1)
        {
          $json = array();
          do {
            while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            $json[] = $row;    
            $PoID = $row['PurchaseOrderID'];
            $sql = "SELECT ut.PO, ut.PONo 
                    FROM UnloadingTransaction ut 
                    LEFT JOIN PurchaseOrder po ON ut.PO = po.PurchaseOrderID
                    WHERE ut.PO <> 0 AND ut.PO = ?";
            $params = array($PoID);
            $stmt1 = sqlsrv_query($conn,$sql);
              while($PurchaseOrderRow = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC))
              {
                  $PurchaseDetail[] = $PurchaseOrderRow;
              }
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