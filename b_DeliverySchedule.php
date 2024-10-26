<?php 

    require_once 'connection.php';
    $data = json_decode(file_get_contents('php://input'));
    // $order_details_id=$data->order_details_id;
    // var_dump($data);
    if ( sqlsrv_begin_transaction( $conn ) === false ) {
        die( print_r( sqlsrv_errors(), true ));
    } 
          if(isset($data))
            {  
                $DeliveryScheduleID = 0;
                $SONumber = "";
                $CustomerID =0;
                $Address = "";
                $TruckID = 0;
                $TotalQty = 0;
                $DateSchedule = "";
                $UserID="";
                $deleted = 0;
                
                if(isset($data->DeliveryScheduleID))
                { 
                  $DeliveryScheduleID=$data->DeliveryScheduleID;
                }

                if(isset($data->SONumber))
                { 
                  $SONumber=$data->SONumber;
                }
                if(isset($data->CustomerID))
                { 
                  $CustomerID=$data->CustomerID;
                }
                if(isset($data->Address))
                {
                $Address=$data->Address;
                }
                if(isset($data->TruckID))
                {
                $TruckID=$data->TruckID;
                }
                if(isset($data->DateSchedule))
                {
                $DateSchedule=$data->DateSchedule;
                }
                if(isset($data->TotalQty))
                {
                $TotalQty=$data->TotalQty;
                }
                if(isset($data->Status))
                { 
                  $Status=$data->Status;
                }
                if(isset($data->deleted))
                {
                $deleted=$data->deleted;
                }
                if(isset($data->UserID))
                {
                $UserID=$data->UserID;
                }
              if($DeliveryScheduleID == 0)
                {
                    $DeliveryScheduleIDs = "";
                    $sql = "SELECT COUNT(DeliveryScheduleID) AS DeliveryScheduleID FROM DeliverySchedule WHERE SONumber = ?";
                    $params = array($SONumber);
                    $stmt = sqlsrv_query($conn, $sql,$params);
                    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
                    {
                      $DeliveryScheduleIDs = $row['DeliveryScheduleID'];
                    }
                    if($DeliveryScheduleIDs == 0)
                    {
                      $sql = "INSERT INTO DeliverySchedule (SONumber,CustomerID,Address,TruckID,DateSchedule,TotalQty,Status,deleted,UserID)
                      VALUES(?,?,?,?,?,?,?,?,?) SELECT SCOPE_IDENTITY()";
                      $params = array($SONumber,$CustomerID,$Address,$TruckID,$DateSchedule,$TotalQty,$Status,$deleted,$UserID);
                      $stmt = sqlsrv_query($conn, $sql, $params);
                      sqlsrv_next_result($stmt); 
                      sqlsrv_fetch($stmt); 
                      $DeliveryScheduleID = sqlsrv_get_field($stmt, 0); 
                          if($stmt)
                          {
                          sqlsrv_commit($conn);
                            echo 1;
                          }
                          else
                          {
                            echo 0;
                          } 
                    }
                    else
                    {
                      echo 0;
                    }
                }
                else
                {
                  $DeliveryScheduleIDs = "";
                  $sql = "SELECT COUNT(DeliveryScheduleID)AS DeliveryScheduleID FROM DeliverySchedule WHERE SONumber = ? AND DeliveryScheduleID <> ? ";
                  $params = array($SONumber, $DeliveryScheduleID);
                  $stmt = sqlsrv_query($conn, $sql,$params);
                  while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
                  {
                    $DeliveryScheduleIDs = $row['DeliveryScheduleID'];
                  }
                  if($DeliveryScheduleIDs == 0)
                  {
                     $sqls = "UPDATE DeliverySchedule SET SONumber = ?,CustomerID = ?, Address = ?, TruckID = ?, DateSchedule = ?,
                     TotalQty = ?,Status = ?,UserID = ? WHERE DeliveryScheduleID = ?"; 
                     $params2 = array($SONumber, $CustomerID, $Address, $TruckID, $DateSchedule, $TotalQty,$Status, $UserID,$DeliveryScheduleID);
                     $stmt2 = sqlsrv_query($conn,$sqls, $params2);
                       if($stmt2)
                       {
                       sqlsrv_commit($conn);
                       echo 2;
                       } 
                       $sqls = "UPDATE DeliveryScheduleDetails SET deleted = 1 WHERE DeliveryScheduleID = ?"; 
                       $params3 = array($DeliveryScheduleID );
                       $stmt1 = sqlsrv_query($conn, $sqls, $params3); 
                  }
                  else
                  {
                    echo 0;
                  } 
                }
                $array = $data->DeliveryScheduleDetail;
                $length = count($array); // count array
                for($i=0; $i<=$length-1; $i++)
                {       $DeliveryScheduleDetailsID = 0;
                        $FinishProductID = 0;
                        $Quantity = 0;
                        $deleted = 0;
                        if(isset($array[$i]->DeliveryScheduleDetailsID))
                        { 
                          $DeliveryScheduleDetailsID = $array[$i]->DeliveryScheduleDetailsID;//Insert 
                        }   
                        if(isset($array[$i]->FinishProductID))
                          { 
                          $FinishProductID=$array[$i]->FinishProductID->FinishProductID;
                          } 
                        if(isset($array[$i]->Quantity))
                          { 
                            $Quantity = $array[$i]->Quantity;
                          }  
                        if(isset($array[$i]->deleted))
                          { 
                            $deleted = $array[$i]->deleted;
                          } 
                        if($DeliveryScheduleDetailsID == 0)
                          {
                              $sql1 = "INSERT INTO DeliveryScheduleDetails(DeliveryScheduleID,FinishProductID, Quantity,deleted )VALUES(?,?,?,?)";
                              $params1 = array($DeliveryScheduleID, $FinishProductID,$Quantity,$deleted);
                              $stmt1 = sqlsrv_query($conn,$sql1,$params1);           
                          }
                          else
                          {  
                            $sqls = "UPDATE DeliveryScheduleDetails SET DeliveryScheduleID = ?, FinishProductID  = ?,Quantity = ?,deleted = ? WHERE DeliveryScheduleDetailsID = ? "; //Update (order_details table) on specific customer_id
                            $params3 = array($DeliveryScheduleID, $FinishProductID,$Quantity,$deleted,$DeliveryScheduleDetailsID);
                            $stmt2 = sqlsrv_query($conn, $sqls, $params3); 
                          }
                              sqlsrv_commit($conn); 
                        

                  }
            } 
?>