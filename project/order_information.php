<?php

$stmt = $pdo->prepare('SELECT * FROM orders');
$stmt->execute();
$informations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?=template_manager('order_information')?>

<div class="order information content-wrapper">
    <h1>order information</h1>
    <form action="index.php?page=order_information" method="post">
        <table>
            <thead>
                <tr>
                    <td>Order ID</td>
                    <td>Total price</td>
                    <td>Shipping name</td>
                    <td>Shipping address</td>
                    <td>Shipping phone</td>
                    <td>Shipping email</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($informations)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;">You have no order.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($informations as $information): ?>
                <tr>
                    <td class="order_id"><?=$information['order_id']?></td>
                    <td class="total_price">&dollar;<?=$information['total_price']?></td>
                    <td class="name"><?=$information['shipping_name']?></td>
                    <td class="address"><?=$information['shipping_address']?></td>
                    <td class="phone"><?=$information['shipping_phone']?></td>
                    <td class="email"><?=$information['shipping_email']?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>

<?=template_footer()?>