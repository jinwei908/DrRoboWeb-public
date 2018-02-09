<?php

    include "path.php";
	include ROOT_PATH . "php/configfolder/config.php";
    include ROOT_PATH . "php/aws_php/src/Message.php";
    include ROOT_PATH . "php/aws_php/src/MessageValidator.php";
    include ROOT_PATH . "php/aws_php/src/Exception/InvalidSnsMessageException.php";
    
    use Aws\Sns\Message;
    use Aws\Sns\MessageValidator;
    use Aws\Sns\Exception\InvalidSnsMessageException;

    // Instantiate the Message and Validator
    $message = Message::fromRawPostData();
    $validator = new MessageValidator();

    // Validate the message and log errors if invalid.
    try {
       $validator->validate($message);
    } catch (InvalidSnsMessageException $e) {
       // Pretend we're not here if the message is invalid.
       http_response_code(404);
       error_log('SNS Message Validation Error: ' . $e->getMessage());
       die();
    }

    // Check the type of the message and handle the subscription.
    if ($message['Type'] === 'SubscriptionConfirmation') {
       // Confirm the subscription by sending a GET request to the SubscribeURL
       file_get_contents($message['SubscribeURL']);
    }

    if ($message['Type'] === 'Notification') {
       // Do whatever you want with the message body and data.
       $iMessage = $message['Message'];
    }
    

    if(!empty($iMessage)){
        $con = new mysqli($ASEAN100_SERVER_HOST, $SERVER_USER, $SERVER_USER_PASSWORD, $DATABASE_NAME, $ASEAN100_SERVER_PORT, $ASEAN100_SERVER_SOCKET);

        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
        $con->query("SET time_zone='Asia/Singapore'");

        //get person data
        $query = "INSERT INTO AseanIoTDatabase(IoTMessage, DateAdded) VALUES(?, NOW())";
        $inventoryResults = array();
        if ($stmt = $con->prepare($query)) {
            $stmt->bind_param("s", $iMessage);
            $stmt->execute();
            $stmt->store_result();

            $stmt->free_result();
            $stmt->close();
        } else {
            exit('server error: ' . mysqli_error($con));
        }

        $con -> close();
    }

?>
