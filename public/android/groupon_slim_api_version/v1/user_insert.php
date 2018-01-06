<?php

require_once '../include/Config.php';
require_once '../include/DbHandler.php';
require_once '../include/VerificationEmail.php';

$response = array();

if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
   
    $db = new DbHandler();
    $res = $db->createUser($username, $email, $password);
        
    if ($res["status_code"] == USER_CREATED_SUCCESSFULLY) { 
        //get the auto-incremented id
        $id = $res["id"];
    
        //get the verification_code
        $verification_code = $res["verification_code"];    
    
        //create a unique file_name by using the auto-incremented primary id
        $file_name = $id . ".jpg";
        
        //update avatar_img_rel_url into user row
        $res = $db->updateAvatarImgRelURL($id, $file_name);
         
        //if no error updating avatar_img_rel_url, try to send verification email    
        if($res) {
            //send verification email
            $res = VerificationEmail::send($email, $id, $verification_code);
        
            if($res) {
                $response["error"] = false;
                $response["status_code"] = USER_CREATED_EMAIL_SENT;
                $response["message"] = "registered and verification email sent";
                echo json_encode($response);
            } else {
                $response["error"] = true;
                $response["status_code"] = USER_CREATED_EMAIL_NOT_SENT;
                $response["message"] = "registered but failed to send verification email";
                echo json_encode($response);
            }
        } else {
            $response["error"] = true;
            $response["status_code"] = USER_CREATED_AND_UPDATE_FAILED;
            $response["message"] = "update avatar_img_rel_url failed";
            echo json_encode($response);
        }
    } else if ($res["status_code"] == USER_CREATE_FAILED) {
        $response["error"] = true;
        $response["status_code"] = USER_CREATE_FAILED;
        $response["message"] = "error occurred inserting user";
        echo json_encode($response);
    } else if ($res["status_code"] == USER_EMAIL_ALREADY_EXISTS) {
        //check if user has been activated by email
        $res = $db->isUserActivatedByEmail($email);
        
        if($res) {
            $response["error"] = true;
            $response["status_code"] = USER_EMAIL_ALREADY_EXISTS_AND_ACTIVATED;
            $response["message"] = "email already exists in database, and has been activated";
            echo json_encode($response);
        } else {
            $response["error"] = true;
            $response["status_code"] = USER_EMAIL_ALREADY_EXISTS_AND_NOT_ACTIVATED;
            $response["message"] = "email already exists in database, but needs to be verified";
            echo json_encode($response);
        }
    } else if ($res["status_code"] == USER_USERNAME_ALREADY_EXISTS) {
        $response["error"] = true;
        $response["status_code"] = USER_USERNAME_ALREADY_EXISTS;
        $response["message"] = "username already exists in database";
        echo json_encode($response);
    }
} else {
    $response["error"] = true;
    $response["status_code"] = ENTER_MISSING_VALUES;
    $response["message"] = "fill in missing values";
    echo json_encode($response);
}

?>