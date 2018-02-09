<?php
	include "path.php";
	include ROOT_PATH . "php/configfolder/config.php";
    
    $con = new mysqli($ASEAN100_SERVER_HOST, $SERVER_USER, $SERVER_USER_PASSWORD, $DATABASE_NAME, $ASEAN100_SERVER_PORT, $ASEAN100_SERVER_SOCKET);

	if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
	}
    $con->query("SET time_zone='Asia/Singapore'");

    //get person data
    $query = "SELECT DosageName, DosageInventory FROM AseanDosageDetails";
    $inventoryResults = array();
	if ($stmt = $con->prepare($query)) {
		$stmt->execute();
		$stmt->store_result();
        $stmt->bind_result($dName, $dInvent);
        
		$count = $stmt->num_rows;
        
        while ($stmt->fetch()) {
			$inventoryResults[] = array('dosage_name'=>$dName, 'inventory'=>$dInvent);
		}
        
		if($count <= 0){
			exit('error');
		} else {
            echo(json_encode($inventoryResults));
        }
        $stmt->free_result();
		$stmt->close();
	} else {
		exit('server error: ' . mysqli_error($con));
	}

    $con -> close();

?>
