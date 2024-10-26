<?php
require 'connection.php'; // Assuming this file contains your database connection
$data = json_decode(file_get_contents('php://input')); // Assuming JSON data is sent to the script
if (sqlsrv_begin_transaction($conn) === false) {
    die(print_r(sqlsrv_errors(), true));
}
// Extracting data from JSON
$WeighingTransDetialID = $data->WeighingTransDetialID;
$WeighingTransactionID = $data->WeighingTransactionID;
$FinishProductID = $data->FinishProductID;
$RawMaterialID = $data->RawMaterialID;
$CustomerID = $data->CustomerID;
$NoofBags = $data->NoofBags;
$isTransaction = $data->isTransaction;
if (isset($data)) {
    $sql = "EXEC [dbo].[WeighingTransactionDetails] ?,?,?,?,?,?,?";
    $params = array($WeighingTransDetialID,$WeighingTransactionID,$FinishProductID,
    $RawMaterialID,$CustomerID,$NoofBags,$isTransaction
    );
    $stmt = sqlsrv_query($conn, $sql, $params);
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
      sqlsrv_commit($conn);
      echo $row['result'];
    }
}
?>
