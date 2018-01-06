<?php
require_once '../include/Config.php';
require_once '../include/DbHandler.php';

//array for json response 
$response = array(); 

if (isset($_POST['id']) && isset($_POST['vendor_name']) && isset($_POST['account_num']) && isset($_POST['addr_1']) && isset($_POST['addr_2']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['zip']) && isset($_POST['country_code']) && isset($_POST['price_range']) && isset($_POST['vendor_website']) && isset($_POST['num_thumbs_up']) && isset($_POST['num_thumbs_down']) && isset($_POST['image_rel_url']) && isset($_POST['description']) && isset($_POST['hours']) && isset($_POST['phone_1']) && isset($_POST['email']))  {
    $id = $_POST['id'];
    
    $vendor_name = $_POST['vendor_name'];
    $account_num = $_POST['account_num'];
    $addr_1 = $_POST['addr_1'];
    $addr_2 = $_POST['addr_2'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $country_code = $_POST['country_code'];
    $price_range = $_POST['price_range'];
    $vendor_website = $_POST['vendor_website'];
    $num_thumbs_up = $_POST['num_thumbs_up'];
    $num_thumbs_down = $_POST['num_thumbs_down'];
    $image_rel_url = $_POST['image_rel_url'];
    $description = $_POST['description'];
    $hours = $_POST['hours'];
    $phone_1 = $_POST['phone_1'];
    $email = $_POST['email'];
   
    $db = new DbHandler();
    $res = $db->updateVendor($id, $vendor_name, $account_num, $addr_1, $addr_2, $city, $state, $zip, $country_code, $price_range, $vendor_website, $num_thumbs_up, $num_thumbs_down, $image_rel_url,  $description, $hours, $phone_1, $email);
    
    if($res["status_code"] == SUCCESS_EXECUTING_QUERY) {
        $response["error"] = false;
        $response["status_code"] = SUCCESS_EXECUTING_QUERY;
        $response["message"] = "successfully executed database query";  
    } else if($res["status_code"] == ERROR_EXECUTING_QUERY) {
        $response["error"] = true;
        $response["status_code"] = ERROR_EXECUTING_QUERY;
        $response["message"] = "error executing database query: update vendor";
    }
} else {
    $response["error"] = true;
    $response["status_code"] = ENTER_MISSING_VALUES;
    $response["message"] = "fill in missing values";
}

//output data
echo json_encode($response);

?>