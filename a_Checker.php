<?php 

    require_once 'connection.php';
    if (sqlsrv_begin_transaction($conn) === false) {
      die( print_r( sqlsrv_errors(), true ));
    }
    // $validateToken = include('validate_token.php');
    // if(!$validateToken)
    // {
    //   http_response_code(404);
    //   die();
    // }
        $sql = "SELECT
               c.CheckerID
              ,c.CheckerTypeID
              ,c.CheckerName
              ,c.UserID
              ,ct.CheckerType 
              FROM Checker c 
              LEFT JOIN CheckerType ct
              ON c.CheckerTypeID = ct.CheckerTypeID 
              WHERE c.isDel = 'False'"; 
// SELECT
//                ua.UserID AS CheckerID
//               ,ua.Name AS CheckerName
//               ,ua.ULevel
//               FROM UserAccount ua 
//               WHERE ua.ULevel = 1
        $stmt1 = sqlsrv_query($conn,$sql); 
        if($stmt1)
        {
          $json = array();
          do {
            while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            $json[] = $row;     	
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