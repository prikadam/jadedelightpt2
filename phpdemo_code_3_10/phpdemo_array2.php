<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>PHP Demo</title>
</head>
<body>
<?php

    $products = array(
        "sand bags" => 4,
        "salt" => 3,
        "spring water" => 1
    );
    
    $products["spring water"] -= 1;
   
    echo "Order these products asap<br>";
    foreach ($products as $p=>$amount)
    {
        if (!$amount)
            echo "Product: $p.<br>";
    }
        
   
    

?>
</body>
</html>