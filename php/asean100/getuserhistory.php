<?php
	include "path.php";
	include ROOT_PATH . "php/configfolder/config.php";
    
    $con = new mysqli($ASEAN100_SERVER_HOST, $SERVER_USER, $SERVER_USER_PASSWORD, $DATABASE_NAME, $ASEAN100_SERVER_PORT, $ASEAN100_SERVER_SOCKET);

	if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
	}
    $con->query("SET time_zone='Asia/Singapore'");
    
    $userID = isset($_GET['uID']) ? (int)$_GET['uID'] : -1;

    //get person data
    $query = "SELECT dosagetoname(InteractionValue), DateAdded FROM AseanPatientKiosk WHERE PatientID=? ORDER BY DateAdded DESC";
    $historyResults = array();
	if ($stmt = $con->prepare($query)) {
		$stmt->bind_param("i", $userID);
		$stmt->execute();
		$stmt->store_result();
        $stmt->bind_result($dName, $dAdded);
        
		$count = $stmt->num_rows;
        
        while ($stmt->fetch()) {
			$historyResults[] = array('dosage_name'=>$dName, 'date_added'=>$dAdded);
		}
        
		if($count <= 0){
			exit('error');
		} else {
            echo(json_encode($historyResults));
        }
        $stmt->free_result();
		$stmt->close();
	} else {
		exit('server error: ' . mysqli_error($con));
	}

    $con -> close();

?>
