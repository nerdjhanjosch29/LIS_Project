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
$DeliveryID = "";
if(isset($_GET['id']))
{
$DeliveryID = $_GET['id'];
}
    $sql = "SELECT *,
             wp.WarehousePartitionName
            ,w.Warehouse_Name
            ,wl.WarehouseLocation
            FROM DeliveryDetail dd 
            LEFT JOIN WarehousePartition wp ON dd.WarehousePartitionID = wp.WarehousePartitionID
            LEFT JOIN Warehouse w ON wp.WarehouseID = w.WarehouseID
            LEFT JOIN WarehouseLocation wl ON w.WarehouseLocationID = wl.WarehouseLocationID 
            WHERE dd.DeliveryID = $DeliveryID AND dd.deleted = 0";
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