<?php 

include('dbconn.php');
	
$dbc = connect_to_db('gonzalyz');

$query = "SELECT class_cats.title, reqs.number FROM `class_cats`, `reqs` WHERE reqs.class_cat = class_cats.id and reqs.field=1;";	

$result = perform_query($dbc, $query);
    
$core_data = array();	// put the rows as objects in an array

while ( $obj = mysqli_fetch_object( $result ) ) {
		$core_data[] = $obj;
	}
	//echo json_encode($core_data);

$id = $_COOKIE['loginUserID'];


$query2 = "SELECT slots.semester, reqs.number FROM `slots`, `reqs` WHERE plan.student =$id";

$result2 = perform_query($dbc, $query);

$sem_data = array();	// put the rows as objects in an array

while ( $sem = mysqli_fetch_object( $result2 ) ) {
		$sem_data[] = $sem;
	}
	echo json_encode($sem_data);



	mysqli_close($dbc);


    
    
    
    //when go to show table like if number = 1 or 2 etc
    
    //6,7,81,82 are the id's for the fields for bio stuff