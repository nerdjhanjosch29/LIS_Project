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
             MBL
            ,ShippingTransactionID
            ,Packaging
            ,RawMaterialID
            ,SupplierID
            ,Status
             FROM ShippingTransaction
             WHERE MBL <> '0' AND Status = 3
                UNION ALL 
            SELECT
              BL
             ,ShippingTransactionID
             ,Packaging
             ,RawMaterialID
             ,SupplierID
             ,Status  
	          FROM ShippingTransaction
              WHERE BL <> '0' AND Status = 2 AND ATA <> ''";
$stmt1 = sqlsrv_query($conn, $sql);
    $stmt1 = sqlsrv_query($conn, $sql);
    if ($stmt1) {
        $json = array();
        while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            $shippingTransaction = $row;
            $MBL = $row['MBL'];
            // $BL = $row['BL'];
      // Fetch PullOut details for the current ShippingTransaction
      $sqlPullOut = "SELECT
        po.ContainerNumber
       ,po.PullOutID
      FROM PullOut po
      LEFT JOIN ShippingTransaction st ON po.MBL = st.MBL WHERE po.MBL = ? AND po.deleted = 0";
      $paramsPullOut = array($MBL);
      $stmt2 = sqlsrv_query($conn,$sqlPullOut,$paramsPullOut);
      $pullOutDetails = array();
      if ($stmt2) {
          while ($pullOutRow = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
              $pullOutDetails[] = $pullOutRow;
      }
      $shippingTransaction['PullOutDetail'] = $pullOutDetails;
      $json[] = $shippingTransaction;
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
    } else {
        sqlsrv_rollback($conn);
        echo "Rollback";
    }
?>