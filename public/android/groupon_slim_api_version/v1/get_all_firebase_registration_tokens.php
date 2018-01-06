<?php

require_once '../include/Config.php';
require_once '../include/DbHandler.php';

$response = array(); 
   
$db = new DbHandler();
$res = $db->getAllFirebaseRegistrationTokens();


if($res["status_code"] == SUCCESS_EXECUTING_QUERY) {
    $response["error"] = false;
    $response["status_code"] = SUCCESS_EXECUTING_QUERY;
    $response["message"] = "successfully executed database query";
    $response["registration_tokens"] = $res["registration_tokens"];
} else if($res["status_code"] == ERROR_EXECUTING_QUERY) {
    $response["error"] = true;
    $response["status_code"] = ERROR_EXECUTING_QUERY;
    $response["message"] = "error executing database query: get all firebase registration tokens";
}

//output data
echo json_encode($response);

?>