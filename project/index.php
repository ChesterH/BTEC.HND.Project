<?php
	// Acquire company name
	require_once('includes/name.php');
	
	// Start the session
	session_start();
	
	// Add the page header
	$pageTitle = $aCName . " - Homepage";
    
    // Add the breadcrumbs
    $breadcrumbs = array($aCName . " Home");
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
        <h1>News</h1>
        <article>
            <h2>Site Launch</h2>
            <p><?php echo $fCName; ?> (<?php echo $aCName; ?>) has launched a new website to showcase its products and allow its clients to check the status of repair projects they started with it.</p>
            <p>Users can browse the full extent of products the business has at its store as well as communicate with the business through emails.</p>
        </article>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>