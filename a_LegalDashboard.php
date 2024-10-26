

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
        $sql = " SELECT COUNT(po.ContainerNumber)AS BalanceAtPort FROM PullOut po WHERE po.PullOutDate IS NULL";
        $stmt1 = sqlsrv_query($conn,$sql); 
        $sql2 = "SELECT COUNT(po.ContainerNumber)AS BalanceContainer FROM PullOut po WHERE po.ReturnDate IS NULL";       
        $stmt2 = sqlsrv_query($conn,$sql2); 
       //
       $sql3 = "DECLARE @Today DATE = DATEADD(DAY, -1, GETDATE());
                    DECLARE @NextWeek DATE = DATEADD(WEEK, 1, @Today);
                    SELECT 
                    ct.ContainerTypeID
                    ,ct.Container
                    ,(SELECT SUM(st.NoOfContainer) AS IncomingContainers
                    FROM ShippingTransaction st
                    WHERE st.ETA >= @Today 
                    AND st.ETA < @NextWeek AND st.ContainerTypeID = ct.ContainerTypeID) AS IncomingContainers
                    FROM ContainerType ct";  
        $stmt3 = sqlsrv_query($conn,$sql3);
        //BL tthat arrive today
        $sql4 = "DECLARE @dateToday date = GETDATE();
                 SELECT BL, MBL FROM ShippingTransaction WHERE ATA = @dateToday";
        $stmt4 = sqlsrv_query($conn, $sql4); 
        $sql5 = "SELECT 
                   st.BL
                  ,st.Quantity AS TargetQuantity
                  ,ISNULL((SELECT SUM(Quantity) FROM UnloadingTransaction ut WHERE ut.BLNumber = st.BL),0)AS WithdrawnQty
                  ,ISNULL((SELECT st.Quantity - SUM(Quantity) FROM UnloadingTransaction ut WHERE ut.BLNumber = st.BL),0)AS NotWithdrawQty
                  FROM 
                  ShippingTransaction st
                  WHERE st.Packaging = 2"; 
        $stmt5 = sqlsrv_query($conn, $sql5);
        //Sailing Today
        $sql6 = "SELECT BL, MBL, + 'SailingToday' FROM ShippingTransaction WHERE Status = 1";
        $stmt6 = sqlsrv_query($conn, $sql6);
        //Landed Today
        $sql7 ="DECLARE @dateT date = GETDATE();
                SELECT BL, MBL,+ 'LandedToday' FROM ShippingTransaction WHERE Status = 2 AND ATA = @dateT;
";
        $stmt7 = sqlsrv_query($conn,$sql7);
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