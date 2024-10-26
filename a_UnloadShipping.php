<?php 

    require_once 'connection.php';
    if (sqlsrv_begin_transaction($conn) === false) {
      die( print_r( sqlsrv_errors(), true ));
    }

    $today = date('Y-m-d');
    // $validateToken = include('validate_token.php');
    // if(!$validateToken)
    // {
    //   http_response_code(404);
    //   die();
    // }
        $sql = "SELECT  
        st.ShippingLineID
        ,st.ContractPerformaID
        ,st.Lot
        ,st.RawMaterialID
        ,st.SupplierID
        ,st.Packaging
        ,st.AdvanceDocumentsReceived
        ,st.BAI_SPS_IC
        ,st.BAI_SPS_IC_Date
        ,st.FromBAIValidity
        ,st.ToBAIValidity
        ,st.BPI_SPS_IC
        ,st.FromBPIValidity
        ,st.ToBPIValidity
        ,st.MBL
        ,st.BL
        ,st.ShippingLineID
        ,st.Vessel
        ,st.HBL
        ,st.Forwarder
        ,st.ETD
        ,st.ETA
        ,st.ATA
        ,st.ContainerTypeID
        ,st.NoOfContainer
        ,st.NoOfTruck
        ,st.Quantity
        ,st.BrokerID
        ,st.DateDocsReceivedByBroker
        ,st.BPI_SPS_IC_Date
        ,st.OriginalDocsAvailavilityDate
        ,st.BankID
        ,st.DateOfPickup
        ,st.PortOfDischarge
        ,st.Status
        ,st.DateOfDischarge
        ,st.LodgementDate
        ,st.LodgementBankID
        ,st.GatepassRecieved
        ,st.AcknowledgeByLogistics
        ,st.StorageLastFreeDate
        ,st.DemurrageDate
        ,st.DetentionDate
        ,st.Remarks
        ,st.UserID
        FROM ShippingTransaction st";
        $params = array($today);
        $stmt1 = sqlsrv_query($conn,$sql,$params); 
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