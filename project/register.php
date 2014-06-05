<?php
	// Acquire company name
	require_once('includes/name.php');
	
	// Acquire the connection data for the database
	require_once('includes/common.php');
	
	// Start the session
	session_start();
	
	// If the user is logged in, redirect them to the homepage (users who are logged-in have no reason to access the registration page)
	if(isset($_SESSION['email']) || isset($_COOKIE['email']))
	{
		require_once('includes/homeurl.php');
		$homeURL .= 'index.php';
		die(header('Location: ' . $homeURL));
	}
	
	$check = 0;
	$messageNo = 0;
	
	if(isset($_POST['submit']))
	{ 
		//Extract the data input into the form via the superglobal array $_POST[]
		$title = mysqli_real_escape_string($dbc, trim($_POST['title']));
		$first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
		$last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
		if(!empty($_POST['gender']))
		{
			$gender = mysqli_real_escape_string($dbc, trim($_POST['gender']));
		}
		else
		{
			$gender = "";
		}
		$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
		$password1 = mysqli_real_escape_string($dbc, trim($_POST['password1'])); //Encrypts the first password
		$password2 = mysqli_real_escape_string($dbc, trim($_POST['password2'])); //Encrypts the second password
		$tel_num = mysqli_real_escape_string($dbc, trim($_POST['tel_num']));
		$dob_day = mysqli_real_escape_string($dbc, trim($_POST['date_of_birth_day']));
		$dob_month = mysqli_real_escape_string($dbc, trim($_POST['date_of_birth_month']));
		$dob_year = mysqli_real_escape_string($dbc, trim($_POST['date_of_birth_year']));
		$dob = $dob_year . "-" . $dob_month . "-" . $dob_day;
		
		$check = 1;
		
		//Validate Form Fields
		if(empty($first_name))
		{
		    // $first_name is blank
		    $messageNo = 1;
		}
		else if(empty($last_name))
		{
		    // $last_name is blank
		    $messageNo = 2;
		}
		else if (empty($gender))
		{
		    // $gender is blank
		    $messageNo = 3;
		}
		else if(!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/', $email))
		{
		    // $email is invalid because LocalName is bad
		    $messageNo = 4;
		}
		else if((strlen($password1) < 4) || (strlen($password2) < 4))
		{
		    //password is blank or the password fields do not match
		    $messageNo = 5;
		}
		else if($password1 != $password2)
		{
		    //password is blank or the password fields do not match
		    $messageNo = 6;
		}
		else
		{
		    //passwords match
		    $password = md5($password1);
		}
		if(!preg_match('/^(?:[-\s]?\d){7}$/', $tel_num))
		{
		    // $tel_num is not valid
		    $messageNo = 7;
		  
		}
		else if(empty($dob_day) || empty($dob_month) || empty($dob_year))
		{ 
            //$dob is not valid
            $messageNo = 8;
		}
		// Check to see if the form was accessed directly by checking the email variable for a non-empty value.
		else if(empty($email))
		{
            $messageNo = 9;
		}
        else if(($dob_month > 5) && ($dob_year == 1996))
        {
            $messageNo = 10;
        }
		if(!empty($email))
		{
			$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL server.');
			
			$emailquery = "SELECT * FROM member_tbl WHERE email = '$email'";
			$emailresult = mysqli_query($dbc, $emailquery) or die('<p>Error encountered.</p>');
			$row = mysqli_fetch_array($emailresult);
			if($row['email'] == $email)
			{
			  //email already exists
			  $messageNo = 11;
			}
		}
		if($messageNo == 0)
		{
			$query = "INSERT INTO member_tbl (title, firstName, lastName, gender, email, password, DOB, contactNo) VALUES ('$title', '$first_name', '$last_name', '$gender', '$email', '$password', '$dob', '$tel_num')";
			// Issue query and check for database errors. (Error querying database)
			$result = mysqli_query($dbc, $query) or die('<p>Error encountered.<br />Navigate back to the form and check your data.</p>');
            // Add user's cart
            $query = "INSERT INTO cart_tbl (memberID) VALUES ((SELECT memberID FROM member_tbl WHERE email = '$email'))";
            $result = mysqli_query($dbc, $query) or die('<p>Error encountered.<br />Navigate back to the form and check your data.</p>');
			// "Housekeeping"
			mysqli_close($dbc);
		}
	}
	
	// Add the page header
	$pageTitle = $aCName . " - User Registration";
	$page = 6;
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "Registration");
    
    // Add the JavaScript validation script
    $uservalidation = true;
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
      	<h1>User Registration</h1>
        <?php
        if($check == 0)
		{
		?>
        <div id="registrationFormFrame">
        <form id="registrationForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return registration(this);">
        <fieldset><legend>Registration Form</legend>
        <table id="registerTable">
            <tr>
                <th>Select your title:</th>
                <td>
                    <select name="title" id="titlemenu">
                        <option value="Mr.">Mr.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Ms.">Ms.</option>
                        <option value="Dr.">Dr.</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>First Name:</label></th>
                <td><input type="text" id="first_name" name="first_name" /></td>
            </tr>
            <tr>
                <th><label>Last Name:</label></th>
                <td><input type="text" id="last_name" name="last_name" /></td>
            </tr>
            <tr>
                <th><label>Gender:</label></th>
                <td>Male: <input id="gender_male" name="gender" type="radio" value="M" /> Female: <input id="gender_female" name="gender" type="radio" value="F" /></td>
            </tr>
            <tr>
                <th><label>Email:</label></th>
                <td><input type="text" id="email" name="email" value="" /></td>
            </tr>
            <tr>
                <th><label>Password:</label></th>
                <td><input type="password" id="password1" name="password1" /></td>
            </tr>
            <tr>
                <th><label>Password (retype):</label></th>
                <td><input type="password" id="password2" name="password2" /></td>
            </tr>
            <tr>
                <th><label>Date of Birth:</label></th>
                <td id="date_of_birth">
                <!-- Select Drop Down with default option
                    Select Title selected -->
                    <select name="date_of_birth_day">
                      <option value=""> - Day - </option>
                      <?php
						$day = 1;
						while($day <= 31)
						{
							// Increment day each option is displayed
							echo '					  <option value="' . $day . '">' . $day . '</option>' . "\n";
							$day++;
						}
                      ?>
                    </select>
                    <select name="date_of_birth_month">
                      <option value=""> - Month - </option>
                      <option value="1">January</option>
                      <option value="2">February</option>
                      <option value="3">March</option>
                      <option value="4">April</option>
                      <option value="5">May</option>
                      <option value="6">June</option>
                      <option value="7">July</option>
                      <option value="8">August</option>
                      <option value="09">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                    </select>
                    <select name="date_of_birth_year">
                      <option value=""> - Year - </option>
					  <?php
						$year = 1996;
						while($year > 1914)
						{
							// Decrement year after each option is displayed
							echo '					  <option value="' . $year . '">' . $year . '</option>' . "\n";
							$year--;
						}
                      ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Telephone Number:</label></th>
                <td>
                    <input type="text" id="tel_num" name="tel_num" value="" />
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td><input type="submit" value="Register" name="submit" class="button formButton" /></td>
                <td><input type="reset" value="Reset Form" class="button formButton" /></td>
            </tr>
        </table>
        </fieldset>
        </form>
        </div>
        <p>Notes:</p>
        <p>Register to gain full access to the site, including the site's bulletin board, special offers and privileges.</p>
        <p>Also, note the following points regarding registration and the registration form:</p>
        <ul>
            <li>You must be at least 18 years old on the day you register. See the FAQ page for details.</li>
            <li>The minimum password length is 4 characters. Example: "1234".</li>
            <li>Telephone number should be in the format "555 5555" or "555-5555".</li>
            <li>Valid emails are required. Emails should look like this: "my_name@my_email_host.com".</li>
        </ul>
        <?php
		}
		if($check == 1)
		{
        switch($messageNo)
		{
			case 0:
				echo '<p>Congratulations! You successfully filled out the registration form.</p>';
				echo '<p>You will be redirected to the login page shortly.</p><br />';
				echo '</div>';
				require_once('includes/footer.php');
				echo '</body>';
				echo '</html>';
				require_once('includes/homeurl.php');
				$homeURL .= 'login.php';
				die(header('Refresh: 2; url=' . $homeURL));
				break;
			case 1:
				echo '<p>There was an error with the First name field of the registration form.</p>';
				break;
			case 2:
				echo '<p>There was an error with the Last name field of the registration form.</p>';
				break;
			case 3:
				echo '<p>There was an error with the Gender field of the registration form.</p>';
				break;
			case 4:
				echo '<p>Your email\'s local name was found to be invalid. This may mean you entered your email in an incorrect format.</p>';
				break;
			case 5:
				echo '<p>Your password was shorter than the minimum length.</p>';
				break;
			case 6:
				echo '<p>Your password fields did not match.</p>';
				break;
			case 7:
				echo '<p>Your telephone number is invalid.</p>';
				break;
			case 8:
				echo '<p>Your date of birth was found to be invalid.</p>';
				break;
			case 9:
				echo '<p>The form was not filled out.</p>';
				break;
            case 10:
                echo '<p>You are not at least 18 years of age and therefore are not allowed to register with this site.</p>';
                break;
			case 11:
				echo '<p>The email you provided already exists in this website\'s database.</p>';
				break;
			default:
				echo '<p>There was an error.</p>'; //If this page was called and a value for "result" that is not found here was provided then some sort of error must have occurred.
		}
		echo '<a href="register.php" title="The Registration Page" class="button">Return to the Registration Page</a>';
		}
		?>
    </div>
<?php require_once('includes/footer.php'); ?>
</body>
</html>