<?php

//array for json response 
$response = array(); 

if (isset($_POST['registration_token']) && isset($_POST['message']))  {
    $registration_token = $_POST['registration_token'];
    $message = $_POST['message'];

    require_once __DIR__ . '/../libraries/firebase/fcm.php';
    require_once __DIR__ . '/../libraries/firebase/push.php';

    $fcm = new FCM();
    $push = new Push();
    
    $data = array();
    $data['user'] = $user;
    $data['message'] = $message;
    $data['image'] = '';
    
    $push->setTitle("Cloud Messaging");
    $push->setData($data);
    
    //send push message to single user
    $result = $fcm->send($registration_token, $push->getPush());
    
    $response["result"] = $result;
} else {
    $response["error"] = true;
    $response["status_code"] = ENTER_MISSING_VALUES;
    $response["message"] = "fill in missing values";
}

//output data
echo json_encode($response);

?>