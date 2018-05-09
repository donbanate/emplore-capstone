<?php 
		$serverName = "localhost";
		$user = "capstone001";
		$dbName = "employees";
		$password = "CtDoDYe";

		$conn = new mysqli($serverName, $user, $password, $dbName);

		if ($conn->connect_error) {
			die("Failed to connect to database! " . $conn->connect_error);
			$conn->close();
		}
 ?>
