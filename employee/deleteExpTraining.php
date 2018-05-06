<?php 
	session_start(); 
	include '../db_connection/db_conn.php';
    $user = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$user' ";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'employee')  {
		echo "<script>window.open('../index.php', '_self');</script>";
	}else{
		$id = addslashes($_GET['id']);
		$sql = "DELETE FROM expected_trainings WHERE id = '$id'";

		if ($conn->query($sql) === TRUE) {
			echo "<script>alert('Deleted!');</script>";
			echo "<script>window.open('expectedTrainings.php', '_self');</script>";
		}else{
			echo "<script>alert('Something went wrong!');</script>";
			echo "<script>window.open('expectedTrainings.php', '_self');</script>";
		}
	}
?>