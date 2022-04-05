<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>PHP Demo</title>
</head>
<body>
<?php

    // params = first op, second op, operator
    function calculate($params)
    {
        if (isset($params["first"]))
           $op1 = $params["first"];
        else 
            $op1 =0;
        
        $op2 = isset($params["second"]) ? $params["second"]:0;
        
        $oper = isset($params["operator"]) ? $params["operator"]:"+";
     
        foreach($params as $p=>$val)
        {
            echo "$p = $val <br>";
        }
        echo "The calculation is: $op1 $oper $op2<br>";
    }
    
    calculate (array("second"=>10));
        
   
    

?>
</body>
</html>