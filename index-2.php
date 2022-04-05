<?php

$servername='localhost';
$db='dbyv0vxjezcgvt';
$username='uiv6ccapc17yq';
$password='esd556egjjt5';

$conn = mysqli_connect($servername, $username, $password, $db);
# Init the MySQL Connection


if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

$conn->select_db($database);

    
$result = mysqli_query($conn,"SELECT * FROM JadeDelight");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Jade Delight 2</title>

    <style>
        header {
            height: 200px;
            overflow: hidden;
            background-image: url(./assets/header.jpg);
            background-position: center;
        }

        label {
            display: inline-block;
            float: left;
            clear: left;
            width: 250px;
            text-align: left;
        }

        .form-input {
            display: inline-block;
        }

        .display-none {
            display: none;
        }
    </style>
</head>



<body>
    <header>
    </header>

    <h1>Jade Delight</h1>

    <form>
        <p>
            <label>First Name: </label><input type="text" name="fname" id="fname" class="form-input" />
        </p>
        <p>
            <label>Last Name*: </label><input type="text" name="lname" id="lname" class="form-input" />
        </p>
        <p id="street_wrapper" class="display-none">
            <label>Street: </label><input type="text" name="street" id="street" class="form-input" />
        </p>
        <p id="city_wrapper" class="display-none">
            <label>City: </label><input type="text" name="city" id="city" class="form-input" />
        </p>
        <p>
            <label>Phone*: </label><input type="tel" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" id="phonenum" class="form-input" />
        </p>
        <p>
            <label>Email*: </label><input type="email" name="email" id="email" class="form-input" />
        </p>
        <p class="pa-small">
            <input type="radio" id="pickup_radio" name="p_or_d" value="pickup" checked="checked" onclick="radioClick()" />Pickup
            <input type="radio" id="delivery_radio" name="p_or_d" onclick="radioClick()" value="delivery" />Delivery
        </p>

        <table border="0" cellpadding="3">
            <thead>
                <tr>
                    <th>Select Item</th>
                    <th>Item Name</th>
                    <th>Cost Each</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php

                function makeSelect($name, $minRange, $maxRange)
                {
                ?>
                    <select name='<?php echo $name ?>' size='1'>
                        <?php
                        for ($j = $minRange; $j <= $maxRange; $j++) {
                        ?>
                            <option><?php echo $j ?></option>
                    </select>
                <?php
                        }
                    }

                    # array to store product list
                    $productList = array();

                    # Prepare the SELECT Query
                    $selectSQL = "SELECT * FROM `product`";
                    $selectRes = mysqli_query($selectSQL);
                    $count = mysqli_num_rows($selectRes);
                    # Execute the SELECT Query
                    if (!($selectRes = mysqli_query($selectSQL))) {
                        echo "Retrieval of data from Database Failed - #" . mysqli_errno() . ': ' . mysqli_error();
                    } else {
                        if (mysqli_num_rows($selectRes) == 0) {
                ?>
                    <tr>
                        <td colspan="4">No Rows Returned</td>
                    </tr>
                    <?php
                        } else {
                            $i = 0;
                            while ($row = mysqli_fetch_assoc($selectRes)) {
                                array_push($productList, (object)[
                                    'name' => $row['name'],
                                    'cost' => $row['cost']
                                ]);
                    ?>
                        <tr>
                            <td>
                                <select name='quan<?php echo $i ?>' size='1'>
                                    <?php
                                    for ($j = 0; $j <= 10; $j++) {
                                    ?>
                                        <option>
                                            <?php echo $j ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <?php
                                echo $row['name'];
                                ?>
                            </td>
                            <td>$
                                <?php
                                echo $row['cost'];
                                ?>
                            </td>
                            <td>$ <input type='text' name='cost' placeholder="0.00" /></td>
                        </tr>
            <?php
                                $i += 1;
                            }
                        }
                    }
            ?>
            </tbody>
        </table>
        <p>Subtotal: $<input type="text" name="subtotal" id="subtotal" /></p>
        <p>Mass tax 6.25%: $ <input type="text" name="tax" id="tax" /></p>
        <p>Total: $ <input type="text" name="total" id="total" /></p>
    </form>

    <button type="button" value="Submit Order" onclick="validate()">
        Submit Order
    </button>
</body>

<script>
    let itemObj = {};
    let itemQuantObj = {};
    // let orderList = [];
    // show or hide street and city field based on pickup and delivery
    function radioClick() {
        const pickup = document.getElementById("pickup_radio");
        const delivery = document.getElementById("delivery_radio");

        if (pickup.checked) {
            document.getElementById('street_wrapper').classList.toggle("display-none")
            document.getElementById('city_wrapper').classList.toggle("display-none")
        } else {
            document.getElementById('street_wrapper').classList.toggle("display-none")
            document.getElementById('city_wrapper').classList.toggle("display-none")
        }
    }

    // get the total product count from db
    const productCount = <?php echo $count; ?>;
    const menuItems = <?php echo json_encode($productList); ?>;
    console.log('>>> menu items = ', menuItems);

    // add event listener on change of quantity change
    for (let i = 0; i < productCount; i++) {
        console.log('>>> added change')
        document.getElementsByName("quan" + i)[0].onchange = changeItemCost;
    }

    function changeItemCost(event) {
        const item = event.target;
        const index_of_item = item.name.slice(-1);
        const quantity = parseInt(item.value);
        const total_item = quantity * parseFloat(menuItems[index_of_item].cost);
        const rounded_item = total_item.toFixed(2);
        document.getElementsByName("cost")[index_of_item].value = rounded_item;
        itemQuantObj[menuItems[index_of_item]['name']] = quantity;
        calculate_final_values();
    }

    const first_zero = parseFloat("0");
    document.getElementById("subtotal").value = first_zero.toFixed(2);
    document.getElementById("tax").value = first_zero.toFixed(2);
    document.getElementById("total").value = first_zero.toFixed(2);

    function calculate_final_values() {
        var subtotal = 0;
        for (var i = 0; i < menuItems.length; i++) {
            subtotal = parseFloat(subtotal);
            if (document.getElementsByName("cost")[i].value == "") {
                var zero = parseFloat("0");
                document.getElementsByName("cost")[i].value = zero.toFixed(2);
            }
            var added_cost = document.getElementsByName("cost")[i].value;
            var float_subtotal = parseFloat(added_cost)
            subtotal = subtotal + float_subtotal;
            if (added_cost >= 0) {
                if (added_cost == 0) {
                    if (menuItems[i]['name'] in itemObj) {
                        itemObj[menuItems[i]['name']] = added_cost
                    }
                } else {
                    itemObj[menuItems[i]['name']] = added_cost
                }
            }
        }
        var float_subtotal_final = parseFloat(subtotal);
        document.getElementById("subtotal").value = subtotal.toFixed(2);

        // Calculating tax value
        document.getElementById("tax").value = (subtotal * 0.0625).toFixed(2);

        // Calculating total
        document.getElementById("total").value = (subtotal + (subtotal * 0.0625)).toFixed(2);

        console.log(itemObj, itemQuantObj);
    }

    // logic to do validations and submit the form
    function validate() {
        if (validatePhone() === false || validatelast() === false) {
            if (validatePhone() === false) {
                alert("Your phone number must be of format XXX-XXX-XXXX.");
            }
            if (validatelast() == false) {
                alert("Please enter your last name.");
            }
            return;
        }
        if (validateEmail() === false) {
            alert("Please enter your email.");
        }
        if (validateOrder() === false) {
            alert("Please order something!");
            return;
        }
        pickupinfo();
    }

    function validateEmail() {
        return String(email)
            .toLowerCase()
            .match(
                /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
    }

    function validateOrder() {
        for (let item of Object.keys(itemQuantObj)) {
            console.log('>>> heya!')
            return true;
        }
        return false;
    }

    function validatePhone() {
        var phoneval = document.getElementById("phonenum").value;
        var phoneno = /^\(?([0-9]{3})\)?[-]?([0-9]{3})[-]?([0-9]{4})$/;
        if (phoneval.match(phoneno)) {
            return true;
        } else {
            return false;
        }
    }

    function validatelast() {
        var lastname = document.getElementById("lname").value;
        if (lastname != "") {
            return true;
        } else {
            return false;
        }
        return;
    }

    function pickupinfo() {
        let obj = {}
        const pickup = document.getElementById("pickup_radio").checked;
        const street = document.getElementById("street").value;
        const city = document.getElementById("city").value;
        const date = new Date();
        const email = document.getElementById("email").value;
        if (pickup == true) {
            finaltime = add_minutes(15);
            obj = {
                pickup: finaltime,
                total: document.getElementById("total").value,
                subtotal: document.getElementById("subtotal").value,
                tax: document.getElementById("tax").value,
                email
            };
        } else {
            if (street == "" || city == "") {
                alert("Fill in your street AND your city if you want delivery");
            } else {
                finaltime = add_minutes(30);
                obj = {
                    delivery: finaltime,
                    total: document.getElementById("total").value,
                    subtotal: document.getElementById("subtotal").value,
                    tax: document.getElementById("tax").value,
                    email
                };
            }
        }
        console.log('>>> time total = ', obj);

        let tempdata = [];
        for (let key of Object.keys(itemQuantObj)) {
            tempdata.push({
                quantity: itemQuantObj[key],
                name: key,
                cost: itemObj[key]
            });
        }

        data = {
            ...obj,
            order: tempdata
        }
        console.log('>>> final data => ', data)
        // const httpc = new XMLHttpRequest();
        // const url = "confirmation.php?data="+JSON.stringify([{data}]);
        // httpc.open("GET", url, true);
        // httpc.send();
        window.location = "http://localhost/jadedelightPHP/confirmation.php?data=" + JSON.stringify([{
            data
        }]);
    }

    function add_minutes(minutes_add) {
        var date = new Date();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        minutes = minutes + minutes_add;
        if (minutes > 59) {
            minutes = minutes % 60;
            if (hours == 23) {
                hours = 0;
            } else {
                hours += 1;
            }
        }
        var finaltime = "";
        if (hours < 10) {
            finaltime += "0" + hours + ":";
        } else {
            finaltime += hours + ":";
        }
        if (minutes < 10) {
            finaltime += "0" + minutes;
        } else {
            finaltime += minutes;
        }
        return finaltime;
    }
    console.log(itemObj);
</script>

</html>
