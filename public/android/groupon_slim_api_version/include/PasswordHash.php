<?php 

class PasswordHash {
    //blowfish
    private static $algorithm = '$2a';
    private static $cost = '$10';
    
    //mainly for internal use
    public static function unique_salt() {
       return substr(sha1(mt_rand()), 0, 22);
    }
    
    //this will generate the hash
    public static function hash($password) {
        return crypt($password, self::$algorithm . self::$cost . '$' . self::unique_salt());
    }
    
    //this will be used to compare a password against a hash
    public static function check_password($hash, $password) {
        $full_salt = substr($hash, 0, 29);
        $new_hash = crypt($password, $full_salt);
        return ($hash == $new_hash);
    }
}

?>
