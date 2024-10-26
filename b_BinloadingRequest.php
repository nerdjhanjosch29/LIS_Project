<?php 
    require 'connection.php';
    $data = json_decode(file_get_contents('php://input'));
    if ( sqlsrv_begin_transaction( $conn ) === false ) {
    die( print_r( sqlsrv_errors(), true ));
    } 
    if(isset($data))
    {
            $BinloadRequestID = 0;
            $PO = 0;
            $BL = 0;
            $WarehouseLocationID = 0;
            $WarehouseID = 0;
            $WarehousePartitionID = 0;
            $WarehousePartitionStockID = 0;
            $PlantID = 0;
            $DriverID = 0;
            $TruckID = 0;
            $RequestDate = "";
            $RawMaterialID = 0;
            $Quantity = 0;
            $Weight = 0;
            $Status = 0;
            $UserID = "";
            if($data->BinloadRequestID)
            {
                $BinloadRequestID = $data->BinloadRequestID;
            }
            if($data->PO)
            {
                $PO = $data->PO;
            }   
            if($data->BL)
            {
                $BL = $data->BL;
            }         
            if($data->WarehouseLocationID)
            {
                $WarehouseLocationID = $data->WarehouseLocationID;
            }  
            if($data->WarehouseID)
            {
                $WarehouseID = $data->WarehouseID;
            } 
            if($data->WarehousePartitionID)
            {
                $WarehousePartitionID = $data->WarehousePartitionID;
            }      
            if($data->WarehousePartitionStockID)
            {
                $WarehousePartitionStockID = $data->WarehousePartitionStockID;
            } 
            if($data->PlantID)
            {
                $PlantID = $data->PlantID;
            }    
            if($data->DriverID)
            {
                $DriverID = $data->DriverID;
            }    
            if($data->TruckID)
            {
                $TruckID = $data->TruckID;
            }   
            if($data->RequestDate)
            {
                $RequestDate = $data->RequestDate;
            }            
            if($data->RawMaterialID)
            {
                $RawMaterialID = $data->RawMaterialID;
            }
            if($data->Quantity)
            {
                $Quantity = $data->Quantity;
            }
            if($data->Weight)
            {
                $Weight = $data->Weight;
            }
            if($data->Status)
            {
                $Status = $data->Status;
            }
            if($data->UserID)
            {
                $UserID = $data->UserID;
            }
            if($BinloadRequestID == 0)
            {
                $sql = "INSERT INTO BinloadRequest(PO,BL,WarehouseLocationID,WarehouseID,WarehousePartitionID,
                WarehousePartitionStockID,PlantID,DriverID,TruckID,RequestDate,
                RawMaterialID,Quantity,Weight,Status,UserID)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                SELECT SCOPE_IDENTITY()";
                $params = array($PO,$BL,$WarehouseLocationID,$WarehouseID,$WarehousePartitionID,$WarehousePartitionStockID,
                $PlantID,$DriverID,$TruckID,$RequestDate,
                $RawMaterialID,$Quantity,$Weight,$Status,$UserID);
                $stmt = sqlsrv_query($conn, $sql, $params);
                sqlsrv_commit($conn);
                if($stmt)
                {
                    echo 1; 
                }     
            }
            else
            {
                $sql = "UPDATE BinloadRequest SET WarehouseLocationID = ?,
                PlantID = ?,DriverID = ?, TruckID = ?,
                RequestDate = ?, RawMaterialID = ?, Quantity = ?, Weight = ?, Status = ?, UserID = ? 
                WHERE BinloadRequestID = ?";
                $params = array($WarehouseLocationID,$PlantID,$DriverID,$TruckID,$RequestDate,$RawMaterialID,$Quantity,
                $Weight,$Status,$UserID,$BinloadRequestID);
                $stmt = sqlsrv_query($conn,$sql,$params);
                sqlsrv_commit($conn);
                echo 2;
            }
            if($BinloadingDetailID == 0)
            {
                $SystemLogID = 0;
                $FunctionID = 1;
                $TableName = "BinloadRequest";
                $sql = "EXEC [dbo].[SystemLogInsert]
                        @SystemLogID = ?,
                        @UserID = ?,
                        @FunctionID = ?.
                        @TableName = ?";
                $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
                $stmt = sqlsrv_query($conn, $sql, $params);
            }
            else
            {
                $SystemLogID = 0;
                $FunctionID = 2;
                $TableName = "BinloadRequest";
                $sql = "EXEC [dbo].[SystemLogEdit]
                        @SystemLogID = ?,
                        @UserID = ?,
                        @FunctionID = ?,
                        @TableName = ?";
                $params = array($SystemLogID,$UserID,$FunctionID,$TableName);
                $stmt = sqlsrv_query($conn,$sql,$params);
            }
            sqlsrv_commit($conn);         
     }   
    
?>
