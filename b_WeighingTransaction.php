<?php
require 'connection.php'; //this file contains your database connection
$data = json_decode(file_get_contents('php://input')); 
if (sqlsrv_begin_transaction($conn) === false) {
    die(print_r(sqlsrv_errors(), true));
}
if (isset($data)) 
{
    $WeighingTransactionID = 0;
    $TruckID = 0;
    $DriverID = 0;
    $CheckerID =0;
    $WeigherID = 0;
    $SupplierID = 0;
    $CustomerID = 0;
    $DrNumber = 0; 
    $GrossWeight = 0;
    $TareWeight = 0;
    $NetWeight = 0;
    $rmGrossWeight = 0;
    $rmTareWeight = 0;
    $rmNetWeight = 0;
    $LossOverWeight = 0;
    $ShippingID = 0;
    $DateTimeArrived = "";
    $WeighInDate = "";
    $WeighOutDate ="";
    $Others = "";
    $NoOfBags = 0;
    $Remarks = "";
    $deleted = 0;
    $WeighingTransDetailID = 0;
        if($data->TruckID)
        {
          $TruckID = $data->TruckID;
        }
        if($data->DriverID)
        {
          $DriverID = $data->DriverID;
        }
        if($data->CheckerID)
        {
          $CheckerID = $data->CheckerID;
        }
        if($data->WeigherID)
        {
          $WeigherID = $data->WeigherID;
        }
        if($data->SupplierID)
        {
          $SupplierID = $data->SupplierID;
        }
        if($data->DrNumber)
        {
          $DrNumber = $data->DrNumber;
        }
        if($data->GrossWeight)
        {
          $GrossWeight = $data->GrossWeight;
        }
        if($data->TareWeight)
        {
          $TareWeight = $data->TareWeight;
        }
        if($data->NetWeight)
        {
          $NetWeight = $data->NetWeight;
        }
        if($data->rmGrossWeight)
        {
          $rmGrossWeight = $data->rmGrossWeight;
        }
        if($data->rmTareWeight)
        {
          $rmTareWeight = $data->rmTareWeight;
        }
        if($data->rmNetWeight)
        {
          $rmNetWeight = $data->rmNetWeight;
        }
        if($data->LossOverWeight)
        {
          $LossOverWeight = $data->LossOverWeight;
        }
        if($data->ShippingID)
        {
          $ShippingID = $data->ShippingID;
        }
        if($data->WeighInDate)
        {
          $WeighInDate = $data->WeighInDate;
        }
        if($data->WeighOutDate)
        {
          $WeighOutDate = $data->WeighOutDate;
        }       
        if($data->isTransaction)
        {
          $isTransaction = $data->isTransaction->isTransactionID;
        }
        if($data->Remarks)
        {
          $Remarks = $data->Remarks;
        }
        if($data->WeighingTransactionID)
        {
          $WeighingTransactionID = $data->WeighingTransactionID;
        }
        if($data->WeighingTransDetailID)
        {
          $WeighingTransDetailID = $data->WeighingTransDetailID;
        }
        if($WeighingTransactionID == 0)
        {
            if($isTransaction == 1)
            {
              $WeighingTransactionID = "";
              $sql ="INSERT INTO WeighingTransaction(TruckID,DriverID,CheckerID,WeigherID,SupplierID,
              DrNumber,GrossWeight,TareWeight,NetWeight,rmGrossWeight,rmTareWeight,rmNetWeight,LossOverWeight,ShippingID,
              DateTimeArrived,WeighInDate,WeighOutDate,NoOfBags,isTransaction,Remarks,deleted)
              VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) SELECT SCOPE_IDENTITY()";
              $params = array($TruckID,$DriverID,$CheckerID,$WeigherID,$SupplierID,$DrNumber,$GrossWeight,$TareWeight,$NetWeight,$rmGrossWeight,$rmTareWeight,$rmNetWeight,
              $LossOverWeight, $ShippingID, $DateTimeArrived, $WeighInDate,$WeighOutDate,$NoOfBags,$isTransaction,$Remarks,$deleted);
              $stmt = sqlsrv_query($conn,$sql,$params);
              sqlsrv_next_result($stmt); 
              sqlsrv_fetch($stmt); 
              $WeighingTransactionID = sqlsrv_get_field($stmt, 0); 
              sqlsrv_commit($conn);
              echo 1;
            }
            else if($isTransaction == 2)
            {
              if($data->CustomerID)
              {
                $CustomerID = $data->CustomerID;
              }
              if($data->NoOfBags)
              {
                $NoOfBags = $data->NoOfBags;
              }
              $WeighingTransactionID = "";
              $sql ="INSERT INTO WeighingTransaction(TruckID,DriverID,CheckerID,WeigherID,CustomerID,
              DrNumber,GrossWeight,TareWeight,NetWeight,LossOverWeight,ShippingID,
              DateTimeArrived,WeighInDate,WeighOutDate,NoOfBags,isTransaction,Remarks,deleted)
              VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) SELECT SCOPE_IDENTITY()";
              $params = array($TruckID,$DriverID,$CheckerID,$WeigherID,$CustomerID,$DrNumber,$GrossWeight,$TareWeight,$NetWeight,
              $LossOverWeight, $ShippingID, $DateTimeArrived, $WeighInDate,$WeighOutDate,$NoOfBags,$isTransaction,$Remarks,$deleted);
              $stmt = sqlsrv_query($conn,$sql,$params);
              sqlsrv_next_result($stmt); 
              sqlsrv_fetch($stmt); 
              $WeighingTransactionID = sqlsrv_get_field($stmt, 0); 
              sqlsrv_commit($conn);
              echo 1;
            }
            // else if($isTransaction == 3)
            // {
            //   $sql ="INSERT INTO WeighingTransaction(TruckID,DriverID,CheckerID,WeigherID,SupplierID,CustomerID,DrNumber,GrossWeight,TareWeight,rmGrossWeight,
            //   rmTareWeight,rmNetWeigth,LossOverWeight,ShippingID,
            //   DateTimeArrived,WeighInDate,WeighOutDate,NoOfBags,isTransaction,Remarks)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            //   $params = array($TruckID,$DriverID,$CheckerID,$WeigherID,$SupplierID,$CustomerID,$DrNumber,$GrossWeight,$TareWeight,$NetWeight,$rmGrossWeight,
            //   $rmTareWeight,$rmNetWeight,$LossOverWeight, $ShippingID, $DateTimeArrived, $WeighInDate,$WeighOutDate,$Others,$NoOfBags,$isTransaction,$Remarks,$deleted);
            //   $stmt = sqlsrv_query($conn, $sql, $params);
            //   sqlsrv_commit($conn);
            // }
            // else if($isTransaction == 4)
            // {
            //   $sql ="INSERT INTO WeighingTransaction(TruckID,DriverID,CheckerID,WeigherID,SupplierID,CustomerID,DrNumber,GrossWeight,TareWeight,rmGrossWeight,
            //   rmTareWeight,rmNetWeigth,LossOverWeight,ShippingID,
            //   DateTimeArrived,WeighInDate,WeighOutDate,NoOfBags,isTransaction,Remarks,deleted)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            //   $params = array($TruckID,$DriverID,$CheckerID,$WeigherID,$SupplierID,$CustomerID,$DrNumber,$GrossWeight,$TareWeight,$NetWeight,$rmGrossWeight,
            //   $rmTareWeight,$rmNetWeight,$LossOverWeight, $ShippingID, $DateTimeArrived, $WeighInDate,$WeighOutDate,$Others,$NoOfBags,$isTransaction,$Remarks, $deleted);
            //   $stmt = sqlsrv_query($conn, $sql, $params);
            //   sqlsrv_commit($conn);
            // }
            // else if($isTransaction == 5)
            // {
            //   $sql = "INSERT INTO WeighingTransaction(TruckID,DriverID,CheckerID,WeigherID,SupplierID,CustomerID,DrNumber,GrossWeight,TareWeight,rmGrossWeight,
            //   rmTareWeight,rmNetWeigth,LossOverWeight,ShippingID,
            //   DateTimeArrived,WeighInDate,WeighOutDate,NoOfBags,isTransaction,Remarks,deleted)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            //   $params = array($TruckID,$DriverID,$CheckerID,$WeigherID,$SupplierID,$CustomerID,$DrNumber,$GrossWeight,$TareWeight,$NetWeight,$rmGrossWeight,
            //   $rmTareWeight,$rmNetWeight,$LossOverWeight, $ShippingID, $DateTimeArrived, $WeighInDate,$WeighOutDate,$Others,$NoOfBags,$isTransaction,$Remarks,$deleted);
            //   $stmt = sqlsrv_query($conn, $sql, $params);
            //   sqlsrv_commit($conn);
            // }
        //     else
        //     {
        //       echo -1;
        //     }
        // }
      }
      else
      {

          if($isTransaction == 1)
          {

            $sql = "UPDATE WeighingTransaction SET TruckID = ?, DriverID = ?, CheckerID = ?, WeigherID = ?, SupplierID = ?,
            DrNumber = ?, GrossWeight = ?, TareWeight = ?,NetWeight = ?,rmGrossWeight =?, rmTareWeight = ?, rmNetWeight = ?, LossOverWeight =?,
            ShippingID =?, DateTimeArrived =?, WeighInDate = ?, WeighOutDate = ?,  isTransaction = ?, Remarks =?, deleted =? 
            WHERE WeighingTransactionID =?";
            $params = array($TruckID,$DriverID,$CheckerID,$WeigherID,$SupplierID,$DrNumber,$GrossWeight,$TareWeight,$NetWeight,$rmGrossWeight,
            $rmTareWeight,$rmNetWeight,$LossOverWeight, $ShippingID, $DateTimeArrived, $WeighInDate,$WeighOutDate,$isTransaction,$Remarks,$deleted, $WeighingTransactionID);
            $stmt = sqlsrv_query($conn, $sql, $params);
            sqlsrv_commit($conn);
            // var_dump($params);
            if($stmt)
            {
              echo 2;
            }
            $sql = "UPDATE WeighingTransactionDetail SET deleted = 1 WHERE WeighingTransactionID = ?";
            $params = array($WeighingTransactionID);
            $stmt = sqlsrv_query($conn, $sql, $params);
          }
          else if($isTransaction == 2)
          {
            $sql = "UPDATE WeighingTransaction SET TruckID = ?, DriverID = ?, CheckerID = ?, WeigherID = ?, SupplierID = ?, CustomerID = ?,
            DrNumber = ?, GrossWeight = ?, TareWeight = ?,NetWeight = ?, LossOverWight =?,
            ShippingID =?, DateTimeArrived =?, WeighInDate = ?, WeighOutDate = ?, NoOfBags = ?, isTransaction = ?, Remarks =?, deleted =? 
            WHERE WeighingTransactionID =?";
            $params = array($TruckID,$DriverID,$CheckerID,$WeigherID,$SupplierID,$CustomerID,$DrNumber,$GrossWeight,$TareWeight,$NetWeight,
            $LossOverWeight, $ShippingID, $DateTimeArrived, $WeighInDate,$WeighOutDate,
            $Others,$NoOfBags,$isTransaction,$Remarks,$deleted, $WeighingTransactionID);
            $stmt = sqlsrv_query($conn, $sql, $params);
            sqlsrv_commit($conn);
            echo 2;
            $sql = "UPDATE WeighingTransactionDetail SET deleted = 1 WHERE WeighingTransactionID = ?";
            $params = array($WeighingTransactionID);
            $stmt = sqlsrv_query($conn, $sql, $params);
          }

        }
          if($WeighingTransDetailID == 0)
          {
            if($isTransaction == 1)
            {
                $RawMaterialID = 0;
                if($data->RawMaterialID)
                {
                  $RawMaterialID = $data->RawMaterialID;
                }
                $sqls = "INSERT INTO WeighingTransactionDetail(WeighingTransactionID, RawMaterialID, SupplierID, NoOfBags, isTransaction,deleted)
                VALUES(?,?,?,?,?,?)";
                $params1 = array($WeighingTransactionID, $RawMaterialID,$SupplierID, $NoOfBags, $isTransaction, $deleted);
                $stmt1 = sqlsrv_query($conn, $sqls, $params1);
                sqlsrv_commit($conn);
                // var_dump($stmt1);
            }
            else if($isTransaction == 2)
            {
              if($data->CustomerID)
              {
                $CustomerID = $data->CustomerID;
              }
                $arrays = $data->WeighingDetail;
                $lengths = count($arrays); // count array
                for($i=0; $i<=$lengths-1; $i++)
                { 
                  $NoOfBags = 0;
                  $CustomerID =0;
                  $FinishProductiD = 0;
                  if($data->NoOfBags)
                  {
                    $NoOfBags = $arrays[$i]->NoOfBags;
                  }

                  if($arrays[$i]->FinishProductID)
                  {
                    $FinishProductID = $arrays[$i]->FinishProductID->FinishProductID;
                  }
                    $sqls = "INSERT INTO WeighingTransactionDetail(WeighingTransactionID,FinishProductID,CustomerID,NoOfBags,isTransaction,deleted)
                    VALUES(?,?,?,?,?,?)";
                    $params1 = array($WeighingTransactionID,$FinishProductID,$CustomerID,$NoOfBags,$isTransaction,$deleted);
                    $stmt1 = sqlsrv_query($conn,$sqls,$params1);
                    sqlsrv_commit($conn);
                    // var_dump($stmt1);
                }
            }
          }


          else 
          {

            if($isTransaction == 1)
            {
              $RawMaterialID = 0;

              if($data->RawMaterialID)
              {
                $RawMaterialID = $data->RawMaterialID;
              }
              $sqls = "UPDATE WeighingTransactionDetail SET WeighingTransactionID = ?, RawMaterialID = ?, SupplierID = ?, NoOfBags = ?, deleted = ? WHERE WeighingTransactionID =?";
              $params1 = array($WeighingTransactionID, $RawMaterialID, $SupplierID, $NoOfBags, $deleted, $WeighingTransactionID);
              $stmt1 = sqlsrv_query($conn, $sqls, $params1);
              sqlsrv_commit($conn);
              // var_dump($stmt1);
            }
            else if($isTransaction == 2)
            {
              if($data->CustomerID)
              {
                $CustomerID = $data->CustomerID;
              }
                $arrays = $data->WeighingDetail;
                $lengths = count($arrays); // count array
                for($i=0; $i<=$lengths-1; $i++)
                { 
                  $NoOfBags = 0;
                  $CustomerID =0;
                  $FinishProductiD = 0;
                  if($data->NoOfBags)
                  {
                    $NoOfBags = $arrays[$i]->NoOfBags;
                  }
                  if($arrays[$i]->FinishProductID)
                  {
                    $FinishProductID = $arrays[$i]->FinishProductID->FinishProductID;
                  }
                    $sqls = "UPDATE WeighingTransactionDetail SET WeighingTransactionID = ?, FinishProductID = ?,  CustomerID = ?, NoOfBags = ?, deleted = ? WHERE WeighingTransactionID =?";
                    $params1 = array($WeighingTransactionID,$FinishProductID,$CustomerID,$NoOfBags,$isTransaction,$deleted,$WeighingTransactionID);
                    $stmt1 = sqlsrv_query($conn,$sqls,$params1);
                    sqlsrv_commit($conn);
                    // var_dump($stmt1);
                }
            }
          }
      }
// ?>
