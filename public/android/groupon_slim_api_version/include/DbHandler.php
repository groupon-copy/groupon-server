<?php

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Marcus Chiu
 */
class DbHandler {
     private $connection;
     
     function __construct() {
         require_once dirname(__FILE__) . '/DbConnect.php';
         //opening db connection
         $db = new DbConnect();
         $this->connection = $db->connect();
     }
     
     /**
      * @param String $username User full name
      * @param String $email
      * @param String $password
      * @return array $response
      */
     public function createUser($username, $email, $password) {
         require_once 'PasswordHash.php';
         $response = array();
         
         // First check if user already exists in database
         if(!$this->isEmailExists($email)) {
             if(!$this->isUsernameExists($username)) {
                 //generating password hash
                 //$password_hash = PasswordHash::hash($password);
             
                 // generate API key
                 $api_key = $this->generateApiKey();   
             
                 // generate Verification Code
                 $verification_code = $this->generateVerificationCode();     
             
                 // insert query
                 $stmt = $this->connection->prepare("INSERT INTO users(username, email, password, api_key, verification_code) values(?, ?, ?, ?, ?)");
                 $stmt->bind_param("sssss", $username, $email, $password, $api_key, $verification_code);
 
                 $result = $stmt->execute();
 
                 $stmt->close();
             
                 //Check for successful insertion
                 if($result) {
                     //user successfully inserted
                     $response["status_code"] = USER_CREATED_SUCCESSFULLY;
                     $response["verification_code"] = $verification_code;
                     $response["id"] = $this->connection->insert_id; //returns auto incremented id
             	     return $response;
                 } else {
                     //failed to create user
                     $response["status_code"] = USER_CREATE_FAILED;
                     return $response;
                 }
             } else {
                 //user with same username already exists in database
                 $response["status_code"] = USER_USERNAME_ALREADY_EXISTS;
                 return $response;
             }
         } else {
             //user with same email already exists in database
             $response["status_code"] = USER_EMAIL_ALREADY_EXISTS;
             return $response;
         }
     }
     
     /**
      * @param String $id
      * @param String $verification_code
      * @return array $response
      */
     public function verifyUser($id, $verification_code) {
         if($this->isIdAndVerificationCodeExists($id, $verification_code)) {
             if(!$this->isUserActivated($id)) {
                 //we have a match, now activate user account
                 $stmt = $this->connection->prepare("UPDATE users SET active='1' WHERE id = ?");
                 $stmt->bind_param("i", $id);
                 $result = $stmt->execute();
                 $stmt->close();
             
                 if($result) {
             	     return USER_IS_NOW_VERIFIED;
                 } else {
                     return USER_VERIFY_FAILED;
                 }
             } else {
                 return USER_ALREADY_ACTIVATED;
             }
         } else {
             return VERIFICATION_CODE_DOES_NOT_EXIST;
         }
     }
     
     /**
      * @param String $email used to fetch row
      * @param String $password used to compare password from row
      * @return array $response returns details of user
      */
     public function loginUser($email, $password) {
         //array to return
         $response = array();
         
         //fetch user by email
         $stmt = $this->connection->prepare("SELECT id, password, username, verification_code, active, api_key, avatar_img_rel_url FROM users WHERE email = ?");
         $stmt->bind_param("s", $email);
         $stmt->execute();
         $stmt->bind_result($id, $fetched_password, $username, $verification_code, $active, $api_key, $avatar_img_rel_url);
         $stmt->store_result();
         
         //check if row exists
         if($stmt->num_rows > 0) {
             $stmt->fetch();
             $stmt->close();
             //found user with email
             //now verify the password
             if($password == $fetched_password) {
                 //now verify if user account is activated
                 if($active == 1) {
                     $response["status_code"] = LOGIN_SUCCESS;
                     $response["id"] = $id;
                     $response["verification_code"] = $verification_code;
                     $response["username"] = $username;
                     $response["api_key"] = $api_key;
                     $response["avatar_img_rel_url"] = $avatar_img_rel_url;
                     return $response;
                 } else {
                     $response["status_code"] = USER_NEEDS_TO_BE_VERIFIED;
                     return $response;
                 }
             } else {
                 $response["status_code"] = PASSWORD_INCORRECT;
                 return $response;
             }
         } else {
             $response["status_code"] = EMAIL_DOES_NOT_EXIST;
             return $response;
         }
     }
     
