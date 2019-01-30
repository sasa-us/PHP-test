<?php
$assArr = array(
    "1"=>"<![CDATA[food, Beverages &gt; tobacco > food items > cooking &gt; baking]]>", 
    "2"=>"boba", 
    "3"=>"http://206.189/index.php"
);

foreach($assArr as $x => $x_value) {
    $x_value = strtr($x_value, array('[CDATA[' => '' , ']]'=>''));
    //$x_value = str_replace('[CDATA[','', $x_value);
    echo "value=" . $x_value;
    echo "<br>";
}

?>