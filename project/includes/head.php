<?php
	echo '<!doctype html>' . "\n";
	echo '<html>' . "\n";
	echo '<head>' . "\n";
	echo '<meta charset="utf-8">' . "\n";
    // If the page is for the staff it should not be indexed by search engine robots
    if(isset($staff))
    {
        echo '<meta name="robots" content="noindex,nofollow" />' . "\n";
    }
	echo '<link href="css/style.css" type="text/css" rel="stylesheet" />' . "\n";
	if(preg_match("/Staff/", $pageTitle)) { echo '<link href="css/staff.css" type="text/css" rel="stylesheet" />' . "\n"; };
	echo '<link href="images/favicon.ico" rel="shortcut icon" />' . "\n";
    // Add JavaScript validation for all staff pages with forms
    if(isset($staffvalidation))
    {
        echo '<script type="text/javascript" src="js/staffvalidation.js"></script>' . "\n";
    }
    // Add JavaScript validation for all general pages with forms
    if(isset($uservalidation))
    {
        echo '<script type="text/javascript" src="js/uservalidation.js"></script>' . "\n";
    }
	echo '<title>' . $pageTitle.'</title>' . "\n";
	echo '</head>' . "\n";
?>