     /**
      * Get primary id and verification_code by email and password
      * @param String $email
      * @return array $response 
      */
     public function getIdAndVerificationCode($email) {
         //array to return
         $response = array();
         
         //fetch user by email
         $stmt = $this->connection->prepare("SELECT id, verification_code, active FROM users WHERE email = ?");
         $stmt->bind_param("s", $email);
         $stmt->execute();
         $stmt->bind_result($id, $verification_code, $active);
         $stmt->store_result();
         
         //check if row exists
         if($stmt->num_rows > 0) {
             $stmt->fetch();
             $stmt->close();
             
             if($active != 1) {
                 $response["status_code"] = USER_EMAIL_ALREADY_EXISTS;
                 $response["id"] = $id;
                 $response["verification_code"] = $verification_code;
                 return $response;
             } else {
                 $response["status_code"] = USER_ALREADY_VERIFIED;
                 return $response;
             }
         } else {
             $response["status_code"] = EMAIL_DOES_NOT_EXIST;
             return $response;
         }
     }
     
     public function doesIdAndApiKeyExist($id, $api_key) {
         //fetch user by email
         $stmt = $this->connection->prepare("SELECT id FROM users WHERE id = ? AND api_key = ?");
         $stmt->bind_param("is", $id, $api_key);
         $stmt->execute();
         $stmt->store_result();
         
         //check if row exists
         if($stmt->num_rows > 0) {
             return true;
         } else {
             return false;
         }
     }
     
     /** 
      * @param $id is the primary id 
      * @param $avatar_img_rel_url is the new thing to set
      * @return true if success otherwise false
      */
     public function updateAvatarImgRelURL($id, $avatar_img_rel_url) {
         //we have a match, now activate user account
         $stmt = $this->connection->prepare("UPDATE users SET avatar_img_rel_url = ? WHERE id = ?");
         $stmt->bind_param("si", $avatar_img_rel_url, $id);
         $result = $stmt->execute();
         $stmt->close();
     
         if($result) {
     	     return true;
         } else {
             return false;
         }
     }
     
     public function getAllDeals() {
         //array to be returned
         $response = array();
         
         //create query and execute it
	 $qstring = "SELECT * FROM deals";
	 $result = mysqli_query($this->connection, $qstring);
         
         if($result) {
             $response["status_code"] = SUCCESS_EXECUTING_QUERY; 
             
             if (mysqli_num_rows($result) > 0) {
		 $response["deals"] = array();
	 
		 while ($row = mysqli_fetch_array($result)){
			 $deal = array();
			 
			 $deal["id"] = $row["id"];
			 $deal["vendor_id"] = $row["vendor_id"];
			 $deal["bold_text"] = $row["bold_text"];
			 $deal["fine_print_text"] = $row["fine_print_text"];
			 $deal["img_rel_url"] = $row["img_rel_url"];
			 $deal["highlight_text"] = $row["highlight_text"];
			 $deal["valid_from"] = $row["valid_from"];
			 $deal["valid_until"] = $row["valid_until"];
			 $deal["original_price"] = $row["original_price"];
			 $deal["current_price"] = $row["current_price"];
			 $deal["num_bought"] = $row["num_bought"];
			 $deal["num_thumbs_up"] = $row["num_thumbs_up"];
			 $deal["num_thumbs_down"] = $row["num_thumbs_down"];
			 $deal["is_limited_time_offer"] = $row["is_limited_time_offer"];
			 $deal["is_limited_availability"] = $row["is_limited_availability"];
		
		 	array_push($response["deals"], $deal);
	 	 }
	 
	 	 return $response;
 	     } else {
	         $response["deals"] = array();
	         return $response;
 	     }
         } else {
             $response["status_code"] = ERROR_EXECUTING_QUERY; 
	     return $response;
         }
     }
     
