<?= template_header('Place Order') ?>
<?php
$totalPrice = $_SESSION['subtotal'];
if (isset($_POST["price"]))
    $totalPrice = $_POST["price"];
?>

<div class="placeorder content-wrapper">
    <form action="index.php?page=submitorder" method="post">
        <label for="Name" class="form-label">Name</label>
        <input type="text" name="Name" placeholder="Your Name" value="" required="" class="form-field">
        <label for="Address" class="form-label">Address</label>
        <input type="text" name="Address" placeholder="format address" value="" required="" class="form-field">
        <label for="Phone" class="form-label">Phone</label>
        <input type="text" name="Phone" placeholder="09XX-XXX-XXX" value="" required="" class="form-field">
        <label for="email" class="form-label">Email</label>
        <input type="text" name="email" placeholder="example@gmail.com" value="" required="" class="form-field">
        <div class="subtotal">
            <span class="text">total:</span>
            <span class="price">&dollar;<?= $totalPrice ?></span>
        </div>
        <br><br>
        <div class="buttons">
            <button>submit
        </div>
    </form>
</div>

<?= template_footer() ?>