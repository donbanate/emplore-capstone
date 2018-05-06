<?php 
		$serverName = "localhost";
		$user = "emplore";
		$dbName = "emplore";
		$password = "CTDo9IryDWZqYDYe";

		$conn = new mysqli($serverName, $user, $password, $dbName);

		if ($conn->connect_error) {
			die("Failed to connect to database! " . $conn->connect_error);
			$conn->close();
		}
 ?>