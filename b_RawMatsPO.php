<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
  if(isset($data))
  { 
    $PurchaseOrderID = 0;
    $PONo= "";
    $PODate= "";
    $DeliveryDate = "";
    $Terms = "";
    $PRNumber = "";
    $SupplierID = 0;
    $SupplierAddress = "";
    $TotalQuantity = 0;
    $TotalAmount = 0;
    $deleted = 0;
    $UserID = "";
    if($data->PurchaseOrderID)
    {
      $PurchaseOrderID =$data->PurchaseOrderID;
    }
    if($data->PONo)
    {
      $PONo=$data->PONo;
    }
    if($data->PODate)
    {
      $PODate=$data->PODate;
    }
    if($data->DeliveryDate)
    {
      $DeliveryDate=$data->DeliveryDate;
    }
    if($data->Terms)
    {
      $Terms=$data->Terms;
    }
    if($data->PRNumber)
    {
      $PRNumber=$data->PRNumber;
    }
    if($data->SupplierID)
    {
      $SupplierID=$data->SupplierID;
    }
    if($data->SupplierAddress)
    {
      $SupplierAddress=$data->SupplierAddress;
    }
    if($data->TotalQuantity)
    {
      $TotalQuantity=$data->TotalQuantity;
    }

    if($data->TotalAmount)
    {
      $TotalAmount=$data->TotalAmount;
    }
    if($data->UserID)
    {
      $UserID=$data->UserID;
    }
    if($PurchaseOrderID == 0)
    {
        $PurchaseOrderID = "";
        $sql="INSERT INTO PurchaseOrder (PONo,PODate,DeliveryDate,Terms,PRNumber,SupplierID,SupplierAddress,TotalQuantity,TotalAmount,deleted,UserID)
        VALUES(?,?,?,?,?,?,?,?,?,?,?) SELECT SCOPE_IDENTITY()";
        $params = array($PONo,$PODate,$DeliveryDate,$Terms,$PRNumber,$SupplierID,$SupplierAddress,$TotalQuantity,$TotalAmount,$deleted, $UserID);     
        $stmt1 = sqlsrv_query($conn, $sql, $params);
        sqlsrv_next_result($stmt1); 
        sqlsrv_fetch($stmt1); 
        $PurchaseOrderID = sqlsrv_get_field($stmt1, 0); 
        sqlsrv_commit($conn);
            echo 1;       
    }
    else
    {
        $sql = "UPDATE PurchaseOrder SET PONo = ?, PODate = ?,DeliveryDate = ?,Terms =?,PRNumber = ?,SupplierID = ?,SupplierAddress =?,TotalQuantity = ?,TotalAmount = ?, deleted = ?,UserID = ?
        WHERE PurchaseOrderID = ?";
        $params3 = array($PONo,$PODate,$DeliveryDate,$Terms,$PRNumber,$SupplierID,$SupplierAddress,$TotalQuantity,$TotalAmount,$deleted,$UserID, $PurchaseOrderID);
        $stmt3 = sqlsrv_query($conn, $sql, $params3);
        sqlsrv_commit($conn);
        // var_dump($stmt3);
        $sql = "UPDATE PurchaseOrderDetail SET deleted = 1 WHERE PurchaseOrderID = ?";
        $params4 = array($PurchaseOrderID);
        $stmt4 = sqlsrv_query($conn, $sql, $params4);
        sqlsrv_commit($conn);
            echo 2;
    }
        $array=$data->OrderDetail;
        $length=count($array); // count array
        for($i=0; $i<=$length-1; $i++)
        { 
            $PurchaseOrderDetailID = 0;
            $MaterialCode = "";
            $RawMaterialID= "";
            $Quantity = 0;
            $UnitPrice = 0;
            $deleted = 0;
            $Amount = 0;
            if($array[$i]->PurchaseOrderDetailID )
            {
              $PurchaseOrderDetailID =$array[$i]->PurchaseOrderDetailID ;
            }
            if($array[$i]->MaterialCode)
            {
              $MaterialCode=$array[$i]->MaterialCode;
            }
            if($array[$i]->RawMaterialID)
            {
              $RawMaterialID=$array[$i]->RawMaterialID;
            }
            if($array[$i]->Quantity)
            {
              $Quantity=$array[$i]->Quantity;
            }
            if($array[$i]->UnitPrice)
            {
              $UnitPrice=$array[$i]->UnitPrice;
            }
            if($array[$i]->Amount)
            {
              $Amount=$array[$i]->Amount;
            }
            if($PurchaseOrderDetailID == 0)
            {
              $sql="INSERT INTO PurchaseOrderDetail(PurchaseOrderID,MaterialCode,RawMaterialID,Quantity,UnitPrice,Amount,deleted)
              VALUES(?,?,?,?,?,?,?)";
              $params = array($PurchaseOrderID,  
              $MaterialCode,$RawMaterialID,$Quantity,$UnitPrice,$Amount,$deleted);
              $stmt2 = sqlsrv_query($conn,$sql,$params);
              sqlsrv_commit($conn);
            }
            else
            {
              $sql = "UPDATE PurchaseOrderDetail SET PurchaseOrderID =?,MaterialCode = ?,RawMaterialID = ?,Quantity = ?,
              UnitPrice = ?,Amount = ?,deleted = ? 
              WHERE PurchaseOrderDetailID = ?";
              $params = array($PurchaseOrderID,  
              $MaterialCode,$RawMaterialID,$Quantity,$UnitPrice,$Amount,$deleted,$PurchaseOrderDetailID);
              $stmt1 = sqlsrv_query($conn, $sql, $params);
              sqlsrv_commit($conn);
                  // var_dump($deleted);
            }   
            if($stmt1)
            {
                 sqlsrv_commit($conn);
            } 
          }
  }
?>