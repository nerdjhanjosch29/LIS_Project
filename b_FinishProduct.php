<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));



if(isset($data))
{
$FinishProductID = $data->FinishProductID;
$FinishProductCode = $data->FinishProductCode;
$FinishProduct = $data->FinishProduct;
$KiloPerBag = $data->KiloPerBag;
$Quantity = $data->Quantity;
$Weight = $data->Weight;
$UserID = $data->UserID;
 $sql = "EXEC [dbo].[FinishProducts]
 @FinishProductID = ?,
 @FinishProductCode = ?,
 @FinishProduct = ?,
 @KiloPerBag = ?,
 @Quantity = ?,
 @Weight = ?,
 @UserID = ?";
 $params = array($FinishProductID, $FinishProductCode, $FinishProduct, $KiloPerBag, $Quantity,$Weight, $UserID);
 $stmt = sqlsrv_query($conn, $sql, $params);
 $result = "";
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{ 
  echo $result = $row['result'];
}

  if($result == 1)
  {
    $sql = "EXEC [dbo].[AddFinishProduct]";
    $stmt = sqlsrv_query($conn, $sql);
  }
}

?>