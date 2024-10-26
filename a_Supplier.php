<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'connection.php';

// Start Transaction (Optional for SELECT queries)
if (sqlsrv_begin_transaction($conn) === false) {
    die("Transaction start failed: " . print_r(sqlsrv_errors(), true));
}

$sql = "SELECT 
           s.SupplierID,
           s.Supplier,
           s.Address,
           s.Origin,
           s.Indentor,
           s.IndentorAddress,
           s.ContactPerson,
           s.ContactNumber,
           s.UserID
        FROM Supplier s
        WHERE s.isDel = 'False'";

$stmt1 = sqlsrv_query($conn, $sql);

// Check if Query Execution was Successful
if ($stmt1 === false) {
    sqlsrv_rollback($conn);
    die("Query execution failed: " . print_r(sqlsrv_errors(), true));
}

$json = array();
while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    if ($row === false) {
        sqlsrv_rollback($conn);
        die("Data fetch failed: " . print_r(sqlsrv_errors(), true));
    }
    $json[] = $row;
}

// Commit Transaction (Optional for SELECT queries)
if (sqlsrv_commit($conn) === false) {
    sqlsrv_rollback($conn);
    die("Transaction commit failed: " . print_r(sqlsrv_errors(), true));
}

// Attempt JSON Encoding with Error Handling
$json_encoded = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);

if ($json_encoded === false) {
    die("JSON encoding failed: " . json_last_error_msg());
}

header('Content-Type: application/json; charset=utf-8');
echo $json_encoded;
?>
