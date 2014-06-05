<?php   
    // Acquire company name
    require_once('includes/name.php');
    
    // Acquire the connection data for the database
    require_once('includes/common.php');
    
    // Start the session
    session_start();
    
    // If the user is not logged in, redirect them to the homepage (a user who are not logged-in as a manager have no reason to access the management page)
    if(!isset($_SESSION['email']) || !isset($_COOKIE['email']) || $_SESSION['userType'] != 1)
    {
        require_once('includes/homeurl.php');
        $homeURL .= 'index.php';
        die(header('Location: ' . $homeURL));
    }
    
    // Staff page variable used to request search engines to not index the page
    $staff = true;
    
    // Variables to track if an operation was successful
    $pSelection = 0;
    $check1 = 0;
    $check2 = 0;
    $check3 = 0;
    $success = 0;
    
    // A request was made to access a form to alter the state of products in the database
    if(isset($_POST['pSelection']))
    {
        switch($_POST['pSelection'])
        {
            // Manager chose to add a product
            case 'Add Product':
                $pSelection = 1;
                break;
            // Manager chose to edit a product
            case 'Edit Product':
                $pSelection = 2;
                $check2 = 1;
                break;
            // Manager chose to archive a product
            case 'Archive Product':
                $pSelection = 3;
                $check2 = 1;
                break;
            // Manager chose to restore a product
            case 'Restore Product':
                $pSelection = 4;
                $check2 = 1;
                break;
            // Manager chose to add an image
            case 'Upload Image':
                $pSelection = 5;
                $check2 = 1;
                break;
            default:
                // No selection made because the page was accessed before a selection was made, so do nothing
        }
    }
    // A request was made to add a product to the database
    if(isset($_POST['addProduct']))
    {
        $product = mysqli_real_escape_string($dbc, trim($_POST['product']));
        // Check if product is in the database before adding it
        $query = "SELECT product FROM product_tbl WHERE product = '$product'";
        $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with searching the database to add a product. Please try again or contact support if the problem persists.';
        // If no match was found, add the product
        if(mysqli_num_rows($result) == 0)
        {
            $details = mysqli_real_escape_string($dbc, trim($_POST['details']));
            $price = mysqli_real_escape_string($dbc, trim($_POST['price']));
            $link = mysqli_real_escape_string($dbc, trim($_POST['link']));
            require_once('includes/uploadimage.php');
            $image = $_FILES["image"]["name"];
            $subCategoryID = mysqli_real_escape_string($dbc, trim($_POST['subCategoryID']));
            $stock = mysqli_real_escape_string($dbc, trim($_POST['stock']));
            // If the image upload script did not detect an error, proceed with processing the rest of the data
            if(empty($errorMsg))
            {
                if(!(empty($product) || empty($image) || empty($details) || empty($price) || empty($link) || empty($subCategoryID) || empty($stock)))
                {
                    $query = "INSERT INTO product_tbl (product, image, details, price, link, subCategoryID, stockLevel) VALUES ('$product', '$image', '$details', '$price', '$link', '$subCategoryID', '$stock')";
                    $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with adding the product to the database. Please try again or contact support if the problem persists.';
                    $check1 = 2;
                    $success = 1;
                }
                else
                {
                    $errorMsg = 'One or more of the add product form fields were detected as empty. Please try again or contact support if the problem persists.';
                }
            }
        }
        else
        {
                $errorMsg = 'The product you attempted to add already exists within the database. Please try again or contact support if the problem persists.';
        }
        // "Housekeeping"
        mysqli_close($dbc);
    }
    // A request was made to view the products in the database to modify one
    if(isset($_POST['editSelection']))
    {
                $check2 = 1;
                $pSelection = 2;
                // Acquire the list of images in the images directory to display in the product modification form
                $imageList = scandir('images');
    }
    // A request was made to modify a product in the database
    if(isset($_POST['editProduct']))
    {
        $productID = mysqli_real_escape_string($dbc, trim($_POST['productID']));
        $product = mysqli_real_escape_string($dbc, trim($_POST['product']));
        $image = mysqli_real_escape_string($dbc, trim($_POST['image']));
        $details = mysqli_real_escape_string($dbc, trim($_POST['details']));
        $price = mysqli_real_escape_string($dbc, trim($_POST['price']));
        $link = mysqli_real_escape_string($dbc, trim($_POST['link']));
        $subCategoryID = mysqli_real_escape_string($dbc, trim($_POST['subCategoryID']));
        if(!(empty($productID) || empty($product) || empty($image) || empty($details) || empty($price) || empty($link) || empty($subCategoryID)))
        {
            $query = "UPDATE product_tbl SET product = '$product', image = '$image', details = '$details', price = '$price', link = '$link', subCategoryID = '$subCategoryID' WHERE productID = '$productID'";
            $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with modifying the selected product. Please try again or contact support if the problem persists.';
            $check2 = 2;
            $success = 1;
            // "Housekeeping"
            mysqli_close($dbc);
        }
        else
        {
            $errorMsg = 'One or more of the edit product form fields were detected as empty. Please try again or contact support if the problem persists.';
        }
    }
    // A request was made to archive a product in the database
    if(isset($_POST['archiveProduct']))
    {
        $productID = mysqli_real_escape_string($dbc, trim($_POST['productID']));
        if(!(empty($productID)))
        {
            $query = "UPDATE product_tbl SET availability = 'F' WHERE productID = '$productID'";
            $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with modifying the selected product. Please try again or contact support if the problem persists.';
            $check2 = 4;
            $success = 1;
            // "Housekeeping"
            mysqli_close($dbc);
        }
        else
        {
            $errorMsg = 'There was an error with archiving the selected product. Please try again or contact support if the problem persists.';
        }
    }
    // A request was made to restore a product in the database
    if(isset($_POST['restoreProduct']))
    {
        $productID = mysqli_real_escape_string($dbc, trim($_POST['productID']));
        if(!(empty($productID)))
        {
            $query = "UPDATE product_tbl SET availability = 'T' WHERE productID = '$productID'";
            $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with modifying the selected product. Please try again or contact support if the problem persists.';
            $check2 = 5;
            $success = 1;
            // "Housekeeping"
            mysqli_close($dbc);
        }
        else
        {
            $errorMsg = 'There was an error with restoring the selected product. Please try again or contact support if the problem persists.';
        }
    }
    // A request was made to add an image in the database
    if(isset($_POST['uploadImage']))
    {
        require_once('includes/uploadimage.php');
        $check3 = 1;
        $success = 1;
    }
    
    // Parent category tracking variables
    $pCSelection = 0;
    $check6 = 0;
    
    // A request was made to access a form to alter the state of parent categories in the database
    if(isset($_POST['pCSelection']))
    {
        switch($_POST['pCSelection'])
        {
            // Manager chose to add a parent category
            case 'Add Parent Category':
                $pCSelection = 1;
                break;
            // Manager chose to edit a parent category
            case 'Edit Parent Category':
                $pCSelection = 2;
                $check6 = 1;
                break;
            default:
                // No selection made because the page was accessed before a selection was made, so do nothing
        }
    }
    // A request was made to add a parent category to the database
    if(isset($_POST['addParentCategory']))
    {
        $parentCategory = mysqli_real_escape_string($dbc, trim($_POST['parentCategory']));
        $description = mysqli_real_escape_string($dbc, trim($_POST['description']));
        // Start checking to see if the parent category already exists within the database
        $query = "SELECT * FROM parent_category_tbl WHERE parentCategory = '$parentCategory'";
        $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with processing the parent category. Please try again or contact support if the problem persists.';
        $checkResult = mysqli_fetch_array($result);
        // If it doesn't, attempt to add it
        if(!$checkResult)
        {
            if(!(empty($parentCategory) || empty($description)))
            {
                $query = "INSERT INTO parent_category_tbl (parentCategory, description) VALUES ('$parentCategory', '$description')";
                  
                // Issue query and check for database errors. (Error querying database)
                $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with adding the parent category to the database. Please try again or contact support if the problem persists.';
                $check6 = 2;
                $success = 1;
                // "Housekeeping"
                mysqli_close($dbc);
            }
            else
            {
                $errorMsg = 'One or more of the add parent category form fields were detected as empty. Please try again or contact support if the problem persists.';
            }
        }
        else
        {
            $errorMsg = 'The parent category you attempted to add to the database already exists. Please try again or contact support if the problem persists.';
        }
    }
    // A request was made to view the parent categories in the database to modify one
    if(isset($_POST['editParentCategorySelection']))
    {
                $check6 = 1;
                $pCSelection = 2;
    }
    // A request was made to modify a parent category in the database
    if(isset($_POST['editParentCategory']))
    {
        $parentCategoryID = mysqli_real_escape_string($dbc, trim($_POST['parentCategoryID']));
        $parentCategory = mysqli_real_escape_string($dbc, trim($_POST['parentCategory']));
        $description = mysqli_real_escape_string($dbc, trim($_POST['description']));
        // Start checking to see if the parent category already exists within the database
        $query = "SELECT * FROM parent_category_tbl WHERE parentCategoryID = '$parentCategoryID'";
        $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with processing the parent category. Please try again or contact support if the problem persists.';
        $checkID = mysqli_fetch_array($result);
        $query = "SELECT * FROM parent_category_tbl WHERE parentCategory = '$parentCategory'";
        $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with processing the parent category. Please try again or contact support if the problem persists.';
        $checkResult = mysqli_fetch_array($result);
        $nameCheck = false;
        if($checkID)
        {
            // Check to see if the parent category provided matches the current name of the parent category chosen
            // If it does not, then the name provided matches another parent category in the database, so flag the error
            if(strcmp($checkID['parentCategory'],$parentCategory)==0)
            {
                $nameCheck = true;
            }
        }
        // If it doesn't, attempt to add it as long as the name matches the original one or the name does not exist within the database
        if($nameCheck || !$checkResult)
        {
            if(!(empty($parentCategory) || empty($description)))
            {
                $query = "UPDATE parent_category_tbl SET parentCategory = '$parentCategory', description = '$description' WHERE parentCategoryID = '$parentCategoryID'";
                  
                // Issue query and check for database errors. (Error querying database)
                $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with modifying the parent category. Please try again or contact support if the problem persists.';
                $check6 = 3;
                $success = 1;
                // "Housekeeping"
                mysqli_close($dbc);
            }
            else
            {
                $errorMsg = 'One or more of the edit parent category form fields were detected as empty. Please try again or contact support if the problem persists.';
            }
        }
        else
        {
            $errorMsg = 'The parent category you attempted to use already exists within the database. Please try again or contact support if the problem persists.';
        }
    }
    
    // Sub-category tracking variables
    $sCSelection = 0;
    $check7 = 0;
    
    // A request was made to access a form to alter the state of sub-categories in the database
    if(isset($_POST['sCSelection']))
    {
        switch($_POST['sCSelection'])
        {
            // Manager chose to add a sub-category
            case 'Add Sub-category':
                $sCSelection = 1;
                break;
            // Manager chose to edit a sub-category
            case 'Edit Sub-category':
                $sCSelection = 2;
                $check7 = 1;
                break;
            default:
                // No selection made because the page was accessed before a selection was made, so do nothing
        }
    }
    // A request was made to add a sub-category to the database
    if(isset($_POST['addSubCategory']))
    {
        $parentCategoryID = mysqli_real_escape_string($dbc, trim($_POST['parentCategoryID']));  
        $subCategory = mysqli_real_escape_string($dbc, trim($_POST['subCategory']));
        $description = mysqli_real_escape_string($dbc, trim($_POST['description']));
        // Start checking to see if the sub-category already exists within the database
        $query = "SELECT * FROM sub_category_tbl WHERE subCategory = '$subCategory'";
        $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with processing the sub-category. Please try again or contact support if the problem persists.';
        $checkResult = mysqli_fetch_array($result);
        // If it doesn't, attempt to add it
        if(!$checkResult)
        {
            if(!(empty($parentCategoryID) || empty($subCategory) || empty($description)))
            {
                $query = "INSERT INTO sub_category_tbl (subCategory, description) VALUES ('$subCategory', '$description')";
                $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with adding the sub-category to the database. Please try again or contact support if the problem persists.';
                // Query for newly created sub-cateogry ID
                $query = "SELECT subCategoryID FROM sub_category_tbl WHERE subCategory = '$subCategory'";
                $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with acquiring the newly added sub-category from the database. Please try again or contact support if the problem persists.';
                $row = mysqli_fetch_array($result);
                $subCategoryID = $row['subCategoryID'];
                // Insert the category association data into the database
                $query = "INSERT INTO category_tbl (parentCategoryID, subCategoryID) VALUES ('$parentCategoryID', '$subCategoryID')";
                $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with adding the category association data to the database. Please try again or contact support if the problem persists.';
                $check7 = 2;
                $success = 1;
                // "Housekeeping"
                mysqli_close($dbc);
            }
            else
            {
                $errorMsg = 'One or more of the add sub-category form fields were detected as empty. Please try again or contact support if the problem persists.';
            }
        }
        else
        {
            $errorMsg = 'The sub-category you attempted to add to the database already exists. Please try again or contact support if the problem persists.';
        }
    }
    // A request was made to view the sub-categories in the database to modify one
    if(isset($_POST['editSubCategorySelection']))
    {
                $check7 = 1;
                $sCSelection = 2;
    }
    // A request was made to modify a sub-category in the database
    if(isset($_POST['editSubCategory']))
    {
        $parentCategoryID = mysqli_real_escape_string($dbc, trim($_POST['parentCategoryID']));
        $subCategoryID = mysqli_real_escape_string($dbc, trim($_POST['subCategoryID']));
        $categoriesID = mysqli_real_escape_string($dbc, trim($_POST['categoriesID']));
        $subCategory = mysqli_real_escape_string($dbc, trim($_POST['subCategory']));
        $description = mysqli_real_escape_string($dbc, trim($_POST['description']));
        // Start checking to see if the sub-category already exists within the database
        $query = "SELECT * FROM sub_category_tbl WHERE subCategoryID = '$subCategoryID'";
        $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with processing the sub-category. Please try again or contact support if the problem persists.';
        $checkID = mysqli_fetch_array($result);
        $query = "SELECT * FROM sub_category_tbl WHERE subCategory = '$subCategory'";
        $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with processing the sub-category. Please try again or contact support if the problem persists.';
        $checkResult = mysqli_fetch_array($result);
        $nameCheck = false;
        if($checkID)
        {
            // Check to see if the sub-category provided matches the current name of the sub-category chosen
            // If it does not, then the name provided matches another sub-category in the database, so flag the error
            if(strcmp($checkID['subCategory'],$subCategory)==0)
            {
                $nameCheck = true;
            }
        }
        // If it doesn't, attempt to add it as long as the name matches the original one or the name does not exist within the database
        if($nameCheck || !$checkResult)
        {
            if(!(empty($parentCategoryID) || empty($subCategoryID) || empty($categoriesID) || empty($subCategory) || empty($description)))
            {
                $query = "UPDATE sub_category_tbl SET subCategory = '$subCategory', description = '$description' WHERE subCategoryID = '$subCategoryID'";
                $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with modifying the sub-category. Please try again or contact support if the problem persists.';
                // Update the category association data in category_tbl in the event relevant data was modified
                $query = "UPDATE category_tbl SET parentCategoryID = '$parentCategoryID', subCategoryID = '$subCategoryID' WHERE categoriesID = '$categoriesID'";
                $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with modifying the category association data. Please try again or contact support if the problem persists.';
                $check7 = 3;
                $success = 1;
                // "Housekeeping"
                mysqli_close($dbc);
            }
            else
            {
                $errorMsg = 'One or more of the edit sub-category form fields were detected as empty. Please try again or contact support if the problem persists.';
            }
        }
        else
        {
            $errorMsg = 'The sub-category you attempted to use already exists within the database. Please try again or contact support if the problem persists.';
        }
    }
    
    // Add the page header
    $pageTitle = $aCName . " Staff - Management Centre";
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "Management Centre");
    
    // Add the JavaScript validation script
    $staffvalidation = true;
    
    require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
        <h1>Management Centre</h1>
        <?php
        // If no operation had just been successfully executed or if an error was encountered, display the initial form
        if($success == 0 || isset($errorMsg))
        {
            // If an error was encountered, echo the error markup, otherwise there was no error and no operation had just been successfully executed, so skip echoing the markup
            if(isset($errorMsg))
            {
                echo '<p class="errorMsg">' . $errorMsg . '</p>' . "\n";
            }
            // Display the main menu
        ?>
            <div id="selectionFormFrame">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <fieldset><legend>Select Operation</legend>
                <table id="selectionTable">
                    <tr>
                        <th>Product Management:</th>
                        <td><input type="submit" value="Add Product" name="pSelection" class="button mButton" /></td>
                        <td><input type="submit" value="Edit Product" name="pSelection" class="button mButton" /></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><input type="submit" value="Archive Product" name="pSelection" class="button mButton" /></td>
                        <td><input type="submit" value="Restore Product" name="pSelection" class="button mButton" /></td>
                    </tr>
                    <tr>
                        <th>Image Management:</th>
                        <td><input type="submit" value="Upload Image" name="pSelection" class="button mButton" /></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Parent Categories Management:</th>
                        <td><input type="submit" value="Add Parent Category" name="pCSelection" class="button mButton" /></td>
                        <td><input type="submit" value="Edit Parent Category" name="pCSelection" class="button mButton" /></td>
                    </tr>
                    <tr>
                        <th>Sub-categories Management:</th>
                        <td><input type="submit" value="Add Sub-category" name="sCSelection" class="button mButton" /></td>
                        <td><input type="submit" value="Edit Sub-category" name="sCSelection" class="button mButton" /></td>
                    </tr>
                </table>
                </fieldset>
                </form>
            </div>
        <?php
        }     
        if(!isset($errorMsg))
        {
            // If the manager wants to add a product, then display this form
            if(($pSelection == 1) && !isset($_POST['submit']))
            {
             ?>
            <div id="addProductFormFrame">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return productForm(this);" enctype="multipart/form-data">
                <fieldset><legend>Add Product</legend>
                <table id="addProductTable">
                    <tr>
                        <th><label>Name:</label></th>
                        <td><input type="text" id="name" name="product" value="<?php if(!empty($product)) echo $product; // If the user typed in a bad name the name field will retain the name they entered. ?>" /></td>
                    </tr>
                    <tr>
                        <th><label>Image:</label></th>
                        <td><input type="file" id="image" name="image" /></td>
                    </tr>
                    <tr>
                        <th><label>Details:</label></th>
                        <td><textarea id="details" name="details" rows="5" cols="40"><?php if(!empty($details)) echo $details; // If the user typed in a bad details the details field will retain the information they entered. ?></textarea></td>
                    </tr>
                    <tr>
                        <th><label>Price:</label></th>
                        <td><input type="text" id="price" name="price" value="<?php if(!empty($price)) echo $price; // If the user typed in a bad price the price field will retain the number they entered. ?>" /></td>
                    </tr>
                    <tr>
                        <th><label>Link:</label></th>
                        <td><input type="text" id="link" name="link" value="<?php if(!empty($link)) echo $link; // If the user typed in a bad link the link field will retain the URL they entered. ?>" /></td>
                    </tr>
                    <tr>
                        <th><label>Category:</label></th>
                        <td>
                        <!-- Select drop down with default option -->
                            <select name="subCategoryID">
                              <?php
                                $query = "SELECT subCategoryID, subCategory FROM sub_category_tbl";
                                $results = mysqli_query($dbc, $query);
                                while($row = mysqli_fetch_array($results))
                                {
                                    // Categories were found, so list them in the drop down menu
                                    echo '<option value="' . $row['subCategoryID'] . '">' . $row['subCategory'] . '</option>';
                                }
                              ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label>Stock Level:</label></th>
                        <td><input type="text" id="stock" name="stock" value="<?php if(!empty($stock)) echo $stock; // If the user typed in a bad stock number the stock field will retain the number they entered. ?>" /></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Add Product" name="addProduct" class="button mButton" /></td>
                        <td><input type="reset" value="Reset" class="button mButton" /></td>
                    </tr>
                </table>
                </fieldset>
                </form>
            </div>
            <?php
            }
            if($check1 == 2)
            {
                // Confirm the successful addition
                echo '<h2>Product Added Successfully</h2>';
                require_once('includes/homeurl.php');
                $homeURL .= 'management.php';
                echo '<a href="'. $homeURL . '" title="Return to Management Centre" class="button return">Return to Management Centre</a>' . "\n";
            }
            
            // If the manager wants to edit a product, then display this form to allow them to select one
            if(($check2 == 1) && ($pSelection == 2) && !isset($_POST['submit']))
            {
                ?>
                <div id="editProductSelectionFormFrame">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <fieldset><legend>Select Product to Edit</legend>
                    <table id="editProductSelectionTable">
                        <tr>
                            <th><label>Product:</label></th>
                            <td id="productSelection">
                            <!-- Select drop down with default option -->
                                <select name="productSelection">
                                  <?php
                                    $query = "SELECT product FROM product_tbl";
                                    $results = mysqli_query($dbc, $query);
                                    // Use counter to echo HTML with correct spacing to retain indentation structure
                                    $i = 0;
                                    while($row = mysqli_fetch_array($results))
                                    {
                                        // Products were found, so list them in the drop down menu
                                        if($i == 0)
                                        {
                                            echo '<option value="' . $row['product'] . '">' . $row['product'] . '</option>' . "\n";
                                        }
                                        else
                                        {
                                            echo '                                <option value="' . $row['product'] . '">' . $row['product'] . '</option>' . "\n";
                                        }
                                        $i++;
                                    }
                                  ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="Select Product" name="editSelection" class="button mButton" /></td>
                            <td><input type="reset" value="Reset" class="button mButton" /></td>
                        </tr>
                    </table>
                    </fieldset>
                    </form>
                </div>
                <?php
            }
            // If the manager has selected to edit a product, then display this form to allow them to modify it
            if(isset($_POST['editSelection']))
            {
                $product = $_POST['productSelection'];
                $query = "SELECT * FROM product_tbl WHERE product = '$product'";
                $result = mysqli_query($dbc, $query);
                $row = mysqli_fetch_array($result);
                $productID = $row['productID'];
                $product = $row['product'];
                $image = $row['image'];
                $details = $row['details'];
                $price = $row['price'];
                $link = $row['link'];
                $subCategoryID = $row['subCategoryID'];
              ?>
            <div id="editProductFormFrame">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return productForm(this);">
                <fieldset><legend>Edit Product</legend> 
                <table id="editProductTable">
                    <tr>
                        <th><label>Name:</label>
                            <input type="hidden" name="productID" value="<?php echo $productID; // Carry the ID to be used in the UPDATE query ?>">
                        </th>
                        <td><input type="text" id="name" name="product" value="<?php if(!empty($product)) echo $product; // If the user typed in a bad name the name field will retain the name they entered. ?>" /></td>
                    </tr>
                    <tr>
                            <th><label>Image:</label></th>
                            <td>
                            <!-- Select drop down with default option -->
                                <select name="image">
                                  <?php
                                    // Use counter to echo HTML with correct spacing to retain indentation structure
                                    $i = 0;
                                    $x = 0;
                                    while(next($imageList))
                                    {
                                        // Eliminate the current (.) and parent (..) directory references
                                        if((strcmp($imageList[$x], '..') != 0) && (strcmp($imageList[$x], '.') != 0))
                                        {
                                            // Images were found, so list them in the drop down menu
                                            if($i == 0)
                                            {
                                                echo '<option value="' . $imageList[$x] . '"'; if(strcmp($imageList[$x], $image) == 0) echo ' selected'; echo '>' . $imageList[$x] . '</option>' . "\n";
                                            }
                                            else
                                            {
                                                echo '                                  <option value="' . $imageList[$x] . '"'; if(strcmp($imageList[$x], $image) == 0) echo ' selected'; echo '>' . $imageList[$x] . '</option>' . "\n";
                                            }
                                            $i++;
                                        }
                                        $x++;
                                    }
                                  ?>
                                </select>
                            </td>
                        </tr>
                    <tr>
                        <th><label>Details:</label></th>
                        <td><textarea id="details" name="details" rows="5" cols="40"><?php if(!empty($details)) echo $details; // If the user typed in a bad details the details field will retain the information they entered. ?></textarea></td>
                    </tr>
                    <tr>
                        <th><label>Price:</label></th>
                        <td><input type="text" id="price" name="price" value="<?php if(!empty($price)) echo $price; // If the user typed in a bad price the price field will retain the number they entered. ?>" /></td>
                    </tr>
                    <tr>
                        <th><label>Link:</label></th>
                        <td><input type="text" id="link" name="link" value="<?php if(!empty($link)) echo $link; // If the user typed in a bad link the link field will retain the URL they entered. ?>" /></td>
                    </tr>
                    <tr>
                        <th><label>Sub-category:</label></th>
                        <td>
                        <!-- Select drop down with default option -->
                            <select name="subCategoryID">
                              <?php
                                // Acquire the sub-category data to display the list and pass the ID to the update query
                                $query = "SELECT subCategoryID, subCategory FROM sub_category_tbl";
                                $results = mysqli_query($dbc, $query);
                                // Use counter to echo HTML with correct spacing to retain indentation structure
                                $i = 0;
                                while($row2 = mysqli_fetch_array($results))
                                {
                                    if($i == 0)
                                    {
                                        // Categories were found, so list them in the drop down menu
                                        echo '<option value="' . $row2['subCategoryID'] . '"'; if(strcmp($row2['subCategoryID'], $subCategoryID) == 0) echo ' selected'; echo '>' . $row2['subCategory'] . '</option>' . "\n";
                                    }
                                    else
                                    {
                                        // Categories were found, so list them in the drop down menu
                                        echo '                            <option value="' . $row2['subCategoryID'] . '"'; if(strcmp($row2['subCategoryID'], $subCategoryID) == 0) echo ' selected'; echo '>' . $row2['subCategory'] . '</option>' . "\n";
                                    }
                                    $i++;
                                }
                              ?>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Edit Product" name="editProduct" class="button mButton" /></td>
                        <td><input type="reset" value="Reset" class="button mButton" /></td>
                    </tr>
                </table>
                </fieldset>
                </form>
            </div>
            <?php
            }
            if($check2 == 2)
            {
              // Confirm the successful edit
              echo '<h2>Product Modified Successfully</h2>';
              require_once('includes/homeurl.php');
              $homeURL .= 'management.php';
              echo '<a href="'. $homeURL . '" title="Return to Management Centre" class="button return">Return to Management Centre</a>' . "\n";
            }  
            // If the manager has selected to archive a product, then display this form to allow them to archive it
            if(($check2 == 1) && ($pSelection == 3) && !isset($_POST['submit']))
            {
                ?>
                <div id="archiveProductSelectionFormFrame">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <fieldset><legend>Select Product to Archive</legend>
                    <table id="archiveProductSelectionTable">
                        <tr>
                            <th><label>Product:</label></th>
                            <td id="productSelection">
                            <!-- Select drop down with default option -->
                                <select name="productID">
                                  <?php
                                    $query = "SELECT productID, product FROM product_tbl";
                                    $results = mysqli_query($dbc, $query);
                                    // Use counter to echo HTML with correct spacing to retain indentation structure
                                    $i = 0;
                                    while($row = mysqli_fetch_array($results))
                                    {
                                        // Products were found, so list them in the drop down menu
                                        if($i == 0)
                                        {
                                            echo '<option value="' . $row['productID'] . '">' . $row['product'] . '</option>' . "\n";
                                        }
                                        else
                                        {
                                            echo '                                <option value="' . $row['productID'] . '">' . $row['product'] . '</option>' . "\n";
                                        }
                                        $i++;
                                    }
                                  ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="Archive Product" name="archiveProduct" class="button mButton" /></td>
                            <td><input type="reset" value="Reset" class="button mButton" /></td>
                        </tr>
                    </table>
                    </fieldset>
                    </form>
                </div>
                <?php
            }
            if($check2 == 4)
            {
                // Confirm the successful archive
                echo '<h2>Product Archived Successfully</h2>';
                require_once('includes/homeurl.php');
                $homeURL .= 'management.php';
                echo '<a href="'. $homeURL . '" title="Return to Management Centre" class="button return">Return to Management Centre</a>' . "\n";
            }
            // If the manager has selected to restore a product, then display this form to allow them to restore it
            if(($check2 == 1) && ($pSelection == 4) && !isset($_POST['submit']))
            {
                ?>
                <div id="restoreProductSelectionFormFrame">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <fieldset><legend>Select Product to Restore</legend>
                    <table id="restoreProductSelectionTable">
                        <tr>
                            <th><label>Product:</label></th>
                            <td id="productSelection">
                            <!-- Select drop down with default option -->
                                <select name="productID">
                                  <?php
                                    $query = "SELECT productID, product FROM product_tbl";
                                    $results = mysqli_query($dbc, $query);
                                    // Use counter to echo HTML with correct spacing to retain indentation structure
                                    $i = 0;
                                    while($row = mysqli_fetch_array($results))
                                    {
                                        // Products were found, so list them in the drop down menu
                                        if($i == 0)
                                        {
                                            echo '<option value="' . $row['productID'] . '">' . $row['product'] . '</option>' . "\n";
                                        }
                                        else
                                        {
                                            echo '                                <option value="' . $row['productID'] . '">' . $row['product'] . '</option>' . "\n";
                                        }
                                        $i++;
                                    }
                                  ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="Restore Product" name="restoreProduct" class="button mButton" /></td>
                            <td><input type="reset" value="Reset" class="button mButton" /></td>
                        </tr>
                    </table>
                    </fieldset>
                    </form>
                </div>
                <?php
            }
            if($check2 == 5)
            {
                // Confirm the successful restoration
                echo '<h2>Product Restored Successfully</h2>';
                require_once('includes/homeurl.php');
                $homeURL .= 'management.php';
                echo '<a href="'. $homeURL . '" title="Return to Management Centre" class="button return">Return to Management Centre</a>' . "\n";
            }
            // If the manager wants to add an image, then display this form
            if(($pSelection == 5) && !isset($_POST['submit']))
            {
            ?>
                <div id="uploadImageFormFrame">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                <fieldset><legend>Upload Image</legend>
                <table id="uploadImageTable">
                    <tr>
                        <th><label>Image:</label></th>
                        <td><input type="file" id="image" name="image" /></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Upload Image" name="uploadImage" class="button mButton" /></td>
                        <td><input type="reset" value="Reset" class="button mButton" /></td>
                    </tr>
                </table>
                </fieldset>
                </form>
            </div>
            <?php
            }
            if($check3 == 1)
            {
                // Confirm the successful addition
                echo '<h2>Image Added Successfully</h2>';
                require_once('includes/homeurl.php');
                $homeURL .= 'management.php';
                echo '<a href="'. $homeURL . '" title="Return to Management Centre" class="button return">Return to Management Centre</a>' . "\n";
            }
            
            // Categories
            // Parent Categories
            // If the manager wants to add a parent category, then display this form
            if(($pCSelection == 1) && !isset($_POST['submit']))
            {
             ?>
            <div id="addParentCategoryFormFrame">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return parentCategoryForm(this);">
                <fieldset><legend>Add Parent Category</legend>
                <table id="addParentCategoryTable">
                    <tr>
                        <th><label>Parent Category:</label></th>
                        <td><input type="text" id="name" name="parentCategory" value="<?php if(!empty($parentCategory)) echo $parentCategory; // If the user typed in a bad name the name field will retain the name they entered. ?>" /></td>
                    </tr>
                    <tr>
                        <th><label>Description:</label></th>
                        <td><textarea id="description" name="description" rows="5" cols="40"><?php if(!empty($description)) echo $description; // If the user typed in a bad description the details field will retain the text they entered. ?></textarea></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Add Parent Category" name="addParentCategory" class="button mButton" /></td>
                        <td><input type="reset" value="Reset" class="button mButton" /></td>
                    </tr>
                </table>
                </fieldset>
                </form>
            </div>
            <?php
            }
            if($check6 == 2)
            {
                // Confirm the successful addition
                echo '<h2>Parent Category Added Successfully</h2>';
                require_once('includes/homeurl.php');
                $homeURL .= 'management.php';
                echo '<a href="'. $homeURL . '" title="Return to Management Centre" class="button return">Return to Management Centre</a>' . "\n";
            }
            
            // If the manager wants to edit a parent category, then display this form to allow them to select one
            if(($check6 == 1) && ($pCSelection == 2) && !isset($_POST['submit']))
            {
                ?>
                <div id="editParentCategorySelectionFormFrame">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <fieldset><legend>Select Parent Category to Edit</legend>
                    <table id="editParentCategorySelectionTable">
                        <tr>
                            <th><label>Parent Categories:</label></th>
                            <td id="parentCategorySelection">
                            <!-- Select drop down with default option -->
                                <select name="parentCategorySelection">
                              <?php
                                $query = "SELECT parentCategory FROM parent_category_tbl";
                                $results = mysqli_query($dbc, $query);
                                // Use counter to echo HTML with correct spacing to retain indentation structure
                                $i = 0;
                                while($row = mysqli_fetch_array($results))
                                {
                                    if($i == 0)
                                    {
                                        // Categories were found, so list them in the drop down menu
                                        echo '<option value="' . $row['parentCategory'] . '">' . $row['parentCategory'] . '</option>' . "\n";
                                    }
                                    else
                                    {
                                        // Categories were found, so list them in the drop down menu
                                        echo '                            <option value="' . $row['parentCategory'] . '">' . $row['parentCategory'] . '</option>' . "\n";
                                    }
                                    $i++;
                                }
                              ?>
                            </select>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="Select Parent Category" name="editParentCategorySelection" class="button mButton" /></td>
                            <td><input type="reset" value="Reset" class="button mButton" /></td>
                        </tr>
                    </table>
                    </fieldset>
                    </form>
                </div>
                <?php
            }
            // If the manager has selected to edit a parent category, then display this form to allow them to modify it
            if(isset($_POST['editParentCategorySelection']))
            {
                $parentCategory = $_POST['parentCategorySelection'];
                $parentCategoryID = 0;
                $description = "";
                $query = "SELECT * FROM parent_category_tbl WHERE parentCategory = '$parentCategory'";
                $result = mysqli_query($dbc, $query);
                if(mysqli_num_rows($result) == 1)
                {
                    $row = mysqli_fetch_array($result);
                    $parentCategoryID = $row['parentCategoryID'];
                    $description = $row['description'];
                } 
              ?>
            <div id="editParentCategoryFormFrame">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return parentCategoryForm(this);">
                <fieldset><legend>Edit Parent Category</legend>
                <table id="editParentCategoryTable">
                    <tr>
                        <th><label>Parent Category:</label><input type="hidden" name="parentCategoryID" value="<?php echo $parentCategoryID; // Carry the ID to be used in the UPDATE query ?>"></th>
                        <td><input type="text" id="name" name="parentCategory" value="<?php if(!empty($parentCategory)) echo $parentCategory; // If the user typed in a bad name the name field will retain the name they entered. ?>" /></td>
                    </tr>
                    <tr>
                        <th><label>Description:</label></th>
                        <td><textarea id="description" name="description" rows="5" cols="40"><?php if(!empty($description)) echo $description; // If the user typed in a bad description the details field will retain the text they entered. ?></textarea></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Edit Parent Category" name="editParentCategory" class="button mButton" /></td>
                        <td><input type="reset" value="Reset" class="button mButton" /></td>
                    </tr>
                </table>
                </fieldset>
                </form>
            </div>
            <?php
            }
            if($check6 == 3)
            {
              // Confirm the successful edit
              echo '<h2>Parent Category Modified Successfully</h2>';
              require_once('includes/homeurl.php');
              $homeURL .= 'management.php';
              echo '<a href="'. $homeURL . '" title="Return to Management Centre" class="button return">Return to Management Centre</a>' . "\n";
            }
            
            // Sub-categories
            // If the manager wants to add a sub-category, then display this form
            if(($sCSelection == 1) && !isset($_POST['submit']))
            {
             ?>
            <div id="addSubCategoryFormFrame">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return subCategoryForm(this);">
                <fieldset><legend>Add Sub-category</legend>
                <table id="addSubCategoryTable">
                    <tr>
                        <th><label>Parent Category:</label></th>
                        <td id="parentCategorySelection">
                        <!-- Select drop down with default option -->
                            <select name="parentCategoryID">
                                <?php
                                    $query = "SELECT parentCategoryID, parentCategory FROM parent_category_tbl";
                                    $results = mysqli_query($dbc, $query);
                                    // Use counter to echo HTML with correct spacing to retain indentation structure
                                    $i = 0;
                                    while($row = mysqli_fetch_array($results))
                                    {
                                        if($i == 0)
                                        {
                                            // Categories were found, so list them in the drop down menu
                                            echo '<option value="' . $row['parentCategoryID'] . '">' . $row['parentCategory'] . '</option>' . "\n";
                                        }
                                        else
                                        {
                                            // Categories were found, so list them in the drop down menu
                                            echo '                              <option value="' . $row['parentCategoryID'] . '">' . $row['parentCategory'] . '</option>' . "\n";
                                        }
                                        $i++;
                                    }
                                  ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label>Sub-category:</label></th>
                        <td><input type="text" id="name" name="subCategory" value="<?php if(!empty($subCategory)) echo $subCategory; // If the user typed in a bad name the name field will retain the name they entered. ?>" /></td>
                    </tr>
                    <tr>
                        <th><label>Description:</label></th>
                        <td><textarea id="description" name="description" rows="5" cols="40"><?php if(!empty($description)) echo $description; // If the user typed in a bad description the details field will retain the text they entered. ?></textarea></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Add Sub-category" name="addSubCategory" class="button mButton" /></td>
                        <td><input type="reset" value="Reset" class="button mButton" /></td>
                    </tr>
                </table>
                </fieldset>
                </form>
            </div>
            <?php
            }
            if($check7 == 2)
            {
                // Confirm the successful addition
                echo '<h2>Sub-category Added Successfully</h2>';
                require_once('includes/homeurl.php');
                $homeURL .= 'management.php';
                echo '<a href="'. $homeURL . '" title="Return to Management Centre" class="button return">Return to Management Centre</a>' . "\n";
            }
            
            // If the manager wants to edit a sub-category, then display this form to allow them to select one
            if(($check7 == 1) && ($sCSelection == 2) && !isset($_POST['submit']))
            {
                ?>
                <div id="editSubCategorySelectionFormFrame">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <fieldset><legend>Select Sub-category to Edit</legend>
                    <table id="editSubCategorySelectionTable">
                        <tr>
                            <th><label>Sub-categories:</label></th>
                            <td id="subCategorySelection">
                            <!-- Select drop down with default option -->
                                <select name="subCategorySelection">
                                    <?php
                                    $query = "SELECT subCategory FROM sub_category_tbl";
                                    $results = mysqli_query($dbc, $query);
                                    // Use counter to echo HTML with correct spacing to retain indentation structure
                                    $i = 0;
                                    while($row = mysqli_fetch_array($results))
                                    {
                                        if($i == 0)
                                        {
                                            // Categories were found, so list them in the drop down menu
                                            echo '<option value="' . $row['subCategory'] . '">' . $row['subCategory'] . '</option>' . "\n";
                                        }
                                        else
                                        {
                                            // Categories were found, so list them in the drop down menu
                                            echo '                                <option value="' . $row['subCategory'] . '">' . $row['subCategory'] . '</option>' . "\n";
                                        }
                                        $i++;
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="Select Sub-category" name="editSubCategorySelection" class="button mButton" /></td>
                            <td><input type="reset" value="Reset" class="button mButton" /></td>
                        </tr>
                    </table>
                    </fieldset>
                    </form>
                </div>
                <?php
            }
            // If the manager has selected to edit a sub-category, then display this form to allow them to modify it
            if(isset($_POST['editSubCategorySelection']))
            {
                $subCategory = $_POST['subCategorySelection'];
                $query = "SELECT * FROM sub_category_tbl WHERE subCategory = '$subCategory'";
                $result = mysqli_query($dbc, $query);
                $row = mysqli_fetch_array($result);
                $subCategoryID = $row['subCategoryID'];
                $description = $row['description'];
                // Query for the sub-category ID's matching parent category ID
                $query = "SELECT parentCategoryID FROM category_tbl WHERE subCategoryID = '$subCategoryID'";
                $result = mysqli_query($dbc, $query);
                $row = mysqli_fetch_array($result);
                $parentCategoryID = $row['parentCategoryID'];
                // Query for the parent category matching the parent category ID
                $query = "SELECT parentCategory FROM parent_category_tbl WHERE parentCategoryID = '$parentCategoryID'";
                $result = mysqli_query($dbc, $query);
                $row = mysqli_fetch_array($result);
                $parentCategory = $row['parentCategory'];
                // Query for the category association data in category_tbl
                $query = "SELECT categoriesID FROM category_tbl WHERE parentCategoryID = '$parentCategoryID' AND subCategoryID = '$subCategoryID'";
                $result = mysqli_query($dbc, $query);
                $row = mysqli_fetch_array($result);
                $categoriesID = $row['categoriesID'];
            
              ?>
            <div id="editSubCategoryFormFrame">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return subCategoryForm(this);">
                <fieldset><legend>Edit Sub-category Category</legend>
                <table id="editSubCategoryTable">
                    <tr>
                        <th><label>Parent Category:</label></th>
                        <td id="parentCategoryID">
                        <input type="hidden" name="subCategoryID" value="<?php echo $subCategoryID; // Carry the ID to be used in the UPDATE query ?>">
                        <input type="hidden" name="parentCategoryID" value="<?php echo $parentCategoryID; // Carry the ID to be used in the UPDATE query ?>">
                        <input type="hidden" name="categoriesID" value="<?php echo $categoriesID; // Carry the ID to be used in the UPDATE query ?>">
                        <!-- Select drop down with default option -->
                            <select name="parentCategoryID">
                                <?php
                                    $query = "SELECT parentCategoryID, parentCategory FROM parent_category_tbl";
                                    $results = mysqli_query($dbc, $query);
                                    // Use counter to echo HTML with correct spacing to retain indentation structure
                                    $i = 0;
                                    while($row = mysqli_fetch_array($results))
                                    {
                                        if($i == 0)
                                        {
                                            // Categories were found, so list them in the drop down menu
                                            echo '<option value="' . $row['parentCategoryID'] . '"'; if(strcmp($row['parentCategory'], $parentCategory) == 0) echo ' selected'; echo '>' . $row['parentCategory'] . '</option>' . "\n";
                                        }
                                        else
                                        {
                                            // Categories were found, so list them in the drop down menu
                                            echo '                                <option value="' . $row['parentCategoryID'] . '"'; if(strcmp($row['parentCategory'], $parentCategory) == 0) echo ' selected'; echo '>' . $row['parentCategory'] . '</option>' . "\n"; 
                                        }
                                        $i++;
                                    }
                                  ?>
                            </select>
                        </td>
                    </tr>
                     <tr>
                        <th><label>Sub-category:</label></th>
                        <td><input type="text" id="name" name="subCategory" value="<?php if(!empty($subCategory)) echo $subCategory; // If the user typed in a bad name the name field will retain the name they entered. ?>" /></td>
                    </tr>
                    <tr>
                        <th><label>Description:</label></th>
                        <td><textarea id="description" name="description" rows="5" cols="40"><?php if(!empty($description)) echo $description; // If the user typed in a bad description the details field will retain the text they entered. ?></textarea></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Edit Sub-category" name="editSubCategory" class="button mButton" /></td>
                        <td><input type="reset" value="Reset" class="button mButton" /></td>
                    </tr>
                </table>
                </fieldset>
                </form>
            </div>
            <?php
            }
            if($check7 == 3)
            {
              // Confirm the successful edit
              echo '<h2>Sub-category Modified Successfully</h2>';
              require_once('includes/homeurl.php');
              $homeURL .= 'management.php';
              echo '<a href="'. $homeURL . '" title="Return to Management Centre" class="button return">Return to Management Centre</a>' . "\n";
            }
        }
        ?>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>