<?php

require_once '../include/Config.php';
require_once '../include/DbHandler.php';

$response = array(); 
   
$db = new DbHandler();
$res = $db->getAllDeals();

if($res["status_code"] == SUCCESS_EXECUTING_QUERY) {
    $response["error"] = false;
    $response["status_code"] = SUCCESS_EXECUTING_QUERY;
    $response["message"] = "successfully executed database query";
    $response["deals"] = $res["deals"];
} else if($res["status_code"] == ERROR_EXECUTING_QUERY) {
    $response["error"] = true;
    $response["status_code"] = ERROR_EXECUTING_QUERY;
    $response["message"] = "error executing database query";
}

//output data in json format
echo json_encode($response);

?>