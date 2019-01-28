<?php
class User {
    private $name;
    private $age;

    public function __construct($name, $age) {
        $this->name = $name;
        $this->age = $age;
    }

    public function getName(){
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }
    //_get magic method
    public function __get($property){
        if(property_exists($this, $property)) {
            return $this->$property;
        }
    }
    public function __set($property, $value) {
        if(property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }
}//end class User

$user1 = new User('bb',10);

//access through function
// echo $user1->setName('aa');
// echo $user1->getName();

//by using magic func
$user1->__set('age', 11);
echo $user1->__get('age');
?>