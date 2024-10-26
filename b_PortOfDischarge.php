<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
if(isset($data))
{
    $PortOfDischargeID=$data->PortOfDischargeID;
    $PortOfDischarge=$data->PortOfDischarge;
    $UserID=$data->UserID;
    $sql="EXEC [dbo].[PortOfDischarges]
    @PortOfDischargeID = ?,
    @PortOfDischarge = ?,
    @UserID = ?";
    $params = array($PortOfDischargeID, $PortOfDischarge, $UserID);
    $stmt = sqlsrv_query($conn, $sql, $params);
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
    {
    sqlsrv_commit($conn);
    echo $row['result'];
    }
}
?>