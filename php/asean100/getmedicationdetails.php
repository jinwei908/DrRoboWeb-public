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

    $userID = isset($_GET['id']) ? (int)$_GET['id'] : -1;

    //get person data
    $query = "SELECT InteractionID, dosagetoname(InteractionValue), dosagetoprice(InteractionValue), dosagetoinstruction(InteractionValue) FROM AseanPatientKiosk WHERE PatientID=? AND PatientPaid=0";
    $medicationResults = array();
	if ($stmt = $con->prepare($query)) {
		$stmt->bind_param("i", $userID);
		$stmt->execute();
		$stmt->store_result();
        $stmt->bind_result($id, $dName, $dPrice, $dIns);
        
        
		$count = $stmt->affected_rows;
        while($stmt->fetch()){
            $medicationResults[] = array('ID'=> $id, 'dosage_name'=>$dName, 'dosage_price'=>$dPrice, 'dosage_instructions'=>$dIns);
        }
		if($count <= 0){
			exit('error');
		} else {
            echo(json_encode($medicationResults));
        }
        $stmt->free_result();
		$stmt->close();
	} else {
		exit('server error: ' . mysqli_error($con));
	}

    $con -> close();

?>
