<?php
session_start();

if(empty($_SESSION['hiddenNumber'])) {
    $_SESSION['hiddenNumber'] = rand(1, 10);
}
$hiddenNumber = rand(1, 10);

//every time hit/refresh the page send a new request,
//so every refresh page will get a new random number 
//need to remember the random number use session
print("hidden number is $hiddenNumber");

if(!empty($_GET['userGuess'])) {
    print("you guessed {$_GET['userGuess']}");
}

?>

<form action="" method="get">

    <div class="display"></div>
    <input type="text" name="userGuess">
    <button>GUESS</button>
</form>