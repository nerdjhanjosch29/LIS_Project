<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
  if(isset($data))
  {
    $MBL = "";
    $HBL = "";
    $UserID = "";
    if($data->HBL)
    {
      $HBL=$data->HBL;
    }
    if($data->MBL)
    {
      $MBL=$data->MBL;
    }
    if($data->UserID)
    {
      $UserID=$data->UserID;
    }
        $array=$data->PullOutDetail;
        $length=count($array); // count array
        $sql = "UPDATE PullOut SET deleted = 1 WHERE MBL = ?";
        $params3 = array($MBL);
        $stmt3 = sqlsrv_query($conn, $sql, $params3);
        sqlsrv_commit($conn);
        for($i=0; $i<=$length-1; $i++)
        { 
          $deleted = 0;
          $PullOutID = 0;
          $ContainerNumber = "";         
          $TruckingID = "";
          $DateOfDischarge=$array[$i]->DateOfDischarge;
          $Storage=$array[$i]->Storage;
          $Demurrage=$array[$i]->Demurrage;
          $Detention=$array[$i]->Detention;
          $DateIn=$array[$i]->DateIn;
          $DateOut=$array[$i]->DateOut;
          $ReturnDate=$array[$i]->ReturnDate;
          $PullOutDate=$array[$i]->PullOutDate;
          $Remarks = "";
            if($array[$i]->ContainerNumber)
            {
              $ContainerNumber=$array[$i]->ContainerNumber;
            }
            if($array[$i]->TruckingID)
            {
              $TruckingID=$array[$i]->TruckingID->TruckingID;
            }
            if($array[$i]->PullOutID)
            {
              $PullOutID=$array[$i]->PullOutID;
            }
            if($array[$i]->deleted)
            {
              $deleted=$array[$i]->deleted;
            }
            if($array[$i]->Remarks)
            {
              $Remarks=$array[$i]->Remarks;
            }
            if($PullOutID == 0)
            {
              $sql="INSERT INTO PullOut (MBL,HBL,DateOfDischarge,ContainerNumber,Storage, Demurrage, Detention,PullOutDate,DateIn,DateOut,ReturnDate,TruckingID,
              deleted,Remarks,UserID)
              VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
              $params = array($MBL,$HBL,$DateOfDischarge,$ContainerNumber,$Storage, $Demurrage, $Detention,
              $PullOutDate,$DateIn,$DateOut,$ReturnDate,$TruckingID,$deleted,$Remarks,$UserID);
              $stmt1 = sqlsrv_query($conn,$sql,$params);
              sqlsrv_commit($conn);
            }
            else
            {
              $sql = "UPDATE PullOut SET MBL = ?, HBL = ?, DateOfDischarge = ?, ContainerNumber = ?, Storage = ?, Demurrage =?, Detention = ?, 
              PullOutDate = ?, DateIn = ?, DateOut = ?, ReturnDate = ?, 
              TruckingID = ?, deleted = ?,Remarks = ?, UserID = ? WHERE PullOutID = ?";
              $params = array($MBL, $HBL, $DateOfDischarge, $ContainerNumber,$Storage, $Demurrage,$Detention, $PullOutDate, $DateIn, $DateOut, $ReturnDate,
               $TruckingID, $deleted,$Remarks, $UserID, $PullOutID);
              $stmt2 = sqlsrv_query($conn, $sql, $params);
              sqlsrv_commit($conn);           
            }   
          }
      if($PullOutID == 0)
      {
        echo 1;
      }
      else
      {
        echo 2;
      }
  }
?>