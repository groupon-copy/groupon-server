<?php

require_once '../include/Config.php';
require_once '../include/DbHandler.php';

$response = array(); 

if(isset($_POST['tag_list'])) {
    $tag_list = $_POST['tag_list'];
   
    $db = new DbHandler();
    $res = $db->getDealsByTagList($tag_list);
    
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
} else {
    $response["error"] = true;
    $response["status_code"] = ENTER_MISSING_VALUES;
    $response["message"] = "fill in missing values";
}

//output data
echo json_encode($response);

?>