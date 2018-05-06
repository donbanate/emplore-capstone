<?php  
    session_start();
    include '../db_connection/db_conn.php';
    $user = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$user' ";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'employee') {
        echo "<script>window.open('../index.php', '_self');</script>";
    }else{
     include '../db_connection/db_conn.php';

?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Expected Needs</title>
	<link rel="icon" type="image/png" href="../logo/logo.png">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="generator" content="Codeply">

    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>
	<center><h2 class="alert alert-info"> Edit Expected Training Need Information</h2></center>
	<?php  
		if (isset($_POST['edit'])) {
			
			$id 		= $_POST['id'];
			$title 		= addslashes(ucwords(strtolower(trim($_POST['title']))));
			$xdate 		= addslashes(trim($_POST['t_date']));
			$xpense 	= addslashes($_POST['xpense']);

			$update = "UPDATE expected_trainings SET training_title = '$title', expected_date = '$xdate', expected_expenses = '$xpense' WHERE id ='$id' ";

			if ($conn->query($update) === TRUE) {
	?>
		<div class="alert alert-success alert-dismissable">
    		<a href="expectedTrainings.php"><button  type="button" class="close" data-dismiss="alert">&times;</button></a>
    		<strong>Success!</strong>

  		</div>
	<?php		
		 }else{

	?>
		<div class="alert alert-danger alert-dismissable">
    		<button type="button" class="close" data-dismiss="alert">&times;</button>
    		<strong>Oh snap!</strong> Something went wrong.
  		</div>
	<?php			
			}


		}
	?>
	<div class="col-8 offset-2">
		<?php  
		$id = addslashes($_GET['id']);

		$sql = "SELECT * FROM expected_trainings WHERE id= '$id' ";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		?>
		<form method="POST" action="editExpNeed.php?id=<?=$id?>">
			<div class="form-group col-12">
				<label>Training Title</label>
				<input class="form-control" type="text" name="title" value="<?=$row['training_title']?>">
			</div>
			<div class="form-group col-12">
				<label>Expected Training Date</label>
				<input class="form-control" type="date" name="t_date" value="<?=$row['expected_date']?>">
			</div>
			<div class="form-group col-12">
				<label>Expected Expense</label>
				<input class="form-control" type="text" name="xpense" value="<?=$row['expected_expenses']?>">
			</div>
			<div class="form-group col-12">
				<input type="hidden" name="id" value="<?=$id?>">
				<a class="btn" data-toggle="tooltip" data-placement="bottom" title="GO BACK" href="expectedTrainings.php"><i class="fa fa-chevron-left"></i></a>
				<button class="btn btn-ouline btn-warning" data-placement="bottom" data-toggle="tooltip" title="PROCEED EDITING"  type="submit" name="edit"><i class="fa fa-edit"></i></button>
			</div>
		</form>
	</div>

</body>
</html>
<!--scripts loaded here-->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    
    <script src="../js/scripts.js"></script>
<script>
	$(document).ready(function(){
	    $('[data-toggle="tooltip"]').tooltip();
	});
</script>
<?php 
}
?>