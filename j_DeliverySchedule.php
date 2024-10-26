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
$Status = "";
if(isset($_GET['status']))
{
$Status = $_GET['status'];
}
    $sql = "SELECT 
             ds.DeliveryScheduleID
            ,ds.SONumber
            ,c.CustomerID
            ,c.CustomerName
            ,ds.Address
            ,t.TruckID
            ,t.PlateNo
            ,ds.DateSchedule
            ,ds.TotalQty
            ,ds.Status
            ,ds.deleted
            FROM DeliverySchedule ds
            LEFT JOIN Customer c ON ds.CustomerID = c.CustomerID
            LEFT JOIN Truck t ON  ds.TruckID = t.TruckID
            WHERE ds.Status = ? AND ds.isDel = 'False'";
    $params = array($Status);
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