<?php
    require_once 'connection.php';
    if (sqlsrv_begin_transaction($conn) === false) {
      die( print_r( sqlsrv_errors(), true ));
    }
        ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
      error_reporting(E_ALL);
    // $validateToken = include('validate_token.php');
    // if(!$validateToken)
    // {
    //   http_response_code(404);
    //   die();
    // }
        $sql = "SELECT 
                 sl.ShippingLineID
                ,sl.ShippingLine
                ,sl.ContactPerson
                ,sl.ContactNumber
                ,sl.UserID
                FROM ShippingLine sl 
                WHERE isDel = 'False'";
        $stmt1 = sqlsrv_query($conn,$sql); 
        if($stmt1)
        {
          $json = array();
          do {
            while ($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            $json[] = $row;     	
            }
          } while (sqlsrv_next_result($stmt1));
        // Attempt JSON Encoding with Error Handling
        $json_encoded = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);

          if ($json_encoded === false) {
              die("JSON encoding failed: " . json_last_error_msg());
          }

          header('Content-Type: application/json; charset=utf-8');
          echo $json_encoded;
        }
        else
        {
        sqlsrv_rollback($conn);
        echo "Rollback";
        }
?>