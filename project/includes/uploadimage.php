<?php
	$allowedExtensions = array("gif", "jpeg", "jpg", "png");
    $getExtension = explode(".", $_FILES["image"]["name"]);
    $extension = end($getExtension);
    $fileType = $_FILES["image"]["type"];
    if((strcmp($fileType, "image/gif") == 0) || (strcmp($fileType, "image/jpeg") == 0) || (strcmp($fileType, "image/jpg") == 0) ||
    (strcmp($fileType, "image/pjpeg") == 0) || (strcmp($fileType, "image/gif") == 0) || (strcmp($fileType, "image/png") == 0)
    && ($_FILES["image"]["size"] < 204800) && in_array($extension, $allowedExtensions))
    {
        if($_FILES["image"]["error"] > 0)
        {
            echo "Return Code: " . $_FILES["image"]["error"] . "<br>";
        }
        else
        { 
            if(file_exists("images/" . $_FILES["image"]["name"]))
            {
                $errorMsg = 'The image name already exists. Please try again or contact support if the problem persists.';
                $imageNameError = true;
            }
            else
            {
                move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $_FILES["image"]["name"]);
            }
        }
    }
    else
    {
        $errorMsg = 'There was an error with your image upload attempt. Please try again or contact support if the problem persists.';
    }
?>