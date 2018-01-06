<?php

require_once '../include/DbHandler.php';

$response = array();

if(isset($_POST['id']) && isset($_POST['verification_code'])){
      //verify data
      $id = $_POST['id'];
      $verification_code = $_POST['verification_code'];
  
      $db = new DbHandler();
      $res = $db->verifyUser($id, $verification_code);
  
      if($res == USER_IS_NOW_VERIFIED) {
          echo '<div class="statusmsg">Your account has been activated, you can now login</div>';
      } else if($res == USER_VERIFY_FAILED) {
          echo '<div class="statusmsg">server has problems updating user row</div>';
      } else if($res == VERIFICATION_CODE_DOES_NOT_EXIST) {
          echo '<div class="statusmsg">invalid verification code and id</div>';
      } else if($res == USER_ALREADY_ACTIVATED) {
          echo '<div class="statusmsg">Account has already been activated</div>';
      }

} else {
    //invalid approach
    echo '<div class="statusmsg">Invalid url, missing fields</div>';
}