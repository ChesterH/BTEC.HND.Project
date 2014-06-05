<?php
  session_start();

  // If the session variabless aren't set, try to set them with a cookie
  if (!isset($_SESSION['memberID']))
  {
    if (isset($_COOKIE['memberID']) && isset($_COOKIE['email']))
	{
      $_SESSION['memberID'] = $_COOKIE['memberID'];
      $_SESSION['email'] = $_COOKIE['email'];
    }
  }
?>