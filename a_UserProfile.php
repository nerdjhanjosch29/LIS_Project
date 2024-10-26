<?php 

    require_once 'connection.php';
    $data = json_decode(file_get_contents('php://input'));
    if (sqlsrv_begin_transaction($conn) === false) {
      die( print_r( sqlsrv_errors(), true ));
    }
    // $validateToken = include('validate_token.php');
    // if(!$validateToken)
    // {
    //   http_response_code(404);
    //   die();
    // }
    $UserID = "";
    if(isset($_GET['UserID']))
    {
      $UserID = $_GET['UserID'];
    }
        $sql = "SELECT 
                 ua.UserID
                ,ua.AvatarUrl 
                ,ua.UName
                ,ua.Name
                ,ua.ContactNo
                ,ua.EmailAdd
                 FROM UserAccount ua
                 WHERE ua.UserID = ?";
        $params = array($UserID);
        $stmt1 = sqlsrv_query($conn,$sql,$params); 
        if($stmt1)
        {
       
          $json = array();
          do {
              while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
              $json = $row; 
              
            }
          } while (sqlsrv_next_result($stmt1));
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode($json);
        }
        else
        {
        sqlsrv_rollback($conn);
        echo "Rollback";
        }      
?>