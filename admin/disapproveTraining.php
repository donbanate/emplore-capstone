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
	<title>Disapprove Training</title>
	<link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>
	<div class="card-block col-6 offset-3">
		<div class="card-header text-white bg-danger"><strong><h3>Dissaprove Training Request <i class="fa fa-meh-o"></i></h3></strong></div>
		<?php  
			if (isset($_POST['disapprove'])) {
				$id = $_POST['id'];
				$comment = addslashes(ucfirst($_POST['reason']));
				$sql = "UPDATE approvals SET remark_type = 'Disapproved', comment = '$comment' WHERE id = '$id' ";
				$sql2 = "UPDATE employee_training_needs SET final_remark = 'Disapproved by the admin' WHERE id = '$id' ";
				if ($conn->query($sql) === TRUE AND $conn->query($sql2) === TRUE) {
		?>
                <script type="text/javascript">
                	alert('Training request disapproved');
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
			$sql = "SELECT * FROM trainings AS T ,approvals AS A WHERE  T.id = A.id AND A.id = '$id' ";
			$result = $conn->query($sql);
			$row = $result -> fetch_assoc();
		?>
			<form method="POST" action="disapproveTraining.php?<?=$row['id']?>">
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
                            <button type="submit" name="disapprove" class="btn btn-outline-danger btn-lg form-control" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="CLICK TO DISAPPROVE"><i class="fa fa-remove"></i></button>
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