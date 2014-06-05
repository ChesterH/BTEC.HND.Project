<?php
	// Acquire company name
	require_once('includes/name.php');
	
	// Acquire the connection data for the database
	require_once('includes/common.php');
	
	// Start the session
	session_start();
	
	// If the user is logged in, redirect them to the homepage (users who are logged-in have no reason to access the login page)
	if(isset($_SESSION['email']) || isset($_COOKIE['email']))
	{
		require_once('includes/homeurl.php');
		$homeURL .= 'index.php';
		die(header('Location: ' . $homeURL));
	}
    
    // Use a variable passed in the URL, which is hidden from regular users, i.e. only those who know the specific URL can access the page, to unlock the staff login functionality
    if(!empty($_GET['staff']))
    {
        // The variable titled "staff" must be used along with its value of "ltsstaff" for the staff login functionality to be unlocked
        if(strcmp($_GET['staff'], "ltsstaff") == 0)
        {
            // Set a session variable to allow the process continued through $_POST to know the user had unlocked the staff login functionality
            $_SESSION['staffLogin'] = true;
        }
    }
	
    // If the user isn't logged in, try to log them in
    if(!isset($_SESSION['email']))
    {
    	if (isset($_POST['submit']))
    	{
            // Grab the user-entered log-in data
            $userEmail = mysqli_real_escape_string($dbc, trim($_POST['email']));
            $userPassword = mysqli_real_escape_string($dbc, trim($_POST['password']));
            if(!empty($userEmail) && !empty($userPassword))
            {  
                // If the session variable is not empty, then the user had unlocked staff login functionality
                if(!empty($_SESSION['staffLogin']))
                {
                    // Look up the email and password in the database
                    $encryptedPassword = md5($userPassword);
                    $query = "SELECT staffID, firstName, email, userType FROM staff_tbl WHERE email = '$userEmail' AND password = '$encryptedPassword'";
                    $result = mysqli_query($dbc, $query);
    
                    if(mysqli_num_rows($result) == 1)
                    {
                        // The credentials are valid so set the user ID and email session vars and cookies and redirect the user to the home page
                        $row = mysqli_fetch_array($result);
                        $_SESSION['userID'] = $row['staffID'];
                        $_SESSION['firstName'] = $row['firstName'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['userType'] = $row['userType'];
                        setcookie('userID', $row['staffID'], time() + (60 * 60 * 24 * 30));    // Expires in 30 days
                        setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));  // Expires in 30 days
                        setcookie('userType', $row['userType'], time() + (60 * 60 * 24 * 30));  // Expires in 30 days
                        header('Location: transfer.php?s=1');
                    } 
                    else
                    {
                        // The credentials are incorrect so set an error message
                        $errorMsg = 'The email and/or password you entered was incorrect. Please try again or contact support if the problem persists.';
                    }
                }
                else
                {
                    // Look up the email and password in the database
                    $encryptedPassword = md5($userPassword);
                    $query = "SELECT memberID, firstName, email, userType FROM member_tbl WHERE email = '$userEmail' AND password = '$encryptedPassword'";
                    $result = mysqli_query($dbc, $query);
    
                    if(mysqli_num_rows($result) == 1)
                    {
                        // The credentials are valid so set the user ID and email session vars and cookies and redirect the user to the home page
                        $row = mysqli_fetch_array($result);
                        $_SESSION['userID'] = $row['memberID'];
                        $_SESSION['firstName'] = $row['firstName'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['userType'] = $row['userType'];
                        setcookie('userID', $row['memberID'], time() + (60 * 60 * 24 * 30));    // Expires in 30 days
                        setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));  // Expires in 30 days
                        setcookie('userType', $row['userType'], time() + (60 * 60 * 24 * 30));  // Expires in 30 days
                        header('Location: transfer.php?s=1');
                    } 
                    else
                    {
                        // The credentials are incorrect so set an error message
                        $errorMsg = 'The email and/or password you entered was incorrect. Please try again or contact support if the problem persists.';
                    }
                }
            }
            else
            {
                // The credentials weren't entered
                $errorMsg = 'No email and/or password was entered. Please try again or contact support if the problem persists.';
            }
    	}
    }
		
	// Add the page header
	$pageTitle = $aCName . " - User Login";
	$page = 5;
    
    // Add the JavaScript validation script
    $uservalidation = true;
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "User Login");
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
        <?php
  		// If the user is not logged in, display the login form
		if (empty($_SESSION['userID']))
		{
		  	// If an error was encountered and logged, display it, otherwise don't add the markup
            if(!empty($errorMsg))
            {
                echo '<p class="errorMsg">' . $errorMsg . '</p>' . "\n";
            }
		 ?>
        <div id="loginFormFrame">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return login(this);">
            <fieldset><legend>User Log-in Form</legend>
            <table id="loginTable">
                <tr>
                    <th><label>Email:</label></th>
                    <td><input type="text" id="email" name="email" value="<?php if (!empty($user_email)) echo $user_email; // If the user typed in a wrong password the email field will retain the email they entered. ?>" /></td>
                </tr>
                <tr>
                    <th><label>Password:</label></th>
                    <td><input type="password" id="password" name="password" /></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td><input type="submit" value="Log-in" name="submit" class="button formButton" /></td>
                    <td><input type="reset" value="Reset" class="button formButton" /></td>
                </tr>
            </table>
            </fieldset>
            </form>
        </div>
		<?php
        }
        ?>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>