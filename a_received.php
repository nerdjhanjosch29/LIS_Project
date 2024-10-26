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
    $id = "";
    if(isset($_GET['id']))
    {
      $id = $_GET['id'];
    }
    if($id == 0)
    {
      $sql = "SELECT DISTINCT (ShippingTransactionID)
			        ,st.MBL
              ,st.Packaging
              ,st.ShippingTransactionID
              ,st.BL
              ,st.ShippingLineID
              ,sl.ShippingLine
              ,st.NoOfTruck
              ,st.NoOfContainer
              ,st.ContainerTypeID
              ,ct.Container
              ,st.RawMaterialID
              ,rm.RawMaterial
              ,st.SupplierID
              ,s.Supplier
              ,st.PortOfDischarge
              ,st.BrokerID
              ,b.Broker
              ,st.ATA
              ,st.StorageLastFreeDate
              ,st.DemurrageDate
              ,st.DetentionDate
              ,st.NoOfTruck -
							 (SELECT        COUNT(BLNumber)  AS Expr1
                FROM           UnloadingTransaction
                WHERE        (BLNumber = st.BL)) AS Balance_Truck,
							 st.NoOfContainer -
							 (SELECT        COUNT(ContainerNumber) AS Expr1
                FROM            dbo.PullOut AS PullOut_3
                WHERE        (MBL = st.MBL AND PullOutDate <> '')) AS Balance_Container,
							 (SELECT        COUNT(ContainerNumber) AS Expr1
                FROM            dbo.PullOut AS PullOut_2
                WHERE        MBL = st.MBL AND PullOutDate <> '') AS Pulled_Out,
							 (SELECT        COUNT(BLNumber) AS Expr1
                FROM            dbo.UnloadingTransaction ut
                WHERE       (ut.BLNumber = st.BL)  ) AS Withdrawn,   
							 (SELECT        COUNT(BLNumber) AS Expr1
                FROM            dbo.UnloadingTransaction 
                WHERE        (BLNumber = st.MBL)) AS Unloaded_Container,
               (SELECT        COUNT(BLNumber) AS Expr1
                FROM            dbo.UnloadingTransaction ut
                WHERE        (ut.BLNumber = st.BL)) AS Unloaded_Truck
                FROM           dbo.ShippingTransaction AS st LEFT OUTER JOIN
                               dbo.UnloadingTransaction AS ut ON st.MBL = ut.BL LEFT OUTER JOIN
                               dbo.ShippingLine AS sl ON st.ShippingLineID = sl.ShippingLineID LEFT OUTER JOIN
                               dbo.RawMaterial AS rm ON st.RawMaterialID = rm.RawMaterialID LEFT OUTER JOIN
                               dbo.Supplier AS s ON st.SupplierID = s.SupplierID LEFT OUTER JOIN
                               dbo.Broker AS b ON st.BrokerID = b.BrokerID LEFT OUTER JOIN
                               dbo.ContainerType AS ct ON st.ContainerTypeID = ct.ContainerTypeID ";

                     $stmt1 = sqlsrv_query($conn,$sql); 
                     if($stmt1)
                     {
                       $json = array();
                       do {
                           while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) 
                           {
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
    }
    else
    {
      $sql = "SELECT DISTINCT (ShippingTransactionID)
              ,st.Packaging
              ,st.MBL
              ,st.BL
              ,st.ShippingLineID
              ,sl.ShippingLine
              ,st.NoOfTruck
              ,st.NoOfContainer
              ,st.ContainerTypeID
              ,ct.Container
              ,st.RawMaterialID
              ,rm.RawMaterial
              ,st.SupplierID
              ,s.Supplier
              ,st.PortOfDischarge
              ,st.BrokerID
              ,b.Broker
              ,st.ATA
              ,st.StorageLastFreeDate
              ,st.DemurrageDate
              ,st.DetentionDate
              ,st.NoOfTruck -
							 (SELECT        COUNT(BLNumber)  AS Expr1
                FROM           UnloadingTransaction
                WHERE        (BLNumber = st.BL)) AS Balance_Truck
              ,st.NoOfContainer -
								(SELECT        COUNT(ContainerNumber) AS Expr1
                 FROM            dbo.PullOut AS PullOut_3
                 WHERE        (MBL = st.MBL AND PullOutDate <> '01-01-1900')) AS Balance_Container,
								(SELECT        COUNT(ContainerNumber) AS Expr1
                 FROM            dbo.PullOut AS PullOut_2
                 WHERE        MBL = st.MBL AND PullOutDate <> '01-01-1900') AS Pulled_Out,
								(SELECT        COUNT(BLNumber) AS Expr1
                 FROM            dbo.UnloadingTransaction ut
                 WHERE       (ut.BLNumber = st.BL)  ) AS Withdrawn,   
								(SELECT        COUNT(BLNumber) AS Expr1
                 FROM            dbo.UnloadingTransaction
                 WHERE        (BLNumber = st.MBL)) AS Unloaded_Container,
                (SELECT        COUNT(BLNumber) AS Expr1
                 FROM            dbo.UnloadingTransaction ut
                 WHERE        (ut.BLNumber = st.BL)) AS Unloaded_Truck
                 FROM       dbo.ShippingTransaction AS st 
                 LEFT OUTER JOIN dbo.UnloadingTransaction AS ut ON st.MBL = ut.BL 
                 LEFT OUTER JOIN dbo.ShippingLine AS sl ON st.ShippingLineID = sl.ShippingLineID 
                 LEFT OUTER JOIN dbo.RawMaterial AS rm ON st.RawMaterialID = rm.RawMaterialID 
                 LEFT OUTER JOIN dbo.Supplier AS s ON st.SupplierID = s.SupplierID
                 LEFT OUTER JOIN dbo.Broker AS b ON st.BrokerID = b.BrokerID 
                 LEFT OUTER JOIN dbo.ContainerType AS ct ON st.ContainerTypeID = ct.ContainerTypeID
                 WHERE ContractPerformaID = ?";
                 
                     $params = array($id);
                     $stmt1 = sqlsrv_query($conn,$sql,$params); 
                     if($stmt1)
                     {
                       $json = array();
                       do {
                           while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) 
                           {
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

    }
       
      
?>