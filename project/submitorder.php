<?= template_header('') ?>
<?php
$totalPrice = $_SESSION['subtotal'];;
$nameInput = "";
$addressInput = "";
$phoneInput = "";
$emailInput = "";
if (isset($_POST["Name"]))
    $nameInput = $_POST["Name"];
if (isset($_POST["Address"]))
    $addressInput = $_POST["Address"];
if (isset($_POST["Phone"]))
    $phoneInput = $_POST["Phone"];
if (isset($_POST["email"]))
    $emailInput = $_POST["email"];

if ($nameInput != "" && $addressInput != "" && $phoneInput != "" && $emailInput != "") {
    $db = pdo_connect_mysql();

    // put data into order
    $query = "INSERT INTO  `team5`.`orders`(total_price,shipping_name,shipping_address,shipping_phone,shipping_email) 
              VALUES(:total_price,:name,:address,:phone,:email)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":total_price", $totalPrice);
    $stmt->bindParam(":name", $nameInput);
    $stmt->bindParam(":address", $addressInput);
    $stmt->bindParam(":phone",  $phoneInput);
    $stmt->bindParam(":email",  $emailInput);
    if ($stmt->execute()) {
        //echo "Insert Success!";
    } else {
        echo $stmt->error;
    }

    $psql = "SELECT MAX(order_id) FROM orders;";
    $res = $db->query($psql);
    $order_id = $res->fetchColumn(0);
    $query = "INSERT INTO  `team5`.`order_contain`(order_id,item_id,quantity) 
              VALUES(:order_id,:item_id,:quantity)";
    $stmt = $pdo->prepare($query);
    foreach ($_SESSION['cart'] as $item_id => $quantity) {
        $stmt->bindParam(":order_id", $order_id);
        $stmt->bindParam(":item_id", $item_id);
        $stmt->bindParam(":quantity", $quantity);
        if ($stmt->execute()) {
            //echo "Insert Success!";
        } else {
            echo $stmt->error;
        }
    }
    if (isset($_SESSION['account_loggedin'])) {
        $query = "INSERT INTO  `team5`.`buy`(order_id,customer_id) 
              VALUES(:order_id,:customer_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->bindParam(":customer_id",$_SESSION['account_id']);
        if ($stmt->execute()) {
            //echo "Insert Success!";
        } else {
            echo $stmt->error;
        }
    }
    if (isset($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }
}
?>
<div class="placeorder content-wrapper">
    <form action="" method="post">
        <p>Thank you for your Patronage!</p>
        <p>Your order will be shipped immediately.</p>
    </form>
</div>

<?= template_footer() ?>