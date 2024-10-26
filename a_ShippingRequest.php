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
$ContractPerformaID = "";
if(isset($_GET['id']))
{
$ContractPerformaID = $_GET['id'];
}
    $sql = "SELECT  
             st.ShippingTransactionID
            ,st.ContractPerformaID
            ,st.SPSICNumber
            ,st.Validity
            ,st.BLNumber
            ,sl.ShippingLine
            ,sl.ShippingLineID
            ,ct.Container
            ,ct.ContainerTypeID
            ,st.NoOfContainer
            ,s.Supplier
            ,s.SupplierID
            ,rm.RawMaterialID
            ,rm.RawMaterial
            ,st.PortOfDischargeID
            ,st.EstimatedTimeDeparture
            ,st.EstimatedTimeArrival
            ,st.DocumentStatus
            ,st.DateDocumentReceived
            ,st.BrokerID
            ,b.Broker
            ,st.DateDocsReceivedByBroker
            ,st.ImportClearanceBaiDate
            ,st.ImportClearanceBPIDate
            ,st.BankID
            ,st.AvailabilityDate
            ,st.PickupDate
            ,st.ShipmentPeriod
            ,st.Remarks
            ,st.UserID
          FROM ShippingTransaction st
          LEFT JOIN ShippingLine sl ON st.ShippingLineID = sl.ShippingLineID
          LEFT JOIN ContainerType ct ON st.ContainerTypeID = ct.ContainerTypeID
          LEFT JOIN Supplier s ON st.SupplierID = s.SupplierID
          LEFT JOIN RawMaterial rm ON st.RawMaterialID = rm.RawMaterialID
          LEFT JOIN Broker b ON st.BrokerID = b.BrokerID
          WHERE ContractPerformaID = ?";
    $params = array($ContractPerformaID);
    $stmt1 = sqlsrv_query($conn,$sql,$params); 
    if($stmt1)
    {
      $json = array();
      do 
      {
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

?>