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
		
		$id = addslashes($_GET['id']);
		$sql = "SELECT * FROM users AS U, employees AS E, employee_designations AS D, trainings AS T ,employee_training_needs AS N, iteneraries AS I WHERE U.id = E.id AND U.id = D.id AND U.id = T.employee_id AND T.id = N.id AND T.id = I.id AND  T.id = '$id'";
		$result = $conn->query($sql);
        $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Print Preview</title>
	<link rel="icon" type="image/png" href="../logo/logo.png">
	<link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>
<div class="col-lg-12">
	<div id="printBtn">
		<a href="approvedTrainings.php" class="btn btn-outline-info"><i class="fa fa-chevron-left"></i> BACK</a>
		<button onclick="printWindow()"  class="btn btn-outline-success"><i class="fa fa-print"></i> Print</button>
	</div>
</div>
<div class="col-12">
	<div class="table-responsive">
		<center>
			<!-- <span><h3>CAGAYAN STATE UNIVERSITY GONZAGA</h3></span>
			<h5>Brgy. Flourishing, Gonzaga Cagayan Valley</h5> -->
			<span><img src="../logo/letter.png"></span>
			<h6>
				<?php 
					date_default_timezone_set('UTC');
					echo date('F d, Y l');
			 	?> 	
			</h6>
		</center>
		<h4>General Information</h4>
		<table class="table table-striped table-bordered">
			<tr>
				<td><strong>Name</strong></td>
				<td><strong>Designation</strong></td>
				<td><strong>Training Title</strong></td>
				<td><strong>Training Date</strong></td>
			</tr>
			<tr>
				<td><?=$row['first_name'] . ' ' . $row['middle_name'] . ' '.$row['last_name'] . ' '. $row['name_extension'] ?></td>
				<td><?=$row['designated_as']?></td>
				<td><?=$row['training_title']?></td>
				<td><?=$row['training_date']?></td>
			</tr>
		</table>
		<p></p>
		<table class="table table-striped table-bordered">
			<tr>
				<td><strong>Endorsement</strong></td>
				<td><strong>Sponsoring Institution</strong></td>
				<td><strong>Training Officer</strong></td>
				<td><strong>Venue</strong></td>
			</tr>
			<tr>
				<td><?=$row['endorsement']?></td>
				<td><?=$row['sponsor_institution']?></td>
				<td><?=$row['training_officer']?></td>
				<td><?=$row['venue']?></td>
			</tr>
		</table>
		<table class="table table-striped table-bordered">
			<tr>
				<td><strong>Return-to-post</strong></td>
				<td><strong>Reason of Participation</strong></td>
				
			</tr>
			<tr>
				<td><?=$row['return_to_post']?></td>
				<td><?=$row['reason_of_participation']?></td>
			</tr>
		</table>
		<table class="table table-striped table-bordered">
			<tr>
				<td><strong>Project Title</strong></td>
				<td><strong>Completion Date</strong></td>
			</tr>
			<tr>
				<td><?=$row['project_title']?></td>
				<td><?=$row['completion_date']?></td>
			</tr>
		</table>
	</div>
	<div class="table-responsive">
		<h4>Projected Incidental Expenses</h4>
		<table class="table table-striped table-bordered">
			<tr>
				<td><strong>Total Transporation Expenses</strong></td>
				<td><strong>Total Cost of Per Diem</strong></td>
				<td><strong>Seminar Training Fee</strong></td>
				<td><strong>Total Expenses to be Incurred</strong></td>
			</tr>
			<tr>
				<td><?=$row['transportation_expense']?></td>
				<td><?=$row['total_cost_of_per_diem']?></td>
				<td><?=$row['seminar_training_fee']?></td>
				<td><?=$row['total_expenses_to_be_incurred']?></td>
			</tr>
		</table>
	</div>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<div class="table-responsive">
		<h4>Itenerary</h4>
		<table class="table table-striped table-bordered">
			<tr>
				<td><strong>Venue</strong></td>
				<td><strong>Date Departure</strong></td>
				<td><strong>Date Arrival</strong></td>
				<td><strong>Transportation</strong></td>
				<td><strong>Expenses</strong></td>
				<td><strong>Total</strong></td>
			</tr>
			<tr>
				<td><?=$row['venue']?></td>
				<td><?=$row['date_departure']?></td>
				<td><?=$row['date_arrival']?></td>
				<td><?=$row['transportation']?></td>
				<td><?=$row['expenses']?></td>
				<td><?=$row['total']?></td>
			</tr>
		</table>
		
	</div>
	<div class="table-responsive">
		<?php  
			$empID = $row['employee_id'];
			$lsa = "SELECT * FROM seminars_attended WHERE employee_id = '$empID' ORDER BY id DESC";
			$result1 = $conn->query($lsa);
			$show = $result1->fetch_assoc();
		?>
		<table class="table table-striped table-bordered">
			<h4>Previous Seminar Attended</h4>
		<tr>
			<td>Last Seminar Attended</td>
			<td>Date</td>
			<td>Venue</td>
			<td>Expenses Incurred</td>
		</tr>
		<tr>
			<td><?php 
				if (empty($show['last_seminar_attended'])) {
					echo "<i>No training attended yet</i>";
				}else{
					echo $show['last_seminar_attended'];
				}
			?>
			</td>
			<td><?php 
				if (empty($show['date_attended'])) {
				echo "<i>N/A</i>";
				}else{
					echo $show['date_attended'];
				}
			?>
			</td>
			<td><?php
				if (empty($show['venue'])) {
				echo "<i>N/A</i>";
				}else{
					echo $show['venue'];
				}
			?>	
			</td>
			<td><?php
			if (empty($show['expend'])) {
			echo "<i>N/A</i>";
				}else{
					echo $show['expend'];
				}
			?></td>
		</tr>
		</table>
	</div>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<h5><strong>FERDINAND C. OLI, Ph.D</strong></h5>
		<p>Campus Executive Officer</p>
</div>
</body>
 <!--scripts loaded here-->
    
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    
    <script src="../js/scripts.js"></script>
<script type="text/javascript">
	function printWindow(){
		document.getElementById("printBtn").hidden = true;
		window.print();
		document.getElementById("printBtn").hidden = false;
	}
</script>
<script>
        $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        });
</script>
</html>
<?php  
}
?>