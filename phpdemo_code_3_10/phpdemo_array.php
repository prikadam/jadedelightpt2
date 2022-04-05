<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>PHP Demo</title>
</head>
<body>
<?php

    $messages = array(
        "Have a good day",
        "Keep looking up",
        "Fortune is coming your way"
    );
    
    function getRand()
    {
        global $messages;
        return rand(0, count($messages)-1);
    }
    
    function getMessage()
    {
        global $messages;
        return $messages[getRand()];
    }
    
/*    for ($i=0; $i<=12; $i++)
        echo "random number: " . getRand(). "<br>";
    foreach($messages as $msg)
        echo "$msg <br>";
 */
    
    echo "Your forune today: " . getMessage() . "<br>";
    
    

?>
</body>
</html>