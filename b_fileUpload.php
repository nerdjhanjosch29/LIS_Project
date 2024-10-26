<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 

// $TableID = 0;
// $ImageCategory = "";
// $ImageUrl = "";
// $TableName = "";
// $deleted = 0;
// if($data->TableID)
// {
//   $TableID =$data->TableID;
// }
// if($data->ImageCategory)
// {
//   $ImageCategory =$data->ImageCategory;
// }
// if($data->TableName)
// {
//   $TableName =$data->TableName;
// }
if (isset($_FILES['file'])) {

$array=$data->fileDetails;
$length=count($_FILES['file']); // count array

for($i=0; $i<=$length-1; $i++)
{ 

        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];
        // print_r($file);
        // var_dump($file);
        $fileExt = explode('.', $fileName);    
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg', 'jpeg', 'png');   
        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 5000000) {
                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                    $BeforeImage = 'uploads/' . $fileNameNew;
                    if (move_uploaded_file($fileTmpName, $BeforeImage)) {
                        // $sql ="INSERT INTO ImageTable (TableID,ImageCategory,ImageUrl,TableName,deleted)VALUES(?,?,?,?,?)";
                        // $params = array($TableID, $Imagecategory,$BeforeImage,$TableName,$deleted);
                         $sql ="INSERT INTO FileUploads (Image)VALUES(?)";
                          // $params = array($TableID, $Imagecategory,$BeforeImage,$TableName,$deleted);
                        $stmt = sqlsrv_query($conn,$sql,$params);
                        sqlsrv_commit($conn);
                        var_dump($stmt);
                        echo 1;
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
      var_dump($length);

    }
   

?>
