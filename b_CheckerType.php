
<?php 
    require 'connection.php';
    $data = json_decode(file_get_contents('php://input'));
    if ( sqlsrv_begin_transaction( $conn ) === false ) {
    die( print_r( sqlsrv_errors(), true ));
    } 
        if(isset($data))
        {
        $CheckerTypeID=$data->CheckerTypeID;
        $CheckerType=$data->CheckerType;
        $UserID=$data->UserID;
        //Query 
        $sql = "EXEC [dbo].[CheckerTypes]
        @CheckerTypeID = ?,
        @CheckerType = ?,
        @UserID = ?";
        $params = array($CheckerTypeID,$CheckerType, $UserID);
        $stmt = sqlsrv_query($conn, $sql, $params);
        // var_dump($stmt);
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
        {
            sqlsrv_commit($conn);
            echo $row['result']; 
        }
        }
?>
