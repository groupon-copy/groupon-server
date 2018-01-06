<?php

require_once '../include/Config.php';
require_once '../include/DbHandler.php';

$response = array();

if(isset($_POST['email']) && isset($_POST['password'])){
      //verify data
      $email = $_POST['email'];
      $password = $_POST['password'];
  
      $db = new DbHandler();
      $res = $db->loginUser($email, $password);
  
      if($res["status_code"] == LOGIN_SUCCESS) {
          $response["error"] = false;
          $response["status_code"] = LOGIN_SUCCESS;
          $response["id"] = $res["id"];
          $response["verification_code"] = $res["verification_code"];
          $response["username"] = $res["username"];
          $response["api_key"] = $res["api_key"];
          $response["avatar_img_rel_url"] = $res["avatar_img_rel_url"];
          $response["message"] = "login success";
          echo json_encode($response);
      } else if($res["status_code"] == PASSWORD_INCORRECT) {
          $response["error"] = true;
          $response["status_code"] = PASSWORD_INCORRECT;
          $response["message"] = "password incorrect";
          echo json_encode($response);
      } else if($res["status_code"] == EMAIL_DOES_NOT_EXIST) {
          $response["error"] = true;
          $response["status_code"] = EMAIL_DOES_NOT_EXIST;
          $response["message"] = "email does not exist";
          echo json_encode($response);
      } else if($res["status_code"] == USER_NEEDS_TO_BE_VERIFIED) {
          $response["error"] = true;
          $response["status_code"] = USER_NEEDS_TO_BE_VERIFIED;
          $response["message"] = "user needs to be verified before login";
          echo json_encode($response);
      }

}else{
    //invalid approach
    $response["error"] = true;
    $response["status_code"] = ENTER_MISSING_VALUES;
    $response["message"] = "enter missing values";
    echo json_encode($response);
}