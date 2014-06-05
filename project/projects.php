<?php	
	// Acquire company name
	require_once('includes/name.php');
	
	// Acquire the connection data for the database
	require_once('includes/common.php');
	
	// Start the session
	session_start();
	
	// If the user is not logged in, redirect them to the homepage (users who are not logged-in have no reason to access the projects page)
	if(!isset($_SESSION['email']) || !isset($_COOKIE['email']) || $_SESSION['userType'] != 3)
	{
		require_once('includes/homeurl.php');
		$homeURL .= 'index.php';
		die(header('Location: ' . $homeURL));
	}
    else
    {
        // Save the user's memberID to access their projects
        $memberID = $_SESSION['userID'];
        
        // First check to see if the user has any active repair projects
        $query = "SELECT status FROM project_tbl WHERE memberID = '$memberID'";
        $results = mysqli_query($dbc, $query);
        $errorMsg = '';
        $archived = 0;
        $numProjects = 0;
        while($row = mysqli_fetch_array($results))
        {
            // Count the number of projects attributed to the user, then count those which are archived
            $numProjects++;
            if(strcmp($row['status'],'Archived') == 0)
            {
                $archived++;
            }
        }
        if($numProjects == 0)
        {
            $archived = 9999;
        }
        // If there are any repair projects attributed to the user, and if there is at least one open project, then display the list
        if(($numProjects > 0) && ($numProjects != $archived))
        {
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or $errorMsg = 'Error connecting to MySQL server.';
            $query = "SELECT * FROM project_tbl WHERE memberID = '$memberID'";
            $results = mysqli_query($dbc, $query) or $errorMsg = 'There was an error with acquiring the project data from the database. Please try again or contact support if the problem persists.';
        }
    }
	
	// Add the page header
	$pageTitle = $aCName . " - Repair Projects";
	$page = 1;
    
    // Add the breadcrumbs
    $breadcrumbs = array("index.php", $aCName . " Home", $aCName . " Home", "usercentre.php", "The User Centre", "User Centre", "Repair Projects");
    
	require_once('includes/head.php');
?>
<body>
    <?php require_once('includes/header.php'); ?>
    <div id="mainContent">
    	<?php
		// If an error was encountered and logged, display it, otherwise don't add the markup
        if(!empty($errorMsg))
        {
            echo '<p class="errorMsg">' . $errorMsg . '</p>' . "\n";
        }
        if(($numProjects > 0) && ($numProjects != $archived))
        {
            ?><h1>Repair Project</h1>
            <p>The projects you currently have open with <?php echo $aCName; ?> are as follows:</p>
            <table id="viewProjectTable">
                <tr><th>Project Details</th><th>Project Status</th><th>Project Cost</th><th>EDC</th><th>Date Started</th><th>Date Completed</th></tr>
                <?php
                while($row = mysqli_fetch_array($results))
                {
                    $status = $row['status'];
                    if(strcmp($status, 'Archived') != 0)
                    {
                        $projectID = $row['projectID'];
                        $details = $row['details'];
                        $cost = $row['cost'];
                        $eDC = $row['eDC'];
                        $dS = $row['dateStarted'];
                        $dC = $row['dateCompleted'];
                        // If the database returned its default value for an empty date field, inform them the date is to be determined instead
                        if($dC == "0000-00-00")
                        {
                            $dC = "TBD";
                        }
                echo '    <tr>' . "\n";
                ?>
                    <td><?php if(!empty($details)) echo $details; ?></td>
                    <td><?php if(!empty($status)) echo $status; ?></td>
                    <td><?php if(!empty($cost)) echo '$' . $cost; ?></td>
                    <td><?php if(!empty($dS)) echo $eDC;  ?></td>
                    <td><?php if(!empty($dS)) echo $dS;  ?></td>
                    <td><?php if(!empty($dC)) echo $dC; else echo ''; ?></td>
                </tr>
            <?php
                    }
                }  
            echo '</table>' . "\n";
            // "Housekeeping"
            mysqli_close($dbc);
            echo '            <p><b>Key</b>:</p>' . "\n";
            echo '            <p>EDC: Estimated Date of Completion (The time at which the technician expects to complete your project).</p>' . "\n";
            echo '            <p>Complete: The computer/device submitted has been repaired.</p>' . "\n";
            echo '            <p>Incomplete: The computer/device submitted is still under repair. The word in brackets indicates the specific stage at which the technician has reached in the process of repairing your device.</p>' . "\n";
            echo '            <p>Note: All dates are in the format yyyy-mm-dd.</p>' . "\n";
		}
		else
		{
			echo '<p>You have no open repair projects with ' . $aCName . '.</p>';
		}
    	?>
    </div>
    <?php require_once('includes/footer.php'); ?>
</body>
</html>