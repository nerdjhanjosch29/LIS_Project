<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
    if(isset($data))
    {
     $Plant = 0;
     if($data->Plant)
     {
        $Plant = $data->Plant;
     }   
        $array = $data->BinloadDetail;
        $lengths = count($array); // count array
        for($i=0; $i<=$lengths-1; $i++)
        { 
            $BinloadingDetailID = 0;
            $StockingDate = "";
            $BinloadingID = 0;
            $WarehouseID = 0;
            $WarehousePartitionID = 0;
            $WarehousePartitionStockID = 0;
            $RawMaterialID = 0;
            $Quantity = 0;
            $Weight = 0;
            $deleted = 0;
            if($array[$i]->BinloadingID)
            {
                $BinloadingID = $array[$i]->BinloadingID;
            } 
            if($array[$i]->StockingDate)
            {
                $StockingDate = $array[$i]->StockingDate;
            } 
            if($array[$i]->BinloadingDetailID)
            {
                $BinloadingDetailID = $array[$i]->BinloadingDetailID;
            }    
            if($array[$i]->WarehouseID)
            {
                $WarehouseID = $array[$i]->WarehouseID;
            }
            if($array[$i]->WarehousePartitionID)
            {
                $WarehousePartitionID = $array[$i]->WarehousePartitionID;
            }
            if($array[$i]->WarehousePartitionStockID)
            {
                $WarehousePartitionStockID = $array[$i]->WarehousePartitionStockID;
            }
            if($array[$i]->RawMaterialID)
            {
                $RawMaterialID = $array[$i]->RawMaterialID;
            }
            if($array[$i]->Quantity)
            {
                $Quantity=$array[$i]->Quantity;
            }
            if($array[$i]->Weight)
            {
                $Weight = $array[$i]->Weight;
            }
            if($BinloadingDetailID == 0)
            {
                $RawMatsQty = 0;
                $RawMatsWeight = 0;
                $sql = "SELECT RawMatsQty, RawMatsWeight FROM WarehousePartitionStock WHERE WarehousePartitionStockID = ?";
                $params = array($WarehousePartitionStockID);
                $stmt = sqlsrv_query($conn, $sql,$params);
                while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
                {
                    $RawMatsQty = $row['RawMatsQty'];
                    $RawMatsWeight = $row['RawMatsWeight'];
                }
                    $RawMatsQtys = $RawMatsQty - $Quantity;
                    $RawMatsWeights = $RawMatsWeight - $Weight;
                    $sql = "INSERT INTO BinloadingDetail (BinloadingID,StockingDate,WarehouseID, WarehousePartitionID, 
                    WarehousePartitionStockID,
                    RawMaterialID, Quantity, Weight,deleted)
                    VALUES (?,?,?,?,?,?,?,?,?)";
                    $params = array($BinloadingID,$StockingDate,$WarehouseID,$WarehousePartitionID,$WarehousePartitionStockID,$RawMaterialID,
                    $Quantity,$Weight,$deleted);
                    $stmt = sqlsrv_query($conn, $sql,$params);
                    sqlsrv_commit($conn);
                    if($stmt)
                    {
                        $sql = "UPDATE WarehousePartitionStock SET RawMatsQty = ?,RawMatsWeight = ? WHERE WarehousePartitionStockID = ?";
                        $params = array($RawMatsQtys,$RawMatsWeights,$WarehousePartitionStockID);
                        $stmt1 = sqlsrv_query($conn,$sql,$params);
                        sqlsrv_commit($conn);
                    }
                }
        } 
        if($BinloadingDetailID == 0)
        {
         echo 1;
        }
        else
        {
        echo 2;
        }
           

}
?>