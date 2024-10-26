
<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
  if(isset($data))
  {
    $BinloadingID = 0;
    $BinloadRequestID = 0;
    $ControlNo = "CN" . rand();
    $PO = 0;
    $BL = 0;
    $PlantID = 0;
    $CheckerID = 0;
    $IntakeID = 0;
    $WarehousePartitionStockID = 0;
    $WarehouseLocationID = 0;
    $WarehousePartitionID = 0;
    $WarehouseID = 0;
    $BinloadingDate = "";
    $BinloadingDateTime = "";
    $RawMaterialID =0;
    $Quantity = 0;
    $Weight = 0;
    $Status = 0;
    $UserID = "";
    $RequestDate = "";
    if($data->BinloadingID)
    {
      $BinloadingID=$data->BinloadingID;
    }
    if($data->BinloadRequestID)
    {
      $BinloadRequestID=$data->BinloadRequestID; 
    }
    if($data->PO)
    {
      $PO=$data->PO; 
    }
    if($data->BL)
    {
      $BL=$data->BL; 
    }
    if($data->PlantID)
    {
      $PlantID=$data->PlantID; 
    }
    if($data->CheckerID)
    {
      $CheckerID=$data->CheckerID; 
    }
    if($data->IntakeID)
    {
      $IntakeID=$data->IntakeID; 
    }
    if($data->WarehouseLocationID)
    {
      $WarehouseLocationID=$data->WarehouseLocationID;
    }
    if($data->WarehousePartitionStockID)
    {
      $WarehousePartitionStockID=$data->WarehousePartitionStockID;
    }
    if($data->BinloadingDate)
    {
      $BinloadingDate= $data->BinloadingDate;
    }
    if($data->BinloadingDateTime)
    {
      $BinloadingDateTime = $data->BinloadingDateTime;
    }
    $BinloadingDateTime= date_create($BinloadingDateTime);
    $time = date_format($BinloadingDateTime, "H:i");
    // Check if the time is between 12:00 AM and 6:00 AM
    if ($time >= '00:00' && $time < '06:00') 
    {
      date_sub($BinloadingDateTime, date_interval_create_from_date_string('1 day'));
      $DateOutput = date_format($BinloadingDateTime, "Y-m-d");
      $BinloadingDateTime = date_add($BinloadingDateTime,date_interval_create_from_date_string("1 day")); 
    }
    else
    {
      date_sub($BinloadingDateTime, date_interval_create_from_date_string('1 day'));
      $BinloadingDateTime = date_add($BinloadingDateTime,date_interval_create_from_date_string("1 day"));
    }
    $BinloadingDateTime = date_format($BinloadingDateTime, "Y-m-d H:i");
    if($data->WarehousePartitionStockID)
    {
      $WarehousePartitionStockID=$data->WarehousePartitionStockID; 
    }
    if($data->WarehouseID)
    {
      $WarehouseID=$data->WarehouseID; 
    }
    if($data->WarehousePartitionID)
    {
      $WarehousePartitionID=$data->WarehousePartitionID; 
    }
    if($data->RawMaterialID)
    {
      $RawMaterialID=$data->RawMaterialID; 
    }
    if($data->Quantity)
    {
      $Quantity=$data->Quantity; 
    }
    if($data->Weight)
    {
      $Weight=$data->Weight; 
    }
    if($data->Status)
    {
      $Status=$data->Status;
    }
    if($data->UserID)
    {
      $UserID=$data->UserID;
    }
    if($BinloadingID == 0)
    {
    // $BinloadingIDs = "";
    $sql = "INSERT INTO Binloading(BinloadRequestID,PO,BL,WarehousePartitionStockID,ControlNo,PlantID,CheckerID,IntakeID,
    BinloadingDate,BinloadingDateTime,RawMaterialID,Quantity,Weight,Status,UserID)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $params = array($BinloadRequestID,$PO,$BL,$WarehousePartitionStockID,$ControlNo,$PlantID,$CheckerID,$IntakeID,$BinloadingDate,
    $BinloadingDateTime,$RawMaterialID,$Quantity,$Weight, $Status,$UserID);
    $stmt = sqlsrv_query($conn,$sql,$params);
    sqlsrv_commit($conn);
    // var_dump($stmt);
    if($stmt)
    {
     echo 1;
 //Execute stored Procedure for Inventory 
    $sql ="EXEC [dbo].[BinloadingInventory]
           @BinloadingID = ?, 
           @PO = ?,
           @BL = ?,
           @ControlNo = ?, 	
           @PlantID = ?, 	
           @CheckerID = ?, 	
           @WarehouseID = ?,	
           @IntakeID = ?, 	
           @WarehouseLocationID = ?, 	
           @WarehousePartitionID = ?, 	
           @BinloadingDate = ?, 
           @BinloadingDateTime = ?,	
           @RawMaterialID = ?,	
           @Quantity = ?, 
           @Weight = ?, 	
           @Status = ?, 	
           @UserID = ?";
           $params1 = array($BinloadingID,$PO,$BL,$ControlNo,$PlantID,$CheckerID,$WarehouseID,$IntakeID,$WarehouseLocationID,
                            $WarehousePartitionID,$BinloadingDate,$BinloadingDateTime,$RawMaterialID,$Quantity,
                            $Weight,$Status,$UserID);
           $stmt11 = sqlsrv_query($conn,$sql,$params1);
        // var_dump($WarehousePartitionStockID);
           $RawMatsQty = 0;
           $RawMatsWeight = 0;
           $sql = "SELECT RawMatsQty,RawMatsWeight
                    FROM WarehousePartitionStock WHERE WarehousePartitionStockID = ?";
           $params = array($WarehousePartitionStockID);
           $stmt = sqlsrv_query($conn, $sql,$params);
           while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
           {
            $RawMatsQty = $row['RawMatsQty'];
            $RawMatsWeight = $row['RawMatsWeight'];
           }
           $RawMatsQtys = $RawMatsQty - $Quantity;
           $RawMatsWeights = $RawMatsWeight - $Weight;          
           $sql = "UPDATE WarehousePartitionStock SET RawMatsQty = ?,RawMatsWeight = ?,
           BinloadControlNo = ?
           WHERE WarehousePartitionStockID = ?";
           $params = array($RawMatsQtys,$RawMatsWeights,$ControlNo,$WarehousePartitionStockID);
           $stmt1 = sqlsrv_query($conn, $sql,$params);
           sqlsrv_commit($conn);                
    }              
  }
  else
  {
    $sql="UPDATE Binloading SET ControlNo = ?,PlantID = ?,CheckerID = ?,DriverID = ?,TruckID = ?,WarehouseID = ?,
    WarehouseLocationID = ?,WarehousePartitionID = ?,
    WarehousePartitionStockID = ?,BinloadingDate = ?,BinloadingDateTime = ?,RawMaterialID = ?,
    Quantity = ?, Weight = ?, Status = ?,UserID = ? WHERE BinloadingID = ?";
    $params = array($ControlNo,$PlantID,$CheckerID,$DriverID,$TruckID,$WarehouseID,$WarehouseLocationID,$WarehousePartitionID,
    $WarehousePartitionStockID,$BinloadingDate,$BinloadingDateTime,$RawMaterialID,$TotalQuantity,$TotalWeight,$Status,$UserID,$BinloadingID);
    $stmt = sqlsrv_query($conn, $sql, $params);
    sqlsrv_commit($conn);
    echo 2;                
  }

  if($BinloadingID == 0)
  {
    $SystemLogID = 0;
    $FunctionID = 1;
    $TableName = "Binloading";
    $sql = "EXEC [dbo].[SystemLogInsert]
            @SystemLogID = ?,
            @UserID = ?,
            @FunctionID = ?,
            @TableName = ?";
    $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
    $stmt = sqlsrv_query($conn, $sql, $params);

  }
  else
  {
    $SystemLogID = 0;
    $FunctionID = 2;
    $TableName = "Binloading"; 
    $sql = "EXEC [dbo].[SystemLogEdit]
            @SystemLogID = ?,
            @UserID = ?,
            @FunctionID = ?,
            @TableName = ?";
    $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
    $stmt = sqlsrv_query($conn, $sql, $params);
  }
}
?>
