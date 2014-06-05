<?php
  session_start();
  // If the session data is empty, the user attempted to log out without being logged in
  if(empty($_SESSION['userID']))
  {
      // If the user is not logged in redirect the user to the home page
      header('Location: transfer.php?s=3');
      // Kill the script to prevent the header from being changed
      die();
  }
  if(isset($_SESSION['userID']))
  {	  
      // If the user is logged in, delete the session vars to log them out
	  if(isset($_SESSION['userID']))
	  {
		// Delete the session vars by clearing the $_SESSION array
		$_SESSION = array();
	
		// Delete the session cookie by setting its expiration to an hour ago (3600 seconds)
		if(isset($_COOKIE[session_name()]))
		{
		  	setcookie(session_name(), '', time() - 3600);
		}
	
		// Destroy the session
		session_destroy();
	  }
	
	  // Delete the user ID and username cookies by setting their expirations to an hour ago (3600 seconds)
	  setcookie('userID', '', time() - 3600);
	  setcookie('email', '', time() - 3600);
	  setcookie('userType', '', time() - 3600);
  }
  if(empty($_SESSION['userID']) && $_GET['r'] == 1)
  {
      // If the user is not logged in redirect the user to the home page
      header('Location: transfer.php?s=4');
      // Kill the script to prevent the header from being changed
      die();
  }
  // Redirect the user to the transfer page for the confirmation message
  header('Location: transfer.php?s=2');
?>