<?php
	session_start();
	include '../db_connection/db_conn.php';
	$user = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$user' ";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'admin') {
	  	echo "<script>window.open('../index.php', '_self')</script>";
	}else{
		include '../bootstrapIncludes.php';

	if (isset($_POST['approve'])) {
	  	$id = $_POST['id'];
	  	$designation = $_POST['designation'];

	  	$approve = "UPDATE employee_designations SET designated_as ='$designation' WHERE id='$id'; ";
	  	$approve .= "UPDATE users SET type ='employee' WHERE id='$id' ";

	  	if ($conn->multi_query($approve) === TRUE) {
	  		/*echo '<script>window.open("approve_account.php?id='. $id .'", "_self");</script>';*/
	  		echo "<script>alert('Account Approved!');</script>";
	  		echo "<script>window.open('acc_requests.php', '_self');</script>";
	  	}else{
	  		echo "Error: " . $approve . "<br>" . $conn->error;
	  	}

	  }else{  
?>
<?php 
		$id = $_GET['id'];
		$query = $conn -> query("SELECT * FROM employees WHERE id='$id'") or die('MADI MET');
		$row = $query -> fetch_array();
		$num = $query -> num_rows;
		if ($num > 0) {
?>
<!DOCTYPE html>
<html>
<head>
	<title>Approve Account</title>
	<link rel="icon" type="image/png" href="../logo/logo.png">
	<link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

	<div class="panel-info" style="margin-left: 30%; margin-right: 30%; margin-top: 2%;">
		<h3 class="alert alert-info">Approve Account</h3>
		<div class="table-responsive">
		<form method="POST" action="approve_account.php">
			<table class="table table-striped table-hover">
			<tr>
				<th>Name</th>
				<td><?=$row['first_name'] .' '. $row['middle_name'] .' '. $row['last_name'] .' '. $row['name_extension']?></td>
			</tr>
			<tr>
				<th>Designation</th>
				<td>
					<select class="form-control" name="designation">
						<option>--</option>
						<option>Faculty</option>
						<option>Associate Dean</option>
						<option>Research Extension</option>
						<option>Research Coordinator</option>
					</select>
				</td>
				
			</tr>
		</table>
				<center><input class="form-control" type="hidden" name="id" value="<?=$id?>">
				<button class="btn btn-success" type="submit" name="approve"><span class="glyphicon glyphicon-ok-circle"></span> Assign Designation</button></center>
		</form>
		<a class="btn btn-xs" href="acc_requests.php"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
		<?php  
		}
		?>
	</div>
	</div>
</body>
</html>
<?php
	}
}  
?>