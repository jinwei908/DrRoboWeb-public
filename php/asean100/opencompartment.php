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

    $compartment = isset($_GET['com']) ? (int)$_GET['com'] : -1;
    $value = 0;
    $pID = -1;
    $pPaid = 1;

    //get person data
    $query = "INSERT INTO AseanPatientKiosk(InteractionValue, ValueUsed, PatientID, PatientPaid, DateAdded) VALUES(?, ?, ?, ?, NOW())";
	if ($stmt = $con->prepare($query)) {
		$stmt->bind_param("iiii", $compartment, $value, $pID, $pPaid);
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

    $con -> close();

?>
