<?php 
	include 'db_connection/db_conn.php';
  	session_start();
  	$sql = "SELECT * FROM users";
  	$result = $conn->query($sql);
  	$checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'admin' ) {
    	echo "<script>window.open('index.php', '_self');</script>";
    }else{

    	$filename = $_GET['id'];

		    header('Content-Type: application/force-download');
		    header('Content-Disposition: attachment; filename="'.basename($filename).'"');
		    header('Content-Length: ' . filesize($filename));
		    readfile($filename);
    }
?>