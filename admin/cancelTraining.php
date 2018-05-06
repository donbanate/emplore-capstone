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
	<title>Cancel Training Training</title>
	<link rel="icon" type="image/png" href="../logo/logo.png">
	<link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>
	<div class="card-block col-6 offset-3">
		<div class="card-header text-white bg-warning"><h3><strong>Cancel Training Request </strong><i class="fa  fa-bitbucket"></i></h3></div>
		<?php  
			if (isset($_POST['cancel'])) {
				$id = $_POST['id'];
				$comment = addslashes(ucfirst($_POST['reason']));
				$cancel = "UPDATE approvals AS A, employee_training_needs AS N SET remark_type = 'Canceled', comment = '$comment' ,final_remark = 'Canceled by the admin' WHERE A.id = N.id AND A.id = '$id'";
				$cancel1 = "UPDATE employee_training_needs SET final_remark = 'Canceled by the admin' WHERE id = '$id'";
				if ($conn->query($cancel) === TRUE AND $conn->query($cancel1)) {
		?>
                <script type="text/javascript">
                	alert('Training request CANCELED');
                    window.open('trainingRequest.php', '_self');
                </script>
            </div>
		<?php
				}else{
		?>
			<div class="alert alert-danger" role="alert">
                <strong>Oh snap!</strong> Something went wrong.
            </div>
		<?php			
				}
			}else{
		?>
		<div class="card-body">
		<?php  
			$id = addslashes($_GET['id']);
			$sql = "SELECT * FROM trainings AS T ,approvals AS A, employee_training_needs AS N WHERE  T.id = A.id AND T.id = N.id AND A.id = '$id' ";
			$result = $conn->query($sql);
			$row = $result -> fetch_assoc();
		?>
			<form method="POST" action="cancelTraining.php?<?=$row['id']?>">
				<div class="form-group">
					<label><strong>Training Title</strong></label>
					<p class="form-control"><?=$row['training_title']?></p>
				</div>
				<div>
					<label><strong>Comment</strong></label>
					<textarea class="form-control" rows="5" name="reason" placeholder="Please attach your reason(s) here!" autofocus="" required=""></textarea>
				</div>
				<p></p>
				<div class="row">
                        <div class="form-group col-6">
                            <a href="trainingRequest.php" class="btn btn-outline-primary btn-lg form-control" data-toggle="tooltip" title="GO BACK"><i class="fa fa-chevron-left"></i></a>
                        </div>
                        <p></p>
                        <div class="form-group col-6">
                            <button type="submit" name="cancel" class="btn btn-outline-warning btn-lg form-control" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="CLICK TO CANCEL"><i class="fa fa-remove"></i></button>
                            <input type="hidden" name="id" value="<?=$id?>">
                        </div>
                </div>
			</form>
		</div>
	</div>
</body>
<script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/scripts.js"></script>
    <script>
        $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</html>
</html>
<?php
	} 
}
?>