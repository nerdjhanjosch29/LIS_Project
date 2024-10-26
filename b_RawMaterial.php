<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));

if(isset($data))
{
  
$RawMaterialID= 0;
$RawMaterial = "";
$Category="";
$PackagingID = 0;
$MinimumQuantity = 0; 
$MinimumWeight = 0; 
$UserID = "";

if(isset($data->RawMaterialID))
{
  $RawMaterialID= $data->RawMaterialID;
}
if(isset($data->RawMaterial))
{
  $RawMaterial= $data->RawMaterial;
}
if(isset($data->Category))
{
  $Category= $data->Category;
}
// if(isset($data->Quantity))
// {
//   $Quantity= $data->Quantity;
// }
// if(isset($data->Weight))
// {
//   $Weight= $data->Weight;
// }
if(isset($data->MinimumQuantity))
{
  $MinimumQuantity= $data->MinimumQuantity;
}
if(isset($data->MinimumWeight))
{
  $MinimumWeight= $data->MinimumWeight;
}
if(isset($data->UserID))
{
  $UserID= $data->UserID;
}
$sql = "EXEC [dbo].[RawMaterials]
        @RawMaterialID = ?,
        @RawMaterial = ?,
        @Category = ?,
        @MinimumQuantity = ?,
        @MinimumWeight = ?,
        @UserID = ?";
$params = array($RawMaterialID,$RawMaterial,$Category,$MinimumQuantity,$MinimumWeight,$UserID);
$stmt = sqlsrv_query($conn, $sql, $params);
$result = "";
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
  echo $result = $row['result'];
}
if($result == 1)
{
  $sql = "EXEC [dbo].[AddRawMaterial]";
  $stmt = sqlsrv_query($conn, $sql);
}

}
?>