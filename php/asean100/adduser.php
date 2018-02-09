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

    $userNRIC = isset($_GET['nric']) ? $_GET['nric'] : '';
    $userName = isset($_GET['name']) ? $_GET['name'] : '';
    $userImage = "http://res.cloudinary.com/www-reversethatshell-com/image/upload/c_scale,h_172,w_150/v1506853451/android_logo_PNG32_ojg7rl.png";
    $userFunds = 150.0;

    //get person data
    $query = "INSERT INTO AseanHackPatients (PatientName, PatientNRIC, FundsAvailable, ImageURL, DateModified) VALUES (?, ?, ?, ?, NOW())";
	if ($stmt = $con->prepare($query)) {
		$stmt->bind_param("ssds", $userName, $userNRIC, $userFunds, $userImage);
		$stmt->execute();
		$stmt->store_result();
        
		$count = $stmt->affected_rows;
        
		if($count <= 0){
			exit('error');
		} else {
            $personResult = array('ID'=> mysqli_insert_id($con), 'personName'=>$userName, 'personNRIC'=>$userNRIC, 'funds'=>$userFunds, 'image'=>$userImage, 'dateMod' => getdate());
            echo(json_encode($personResult));
        }
        $stmt->free_result();
		$stmt->close();
	} else {
		exit('server error: ' . mysqli_error($con));
	}

    $con -> close();

?>
