<?php
class User {
    public $name;

    public function sayHello() {
        //this refer this class
        return $this->$name . 'hello';
    }

}

$user1 = new User();
$user1->$name = 'sasa';
//-> access property equal with .
echo $user1->$name;
echo '<br>';
echo $user1->sayHello();
echo '<br>';

$user2 = new User();
$user2->$name = 'sasa2';
echo '<br>';
echo $user2->sayHello();
?>