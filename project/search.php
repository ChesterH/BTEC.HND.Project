<?php
	// Acquire company name
	require_once('includes/name.php');
	
	// Connect to the database
	require_once('includes/common.php');
	
	// Start the session
	session_start();
	
    // Variable to track if an operation was successful
	$check = 1;
	
    // If the URL does not contain a search term the user will be sent back to whichever page on which they last were
	if(empty($_GET['s']))
	{
		$URL = $_SERVER['HTTP_REFERER'];
		die(header('Location: ' . $URL));
	}
	else
	{
		$userSearchTerm = mysqli_real_escape_string($dbc, trim($_GET['s']));
	}
    // Acquire the sort setting
	if(empty($_GET['t']))
	{
		$sort = 1;
	}
	else
	{
		$sort = $_GET['t'];
	}

	// This function builds a search query from the search keywords and sort setting
	function buildQuery($userSearchTerm, $sort)
	{
		$searchQuery = "SELECT * FROM product_tbl";
		// Extract the search keywords into an array
		$cleanSearch = str_replace(',', ' ', $userSearchTerm);
		$searchWords = explode(' ', $cleanSearch);
		$finalSearchWords = array();
		if(count($searchWords) > 0)
		{
			foreach($searchWords as $word)
			{
				if(!empty($word))
				{
					$finalSearchWords[] = $word;
				}
			}
		}
		// Generate a WHERE clase using all of the search keywords
		$whereList = array();
		if(count($finalSearchWords) > 0)
		{
			foreach($finalSearchWords as $word)
			{
				$whereList[] = "product LIKE '%$word%'";
			}
		}
		$whereClause = implode(' OR ', $whereList);
		// Add the keyword WHERE clause to the search query
		if(!empty($whereClause))
		{
			$searchQuery .= " WHERE $whereClause AND availability = 'T'";
		}
		// Sort the search query using the sort setting
		switch($sort)
		{
			// Ascending by product name
			case 1:
				$searchQuery .= " ORDER BY product";
				break;
			// Descending by product name
			case 2:
				$searchQuery .= " ORDER BY product DESC";
				break;
			// Ascending by price
			case 3:
				$searchQuery .= " ORDER BY price";
				break;
			// Descending by price
			case 4:
				$searchQuery .= " ORDER BY price DESC";
				break;
			// Ascending by category
			case 5:
				$searchQuery .= " ORDER BY subCategoryID";
				break;
			// Descending by category
			case 6:
				$searchQuery .= " ORDER BY subCategoryID DESC";
				break;
			default:
				// No sort setting provided, so don't sort the query
		}
		return $searchQuery;
	}
    // Build the main part of the query
	$searchQuery = buildQuery($userSearchTerm, $sort);
	
	// This function builds heading links based on the specified sort setting
	function sortLinksGenerator($userSearchTerm, $sort)
	{
		$sortLinks = '';
		switch($sort)
		{
			case 1:
				$sortLinks .= '            <td><a href = "' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=2" class="button">Name</a></td>' . "\n";
				$sortLinks .= '            <td><a href = "' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=3" class="button">Price</a></td>' . "\n";
				$sortLinks .= '            <td><a href = "' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=5" class="button">Category</a></td>' . "\n";
				break;
			case 3:
				$sortLinks .= '            <td><a href = "' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=1" class="button">Name</a></td>' . "\n";
				$sortLinks .= '            <td><a href = "' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=4" class="button">Price</a></td>' . "\n";
				$sortLinks .= '            <td><a href = "' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=3" class="button">Category</a></td>' . "\n";
				break;
			case 5:
				$sortLinks .= '            <td><a href = "' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=1" class="button">Name</a></td>' . "\n";
				$sortLinks .= '            <td><a href = "' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=3" class="button">Price</a></td>' . "\n";
				$sortLinks .= '            <td><a href = "' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=6" class="button">Category</a></td>' . "\n";
				break;
			default:
				$sortLinks .= '            <td><a href = "' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=1" class="button">Name</a></td>' . "\n";
				$sortLinks .= '            <td><a href = "' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=3" class="button">Price</a></td>' . "\n";
				$sortLinks .= '            <td><a href = "' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=5" class="button">Category</a></td>' . "\n";
				break;
		}	
		return $sortLinks;
	}
	
	// This function builds navigational page links based on the current page and the number of pages
	function pageLinksGenerator($userSearchTerm, $sort, $curPage, $numPages)
	{
		$pageLinks = '';
		//If this page is not the first page, generate the "Previous" link
		if($curPage > 1)
		{
			$pageLinks .= '            <li><a href="' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=' . $sort . '&amp;page=' . ($curPage - 1) . '" class="pLButton">&lt;</a></li>' . "\n"; // We still have to pass along the user search data and the sort order in each link URL
		}
		else
		{
			// The "previous" link appears as a left arrow, as in "<-"
		}
		// Loop through the pages generating the page number links
		for($i = 1; $i <= $numPages; $i++)
		{
			if($curPage == $i)
			{
				$pageLinks .= '            <li><span class="psuedoB pLButton">' . $i . '</span></li>' . "\n";
			}
			else
			{
				// Make sure each page link points back to the same script - we're just passing a different page number with each link
				$pageLinks .= '            <li><a href="' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=' . $sort . '&amp;page=' . $i . '" class="pLButton">' . $i . '</a></li>' . "\n"; // The link to a specific page is just the page number
			}
		}
		// If this page is not the last page, generate the "Next" link
		if($curPage < $numPages)
		{
			$pageLinks .= '            <li><a href="' . $_SERVER['PHP_SELF'] . '?s=' . $userSearchTerm . '&amp;t=' . $sort . '&amp;page=' . ($curPage + 1) . '" class="pLButton">&gt;</a></li>' . "\n"; //The "next" link appears as a right arrow, as in "->"
		}
		return $pageLinks;
	}
	// Calculate pagination information
	$curPage = isset($_GET['page']) ? $_GET['page'] : 1; // Initialise the pagination variables because they will be needed to LIMIT the query and build the pagination links
	$resultsPerPage = 5; // number of results per page;
	$skip = (($curPage - 1) * $resultsPerPage);
	// Query to get the total results
	$searchQueryResult = mysqli_query($dbc, $searchQuery) or $errorMsg = 'There was an error with acquiring the search results. Please try again or contact support if the problem persists.';
	$totalSearchResults = mysqli_num_rows($searchQueryResult);
    // Check to see if the qeury found something
	if($totalSearchResults == 0)
	{
		$check = 0;
	}
    
    // Add the page header
    $pageTitle = $aCName . " - Search - " . $userSearchTerm;
    require_once('includes/homeurl.php');
    
    // Add image URL
    $imageURL = $homeURL . 'images/';
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "Product Search");
    
    require_once('includes/head.php');
    ?>
    <body>
        <?php require_once('includes/header.php'); ?>
        <div id="mainContent">
            <h2>Search results for: "<?php echo $userSearchTerm ?>"</h2>
    <?php
	// If an error was encountered, echo the error markup, otherwise there was no error and no operation had just been successfully executed, so skip echoing the markup
    if(isset($errorMsg))
    {
        echo '<p class="errorMsg">' . $errorMsg . '</p>' . "\n";
    }
	// If the query found something, display the results
	if($check != 0)
	{
		$numPages = ceil($totalSearchResults / $resultsPerPage);
		
		// Start generating the table of results
		echo '<section>' . "\n";
        echo '    <table id="searchResultsHeadingTable">' . "\n";	
		//Generate the search result headings
		echo '        <tr>' . "\n";
		echo sortLinksGenerator($userSearchTerm, $sort); // Call the sortLinksGenerator() function to create the links for the results headings then display them
		echo '        </tr>' . "\n";
        echo'    </table>' . "\n";
		
		// Query again to get just the subset of results
		$searchQuery = $searchQuery . " LIMIT $skip, $resultsPerPage"; // The LIMIT clause created to qeury only a subset of results
		$searchQueryResult = mysqli_query($dbc, $searchQuery) or $errorMsg = 'There was an error with acquiring a page of the results. Please try again or contact support if the problem persists.';
		while($row = mysqli_fetch_array($searchQueryResult))
		{
		    // Acquire the sub-category's name from the ID
		    $subCategoryID = $row['subCategoryID'];
		    $query2 = "SELECT subCategory FROM sub_category_tbl WHERE subCategoryID = '$subCategoryID'";
            $result2 = mysqli_query($dbc, $query2) or $errorMsg = 'There was an error with acquiring sub-category data. Please try again or contact support if the problem persists.';
            $row2 = mysqli_fetch_array($result2);
            $subCategory = $row2['subCategory'];
            echo'    <table class="searchResultsTable">' . "\n";
			echo '        <tr>' . "\n";
			echo '            <td><a href="products.php?p=' . $row['productID'] . '"  class="button">' . $row['product'] . '</a></td><td></td><td></td>' . "\n";
            echo '        </tr>' . "\n";
            echo '        <tr>' . "\n";
            echo '            <td class="productImage"><img src="' . $imageURL . $row['image'] . '" alt="' . $row['product'] . '" title="' . $row['product'] . '" /></td>' . "\n";
            echo '            <td>' . $row['price'] . '</td>' . "\n";
            echo '            <td>' . $subCategory . '</td>' . "\n";
            echo '        </tr>' . "\n";
            echo '    </table>' . "\n";
            echo '    <table class="searchDescription pb2 mb2">' . "\n";
            echo '        <tr>' . "\n";
			echo '            <td class="productDescription">' . $row['details'] . '<a href="' . $row['link'] . '"  class="button searchLink">Manufacturer\'s Page</a></td>' . "\n";
            echo '        </tr>' . "\n";
            echo '    </table>' . "\n";
		}
		echo '    </section>' . "\n";
		// Generate navigational links if we have more than one page
		if($numPages > 1)
		{
		    echo '    <section id="paginationLinks" class="hList">' . "\n";
            echo '    <ul>' . "\n";
			echo pageLinksGenerator($userSearchTerm, $sort, $curPage, $numPages); // Call the pageLinksGenerator() function to generate the page links then display them
			echo '    </ul>' . "\n";
			echo '    </section>' . "\n";
		}
		
		echo '        <a href="#top" title="Back to Top" class="button baseButton">Back to Top</a>' . "\n";
	}
	else
	{
		echo '        <p>Nothing found.</p>' . "\n";
	}
	// "Housekeeping"
	mysqli_close($dbc);
	
?>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>