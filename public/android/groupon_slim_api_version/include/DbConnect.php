<?php 

class DbConnect {
    private $connection;
    
    function __construct() {
    }
    
    /*establishing database connection
    * Establishing database connection
    * @return database connection handler
    */
    function connect() {
    	include_once dirname(__FILE__) . '/Config.php';
    	
    	//connecting to mysql database
    	$this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    	
    	//check for database connection error
    	if(mysqli_connect_errno()) {
    	    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    	}
    	
    	//return connection resource
    	return $this->connection;
    }
}

?>