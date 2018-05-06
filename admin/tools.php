<?php  
	session_start();
	include '../db_connection/db_conn.php';
	$user = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$user' ";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'admin') {
		echo "<script>window.open('../index.php', '_self');</script>";
	}else{
?>
<!DOCTYPE html>
<html>
<head>
	<title>System Backups</title>
	<link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>
	<div class="card-block col-6 offset-3">
		<div class="card-header bg-info text-white"><h1><strong>System Tools <i class="fa fa-archive"></i></strong></h1></div>
		<div class="card-body card-footer">
			<div class="">
			<div class="row">
				<div class="col-6 offset-3">
		
					<center>
						<?php $bup = "code_backup/source_code.zip"; ?>
					<a class="btn btn-outline-info btn-lg" href="backup_sc.php?id=<?php echo $bup; ?>" target="_blank"">Download Source Code</a>
					<hr>
					
					<a class="btn btn-outline-info btn-lg" href="../backup.php">Backup Database</a>
					</center>
				</div>
			</div>
			<a href="index.php" class="btn btn-outline-info"><i class="fa fa-chevron-left"></i></a>
		</div>
		</div>
	</div>
	
</body>
</html>
<script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    
    <script src="../js/scripts.js"></script>
<?php } ?>