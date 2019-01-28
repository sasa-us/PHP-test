<?php
class User {
    protected $name = 'sasa';
    protected $age;

    public function __construct($name, $age) {
        $this->name = $name;
        $this->age = $age;
    }
}

class Customer extends User {
    private $balance;

    public function __construct($name, $age, $balance) {
        parent::__construct($name, $age);
        $this->balance = $balance;
    }
    public function pay($amount) {
        return $this->name . ' pay $' . $amount;
    }

    public function getBalance() {
        return $this->balance;
    }
}

//$customer1 = new Customer();//if use this will get parent protected property
//echo $customer1->pay(100);
$customer1 = new Customer('aa', 11, 400);
echo $customer1->getBalance();



?>