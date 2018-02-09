<?php
	include "path.php";
	include ROOT_PATH . "php/configfolder/config.php";
    
    $con = new mysqli($ASEAN100_SERVER_HOST, $SERVER_USER, $SERVER_USER_PASSWORD, $DATABASE_NAME, $ASEAN100_SERVER_PORT, $ASEAN100_SERVER_SOCKET);

	if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
	}
    $con->query("SET time_zone='Asia/Singapore'");
    date_default_timezone_set('Asia/Singapore');

    $userID = isset($_GET['uID']) ? (int)$_GET['uID'] : -1;
    $interactionID = isset($_GET['iID']) ? (int)$_GET['iID'] : -1;
    //paydosage
        
    //get person data
    $query = "UPDATE AseanPatientKiosk SET PatientPaid=1, DateAdded=NOW() WHERE PatientID=? AND InteractionID=?";
	if ($stmt = $con->prepare($query)) {
		$stmt->bind_param("ii", $userID, $interactionID);
		$stmt->execute();
		$stmt->store_result();
        
		$count = $stmt->affected_rows;
        
		if($count <= 0){
			exit('error');
		} else {
            echo('success');
        }
        $stmt->free_result();
		$stmt->close();
	} else {
		exit('server error: ' . mysqli_error($con));
	}

    $query1 = "SELECT paydosage(?, InteractionValue) FROM AseanPatientKiosk WHERE InteractionID=?";
	if ($stmt1 = $con->prepare($query1)) {
		$stmt1->bind_param("ii", $userID, $interactionID);
		$stmt1->execute();
		$stmt1->store_result();
        
		$count1 = $stmt1->affected_rows;
        
		if($count1    <= 0){
			exit('error');
		} else {
            echo('success');
        }
        $stmt1->free_result();
		$stmt1->close();
	} else {
		exit('server error: ' . mysqli_error($con));
	}

    $con -> close();

?>
