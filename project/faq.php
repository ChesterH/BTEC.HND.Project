<?php	
	// Acquire company name
	require_once('includes/name.php');
	
	// Start the session
	session_start();
	
	// Add the page header
	$pageTitle = $aCName . " - FAQ";
	$page = 2;
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "usercentre.php", "The User Centre", "User Centre", "FAQ");
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
        <h1><?php echo $aCName; ?> - FAQ</h1>
        <strong>General</strong>
        <article class="mt1 mb2">
            <p>Q. What are the benefits of registering?</p>
            <p>A. You will be able to check any current repair projects you have with <?php echo $fCName; ?></p>
        </article>
        <article class="mb2">
            <p>Q. Are there any restrictions to using the website without registering?</p>
            <p>A. Other than ordering/purchasing products, as well as the services which cannot be offered to anonymous users (the repair service), there are no restrictions. You can still browse and search the website for products and view their details.</p>
        </article>
        <strong>Registration</strong>
        <article class="mt1 mb2">
            <p>Q. Why must I be at least 18 years of age on the day I register with this website?</p>
            <p>A. Registration provides you access to services requiring payment. While most items can be bought in the physical store without such a restriction, <?php echo $fCName; ?>'s website will offer online payment options in the future, and intends to restrict all such purchases to persons over the age of 18.</p>
        </article>
        <strong>Search</strong>
        <article class="mt1 mb2">
            <p>Q. I tried searching for a type of product I saw on the site but I found nothing. What is the problem?</p>
            <p>A. The site's internal search functionality tries to match the search terms to a product's name. To find a product you will need to search by its name.</p>
        </article>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>