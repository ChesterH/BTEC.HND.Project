<?php	
	// Acquire company name
	require_once('includes/name.php');
	
	// Start the session
	session_start();
	
	// Add the page header
	$pageTitle = $aCName . " - User Centre";
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "User Centre");
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
    	<?php
		if((isset($_SESSION['memberID']) || isset($_COOKIE['email'])))
		{
			echo '<p>Welcome to ' . $oCName . '.</p> ' . "\n";
		}
		else
		{
			echo '<p>Login to gain full access to the site.</p>' . "\n";
		}
		?>
        <h1>User Centre</h1>
        <p>Feel free to browse the website for the range of products we offer.</p>
        <p>We currently do not offer online purchases as yet, due to demand, but instead we offer the option for you to request products for delivery.</p>
        <p>Remember, you can use the search bar near the top of the page to search for a product you want.</p>
        <p>If you have any problems, see the FAQ page or use the Contact Us form, both of which can be found under the User Centre section of the menu above.</p>
        <p>We hope you enjoy you're stay here.</p>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>