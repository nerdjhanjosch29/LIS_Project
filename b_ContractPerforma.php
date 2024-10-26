<?php
require 'connection.php';

// Get JSON data from request body
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 

if (isset($data)) {
    // Extract data from JSON
    $ContractPerformaID = $data->ContractPerformaID;
    $ContractNo = $data->ContractNo;
    $Quantity = $data->Quantity;
    $EstimatedContainer = $data->EstimatedContainer;
    $Packaging = $data->Packaging;
    $PackedInID = $data->PackedInID;
    $RawMaterialID = $data->RawMaterialID;
    $SupplierID = $data->SupplierID;
    $SupplierAddress = $data->SupplierAddress;
    $PortOfDischargeID = $data->PortOfDischargeID;
    $FromShipmentPeriod = $data->FromShipmentPeriod;
    $ToShipmentPeriod = $data->ToShipmentPeriod;
    $CountryOfOrigin = $data->CountryOfOrigin;
    $Status = $data->Status;
    $UserID = $data->UserID;

    // Define the stored procedure call
    $sql = "EXEC [dbo].[ContractPerformas]
    @ContractPerformaID = ?,
    @ContractNo = ?,
    @Quantity = ?,
    @EstimatedContainer = ?,
    @Packaging = ?,
    @PackedInID = ?,
    @RawMaterialID = ?,
    @SupplierID = ?,
    @SupplierAddress = ?,
    @PortOfDischargeID = ?,
    @FromShipmentPeriod = ?, 
    @ToShipmentPeriod = ?,
    @CountryOfOrigin = ?,
    @Status = ?,
    @UserID = ?";
    // Prepare parameters
    $params = array($ContractPerformaID,$ContractNo,$Quantity,$EstimatedContainer,
        $Packaging,$PackedInID,$RawMaterialID,$SupplierID, $SupplierAddress,$PortOfDischargeID,
        $FromShipmentPeriod,$ToShipmentPeriod,$CountryOfOrigin,$Status, $UserID);
    // Execute the stored procedure
    $stmt = sqlsrv_query($conn, $sql, $params);
 
    // Fetch the result
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
     {
        echo $row['result'];
        sqlsrv_commit($conn);
     }
  if($ContractPerformaID == 0)
  {
    $SystemLogID = 0;
    $FunctionID = 1;
    $TableName = "Contract Performa";
    $sql = "EXEC [dbo].[SystemLogInsert]
            @SystemLogID = ?, 
            @UserID = ?,
            @FunctionID = ?,
            @TableName = ?";
    $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
    $stmt = sqlsrv_query($conn, $sql,$params);
  }
  else
  {
    $SystemLogID = 0;
    $FunctionID = 2;
    $TableName = "Contract Performa"; 
    $sql = "EXEC [dbo].[SystemLogEdit]
            @SystemLogID = ?,
            @UserID = ?,
            @FunctionID = ?,
            @TableName = ?";
    $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
    $stmt = sqlsrv_query($conn,$sql,$params);
  }
}
?>