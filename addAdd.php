<?php
print_r($_POST);
	if($_POST['addName'] == "" || $_POST['addDate'] == "" || $_POST['addTime'] == "")
	{
	$_SESSION['message'] = "You can't leave empty fields";
    header("location: profile.php");
	}
?>