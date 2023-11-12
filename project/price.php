<?php
//update amount to database
//wrong answer
if (isset($_POST['product_id'], $_POST['price']) ){
    
    // Set the post variables so we easily identify them, also make sure they are integer
    $product_id = (int)$_POST['product_id'];
    $price = (int)$_POST['price'];
    //database
    pdo_connect_mysql();
   
    $query = 'UPDATE item
    SET price= :price
    WHERE item_id= :id';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":price", $price);
    $stmt->bindParam(":id", $product_id);
    if ($stmt->execute()) {
        //echo "Insert Success!";
    } else {
        echo $stmt->error;
    }
}

?>
<?=template_manager('price')?>
<div class="placeorder content-wrapper">
    <form action="" method="post">
        <p>Change successful!</p>
        <a href="index.php?page=manage_product&id=<?=$product_id?>" class="product">Back</a>
    </form>
</div> 
<?=template_footer()?>

