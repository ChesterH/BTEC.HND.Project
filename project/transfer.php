<?php
  	// Acquire company name
	require_once('includes/name.php');
	
	require_once('includes/session.php');
    
    // Acquire the situation from the referring script
    if(isset($_GET['s']))
    {
        $state = $_GET['s'];
    }
    // If no value for state was provided, then the user is attempting to access the page without authorisation, so redirect them
    else
    {
        require_once('includes/homeurl.php');
        $homeURL .= 'index.php';
        die(header('Location: ' . $homeURL));
    }
	
    // Construct the redirection URL based on the type of user
    require_once('includes/homeurl.php');
	if((isset($_SESSION['userID']) || isset($_COOKIE['email'])))
	{
		if($_SESSION['userType'] == 1)
		{
			$homeURL .=  'management.php';
		}
		else if($_SESSION['userType'] == 2)
		{
			$homeURL .= 'technician.php';
		}
		else if($_SESSION['userType'] == 3)
		{
			$homeURL .= 'index.php';
		}
	}
    // If the user changed their email, send them to the login page so they can continue to use the website as a member
    if($state == 4)
    {
        $homeURL .= 'login.php';
        header('Refresh: 2; url=' . $homeURL);
    }
    else
    {
        header('Refresh: 2; url=' . $homeURL);
    }

	// Add the page header
	$pageTitle = $aCName . " - Transferring...";
	require_once('includes/head.php');
?>
<body>
	<?php require_once('includes/header.php'); ?>
    <div id="mainContent">
      	<h1>Transferring...</h1>
        <?php
			if((isset($_SESSION['userID']) || isset($_COOKIE['email'])) && $state == 1)
			{
				echo '<p>You are now logged in, ' . $_SESSION['firstName'] . '.</p>' . "\n";			
			}
			else if($state == 2)
			{
				echo '<p>You are now logged out.</p>' . "\n";
			}
			else if($state == 3)
			{
				echo '<p>You were not logged in.</p>' . "\n";
			}
            if($state == 4)
            {
                echo '        <p>In 2 seconds you\'ll be taken to the login page.</p>' . "\n";
            }
            else
            {
                echo '        <p>In 2 seconds you\'ll be taken to the homepage.</p>' . "\n";
            }
		?>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>