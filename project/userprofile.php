<?php	
	// Acquire company name
	require_once('includes/name.php');
	
    // Acquire the connection data for the database
    require_once('includes/common.php');
    
	// Start the session
	session_start();
    
    // If the user is not logged in, redirect them to the homepage (users who are not logged-in have no reason to access the user profile page)
    if(!isset($_SESSION['email']) || !isset($_COOKIE['email']))
    {
        require_once('includes/homeurl.php');
        $homeURL .= 'index.php';
        die(header('Location: ' . $homeURL));
    }
    
    // If the URL contains unknown information the user will be redirected to this page
    if(empty($_GET['m']) && !empty($_GET))
    {
        require_once('includes/homeurl.php');
        $homeURL .= 'userprofile.php';
        die(header('Location: ' . $homeURL));
    }
    
    $check = 0;
    $email = $_SESSION['email'];
    $userInfo = array();
    if($_SESSION['userType'] == 3)
    {
        $query = "SELECT * FROM member_tbl WHERE email = '$email'";
        $result = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($result);
        $userID = $row['memberID'];
        $userInfo = $row;
    }
    else
    {
        $query = "SELECT * FROM staff_tbl WHERE email = '$email'";
        $result = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($result);
        $userID = $row['staffID'];
        $userInfo = $row;   
    }
    
    
    if(isset($_POST['updateProfile']))
    {
        //Extract the data input into the form via the superglobal array $_POST[]
        $title = mysqli_real_escape_string($dbc, trim($_POST['title']));
        $first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
        $last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
        $tel_num = mysqli_real_escape_string($dbc, trim($_POST['tel_num']));
        if($_SESSION['userType'] == 3)
        {
            $dob_day = mysqli_real_escape_string($dbc, trim($_POST['date_of_birth_day']));
            $dob_month = mysqli_real_escape_string($dbc, trim($_POST['date_of_birth_month']));
            $dob_year = mysqli_real_escape_string($dbc, trim($_POST['date_of_birth_year']));
            $dob = $dob_year . "-" . $dob_month . "-" . $dob_day;
        }
        
        //Validate Form Fields
        if(empty($first_name) || empty($last_name) || empty($tel_num))
        {
            if($_SESSION['userType'] == 3)
            {
                // If the user is a member, check their date of birth     
                if(empty($dob_day) || empty($dob_month) || empty($dob_year))
                {
                    // A field was left blank
                    $errorMsg = 'One of the fields in the form was left blank. Please try again or contact support if the problem persists.';
                }
            }
            // A field was left blank
            $errorMsg = 'One of the fields in the form was left blank. Please try again or contact support if the problem persists.';
        }
        if(!preg_match('/^(?:[-\s]?\d){7}$/', $tel_num))
        {
            // $tel_num is not valid
            $errorMsg = 'The telephone number you provided was invalid. Please try again or contact support if the problem persists.';
          
        }
        if(!isset($errorMsg))
        {
            if($_SESSION['userType'] == 3)
            {
                $query = "UPDATE member_tbl SET title = '$title', firstName = '$first_name', lastName = '$last_name', DOB = '$dob', contactNo = '$tel_num' WHERE memberID = '$userID'";
            }
            else
            {
                $query = "UPDATE staff_tbl SET title = '$title', firstName = '$first_name', lastName = '$last_name', contactNo = '$tel_num' WHERE staffID = '$userID'";
            }
            // Issue query and check for database errors
            $result = mysqli_query($dbc, $query) or $errorMsg = 'There was a problem with updating your profile. Please try again or contact support if the problem persists.';
            $check = 2;
        }
    }
    else if(isset($_POST['changeEmail']))
    {
        $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
        if(!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/', $email))
        {
            // $email is invalid because LocalName is bad
            $errorMsg = 'The email you provided was invalid. Please try again or contact support if the problem persists.';
        }
        if(!isset($errorMsg))
        {
            if(strcmp($email, $_SESSION['email']) != 0)
            {
                if($_SESSION['userType'] == 3)
                {
                    $query = "SELECT * FROM member_tbl WHERE memberID = '$userID'";
                }
                else
                {
                    $query = "SELECT * FROM staff_tbl WHERE staffID = '$userID'";
                }
                $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with processing your data. Please try again or contact support if the problem persists.';
                $row = mysqli_fetch_array($result);
                if($row['email'] == $email)
                {
                  // The email provided by the user already exists
                  $errorMsg = 'The email you provided was invalid. Please try again or contact support if the problem persists.';
                }
            }
            else
            {
                $errorMsg = 'You did not provide a different email. Please try again or contact support if the problem persists.';
            }
        }
        if(!isset($errorMsg))
        {
            $memberEmail = $_SESSION['email'];
            if($_SESSION['userType'] == 3)
            {
                $query = "UPDATE member_tbl SET email = '$email' WHERE memberID = '$userID'";
            }
            else
            {
                $query = "UPDATE staff_tbl SET email = '$email' WHERE staffID = '$userID'";
            }
            // Issue query and check for database errors
            $result = mysqli_query($dbc, $query) or $errorMsg = 'There was a problem with changing your email. Please try again or contact support if the problem persists.';
            $check = 4;
        }
    }
    
    // The user chose to modify their user details
    if(isset($_GET['m']))
    {
        // The user chose to modify their profile details
        if($_GET['m'] == 1 && $_SESSION['userType'] == 3)
        {
            list($dOBYear, $dOBMonth, $dOBDay) = explode("-", $row['DOB']);
            $check = 1;
        }
        else if($_GET['m'] == 1 && $_SESSION['userType'] != 3)
        {
            $check = 1;
        }
        // The user chose to modify their email
        else if($_GET['m'] == 2)
        {
            $check = 3;
        }
        // The value does not match the allowed options so redirect the user
        else
        {
            require_once('includes/homeurl.php');
            $homeURL .= 'userprofile.php';
            die(header('Location: ' . $homeURL));
        }
    }
	
	// Add the page header
	$pageTitle = $aCName . " - User Profile";
    
    // Add the breadcrumbs
    if(isset($_GET['m']))
    {
        if($_GET['m'] == 1)
        {
            $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "usercentre.php", "The User Centre", "User Centre", "userprofile.php", "User Profile", "User Profile", "Modify Profile");
            
        }
        else if($_GET['m'] == 2)
        {
            $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "usercentre.php", "The User Centre", "User Centre", "userprofile.php", "User Profile", "User Profile", "Change Email");
            
        }
    }
    else
    {
        $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "usercentre.php", "The User Centre", "User Centre", "User Profile");
    }
    
    // Add the JavaScript validation script
    $uservalidation = true;
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
    	<?php
    	   // If an error was encountered, echo the error markup, otherwise there was no error and no operation had just been successfully executed, so skip echoing the markup
            if(isset($errorMsg))
            {
                echo '<p class="errorMsg">' . $errorMsg . '</p>' . "\n";
            }
            if($check == 0)
            {
		?>
        <h1>Your Profile</h1>
        <div id="userProfile">
        <table id="profileTable">
            <tr>
                <th>Title:</th>
                <td><?php echo $userInfo['title']; ?></td>
            </tr>
            <tr>
                <th><label>First Name:</label></th>
                <td><?php echo $userInfo['firstName']; ?></td>
            </tr>
            <tr>
                <th><label>Last Name:</label></th>
                <td><?php echo $userInfo['lastName']; ?></td>
            </tr>
            <tr>
                <th><label>Gender:</label></th>
                <td><?php echo $userInfo['gender']; ?></td>
            </tr>
            <tr>
                <th><label>Email:</label></th>
                <td><?php echo $userInfo['email']; ?></td>
            </tr>
            <?php
            if($_SESSION['userType'] == 3)
            {
            ?>
            <tr>
                <th><label>Date of Birth:</label></th>
                <td><?php echo date('d-m-Y', strtotime($userInfo['DOB'])); ?></td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <th><label>Telephone Number:</label></th>
                <td><?php echo $userInfo['contactNo']; ?></td>
            </tr>
        </table>
        </div>
        <a href="userprofile.php?m=1" title="Modify Your Details" class="button assistance">Modify Details</a>
        <br>
        <a href="userprofile.php?m=2" title="Change Your Email" class="button assistance">Change Email</a>
    <?php
            }
            if($check == 1)
            {
    ?>
        <h1>Edit Your Details:</h1>
        <div id="userProfile">
        <form id="editProfileForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return profile(this);">
        <fieldset><legend>Profile Modification Form</legend>
        <table id="profileTable">
            <tr>
                <th>Select your title:</th>
                <td>
                    <select name="title" id="titlemenu">
                        <option value="Mr.">Mr.</option>
                        <option value="Mrs."<?php if(strcmp($userInfo['title'], 'Mrs.') == 0){echo ' selected';} ?>>Mrs.</option>
                        <option value="Ms."<?php if(strcmp($userInfo['title'], 'Ms.') == 0){echo ' selected';} ?>>Ms.</option>
                        <option value="Dr."<?php if(strcmp($userInfo['title'], 'Dr.') == 0){echo ' selected';} ?>>Dr.</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>First Name:</label></th>
                <td><input type="text" id="first_name" name="first_name" onblur="checkForContent(this, document.getElementById('first_name_help'))" value="<?php if(!empty($row['firstName'])) echo $row['firstName']; // Display the user's first name ?>" /></td>
            </tr>
            <tr>
                <th><label>Last Name:</label></th>
                <td><input type="text" id="last_name" name="last_name" onblur="checkForContent(this, document.getElementById('last_name_help'))" value="<?php if(!empty($row['lastName'])) echo $row['lastName']; // Display the user's first name ?>" /></td>
            </tr>
            <?php
            if($_SESSION['userType'] == 3)
            {
            ?>
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
                            if($day == 1)
                            {
                                $day = sprintf("%02s", $day);
                                echo '<option value="' . $day . '"';
                                if($day == $dOBDay)
                                {
                                    echo ' selected';
                                }
                                echo '>' . $day . '</option>' . "\n";
                            }
                            else
                            {
                                if($day < 10)
                                {
                                    $day = sprintf("%02s", $day);
                                }
                                echo '                      <option value="' . $day . '"';
                                if($day == $dOBDay)
                                {
                                    echo ' selected';
                                }
                                echo '>' . $day . '</option>' . "\n";
                            }
                            $day++;
                        }
                      ?>
                    </select>
                    <select name="date_of_birth_month">
                      <option value=""> - Month - </option>
                      <option value="01"<?php if($dOBMonth == 01){echo ' selected';} ?>>January</option>
                      <option value="02"<?php if($dOBMonth == 02){echo ' selected';} ?>>Febuary</option>
                      <option value="03"<?php if($dOBMonth == 03){echo ' selected';} ?>>March</option>
                      <option value="04"<?php if($dOBMonth == 04){echo ' selected';} ?>>April</option>
                      <option value="05"<?php if($dOBMonth == 05){echo ' selected';} ?>>May</option>
                      <option value="06"<?php if($dOBMonth == 06){echo ' selected';} ?>>June</option>
                      <option value="07"<?php if($dOBMonth == 07){echo ' selected';} ?>>July</option>
                      <option value="08"<?php if($dOBMonth == 08){echo ' selected';} ?>>August</option>
                      <option value="09"<?php if($dOBMonth == 09){echo ' selected';} ?>>September</option>
                      <option value="10"<?php if($dOBMonth == 10){echo ' selected';} ?>>October</option>
                      <option value="11"<?php if($dOBMonth == 11){echo ' selected';} ?>>November</option>
                      <option value="12"<?php if($dOBMonth == 12){echo ' selected';} ?>>December</option>
                    </select>
                    <select name="date_of_birth_year">
                      <option value=""> - Year - </option>
                      <?php
                        $year = 1996;
                        while($year > 1914)
                        {
                            // Decrement year after each option is displayed
                            if($year == 1996)
                            {
                                echo '<option value="' . $year . '"';
                                if($year == $dOBYear)
                                {
                                    echo ' selected';
                                }
                                echo '>' . $year . '</option>' . "\n";
                                
                            }
                            else
                            {
                                echo '                      <option value="' . $year . '"';
                                if($year == $dOBYear)
                                {
                                    echo ' selected';
                                }
                                echo '>' . $year . '</option>' . "\n";
                            }
                            $year--;
                        }
                      ?>
                    </select>
                </td>
            </tr>
            <?php
            }
            ?>
            <tr>
                <th><label>Telephone Number:</label></th>
                <td>
                    <input type="text" id="tel_num" name="tel_num" value="<?php if(!empty($userInfo['contactNo'])) echo $userInfo['contactNo']; // Display the user's first name ?>" onblur="checkForContent(this, document.getElementById('tel_num_help'))"/>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td><input type="submit" value="Update" name="updateProfile" class="button formButton" /></td>
                <td><input type="reset" value="Reset Form" class="button formButton" /></td>
            </tr>
        </table>
        </fieldset>
        </form>
        </div>
        <p>Notes:</p>
        <p>Note the following points regarding your profile information:</p>
        <ul>
            <li>The minimum password length is 4 characters. Example: "1234".</li>
            <li>Telephone number should be in the format "555 5555" or "555-5555".</li>
            <li>Valid emails are required. Emails should look like this: "my_name@my_email_host.com".</li>
        </ul>
    <?php
            }
            if($check == 2)
            {
                // Confirm the successful update
                echo '<h2>Success</h2>' . "\n";
                echo '        <p>Your profile was successfully updated.</p>' ."\n";
                require_once('includes/homeurl.php');
                $homeURL .= 'userprofile.php';
                echo '        <a href="'. $homeURL . '" title="Return to Your Profile Page" class="button return">Return to Your Profile Page</a>' . "\n";
            }
            if($check == 3)
            {
     ?>
        <h1>Change Your Email:</h1>
        <div id="userProfile">
        <form id="editProfileForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return emailUpdate(this);">
        <fieldset><legend>Change Email Form</legend>
        <table id="profileTable">
            <tr>
                <th><label>Email:</label></th>
                <td><input type="text" id="email" name="email" value="" onblur="checkForContent(this, document.getElementById('email_help'))" /></td>
            </tr>
        </table>
        <table>
            <tr>
                <td><input type="submit" value="Update" name="changeEmail" class="button formButton" /></td>
                <td><input type="reset" value="Reset Form" class="button formButton" /></td>
            </tr>
        </table>
        </fieldset>
        </form>
        </div>
    <?php
        }
        if($check == 4)
        {
            // Confirm the successful update
            echo '<h2>Success</h2>' . "\n";
            echo '        <p>Your email was successfully updated.</p>' ."\n";
            echo '        <p>In 3 seconds you\'ll be logged out so you can login with your new email.</p>' . "\n";
            require_once('includes/homeurl.php');
            $homeURL .= 'logout.php?r=1';
    ?>
            <meta http-equiv="refresh" content="3;url=<?php echo $homeURL ?>">
    <?php
        }
    ?>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>