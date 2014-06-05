<?php
	// Acquire company name
	require_once('includes/name.php');
	
	// Start the session
	session_start();
	
	// Add the page header
	$pageTitle = $aCName . " - About Us";
	$page = 3;
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "About Us");
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
        <h1>About Us</h1>
        <p><?php echo $fCName; ?>, or <?php echo $aCName; ?>, is a small IT business which specialises in repairing computers and a variety of other related devices such as tablets and peripherals.</p>
        <p><?php echo $aCName; ?> also sells a wide range of computer related products.</p>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>