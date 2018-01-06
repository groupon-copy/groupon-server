<?php

class VerificationEmail {
    /* send verification email
    * @param $email email to send to
    * @param $verification_code
    * @return boolean whether email was sent or not
    */
    public static function send($email, $id, $verification_code) {
    	//email variable should be defined in script using this
	$to = $email;
	
	$subject = "Verification Code";
	
	//verification_code variable should be defined in script using this
	//$message = "<b>This is HTML message</b>";
	$message = '
	
	Thanks for signing up!
	Your account has been created!
	
	Please click this link to activate your account:
	http://www.chocolateguitar.com/android/groupon_slim_api_version/html/registration_verify_user.html?id=' . $id . '&verification_code=' . $verification_code; //our message above including the link
	
	//header = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	//header .= 'From: Administrator <info@problem.com >' . "\r\n";
	
	$header = "From:verification@somedomain.com \r\n";
	//header .= "Cc:abc@somedomain.com \r\n";
	//header .= "MIME-VERSION: 1.0 \r\n";
	//header .= "Content-type: text/html \r\n";
	
	ini_set('SMTP', "smtpout.secureserver.net");
	//ini_set('smtp_port', "25");
	
	$return_value = mail($to, $subject, $message, $header);
	
	if($return_value) {
	    return true;
	} else {
	    return false;
	}
    }
}

?>