<?php
require_once '../include/Config.php';
require_once '../include/DbHandler.php';

//array for json response 
$response = array(); 

if (isset($_POST['id']) && isset($_POST['tag_list']))  {
    $id = $_POST['id'];
    $tag_list = $_POST['tag_list'];
   
    $db = new DbHandler();
    $res = $db->insertTagsForDealID($id, $tag_list);
    
    if($res["status_code"] == SUCCESS_EXECUTING_QUERY) {
        $response["error"] = false;
        $response["status_code"] = SUCCESS_EXECUTING_QUERY;
        $response["message"] = "successfully executed database query";
    } else if($res["status_code"] == ERROR_EXECUTING_QUERY) {
        $response["error"] = true;
        $response["status_code"] = ERROR_EXECUTING_QUERY;
        $response["message"] = "error executing database query: insert deal";
    }
} else {
    $response["error"] = true;
    $response["status_code"] = ENTER_MISSING_VALUES;
    $response["message"] = "fill in missing values";
}

//output data
echo json_encode($response);

?>