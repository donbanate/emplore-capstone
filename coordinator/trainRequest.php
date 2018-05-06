<?php  
    session_start();
    include '../db_connection/db_conn.php';
    $user = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$user' ";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'coordinator') {
        echo "<script>window.open('../index.php', '_self');</script>";
    }else{
    $req = "SELECT * FROM employee_training_needs WHERE is_in_training_needs = '0' AND is_fund_available = '0' AND remark = 'unchecked'";
    $result = $conn->query($req);
    $countReq = $result-> num_rows;

    $training = "SELECT * FROM employee_training_needs WHERE is_in_training_needs = '1'";
    $result = $conn->query($training);
    $countTrain = $result-> num_rows;

    $rep = "SELECT * FROM reports"; 
    $result = $conn->query($rep);
    $countRep = $result-> num_rows;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Training Requests</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body >
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-primary mb-3">
    <div class="flex-row d-flex">
        <a class="navbar-brand" href="#" title="Admin"><span class="fa fa-user"></span> Coordinator</a>
        <button type="button" class="navbar-toggler" data-toggle="offcanvas" title="Toggle responsive left sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="collapsingNavbar">
        <ul class="navbar-nav">
                <li class="nav-item active">
                <a class="nav-link" href="manual.php" target="_blank">Help<span class="sr-only">Help</span></a>
            </li>
                <li class="nav-item active">
                <a class="nav-link" href="#" data-target="#myModal3" data-toggle="modal">About <span class="sr-only">About</span></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link active" href="" data-target="#myModal" data-toggle="modal"><span class="fa fa-sign-out"></span> Log out<span class="sr-only">Log out</span></a>
            </li>
        </ul>
    </div>
</nav>
<div class="container-fluid" id="main">
    <div class="row row-offcanvas row-offcanvas-left">
        <div class="col-md-3 col-lg-2 sidebar-offcanvas" id="sidebar" role="navigation">
            <ul class="nav flex-column pl-1">
                <li class="nav-item"><a class="nav-link" href="index.php">Overview</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="#submenu1" data-toggle="collapse" data-target="#submenu1"><span class="badge badge-primary"><?php echo $countReq; ?></span> Trainings ▾</a>
                    <ul class="list-unstyled flex-column pl-3 collapse" id="submenu1" aria-expanded="false">
                       <li class="nav-item"><a class="nav-link" href="trainRequest.php"><span class="badge badge-dark"><?php echo $countReq; ?></span> Training Requests</a></li>
                       <li class="nav-item"><a class="nav-link" href="trainingNeeds.php">Training Needs</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="checkedTrainings.php">Checked Trainings</a></li>
                <li class="nav-item"><a class="nav-link" href="viewReports.php">Submitted Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="changePsswd.php">Change Password</a></li>
            </ul>
        </div>
        <!--/col-->

        <div class="col-md-9 col-lg-10 main">

            <!--toggle sidebar button
            <p class="hidden-md-up">
                <button type="button" class="btn btn-primary-outline btn-sm" data-toggle="offcanvas"><i class="fa fa-chevron-left"></i> Menu</button>
            </p>-->
            <div class="row mb-3">
               <h1><strong><font color="#3498db">Training Requests <i class="fa fa-envelope"></i></font></strong></h1>
            </div>
            <div class="row mb-3">
            	<div class="col-xl-12 col-sm-12">
                    <form class="pull-right" method="POST" action="trainRequest.php">
                     <div class="form-group col-12">
                      <div class="form-group col-12">
                        <label class="sr-only" for="exampleInputAmount"></label>
                        <div class="input-group col-12">
                          <input name="searchString" class="form-control" id="exampleInputAmount" placeholder="Search" type="text">
                          <button type="submit" name="btnSearch" class="btn input-group-addon"><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                    </form>
                    <?php  
                        if (!isset($_POST['btnSearch']) OR !isset($_POST['searchString'])) {
                    ?>
            		<div class="table-responsive">
            			<table class="table table-bordered table-hover">
            				<thead class="thead-dark">
            					<tr>
                                    <th>Name of Employee</th>
            						<th>Training Title</th>
            						<th>Training Date</th>
            						<th>Training Venue</th>
            						<th colspan="2">Action</th>
            					</tr>
            				</thead>
            				<tbody>
            				<?php
                                if (!isset($_GET['page'])) {
                                    $sql = "SELECT * FROM employees AS E, trainings AS T, employee_training_needs AS N WHERE N.remark = 'unchecked' AND E.id = T.employee_id AND N.id = T.id ORDER BY N.id DESC LIMIT 0, 10";
                                }else{
                                    $min = 0;
                                    $page = addslashes($_GET['page']);

                                    $min = ($page * 10) - 10;

                                    $sql = "SELECT * FROM employees AS E, trainings AS T, employee_training_needs AS N WHERE N.remark = 'unchecked' AND E.id = T.employee_id AND N.id = T.id ORDER BY N.id DESC LIMIT $min, 10";
                                }
            					
            					$result = $conn->query($sql);
            					while ($row = $result->fetch_assoc()) {
            					 	if ($row['is_fund_available'] == '0' AND $row['is_in_training_needs'] == '0' AND $row['remark'] == 'unchecked') { 
            				?>
            					<tr>
                                    <td><?=$row['first_name'] . ' ' .$row['middle_name'] . ' ' .$row['last_name'] . ' '.$row['name_extension']?></td>
            						<td><?=$row['training_title']?></td>
            						<td><?=$row['training_date']?></td>
            						<td><?=$row['venue']?></td>
            						<td><a class="btn btn-outline-primary" data-toggle="tooltip" title="CLICK TO CHECK" data-placement="right" href="viewNeeds.php?id=<?=$row['id']?>"><i class="fa fa-thumbs-up"></i></a></td>
                                    <td><a class="btn btn-outline-danger" data-toggle="tooltip" title="CLICK TO UNCHECK" data-placement="right" href="removeTrainingRequest.php?id=<?=$row['id']?>"><i class="fa fa-thumbs-down"></i></a></td>
            					</tr>
            				<?php  
            				 	}
            				}
            				?>
            				</tbody>
            			</table>
            		</div>
                    <nav>
                        <ul class="pagination">
                            <?php
                                $sql = "SELECT * FROM employee_training_needs WHERE is_in_training_needs = '0' AND is_fund_available = '0' AND remark = 'unchecked'";
                                $result = $conn->query($sql);
                                $num = $result-> num_rows;

                                if ($num % 10 == 0) {
                                    $numPages = floor($num / 10);
                                }else{
                                    $numPages = floor($num / 10) + 1;
                                }

                                if ($numPages > 1) {    
                                    for ($i=1; $i < $numPages+1; $i++) { 
                            ?>
                                        <li class="page-item">
                                        <a href="trainRequest.php?page=<?php echo $i; ?>" class="page-link">
                                        <?php echo $i; ?>                  
                                        </a>
                                        </li>
                            <?php
                                    }
                                }
                            ?>
                        </ul>
                    </nav>
            	</div>
            </div>
            <?php  
            }else{
            ?>
            <?php
                if (isset($_POST['btnSearch'])) {
                    $searchString = addslashes(trim($_POST['searchString']));
                    if ($searchString != '' AND $searchString != ' ') {
                        $sql2 = "SELECT * FROM employees AS E, trainings AS T, employee_training_needs AS N WHERE (E.first_name LIKE '%$searchString%' OR E.middle_name LIKE '%$searchString%' OR E.last_name LIKE '%$searchString%' OR E.name_extension LIKE '%$searchString%' OR T.training_title LIKE '%$searchString%' OR T.training_date LIKE '%$searchString%' OR T.venue LIKE '%$searchString%') AND N.remark = 'unchecked' AND E.id = T.employee_id AND N.id = T.id AND N.is_in_training_needs = '0' ORDER BY N.id DESC";
                    $checkTrainings = $conn -> query($sql2);

                    $countTrainings = $checkTrainings -> num_rows;
                    if ($countTrainings == 0) {
            ?>
                <div class="well">
                    No search result found!
                    <script type="text/javascript">
                        window.open('trainRequest.php', '_self');
                    </script>
                </div>
            <?php 
                }else{
            ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Name of Employee</th>
                                <th>Training Title</th>
                                <th>Training Date</th>
                                <th>Training Venue</th>
                                <th colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                while ($fetchTrainings = $checkTrainings -> fetch_array()) {
                                    if ($fetchTrainings['is_fund_available'] == '0' AND $fetchTrainings['is_in_training_needs'] == '0' AND $fetchTrainings['remark'] == 'unchecked') {
                            ?>
                                <td><?=$fetchTrainings['first_name'] . ' ' .$fetchTrainings['middle_name'] . ' ' .$fetchTrainings['last_name'] . ' '.$fetchTrainings['name_extension']?></td>
                                    <td><?=$fetchTrainings['training_title']?></td>
                                    <td><?=$fetchTrainings['training_date']?></td>
                                    <td><?=$fetchTrainings['venue']?></td>
                                    <td><a class="btn btn-outline-primary" data-toggle="tooltip" title="CLICK TO CHECK" data-placement="right" href="viewNeeds.php?id=<?=$fetchTrainings['id']?>"><i class="fa fa-thumbs-up"></i></a></td>
                                    <td><a class="btn btn-outline-danger" data-toggle="tooltip" title="CLICK TO UNCHECK" data-placement="right" href="removeTrainingRequest.php?id=<?=$fetchTrainings['id']?>"><i class="fa fa-thumbs-down"></i></a></td>
                                </tr>
                            <?php
                                    }    
                                }
                            ?>

                            <a class="btn btn-outline-primary" href="trainRequest.php"><i class="fa fa-chevron-left"></i> GO BACK</a>
                            <p></p>
                        </tbody>
                    </table>
                </div>
            <?php
                    }
                }  
            }
         }   
            ?>
            <!--/row-->

            <a id="features"></a>
            <hr>
                        </div>
                        <!--/tabs content-->
                    </div><!--/card-->
                </div><!--/col-->
            </div><!--/row-->

        </div>
        <!--/main col-->
    </div>

</div>
<!--/.container-->
<footer class="container-fluid">
    <p class="text-right small">Emplore © <?php echo date('Y'); ?></p>
</footer>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-warning" id="myModalLabel"><i class="fa fa-warning fa-2x"></i></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <strong><h4>Are you sure to sign out?</h4></strong>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary-outline" data-dismiss="modal">OK</button> -->
                <a href="../logout.php" class="btn btn-outline-danger">Sign-out <i class="fa fa-sign-out"></i></a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div> 
    </div>
</div>
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel3"><strong><font color="#446CB3">About Emplore <i class="fa fa-info-circle"></i></font></strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>EMPLORE</strong> is a computerized Employee Trainings and Seminars Record System that helps keep records and make the task easier and faster. The system can keep records of trainings and seminars attended by the employees of Cagayan State University Gonzaga Campus. The process of sending and approving training request made easier. The system is easy to use and has user-friendly features and it is more efficient than the manual system.</p>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary-outline" data-dismiss="modal">OK</button> -->
            </div>
        </div>
    </div>
</div>
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
  </body>
</html>
<?php  
}
?>