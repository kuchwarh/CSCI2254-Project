<?php include("dbconn.php");


if (!isset($_POST['username']) or !isset($_POST['password'])
	or (0 == checklogin($_POST['username'], $_POST['password']))) {
		echo "<script>alert('Incorrect username or password.');
		window.location.href='../adminlogin.html';
		</script>";
		
} else {
		$captcha;
        if(isset($_POST['g-recaptcha-response'])){
        	$captcha=$_POST['g-recaptcha-response'];
        };
        if(!$captcha){
        	echo "<script> alert('Keep account safe: Verify you are a human before login.');
			window.location.href='../adminlogin.html';</script>";
        };
        $secretKey = "6Lf-AR8TAAAAAMVFeMJkqlpeN4jDXsgmF1BLsiAU";
        $ip = $_SERVER['REMOTE_ADDR'];
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
        $responseKeys = json_decode($response,true);
        if(intval($responseKeys["success"]) !== 1) {
     		echo "<script> alert('Spammer detected.');
			window.location.href='../adminlogin.html';</script>";
        } else {
			setcookie('adminUserID', getID($_POST['username']), time() + 60*60*24*30, '/');
			header("Location: ../adminhome.html");
		};
};

function checklogin($name, $password) {
	$dbc = connect_to_db('gonzalyz');
	$encodepw = sha1($password);
	$result = perform_query($dbc, "select * from admins where username = '$name' and password = '$encodepw'");
	$matches = mysqli_num_rows($result);
	disconnect_from_db($dbc, $result);
	return ($matches == 1);
};

function getID($name) {
	$dbc = connect_to_db('gonzalyz');
	$result = perform_query($dbc, "select id from admins where username = '$name'");
	$row = mysqli_fetch_array($result);
	disconnect_from_db($dbc, $result);
	return $row['id'];
};