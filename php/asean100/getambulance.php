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
    $query = "SELECT IoTID FROM AseanIoTDatabase WHERE IoTMessageValidated=0 LIMIT 1";
    $iID = 0;
	if ($stmt = $con->prepare($query)) {
		$stmt->execute();
		$stmt->store_result();
        $stmt->bind_result($id);
        
		$count = $stmt->num_rows;
        
        while ($stmt->fetch()) {
            $iID = $id;
		}
        
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

    //Set value used = 1
    $query1 = "UPDATE AseanIoTDatabase SET IoTMessageValidated=1 WHERE IoTID=?";
    if ($stmt1 = $con->prepare($query1)) {
        $stmt1->bind_param('i', $iID);
		$stmt1->execute();
		$stmt1->store_result();
        
		$count1 = $stmt1->affected_rows;
        
		if($count1 <= 0){
			exit('error' . $iID);
		}
        
        $stmt1->free_result();
		$stmt1->close();
	} else {
		exit('server error: ' . mysqli_error($con));
	}

    $con -> close();

?>
