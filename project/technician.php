<?php	
	// Acquire company name
	require_once('includes/name.php');
	
	// Acquire the connection data for the database
	require_once('includes/common.php');
	
	// Start the session
	session_start();
	
	// If the user is logged in, redirect them to the homepage (users who are logged-in have no reason to access the technician page)
	if(!isset($_SESSION['email']) || !isset($_COOKIE['email']) || $_SESSION['userType'] != 2)
	{
		require_once('includes/homeurl.php');
		$homeURL .= 'index.php';
		die(header('Location: ' . $homeURL));
	}
    
    // Staff page variable used to request search engines to not index the page
    $staff = true;
    
	// Variables to track if an operation was successful
	$selection = 0;
	$check = 0;
	$check2 = 0;
    // List of options for the status of a repair project
    $options = array("Incomplete (Diagnosing)", "Incomplete (Repairing)", "Complete", "Archived");

    // A request was made to access a form to alter the state of projects in the database
	if(isset($_POST['selection']))
	{
		switch($_POST['selection'])
		{
			// Manager chose to add a project
			case 'Add Project':
				$selection = 1;
				break;
			// Manager chose to edit a project
			case 'View Project':
				$selection = 2;
				$check2 = 1;
				break;
			default:
				// No selection made because the page was accessed before a selection was made, so do nothing
		}
	}
	// A request was made to add a product to the database
	if(isset($_POST['addProject']))
	{
		$memberEmail = $_POST['memberEmail'];
		$details = $_POST['details'];
        $eDC = $_POST['eDC'];
        // Use current date
        $dS = date('Y-m-d');
		if(!(empty($memberEmail) || empty($details) || empty($dS) || empty($eDC)))
		{
		    $query = "SELECT memberID FROM member_tbl WHERE email = '$memberEmail'";
            $result = mysqli_query($dbc, $query);
            // If the member's email was found, attach them to the project
            if(mysqli_num_rows($result) == 1)
            {
                $row = mysqli_fetch_array($result);
                $memberID = $row['memberID'];
                $technicianID = $_SESSION['userID'];
                $query = "INSERT INTO project_tbl (memberID, technicianID, details, eDC, dateStarted) VALUES ('$memberID', '$technicianID', '$details', '$eDC', '$dS')";
                $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with adding the project to the database. Please try again or contact support if the problem persists.';
                
            }
            // Otherwise, the email was not matched to a record in the members table
            else
            {
                $query = "INSERT INTO project_tbl (memberID, details, dateStarted) VALUES ('0', '$details', '$dS')";
                $result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with adding the project to the database. Please try again or contact support if the problem persists.';
            }
            $check = 1;
            // "Housekeeping"
            mysqli_close($dbc);
			
		}
		else
		{
			$errorMsg = 'One or more of the fields were detected as empty. Please try again or contact support if the problem persists.';
		}
	}
	// A request was made to view the projects in the database to modify one
    if(isset($_POST['editSelection']))
    {
                $check2 = 1;
                $selection = 2;
    }
    // A request was made to modify a project in the database
	if(isset($_POST['editProject']))
	{
	    $projectID = $_POST['projectID'];
		$cost = $_POST['cost'];
		$details = $_POST['details'];
		$status = $_POST['status'];
        $eDC = $_POST['eDC'];
		$dC = $_POST['dC'];
		if(!(empty($projectID) || empty($details) || empty($status)))
		{
			$query = "UPDATE project_tbl SET cost = '$cost', details = '$details', status = '$status', eDC = '$eDC', dateCompleted = '$dC' WHERE projectID = '$projectID'";
			$result = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with modifying the selected project. Please try again or contact support if the problem persists.';
			$check = 2;
			// "Housekeeping"
			mysqli_close($dbc);
		}
		else
		{
			$errorMsg = 'One or more of the fields were detected as empty. Please try again or contact support if the problem persists.';
		}
	}
	
	// Add the page header
	$pageTitle = $aCName . " Staff - Technician Centre";
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "Technician Centre");
    
    // Add the JavaScript validation script
    $staffvalidation = true;
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
    	<h1>Technician Centre</h1>
    	<?php
    	// If an error was encountered, echo the error markup, otherwise there was no error and no operation had just been successfully executed, so skip echoing the markup
        if(isset($errorMsg))
        {
            echo '<p class="errorMsg">' . $errorMsg . '</p>' . "\n";
        }
        // If no process was marked as successful, display the default page
		if($check == 0)
		{
		?>
        <div id="selectionFormFrame">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <fieldset><legend>Select Operation</legend>
            <table id="selectionTable">
                <tr>
                    <td><input type="submit" value="Add Project" name="selection" class="button  mButton" /></td>
                    <td><input type="submit" value="View Project" name="selection" class="button  mButton" /></td>
                </tr>
            </table>
            </fieldset>
            </form>
        </div>
        <?php
		}
		// If the manager wants to add a product, then display the form
		if(($selection == 1) && !isset($_POST['submit']))
		{
		  	//echo '<p class="error">' . $errorMsg . '</p>';
		 ?>
        <div id="addProjectFormFrame">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return projectAdd(this);">
            <fieldset><legend>Add Project</legend>
            <table id="addProjectTable">
            	<?php // If the user typed in a bad value a field, it will retain the value they entered to allow them to edit it. ?>
                <tr>
                    <th><label>Member Email:</label></th>
                    <td><input type="text" id="memberEmail" name="memberEmail" value="<?php if(!empty($memberEmail)) echo $memberEmail; ?>" /></td>
                </tr>
                <tr>
                    <th><label>Details:</label></th>
                    <td><textarea id="details" name="details" rows="5" cols="40"><?php if(!empty($details)) echo $details; ?></textarea></td>
                </tr>
                <tr>
                    <th><label>Estimated Date of Completion:</label></th>
                    <td><input type="text" id="eDC" name="eDC" value="<?php if(!empty($eDC)) echo $eDC; ?>" /></td>
                </tr>
            </table>
            <table>
            <tr>
                <td><input type="submit" value="Add Project" name="addProject" class="button  mButton" /></td>
                <td><input type="reset" value="Reset" class="button  mButton" /></td>
            </tr>
            </table>
            </fieldset>
            </form>
        </div>
        <?php
        }
        if($check == 1)
		{
          // Confirm the successful addition
		  echo '<h2>Project Added Successfully</h2>';
		  require_once('includes/homeurl.php');
		  $homeURL .= 'technician.php';
		  echo '<a href="'. $homeURL . '" title="Return to Technician Centre" class="button">Return to Technician Centre</a>' . "\n";
        }
		// If the manager wants to edit a product, then display the form
		if(($check2 == 1) && ($selection == 2) && !isset($_POST['submit']))
		{
			?>
		<div id="editProjectSelectionFormFrame">
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<fieldset><legend>Select Project to View</legend>
			<table id="editProjectSelectionTable">
				<tr>
				<th><label>Project ID:</label></th>
				<td id="projectSelection">
				<!-- Select drop down with default option -->
					<select name="projectSelection">
					  <?php
						$query = "SELECT projectID, memberID, status, dateStarted FROM project_tbl";
						$results = mysqli_query($dbc, $query);
						while($row = mysqli_fetch_array($results))
						{
						    // Projects were found, so list them in the drop down menu
						    $memberID = $row['memberID'];
						    $getNames = "SELECT firstName, lastName FROM member_tbl WHERE memberID = '$memberID'";
                            $getNamesResults = mysqli_query($dbc, $getNames);
                            $getNamesData = mysqli_fetch_array($getNamesResults);
						    echo '<option value="' . $row['projectID'] . '">' . $row['projectID'] . " - " . $getNamesData['firstName'] . " " . $getNamesData['lastName'] . " - " . $row['dateStarted'] . " - " . $row['status'] . '</option>';
						}
                        // "Housekeeping"
						mysqli_close($dbc);
					  ?>
					</select>
                    <input type="hidden" name="editSelection" value="1"> 
				</td>
			</tr>
			</table>
			<table>
			<tr>
                <td><input type="submit" value="Select Project" name="editSelection" class="button  mButton" /></td>
                <td><input type="reset" value="Reset" class="button  mButton" /></td>
            </tr>
            </table>
			</fieldset>
			</form>
		</div>
            <?php
			}
			if(isset($_POST['editSelection']))
			{
				$projectID = $_POST['projectSelection'];
				$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL server.');
				$query = "SELECT * FROM project_tbl WHERE projectID = '$projectID'";
				$result = mysqli_query($dbc, $query);
				if(mysqli_num_rows($result) == 1)
				{
					$row = mysqli_fetch_array($result);
					$projectID = $row['projectID'];
					$memberID = $row['memberID'];
					$cost = $row['cost'];
					$details = $row['details'];
					$status = $row['status'];
                    $eDC = $row['eDC'];
					$dS = $row['dateStarted'];
					$dC = $row['dateCompleted'];
				}
                // If the database returned its default value for an empty date field, display an empty string to the user instead
				if($dC == "0000-00-00")
				{   
					$dC = "";
				}
			  ?>
		<div id="editProjectFormFrame">
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return projectEdit(this);">
            <fieldset><legend>Edit Project</legend>
            <?php // If the user typed in a bad value a field, it will retain the value they entered to allow them to edit it. ?>
            <input type="hidden" name="projectID" value="<?php if(!empty($projectID)) echo $projectID; ?>">
            <table id="editProjectTable">
                <tr>
                    <th><label>Cost:</label></th>
                    <td><input type="text" id="cost" name="cost" value="<?php if(!empty($cost)) echo $cost; ?>" /></td>
                </tr>
                <tr>
                    <th><label>Details:</label></th>
                    <td><textarea id="details" name="details" rows="5" cols="40"><?php if(!empty($details)) echo $details; ?></textarea></td>
                </tr>
                <tr>
				<th><label>Status:</label></th>
				<td>
				<!-- Select drop down with default option -->
					<select name="status">
					  <?php
						for($x = 0;$x < 4; $x++)
						{
						  // Use the list of options for the status in the drop down menu
						  echo '<option value="' . $options[$x] . '"'; if(strcmp($options[$x], $status) == 0) echo ' selected'; echo '>' . $options[$x] . '</option>';
						}
                        // "Housekeeping"
						mysqli_close($dbc);
					  ?>
					</select> 
				</td>
				</tr>
				<tr>
                    <th><label>Estimated Date of Completion:</label></th>
                    <td><input type="text" id="eDC" name="eDC" value="<?php if(!empty($eDC)) echo $eDC; ?>" /></td>
                </tr>
                <tr>
                    <th><label>Date Completed:</label></th>
                    <td><input type="text" id="dateCompleted" name="dC" value="<?php if(!empty($dC)) echo $dC; else echo ''; ?>" /></td>
                </tr>
            </table>
            <table>
            <tr>
                <td><input type="submit" value="Update Project" name="editProject" class="button  mButton" /></td>
                <td><input type="reset" value="Reset" class="button  mButton" /></td>
            </tr>
            </table>
            </fieldset>
        	</form>
		</div>
			<?php
			}
			if($check == 2)
			{
			  // Confirm the successful edit
			  echo '<h2>Project Edited Successfully</h2>';
			  require_once('includes/homeurl.php');
			  $homeURL .= 'technician.php';
			  echo '<a href="'. $homeURL . '" title="Return to Technician Centre" class="button">Return to Technician Centre</a>' . "\n";
			}
        	?>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>