<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed</title>

    <style>
        td {
            width: 100px;
        }

        thead>tr {
            font-weight: 600;
        }

        header {
            height: 200px;
            overflow: hidden;
            background-image: url(./assets/header.jpg);
            background-position: center;
        }

        p>span {
            font-weight: 600;
        }
    </style>
</head>

<body>
    <header>
    </header>

    <h1>Order Confirmation</h1>
    <?php
    $payload = $_GET['data'];
    // echo $payload->data;
    // echo gettype($payload);
    $myArray = json_decode($payload);
    $myEncode = json_encode($myArray[0]);
    // echo $myEncode;
    // var_dump(json_decode($myEncode));
    $decoded = json_decode($myEncode);

    $pickup = "";
    $delivery = "";
    $total = "";
    $subtotal = "";
    $tax = "";
    $email = "";
    $orderList = array();

    foreach ($decoded as $key => $value) {
        foreach ($value as $k => $v) {
            if ($k == "pickup") {
                $pickup = $v;
            } elseif ($k == "delivery") {
                $delivery = $v;
            } elseif ($k == "total") {
                $total = $v;
            } elseif ($k == "subtotal") {
                $subtotal = $v;
            } elseif ($k == "tax") {
                $tax = $v;
            } elseif ($k == "email") {
                $email = $v;
            } elseif ($k == "order") {
                // foreach($v as $ky => $vl) {
                // array_push($tempList, $ky);
                // array_push($tempList, $vl);
                // }
                foreach ($v as $item) {
                    $tempList = array();

                    foreach ($item as $ky => $vl) {
                        array_push($tempList, $vl);
                    }
                    array_push($orderList, $tempList);
                }
            }
        }
    }

    // send email to user
    $to = $email;
    $subject = 'Order Confirmed!';
    $message = "Thank you for the order." . "\n" . "Order Total = " . $total . ($pickup != ""? " Pickup": " Delivery") . " time = " . ($pickup != ""? $pickup: $delivery);
    echo $message;
    $headers = 'From: youremail@example.com' . "\r\n" .
        'Reply-To: youremail@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

    ?>
    <table>
        <thead>
            <tr>
                <td>Quantity</td>
                <td>Product</td>
                <td>Cost</td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($orderList as $row) {
            ?>
                <tr>
                    <?php
                    foreach ($row as $col) {

                    ?>
                        <td>
                            <?php
                            echo $col;
                            ?>
                        </td>
                    <?php
                    }
                    ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <br>
    <p>
        <span>Subtotal:</span> <?php echo $subtotal; ?>
    </p>
    <p>
        <span>Tax:</span> <?php echo $tax; ?>
    </p>
    <p>
        <span>Total:</span> <?php echo $total; ?>
    </p>
    <?php
    if ($pickup != '') {
    ?>
        <p>
            <span>Pickup Time:</span> <?php echo $pickup; ?>
        </p>
    <?php
    }
    if ($delivery != '') {
    ?>
        <p>
            <span>Delivery Time:</span> <?php echo $delivery; ?>
        </p>
    <?php
    }
    ?>
</body>

</html>