     public function getDealByID($id) {
         //array to return
         $response = array();
         
         //fetch user by email
         $stmt = $this->connection->prepare("SELECT id, vendor_id, bold_text, fine_print_text, img_rel_url, highlight_text, valid_from, valid_until, original_price, current_price, num_bought, num_thumbs_up, num_thumbs_down, is_limited_time_offer, is_limited_availability FROM deals WHERE id = ?");
         $stmt->bind_param("i", $id);
         $result = $stmt->execute();
         $stmt->bind_result($fetched_id, $vendor_id, $bold_text, $fine_print_text, $img_rel_url, $highlight_text, $valid_from, $valid_until, $original_price, $current_price, $num_bought, $num_thumbs_up, $num_thumbs_down, $is_limited_time_offer, $is_limited_availability);
         $stmt->store_result();
         $num_rows = $stmt->num_rows;
         $stmt->fetch();
         $stmt->close();
         
         //check for successful query 
         if($result) {     
             $response["status_code"] = SUCCESS_EXECUTING_QUERY;  
         
             //check if row exists
             if($num_rows > 0) {
                 $vendor = array();
		 $deal["id"] = $fetched_id;
	         $deal["vendor_id"] = $vendor_id;
		 $deal["bold_text"] = $bold_text;
		 $deal["fine_print_text"] = $fine_print_text;
		 $deal["img_rel_url"] = $img_rel_url;
		 $deal["highlight_text"] = $highlight_text;
		 $deal["valid_from"] = $valid_from;
		 $deal["valid_until"] = $valid_until;
	         $deal["original_price"] = $original_price;
		 $deal["current_price"] = $current_price;
		 $deal["num_bought"] = $num_bought;
		 $deal["num_thumbs_up"] = $num_thumbs_up;
		 $deal["num_thumbs_down"] = $num_thumbs_down;
		 $deal["is_limited_time_offer"] = $is_limited_time_offer;
		 $deal["is_limited_availability"] = $is_limited_availability;
               		
		 $response["deal"] = array();
		 array_push($response["deal"], $deal);
		 
		 $response["status_code"] = SUCCESS_EXECUTING_QUERY;  
		 
		 return $response;
             } else { 
                 $response["deal"] = array();
                 return $response;
             }
         } else {
             $response["status_code"] = ERROR_EXECUTING_QUERY; 
	     return $response;
         }
     }
     
