<?php 
    require_once 'connection.php';
    $data = json_decode(file_get_contents('php://input'));
    if ( sqlsrv_begin_transaction( $conn ) === false ) {
        die( print_r( sqlsrv_errors(), true ));
    } 
          if(isset($data))
            {  
                $DeliveryID = 0;
                $DeliveryNo = 0;
                $KiloPerBag =0;
                $DeliveryDate = "";
                $CustomerID = 0;
                $UserID="";
                $PurchaseOrderNo = "";
                if(isset($data->DeliveryID))
                { 
                  $DeliveryID=$data->DeliveryID;
                }
                if(isset($data->DeliveryNo))
                { 
                  $DeliveryNo=$data->DeliveryNo;
                }
                if(isset($data->SONumber))
                { 
                  $SONumber=$data->SONumber;
                }
                if(isset($data->KiloPerBag))
                { 
                  $KiloPerBag=$data->KiloPerBag;
                }
                if(isset($data->DeliveryDate))
                {
                $DeliveryDate=$data->DeliveryDate;
                }
                if(isset($data->CustomerID))
                {
                $CustomerID=$data->CustomerID;
                }
                if(isset($data->Status))
                {
                $Status=$data->Status;
                }
                if(isset($data->TotalQty))
                {
                $TotalQty=$data->TotalQty;
                }
                if(isset($data->UserID))
                {
                $UserID=$data->UserID;
                }
                if(isset($data->PurchaseOrderNo))
                {
                  $PurchaseOrderNo=$data->PurchaseOrderNo;
                }
              if($DeliveryID == 0)
                {
                    $DeliveryID = "";
                    $sql3 = "INSERT INTO Delivery (DeliveryNo, SONumber, PurchaseOrderNo,
                     KiloPerBag, DeliveryDate, CustomerID,TotalQty, UserID)VALUES(?,?,?,?,?,?,?,?)
                    SELECT SCOPE_IDENTITY()";
                     $params3 = array($DeliveryNo,$SONumber, $PurchaseOrderNo, $KiloPerBag, $DeliveryDate, $CustomerID,$TotalQty, $UserID);
                    $stmt1 = sqlsrv_query($conn,$sql3,$params3); 
                    sqlsrv_next_result($stmt1); 
                    sqlsrv_fetch($stmt1); 
                    $DeliveryID = sqlsrv_get_field($stmt1, 0);    
                        if($stmt1)
                        {
                          echo 1;
                          $sql = "UPDATE DeliverySchedule SET Status = ? WHERE SONumber = ?";
                          $params = array($Status, $SONumber);
                          $stmt = sqlsrv_query($conn, $sql,$params);
                          sqlsrv_commit($conn);
                        }
                        else
                        {
                          echo 0;
                        }  
                }
                else
                {
                    //Update Query
                    $sqls = "UPDATE Delivery SET DeliveryNo = ?,SONumber = ?, PurchaseOrderNo = ?, KiloPerBag = ?, DeliveryDate = ?, CustomerID = ?, TotalQty = ?, UserID = ? WHERE DeliveryID = ?"; //Update (Delivery) on specific Customer_ID
                    $params2 = array($DeliveryNo,$SONumber, $PurchaseOrderNo, $KiloPerBag, $DeliveryDate, $CustomerID,$TotalQty, $UserID,$DeliveryID);
                    $stmt1 = sqlsrv_query($conn,$sqls, $params2);
                      if($stmt1)
                      {
                      sqlsrv_commit($conn);
                      echo 2;
                      } 
                      $sqls = "UPDATE DeliveryDetail SET deleted = 1 WHERE DeliveryID = ?"; //Update deleted to 1
                      $params3 = array($DeliveryID);
                      $stmt1 = sqlsrv_query($conn, $sqls, $params3);
                }
                  $arrays = $data->DeliveryDetail;
                  $lengths = count($arrays); // count array
                  for($i=0; $i<=$lengths-1; $i++)
                  { 
                          $DeliveryDetailID = 0;
                          $FinishProductID = 0;
                          $Quantity = 0;   
                          $WarehouseID = 0; 
                          $WarehousePartitionID = 0;    
                          $WarehousePartitionStockID = 0;   
                          $deleted = 0;
                          $Condemned = 0;
                          $Weight = 0;
                          if(isset($arrays[$i]->FinishProductID))
                          { 
                          $FinishProductID=$arrays[$i]->FinishProductID->FinishProductID;
                          }
                            //2nd loop
                            $array = $data->DeliveryDetail[$i]->Orders;
                            $length = count($array); // count array
                            for($j=0; $j<=$length-1; $j++)
                            { 
                                    if(isset($array[$j]->DeliveryDetailID))
                                    { 
                                      $DeliveryDetailID = $array[$j]->DeliveryDetailID; 
                                    }  
                                    if(isset($array[$j]->WarehousePartitionStockID))
                                    { 
                                      $WarehousePartitionStockID = $array[$j]->WarehousePartitionStockID; //Insert 
                                    }  
                                    if(isset($array[$j]->WarehouseID))
                                    { 
                                      $WarehouseID = $array[$j]->WarehouseID; //Insert 
                                    } 
                                    if(isset($array[$j]->WarehousePartitionID))
                                    { 
                                      $WarehousePartitionID = $array[$j]->WarehousePartitionID; //Insert 
                                    }  
                                    if(isset($array[$j]->Quantity))
                                    { 
                                      $Quantity = $array[$j]->Quantity;
                                    }  
                                    
                                    if(isset($array[$j]->Weight))
                                    { 
                                      $Weight = $array[$j]->Weight;
                                    }  
                                    if($DeliveryDetailID == 0)
                                    { 
                                      $sqls = "INSERT INTO DeliveryDetail(DeliveryID,WarehousePartitionStockID,WarehouseID,WarehousePartitionID,FinishProductID,Quantity,Weight,Condemned,deleted)
                                      VALUES(?,?,?,?,?,?,?,?,?)"; 
                                       $params3 = array($DeliveryID,$WarehousePartitionStockID,$WarehouseID,$WarehousePartitionID,$FinishProductID, $Quantity,$Weight, $Condemned,$deleted);
                                       $stmt2 = sqlsrv_query($conn, $sqls, $params3);  
                                       sqlsrv_commit($conn);
                                    }  
                                            $StockingDate = "";
                                            $FinProdQty = "";
                                            $sql4 = "SELECT FinProdQty, StockingDate FROM WarehousePartitionStock WHERE WarehousePartitionStockID = ? 
                                            AND FinishProductID = ?";
                                            $params4 = array($WarehousePartitionStockID,$FinishProductID);
                                            $stmt4 = sqlsrv_query($conn, $sql4, $params4);
                                            while($row = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC))
                                            {
                                              $FinProdQty = $row['FinProdQty'];
                                              $StockingDate = $row['StockingDate'];
                                              $StockingDate = $StockingDate->format('Y-m-d');
                                            }
                                            $totalQ = $FinProdQty - $Quantity;
                                            $sqla = "UPDATE WarehousePartitionStock SET FinProdQty = ? WHERE WarehousePartitionStockID = ? AND FinishProductID = ?";
                                            $paramsf = array($totalQ, $WarehousePartitionStockID, $FinishProductID);
                                            $stmtf = sqlsrv_query($conn, $sqla, $paramsf);

                                            $ProductionOutput = 0;
                                            $ProductionOutputWeight = 0;                                        
                                            $total = 0;
                                            $totalWeight= 0;
                                            $EndingQty = 0;
                                            $EndingWeight = 0;
                                            $Outgoings = 0;
                                            $OutgoingWeight = 0;
                                            $BeginWeight =0;
                                            $Begin = 0;

                                            $sqlk = "SELECT 

                                              BeginQty
                                             ,ProductionOutput
                                             ,OutgoingQty
                                             ,OutgoingWeight
                                              FROM FinishProductInventory
                                              WHERE FinishProductID = ? AND InventoryDate = ?";

                                            $paramsk = array($FinishProductID, $StockingDate);
                                            $stmtk = sqlsrv_query($conn, $sqlk, $paramsk);
                                            while($row = sqlsrv_fetch_array($stmtk, SQLSRV_FETCH_ASSOC))
                                            {
                                              $Begin = $row['BeginQty'];
                                              $Output = $row['ProductionOutput'];
                                              $Outgoings = $row['OutgoingQty'];
                                              $OutgoingWeight = $row['OutgoingWeight'];
                                            }
                                            $total = $Outgoings + $Quantity;
                                            $totalWeight = $OutgoingWeight + $Weight;
                                            $EndingQty = $Begin + $ProductionOutput - $total - $Condemned;
					                                  $EndingWeight = $BeginWeight + $ProductionOutputWeight - $totalWeight - $Condemned;
                                            $sql2 ="UPDATE FinishProductInventory SET BeginQty = ?, BeginWeight = ?, OutgoingQty = ?, OutgoingWeight = ?, Condemned = ?, 
                                            EndingQty = ?, EndingWeight = ? WHERE FinishProductID = ? AND InventoryDate = ?";
                                            $params2 = array($Begin, $BeginWeight, $total, $totalWeight, $Condemned, $EndingQty, $EndingWeight, $FinishProductID, $StockingDate);
                                            $stmt2 = sqlsrv_query($conn, $sql2, $params2);
                                            sqlsrv_commit($conn);
                                            while (strtotime($StockingDate) <= strtotime(date('Y-m-d'))) {
                                            $Output = 0;
                                            $OutgoingWeight = 0;
                                            $OutgoingQty = 0;
                                            $OutgoingWeight =0;
                                            $sqlk = "SELECT ProductionOutput, ProductionOutputWeight, OutgoingQty,OutgoingWeight FROM FinishProductInventory WHERE FinishProductID = ? AND InventoryDate = ?";
                                            $paramsk = array($FinishProductID, $StockingDate);
                                            $stmtk = sqlsrv_query($conn, $sqlk, $paramsk);
                                            while($row = sqlsrv_fetch_array($stmtk, SQLSRV_FETCH_ASSOC))
                                            {
                                              $Output = $row['ProductionOutput'];
                                              $OutputWeight = $row['ProductionOutputWeight'];
                                              $OutgoingQty  = $row['OutgoingQty'];
                                              $OutgoingWeights= $row['OutgoingWeight'];
                                            }

                                            if ($Output != 0) {
                                              // Calculate EndingQty and EndingWeight
                                              $EndingQty = $Begin + $Output - $OutgoingQty - $Condemned;
                                              $EndingWeight = $BeginWeight + $OutputWeight - $OutgoingWeights - $Condemned;
                                              // Update FinishProductInventory
                                              $updateQuery = "UPDATE FinishProductInventory SET BeginQty = ?, BeginWeight = ?, EndingQty = ?, EndingWeight = ?
                                                              WHERE FinishProductID = ? AND InventoryDate = ?";
                                              $params = array($Begin, $BeginWeight, $EndingQty, $EndingWeight, $FinishProductID, $StockingDate);
                                              $stmt = sqlsrv_query($conn, $updateQuery, $params);

                                          } else {
                                              // Update FinishProductInventory without calculations
                                              $updateQuery = "UPDATE FinishProductInventory SET BeginQty = ?, BeginWeight = ?, EndingQty = ?, EndingWeight = ?
                                                              WHERE FinishProductID = ? AND InventoryDate = ?";
                                              $params = array($Begin, $BeginWeight, $EndingQty, $EndingWeight, $FinishProductID, $StockingDate);
                                              $stmt = sqlsrv_query($conn, $updateQuery, $params);
                                          }
                                          
                                          // Select the last EndingQty
                                          $selectQuery = "SELECT EndingQty, EndingWeight FROM FinishProductInventory WHERE FinishProductID = ? AND InventoryDate = ?";
                                          $params = array($FinishProductID, $StockingDate);
                                          $stmt = sqlsrv_query($conn, $selectQuery, $params);
                                  
                                          $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                                          if ($row) {
                                              $Begin = $row['EndingQty'];
                                              $BeginWeight = $row['EndingWeight'];
                                          }
                                          // Increment StockingDate by one day
                                          $StockingDate = date('Y-m-d', strtotime($StockingDate . ' +1 day'));
                                 }   
                                }     
                    }  
                }           
?>