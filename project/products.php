<?php
    // Acquire company name
    require_once('includes/name.php');
    
    // Acquire the connection data for the database
    require_once('includes/common.php');
    
    // Start the session
    session_start();
    
    // Define the display limit per page for products
    define('PRODUCTS_PER_PAGE', '5');

    // Define image directory
    require_once('includes/homeurl.php');
    $imageURL = $homeURL . 'images/';
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home");
    
    // If there no information is passed via the URL, then the user has requested the default products page
    if(empty($_GET['pc']) && empty($_GET['sc']) && empty($_GET['p']) && empty($_GET))
    {
        $pageTitle = $aCName . " - Products";
        // Generate the remainder of the breadcrumbs
        array_push($breadcrumbs, "Products");
    }
    // If the URL contains unknown information the user will be redirected to this page
    else if(empty($_GET['pc']) && empty($_GET['sc']) && empty($_GET['p']) && !empty($_GET))
    {
        require_once('includes/homeurl.php');
        $homeURL .= 'products.php';
        die(header('Location: ' . $homeURL));
    }
    // Display products by parent category
    if(!empty($_GET['pc']))
    {
        $parentCategoryID = mysqli_real_escape_string($dbc, trim($_GET['pc']));
        // Acquire the parent category name
        $query = "SELECT parentCategory FROM parent_category_tbl WHERE parentCategoryID = '$parentCategoryID'";
        $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with acquiring data on the requested parent category. Please try again or contact support if the problem persists.';
        $row = mysqli_fetch_array($result);
        $parentCategory = $row['parentCategory'];
        // Acquire the sub-category ID to be used to display all the products in the sub-categories in the requested parent category
        $query = "SELECT subCategoryID FROM category_tbl WHERE parentCategoryID = '$parentCategoryID'";
        $results = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with acquiring data for the sub-categories related to the requested parent category. Please try again or contact support if the problem persists.';
        // If no records were retrieved from the database, the id provided was modified by the user, so redirect them
        if(mysqli_num_rows($results) == 0)
        {
            require_once('includes/homeurl.php');
            $homeURL .= 'products.php';
            die(header('Location: ' . $homeURL));
        }
        $pageTitle = $aCName . " - Products - " . $parentCategory;
        // Generate the remainder of the breadcrumbs
        array_push($breadcrumbs, "products.php", "The Products Page", "Products", "$parentCategory");
    }
    // Display products by sub-category
    if(!empty($_GET['sc']))
    {
        $subCategoryID = mysqli_real_escape_string($dbc, trim($_GET['sc']));
        // Acquire the sub-category name
        $query = "SELECT subCategory FROM sub_category_tbl WHERE subCategoryID = '$subCategoryID'";
        $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with acquiring data for the requested sub-category. Please try again or contact support if the problem persists.';
        $row = mysqli_fetch_array($result);
        $subCategory = $row['subCategory'];
        // Acquire the sub-category ID to be used to display all the products in the requested sub-category
        $query = "SELECT * FROM product_tbl WHERE subCategoryID = '$subCategoryID' AND availability = 'T'";
        $results = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with acquiring the information for the products related to the requested sub-category. Please try again or contact support if the problem persists.';
        // If no records were retrieved from the database, the id provided was modified by the user, so redirect them
        if(mysqli_num_rows($results) == 0)
        {
            require_once('includes/homeurl.php');
            $homeURL .= 'products.php';
            die(header('Location: ' . $homeURL));
        }
        $pageTitle = $aCName . " - Products - " . $subCategory;
        // Acquire the remainder of the information needed to generate the complete breadcrumbs
        $pCQuery = "SELECT parentCategoryID, parentCategory FROM parent_category_tbl WHERE parentCategoryID = (SELECT parentCategoryID FROM category_tbl WHERE subCategoryID = '$subCategoryID')";
        $pCResults = mysqli_query($dbc, $pCQuery);
        $pCRow = mysqli_fetch_array($pCResults);
        // Generate the remainder of the breadcrumbs
        array_push($breadcrumbs, "products.php", "The Products Page", "Products", "products.php?pc=" . $pCRow['parentCategoryID'], $pCRow['parentCategory']. " Products", $pCRow['parentCategory'], "$subCategory");
    }
    // Display individual product
    if(!empty($_GET['p']))
    {
        $productID = mysqli_real_escape_string($dbc, trim($_GET['p']));
        // Acquire the product information
        $query = "SELECT * FROM product_tbl WHERE productID = '$productID' AND availability = 'T'";
        $results = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with acquiring data for the requested product. Please try again or contact support if the problem persists.';
        // If no records were retrieved from the database, the id provided was modified by the user, so redirect them
        if(mysqli_num_rows($results) == 0)
        {
            require_once('includes/homeurl.php');
            $homeURL .= 'products.php';
            die(header('Location: ' . $homeURL));
        }
        $row = mysqli_fetch_array($results);
        $product = $row['product'];
        $subCategoryID = $row['subCategoryID'];
        $pageTitle = $aCName . " - Products - " . $product;
        // Acquire the remainder of the information needed to generate the complete breadcrumbs
        $sCQuery = "SELECT subCategory FROM sub_category_tbl WHERE subCategoryID = '$subCategoryID'";
        $sCResults = mysqli_query($dbc, $sCQuery);
        $sCRow = mysqli_fetch_array($sCResults);
        $pCQuery = "SELECT parentCategoryID, parentCategory FROM parent_category_tbl WHERE parentCategoryID = (SELECT parentCategoryID FROM category_tbl WHERE subCategoryID = '$subCategoryID')";
        $pCResults = mysqli_query($dbc, $pCQuery);
        $pCRow = mysqli_fetch_array($pCResults);
        // Generate the remainder of the breadcrumbs
        array_push($breadcrumbs, "products.php", "The Products Page", "Products", "products.php?pc=" . $pCRow['parentCategoryID'], $pCRow['parentCategory']. " Products", $pCRow['parentCategory'], "products.php?sc=" . $subCategoryID, $sCRow['subCategory']. " Products", $sCRow['subCategory'], "$product");
    }
    // Acquire the page number information
    // If no page values was provided, then the page requested would be the first
    if(empty($_GET['page']))
    {
        $pageNumber = 1;
        // The first page displays the first set of products, and therefore there are none to skip
        $skip = 0;
    }
    else
    {
        $pageNumber = $_GET['page'];
        // The number of products to skip is 5 for every page past the first
        $skip = (($pageNumber - 1) * PRODUCTS_PER_PAGE);
    }

    // Add the page header
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
        // If there no information is passed via the URL, then the user has requested the default products page
        if(empty($_GET['pc']) && empty($_GET['sc']) && empty($_GET['p']))
        {
            echo '<h1>Products</h1>' . "\n";
            echo '<p>In this section of the website you can browse products under the following categories:' . "\n";
            echo '<ul>' . "\n";
            echo '  <li><a class="pLink" href="products.php?pc=1" title="Secondary">Secondary</a>' . "\n";
            echo '      <ul>' . "\n";
            echo '          <li><a class="pLink" href="products.php?sc=1" title="Accessories">Accessories</a></li>' . "\n";
            echo '          <li><a class="pLink" href="products.php?sc=2" title="Cases">Cases</a></li>' . "\n";
            echo '      </ul>' . "\n";
            echo '  </li>' . "\n";
            echo '  <li><a class="pLink" href="products.php?pc=2" title="Storage">Storage</a>' . "\n";
            echo '      <ul>' . "\n";
            echo '          <li><a class="pLink" href="products.php?sc=3" title="External Hard Drives">External Hard Drives</a></li>' . "\n";
            echo '          <li><a class="pLink" href="products.php?sc=4" title="Flash Drives">Flash Drives</a></li>' . "\n";
            echo '      </ul>' . "\n";
            echo '  </li>' . "\n";
            echo '  <li><a class="pLink" href="products.php?pc=3" title="Other">Other</a>' . "\n";
            echo '      <ul>' . "\n";
            echo '          <li><a class="pLink" href="products.php?sc=5" title="Music">Music</a></li>' . "\n";
            echo '      </ul>' . "\n";
            echo '  </li>' . "\n";
            $check = 1;
        }
        // The user has requested the products in a specific parent category
        if(!empty($parentCategory))
        {
            echo '<h1>Products - ' . $parentCategory . '</h1>' . "\n";
            $row = mysqli_fetch_array($results);
            $subCategoryID = $row['subCategoryID'];
            // Start building the query to obtain the products from all relevant sub-categories
            $query = "(SELECT * FROM product_tbl WHERE subCategoryID = '$subCategoryID' AND availability = 'T')";
            // While there are still sub-categories in the list, create the query to obtain the next set and combine them with the others
            while($row = mysqli_fetch_array($results))
            {
                $subCategoryID = $row['subCategoryID'];
                $query .= " UNION ";
                $query .= "(SELECT * FROM product_tbl WHERE subCategoryID = '$subCategoryID' AND availability = 'T')";
            }
            $results2 = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with acquiring information for the products under the sub-category related to the requested sub-category. Please try again or contact support if the problem persists.';
            // Copy the results of the query for the combined sets of products to a two-dimensional array
            while($row = mysqli_fetch_array($results2))
            {
                $totalResults[] = $row;
            }
            // Acquire the number of products retrieved from the database
            $total = count($totalResults);
            // If the total number of products doees not exceed the number skipped, then the user tried to access a page which would show nothing, so redirect them to the products page
            if(($total <= ($skip)) && $pageNumber > 1)
            {
                require_once('includes/homeurl.php');
                $homeURL .= 'products.php';
                die(header('Location: ' . $homeURL));
            }
            // Start displaying the results by using a variable to track at which point in the array to start and stop displaying the contents of the two-dimensional array
            for($i = $skip; $i < ($skip + PRODUCTS_PER_PAGE); $i++)
            {
                // As long as there are results to display, use the two-dimensional to display each product one by one
                if(!empty($totalResults[$i]))
                {
                    echo '        <table class="productsTable">' . "\n";
                    echo '            <tr><th class="pr1">Product: </th><td><a href="products.php?p=' . $totalResults[$i]['productID'] . '" title="'. $totalResults[$i]['product'] . '"  class="button">'. $totalResults[$i]['product'] . '</a></td><td></td></tr>' . "\n";
                    echo '            <tr><th>Preview: </th><td class="productImage"><img src="' . $imageURL . $totalResults[$i]['image'] . '" alt="' . $totalResults[$i]['product'] . '" title="' . $totalResults[$i]['product'] . '"  /></td><td class="descr pl2 pr2">' . $totalResults[$i]['details'] . '</td></tr>' . "\n";
                    echo '        </table>' . "\n";
                    echo '        <hr>' . "\n";
                }
            }
            // Display the section of the page which will contain the links to allow the user to navigate the results if more than one page exists
            if($total > PRODUCTS_PER_PAGE)
            {
                echo '        <section id="paginationLinks" class="hList">' . "\n";
                echo '            <ul>' ."\n";
                // If there is more than one page, check whether to display previous or next links
                if($pageNumber > 1)
                {
                    // If the previous page is the first, display the previous link without adding a page value
                    if(($pageNumber - 1) == 1)
                    {
                        echo '            <li><a href="' . $_SERVER['PHP_SELF'] . '?pc=' . $parentCategoryID . '"   class="pLButton">&lt;</a></li>' . "\n";
                    }
                    // Otherwise, include the value for the previous page
                    else
                    {
                        echo '            <li><a href="' . $_SERVER['PHP_SELF'] . '?pc=' . $parentCategoryID . '&amp;page=' . ($pageNumber - 1) . '"   class="pLButton">&lt;</a></li>' . "\n";
                    }
                    // If the total number of products exceeds the maximum number which can be displayed for that page, there will be atleast one more to display on the next page
                    if($total > ($skip + PRODUCTS_PER_PAGE))
                    {
                        echo '            <li><a href="' . $_SERVER['PHP_SELF'] . '?pc=' . $parentCategoryID . '&amp;page=' . ($pageNumber + 1) . '"   class="pLButton">&gt;</a></li>' . "\n";
                    }
                }
                else
                {
                    // If the total number of products exceeds the maximum number which can be displayed for that page, there will be atleast one more to display on the next page
                    if($total > ($skip + PRODUCTS_PER_PAGE))
                    {
                        echo '            <li><a href="' . $_SERVER['PHP_SELF'] . '?pc=' . $parentCategoryID . '&amp;page=' . ($pageNumber + 1) . '"   class="pLButton">&gt;</a></li>' . "\n";
                    }
                }
                echo '            </ul>' ."\n";
                echo '        </section>' . "\n";
            }
            echo '        <a href="#top" title="Back to Top" class="button baseButton">Back to Top</a>' . "\n";
            // Clear the sub-category variable to prevent the script from executing the sub-category display functionality
            $subCategory = "";
        }
        // The user has requested the products in a specific sub-category
        if(!empty($subCategory))
        {
            echo '<h1>Products - ' . $subCategory . '</h1>' . "\n";
            // Start displaying the results
            while($row = mysqli_fetch_assoc($results))
            {
                $totalResults[] = $row;
            }
            // Acquire the number of products retrieved from the database
            $total = count($totalResults);
            // If the total number of products does not exceed one or more than the limit per page, and the user tried to access a page past the first, redirect them to the products page
            if($total < (PRODUCTS_PER_PAGE + 1) && $pageNumber > 1)
            {
                require_once('includes/homeurl.php');
                $homeURL .= 'products.php';
                die(header('Location: ' . $homeURL));
            }
            // Start displaying the results by using a variable to track at which point in the array to start and stop displaying the contents of the two-dimensional array
            for($i = $skip; $i < ($skip + PRODUCTS_PER_PAGE); $i++)
            {
                // As long as there are results to display, use the two-dimensional to display each product one by one
                if(!empty($totalResults[$i]))
                {
                    echo '        <table class="productsTable">' . "\n";
                    echo '            <tr><th>Product: </th><td><a href="products.php?p='. $totalResults[$i]['productID'] . '" title="'. $totalResults[$i]['product'] . '"  class="button">'. $totalResults[$i]['product'] . '</a></td></tr>' . "\n";
                    echo '            <tr><th>Preview: </th><td class="productImage"><img src="' . $imageURL . $totalResults[$i]['image'] . '" alt="' . $totalResults[$i]['product'] . '" title="' . $totalResults[$i]['product'] . '"  /></td></tr>' . "\n";
                    echo '            <tr><th>Description: </th><td>' . $totalResults[$i]['details'] . '</td></tr>' . "\n";
                    echo '        </table>' . "\n";
                    echo '        <hr>' . "\n";
                }
            }
            // Display the section of the page which will contain the links to allow the user to navigate the results if more than one page exists
            if($total > PRODUCTS_PER_PAGE)
            {
                echo '<section id="paginationLinks" class="hList">' . "\n";
                echo '<ul>' ."\n";
                // If there is more than one page, check whether to display previous or next links
                if($pageNumber > 1)
                {
                    // If the previous page is the first, display the previous link without adding a page value
                    if(($pageNumber - 1) == 1)
                    {
                        echo '<li><a href="' . $_SERVER['PHP_SELF'] . '?sc=' . $subCategoryID . '"  class="pLButton">&lt;</a></li>' . "\n";
                    }
                    // Otherwise, include the value for the previous page
                    else
                    {
                        echo '<li><a href="' . $_SERVER['PHP_SELF'] . '?sc=' . $subCategoryID . '&amp;page=' . ($pageNumber - 1) . '"  class="pLButton">&lt;</a></li>' . "\n";
                    }
                    // If the total number of products exceeds the maximum number which can be displayed for that page, there will be atleast one more to display on the next page
                    if($total > ($skip + PRODUCTS_PER_PAGE))
                    {
                        echo '<li><a href="' . $_SERVER['PHP_SELF'] . '?sc=' . $subCategoryID . '&amp;page=' . ($pageNumber + 1) . '"  class="pLButton">&gt;</a></li>' . "\n";
                    }
                }
                else
                {
                    // If the total number of products exceeds the maximum number which can be displayed for that page, there will be atleast one more to display on the next page
                    if($total > ($skip + PRODUCTS_PER_PAGE))
                    {
                        echo '<li><a href="' . $_SERVER['PHP_SELF'] . '?sc=' . $subCategoryID . '&amp;page=' . ($pageNumber + 1) . '"  class="pLButton">&gt;</a></li>' . "\n";
                    }
                }
                echo '</ul>' ."\n";
                echo '</section>' . "\n";
            }
            echo '        <a href="#top" title="Back to Top" class="button baseButton">Back to Top</a>' . "\n";
        }
        // The user has requested a specific product
        if(!empty($row['productID']))
        {
            //Display the product
            echo '<h1>Product - ' . $row['product'] . '</h1>';
            echo '        <table class="productsTable">' . "\n";
            echo '            <tr><th>Product: </th><td><a href="products.php?p='. $row['productID'] . '" title="'. $row['product'] . '"  class="button">'. $row['product'] . '</a></td></tr>' . "\n";
            echo '            <tr><th>Preview: </th><td class="productImage"><img src="' . $imageURL . $row['image'] . '" alt="' . $row['product'] . '" title="' . $row['product'] . '" /></td></tr>' . "\n";
            echo '            <tr><th>Description: </th><td>' . $row['details'] . '</td></tr>' . "\n";
            echo '            <tr><th>Price: </th><td>&#36;' . $row['price'] . '</td></tr>' . "\n";
            echo '            <tr><th>Link: </th><td><a href="' . $row['link'] . '" title="Manufacturer\'s Page"  class="button">Manufacturer\'s Page</a><br></td></tr>' . "\n";
            echo '            <tr><th></th><td><a href="cart.php?p=' . $row['productID'] . '" title="Add to Cart"  class="button">Add to Cart</a></td></tr>' . "\n";
            echo '        </table>' . "\n";
        }
        // "Housekeeping"
        mysqli_close($dbc);
        ?>
        </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>