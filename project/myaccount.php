<?php

$error = '';
// User clicked the "Login" button, proceed with the login process... check POST data and validate email
if (isset($_POST['login'], $_POST['email'], $_POST['password']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    // Check if the account exists
    $stmt = $pdo->prepare('SELECT * FROM customer WHERE account = ?');
    $stmt->execute([ $_POST['email'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    // If account exists verify password
    if ($account && $_POST['password'] == $account['password']) {
        // User has logged in, create session data
        session_regenerate_id();
        $_SESSION['account_loggedin'] = TRUE;
        $_SESSION['account_id'] = $account['customer_id'];
        $_SESSION['account_role'] = $account['role'];
        $products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if ($products_in_cart) {
            // user has products in cart, redirect them to the checkout page
            header('Location: ' . url('index.php?page=checkout'));
        } else {
            // Redirect the user back to the same page, they can then see their order history
            header('Location: ' . url('index.php?page=myaccount'));
        }
        exit;
    } else {
        $error = 'Incorrect Email/Password!';
    }
}
// Variable that will output registration errors
$register_error = '';
// User clicked the "Register" button, proceed with the registration process... check POST data and validate email
if (isset($_POST['register'], $_POST['email'], $_POST['password'], $_POST['cpassword']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    // Check if the account exists
    $stmt = $pdo->prepare('SELECT * FROM customer WHERE account = ?');
    $stmt->execute([ $_POST['email'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($account) {
        // Account exists!
        $register_error = 'Account already exists with that email!';
    } else if ($_POST['cpassword'] != $_POST['password']) {
        $register_error = 'Passwords do not match!';
    } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
        // Password must be between 5 and 20 characters long.
        $register_error = 'Password must be between 5 and 20 characters long!';
    } else {
        // Account doesnt exist, create new account
        $stmt = $pdo->prepare('INSERT INTO customer (account, password, role) VALUES (?,?,?)');
        // Hash the password
        $password = $_POST['password'];
        $stmt->execute([ $_POST['email'], $password, 'member']);
        $account_id = $pdo->lastInsertId();
        // Automatically login the user
        session_regenerate_id();
        $_SESSION['account_loggedin'] = TRUE;
        $_SESSION['account_id'] = $account_id;
        $_SESSION['account_role'] = 'Member';
        $products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if ($products_in_cart) {
            // User has products in cart, redirect them to the checkout page
            header('Location: ' . url('index.php?page=checkout'));
        } else {
            // Redirect the user back to the same page, they can then see their order history
            header('Location: ' . url('index.php?page=myaccount'));
        }
        exit;
    }
}
// Determine the current tab page
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'orders';
// If user is logged in
if (isset($_SESSION['account_loggedin'])) {
    // Select all the users transations, which will appear under "My Orders"
    $stmt = $pdo->prepare('SELECT * FROM orders NATURAL JOIN buy WHERE customer_id = ? ORDER BY created DESC');
    $stmt->execute([ $_SESSION['account_id'] ]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Select all the users transations, which will appear under "My Orders"
    $stmt = $pdo->prepare('SELECT
        p.item_name,
        p.item_id AS id,
        p.img AS img,
        t.order_id AS order_id,
        t.created AS transaction_date,
        t.total_price AS total_price,
        p.price AS price,
        ti.quantity AS quantity,
        ti.item_id
        FROM orders t
        JOIN buy b ON t.order_id = b.order_id
        JOIN customer a ON b.customer_id = a.customer_id
        JOIN order_contain ti ON t.order_id = ti.order_id
        JOIN item p ON ti.item_id = p.item_id
        WHERE b.customer_id = ?
        ORDER BY t.created DESC');
    $stmt->execute([ $_SESSION['account_id'] ]);
    $transactions_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retrieve account details
    $stmt = $pdo->prepare('SELECT * FROM customer WHERE customer_id = ?');
    $stmt->execute([ $_SESSION['account_id'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    // Update settings
    if (isset($_POST['save_details'], $_POST['email'], $_POST['password'])) {
        // Assign and validate input data
        $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        // Check if account exists with captured email
        $stmt = $pdo->prepare('SELECT * FROM customer WHERE account = ?');
        $stmt->execute([ $_POST['email'] ]);
        // Validation
        if ($_POST['email'] != $account['email'] && $stmt->fetch(PDO::FETCH_ASSOC)) {
            $error = 'Account already exists with that email!';
        } else if ($_POST['password'] && (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5)) {
            $error = 'Password must be between 5 and 20 characters long!';
        } else {
            // Update account details in database
            $password = $_POST['password'] ? $_POST['password'] : $account['password'];
            $stmt = $pdo->prepare('UPDATE accounts SET email = ?, password = ? WHERE id = ?');
            $stmt->execute([ $_POST['email'], $password, $_SESSION['account_id'] ]);
            // Redirect to settings page
            header('Location: ' . url('index.php?page=myaccount&tab=settings'));
            exit;           
        }
    }
}
?>



<?=template_header('My Account')?>

<div class="myaccount content-wrapper">

    <?php if (!isset($_SESSION['account_loggedin'])): ?>

    <div class="login-register">

        <div class="login">

            <h1>Login</h1>

            <form action="" method="post">

                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" placeholder="john@example.com" required class="form-field">

                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" placeholder="Password" required class="form-field">

                <input name="login" type="submit" value="Login" class="btn">

            </form>

            <?php if ($error): ?>
            <p class="error"><?=$error?></p>
            <?php endif; ?>

        </div>

        <div class="register">

            <h1>Register</h1>

            <form action="" method="post">

                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" placeholder="john@example.com" required class="form-field">

                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" placeholder="Password" required class="form-field">

                <label for="cpassword" class="form-label">Confirm Password</label>
                <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" required class="form-field">

                <input name="register" type="submit" value="Register" class="btn">

            </form>

            <?php if ($register_error): ?>
            <p class="error"><?=$register_error?></p>
            <?php endif; ?>

        </div>

    </div>

    <?php else: ?>

    <h1>My Account</h1>

    <div class="menu">

        <h2>Menu</h2>
        
        <div class="menu-items">
            <a href="<?=url('index.php?page=myaccount')?>">Orders</a>
            <a href="<?=url('index.php?page=myaccount&tab=settings')?>">Settings</a>
        </div>

    </div>

    <?php if ($tab == 'orders'): ?>
    <div class="myorders">

        <h2>My Orders</h2>

        <?php if (empty($transactions)): ?>
        <p>You have no orders</p>
        <?php endif; ?>
        <?php foreach ($transactions as $transaction): ?>
        <div class="order">
            <div class="order-header">
                <div>
                    <div><span>Order</span># <?=$transaction['order_id']?></div>
                    <div class="rhide"><span>Date</span><?=date('F j, Y', strtotime($transaction['created']))?></div>
                </div>
                <div>
                    <div><span>Total</span><?=currency_code?><?=number_format($transaction['total_price'],2)?></div>
                </div>
            </div>
            <div class="order-items">
                <table>
                    <tbody>
                        <?php foreach ($transactions_items as $transaction_item): ?>
                        <?php if ($transaction_item['order_id'] != $transaction['order_id']) continue; ?>
                        <tr>
                            <td class="img">
                                <?php if (!empty($transaction_item['img'])): ?>
                                <img src="imgs/<?=$transaction_item['img']?>" width="50" height="50" alt="<?=$transaction_item['item_name']?>">
                                <?php endif; ?>
                            </td>
                            <td class="name"><?=$transaction_item['quantity']?> x <?=$transaction_item['item_name']?></td>
                            <td class="price"><?=currency_code?><?=number_format($transaction_item['price'] * $transaction_item['quantity'],2)?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>                
            </div>
        </div>
        <?php endforeach; ?>


    </div>

    <?php elseif ($tab == 'settings'): ?>
    <div class="settings">

        <h2>Settings</h2>

        <form action="" method="post">

            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" placeholder="Email" value="<?=htmlspecialchars($account['account'], ENT_QUOTES)?>" class="form-field" required>

            <label for="password" class="form-label">New Password</label>
            <input type="password" id="password" name="password" placeholder="New Password" value="" autocomplete="new-password" class="form-field">

            <input name="save_details" type="submit" value="Save" class="btn">

        </form>

    </div>

    <?php endif; ?>

    <?php endif; ?>

</div>


<?=template_footer()?>