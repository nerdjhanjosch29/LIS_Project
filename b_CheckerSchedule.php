
<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
    if(isset($data))
    {
        $ScheduleRotationID = 0;
        $UserID = "";
        $WarehouseLocationID = 0;
        $Category = 0;
        $PlantID = 0;
        $DateRotation = "";
        $AdminUserID = "";

        if($data->ScheduleRotationID)
        {
            $ScheduleRotationID=$data->ScheduleRotationID;
        }
        if($data->UserID)
        {
            $UserID=$data->UserID;
        }
        if($data->WarehouseLocationID)
        {
            $WarehouseLocationID=$data->WarehouseLocationID;
        }
        // if($data->Category)
        // {
        //     $Category=$data->Category;
        // }
        if($data->DateRotation)
        {
            $DateRotation=$data->DateRotation;
        }
        if($data->AdminUserID)
        {
            $AdminUserID=$data->AdminUserID;
        }
        //Query 
        $sql = "EXEC [dbo].[CheckerSchedules]
        @ScheduleRotationID = ?,
        @UserID = ?,
        @WarehouseLocationID = ?,
        @PlantID= ?,
        @Category = ?,
        @DateRotation = ?,
        @AdminUserID = ?";
        $params = array($ScheduleRotationID,$UserID,$WarehouseLocationID,$Category,$PlantID,$DateRotation,$AdminUserID);
        $stmt = sqlsrv_query($conn, $sql, $params);
        //   var_dump($params);
      while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
      {
        sqlsrv_commit($conn);
        echo $row['result']; 
      }
      if($ScheduleRotationID == 0)
      {
        $SystemLogID = 0;
        $FunctionID = 1;
        $TableName = "Checker Schedule";
        $sql = "EXEC [dbo].[SystemLogInsert] 
                @SystemLogID = ?,
                @UserID = ?,
                @FunctionID = ?,
                @TableName = ?";
        $params = array($SystemLogID,$AdminUserID,$FunctionID,$TableName);
        $stmt = sqlsrv_query($conn, $sql, $params);
      }
      {
        $SystemLogID = 0;
        $FunctionID = 2;
        $TableName = "Checker Schedule";
        $sql = "EXEC [dbo].[SystemLogEdit]
               @SystemLogID = ?,
               @UserID = ?,
               @FunctionID = ?,
               @TableName = ?";
        $params = array($SystemLogID,$AdminUserID,$FunctionID,$TableName);
        $stmt = sqlsrv_query($conn, $sql, $params);
      }
      sqlsrv_commit($conn);
    }
?>
