<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 

    $UserID = "";
  if(isset($_GET['UserID']))
  {
    $UserID = $_GET['UserID'];
  }
    // var_dump($UserID);
  if($UserID == "")
  {
    // No UserID Found
    echo 5;

  }
  else
  {
        $SelectOldPassword = "";
        $SelectPasswordQuery = "SELECT PWord FROM UserAccount WHERE UserID = ?";
        $UserIDParameter = array($UserID);
        $SelectPasswordExecute = sqlsrv_query($conn,$SelectPasswordQuery,$UserIDParameter);
        while($SelectPasswordRow = sqlsrv_fetch_array($SelectPasswordExecute, SQLSRV_FETCH_ASSOC))
        {
            $SelectOldPassword = $SelectPasswordRow['PWord'];
        }
        if(isset($data))
        {
            $InputOldPassword = $data->CurrentPassword;
            $InputNewPassword = $data->NewPassword;
            $ConfirmNewPassword = $data->ConfirmNewPassword;

               $InputOldPassword = md5($InputOldPassword);
               $InputNewPassword = md5($InputNewPassword);
               $ConfirmNewPassword = md5($ConfirmNewPassword);
            // Verify if current Password is same in Input OldPasword
            if($SelectOldPassword == $InputOldPassword)
            {
                //Check if Input New Password same in ConfirmPassword that you entered
                if($InputNewPassword == $ConfirmNewPassword)
                {
                    //ConfirmPassword Match the NewPassword
                    $UpdatePasswordQuery = "UPDATE UserAccount SET PWord = ? WHERE UserID = ?";
                    $UpdatePasswordParams = array($InputNewPassword,$UserID);
                    $UpdatePasswordExecute = sqlsrv_query($conn, $UpdatePasswordQuery,$UpdatePasswordParams);
                    if($UpdatePasswordExecute)
                    {
                        //Successfully Updated
                        echo 2;
                        sqlsrv_commit($conn);

                          $SystemLogID = 0;
                          $FunctionID = 2;
                          $TableName = "Change Password";

                          $sql = "EXEC [dbo].[SystemLogInsert]
                                  @SystemLogID = ?,
                                  @UserID = ?,
                                  @FunctionID = ?,
                                  @TableName = ?";
                          $params = array($SystemLogID, $UserID,$FunctionID, $TableName);
                          $stmt = sqlsrv_query($conn, $sql, $params);
                    }
                    else 
                    {
                        //Query not Successful
                        echo -1;
                    }
                }
                else
                {
                    //ConfirmPassword Not Match to the NewPassword
                    echo -2;
                }
              
                
            }
            else
            {   
                //Password that you enter is not Match to the Old Password
                echo 0;
            }
        }
       
  }
   
?>