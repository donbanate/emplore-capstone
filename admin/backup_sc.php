<?php 
	include '../db_connection/db_conn.php';
  	session_start();
  	$user = $_SESSION['id'];
  	$sql = "SELECT * FROM users WHERE id = '$user' ";
  	$result = $conn->query($sql);
  	$checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'admin' ) {
    	echo "<script>window.open('../index.php', '_self');</script>";
    }else{	
    	$id = $_GET['id'];

		$content_type = "application/force-download";
		header("Content-Type: " . $content_type);
		header("Content-Disposition: attachment; filename=\"" . basename($id) . "\";");
		readfile($id);
    }
?>

