<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 


if(isset($data))
  {
      $RawMaterialInventoryID = 0;
      $RawMaterialID = 0;
      $InventoryDate = "";
      $BinloadingQty = 0;
      $BinloadingWeight = 0;
      $BinloadingPrice = 0;
      $CondemQty = 0;
      $CondemWeight = 0;
      $CondemPrice= 0;
    
    if(isset($data->RawMaterialInventoryID))
    { 
      $RawMaterialInventoryID=$data->RawMaterialInventoryID;
    }
    if(isset($data->RawMaterialID))
    { 
      $RawMaterialID=$data->RawMaterialID;
    }
    if(isset($data->InventoryDate))
    { 
      $InventoryDate=$data->InventoryDate;
    }
    if(isset($data->BinloadingQty))
    { 
      $BinloadingQty=$data->BinloadingQty;
    }
    if(isset($data->BinloadingWeight))
    { 
      $BinloadingWeight=$data->BinloadingWeight;
    }
    if(isset($data->BinloadingPrice))
    { 
      $BinloadingPrice=$data->BinloadingPrice;
    }
    if(isset($data->CondemQty))
    { 
      $CondemQty=$data->CondemQty;
    }
    if(isset($data->CondemWeight))
    { 
      $CondemWeight=$data->CondemWeight;
    }
    if(isset($data->CondemPrice))
    { 
      $CondemPrice=$data->CondemPrice;
    }
    $sql = "EXEC [dbo].[Binloading]
		@RawMaterialInventoryID = ?,
		@RawMaterialID = ?,
		@InventoryDate = ?,
		@BinloadingQty = ?,
		@BinloadingWeight = ?,
		@BinloadingPrice = ?,
		@CondemQty = ?,
		@CondemWeight = ?,
		@CondemPrice = ?";
    $params = array($RawMaterialInventoryID,$RawMaterialID, $InventoryDate, $BinloadingQty,$BinloadingWeight,$BinloadingPrice,$CondemQty,$CondemWeight,$CondemPrice);
    $stmt = sqlsrv_query($conn, $sql,$params);
 
  //  $sql = "EXEC	[dbo].[RawMaterialInventorys]
  //  @RawMaterialInventoryID = ?,
  //  @RawMaterialID = ?,
  //  @InventoryDate = ?,
  //  @IncomingQty = ?,
  //  @IncomingWeight = ?,
  //  @IncomingPrice = ?,
  //  @BinloadingQty = ?,
  //  @BinloadingWeight = ?,
  //  @BinloadingPrice = ?,
  //  @CondemQty = ?,
  //  @CondemWeight = ?,
  //  @CondemPrice = ?";
  //  $params = array($RawMaterialInventoryID,$RawMaterialID,$InventoryDate,$IncomingQty,
  //  $IncomingWeight,$IncomingPrice,$BinloadingQty,$BinloadingWeight,$BinloadingPrice,$CondemQty,$CondemWeight,$CondemPrice);
  //  $stmt = sqlsrv_query($conn, $sql,$params);

   while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
   {
    sqlsrv_commit($conn);
    echo $row['result'];
   }
  }

?>
