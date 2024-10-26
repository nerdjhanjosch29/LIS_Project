<?php 

require_once 'connection.php';
if (sqlsrv_begin_transaction($conn) === false) {
  die( print_r( sqlsrv_errors(), true ));
}
$data = json_decode(file_get_contents('php://input'));
// $validateToken = include('validate_token.php');
// if(!$validateToken)
// {
//   http_response_code(404);
//   die();
// }
$Status= 0;
if(isset($_GET['status']))
{
$Status= $_GET['status'];
}
$id=0;
if(isset($_GET['id']))
{
$id= $_GET['id'];
}

if ($Status == 1 && $id == 0)
{
  $sql = "SELECT
          st.ShippingTransactionID
          ,st.ContractPerformaID
          ,cp.ContractNo
          ,st.Lot
          ,rm.RawMaterialID
          ,rm.RawMaterial
          ,st.Packaging
          ,s.SupplierID
          ,cp.SupplierAddress
          ,ct.Container
          ,bk.BankID
          ,bk.Bank
          ,bkl.BankID
          ,bkl.Bank AS LodgementBank
          ,st.LodgementBankID
          ,ct.ContainerTypeID
          ,st.NoOfContainer
          ,s.Supplier
          ,s.SupplierID
          ,rm.RawMaterialID
          ,rm.RawMaterial
          ,st.AdvanceDocumentsReceived
          ,b.Broker
          ,st.BAI_SPS_IC
          ,st.FromBAIValidity
          ,st.ToBAIValidity
          ,st.BPI_SPS_IC
          ,st.FromBPIValidity
          ,st.ToBPIValidity
          ,st.MBL
          ,st.BL
          ,sl.ShippingLineID
          ,st.Vessel
          ,sl.ShippingLine
          ,st.HBL
          ,st.Forwarder
          ,st.ETD
          ,st.ETA
          ,st.ATA
          ,st.NoOfTruck
          ,st.Quantity
          ,st.BrokerID
          ,st.DateDocsReceivedByBroker
          ,st.BAI_SPS_IC_Date
          ,st.BPI_SPS_IC_Date
          ,st.OriginalDocsAvailavilityDate
          ,st.BankID
          ,st.DateOfPickup
          ,st.PortOfDischarge
          ,st.Status
          ,st.DateofDischarge
          ,st.LodgementDate
          ,st.LodgementBankID
          ,st.GatepassRecieved
          ,st.AcknowledgeByLogistics
          ,st.StorageLastFreeDate
          ,st.DemurrageDate
          ,st.DetentionDate
          ,st.Remarks
          ,st.UserID
           FROM ShippingTransaction st
           LEFT JOIN ShippingLine sl ON st.ShippingLineID = sl.ShippingLineID
           LEFT JOIN ContainerType ct ON st.ContainerTypeID = ct.ContainerTypeID
           LEFT JOIN ContractPerforma cp ON st.ContractPerformaID = cp.ContractPerformaID
           LEFT JOIN Supplier s ON st.SupplierID = s.SupplierID
           LEFT JOIN RawMaterial rm ON st.RawMaterialID = rm.RawMaterialID
           LEFT JOIN Bank bk ON st.BankID = bk.BankID
           LEFT JOIN Bank bkl ON st.LodgementBankID = bkl.BankID
           LEFT JOIN Broker b ON st.BrokerID = b.BrokerID
           WHERE st.Status = 1 ORDER BY st.ETA ASC";

    $params = array($Status,$id);
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

}
else if ($Status == 2 && $id == 0)
{
  $sql = "SELECT 
           st.ShippingTransactionID
          ,st.ContractPerformaID
          ,cp.ContractNo
          ,st.Lot
          ,bk.BankID
          ,bk.Bank
          ,bkl.BankID
          ,bkl.Bank AS LodgementBank
          ,st.LodgementBankID
          ,rm.RawMaterialID
          ,rm.RawMaterial
          ,st.Packaging
          ,s.SupplierID
          ,cp.SupplierAddress
          ,ct.Container
          ,ct.ContainerTypeID
          ,st.NoOfContainer
          ,s.Supplier
          ,s.SupplierID
          ,rm.RawMaterialID
          ,rm.RawMaterial
          ,st.AdvanceDocumentsReceived
          ,b.Broker
          ,st.BAI_SPS_IC
          ,st.FromBAIValidity
          ,st.ToBAIValidity
          ,st.BPI_SPS_IC
          ,st.FromBPIValidity
          ,st.ToBPIValidity
          ,st.MBL
          ,st.BL
          ,sl.ShippingLineID
          ,sl.ShippingLine
          ,st.Vessel
          ,st.HBL
          ,st.Forwarder
          ,st.ETD
          ,st.ETA
          ,st.ATA
          ,st.NoOfTruck
          ,st.Quantity
          ,st.BrokerID
          ,st.DateDocsReceivedByBroker
          ,st.BAI_SPS_IC_Date
          ,st.BPI_SPS_IC_Date
          ,st.OriginalDocsAvailavilityDate
          ,st.BankID
          ,st.DateOfPickup
          ,st.PortOfDischarge
          ,st.Status
          ,st.DateofDischarge
          ,st.LodgementDate
          ,st.LodgementBankID
          ,st.GatepassRecieved
          ,st.AcknowledgeByLogistics
          ,st.StorageLastFreeDate
          ,st.DemurrageDate
          ,st.DetentionDate
          ,st.Remarks
          ,st.UserID
       FROM ShippingTransaction st
       LEFT JOIN ShippingLine sl ON st.ShippingLineID = sl.ShippingLineID
       LEFT JOIN ContainerType ct ON st.ContainerTypeID = ct.ContainerTypeID
       LEFT JOIN ContractPerforma cp ON st.ContractPerformaID = cp.ContractPerformaID
       LEFT JOIN Supplier s ON st.SupplierID = s.SupplierID
       LEFT JOIN RawMaterial rm ON st.RawMaterialID = rm.RawMaterialID
       LEFT JOIN Bank bk ON st.BankID = bk.BankID
       LEFT JOIN Bank bkl ON st.LodgementBankID = bkl.BankID
       LEFT JOIN Broker b ON st.BrokerID = b.BrokerID
       WHERE st.Status = 2 ORDER BY st.ATA DESC";
    $params = array($Status,$id);
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

}
else if ($Status == 2)
{
  $sql = "SELECT 
   st.ShippingTransactionID
  ,st.ContractPerformaID
  ,cp.ContractNo
  ,st.Lot
  ,bk.BankID
  ,bk.Bank
  ,bkl.BankID
  ,bkl.Bank AS LodgementBank
  ,st.LodgementBankID
  ,rm.RawMaterialID
  ,rm.RawMaterial
  ,st.Packaging
  ,s.SupplierID
  ,cp.SupplierAddress
  ,ct.Container
  ,ct.ContainerTypeID
  ,st.NoOfContainer
  ,s.Supplier
  ,s.SupplierID
  ,rm.RawMaterialID
  ,rm.RawMaterial
  ,st.AdvanceDocumentsReceived
  ,b.Broker
  ,st.BAI_SPS_IC
  ,st.FromBAIValidity
  ,st.ToBAIValidity
  ,st.BPI_SPS_IC
  ,st.FromBPIValidity
  ,st.ToBPIValidity
  ,st.MBL
  ,st.BL
  ,sl.ShippingLineID
  ,sl.ShippingLine
  ,st.Vessel
  ,st.HBL
  ,st.Forwarder
  ,st.ETD
  ,st.ETA
  ,st.ATA
  ,st.NoOfTruck
  ,st.Quantity
  ,st.BrokerID
  ,st.DateDocsReceivedByBroker
  ,st.BAI_SPS_IC_Date
  ,st.BPI_SPS_IC_Date
  ,st.OriginalDocsAvailavilityDate
  ,st.BankID
  ,st.DateOfPickup
  ,st.PortOfDischarge
  ,st.Status
  ,st.DateofDischarge
  ,st.LodgementDate
  ,st.LodgementBankID
  ,st.GatepassRecieved
  ,st.AcknowledgeByLogistics
  ,st.StorageLastFreeDate
  ,st.DemurrageDate
  ,st.DetentionDate
  ,st.Remarks
  ,st.UserID
  FROM ShippingTransaction st
  LEFT JOIN ShippingLine sl ON st.ShippingLineID = sl.ShippingLineID
  LEFT JOIN ContainerType ct ON st.ContainerTypeID = ct.ContainerTypeID
  LEFT JOIN ContractPerforma cp ON st.ContractPerformaID = cp.ContractPerformaID
  LEFT JOIN Supplier s ON st.SupplierID = s.SupplierID
  LEFT JOIN RawMaterial rm ON st.RawMaterialID = rm.RawMaterialID
  LEFT JOIN Bank bk ON st.BankID = bk.BankID
  LEFT JOIN Bank bkl ON st.LodgementBankID = bkl.BankID
  LEFT JOIN Broker b ON st.BrokerID = b.BrokerID
     WHERE  st.ContractPerformaID = ? AND (st.Status = ? OR st.Status = 3) ORDER BY st.Status ASC";
    $params = array($id,$Status);
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
}
else if($Status == 1)
{
  $sql = "SELECT 
   st.ShippingTransactionID
  ,st.ContractPerformaID
  ,cp.ContractNo
  ,st.Lot
  ,bk.BankID
  ,bk.Bank
  ,bkl.BankID
  ,bkl.Bank AS LodgementBank
  ,st.LodgementBankID
  ,rm.RawMaterialID
  ,rm.RawMaterial
  ,st.Packaging
  ,s.SupplierID
  ,cp.SupplierAddress
  ,ct.Container
  ,ct.ContainerTypeID
  ,st.NoOfContainer
  ,s.Supplier
  ,s.SupplierID
  ,rm.RawMaterialID
  ,rm.RawMaterial
  ,st.AdvanceDocumentsReceived
  ,b.Broker
  ,st.BAI_SPS_IC
  ,st.FromBAIValidity
  ,st.ToBAIValidity
  ,st.BPI_SPS_IC
  ,st.FromBPIValidity
  ,st.ToBPIValidity
  ,st.MBL
  ,st.BL
  ,sl.ShippingLineID
  ,sl.ShippingLine
  ,st.Vessel
  ,st.HBL
  ,st.Forwarder
  ,st.ETD
  ,st.ETA
  ,st.ATA
  ,st.NoOfTruck
  ,st.Quantity
  ,st.BrokerID
  ,st.DateDocsReceivedByBroker
  ,st.BAI_SPS_IC_Date
  ,st.BPI_SPS_IC_Date
  ,st.OriginalDocsAvailavilityDate
  ,st.BankID
  ,st.DateOfPickup
  ,st.PortOfDischarge
  ,st.Status
  ,st.DateofDischarge
  ,st.LodgementDate
  ,st.LodgementBankID
  ,st.GatepassRecieved
  ,st.AcknowledgeByLogistics
  ,st.StorageLastFreeDate
  ,st.DemurrageDate
  ,st.DetentionDate
  ,st.Remarks
  ,st.UserID
  FROM ShippingTransaction st
  LEFT JOIN ShippingLine sl ON st.ShippingLineID = sl.ShippingLineID
  LEFT JOIN ContainerType ct ON st.ContainerTypeID = ct.ContainerTypeID
  LEFT JOIN ContractPerforma cp ON st.ContractPerformaID = cp.ContractPerformaID
  LEFT JOIN Supplier s ON st.SupplierID = s.SupplierID
  LEFT JOIN RawMaterial rm ON st.RawMaterialID = rm.RawMaterialID
  LEFT JOIN Bank bk ON st.BankID = bk.BankID
  LEFT JOIN Bank bkl ON st.LodgementBankID = bkl.BankID
  LEFT JOIN Broker b ON st.BrokerID = b.BrokerID
  WHERE  st.ContractPerformaID = ? AND (st.Status = 1 OR st.Status = 2 OR st.Status = 3) ORDER BY st.Status ASC";
    //  WHERE  st.Status = ? OR st.Status = 2 OR st.Status = 3 AND st.ContractPerformaID = ? ORDER BY st.Status ASC
    $params = array($id);
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
}
else if($Status == 3)
{
  // Ensure no output is sent before headers
  // ob_start();
  // Fetch ShippingTransaction data
      $sql = "SELECT 
       st.ShippingTransactionID
      ,st.ContractPerformaID
      ,cp.ContractNo
      ,st.Packaging
      ,s.SupplierID
      ,cp.SupplierAddress
      ,s.Supplier
      ,s.SupplierID
      ,st.MBL
      ,st.BL
      ,st.HBL
      ,st.Forwarder
      ,b.Broker
      ,st.Status
      ,st.StorageLastFreeDate
      ,st.DemurrageDate
      ,st.DetentionDate
      ,st.DateOfDischarge
      FROM ShippingTransaction st
      LEFT JOIN ShippingLine sl ON st.ShippingLineID = sl.ShippingLineID
      LEFT JOIN ContractPerforma cp ON st.ContractPerformaID = cp.ContractPerformaID
      LEFT JOIN ContainerType ct ON st.ContainerTypeID = ct.ContainerTypeID
      LEFT JOIN Supplier s ON st.SupplierID = s.SupplierID
      LEFT JOIN RawMaterial rm ON st.RawMaterialID = rm.RawMaterialID
      LEFT JOIN Broker b ON st.BrokerID = b.BrokerID
      WHERE st.Status = 3 AND st.ContractPerformaID = ?";         
      $params = array($id);
      $stmt1 = sqlsrv_query($conn, $sql, $params);
      $json = array();
      if ($stmt1) {
          while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
              $shippingTransaction = $row;
              $MBL = $row['MBL'];
              // Fetch PullOut details for the current ShippingTransaction
            $sqlPullOut = "SELECT
              po.PullOutID
             ,po.MBL
             ,st.SupplierID
             ,s.Supplier
             ,st.BrokerID
             ,b.Broker
             ,po.ContainerNumber
             ,po.DateOfDischarge
             ,po.Storage
             ,po.Demurrage
             ,po.DateIn
             ,po.DateOut
             ,po.PullOutDate
             ,po.Detention
             ,po.ReturnDate
             ,po.TruckingID
             ,t.TruckingName
             ,po.Remarks
              FROM PullOut po
              LEFT JOIN ShippingTransaction st ON po.MBL = st.MBL
              LEFT JOIN Supplier s ON st.SupplierID = s.SupplierID
              LEFT JOIN Broker b ON st.BrokerID = b.BrokerID
              LEFT JOIN Trucking t ON po.TruckingID = t.TruckingID
              WHERE po.MBL = ? AND st.Status = 3 AND deleted = 0 AND st.Packaging = 1";
              $paramsPullOut = array($MBL);
            
              $stmt2 = sqlsrv_query($conn, $sqlPullOut, $paramsPullOut);
              $pullOutDetails = array();
              if ($stmt2) {
                  while ($pullOutRow = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                      $pullOutDetails[] = $pullOutRow;
                  }
              }
              $shippingTransaction['PullOutDetail'] = $pullOutDetails;
              $json[] = $shippingTransaction;
          }
      }
      // Send headers and output the JSON
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($json);
    }
    

?>