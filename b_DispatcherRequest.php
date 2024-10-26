
<?php 
    require 'connection.php';
    $data = json_decode(file_get_contents('php://input'));
    if ( sqlsrv_begin_transaction( $conn ) === false ) {
    die( print_r( sqlsrv_errors(), true ));
    } 
        if(isset($data))
        {
            $DispatcherRequestID=$data->DispatcherRequestID;
            $RequestDate=$data->RequestDate;
            $FromWarehouseLocationID = $data->FromWarehouseLocationID;
            $ToWarehouseLocationID=$data->ToWarehouseLocationID;
            $RawMaterialID=$data->RawMaterialID;
            $RequestWeight=$data->RequestWeight;
            $Status=$data->Status;
            $UserID=$data->UserID;
            //Query 
            $sql = "EXEC [dbo].[DispatcherRequests]
                    @DispatcherRequestID = ?,
                    @RequestDate = ?,
                    @FromWarehouseLocationID = ?,
                    @ToWarehouseLocationID = ?,
                    @RawMaterialID = ?,
                    @RequestWeight = ?,
                    @Status = ?,
                    @UserID = ?";
            $params = array($DispatcherRequestID,$RequestDate,$FromWarehouseLocationID,
            $ToWarehouseLocationID,$RawMaterialID,
            $RequestWeight,$Status,$UserID);
            $stmt = sqlsrv_query($conn,$sql,$params);
            while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
            {
                sqlsrv_commit($conn);
                echo $row['result']; 
            }
        }
?>
