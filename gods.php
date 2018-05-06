<!DOCTYPE html>
<html>
<head>
	<title>Gods</title>
</head>
<body>
	<form method="POST" action="">
		<input type="text" name="user">
		<input type="password" name="psswd">
		<input type="submit" name="go">
	</form>
</body>
</html>
<?php  
	include 'db_connection/db_conn.php';

	if (isset($_POST['go'])) {
		$user = $_POST['user'];
		$psswd = sha1(md5($_POST['psswd']));

		$sql = "INSERT INTO users(username, password) VALUES ('$user', '$psswd');";

		$sql .= "INSERT INTO employees(first_name, middle_name, last_name, name_extension, sex, age, birth_date, birth_place, barangay, address, mobile_no) VALUES ('$user', '$user', '$user', '$user', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A');";

		$sql .= "INSERT INTO employee_designations(designated_as, department) VALUES('N/A', 'N/A')";

		if ($conn->multi_query($sql)or die('haha')) {
            echo '<script>alert("Account was sent for approval");</script>';
            }else{
                    echo "Error: " . $sql . "<br>" . $conn->error;
        } 
	}
?>