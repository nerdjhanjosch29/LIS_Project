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
    $sql = "SELECT 
           ShippingTransactionID
          ,ContractPerformaID
          ,Lot
          ,RawMaterialID
          ,SupplierID
          ,Packaging
          ,AdvanceDocumentsReceived
          ,BAI_SPS_IC
          ,FromBAIValidity
          ,ToBAIValidity
          ,BPI_SPS_IC
          ,FromBPIValidity
          ,ToBPIValidity
          ,MBL
          ,BL
          ,ShippingLineID
          ,Vessel
          ,HBL
          ,Forwarder
          ,ETD
          ,ETA
          ,ATA
          ,ContainerTypeID
          ,NoOfContainer
          ,NoOfTruck
          ,Quantity
          ,BrokerID
          ,DateDocsReceivedByBroker
          ,BAI_SPS_IC_Date
          ,BPI_SPS_IC_Date
          ,OriginalDocsAvailavilityDate
          ,BankID
          ,DateOfPickup
          ,PortOfDischarge
          ,Status
          ,DateOfDischarge
          ,LodgementDate
          ,LodgementBankID
          ,GatepassRecieved
          ,AcknowledgeByLogistics
          ,StorageLastFreeDate
          ,DemurrageDate
          ,DetentionDate
          ,Remarks
          ,UserID
           FROM ShippingTransaction WHERE isDel = 'False'";
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