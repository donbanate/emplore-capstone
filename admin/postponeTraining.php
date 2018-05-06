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
	<title>Postpone Training</title>
	<link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>
	<div class="card-block col-6 offset-3">
		<div class="card-header text-white bg-warning"><h3><strong>Reschedule Training </strong><i class="fa fa-calendar-o"></i></h3></div>
		<?php  
			if (isset($_POST['resched'])) {
				$id = $_POST['id'];
				$comment = addslashes(ucfirst($_POST['reason']));
				$trainingSheds = $_POST['tdate'];
				$trainingSheds1 = $_POST['returndate'];
				$trainingSheds2 = $_POST['compdate'];
				$itresched 	= $_POST['datedep'];
				$itresched1 = $_POST['arrivedate'];


				$sql = "UPDATE approvals SET remark_type = 'Rescheduled', comment = '$comment' WHERE id = '$id' ";
				$trainingSched = "UPDATE trainings SET training_date = '$trainingSheds', return_to_post = '$trainingSheds1', completion_date = '$trainingSheds2' WHERE id = '$id' ";
				$trainingShed1 = "UPDATE iteneraries SET date_departure = '$itresched', date_arrival = '$itresched1' WHERE id = '$id' ";
				$trainingShed2 = "UPDATE employee_training_needs SET final_remark = 'Approved by the admin' WHERE id = '$id' ";
				if ($conn->query($sql) === TRUE AND $conn->query($trainingSched) === TRUE AND $conn->query($trainingShed1) === TRUE AND $conn->query($trainingShed2) === TRUE) {
		?>
                <script type="text/javascript">
                	alert('Training request RESCHEDULED');
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
			$sql = "SELECT * FROM trainings AS T ,approvals AS A, iteneraries AS I WHERE T.id = A.id AND T.id = I.id AND A.id = '$id' ";
			$result = $conn->query($sql);
			$row = $result -> fetch_assoc();
		?>
			<form method="POST" action="postponeTraining.php?id=<?=$row['id']?>">
				<div class="form-group">
					<label><strong>Training Title</strong></label>
					<p class="form-control"><?=$row['training_title']?></p>
				</div>
				<h5><i><u>Reschedule Training</u> <i class="fa fa-calendar"></i></i></h5>
				<p><font color="blue"><i>*Please modify these previous trainings</i></font></p>
				<div class="row">
					<div class="form-group col-4">
						<label><i class="fa fa-calendar"></i> Training Date</label>
						<input class="form-control" type="date" name="tdate" value="<?=$row['training_date']?>" autofocus="" required>
					</div>
					<div class="form-group col-4">
						<label><i class="fa fa-undo"></i> Return-to-post</label>
						<input class="form-control" type="date" name="returndate" value="<?=$row['return_to_post']?>" required>
					</div>
					<div class="form-group col-4">
						<label><i class="fa fa-tag"></i> Completion Date</label>
						<input class="form-control" type="date" name="compdate" value="<?=$row['completion_date']?>" required>
					</div>
				</div>
				<h6><strong>Itenerary</strong></h6>
				<div class="row">
					<div class="form-group col-6">
						<label><i class="fa fa-car"></i> Date Departure</label>
						<input class="form-control" type="date" name="datedep" value="<?=$row['date_departure']?>" required>
					</div>
					<div class="form-group col-6">
						<label><i class="fa fa-child"></i> Date Arrival</label>
						<input class="form-control" type="date" name="arrivedate" value="<?=$row['date_arrival']?>" required>
					</div>
				</div>
				<div>
					<label><strong>Comment <i class="fa fa-comments"></i></strong></label>
					<textarea class="form-control" rows="5" name="reason" placeholder="Please attach your reason(s) here!" required=""></textarea>
				</div>
				<p></p>
				<div class="row">
                        <div class="form-group col-6">
                            <a href="trainingRequest.php" class="btn btn-outline-primary btn-lg form-control" data-toggle="tooltip" title="GO BACK"><i class="fa fa-chevron-left"></i></a>
                        </div>
                        <p></p>
                        <div class="form-group col-6">
                            <button type="submit" name="resched" class="btn btn-outline-warning btn-lg form-control" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="CLICK TO RESCHEDULE"><i class="fa fa-calendar-o"></i></button>
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