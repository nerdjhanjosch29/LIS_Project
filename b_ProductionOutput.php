
<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if (sqlsrv_begin_transaction($conn) === false) {
    die(print_r(sqlsrv_errors(), true));
}



if (isset($data)) 
{
    $ProductionOutputID = $data->ProductionOutputID;
    $PlantID = $data->PlantID;
    $LineNumber = $data->LineNumber;
    $FinishProductID = $data->FinishProductID;
    $WarehouseID = $data->WarehouseID;
    $WarehousePartitionID = $data->WarehousePartitionID;
    $DateOutput = $data->DateOutput;
    $DateTimeOutput = $data->DateTimeOutput;
    $DateTimeOutput = date_create($DateTimeOutput);
    $time = date_format($DateTimeOutput, "H:i");
    // Check if the time is between 12:00 AM and 6:00 AM
    if ($time >= '00:00' && $time < '06:00') {
      
        date_sub($DateTimeOutput, date_interval_create_from_date_string('1 day'));
        $DateOutput = date_format($DateTimeOutput, "Y-m-d");
        $DateTimeOutput = date_add($DateTimeOutput,date_interval_create_from_date_string("1 day")); 
    }
    else
    {
        date_sub($DateTimeOutput, date_interval_create_from_date_string('1 day'));
        $DateTimeOutput = date_add($DateTimeOutput,date_interval_create_from_date_string("1 day"));
    }
    $DateTimeOutput = date_format($DateTimeOutput, "Y-m-d H:i");
    $Quantity = $data->Quantity;
    $Weight = $data->Weight;
    $UserID = $data->UserID;
    $sql = "EXEC [dbo].[ProductionOutputss]
    @ProductionOutputID = ?,
    @PlantID = ?,
    @LineNumber = ?,
    @FinishProductID = ?,
    @WarehouseID = ?,
    @WarehousePartitionID = ?,
    @DateTimeOutput = ?,
    @DateOutput = ?,
    @Quantity = ?,
    @Weight = ?,
    @UserID = ?"; 
    $params = array($ProductionOutputID, $PlantID, $LineNumber, $FinishProductID, $WarehouseID, 
    $WarehousePartitionID, $DateTimeOutput,$DateOutput, $Quantity, $Weight, $UserID);
    // var_dump($params);
    $stmt = sqlsrv_query($conn, $sql, $params);
   $result = "";
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
   
      sqlsrv_commit($conn);
        echo $result = $row['result'];
    } 
    if($result == 1)
    {
      $sql = "EXEC [dbo].[AddProd]
      @ProductionOutputID = ?,
      @PlantID = ?,
      @LineNumber = ?,
      @FinishProductID = ?,
      @WarehouseID = ?,
      @WarehousePartitionID = ?,
      @DateTimeOutput = ?,
      @DateOutput = ?,
      @Quantity = ?,
      @Weight = ?,
      @UserID = ?";
      $params = array($ProductionOutputID, $PlantID, $LineNumber, $FinishProductID, $WarehouseID, 
      $WarehousePartitionID, $DateTimeOutput, $DateOutput, $Quantity,$Weight,$UserID);
      $stmt = sqlsrv_query($conn, $sql, $params);
    }   
}
?>
