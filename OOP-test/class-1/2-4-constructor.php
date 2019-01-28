<?php
class User {
    public $name;
    public $age;

    //php5 use same name as class
    public function __construct($name, $age) {
        // echo 'constructor run...';
        //print class name magic constant
        echo 'Class ' . __CLASS__ . ' instantiated<br>';
        $this->name = $name;
        $this->age = $age;
    }
    public function sayHello() {
        return $this->name . 'says hello';
    }

    //called when no other references to a certain obj. used for cleanup, 
    //close connections.
    public function __destruct() {
        echo 'destructor ran...';
    }
}

//instantiate obj
$user1 = new User('sara', 22);
echo $user1->name . ' is ' . $user1->age . ' years old';
