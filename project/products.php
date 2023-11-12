<?php
if(isset($_GET['category'])){
    $category = $_GET['category'];
    // The amounts of products to show on each page
    $num_products_on_each_page = 4;
    // The current page, in the URL this will appear as index.php?page=products&p=1, index.php?page=products&p=2, etc...
    $current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
    // Select products ordered by the date added
    $stmt = $pdo->prepare('SELECT * FROM item natural join item_category where category = ? and item.quantity<>0 ORDER BY date_added DESC LIMIT ?,?');
    // bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
    $stmt->bindValue(1, $category, PDO::PARAM_STR);
    $stmt->bindValue(2, ($current_page - 1) * $num_products_on_each_page, PDO::PARAM_INT);
    $stmt->bindValue(3, $num_products_on_each_page, PDO::PARAM_INT);
    $stmt->execute();
    // Fetch the products from the database and return the result as an Array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Get the total number of products
    $stmt = $pdo->prepare('SELECT * FROM item natural join item_category where category = ? and item.quantity<>0');
    $stmt->execute([$category]);
    $total_products = sizeof($stmt->fetchAll(PDO::FETCH_ASSOC));
}else{
    $category = 'Products';
    // The amounts of products to show on each page
    $num_products_on_each_page = 4;
    // The current page, in the URL this will appear as index.php?page=products&p=1, index.php?page=products&p=2, etc...
    $current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
    // Select products ordered by the date added
    $stmt = $pdo->prepare('SELECT * FROM item WHERE item.quantity<>0 ORDER BY date_added DESC LIMIT ?,?');
    // bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
    $stmt->bindValue(1, ($current_page - 1) * $num_products_on_each_page, PDO::PARAM_INT);
    $stmt->bindValue(2, $num_products_on_each_page, PDO::PARAM_INT);
    $stmt->execute();
    // Fetch the products from the database and return the result as an Array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Get the total number of products
    $total_products = $pdo->query('SELECT * FROM item WHERE item.quantity<>0')->rowCount();
}

?>

<?=template_header('Products')?>
<div class ="category">
    <a href="index.php?page=products" class="catagory_button">ALL</a>
    <a href="index.php?page=products&category=T-Shirt"class="catagory_button">T-Shirt</a> 
    <a href="index.php?page=products&category=men"class="catagory_button">Men</a> 
    <a href="index.php?page=products&category=Women"class="catagory_button">Women</a> <br>
    <a href="index.php?page=products&category=jacket"class="catagory_button">Jacket</a>
    <a href="index.php?page=products&category=jeans"class="catagory_button">Jeans</a>
    <a href="index.php?page=products&category=pants"class="catagory_button">Pants</a>
    <a href="index.php?page=products&category=underwear"class="catagory_button">Underwear</a>
</div>
<div class="products content-wrapper">
    <h1> <?=ucfirst($category)?> </h1>
    <p><?=$total_products?> Items </p>
    <div class="products-wrapper">
        <?php foreach ($products as $product): ?>
        <a href="index.php?page=product&id=<?=$product['item_id']?>" class="product">
            <img src="imgs/<?=$product['img']?>" width="200" height="200" alt="<?=$product['item_name']?>">
            <span class="name"><?=$product['item_name']?></span>
            <span class="price">
                &dollar;<?=$product['price']?>
                <?php if ($product['rrp'] > 0): ?>
                <span class="rrp">&dollar;<?=$product['rrp']?></span>
                <?php endif; ?>
            </span>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="buttons">
        <?php if ($current_page > 1): ?>
            <?php if ($category == 'Products'): ?>
                <a href="index.php?page=products&p=<?=$current_page-1?>">Prev</a>
            <?php else: ?>
                <a href="index.php?page=products&category=<?php echo $category;?>&p=<?=$current_page-1?>">Prev</a>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($total_products > ($current_page * $num_products_on_each_page) - $num_products_on_each_page + count($products)): ?>
            <?php if ($category == "Products"): ?>
                <a href="index.php?page=products&p=<?=$current_page+1?>">Next</a>
            <?php else: ?>
                <a href="index.php?page=products&category=<?php echo $category;?>&p=<?=$current_page+1?>">Next</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>


