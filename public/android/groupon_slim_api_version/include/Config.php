<?php 
// Firebase Key
define('FIREBASE_SERVER_KEY', 'AIzaSyBWFGDa036FDgXaoWHYbqDsyOP1tKUwW5g');

// Include Firebase Configuration
require_once __DIR__ . '/config/firebase.php';

//define('ROOT', '/android/groupon_slim_api_version');
define('USER_AVATAR_IMAGE_DIR_PATH', __DIR__ . "/../data/users/images/");

//database configuration
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'password');
define('DB_HOST', 'localhost');
define('DB_NAME', 'groupon_database');

//Include Database Status Codes
require_once __DIR__ . '/config/database_status_codes.php';

//php post/get is missing values 
define('ENTER_MISSING_VALUES', 15);

?>
