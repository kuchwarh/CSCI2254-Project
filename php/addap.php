<?php include("dbconn.php");

	$id = $_COOKIE['loginUserID'];
	$req = $_POST['aps'];

	$dbc = connect_to_db('gonzalyz');
	$insertap = "insert into aps (student, req) values ('$id', '$req')";
	perform_query($dbc, $insertap);
	
	header("Location: classplanner.php");