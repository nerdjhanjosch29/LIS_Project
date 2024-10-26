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
$DeliveryScheduleID = "";
if(isset($_GET['id']))
{
$DeliveryScheduleID = $_GET['id'];
}
    $sql = "SELECT 
     d.DeliveryScheduleID
    ,d.SONumber
    ,d.CustomerID
    ,c.CustomerName
    ,d.Address
    ,d.TruckID
    ,t.PlateNo
    ,d.TotalQty
    ,d.Status
    ,d.UserID
    ,d.DateSchedule  
    FROM DeliverySchedule d
    LEFT JOIN Customer c ON d.CustomerID = c.CustomerID 
    LEFT JOIN Truck t ON d.TruckID = t.TruckID
    WHERE d.deleted = 0 AND d.Status = 'Pending' AND d.isDel = 'False'";
    $stmt1 = sqlsrv_query($conn,$sql); 
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