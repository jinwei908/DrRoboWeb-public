<?php
	include "path.php";
	include ROOT_PATH . "php/configfolder/config.php";
    
    $con = new mysqli($ASEAN100_SERVER_HOST, $SERVER_USER, $SERVER_USER_PASSWORD, $DATABASE_NAME, $ASEAN100_SERVER_PORT, $ASEAN100_SERVER_SOCKET);

	if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
	}
    $con->query("SET time_zone='Asia/Singapore'");

    $userNRIC = isset($_GET['nric']) ? $_GET['nric'] : '';
    //get person data
    $query = "SELECT ID, PatientName, PatientNRIC,FundsAvailable, ImageURL, getpatientmedication(ID), NOW() FROM AseanHackPatients WHERE PatientNRIC = ? LIMIT 1";
	if ($stmt = $con->prepare($query)) {
		$stmt->bind_param("s", $userNRIC);
		$stmt->execute();
		$stmt->store_result();
        $stmt->bind_result($id, $pName, $pNRIC, $fAvail, $iURL, $patientMed, $dateMod);
        
		$count = $stmt->affected_rows;
		$count = $stmt->num_rows;
        
        while ($stmt->fetch()) {
			$personResult = array('ID'=>$id, 'personName'=>$pName, 'personNRIC'=>$pNRIC, 'funds'=>$fAvail, 'image'=>$iURL, 'patientMed'=>$patientMed, 'dateMod' => $dateMod);
		}
        
		if($count <= 0){
			exit('error');
		} else {
            echo(json_encode($personResult));
        }
        $stmt->free_result();
		$stmt->close();
	} else {
		exit('server error: ' . mysqli_error($con));
	}

    $con -> close();

?>
