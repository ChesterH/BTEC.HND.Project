<?php	
	// Acquire company name
	require_once('includes/name.php');
    
    // Acquire the connection data for the database
    require_once('includes/common.php');
    
	// Start the session
	session_start();
    
    // If the user is not logged in, redirect them to the homepage (a user who is not logged-in as a regular user has no reason to access the cart page)
    if(!isset($_SESSION['email']) || !isset($_COOKIE['email']) || $_SESSION['userType'] != 3)
    {
        require_once('includes/homeurl.php');
        $homeURL .= 'index.php';
        die(header('Location: ' . $homeURL));
    }
    
    // Define the tracking variables
    $address = '';
    $confirm = false;
    $memberID = $_SESSION['userID'];
    $order = false;
    $process = 0;
    $success = 0;
    
    // Check for a request to add a product to the cart by product ID
    if(!empty($_GET['p']))
    {
        $productID = mysqli_real_escape_string($dbc, trim($_GET['p']));
        $query = "INSERT INTO product_cart_tbl (cartID, productID) VALUES ((SELECT cartID FROM cart_tbl WHERE memberID = '$memberID'), '$productID')";
        $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with adding the selected product to your cart. Please try again or contact support if the problem persists.';
        $success = 1;
    }
    // Check for a request to remove a product from the cart by product ID
    if(!empty($_GET['r']))
    {
        $pCartID = mysqli_real_escape_string($dbc, trim($_GET['r']));
        // First check to see if the product being requested for removal from the cart was in the cart at the time of the request
        $query = "SELECT * FROM product_cart_tbl WHERE pCartID = '$pCartID'";
        $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with confirming the presence of the selected item in your cart. Please try again or contact support if the problem persists.';
        $row = mysqli_fetch_array($result);
        // Only attempt to remove an item if it was found in the cart
        if(!empty($row))
        {
            $query = "DELETE FROM product_cart_tbl WHERE pCartID = '$pCartID'";
            $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with removing the selected item from your cart. Please try again or contact support if the problem persists.';
            $success = 2;
        }
        else
        {
            $errorMsg = 'Error: There appeared to be an attempt to remove a product that was not in your cart. No action was taken as a result.';
        }
    }
    // Check if the order was confirmed
    if(!empty($_GET['c']))
    {
        $cCode = mysqli_real_escape_string($dbc, trim($_GET['c']));
        // Check if the confirmation code was stored in the session variable, if not, the confirmation is not valid, so redirect them to the cart page
        if(isset($_SESSION['cCode']))
        {
            // If the code in the URL matches the one stored in the session by this script, confirm the order
            if(strcmp($cCode, $_SESSION['cCode']) == 0)
            {
                $confirm = true;
            }
            else
            {
                require_once('includes/homeurl.php');
                $homeURL .= 'cart.php';
                die(header('Location: ' . $homeURL));
            }
        }
        else
        {
            require_once('includes/homeurl.php');
            $homeURL .= 'cart.php';
            die(header('Location: ' . $homeURL));
        }
    }
    // Check if the address was submitted
    if(isset($_POST['submitAddress']))
    {
        $address = mysqli_real_escape_string($dbc, trim($_POST['address']));
        if(!empty($address))
        {
            $order = true;
        }
        else
        {
            // The order confirmation variable must be set to true to allow the address form to display
            $confirm = true;
            $errorMsg = 'The address you provided was empty. Please try again or contact support if the problem persists.';
        }
    }
    // If the URL contains unknown information the user will be redirected to this page
    if(empty($_GET['p']) && empty($_GET['r']) && empty($_GET['c']) && !empty($_GET))
    {
        require_once('includes/homeurl.php');
        $homeURL .= 'cart.php';
        die(header('Location: ' . $homeURL));
    }
    
    // Add the page header
    $pageTitle = $aCName . " - User Cart";
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "User Cart");
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
    	<?php
    	// If an error was encountered and logged, display it, otherwise don't add the markup
    	if(!empty($errorMsg))
        {
            echo '<p class="errorMsg">' . $errorMsg . '</p>' . "\n";
        }
        // Check to see if the query for the cart made in the header script was empty and no other functionality in this script was triggered
    	if((mysqli_num_rows($result) == 0) && ($process == 0) && ($success == 0) && (!$confirm))
        {
        ?>
            <p>Your cart is empty.</p>
        <?php
        }
        // Check if a product was successfully added to the cart
        if($success == 1 && (!$confirm) && empty($errorMsg))
        {
        ?>
        <h1>User Cart</h1>
        <p>The product was successfully added to your cart.</p>
        <p>You can either <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Continue Browsing Products" class="pLink">continue browsing</a> or you can check your <a href="cart.php" title+"Your Cart" class="pLink">cart</a></p>
        <?php
        }
        // Check if a product was successfully removed from the cart
        else if($success == 2 && (!$confirm) && empty($errorMsg))
        {
        ?>
        <h1>User Cart</h1>
        <p>The product was successfully removed from your cart.</p>
        <p><a href="cart.php" title="Return to Your Cart" class="button">Return to cart</a></p>
        <?php
        }
        // Check if the user requested to confirm the order
        else if($process == 1 && (!$confirm) && empty($errorMsg))
        {
        ?>
        <h1>Order Confirmed</h1>
        <p>Your order was confirmed.</p>
        <p>Your total is: &#36;<?php echo $cartProductsTotal; // Use the previously set session variable ?>.</p>
        <p><a href="cart.php" title="Return to Your Cart" class="button">Return to cart</a></p>
        <?php
        }
        // Check if there are products in the cart  and no other function in this script was triggered 
        if((mysqli_num_rows($result) != 0) && ($success == 0) && ($process == 0) && (!$confirm) && (!$order))
        {
		?>
        <h1>User Cart</h1>	    
		<p>The contents of your cart are as follows:</p>
		<div id="cartFrame">
            <table id="cartTable">
        <?php
        // Use the following variables to track the number of the products as they are retrieved and the total cost of the products in the cart
        $i = 1;
        
        // Add the table column names
        echo '        <tr><th>Cart Item Number</th><th>Product Name</th><th>Product Details</th><th>Product Price</th><th></th></tr>' . "\n";
        
        // Use the products data in $result which was generated in the page header
        while($row = mysqli_fetch_array($result))
        {
            $productID = $row['productID'];
            $query = "SELECT * FROM product_tbl WHERE productID = '$productID'";
            $results = mysqli_query($dbc, $query);
            while($row2 = mysqli_fetch_array($results))
            {
                // Display the products
                echo '                <tr><td>' . $row['pCartID'] . '</td>' . "\n";
                echo '                <td><a href="products.php?p='. $row2['productID'] . '" title="'. $row2['product'] . '">'. $row2['product'] . '</a></td>' . "\n";
                echo '                <td><p>' . $row2['details'] . '</p></td>' . "\n";
                echo '                <td>&#36;' . $row2['price'] . '</td>' . "\n";
                echo '                <td><a href="cart.php?r=' . $row['pCartID'] . '" title="Remove from Cart">Remove</a></td></tr>' . "\n";
            }
        }
        ?>
            </table>
            <p>The total cost of the items in your cart is: &#36;<?php echo $cartProductsTotal; ?></p>
        <?php
        // Generate random string of characters to use as the confirmation code
        // Define list of characters which can be present in the confirmation code
        $characters = 'bcdfghjklmnpqrstvwxyz123456789';
        $code = '';
        // Generate the 10 character long confirmation code
        for($i = 0; $i < 10; $i++)
        {
            // Randomly select a character from the defined list and append it to the confirmation code string
            $code .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        // Store the randomly generated code in a session variable to check at the top of the script
        $_SESSION['cCode'] = $code;
        ?><a href="cart.php?c=<?php echo $code; ?>" title="Checkout" class="button mt1">Checkout</a>
        </div>
        <?php  
		}
        if($confirm)
        {
        ?>
        <h1>Order Confirmation</h1>
        <p>Please complete the following form to provide the shipping address for your order.</p>
        <div id="orderDetailsFormFrame">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <fieldset><legend>Order Details</legend>
            <table id="orderDetailsTable">
                <tr>
                    <th><label>Address:</label></th>
                    <td><textarea id="address" name="address" rows="5" cols="40"><?php if(!empty($address)) echo $address; // If the user typed in a bad message the details field will retain the message they entered. ?></textarea></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Submit" name="submitAddress" title="Submit Form" class="button" /></td>
                    <td><input type="reset" value="Reset" title="Reset Form" class="button" /></td>
                </tr>
            </table>
            </fieldset>
            </form>
        </div>
        <?php
        }
        if($order)
        {
        ?>
        <h1>Order Confirmed</h1>
        <p>Your order was confirmed. The items will be delivered to the address you provided soon.</p>
        <p><strong>Address</strong>:</p>
        <?php
            $address = str_replace('\r\n', '<br>', $address);
            echo $address;
		?>
		<p><strong>Order Information:</strong></p>
		<?php
    		// Use the products data in $result which was generated in the page header
            while($row = mysqli_fetch_array($result))
            {
                $productID = $row['productID'];
                $query = "SELECT * FROM product_tbl WHERE productID = '$productID'";
                $results = mysqli_query($dbc, $query);
                while($row2 = mysqli_fetch_array($results))
                {
                    // Display the products' names
                    echo '<p>' . $row2['product'] . ': &#36;' . $row2['price'] . '</p>' . "\n";
                }
            }
            echo '<p>Total cost : &#36;' . $cartProductsTotal . '</p>' . "\n";
        }
        ?>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>