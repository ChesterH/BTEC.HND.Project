<?php
	echo '<header id="pageHeader" role="banner">' . "\n";
    echo '        <a href="index.php" title="LTS"><img src="images/lts.jpg" alt="LTS"></a>' . "\n";
    echo '    </header>' . "\n";
	echo '    <nav id="mainMenu" role="navigation">' . "\n";
	echo '        <ul class="topMenu">' . "\n";
    // Load the database connection to be used for the cart functionality if the script calling this script has not done so
    if(empty($dbc))
    {
        require_once('includes/common.php');
    }
	// The menu for the manager
	if((isset($_SESSION['email']) || (isset($_COOKIE['email']))) && ($_SESSION['userType'] == 1 || $_COOKIE['userType'] == 1))
	{
		echo '            <li'; if(strcmp($pageTitle, $aCName . ' - Homepage') == 0){echo ' class="selected"';} echo '><a href="index.php" title="The Homepage">Homepage</a></li>' . "\n";
		echo '            <li'; if(strcmp($pageTitle, $aCName . ' Staff - Management Centre') == 0){echo ' class="selected"';} if(!empty($page)){if($page == 2 || $page == 3 || $page == 4){echo ' class="selected"';}} echo '><a href="management.php" title="Management Centre">Management Centre</a>' . "\n";
		echo '			     <ul class="secondTier">' . "\n";
        echo '                   <li'; if(strcmp($pageTitle, $aCName . ' - User Profile') == 0){echo ' class="selected"';} echo '><a href="userprofile.php" title="View Your Profile">User Profile</a></li>' . "\n";
		echo '			 	     <li'; if(strcmp($pageTitle, $aCName . ' - FAQ') == 0){echo ' class="selected"';} echo '><a href="faq.php" title="FAQ">FAQ</a></li>' . "\n";
		echo '            		 <li'; if(strcmp($pageTitle, $aCName . ' - User Password Reset') == 0){echo ' class="selected"';} echo '><a href="passwordchange.php" title="Change Your Password">Change My Password</a></li>' . "\n";
		echo '			 	     <li'; if(strcmp($pageTitle, $aCName . ' - Contact Us') == 0){echo ' class="selected"';} echo '><a href="contactus.php" title="Contact Us">Contact Us</a></li>' . "\n";
		echo '			 	     <li><a href="logout.php" title="Logout">Logout</a></li>' . "\n";
		echo '			     </ul>' . "\n";
		echo '			</li>' . "\n";
		echo '           <li'; if(preg_match("/Products/", $pageTitle)){echo ' class="selected"';} echo '><a href="products.php" title="Products Page">Products</a>' . "\n";
        echo '               <ul class="secondTier">' . "\n";
        echo '                     <li'; if((strcmp($pageTitle, $aCName . ' - Products - Secondary') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Accessories') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Cases') == 0)){echo ' class="selected"';} echo '><a href="products.php?pc=1" title="Secondary">Secondary</a>' . "\n";
        echo '                          <ul class="thirdTier">' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Accessories') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=1" title="Accessories">Accessories</a></li>' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Cases') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=2" title="Cases">Cases</a></li>' . "\n";
        echo '                          </ul>' . "\n";
        echo '                  </li>' . "\n";
        echo '                   <li'; if((strcmp($pageTitle, $aCName . ' - Products - Storage') == 0) || (strcmp($pageTitle, $aCName . ' - Products - External Hard Drives') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Flash Drives') == 0)){echo ' class="selected"';} echo '><a href="products.php?pc=2" title="Storage">Storage</a>' . "\n";
        echo '                          <ul class="thirdTier">' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - External Hard Drives') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=3" title="External Hard Drives">External Hard Drives</a></li>' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Flash Drives') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=4" title="Flash Drives">Flash Drives</a></li>' . "\n";
        echo '                          </ul>' . "\n";
        echo '                  </li>' . "\n";
        echo '                   <li'; if((strcmp($pageTitle, $aCName . ' - Products - Other') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Music') == 0)){echo ' class="selected"';} echo '><a href="products.php?pc=3" title="Other">Other</a>' . "\n";
        echo '                          <ul class="thirdTier">' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Music') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=5" title="Music">Music</a></li>' . "\n";
        echo '                          </ul>' . "\n";
        echo '                  </li>' . "\n";
        echo '               </ul>' . "\n";
        echo '          </li>' . "\n";
		echo '            <li class="search">' . "\n";
		echo '				<form id="searchBar" method="get" action="search.php">' . "\n";
		echo '					<input type="text" id="searchInput" name="s" size="30" maxlength="120" placeholder="Search ' . $aCName . ' Products">' . "\n";
		echo '					<input type="submit" value="Search" class="navpLButton">' . "\n";
		echo '				</form>' . "\n";
		echo '			</li>' . "\n";
	}
	// The menu for the technicians
	else if((isset($_SESSION['email']) || (isset($_COOKIE['email']))) && ($_SESSION['userType'] == 2 || $_COOKIE['userType'] == 2))
	{
		echo '            <li'; if(strcmp($pageTitle, $aCName . ' - Homepage') == 0){echo ' class="selected"';} echo '><a href="index.php" title="The Homepage">Homepage</a></li>' . "\n";
		echo '            <li'; if(strcmp($pageTitle, $aCName . ' Staff - Technician Centre') == 0){echo ' class="selected"';} if(!empty($page)){if($page == 2 || $page == 3 || $page == 4){echo ' class="selected"';}} echo '><a href="technician.php" title="Technician Centre">Technician Centre</a>' . "\n";
		echo '			     <ul class="secondTier">' . "\n";
        echo '                   <li'; if(strcmp($pageTitle, $aCName . ' - User Profile') == 0){echo ' class="selected"';} echo '><a href="userprofile.php" title="View Your Profile">User Profile</a></li>' . "\n";
		echo '			 	     <li'; if(strcmp($pageTitle, $aCName . ' - FAQ') == 0){echo ' class="selected"';} echo '><a href="faq.php" title="FAQ">FAQ</a></li>' . "\n";
		echo '            		 <li'; if(strcmp($pageTitle, $aCName . ' - User Password Reset') == 0){echo ' class="selected"';} echo '><a href="passwordchange.php" title="Change Your Password">Change My Password</a></li>' . "\n";
		echo '			 	     <li'; if(strcmp($pageTitle, $aCName . ' - Contact Us') == 0){echo ' class="selected"';} echo '><a href="contactus.php" title="Contact Us">Contact Us</a></li>' . "\n";
		echo '			 	     <li><a href="logout.php" title="Logout">Logout</a></li>' . "\n";
		echo '			     </ul>' . "\n";
		echo '            </li>' . "\n";
		echo '            <li'; if(preg_match("/Products/", $pageTitle)){echo ' class="selected"';} echo '><a href="products.php" title="Products Page">Products</a>' . "\n";
        echo '               <ul class="secondTier">' . "\n";
        echo '                     <li'; if((strcmp($pageTitle, $aCName . ' - Products - Secondary') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Accessories') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Cases') == 0)){echo ' class="selected"';} echo '><a href="products.php?pc=1" title="Secondary">Secondary</a>' . "\n";
        echo '                          <ul class="thirdTier">' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Accessories') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=1" title="Accessories">Accessories</a></li>' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Cases') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=2" title="Cases">Cases</a></li>' . "\n";
        echo '                          </ul>' . "\n";
        echo '                  </li>' . "\n";
        echo '                   <li'; if((strcmp($pageTitle, $aCName . ' - Products - Storage') == 0) || (strcmp($pageTitle, $aCName . ' - Products - External Hard Drives') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Flash Drives') == 0)){echo ' class="selected"';} echo '><a href="products.php?pc=2" title="Storage">Storage</a>' . "\n";
        echo '                          <ul class="thirdTier">' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - External Hard Drives') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=3" title="External Hard Drives">External Hard Drives</a></li>' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Flash Drives') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=4" title="Flash Drives">Flash Drives</a></li>' . "\n";
        echo '                          </ul>' . "\n";
        echo '                  </li>' . "\n";
        echo '                   <li'; if((strcmp($pageTitle, $aCName . ' - Products - Other') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Music') == 0)){echo ' class="selected"';} echo '><a href="products.php?pc=3" title="Other">Other</a>' . "\n";
        echo '                          <ul class="thirdTier">' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Music') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=5" title="Music">Music</a></li>' . "\n";
        echo '                          </ul>' . "\n";
        echo '                  </li>' . "\n";
        echo '               </ul>' . "\n";
        echo '            </li>' . "\n";
		echo '            <li class="search">' . "\n";
		echo '				<form id="searchBar" method="get" action="search.php">' . "\n";
		echo '					<input type="text" id="searchInput" name="s" size="30" maxlength="120" placeholder="Search ' . $aCName . ' Products">' . "\n";
		echo '					<input type="submit" value="Search" class="navpLButton">' . "\n";
		echo '				</form>' . "\n";
		echo '			</li>' . "\n";
	}
	// The menu for logged-in customers
	else if((isset($_SESSION['email']) || (isset($_COOKIE['email']))) && ($_SESSION['userType'] == 3 || $_COOKIE['userType'] == 3))
	{
		echo '            <li'; if(strcmp($pageTitle, $aCName . ' - Homepage') == 0){echo ' class="selected"';} echo '><a href="index.php" title="The Homepage">Homepage</a></li>' . "\n";
		echo '            <li'; if(strcmp($pageTitle, $aCName . ' - User Centre') == 0){echo ' class="selected"';} else if(!empty($page)){if($page == 1 || $page == 2 || $page == 3 || $page == 4){echo ' class="1selected"';}} echo '><a href="usercentre.php" title="User Centre">User Centre</a>' . "\n";
		echo '			     <ul class="secondTier">' . "\n";
		echo '                     <li'; if(strcmp($pageTitle, $aCName . ' - User Profile') == 0){echo ' class="selected"';} echo '><a href="userprofile.php" title="View Your Profile">User Profile</a></li>' . "\n";
		echo '            		 <li'; if(strcmp($pageTitle, $aCName . ' - Repair Projects') == 0){echo ' class="selected"';} echo '><a href="projects.php" title="Repair Projects">Repair Projects</a></li>' . "\n";
		echo '			 	     <li'; if(strcmp($pageTitle, $aCName . ' - FAQ') == 0){echo ' class="selected"';} echo '><a href="faq.php" title="FAQ">FAQ</a></li>' . "\n";
		echo '            		 <li'; if(strcmp($pageTitle, $aCName . ' - User Password Reset') == 0){echo ' class="selected"';} echo '><a href="passwordchange.php" title="Change Your Password">Change My Password</a></li>' . "\n";
		echo '            		 <li'; if(strcmp($pageTitle, $aCName . ' - Contact Us') == 0){echo ' class="selected"';} echo '><a href="contactus.php" title="Contact Us">Contact Us</a></li>' . "\n";
		echo '            		 <li><a href="logout.php" title="Logout">Logout</a></li>' . "\n";
		echo '			     </ul>' . "\n";
		echo '			</li>' . "\n";
		echo '           <li'; if(preg_match("/Products/", $pageTitle)){echo ' class="selected"';} echo '><a href="products.php" title="Products Page">Products</a>' . "\n";
        echo '               <ul class="secondTier">' . "\n";
        echo '                     <li'; if((strcmp($pageTitle, $aCName . ' - Products - Secondary') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Accessories') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Cases') == 0)){echo ' class="selected"';} echo '><a href="products.php?pc=1" title="Secondary">Secondary</a>' . "\n";
        echo '                          <ul class="thirdTier">' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Accessories') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=1" title="Accessories">Accessories</a></li>' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Cases') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=2" title="Cases">Cases</a></li>' . "\n";
        echo '                          </ul>' . "\n";
        echo '                  </li>' . "\n";
        echo '                   <li'; if((strcmp($pageTitle, $aCName . ' - Products - Storage') == 0) || (strcmp($pageTitle, $aCName . ' - Products - External Hard Drives') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Flash Drives') == 0)){echo ' class="selected"';} echo '><a href="products.php?pc=2" title="Storage">Storage</a>' . "\n";
        echo '                          <ul class="thirdTier">' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - External Hard Drives') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=3" title="External Hard Drives">External Hard Drives</a></li>' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Flash Drives') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=4" title="Flash Drives">Flash Drives</a></li>' . "\n";
        echo '                          </ul>' . "\n";
        echo '                  </li>' . "\n";
        echo '                   <li'; if((strcmp($pageTitle, $aCName . ' - Products - Other') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Music') == 0)){echo ' class="selected"';} echo '><a href="products.php?pc=3" title="Other">Other</a>' . "\n";
        echo '                          <ul class="thirdTier">' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Music') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=5" title="Music">Music</a></li>' . "\n";
        echo '                          </ul>' . "\n";
        echo '                  </li>' . "\n";
        echo '               </ul>' . "\n";
        echo '          </li>' . "\n";
		echo '            <li class="search">' . "\n";
		echo '				<form id="searchBar" method="get" action="search.php">' . "\n";
		echo '					<input type="text" id="searchInput" name="s" size="30" maxlength="120" placeholder="Search ' . $aCName . ' Products">' . "\n";
		echo '					<input type="submit" value="Search" class="navpLButton">' . "\n";
		echo '				</form>' . "\n";
		echo '			</li>' . "\n";
	}
	// The menu for users who are not logged-in
	else
	{
		echo '            <li'; if(strcmp($pageTitle, $aCName . ' - Homepage') == 0){echo ' class="selected"';} echo '><a href="index.php" title="The Homepage">Homepage</a></li>' . "\n";
		echo '            <li'; if(strcmp($pageTitle, $aCName . ' - User Centre') == 0){echo ' class="selected"';} if(!empty($page)){if($page == 2 || $page == 3 || $page == 4 || $page == 5 || $page == 6){echo ' class="selected"';}} echo '><a href="usercentre.php" title="User Centre">User Centre</a>' . "\n";
		echo '			     <ul class="secondTier">' . "\n";
		echo '			 	     <li'; if(strcmp($pageTitle, $aCName . ' - FAQ') == 0){echo ' class="selected"';} echo '><a href="faq.php" title="FAQ">FAQ</a></li>' . "\n";
		echo '			 	     <li'; if(strcmp($pageTitle, $aCName . ' - User Login') == 0){echo ' class="selected"';} echo '><a href="login.php" title="Login">Login</a></li>' . "\n";
		echo '			 	     <li'; if(strcmp($pageTitle, $aCName . ' - User Registration') == 0){echo ' class="selected"';} echo '><a href="register.php" title="Register">Register</a></li>' . "\n";
		echo '            		 <li'; if(strcmp($pageTitle, $aCName . ' - Contact Us') == 0){echo ' class="selected"';} echo '><a href="contactus.php" title="Contact Us">Contact Us</a></li>' . "\n";
		echo '			     </ul>' . "\n";
		echo '			</li>' . "\n";
		echo '           <li'; if(preg_match("/Products/", $pageTitle)){echo ' class="selected"';} echo '><a href="products.php" title="Products Page">Products</a>' . "\n";
        echo '               <ul class="secondTier">' . "\n";
        echo '                     <li'; if((strcmp($pageTitle, $aCName . ' - Products - Secondary') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Accessories') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Cases') == 0)){echo ' class="selected"';} echo '><a href="products.php?pc=1" title="Secondary">Secondary</a>' . "\n";
        echo '                          <ul class="thirdTier">' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Accessories') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=1" title="Accessories">Accessories</a></li>' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Cases') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=2" title="Cases">Cases</a></li>' . "\n";
        echo '                          </ul>' . "\n";
        echo '                  </li>' . "\n";
        echo '                   <li'; if((strcmp($pageTitle, $aCName . ' - Products - Storage') == 0) || (strcmp($pageTitle, $aCName . ' - Products - External Hard Drives') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Flash Drives') == 0)){echo ' class="selected"';} echo '><a href="products.php?pc=2" title="Storage">Storage</a>' . "\n";
        echo '                          <ul class="thirdTier">' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - External Hard Drives') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=3" title="External Hard Drives">External Hard Drives</a></li>' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Flash Drives') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=4" title="Flash Drives">Flash Drives</a></li>' . "\n";
        echo '                          </ul>' . "\n";
        echo '                  </li>' . "\n";
        echo '                   <li'; if((strcmp($pageTitle, $aCName . ' - Products - Other') == 0) || (strcmp($pageTitle, $aCName . ' - Products - Music') == 0)){echo ' class="selected"';} echo '><a href="products.php?pc=3" title="Other">Other</a>' . "\n";
        echo '                          <ul class="thirdTier">' . "\n";
        echo '                              <li'; if(strcmp($pageTitle, $aCName . ' - Products - Music') == 0){echo ' class="selected"';} echo '><a href="products.php?sc=5" title="Music">Music</a></li>' . "\n";
        echo '                          </ul>' . "\n";
        echo '                  </li>' . "\n";
        echo '               </ul>' . "\n";
        echo '          </li>' . "\n";
		echo '            <li class="search">' . "\n";
		echo '				<form id="searchBar" method="get" action="search.php">' . "\n";
		echo '					<input type="text" id="searchInput" name="s" size="30" maxlength="120" placeholder="Search ' . $aCName . ' Products">' . "\n";
		echo '					<input type="submit" value="Search" class="navpLButton">' . "\n";
		echo '				</form>' . "\n";
		echo '			</li>' . "\n";
	}
	echo '        </ul>' . "\n";
	echo '    </nav>' . "\n";
    if($breadcrumbs)
    {
        echo '    <section id="userBar">' . "\n";
        echo '        <span class="breadcrumb">' . "\n";
        $i = 0;
        while($i < count($breadcrumbs))
        {
            if(($i + 3) < count($breadcrumbs))
            {
                echo '            <a href="' . $breadcrumbs[$i] . '" title="' . $breadcrumbs[$i+1] . '">'  . $breadcrumbs[$i+2] . '</a> &gt; ' . "\n";
            }
            else
            {
                echo '            ' . $breadcrumbs[$i] . "\n";
            }
            $i+=3; 
        }
        echo '        </span>' . "\n";
    }
    // Acquire cart data
	if(((isset($_SESSION['email']) || (isset($_COOKIE['email']))) && ($_SESSION['userType'] == 3 || $_COOKIE['userType'] == 3) && $breadcrumbs))
    {
        $memberID = $_SESSION['userID'];
        $query = "SELECT pCartID, productID FROM product_cart_tbl WHERE cartID = (SELECT cartID FROM cart_tbl WHERE memberID = '$memberID')";
        $result = mysqli_query($dbc, $query);
        // Determine the total cost of products in the cart
        $cartProductsTotal = 0;
        $productsList = mysqli_query($dbc, $query);
        while($productsRow = mysqli_fetch_array($productsList))
        {
            $productID = $productsRow['productID'];
            $totalQuery = "SELECT price FROM product_tbl WHERE productID = '$productID'";
            $totalCartResults = mysqli_query($dbc, $totalQuery);
            while($priceRow = mysqli_fetch_array($totalCartResults))
            {
                // Acquire the total cost of the items in the cart
                $cartProductsTotal += $priceRow['price'];
            }
            // Store the total in a session variable
            $cartProductsTotal = number_format((float)$cartProductsTotal, 2, '.', '');
        }
        $productID = 0;
        echo '        <a href="cart.php" title="View Your Cart (Total: &#36;';
        if(isset($cartProductsTotal))
        {
            echo $cartProductsTotal;
        }
        else
        {
            echo '0.00';
        }
        echo ')" class="button usercart">Cart ';
        if(mysqli_num_rows($result) != 0)
        {
            echo '(' . mysqli_num_rows($result) . ')';
        }
        else
        {
            echo '(0)';
        }
        echo '</a>' . "\n";
    }
    if($breadcrumbs)
    {
        echo '    </section>' . "\n";
    }
?>