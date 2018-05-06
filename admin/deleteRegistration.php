<?php
	session_start();
	include('../db_connection/db_conn.php');
	$user = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$user' ";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'admin'){
    	echo "<script>window.open('../index.php', '_self');</script>";
    }else{
	    	if (isset($_GET['id'])) {
			$del = $_GET['id'];

			$delete = "DELETE FROM employees WHERE id='$del'";
			$delete1 = "DELETE FROM users WHERE id='$del'";
			$delete2 = "DELETE FROM employee_designations WHERE id ='$del' ";

			if ($conn->query($delete) === TRUE && $conn->query($delete1) === TRUE && $conn->query($delete2) === TRUE)  {
				echo "<script>alert('Registration Removed!');</script>";
				echo "<script>window.open('acc_requests.php','_self');</script>";
			}else{
				echo "Error: " . $delete . "<br>" . $conn->error;
			}
		}
    }
?>