<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>PHP Demo</title>
</head>
<body>
<?php

    $jim = "James";
    
    function sayHello($name)
    {
        global $jim;
        $name = $jim;
        echo "Hello $name<br>";
    }
    
    sayHello("bob");

?>
</body>
</html>