<?php
session_start();

if(empty($_SESSION['hiddenNumber'])) {
   
    $_SESSION['hiddenNumber'] = rand(1, 10);
    $_SESSION['history'] = []; 
    print("we don't have a hidden number, grab new one: {$_SESSION['hiddenNumber']}<br>");
}

//every time hit/refresh the page send a new request,
//so every refresh page will get a new random number 
//need to remember the random number use session
print("hidden number is {$_SESSION['hiddenNumber']}<br>");

$displayMessage = "";
if(!empty($_GET['userGuess'])) {
//print("you guessed {$_GET['userGuess']}");

$guess = $_GET['userGuess'];
$_SESSION['history'][] = $guess;
    if( $guess > $_SESSION['hiddenNumber']) {
        $displayMessage = "too high";
    } else if ($guess < $_SESSION['hiddenNumber']) {
        $displayMessage = "too low";
    } else if ($guess == $_SESSION['hiddenNumber']) {
        $displayMessage = "got it the number is ". $_SESSION['hiddenNumber'];
        unset($_SESSION['hiddenNumber']); 
        unset($_SESSION['history']); 
        //unset is delete variable
    }
}

?>

<form action="" method="get">

    <div class="display">
        <?php print($displayMessage)?>
    </div>
    <input type="text" name="userGuess">
    <button>GUESS</button>
    <div> your past guesses: 
    <?php
        if(!empty($_SESSION['history'])) {
            foreach($_SESSION['history'] as $value) {
    ?>
        <div>
            <?php print($value); ?>
        </div>
        <?php 
            } 
        }
        ?>
            
    
    </div>
</form>