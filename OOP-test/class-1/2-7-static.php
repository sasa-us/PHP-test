<?php
class User {
    public $name;
    public $age;
    public static $minPassLength = 6; //will be same nomatter what

    public static function validPass($pass) {
        //static won't use this-> to access. user self::
        if(strlen($pass) >= self::$minPassLength) {
            return true;
        }else {
            return false;
        }
    }

}//end class User

// don't need to instatntiate
$psw = 'hello1';
if(User::validPass($psw)) {
    echo 'password valid';
} else {
    echo 'not';
}


?>
