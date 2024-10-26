<?php 
require 'connection.php';
$data = json_decode(file_get_contents('php://input'));
if ( sqlsrv_begin_transaction( $conn ) === false ) {
  die( print_r( sqlsrv_errors(), true ));
} 
  if(isset($data))
  {
        $ShippingTransactionID = 0;
        if($data->ShippingTransactionID)
        {
          $ShippingTransactionID = $data->ShippingTransactionID;
        }
        if($ShippingTransactionID == 0)
        {
            $Packaging = 0;
            if(isset($data->Packaging))
            { 
              $Packaging=$data->Packaging;
            }
            if($Packaging == 1)
            {
              $ContractPerformaID = 0;
              $Lot = 0;
              $RawMaterialID = 0;
              $SupplierID = 0;
              $BAI_SPS_IC = "";
              $BPI_SPS_IC = "";
              $MBL = "";
              $ShippingLineID = 0;
              $Vessel = "";
              $HBL = "";
              $Forwarder = "";
              $ContainerTypeID = 0;
              $NoOfContainer = 0;
              $Quantity = 0;
              $BrokerID = 0;
              $BankID = 0;
              $PortOfDischarge = "";
              $Status = 0;   
              $LodgementBankID = 0;
              $Remarks = "";
              $UserID = "";
              $AdvanceDocumentsReceived=$data->AdvanceDocumentsReceived;
              $FromBAIValidity=$data->FromBAIValidity;
              $ToBAIValidity=$data->ToBAIValidity;
              $FromBPIValidity=$data->FromBPIValidity;
              $ToBPIValidity=$data->ToBPIValidity;
              $ETD=$data->ETD;
              $ETA=$data->ETA;
              $ATA=$data->ATA;
              $DateDocsReceivedByBroker=$data->DateDocsReceivedByBroker;
              $BAI_SPS_IC_Date=$data->BAI_SPS_IC_Date;
              $BPI_SPS_IC_Date=$data->BPI_SPS_IC_Date;
              $OriginalDocsAvailavilityDate=$data->OriginalDocsAvailavilityDate;
              $DateOfPickup=$data->DateOfPickup;
              $DateOfDischarge=$data->DateOfDischarge;
              $LodgementDate=$data->LodgementDate;
              $GatepassRecieved=$data->GatepassRecieved;
              $AcknowledgeByLogistics=$data->AcknowledgeByLogistics;
              $StorageLastFreeDate=$data->StorageLastFreeDate;
              $DemurrageDate=$data->DemurrageDate;
              $DetentionDate=$data->DetentionDate;
              if(isset($data->ShippingTransactionID))
              { 
                $ShippingTransactionID=$data->ShippingTransactionID;
              }
              if(isset($data->ContractPerformaID))
              { 
                $ContractPerformaID=$data->ContractPerformaID;
              }
              if(isset($data->Lot))
              { 
                $Lot=$data->Lot;
              }
              if(isset($data->RawMaterialID))
              { 
                $RawMaterialID=$data->RawMaterialID;
              }
              if(isset($data->SupplierID))
              { 
                $SupplierID=$data->SupplierID;
              }
              if(isset($data->BAI_SPS_IC))
              { 
                $BAI_SPS_IC=$data->BAI_SPS_IC;
              }
              if(isset($data->BPI_SPS_IC))
              { 
                $BPI_SPS_IC=$data->BPI_SPS_IC;
              }
              if(isset($data->MBL))
              { 
                $MBL=$data->MBL;
              }
              if(isset($data->ShippingLineID))
              { 
                $ShippingLineID=$data->ShippingLineID;
              }
              if(isset($data->Vessel))
              { 
                $Vessel=$data->Vessel;
              }
              if(isset($data->HBL))
              { 
                $HBL=$data->HBL;
              }
              if(isset($data->Forwarder))
              { 
                $Forwarder=$data->Forwarder;
              }
              if(isset($data->ContainerTypeID))
              { 
                $ContainerTypeID=$data->ContainerTypeID;
              }
              if(isset($data->NoOfContainer))
              { 
                $NoOfContainer=$data->NoOfContainer;
              }
              if(isset($data->Quantity))
              { 
                $Quantity=$data->Quantity;
              }
              if(isset($data->BrokerID))
              { 
                $BrokerID=$data->BrokerID;
              }
              if(isset($data->BankID))
              { 
                $BankID=$data->BankID;
              }        
              if(isset($data->PortOfDischarge))
              { 
                $PortOfDischarge=$data->PortOfDischarge;
              }
              if(isset($data->Status))
              { 
                $Status=$data->Status;
              }
              if(isset($data->LodgementBankID))
              { 
                $LodgementBankID=$data->LodgementBankID;
              }
              if(isset($data->Remarks))
              { 
                $Remarks=$data->Remarks;
              }
              if(isset($data->UserID))
              { 
                $UserID=$data->UserID;
              }
                $sql = "INSERT INTO ShippingTransaction (ContractPerformaID,Lot,RawMaterialID,SupplierID,Packaging,
                AdvanceDocumentsReceived,BAI_SPS_IC,FromBAIValidity,
                ToBAIValidity,BPI_SPS_IC,FromBPIValidity,
                ToBPIValidity,MBL,
                ShippingLineID,Vessel,HBL,Forwarder,ETD,ETA,ATA,ContainerTypeID,NoOfContainer,Quantity,BrokerID,
                DateDocsReceivedByBroker,BAI_SPS_IC_Date,BPI_SPS_IC_Date,OriginalDocsAvailavilityDate,BankID,DateOfPickup,PortOfDischarge,Status,DateOfDischarge,LodgementDate,
                LodgementBankID,
                GatepassRecieved,AcknowledgeByLogistics,StorageLastFreeDate,DemurrageDate,DetentionDate
                ,Remarks,UserID)
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $params = array($ContractPerformaID,$Lot,$RawMaterialID,$SupplierID,$Packaging,$AdvanceDocumentsReceived,
                $BAI_SPS_IC,$FromBAIValidity,$ToBAIValidity, $BPI_SPS_IC,$FromBPIValidity,$ToBPIValidity,$MBL,
                $ShippingLineID,$Vessel,$HBL,$Forwarder,$ETD,$ETA,$ATA,$ContainerTypeID,$NoOfContainer,$Quantity,$BrokerID,
                $DateDocsReceivedByBroker,$BAI_SPS_IC_Date,$BPI_SPS_IC_Date,$OriginalDocsAvailavilityDate,$BankID,$DateOfPickup,$PortOfDischarge,$Status,
                $DateOfDischarge,$LodgementDate,$LodgementBankID,$GatepassRecieved,$AcknowledgeByLogistics,$StorageLastFreeDate,$DemurrageDate,$DetentionDate,
                $Remarks,$UserID);
                $stmt = sqlsrv_query($conn, $sql, $params);
                sqlsrv_commit($conn);
                if($stmt)
                {
                  echo 1;
                }
          } //Brace for If $Packaging = 1
          else 
          {
             $ContractPerformaID = 0;
              $Lot = 0;
              $RawMaterialID = 0;
              $SupplierID = 0;
              $BAI_SPS_IC = "";
              $BPI_SPS_IC = "";
              $MBL = "";
              $BL = "";
              $ShippingLineID = 0;
              $Vessel = "";
              $HBL = "";
              $Forwarder = "";
              $ContainerTypeID = 0;
              $NoOfContainer = 0;
              $NoOfTruck = 0;
              $Quantity = 0;
              $BrokerID = 0;
              $BankID = 0;
              $PortOfDischarge = "";
              $Status = 0;   
              $LodgementBankID = 0;
              $Remarks = "";
              $UserID = "";
              $AdvanceDocumentsReceived=$data->AdvanceDocumentsReceived;
              $FromBAIValidity=$data->FromBAIValidity;
              $ToBAIValidity=$data->ToBAIValidity;
              $FromBPIValidity=$data->FromBPIValidity;
              $ToBPIValidity=$data->ToBPIValidity;
              $ETD=$data->ETD;
              $ETA=$data->ETA;
              $ATA=$data->ATA;
              $DateDocsReceivedByBroker=$data->DateDocsReceivedByBroker;
              $BAI_SPS_IC_Date=$data->BAI_SPS_IC_Date;
              $BPI_SPS_IC_Date=$data->BPI_SPS_IC_Date;
              $OriginalDocsAvailavilityDate=$data->OriginalDocsAvailavilityDate;
              $DateOfPickup=$data->DateOfPickup;
              $DateOfDischarge=$data->DateOfDischarge;
              $LodgementDate=$data->LodgementDate;
              $GatepassRecieved=$data->GatepassRecieved;
              $AcknowledgeByLogistics=$data->AcknowledgeByLogistics;
              $StorageLastFreeDate=$data->StorageLastFreeDate;
              $DemurrageDate=$data->DemurrageDate;
              $DetentionDate=$data->DetentionDate;
              if(isset($data->ShippingTransactionID))
              { 
                $ShippingTransactionID=$data->ShippingTransactionID;
              }
              if(isset($data->ContractPerformaID))
              { 
                $ContractPerformaID=$data->ContractPerformaID;
              }
              if(isset($data->Lot))
              { 
                $Lot=$data->Lot;
              }
              if(isset($data->RawMaterialID))
              { 
                $RawMaterialID=$data->RawMaterialID;
              }
              if(isset($data->SupplierID))
              { 
                $SupplierID=$data->SupplierID;
              }
              if(isset($data->BAI_SPS_IC))
              { 
                $BAI_SPS_IC=$data->BAI_SPS_IC;
              }
              if(isset($data->BPI_SPS_IC))
              { 
                $BPI_SPS_IC=$data->BPI_SPS_IC;
              }
              if(isset($data->MBL))
              { 
                $MBL=$data->MBL;
              }
              if(isset($data->BL))
              { 
                $BL=$data->BL;
              }
              if(isset($data->ShippingLineID))
              { 
                $ShippingLineID=$data->ShippingLineID;
              }
              if(isset($data->Vessel))
              { 
                $Vessel=$data->Vessel;
              }
              if(isset($data->HBL))
              { 
                $HBL=$data->HBL;
              }
              if(isset($data->Forwarder))
              { 
                $Forwarder=$data->Forwarder;
              }
              if(isset($data->ContainerTypeID))
              { 
                $ContainerTypeID=$data->ContainerTypeID;
              }
              if(isset($data->NoOfContainer))
              { 
                $NoOfContainer=$data->NoOfContainer;
              }
              if(isset($data->NoOfTruck))
              { 
                $NoOfTruck=$data->NoOfTruck;
              }
              if(isset($data->Quantity))
              { 
                $Quantity=$data->Quantity;
              }
              if(isset($data->BrokerID))
              { 
                $BrokerID=$data->BrokerID;
              }
              if(isset($data->BankID))
              { 
                $BankID=$data->BankID;
              }        
              if(isset($data->PortOfDischarge))
              { 
                $PortOfDischarge=$data->PortOfDischarge;
              }
              if(isset($data->Status))
              { 
                $Status=$data->Status;
              }
              if(isset($data->LodgementBankID))
              { 
                $LodgementBankID=$data->LodgementBankID;
              }
              if(isset($data->Remarks))
              { 
                $Remarks=$data->Remarks;
              }
              if(isset($data->UserID))
              { 
                $UserID=$data->UserID;
              }
            else
            {
              $sql = "INSERT INTO ShippingTransaction (ContractPerformaID ,Lot,RawMaterialID,SupplierID,Packaging,
              AdvanceDocumentsReceived,BAI_SPS_IC,FromBAIValidity,
              ToBAIValidity,BPI_SPS_IC, FromBPIValidity,ToBPIValidity,BL,ShippingLineID,
              Vessel,ETD,ETA,ATA,NoOfTruck,Quantity,BrokerID,
              DateDocsReceivedByBroker,BAI_SPS_IC_Date, BPI_SPS_IC_Date,OriginalDocsAvailavilityDate,BankID,DateOfPickup,PortOfDischarge,Status,
              DateOfDischarge,LodgementDate,LodgementBankID,
              GatepassRecieved,AcknowledgeByLogistics,StorageLastFreeDate,DemurrageDate,DetentionDate,
              Remarks,UserID)
              VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
              $params = array($ContractPerformaID ,$Lot,$Served,$Balance, $RawMaterialID,$SupplierID,$Packaging,$AdvanceDocumentsReceived,$BAI_SPS_IC,$FromBAIValidity,
              $ToBAIValidity,
              $BPI_SPS_IC,$FromBPIValidity,$ToBPIValidity,$BL,$ShippingLineID,$ETD,$ETA,$ATA,$NoOfTruck,$Quantity,$BrokerID,$DateDocsReceivedByBroker,
              $BAI_SPS_IC_Date, $BPI_SPS_IC_Date,$OriginalDocsAvailavilityDate,$BankID,$DateOfPickup,$PortOfDischarge,$Status,
              $DateOfDischarge,$LodgementDate,$LodgementBankID,$GatepassRecieved,$AcknowledgeByLogistics,$StorageLastFreeDate,$DemurrageDate,$DetentionDate,
              $Remarks,$UserID);
              $stmt = sqlsrv_query($conn, $sql, $params);
              sqlsrv_commit($conn);
              if($stmt)
              {
                echo 1;
              }
            }
          } //Else for IF $Packaging = 1
    }//IF ShippingTransactionID = 0
    else
    {
            $Packaging = 0;
            if(isset($data->Packaging))
            { 
              $Packaging=$data->Packaging;
            }
                if($Packaging == 1)
                {
                  $ContractPerformaID = 0;
                  $Lot = 0;
                  $RawMaterialID = 0;
                  $SupplierID = 0;
                  $BAI_SPS_IC = "";
                  $BPI_SPS_IC = "";
                  $MBL = "";
                  $BL = "";
                  $ShippingLineID = 0;
                  $Vessel = "";
                  $HBL = "";
                  $Forwarder = "";
                  $ContainerTypeID = 0;
                  $NoOfContainer = 0;
                  $Quantity = 0;
                  $BrokerID = 0;
                  $BankID = 0;
                  $PortOfDischarge = "";
                  $Status = 0;   
                  $LodgementBankID = 0;
                  $Remarks = "";
                  $UserID = "";
                  $AdvanceDocumentsReceived=$data->AdvanceDocumentsReceived;
                  $FromBAIValidity=$data->FromBAIValidity;
                  $ToBAIValidity=$data->ToBAIValidity;
                  $FromBPIValidity=$data->FromBPIValidity;
                  $ToBPIValidity=$data->ToBPIValidity;
                  $ETD=$data->ETD;
                  $ETA=$data->ETA;
                  $ATA=$data->ATA;
                  $DateDocsReceivedByBroker=$data->DateDocsReceivedByBroker;
                  $BAI_SPS_IC_Date=$data->BAI_SPS_IC_Date;
                  $BPI_SPS_IC_Date=$data->BPI_SPS_IC_Date;
                  $OriginalDocsAvailavilityDate=$data->OriginalDocsAvailavilityDate;
                  $DateOfPickup=$data->DateOfPickup;
                  $DateOfDischarge=$data->DateOfDischarge;
                  $LodgementDate=$data->LodgementDate;
                  $GatepassRecieved=$data->GatepassRecieved;
                  $AcknowledgeByLogistics=$data->AcknowledgeByLogistics;
                  $StorageLastFreeDate=$data->StorageLastFreeDate;
                  $DemurrageDate=$data->DemurrageDate;
                  $DetentionDate=$data->DetentionDate;
                  if(isset($data->ShippingTransactionID))
                  { 
                    $ShippingTransactionID=$data->ShippingTransactionID;
                  }
                  if(isset($data->ContractPerformaID))
                  { 
                    $ContractPerformaID=$data->ContractPerformaID;
                  }
                  if(isset($data->Lot))
                  { 
                    $Lot=$data->Lot;
                  }
                  if(isset($data->RawMaterialID))
                  { 
                    $RawMaterialID=$data->RawMaterialID;
                  }
                  if(isset($data->SupplierID))
                  { 
                    $SupplierID=$data->SupplierID;
                  }
                  if(isset($data->BAI_SPS_IC))
                  { 
                    $BAI_SPS_IC=$data->BAI_SPS_IC;
                  }
                  if(isset($data->BPI_SPS_IC))
                  { 
                    $BPI_SPS_IC=$data->BPI_SPS_IC;
                  }
                  if(isset($data->MBL))
                  { 
                    $MBL=$data->MBL;
                  }
                  if(isset($data->BL))
                  { 
                    $BL=$data->BL;
                  }
                  if(isset($data->ShippingLineID))
                  { 
                    $ShippingLineID=$data->ShippingLineID;
                  }
                  if(isset($data->Vessel))
                  { 
                    $Vessel=$data->Vessel;
                  }
                  if(isset($data->HBL))
                  { 
                    $HBL=$data->HBL;
                  }
                  if(isset($data->Forwarder))
                  { 
                    $Forwarder=$data->Forwarder;
                  }
                  if(isset($data->ContainerTypeID))
                  { 
                    $ContainerTypeID=$data->ContainerTypeID;
                  }
                  if(isset($data->NoOfContainer))
                  { 
                    $NoOfContainer=$data->NoOfContainer;
                  }
                  if(isset($data->Quantity))
                  { 
                    $Quantity=$data->Quantity;
                  }
                  if(isset($data->BrokerID))
                  { 
                    $BrokerID=$data->BrokerID;
                  }
                  if(isset($data->BankID))
                  { 
                    $BankID=$data->BankID;
                  }        
                  if(isset($data->PortOfDischarge))
                  { 
                    $PortOfDischarge=$data->PortOfDischarge;
                  }
                  if(isset($data->Status))
                  { 
                    $Status=$data->Status;
                  }
                  if(isset($data->LodgementBankID))
                  { 
                    $LodgementBankID=$data->LodgementBankID;
                  }
                  if(isset($data->Remarks))
                  { 
                    $Remarks=$data->Remarks;
                  }
                  if(isset($data->UserID))
                  { 
                    $UserID=$data->UserID;
                  }
                    $sql = "UPDATE ShippingTransaction SET ContractPerformaID = ?, Lot = ?,RawMaterialID = ?, SupplierID = ?,
                    Packaging = ?, AdvanceDocumentsReceived = ?, BAI_SPS_IC = ?, FromBAIValidity = ?, ToBAIValidity = ?,
                    MBL = ?, BL = ?, ShippingLineID = ?,Vessel = ?, HBL = ?, Forwarder = ?, ETD = ?, ETA = ?,ATA = ?, ContainerTypeID = ?, NoOfContainer = ?,
                    Quantity = ?, BrokerID = ?, DateDocsReceivedByBroker = ?, BAI_SPS_IC_Date = ?,
                    OriginalDocsAvailavilityDate = ?, BankID = ?, DateOfPickup = ?, PortOfDischarge = ?, 
                    Status = ?, DateOfDischarge = ?,LodgementDate = ?,LodgementBankID = ?,
                    GatepassRecieved = ?,AcknowledgeByLogistics = ?,
                    StorageLastFreeDate = ?,DemurrageDate = ?,DetentionDate = ?,
                    Remarks = ?, UserID = ? WHERE ShippingTransactionID = ?";
                    $params = array($ContractPerformaID,$Lot,$RawMaterialID,$SupplierID,$Packaging,$AdvanceDocumentsReceived,
                    $BAI_SPS_IC,$FromBAIValidity,
                    $ToBAIValidity,$MBL, $BL,
                    $ShippingLineID,$Vessel,$HBL,$Forwarder,$ETD,$ETA,$ATA,$ContainerTypeID,$NoOfContainer,$Quantity,$BrokerID,
                    $DateDocsReceivedByBroker,$BAI_SPS_IC_Date,$OriginalDocsAvailavilityDate,$BankID,$DateOfPickup,$PortOfDischarge,$Status,
                    $DateOfDischarge,$LodgementDate,$LodgementBankID,$GatepassRecieved,$AcknowledgeByLogistics,$StorageLastFreeDate,
                    $DemurrageDate,$DetentionDate,
                    $Remarks,$UserID, $ShippingTransactionID);
                    $stmt = sqlsrv_query($conn, $sql, $params);
                    sqlsrv_commit($conn);
                    echo 2;

                    // $sqls = "EXEC [dbo].[AddUnloadedBL]
                    // @ATA = ?,
                    // @Packaging = ?,
                    // @BL = ?,
                    // @MBL = ?,
                    // @ShippingLineID = ?,
                    // @HBL = ?";
                    // $paramss = array($ATA,$Packaging,$BL, $MBL,$ShippingLineID,$HBL);
                    // $stmts = sqlsrv_query($conn, $sqls,$paramss);
              } //Brace for If $Packaging = 1
              else //Elseee for UPDATE
              {
                $ContractPerformaID = 0;
                $Lot = 0;
                $RawMaterialID = 0;
                $SupplierID = 0;
                $BAI_SPS_IC = "";
                $BPI_SPS_IC = "";
                $MBL = "";
                $BL = "";
                $ShippingLineID = 0;
                $Vessel = "";
                $Forwarder = "";
                $ContainerTypeID = 0;
                $NoOfContainer = 0;
                $Quantity = 0;
                $BrokerID = 0;
                $BankID = 0;
                $NoOfTruck = 0;
                $PortOfDischarge = "";
                $Status = 0;   
                $LodgementBankID = 0;
                $Remarks = "";
                $UserID = "";
                $AdvanceDocumentsReceived=$data->AdvanceDocumentsReceived;
                $FromBAIValidity=$data->FromBAIValidity;
                $ToBAIValidity=$data->ToBAIValidity;
                $FromBPIValidity=$data->FromBPIValidity;
                $ToBPIValidity=$data->ToBPIValidity;
                $ETD=$data->ETD;
                $ETA=$data->ETA;
                $ATA=$data->ATA;
                $DateDocsReceivedByBroker=$data->DateDocsReceivedByBroker;
                $BAI_SPS_IC_Date=$data->BAI_SPS_IC_Date;
                $BPI_SPS_IC_Date=$data->BPI_SPS_IC_Date;
                $OriginalDocsAvailavilityDate=$data->OriginalDocsAvailavilityDate;
                $DateOfPickup=$data->DateOfPickup;
                $DateOfDischarge=$data->DateOfDischarge;
                $LodgementDate=$data->LodgementDate;
                $GatepassRecieved=$data->GatepassRecieved;
                $AcknowledgeByLogistics=$data->AcknowledgeByLogistics;
                $StorageLastFreeDate=$data->StorageLastFreeDate;
                $DemurrageDate=$data->DemurrageDate;
                $DetentionDate=$data->DetentionDate;
                if(isset($data->ShippingTransactionID))
                { 
                  $ShippingTransactionID=$data->ShippingTransactionID;
                }
                if(isset($data->ContractPerformaID))
                { 
                  $ContractPerformaID=$data->ContractPerformaID;
                }
                if(isset($data->Lot))
                { 
                  $Lot=$data->Lot;
                }
                if(isset($data->RawMaterialID))
                { 
                  $RawMaterialID=$data->RawMaterialID;
                }
                if(isset($data->SupplierID))
                { 
                  $SupplierID=$data->SupplierID;
                }
                if(isset($data->BAI_SPS_IC))
                { 
                  $BAI_SPS_IC=$data->BAI_SPS_IC;
                }
                if(isset($data->BPI_SPS_IC))
                { 
                  $BPI_SPS_IC=$data->BPI_SPS_IC;
                }
                if(isset($data->MBL))
                { 
                  $MBL=$data->MBL;
                }
                if(isset($data->BL))
                { 
                  $BL=$data->BL;
                }
                if(isset($data->ShippingLineID))
                { 
                  $ShippingLineID=$data->ShippingLineID;
                }
                if(isset($data->Vessel))
                { 
                  $Vessel=$data->Vessel;
                }
                if(isset($data->HBL))
                { 
                  $HBL=$data->HBL;
                }
                if(isset($data->Forwarder))
                { 
                  $Forwarder=$data->Forwarder;
                }
                if(isset($data->ContainerTypeID))
                { 
                  $ContainerTypeID=$data->ContainerTypeID;
                }
                if(isset($data->NoOfTruck))
                { 
                  $NoOfTruck=$data->NoOfTruck;
                }
                if(isset($data->NoOfContainer))
                { 
                  $NoOfContainer=$data->NoOfContainer;
                }
                if(isset($data->Quantity))
                { 
                  $Quantity=$data->Quantity;
                }
                if(isset($data->BrokerID))
                { 
                  $BrokerID=$data->BrokerID;
                }
                if(isset($data->BankID))
                { 
                  $BankID=$data->BankID;
                }        
                if(isset($data->PortOfDischarge))
                { 
                  $PortOfDischarge=$data->PortOfDischarge;
                }
                if(isset($data->Status))
                { 
                  $Status=$data->Status;
                }
                if(isset($data->LodgementBankID))
                { 
                  $LodgementBankID=$data->LodgementBankID;
                }
                if(isset($data->Remarks))
                {
                  $Remarks=$data->Remarks;
                }
                if(isset($data->UserID))
                { 
                  $UserID=$data->UserID;
                }
                  $sql = "UPDATE ShippingTransaction SET ContractPerformaID = ?, Lot = ?, RawMaterialID = ?, SupplierID = ?,
                  Packaging = ?, AdvanceDocumentsReceived = ?, BAI_SPS_IC = ?, FromBAIValidity = ?, ToBAIValidity = ?,BPI_SPS_IC = ?,
                  FromBPIValidity = ?, ToBPIValidity = ?,
                  MBL = ?,BL = ?, ShippingLineID = ?, 
                  Vessel = ?,HBL = ?, Forwarder = ?, ETD = ?, ETA = ?,  ATA = ?, NoOfTruck = ?,
                  Quantity = ?, BrokerID = ?, DateDocsReceivedByBroker = ?, BAI_SPS_IC_Date = ?,
                  BPI_SPS_IC_Date = ?,
                  OriginalDocsAvailavilityDate = ?, BankID = ?, DateOfPickup = ?, PortOfDischarge = ?, 
                  Status = ?, DateOfDischarge = ?,LodgementDate = ?,LodgementBankID = ?,
                  GatepassRecieved = ?,AcknowledgeByLogistics = ?,
                  StorageLastFreeDate = ?,DemurrageDate = ?,DetentionDate = ?,
                  Remarks = ?, UserID = ? WHERE ShippingTransactionID = ?";
                  $params = array($ContractPerformaID,$Lot,$RawMaterialID,$SupplierID,$Packaging,$AdvanceDocumentsReceived,$BAI_SPS_IC,
                  $FromBAIValidity,
                  $ToBAIValidity, 
                  $BPI_SPS_IC,$FromBPIValidity,
                  $ToBPIValidity,$MBL,$BL,
                  $ShippingLineID,$Vessel,
                  $HBL,$Forwarder,$ETD,$ETA,$ATA,$NoOfTruck,$Quantity,$BrokerID,
                  $DateDocsReceivedByBroker,$BAI_SPS_IC_Date,$BPI_SPS_IC_Date,$OriginalDocsAvailavilityDate,$BankID,$DateOfPickup,$PortOfDischarge,$Status,
                  $DateOfDischarge,$LodgementDate,$LodgementBankID,$GatepassRecieved,$AcknowledgeByLogistics,$StorageLastFreeDate,
                  $DemurrageDate,$DetentionDate,
                  $Remarks,$UserID, $ShippingTransactionID);
                  $stmt = sqlsrv_query($conn, $sql, $params);
                  sqlsrv_commit($conn);
                  echo 2;
              }
    }
  }
?>