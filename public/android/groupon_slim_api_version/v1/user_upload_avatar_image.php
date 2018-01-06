<?php

require_once '../include/Config.php';
require_once '../include/DbHandler.php';

$response = array();

if(isset($_POST['id']) && isset($_POST['api_key']) && isset($_POST['avatar_image'])) {
    $id = $_POST['id'];
    $api_key = $_POST['api_key'];
    
    //validate email address
   
    $db = new DbHandler();
    $res = $db->doesIdAndApiKeyExist($id, $api_key);
    
    //if found match
    if ($res) {
        //decode image from string to image format
        $img_src = base64_decode($_POST['avatar_image']);
        
        //file name is its id plus .jpg
    	$file_name = $id . ".jpg";
    	
    	//open file at specified destination to write
	$file = fopen(USER_AVATAR_IMAGE_DIR_PATH . $file_name, 'w');
	
	//write image into file to where it belongs
	fwrite($file, $img_src);
	
	//if file write success
        if(fclose($file)) {
            //No need to update avatar_img_rel_url because the filename will never change
            //insert image rel url into user row
            //$res = $db->updateAvatarImgRelURL($id, $file_name);
            
            //if($res) {
                $response["error"] = false;
                $response["status_code"] = IMG_UPLOADED_UPDATE_SUCCESS;
                $response["message"] = "image has been uploaded and update avatar_img_rel_url success";
                echo json_encode($response);
            //} else {
            //    $response["error"] = true;
            //   $response["status_code"] = IMG_UPLOADED_UPDATE_FAILED;
            //    $response["message"] = "image has been uploaded but server update avatar_img_rel_url failed";
            //    echo json_encode($response);
            //}
        } else {
            $response["error"] = true;
            $response["status_code"] = ERROR_UPLOADING_IMAGE;
            $response["message"] = "error uploading image";
            echo json_encode($response);
        }
    } else {
        $response["error"] = true;
        $response["status_code"] = FAILED_AUTHENTICATION;
        $response["message"] = "no rows matches both id and api_key";
        echo json_encode($response);
    }
} else {
    $response["error"] = true;
    $response["status_code"] = ENTER_MISSING_VALUES;
    $response["message"] = "fill in missing values";
    echo json_encode($response);
}

?>