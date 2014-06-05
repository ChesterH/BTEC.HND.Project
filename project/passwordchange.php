<?php	
	// Acquire company name
	require_once('includes/name.php');
	
	// Acquire the connection data for the database
	require_once('includes/common.php');
	
	// Start the session
	session_start();
    
	// Variable to track if an operation was successful
	$check = 0;
 
	if(isset($_POST['submit']))
	{
		if(!empty($_POST['currentPassword']) && !empty($_POST['newPassword1']) && !empty($_POST['newPassword2']))
		{
			$currentPassword = md5(mysqli_real_escape_string($dbc, trim($_POST['currentPassword'])));
			$newPassword1 = md5(mysqli_real_escape_string($dbc, trim($_POST['newPassword1'])));
			$newPassword2 = md5(mysqli_real_escape_string($dbc, trim($_POST['newPassword2'])));
			if(strcmp($newPassword1, $newPassword2) == 0)
			{
				$memberID = $_SESSION['userID'];
				$query = "SELECT password FROM member_tbl WHERE memberID = '$memberID'";
				$result = mysqli_query($dbc, $query) or $errorMsg = 'Error encountered.';
				$row = mysqli_fetch_array($result);
				if (mysqli_num_rows($result) == 1)
				{
					$currentCompare = $row['password'];
					if(strcmp($currentPassword, $currentCompare) == 0)
					{
						// The memberID matches a member in the table
						$query = "UPDATE member_tbl SET password = '$newPassword1' WHERE memberID = '$memberID'";
						$result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with updating your password. Please try again or contact support if the problem persists.';
						// Query is successful.
						$check = 1;
					}
					else
					{
						$errorMsg = 'The password you entered as your current password is incorrect.';
					}
				}
				else
				{
					$errorMsg = 'There was an error with retrieving your current password from the database. Please try again or contact support if the problem persists.';
				}
			}
			else
			{
				$errorMsg = 'The passwords you entered do not match.';
			}
		}
		else
		{
			$errorMsg = 'You did not provide passwords.';
		}
	}
	
	// Add the page header
	$pageTitle = $aCName . ' - Change Password';
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "usercentre.php", "The User Centre", "User Centre", "Password Change");
    
    // Add the validation script
    $uservalidation = true;
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
    	<?php
		if($check == false)
		{
		    mysqli_close($dbc);
			if(!empty($errorMsg))
            {
                echo '<p class="errorMsg">' . $errorMsg . '</p>' . "\n";
            }
		 ?>
        <h1>Password Change</h1>
        <p>Use the form below to change your password.</p>
        <div id="passwordChangeFormFrame">
        <form id="passwordChangeForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return passwordChange(this);">
        <fieldset><legend>Password Change Form</legend>
        <table id="passwordChangeTable">
            <tr>
                <th><label>Current Password:</label></th>
                <td><input type="password" id="currentPassword" name="currentPassword" /></td>
            </tr>
            <tr>
                <th><label>New Password:</label></th>
                <td><input type="password" id="newPassword1" name="newPassword1" /></td>
            </tr>
            <tr>
                <th><label>New Password (repeat):</label></th>
                <td><input type="password" id="newPassword2" name="newPassword2" /></td>
            </tr>
        </table>
        <table>
            <tr>
                <td><input type="submit" value="Submit" name="submit" class="button" /></td>
                <td><input type="reset" value="Reset Form" onclick="" class="button" /></td>
            </tr>
        </table>
        </fieldset>
        </form>
        </div>
        <?php
        }
        else
		{
          // Confirm the successful reset
		  echo '<h1>Change Successful</h1>';
          echo '<p>Congratulations! Your successfully changed your password.</p>' . "\n";
		  echo '<a href="index.php" title="' . $aCName . 'Homepage" class="button">Go to the Homepage</a>';
		  // "Housekeeping"
		  mysqli_close($dbc);
        }
		?>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>