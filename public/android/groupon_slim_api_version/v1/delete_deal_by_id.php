<?php

require_once '../include/Config.php';
require_once '../include/DbHandler.php';

$response = array(); 

if(isset($_POST['id'])) {
    $id = $_POST['id'];
   
    $db = new DbHandler();
    $res = $db->deleteDealByID($id);
    
    if(true) {
        $response["error"] = false;
        $response["status_code"] = SUCCESS_EXECUTING_QUERY;
        $response["message"] = "successfully executed database query";
    } else {
        $response["error"] = true;
        $response["status_code"] = ERROR_EXECUTING_QUERY;
        $response["message"] = "error executing database query";
    }
} else {
    $response["error"] = true;
    $response["status_code"] = ENTER_MISSING_VALUES;
    $response["message"] = "fill in missing values";
}

//output data
echo json_encode($response);

?>