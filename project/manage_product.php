<?php
// Check to make sure the id parameter is specified in the URL
if (isset($_GET['id'])) {
    // Prepare statement and execute, prevents SQL injection
    $stmt = $pdo->prepare('SELECT * FROM item WHERE item_id = ?');
    $stmt->execute([$_GET['id']]);
    // Fetch the product from the database and return the result as an Array
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the product exists (array is not empty)
    if (!$product) {
        // Simple error to display if the id for the product doesn't exists (array is empty)
        exit('Product does not exist!');
    }

    $stmt_category = $pdo->prepare('SELECT * FROM item natural join item_category WHERE item_id = ?');
    $stmt_category->execute([$_GET['id']]);
    $product_category = $stmt_category->fetch(PDO::FETCH_ASSOC);
    if (!$product_category) {
        // Simple error to display if the id for the product doesn't exists (array is empty)
        exit('Product\'s category does not exist!');
    }

} else {
    // Simple error to display if the id wasn't specified
    exit('Product does not exist!');
}
?>
 
<?=template_manager('Product')?>

<div class="product content-wrapper">
    <img src="imgs/<?=$product['img']?>" width="500" height="500" alt="<?=$product['item_name']?>">
    <div>
        <h1 class="name"><?=$product['item_name']?></h1>
        <div class="name">
            <p>
             amount: <?=$product['quantity']?> 
            </p>
        </div>
        <span class="price">
            &dollar;<?=$product['price']?>
            <?php if ($product['rrp'] > 0): ?>
            <span class="rrp">&dollar;<?=$product['rrp']?></span>
            <?php endif; ?>
        </span>
        <form action="index.php?page=amount" method="post">
            <input type="number" name="quantity" value="1" min="1" max="99" placeholder="Quantity" required>
            <input type="hidden" name="product_id" value="<?=$product['item_id']?>">
            <input type="submit" value="Update Amount">
        </form>
        <form action="index.php?page=price" method="post">
            <input type="number" name="price" value="1"  placeholder="Quantity" required>
            <input type="hidden" name="product_id" value="<?=$product['item_id']?>">
            <input type="submit" value="Update Price">
        </form>
        <div class="description">
            <?=$product['desc']?>
        </div>
    </div>
</div>

<?=template_footer()?>