     /** 
      * Needs to make it safer with prepared statments
      * @return $response an array of deals and its deals info
      */
     public function getDealsByTagList($tag_list) {
         //response array 
         $response = array();
         
         $taglist = preg_split("/,/",$_POST['tag_list']);
	 $clause = "";
	 $first = true;
	 
	 foreach($taglist as $i){
	 	if($first){
	 		$clause .= "AND (T.tag = '$i'";
	 		$first = false;
	 	} else {
	 		$clause .= " OR T.tag = '$i'";
	 	}
	 }
	 if (strlen($clause) > 0){
	 	$clause .= ")";
	 }
	 
	 //create query and execute it
	 $qstring = "SELECT DISTINCT D.* FROM deals D, deal_tag T WHERE D.id = T.deal_id $clause";
	 $result = mysqli_query($this->connection, $qstring);
	 
	 if($result) {
	     $response["status_code"] = SUCCESS_EXECUTING_QUERY; 
	     
	     if (mysqli_num_rows($result) > 0){
	         $response["deals"] = array();
		 
	         while ($row = mysqli_fetch_array($result)){
                     $deal = array();
		     $deal["id"] = $row["id"];
		     $deal["vendor_id"] = $row["vendor_id"];
		     $deal["bold_text"] = $row["bold_text"];
		     $deal["fine_print_text"] = $row["fine_print_text"];
		     $deal["img_rel_url"] = $row["img_rel_url"];
	             $deal["highlight_text"] = $row["highlight_text"];
		     $deal["valid_from"] = $row["valid_from"];
	             $deal["valid_until"] = $row["valid_until"];
		     $deal["original_price"] = $row["original_price"];
		     $deal["current_price"] = $row["current_price"];
		     $deal["num_bought"] = $row["num_bought"];
		     $deal["num_thumbs_up"] = $row["num_thumbs_up"];
	             $deal["num_thumbs_down"] = $row["num_thumbs_down"];
		     $deal["is_limited_time_offer"] = $row["is_limited_time_offer"];
		     $deal["is_limited_availability"] = $row["is_limited_availability"];
			
		     array_push($response["deals"], $deal);
	         }
		 
	         return $response;
             } else {
                 //return response of empty array
	         $response["deals"] = array();
	         return $response;
	     }
	 } else {
	     $response["status_code"] = ERROR_EXECUTING_QUERY; 
	     return $response;
	 }
     }
     
     public function getVendorByID($id) {
         //array to return
         $response = array();
         
         //fetch user by email
         $stmt = $this->connection->prepare("SELECT id, vendor_name, account_num, addr_1, addr_2, city, state, zip, country_code, phone_1, email, price_range, vendor_website, num_thumbs_up, num_thumbs_down, image_rel_url, description, hours from vendor where id = ?");
         $stmt->bind_param("i", $id);
         $result = $stmt->execute();
         $stmt->bind_result($fetched_id, $vendor_name, $account_num, $addr_1, $addr_2, $city, $state, $zip, $country_code, $phone_1, $email, $price_range, $vendor_website, $num_thumbs_up, $num_thumbs_down, $image_rel_url, $description, $hours);
         $stmt->store_result();
         $num_rows = $stmt->num_rows;
         $stmt->fetch();
         $stmt->close();
         
         //check for successful query 
         if($result) {     
             $response["status_code"] = SUCCESS_EXECUTING_QUERY;  
         
             //check if row exists
             if($num_rows > 0) {
                 $vendor = array();
		 $vendor["id"] = $fetched_id;
		 $vendor["vendor_name"] = $vendor_name;
		 $vendor["account_num"] = $account_num;
		 $vendor["addr_1"] = $addr_1;
		 $vendor["addr_2"] = $addr_2;
	 	 $vendor["city"] = $city;
		 $vendor["state"] = $state;
		 $vendor["zip"] = $zip;
		 $vendor["country_code"] = $country_code;
		 $vendor["phone_1"] = $phone_1;
	 	 $vendor["email"] = $email;
		 $vendor["price_range"] = $price_range;
		 $vendor["vendor_website"] = $vendor_website;
	         $vendor["num_thumbs_up"] = $num_thumbs_up;
		 $vendor["num_thumbs_down"] = $num_thumbs_down;
		 $vendor["image_rel_url"] = $image_rel_url;
		 $vendor["description"] = $description;
		 $vendor["hours"] = $hours;
               		
		 $response["vendor"] = array();
		 array_push($response["vendor"], $vendor);
		 
		 $response["status_code"] = SUCCESS_EXECUTING_QUERY;  
		 
		 return $response;
             } else { 
                 $response["vendor"] = array();
                 return $response;
             }
         } else {
             $response["status_code"] = ERROR_EXECUTING_QUERY; 
	     return $response;
         }
     }
     
