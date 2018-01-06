<?php

class FCM {

    //constructor
    function __construct() {
    
    }
    
    //send push notification to single user by FCM registration token
    public function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message, 
        ); 
        
        return $this->sendMessage($fields);
    }
    
    //send message to a topic by topic id
    public function sendTopic($to, $message) {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message
        );
        return $this->sendMessage($fields);
    }
    
    //send push message to multiple users by fcm registration ids
    public function sendMultiple($registration_ids, $message) {
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message
        );
        
        return $this->sendMessage($fields);
    }
    
    private function sendMessage($fields) {
        //include config file
        include_once __DIR__ . '/../../include/Config.php';
        
        //Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        $headers = array(
            'Authorization: key=' . FIREBASE_SERVER_KEY,
            'Content-Type: application/json'
        );
        
        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        //disabling SSL Certificate support temporarily
        curl_setopt($ch, CURLOPT_VERIFYPEER, false);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        
        //Execute post
        $result = curl_exec($ch);
        curl_close($ch);
        
        if($result === FALSE) {
            return $result;
        }
        
        
        
        return $result;
    }

}

?>