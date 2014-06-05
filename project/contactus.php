<?php
	// Acquire company name
	require_once('includes/name.php');
	
	// Acquire the connection data for the database
	require_once('includes/common.php');
	
	// Define the address to which the user's message will be sent
	define('EMAIL_ADDRESS', 'mitukai@gmail.com');
	
	// Start the session
	session_start();
	
	// Variable to track if an operation was successful
	$redirect = false;
    
    // If a message was provided, start processing the data
	if(isset($_POST['message']))
	{
		// Redirect the user to the confirmation page.
		$redirect = true;
	}
	
	// Add the page header
	$pageTitle = $aCName . ' - Contact Us';
	$page = 4;
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "usercentre.php", "The User Centre", "User Centre", "Contact Us");
    
    // Add the JavaScript validation script
    $uservalidation = true;
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
        <h1>Contact Us</h1>
        <?php
        // If the message was not sent, display the form
		if(!$redirect)
		{
			if(!empty($errorMsg))
            {
                echo '<p class="errorMsg">' . $errorMsg . '</p>' . "\n";
            }
		?>
        <div id="contactFormFrame">
            <form id="contactForm"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return contact(this);">
            <fieldset><legend>Contact <?php echo $aCName; ?></legend>
            <table id="contactTable">
                <tr>
                    <th><label>Name:</label></th>
                    <td><input type="text" id="name" name="name" onblur="checkForContent(this, document.getElementById('name_help'))" value="<?php if(!empty($name)) echo $name; // If the user typed in a bad name the name field will retain the name they entered. ?>" /></td>
                </tr>
                <tr>
                    <th><label>Your Email:</label></th>
                    <td><input type="text" id="email" name="email" onblur="checkForContent(this, document.getElementById('email_help'))" value="<?php if(!empty($email)) echo $email; // If the user typed in a bad email name the email field will retain the email they entered. ?>" /></td>
                </tr>
                <tr>
                    <th><label>Message:</label></th>
                    <td><textarea id="message" name="message" rows="5" cols="40"></textarea></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td><input type="submit" value="Send" name="sendMessage" class="button" /></td>
                    <td><input type="reset" value="Reset" class="button" /></td>
                </tr>
            </table>
            </fieldset>
            </form>
        </div>
        <?php
			}
			if($redirect)
			{
			  // Confirm success
			  echo '<h2>Message Sent Successfully</h2>' . "\n";
			  echo '<p>Thank you for contacting us.</p>' . "\n";
			  require_once('includes/homeurl.php');
			  $homeURL .= 'usercentre.php';
			  echo '<a href="'. $homeURL . '" class="button">Return to the User Centre</a>' . "\n";
			}
        ?> 
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>