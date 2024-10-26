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
                 cp.ContractPerformaID
                ,cp.ContractNo
                ,cp.Quantity
                ,cp.EstimatedContainer
                ,cp.Packaging
                ,cp.PackedInID
                ,pki.PackedIn
                ,cp.RawMaterialID
                ,rm.RawMaterial
                ,cp.SupplierID
                ,s.Supplier
                ,cp.SupplierAddress
                ,cp.PortOfDischargeID
                ,pod.PortOfDischarge
                ,cp.FromShipmentPeriod
                ,cp.ToShipmentPeriod
                ,cp.CountryOfOrigin
                ,cp.UnitPrice
                ,cp.Status
                FROM ContractPerforma cp 
                LEFT JOIN PackedIn pki ON cp.PackedInID = pki.PackedInID
                LEFT JOIN RawMaterial rm ON cp.RawMaterialID = rm.RawMaterialID
                LEFT JOIN Supplier s ON cp.SupplierID = s.SupplierID
                LEFT JOIN PortOfDischarge pod ON cp.PortOfDischargeID = pod.PortOfDischargeID
                WHERE cp.isDel = 'False'";
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