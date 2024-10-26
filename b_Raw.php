<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
}
if(isset($data))
{
  $TransferCode = 0;
  $RawMaterialTransferID = 0;
  $DispatcherID = 0;
  $WarehousePartitionStockID = 0;
  $FromWarehouseLocationID = 0;
  $FromWarehouseID = 0;
  $FromWarehousePartitionID = 0;
  $ToWarehouseLocationID = 0;
  $ToWarehouseID = 0;
  $ToWarehousePartitionID = 0;
  $FeedmixDeparture = "";  
  $WarehouseArrival = "";
  $WarehouseDeparture = "";
  $FeedmixArrival = "";
  $GuardID = 0;
  $RawMaterialID = 0;
  $DriverID = 0;
  $TruckID = 0;
  $Status = 0;
  $DepartureWeight = 0;
  $ArrivalWeight = 0;
  $WeigherOut = 0;
  $WeigherIn = 0;
  $Quantity = 0;
  $Weight = 0;
  $CheckerID = 0;
  $UserID = 0;
  $year = date('Y');
    // $DateTimeTransfer = date_format($DateTimeTransfer, "Y-m-d H:i");
    if($data->DateTransfer)
    {
        $DateTransfer = $data->DateTransfer;
    }
   if($data->DateTimeTransfer)
   {
        $DateTimeTransfer = $data->DateTimeTransfer;
        $DateTimeTransfer = date_create($DateTimeTransfer);
        $time = date_format($DateTimeTransfer, "H:i");
        // Check if the time is between 12:00 AM and 6:00 AM
        if ($time >= '00:00' && $time < '06:00') {
            date_sub($DateTimeTransfer, date_interval_create_from_date_string('1 day'));
            $DateTransfer = date_format($DateTimeTransfer, "Y-m-d");
            $DateTimeTransfer = date_add($DateTimeTransfer,date_interval_create_from_date_string("1 day")); 
        }
        else
        {
            date_sub($DateTimeTransfer, date_interval_create_from_date_string('1 day'));
            $DateTimeTransfer = date_add($DateTimeTransfer,date_interval_create_from_date_string("1 day"));
        }
        $DateTimeTransfer = date_format($DateTimeTransfer, "Y-m-d H:i");
  }
  if($data->RawMaterialTransferID)
  {
    $RawMaterialTransferID =$data->RawMaterialTransferID;
  }
  
  if($data->DispatcherID)
  {
    $DispatcherID = $data->DispatcherID;
  }
  // if($data->WarehousePartitionStockID)
  // {
  //   $WarehousePartitionStockID = $data->WarehousePartitionStockID;
  // }
  if($data->FromWarehouseLocationID)
  {
    $FromWarehouseLocationID = $data->FromWarehouseLocationID;
  }
  if($data->FromWarehouseID)
  {
    $FromWarehouseID =$data->FromWarehouseID;
  }
  if($data->FromWarehousePartitionID)
  {
    $FromWarehousePartitionID =$data->FromWarehousePartitionID;
  }
  if($data->ToWarehouseLocationID)
  {
  $ToWarehouseLocationID =$data->ToWarehouseLocationID;
  }
  if($data->ToWarehouseID)
  {
    $ToWarehouseID =$data->ToWarehouseID;
  }
  if($data->ToWarehousePartitionID)
  {
    $ToWarehousePartitionID =$data->ToWarehousePartitionID;
  }
  if($data->FeedmixDeparture)
  {
    $FeedmixDeparture = $data->FeedmixDeparture;
    $FeedmixDeparture=date_create($data->FeedmixDeparture);
    $FeedmixDeparture= date_format($FeedmixDeparture,"Y-m-d H:i");
  }
  if($data->WarehouseArrival)
  {
    $WarehouseArrival = $data->WarehouseArrival;
    $WarehouseArrival=date_create($data->WarehouseArrival);
    $WarehouseArrival= date_format($WarehouseArrival,"Y-m-d H:i");
  }
  if($data->WarehouseDeparture)
  {
    $WarehouseDeparture = $data->WarehouseDeparture;
    $WarehouseDeparture=date_create($data->WarehouseDeparture);
    $WarehouseDeparture= date_format($WarehouseDeparture,"Y-m-d H:i");
  }
  if($data->FeedmixArrival)
  {
    $FeedmixArrival = $data->FeedmixArrival;
    $FeedmixArrival=date_create($data->FeedmixArrival);
    $FeedmixArrival= date_format($FeedmixArrival,"Y-m-d H:i");
  }
  // if($data->GuardID)
  // {
  //   $GuardID =$data->GuardID;
  // }
  if($data->RawMaterialID)
  {
    $RawMaterialID =$data->RawMaterialID;
  }
  if($data->Status)
  {
    $Status = $data->Status;
  }
  if($data->DepartureWeight)
  {
    $DepartureWeight = $data->DepartureWeight;
  }
  if($data->ArrivalWeight)
  {
    $ArrivalWeight = $data->ArrivalWeight;
  }
  if($data->WeigherOut)
  {
    $WeigherOut = $data->WeigherOut;
  }
  if($data->WeigherIn)
  {
    $WeigherIn = $data->WeigherIn;
  }
  if($data->DriverID)
  {
    $DriverID = $data->DriverID;
  }
  if($data->TruckID)
  {
    $TruckID =$data->TruckID;
  }
  if($data->Quantity)
  {
    $Quantity =$data->Quantity;
  }
  if($data->Weight)
  {
    $Weight =$data->Weight;
  }
  if($data->CheckerID)
  {
    $CheckerID =$data->CheckerID;
  }
  if($data->UserID)
  {
    $UserID =$data->UserID;
  }
  $sql = "SELECT TOP 1 ISNULL(TransferCode, 0) AS TransferCode FROM RawMaterialTransfer WHERE TransferCode LIKE ? ORDER BY TransferCode DESC";
  $params = array($year. "%");
  $stmt = sqlsrv_query($conn,$sql,$params);
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
  $TransferCode = $row['TransferCode'];
  }      
  if($TransferCode == 0)
  {
  $TransferCode = $year."0001"; 
  }
  else
  {
  $TransferCode = $TransferCode + 1;  
  }
    $sql="EXEC [dbo].[RawMaterialTransfers]	
    @RawMaterialTransferID = ?,
    @TransferCode = ?,
    @DateTransfer = ?,
    @DateTimeTransfer = ?,
    @DispatcherID = ?,
    @WarehousePartitionStockID = ?,
    @FromWarehouseLocationID = ?,
    @FromWarehouseID = ?, 
    @FromWarehousePartitionID = ?,
    @ToWarehouseLocationID = ?,
    @ToWarehouseID = ?,
    @ToWarehousePartitionID = ?,
    @FeedmixDeparture = ?,
    @WarehouseArrival = ?,
    @WarehouseDeparture = ?,
    @FeedmixArrival = ?,
    @GuardID = ?,
    @RawMaterialID = ?,
    @Status = ?,
    @DepartureWeight = ?,
    @ArrivalWeight =?,
    @WeigherOut = ?,
    @WeigherIn = ?,
    @DriverID = ?,
    @TruckID = ?,
    @Quantity = ?,
    @Weight = ?,
    @CheckerID = ?,
    @UserID = ?";
    $params = array($RawMaterialTransferID,$TransferCode,$DateTransfer,$DateTimeTransfer,$DispatcherID,$WarehousePartitionStockID,$FromWarehouseLocationID,$FromWarehouseID,$FromWarehousePartitionID,
    $ToWarehouseLocationID,$ToWarehouseID,$ToWarehousePartitionID,$FeedmixDeparture,$WarehouseArrival,$WarehouseDeparture,
    $FeedmixArrival,$GuardID,$RawMaterialID,$Status,$DepartureWeight,$ArrivalWeight,$WeigherOut,$WeigherIn,$DriverID,$TruckID,$Quantity,$Weight,$CheckerID,$UserID);
    $stmt = sqlsrv_query($conn,$sql,$params);
    // var_dump($Status);
    $result = "";
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
    sqlsrv_commit($conn);
    echo $result = $row['result'];
    }
  
}

?>