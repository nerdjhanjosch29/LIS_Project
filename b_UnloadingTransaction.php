<?php 
require 'connection.php';
// $data = json_decode(file_get_contents('php://input'));
$data = json_decode($_POST['data']);
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
// var_dump($data);
if(isset($data))
{
  $data = $data->data;
  $isTransactionID = 0;
  $UnloadingTransactionID = 0;
  $PONo = 0;
  $PO = 0;
  $BL = 0;
  $Lot = 0;
  $BeforeImage = "";
  $BLNumber = "";
  $ContainerNumber = "";
  $DateUnload = "";
  $DateTimeUnload ="";
  $BeforeImage ="";
  $DrNumber = "";
  $CheckerID = 0;
  $TruckID = 0;
  $SupplierID = 0;
  $RawMaterialID = 0;
  $WarehouseLocationID = 0;
  $WarehouseID = 0;
  $WarehousePartitionID = 0;
  $Quantity = 0;
  $Weight = 0;
  $Status = 0;
  $UserID = "";
  $ImageCategory = "BeforeUnloading";
  $ImageCategory1 = "DuringUnloading";
  $ImageCategory1 = "AfterUnloading";
  $TableName = "Unloading";
  // $DuringImage = "";
  $deleted = 0;
  // $ImageUrl = "";
  $ImageID = 0;
if($data->isTransactionID)
{
    $isTransactionID = $data->isTransactionID;
}
if($data->UnloadingTransactionID)
{
  $UnloadingTransactionID = $data->UnloadingTransactionID;
}
if($data->PO)
{
  $PO =$data->PO;
}
if($data->BL)
{
  $BL=$data->BL;
}
if($data->BLNumber)
{
  $BLNumber=$data->BLNumber;
}
if($data->ContainerNumber)
{
  $ContainerNumber =$data->ContainerNumber;
}
if($data->DateUnload)
{
  $DateTimeUnload=$data->DateTimeUnload;
}
if($data->DateUnload)
{
  $DateUnload =$data->DateUnload;
}
$DateTimeUnload= date_create($DateTimeUnload);
$time = date_format($DateTimeUnload, "H:i");
// Check if the time is between 12:00 AM and 6:00 AM
if ($time < '06:00') {
  date_sub($DateTimeUnload, date_interval_create_from_date_string('1 day'));
  $DateUnload = date_format($DateTimeUnload, "Y-m-d");
  $DateTimeUnload = date_add($DateTimeUnload,date_interval_create_from_date_string("1 day")); 
}
else
{
  date_sub($DateTimeUnload, date_interval_create_from_date_string('1 day'));
  $DateTimeUnload = date_add($DateTimeUnload,date_interval_create_from_date_string("1 day"));
}
$DateTimeUnload = date_format($DateTimeUnload, "Y-m-d H:i");
if($data->DrNumber)
{
  $DrNumber = $data->DrNumber;
}
if($data->CheckerID)
{
  $CheckerID = $data->CheckerID;
}
if($data->TruckID)
{
  $TruckID =$data->TruckID;
}
if($data->SupplierID)
{
  $SupplierID =$data->SupplierID;
}
if($data->RawMaterialID)
{
  $RawMaterialID = $data->RawMaterialID;
}
if($data->WarehouseLocationID)
{
  $WarehouseLocationID = $data->WarehouseLocationID;
}
if($data->WarehouseID)
{
$WarehouseID = $data->WarehouseID;
}
if($data->WarehousePartitionID)
{
  $WarehousePartitionID = $data->WarehousePartitionID;
}
if($data->Quantity)
{
  $Quantity = $data->Quantity;
}
if($data->Weight)
{
  $Weight = $data->Weight;
}
if($data->Status)
{
  $Status = $data->Status;
}
if($data->UserID)
{
  $UserID = $data->UserID;
}
if($UnloadingTransactionID == 0)
{
  $newID = 0;
  $sql="INSERT INTO UnloadingTransaction (isTransactionID,PO,BL,BLNumber,ContainerNumber,DateTimeUnload, DateUnload, DrNumber,CheckerID,
   TruckID,SupplierID,RawMaterialID, 
	WarehouseLocationID,WarehouseID,WarehousePartitionID,Quantity, Weight,Status,UserID)
  VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) SELECT SCOPE_IDENTITY()";
  $params = array($isTransactionID,$PO,$BL,$BLNumber,$ContainerNumber,$DateTimeUnload,$DateUnload, $DrNumber,$CheckerID, $TruckID,
	$SupplierID,$RawMaterialID,$WarehouseLocationID, $WarehouseID, $WarehousePartitionID,$Quantity,$Weight,$Status, $UserID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  // var_dump($stmt);
  sqlsrv_next_result($stmt); 
  sqlsrv_fetch($stmt); 
  $newID = sqlsrv_get_field($stmt, 0);   
  if($stmt)
  {
  echo 1;
  sqlsrv_commit($conn);
  }
    if(isset($_FILES['files']))
    {
      $files = $_FILES['files']['name'];
      $length = count($files); // count the number of files uploaded
      for ($i = 0; $i<$length; $i++) { 
      $fileName = $_FILES['files']['name'][$i];
      $fileTmpName = $_FILES['files']['tmp_name'][$i];
      $fileSize = $_FILES['files']['size'][$i];
      $fileError = $_FILES['files']['error'][$i];
      $fileType = $_FILES['files']['type'][$i];
      // var_dump($length);
      $fileExt = explode('.', $fileName);    
      $fileActualExt = strtolower(end($fileExt));
      $allowed = array('jpg', 'jpeg', 'png');   
      if (in_array($fileActualExt, $allowed)) {
          if ($fileError === 0) {
              if ($fileSize < 5000000) {
                  $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                  $BeforeImage = 'uploads/' . $fileNameNew;
                  if (move_uploaded_file($fileTmpName, $BeforeImage)) {                 
                      $sql = "INSERT INTO ImageTable (TableID,ImageCategory,ImageUrl,TableName,deleted) VALUES (?,?,?,?,?)";
                      $params = array($newID,$ImageCategory,$BeforeImage,$TableName,$deleted);
                      $stmt = sqlsrv_query($conn, $sql, $params);
                      sqlsrv_commit($conn);
                  } else {
                      echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
                  }
              } else {
                  echo json_encode(['status' => 'error', 'message' => 'File size exceeds limit']);
              }
          } else {
              echo json_encode(['status' => 'error', 'message' => 'Error uploading file']);
          }
      } else {
          echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
      }
    }
  }
  // /During Imagee
  //   if(isset($_FILES['filess']))
  //   {
  //     $filess = $_FILES['filess']['name'];
  //     $length = count($filess); // count the number of files uploaded
  //     for ($i = 0; $i<$length; $i++) { 

  //     $fileName = $_FILES['filess']['name'][$i];
  //     $fileTmpName = $_FILES['filess']['tmp_name'][$i];
  //     $fileSize = $_FILES['filess']['size'][$i];
  //     $fileError = $_FILES['filess']['error'][$i];
  //     $fileType = $_FILES['filess']['type'][$i];
  //     // var_dump($length);

  //     $fileExt = explode('.', $fileName);    
  //     $fileActualExt = strtolower(end($fileExt));
  //     $allowed = array('jpg', 'jpeg', 'png');   

  //     if (in_array($fileActualExt, $allowed)) {
  //         if ($fileError === 0) {
  //             if ($fileSize < 5000000) {
  //                 $fileNameNew = uniqid('', true) . "." . $fileActualExt;
  //                 $BeforeImage = 'uploads/' . $fileNameNew;

  //                 if (move_uploaded_file($fileTmpName, $DuringImage)) {                 
  //                     $sql = "INSERT INTO ImageTable (TableID,ImageCategory,ImageUrl,TableName,deleted) VALUES (?,?,?,?,?)";
  //                     $params = array($newID,$ImageCategory1,$DuringImage,$TableName,$deleted);
            
  //                     $stmt = sqlsrv_query($conn, $sql, $params);
  //                     sqlsrv_commit($conn);
  //                 } else {
  //                     echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
  //                 }
  //             } else {
  //                 echo json_encode(['status' => 'error', 'message' => 'File size exceeds limit']);
  //             }
  //         } else {
  //             echo json_encode(['status' => 'error', 'message' => 'Error uploading file']);
  //         }
  //     } else {
  //         echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
  //     }
  //   }

  // }
  // AfterImage
  // if(isset($_FILES['filesss']))
  //   {
  //     $filesss = $_FILES['filesss']['name'];
  //     $length = count($filesss); // count the number of files uploaded
  //     for ($i = 0; $i<$length; $i++) { 

  //     $fileName = $_FILES['filesss']['name'][$i];
  //     $fileTmpName = $_FILES['filesss']['tmp_name'][$i];
  //     $fileSize = $_FILES['filesss']['size'][$i];
  //     $fileError = $_FILES['filesss']['error'][$i];
  //     $fileType = $_FILES['filesss']['type'][$i];
  //     // var_dump($length);

  //     $fileExt = explode('.', $fileName);    
  //     $fileActualExt = strtolower(end($fileExt));
  //     $allowed = array('jpg', 'jpeg', 'png');   

  //     if (in_array($fileActualExt, $allowed)) {
  //         if ($fileError === 0) {
  //             if ($fileSize < 5000000) {
  //                 $fileNameNew = uniqid('', true) . "." . $fileActualExt;
  //                 $BeforeImage = 'uploads/' . $fileNameNew;

  //                 if (move_uploaded_file($fileTmpName, $AfterImage)) {                 
  //                     $sql = "INSERT INTO ImageTable (TableID,ImageCategory,ImageUrl,TableName,deleted) VALUES (?,?,?,?,?)";
  //                     $params = array($newID,$ImageCategory2,$AfterImage,$TableName,$deleted);
            
  //                     $stmt = sqlsrv_query($conn, $sql, $params);
  //                     sqlsrv_commit($conn);
  //                 } else {
  //                     echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
  //                 }
  //             } else {
  //                 echo json_encode(['status' => 'error', 'message' => 'File size exceeds limit']);
  //             }
  //         } else {
  //             echo json_encode(['status' => 'error', 'message' => 'Error uploading file']);
  //         }
  //     } else {
  //         echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
  //     }
  //   }
    
  // }
}
else
{
  $sql="UPDATE UnloadingTransaction SET isTransactionID = ?,PONo = ?,PO = ?, BL = ?, BLNumber = ?,Lot = ?,ContainerNumber = ?, 
  DateTimeUnload = ?, DateUnload = ?,DrNumber = ?, CheckerID = ?,TruckID = ?, SupplierID = ?,RawMaterialID = ?, 
	WarehouseLocationID = ?,WarehouseID = ?,WarehousePartitionID = ?, Quantity =?, Weight = ?, Status = ?,UserID = ? 
	WHERE UnloadingTransactionID = ?";
  $params = array($isTransactionID,$PONo,$PO,$BL,$BLNumber,$Lot,$ContainerNumber,$DateTimeUnload,$DateUnload,$DrNumber, 
  $CheckerID, $TruckID,$SupplierID,$RawMaterialID,$WarehouseLocationID,$WarehouseID,$WarehousePartitionID,
  $Quantity,$Weight,$Status,$UserID,$UnloadingTransactionID);
  $stmt = sqlsrv_query($conn, $sql, $params);
  sqlsrv_commit($conn);
  if($stmt)
  {
   echo 2;
  }
    // if(isset($_FILES['files']))
    // {
       
    //   $files = $_FILES['files']['name'];
    //   $length = count($files); // count the number of files uploaded
    //   for ($i = 0; $i<$length; $i++) { 
    //     $ImageID = 0;
    //     $OldImage = "";

    //     if($files[$i]->ImageID)
    //     {
    //       $ImageID = $files[$i]->ImageID;
    //     }
    //     if($files[$i]->old_image)
    //     {
    //       $old_image = $files[$i]->old_image;
    //     }
    //   $fileName = $_FILES['files']['name'][$i];
    //   $fileTmpName = $_FILES['files']['tmp_name'][$i];
    //   $fileSize = $_FILES['files']['size'][$i];
    //   $fileError = $_FILES['files']['error'][$i];
    //   $fileType = $_FILES['files']['type'][$i];
    //   // var_dump($length);
    //   $fileExt = explode('.', $fileName);    
    //   $fileActualExt = strtolower(end($fileExt));
    //   $allowed = array('jpg', 'jpeg', 'png');
    //   if (in_array($fileActualExt, $allowed)) {
    //       if ($fileError === 0) {
    //           if ($fileSize < 500000000) {
    //               $fileNameNew = uniqid('', true) . "." . $fileActualExt;
    //               $BeforeImage = 'uploads/' . $fileNameNew;
    //               if (file_exists($BeforeImage = 'uploads/' . $fileNameNew)) {
    //               } else {
    //                   $sql = "UPDATE ImageTable SET TableID = ?, ImageCategory = ?,ImageUrl = ?,TableName = ? WHERE TableID = ? AND ImageID = ? AND ImageCategory =?";
    //                   $params = array($UnloadingTransactionID,$ImageCategory,$BeforeImage,$TableName, $UnloadingTransactionID,$ImageID, $ImageCategory);
            
    //                   $stmt = sqlsrv_query($conn, $sql, $params);
    //                   sqlsrv_commit($conn);
    //                   if($fileName = $_FILES['files']['name'][$i] != "")
    //                   {
    //                     move_uploaded_file($fileTmpName,$BeforeImage = 'uploads/' . $fileNameNew);
    //                     unlink("uploads/".$old_image);
    //                     echo 0;
    //                   }
    //               }
    //           } else {
    //               echo json_encode(['status' => 'error', 'message' => 'File size exceeds limit']);
    //           }
    //       } else {
    //           echo json_encode(['status' => 'error', 'message' => 'Error uploading file']);
    //       }
    //   } else {
    //       echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
    //   }
    // }
    // }
}
    if($Status == 3)
    {
      $sql="EXEC [dbo].[UnloadingInventory]
        @UnloadingTransactionID = ?,
        @BL = ?,
        @PO = ?,
        @ContainerNumber = ?,
        @DateTimeUnload = ?,
        @DateUnload = ?,
        @CheckerID = ?,
        @TruckID = ?,
        @SupplierID = ?,
        @RawMaterialID = ?,
        @WarehouseLocationID = ?,
        @WarehouseID = ?,
        @WarehousePartitionID = ?,
        @Quantity = ?,
        @Weight = ?,
        @Status = ?,
        @UserID = ?";
      $params = array($UnloadingTransactionID,$BL,$PO,$ContainerNumber,
      $DateTimeUnload,$DateUnload,$CheckerID,$TruckID,$SupplierID,$RawMaterialID,
      $WarehouseLocationID,$WarehouseID,$WarehousePartitionID,
      $Quantity,$Weight,$Status,$UserID);
      $stmt = sqlsrv_query($conn, $sql, $params);
    // var_dump($DateTimeUnload);
      sqlsrv_commit($conn);
      // var_dump($Status);
      // var_dump($stmt);
    }
}
?>