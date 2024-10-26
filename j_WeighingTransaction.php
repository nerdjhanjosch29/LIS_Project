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
        $id = 0;
        if(isset($_GET['transid']))
        {
          $id = $_GET['transid'];
        }

        $sql = "SELECT [WeighingTransactionID]
        ,[TruckID]
        ,[PlateNo]
        ,[DriverID]
        ,[DriverName]
        ,[CheckerID]
        ,[SupplierID]
        ,[Supplier]
        ,[CustomerID]
        ,[CustomerName]
        ,[CheckerName]
        ,[DrNumber]
        ,[GrossWeight]
        ,[TareWeight]
        ,[NetWeight]
        ,[rmGrossWeight]
        ,[rmTareWeight]
        ,[rmNetWeight]
        ,[LossOverWeight]
        ,[ShippingID]
        ,[ShippingLine]
        ,[DateTimeArrived]
        ,[WeighInDate]
        ,[WeighOutDate]
        ,[Others]
        ,[NoOfBags]
        ,[isTransaction]
        ,[Remarks]
        ,[WeigherID]
        ,[WeigherName]
    FROM [LIS_db].[dbo].[View_WeighingTransaction] WHERE [isTransaction]= ?
        ";
        $params = array($id);
        $stmt1 = sqlsrv_query($conn,$sql,$params); 
        if($stmt1)
        {
          $json = array();
          do {
            while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
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