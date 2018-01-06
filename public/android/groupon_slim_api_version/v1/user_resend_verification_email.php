<?php

require_once '../include/Config.php';
require_once '../include/DbHandler.php';
require_once '../include/VerificationEmail.php';

$response = array();

if(isset($_POST['email'])){
      //verify data
      $email = $_POST['email'];
  
      $db = new DbHandler();
      $res = $db->getIdAndVerificationCode($email);
  
      if($res["status_code"] == USER_EMAIL_ALREADY_EXISTS) {
          //send verification email
          $res = VerificationEmail::send($email, $res["id"], $res["verification_code"]);
        
          if($res) {
              $response["error"] = false;
              $response["status_code"] = EMAIL_SENT;
              $response["message"] = "verification email sent";
              echo json_encode($response);
          } else {
              $response["error"] = true;
              $response["status_code"] = EMAIL_NOT_SENT;
              $response["message"] = "failed to send verification email";
              echo json_encode($response);
          }
      } else if($res["status_code"] == EMAIL_DOES_NOT_EXIST) {
          $response["error"] = true;
          $response["status_code"] = EMAIL_DOES_NOT_EXIST;
          $response["message"] = "email does not exist in database";
          echo json_encode($response);
      } else if($res["status_code"] == USER_ALREADY_VERIFIED) {
          $response["error"] = true;
          $response["status_code"] = USER_ALREADY_VERIFIED;
          $response["message"] = "user account has already been verified";
          echo json_encode($response);
      }

}else{
    //invalid approach
    $response["error"] = true;
    $response["status_code"] = ENTER_MISSING_VALUES;
    $response["message"] = "enter missing values";
    echo json_encode($response);
}