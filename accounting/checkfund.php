<?php  
	session_start();
    include '../db_connection/db_conn.php';
    $user = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$user' ";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'accounting') {
        echo "<script>window.open('../index.php', '_self');</script>";
    }else{
    	
        date_default_timezone_set('Asia/Manila');
        $currentTime = date('Y-m-d h:i:s');

    	$id = $_GET['id'];
    	$sql = "UPDATE employee_training_needs SET is_fund_available = '1', remark = 'checked',final_remark = 'approved', date_checked = '$currentTime' WHERE id = '$id'";

    	if ($conn->query($sql) === TRUE) {
    		echo "<script>alert('Fund successfully checked!');</script>";
    		echo "<script>window.open('fundRequest.php', '_self');</script>";
    	}
    }
?>