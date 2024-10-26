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

        $sql = " SELECT
                 po.PullOutID
                ,po.HBL
                ,po.MBL
                ,st.SupplierID
                ,s.Supplier
                ,st.BrokerID
                ,b.Broker
                ,po.ContainerNumber
                ,st.DateOfDischarge
                ,st.StorageLastFreeDate
                ,st.DemurrageDate
                ,po.DateIn
                ,po.DateOut
                ,po.PullOutDate
                ,st.DetentionDate
                ,po.ReturnDate
                ,po.TruckingID
                ,t.TruckingName
                ,po.Remarks
                FROM PullOut po
                LEFT JOIN ShippingTransaction st ON po.MBL = st.MBL
                LEFT JOIN Supplier s ON st.SupplierID = s.SupplierID
                LEFT JOIN Broker b ON st.BrokerID = b.BrokerID
                LEFT JOIN Trucking t ON po.TruckingID = t.TruckingID
                WHERE st.Status = 2 AND po.isDel = 'False' AND st.Packaging = 1";
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