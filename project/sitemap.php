<?php
	// Acquire company name
	require_once('includes/name.php');
	
	// Start the session
	session_start();
	
	// Add the page header
	$pageTitle = $aCName . " - Sitemap";
    
    // Add the breadcrumbs
    $breadcrumbs = array($aCName . " Sitemap");
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
        <h1>Sitemap</h1>
        <section class="sLink">
            <ul>
                <li><a href="index.php" title="<?php echo $aCName; ?> Homepage" class="pLink">Homepage</a>
                <ul>
                    <?php
                    if((isset($_SESSION['email']) || (isset($_COOKIE['email']))) && ($_SESSION['userType'] == 1 || $_COOKIE['userType'] == 1))
                    {
                    ?><li><a href="management.php" title="ManagementCentre" class="pLink">Management Centre</a>
                    <?php
                    }
                    else if((isset($_SESSION['email']) || (isset($_COOKIE['email']))) && ($_SESSION['userType'] == 2 || $_COOKIE['userType'] == 2))
                    {
                    ?><li><a href="technician.php" title="Technician Centre" class="pLink">Technician Centre</a>
                    <?php
                    }
                    else
                    {
                    ?><li><a href="usercentre.php" title="User Centre" class="pLink">User Centre</a>
                    <?php
                    }
                    ?><ul>
                        <li><a href="userprofile.php" title="View Your Profile" class="pLink">User Profile</a></li>
                        <?php
                        if((isset($_SESSION['email']) || (isset($_COOKIE['email']))) && ($_SESSION['userType'] == 3 || $_COOKIE['userType'] == 3))
                        {
                        ?><li><a href="projects.php" title="Repair Projects" class="pLink">Repair Projects</a></li>
                        <?php
                        }
                        ?><li><a href="faq.php" title="FAQ" class="pLink">FAQ</a></li>
                        <li><a href="passwordchange.php" title="Change Your Password" class="pLink">Change Your Password</a></li>
                        <li><a href="contactus.php" title="Contact Us" class="pLink">Contact Us</a></li>
                    </ul>
                    </li>
                    <li><a href="products.php" title="products.php" class="pLink">Products</a>
                    <ul>
                        <li><a href="products.php?pc=1" title="Secondary" class="pLink">Secondary</a>
                        <ul>
                            <li><a href="products.php?sc=1" title="Accessories" class="pLink">Accessories</a></li>
                            <li><a href="products.php?sc=2" title="Cases" class="pLink">Cases</a></li>
                        </ul>
                        </li>
                        <li><a href="products.php?pc=2" title="Storage" class="pLink">Storage</a>
                        <ul>
                            <li><a href="products.php?sc=3" title="External Hard Drives" class="pLink">External Hard Drives</a></li>
                            <li><a href="products.php?sc=4" title="Flash Drives" class="pLink">Flash Drives</a></li>
                        </ul>
                        </li>
                        <li><a href="products.php?pc=3" title="Other" class="pLink">Other</a>
                        <ul>
                            <li><a href="products.php?sc=5" title="Music" class="pLink">Music</a></li>
                        </ul>
                        </li>
                    </ul>
                    </li>
                    <?php
                    if((isset($_SESSION['email']) || (isset($_COOKIE['email']))) && ($_SESSION['userType'] == 3 || $_COOKIE['userType'] == 3))
                    {
                    ?><li><a href="cart.php" title="User Cart" class="pLink">User Cart</a></li>
                    <?php
                    }
                    ?><li><a href="aboutus.php" title="About Us" class="pLink">About Us</a></li>
                </ul>
                </li>
            </ul>
        </section>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>