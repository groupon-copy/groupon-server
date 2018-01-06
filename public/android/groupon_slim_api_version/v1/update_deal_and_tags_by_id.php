<?php
require_once '../include/Config.php';
require_once '../include/DbHandler.php';

//array for json response 
$response = array(); 

if (isset($_POST['id']) && isset($_POST['vendor_id']) && isset($_POST['bold_text']) && isset($_POST['img_rel_url']) && isset($_POST['fine_print_text']) && isset($_POST['highlight_text']) && isset($_POST['valid_from']) && isset($_POST['valid_until']) && isset($_POST['original_price']) && isset($_POST['current_price']) && isset($_POST['num_thumbs_up']) && isset($_POST['num_thumbs_down']) && isset($_POST['is_limited_availability']) && isset($_POST['is_limited_time_offer']) && isset($_POST['num_bought']) && isset($_POST['tag_list']))  {
    $id = $_POST['id'];

    $vendor_id = $_POST['vendor_id'];
    $bold_text = $_POST['bold_text'];
    $img_rel_url = $_POST['img_rel_url'];
    $highlight_text = $_POST['highlight_text'];
    $fine_print_text = $_POST['fine_print_text'];
    $original_price = $_POST['original_price'];
    $current_price = $_POST['current_price'];
    $num_bought = $_POST['num_bought'];
    $num_thumbs_up = $_POST['num_thumbs_up'];
    $num_thumbs_down = $_POST['num_thumbs_down'];
    $valid_from = $_POST['valid_from'];
    $valid_until = $_POST['valid_until'];
    $is_limited_time_offer = $_POST['is_limited_time_offer'];
    $is_limited_availability = $_POST['is_limited_availability'];
    
    $tag_list = $_POST['tag_list'];
   
    $db = new DbHandler();
    $res = $db->updateDeal($id, $vendor_id, $bold_text, $img_rel_url, $highlight_text, $fine_print_text, $original_price, $current_price, $num_bought, $num_thumbs_up, $num_thumbs_down, $valid_from, $valid_until, $is_limited_time_offer, $is_limited_availability);
    
    if($res["status_code"] == SUCCESS_EXECUTING_QUERY) {
        //remove all current tags of this deal by id
        $res = $db->deleteAllTagsOfDealID($id);
        
        if($res["status_code"] == SUCCESS_EXECUTING_QUERY) {
            //insert tags to newly created deal with aquired auto-incremented deal id
            $res = $db->insertTagsForDealID($id, $tag_list);
        
            if($res["status_code"] == SUCCESS_EXECUTING_QUERY) {
                $response["error"] = false;
                $response["status_code"] = SUCCESS_EXECUTING_QUERY;
                $response["message"] = "successfully executed database query";    
            } else if($res["status_code"] == ERROR_EXECUTING_QUERY) {
                $response["error"] = true;
                $response["status_code"] = ERROR_EXECUTING_QUERY;
                $response["message"] = "error executing database query: insert tags";
            }  
        } else if($res["status_code"] == ERROR_EXECUTING_QUERY) {
            $response["error"] = true;
            $response["status_code"] = ERROR_EXECUTING_QUERY;
            $response["message"] = "error executing database query: deleting tags";
        }
    } else if($res["status_code"] == ERROR_EXECUTING_QUERY) {
        $response["error"] = true;
        $response["status_code"] = ERROR_EXECUTING_QUERY;
        $response["message"] = "error executing database query: update deal";
    } else if($res["status_code"] == ERROR_BAD_DATE_TIME_FORMAT) {
		$response["error"] = true;
        $response["status_code"] = ERROR_BAD_DATE_TIME_FORMAT;
        $response["message"] = "error bad date time format";
	}
} else {
    $response["error"] = true;
    $response["status_code"] = ENTER_MISSING_VALUES;
    $response["message"] = "fill in missing values";
}

//output data
echo json_encode($response);

?>