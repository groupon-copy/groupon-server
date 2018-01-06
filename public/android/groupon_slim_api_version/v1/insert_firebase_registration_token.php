<?php

require_once '../include/Config.php';
require_once '../include/DbHandler.php';

//array for json response 
$response = array(); 

if (isset($_POST['registration_token']))  {
    $registration_token = $_POST['registration_token'];
   
    $db = new DbHandler();
    $res = $db->insertFirebaseRegistrationToken($registration_token);
    
    if($res["status_code"] == SUCCESS_EXECUTING_QUERY) {
        $response["error"] = false;
        $response["status_code"] = SUCCESS_EXECUTING_QUERY;
        $response["id"] = $res["id"];
        $response["message"] = "successfully executed database query";    
    } else if($res["status_code"] == ERROR_EXECUTING_QUERY) {
        $response["error"] = true;
        $response["status_code"] = ERROR_EXECUTING_QUERY;
        $response["message"] = "error executing database query: insert firebase registration token";
    }
} else {
    $response["error"] = true;
    $response["status_code"] = ENTER_MISSING_VALUES;
    $response["message"] = "fill in missing values";
}

//output data
echo json_encode($response);

?>