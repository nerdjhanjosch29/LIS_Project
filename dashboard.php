<?php 
  require 'connection.php';
  $sql = "SELECT COUNT(ShippingTransactionID) AS [Total Shipment] FROM ShippingTransaction";
  $stmt = sqlsrv_query($conn, $sql);
  $totalShip = "";
  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
  {
   $totalShip = $row['Total Shipment'];
  }
  if($totalShip == 0)
  {
    echo 0;
  }
  else
  {
   echo $totalShip;
  } 

?>