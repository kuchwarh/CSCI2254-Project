<?php include("dbconn.php");

	$id = $_COOKIE['loginUserID'];
	if (isset($_POST['major1'])) {
		$major1 = $_POST['major1'];
	} else {
		$major1 = 0;};
	if (isset($_POST['major2'])) {
		$major2 = $_POST['major2'];
	} else {
		$major2 = 0;};
	if (isset($_POST['minor1'])) {
		$minor1 = $_POST['minor1'];
	} else {
		$minor1 = 0;};
	if (isset($_POST['minor2'])) {
		$minor2 = $_POST['minor2'];
	} else {
		$minor2 = 0;};
	
	$dbc = connect_to_db('gonzalyz');
	$notcurrent = "select enroll.field from enroll, fields_of_study 
				   where enroll.field not in ('$major1', '$major2', '$minor1', '$minor2') 
				   and student = '$id' and type in ('major', 'minor') and field = fields_of_study.id";
	
	$result = perform_query($dbc, $notcurrent);
	$fields = array();
	while ($obj = mysqli_fetch_object($result)) {
		$fields[] = $obj;
	};
	foreach ($fields as $field) {
		$setfalse = "update enroll set current = false where field = '$field->field' and student = '$id'";
		perform_query($dbc, $setfalse);
	};
	
	$needinsert = "select id from fields_of_study 
				   where id in ('$major1', '$major2', '$minor1', '$minor2') 
				   and id not in (select field from enroll 
				   				  where student = '$id' and current = true)";
	$result2 = perform_query($dbc, $needinsert);
	$infields = array();
	while ($obj = mysqli_fetch_object($result2)) {
		$infields[] = $obj;
	};
	foreach ($infields as $field) {
		$insert = "insert into enroll (field, student, current) values ('$field->id', '$id', true)";
		perform_query($dbc, $insert);
	};
	
	header("Location: ../fieldupdate.html");