	 public function insertDeal($vendor_id, $bold_text, $img_rel_url, $highlight_text, $fine_print_text, $original_price, $current_price, $num_bought, $num_thumbs_up, $num_thumbs_down, $valid_from, $valid_until, $is_limited_time_offer, $is_limited_availability) {
         $stmt = $this->connection->prepare("INSERT INTO deals(vendor_id, bold_text, img_rel_url, highlight_text, fine_print_text, original_price, current_price, num_bought, num_thumbs_up, num_thumbs_down, is_limited_time_offer, is_limited_availability, valid_from, valid_until) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

		 //prints error if have one
		 //echo $this->connection->error;
		 
		 //$date_time = date("Y-m-d H:i:s", mktime(0, 0, 0, 0, 0, 0));
		 
		 $dateTimeFormatError = false;
		 
		 if($valid_from == '') {
			 $valid_from = NULL;
		 } else if (!$this->isDateTime($valid_from)) {
			 $dateTimeFormatError = true;
		 }
		 
		 if($valid_until == '') {
			 $valid_until = NULL;
		 } else if (!$this->isDateTime($valid_until)) {
			 $dateTimeFormatError = true;
		 }
		 
		 if(!$dateTimeFormatError) {
			 $stmt->bind_param("issssddiiiiiss", $vendor_id, $bold_text, $img_rel_url, $highlight_text, $fine_print_text, $original_price, $current_price, $num_bought, $num_thumbs_up, $num_thumbs_down, $is_limited_time_offer, $is_limited_availability, $valid_from, $valid_until);
             $result = $stmt->execute();
		 
		     //prints error if have one
		     //echo $this->connection->error;
         
		     $stmt->close();
		 
             //array to return
             $response = array();
             if($result) {
                 $response["status_code"] = SUCCESS_EXECUTING_QUERY; 
                 $response["id"] = $this->connection->insert_id;
                 return $response;
             } else {
                 $response["status_code"] = ERROR_EXECUTING_QUERY; 
	             return $response;
             } 
		 } else {
			 $response["status_code"] = ERROR_BAD_DATE_TIME_FORMAT; 
	         return $response;
		 }
     }
     
     //checks if parameter is in datetime format
     public function isDateTime($x) {
         return (date('Y-m-d H:i:s', strtotime($x)) == $x);
     }
     
     public function updateDeal($id, $vendor_id, $bold_text, $img_rel_url, $highlight_text, $fine_print_text, $original_price, $current_price, $num_bought, $num_thumbs_up, $num_thumbs_down, $valid_from, $valid_until, $is_limited_time_offer, $is_limited_availability) {
         $dateTimeFormatError = false;
		 
		 if($valid_from == '') {
			 $valid_from = NULL;
		 } else if (!$this->isDateTime($valid_from)) {
			 $dateTimeFormatError = true;
		 } 
		 
		 if($valid_until == '') {
			 $valid_until = NULL;
		 } else if (!$this->isDateTime($valid_until)) {
			 $dateTimeFormatError = true;
		 }
		 
		 if(!$dateTimeFormatError) {
		     $stmt = $this->connection->prepare("UPDATE deals SET vendor_id = ?, bold_text = ?, img_rel_url = ?, highlight_text = ?, fine_print_text = ?, original_price = ?, current_price = ?, num_bought = ?, num_thumbs_up = ?, num_thumbs_down = ?, valid_from = ?, valid_until = ?, is_limited_time_offer = ?, is_limited_availability = ? WHERE id = ?");
			 $stmt->bind_param("issssddiiissiii", $vendor_id, $bold_text, $img_rel_url, $highlight_text, $fine_print_text, $original_price, $current_price, $num_bought, $num_thumbs_up, $num_thumbs_down, $valid_from, $valid_until, $is_limited_time_offer, $is_limited_availability, $id);
			 $result = $stmt->execute();
			 $stmt->close();
			 
			 //array to return
			 $response = array();
			 if($result) {
				 $response["status_code"] = SUCCESS_EXECUTING_QUERY; 
				 return $response;
			 } else {
				 $response["status_code"] = ERROR_EXECUTING_QUERY; 
			     return $response;
			 }
		 } else {
			 $response["status_code"] = ERROR_BAD_DATE_TIME_FORMAT; 
			 return $response;
		 }
     }
     
	 public function updateVendor($id, $vendor_name, $account_num, $addr_1, $addr_2, $city, $state, $zip, $country_code, $price_range, $vendor_website, $num_thumbs_up, $num_thumbs_down, $image_rel_url, $description, $hours, $phone_1, $email) {
         $stmt = $this->connection->prepare("UPDATE vendor SET vendor_name = ?, account_num = ?, addr_1 = ?, addr_2 = ?, city = ?, state = ?, zip = ?, country_code = ?, price_range = ?, vendor_website = ?, num_thumbs_up = ?, num_thumbs_down = ?, image_rel_url = ?, description = ?, hours = ?, phone_1 = ?, email = ? WHERE id = ?");
         $stmt->bind_param("sissssssssiisssssi", $vendor_name, $account_num, $addr_1, $addr_2, $city, $state, $zip, $country_code, $price_range, $vendor_website, $num_thumbs_up, $num_thumbs_down, $image_rel_url, $description, $hours, $phone_1, $email, $id);
         $result = $stmt->execute();
         $stmt->close();
         
         //array to return
         $response = array();
         if($result) {
             $response["status_code"] = SUCCESS_EXECUTING_QUERY; 
             return $response;
         } else {
             $response["status_code"] = ERROR_EXECUTING_QUERY; 
	     return $response;
         }
     }
     
     public function deleteAllTagsOfDealID($id) {
         $stmt = $this->connection->prepare("DELETE FROM deal_tag WHERE deal_id = ?");
         $stmt->bind_param("i", $id);
         $result = $stmt->execute();
         $stmt->close();
         
         //array to return
         $response = array();
         if($result) {
             $response["status_code"] = SUCCESS_EXECUTING_QUERY; 
             return $response;
         } else {
             $response["status_code"] = ERROR_EXECUTING_QUERY; 
	     return $response;
         }
     }
     
     public function insertTagsForDealID($id, $tag_list) {
         $tag_list_array = preg_split("/,/",$tag_list);
	 //$tag_count = count($tag_list_array);
	 $error = 0;
	 
	 $stmt = $this->connection->prepare("INSERT INTO deal_tag(deal_id, tag) VALUES (?, ?)");
	 
	 foreach($tag_list_array as $tag) {
             $stmt->bind_param("is", $id, $tag);
             $result = $stmt->execute();
             
 	     if ($result == false){
 		$error = 1;
 	     }
	 }
	 
	 $stmt->close();
	 
	 $response = array();
	 if($error == 0) {
	     $response["status_code"] = SUCCESS_EXECUTING_QUERY; 
	     return $response;
	 } else {
	     $response["status_code"] = ERROR_EXECUTING_QUERY; 
	     return $response;
	 }
     }
     
     /**
      * delete deal row based on primary id
      * @param $id is the primary
      * @return boolean whether a row(s) is affected
      */
     public function deleteDealByID($id) {
         $stmt = $this->connection->prepare("DELETE FROM deals WHERE id = ?");
         $stmt->bind_param("i", $id);
         $stmt->execute();
         $num_affected_rows = $stmt->affected_rows;
         $stmt->close();
         return $num_affected_rows > 0;
     }
     
     public function insertVendor($vendor_name, $account_num, $addr_1, $addr_2, $city, $state, $zip, $country_code, $price_range, $vendor_website, $num_thumbs_up, $num_thumbs_down, $image_rel_url,  $description, $hours, $phone_1, $email) {
         $stmt = $this->connection->prepare("INSERT INTO vendor(vendor_name, account_num, addr_1, addr_2, city, state, zip, country_code, price_range, vendor_website, num_thumbs_up, num_thumbs_down, image_rel_url,  description, hours, phone_1, email) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
         $stmt->bind_param("sissssssssiisssss", $vendor_name, $account_num, $addr_1, $addr_2, $city, $state, $zip, $country_code, $price_range, $vendor_website, $num_thumbs_up, $num_thumbs_down, $image_rel_url,  $description, $hours, $phone_1, $email);
         $result = $stmt->execute();
         $stmt->close();
         
         //array to return
         $response = array();
         if($result) {
             $response["status_code"] = SUCCESS_EXECUTING_QUERY; 
             $response["id"] = $this->connection->insert_id;
             return $response;
         } else {
             $response["status_code"] = ERROR_EXECUTING_QUERY; 
	     return $response;
         }
     }
     
     /**
      * Checking for duplicate user by email address
      * @param String $email email to check in database
      * @return boolean
      */
     private function isEmailExists($email) {
        $stmt = $this->connection->prepare("SELECT id from users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
     }
     
     /**
      * Checking for duplicate user by username
      * @param String $username username to check in database
      * @return boolean
      */
     private function isUsernameExists($username) {
        $stmt = $this->connection->prepare("SELECT id from users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
     }
     
     /**
      * Checking if verification code exists
      * @param String $verification_code verification code to check in database
      * @return boolean
      */
     private function isIdAndVerificationCodeExists($id, $verification_code) {
        $stmt = $this->connection->prepare("SELECT id from users WHERE id = ? AND verification_code = ?");
        $stmt->bind_param("is", $id, $verification_code);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
     }
     
     /**
      * Check if activited
      * @param String $id id to check in database
      * @return boolean, true if active, false if not active or id does not exist in users table
      */
     public function isUserActivated($id) {
        $stmt = $this->connection->prepare("SELECT id from users WHERE id = ? AND active = 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
     }
     
     /**
      * Check if activited
      * @param String $email email to check in database
      * @return boolean, true if active, false if not active or id does not exist in users table
      */
     public function isUserActivatedByEmail($email) {
        $stmt = $this->connection->prepare("SELECT id from users WHERE email = ? AND active = 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
     }
     
     public function getAllFirebaseRegistrationTokens() {
         $stmt = $this->connection->prepare("SELECT registration_token from firebase_registration_tokens");
         $result = $stmt->execute();
        
         //bind result to variables
         $stmt->bind_result($registration_token);
        
         //Store the result (to get properties) 
         $stmt->store_result();
        
         //array to return
         $response = array();

         //check if execute has no problems
         if($result) {
             $registration_tokens = array();
             while($stmt->fetch()) {
                 array_push($registration_tokens, $registration_token);
             }
             $response["status_code"] = SUCCESS_EXECUTING_QUERY; 
             $response["registration_tokens"] = $registration_tokens;
             return $response;
         } else {
             $response["status_code"] = ERROR_EXECUTING_QUERY; 
	     return $response;
         }
        
         //Frees stored result memory for the given statement handle
         $stmt->free_result();
        
         $stmt->close();
        
         return $return;
     }
     
     public function insertFirebaseRegistrationToken($registration_token) {
         $stmt = $this->connection->prepare("INSERT INTO firebase_registration_tokens(registration_token) values(?)");
         $stmt->bind_param("s", $registration_token);
         $result = $stmt->execute();
         $stmt->close();
         
         //array to return
         $response = array();
         if($result) {
             $response["status_code"] = SUCCESS_EXECUTING_QUERY; 
             $response["id"] = $this->connection->insert_id;
             return $response;
         } else {
             $response["status_code"] = ERROR_EXECUTING_QUERY; 
	     return $response;
         }
     }
     
     /**
      * Generates random unique MD5 String for user API key
      */
     private function generateApiKey() {
         return md5(uniqid(rand(), true));
     }
     
     /**
      * Generates random unique MD5 String for verification code
      */
     private function generateVerificationCode() {
         return md5(uniqid(rand(), true));
     }
}

?>