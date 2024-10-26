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
    $sql = " SELECT 
             dd.DeliveryScheduleDetailsID
            ,dd.DeliveryScheduleID
            ,dd.FinishProductID
            ,dd.Quantity
            ,dd.deleted
            FROM DeliveryScheduleDetails dd
            WHERE DeliveryScheduleID = ? AND deleted = 0";
    $params = array($DeliveryScheduleID);
    $stmt1 = sqlsrv_query($conn,$sql, $params); 
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