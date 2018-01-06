<?php

require_once __DIR__ . '/../include/Config.php';
require_once __DIR__ . '/../include/DbHandler.php';
require_once __DIR__ . '/../libraries/firebase/fcm.php';
require_once __DIR__ . '/../libraries/firebase/push.php';

//array to return
$response = array(); 
   
if(isset($_POST['id'])) {
	$db = new DbHandler();
	$res = $db->getAllFirebaseRegistrationTokens();
	
	if($res["status_code"] == SUCCESS_EXECUTING_QUERY) {
	    $registration_tokens = $res["registration_tokens"];
	    $id = $_POST['id'];
	
	    $fcm = new FCM();
	    $push = new Push();
	    
	    //this is the data payload
	    $data = array();
	    $data['data_type'] = GROUPON_NOTIFICATION;
	    $data['id'] = $id;

	    $push->setTitle("\"Groupon Notification\"");
	    $push->setData($data);
	    
	    //send push message to single user
	    $result = $fcm->sendMultiple($registration_tokens, $push->getPush());
	    
	    echo $result;
	} else if($res["status_code"] == ERROR_EXECUTING_QUERY) {
	    $response["error"] = true;
	    $response["status_code"] = ERROR_EXECUTING_QUERY;
	    $response["message"] = "error executing database query: get all firebase registration tokens";
	    echo json_encode($response);
	}
} else {
    $response["error"] = true;
    $response["status_code"] = ENTER_MISSING_VALUES;
    $response["message"] = "fill in missing values";
    echo json_encode($response);
}

?>