<?php  
    session_start();
    include '../db_connection/db_conn.php';
    $user = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$user' ";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'coordinator')  {
        echo "<script>window.open('../index.php', '_self');</script>";
    }else{
        include '../db_connection/db_conn.php';

    $req = "SELECT * FROM employee_training_needs WHERE is_in_training_needs = '0' AND is_fund_available = '0' AND remark = 'unchecked' ";
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
    <title>View Reports</title>
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

            <h1 class="d-none d-sm-block">
            <strong><font color="#f1c40f">Submitted Training Reports <i class="fa fa-file-text-o"></i></font></strong>
            </h1>
            <form method="POST" action="viewReports.php">
                <div class="form-group col-12">
                      <div class="form-group col-12">
                        <label class="sr-only" for="exampleInputAmount"></label>
                        <div class="input-group col-12">
                          <input name="searchString" class="form-control" id="exampleInputAmount" placeholder="Search" type="text">
                          <button type="submit" name="searchBtn" class="btn input-group-addon"><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
            </form>
            <div class="row mb-3">
                <div class="col-12">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead class="thead-dark">
								<tr>
                                    <th>Name</th>
									<th>Training Title</th>
                                    <th>Status</th>
                                    <th>Number of Paticipants</th>
									<th>Date of Submission</th>
								</tr>
							</thead> 
					<?php
                        if (!isset($_POST['searchBtn'])) {
                            $checkAttachments = $conn->query("SELECT * FROM employees AS E, reports AS R WHERE E.id = R.employee_id");
                        }else{
                            $searchString = addslashes($_POST['searchString']);
                            $checkAttachments = $conn->query("SELECT * FROM employees AS E, reports AS R WHERE E.id = R.employee_id AND (E.first_name LIKE '%$searchString%' OR E.middle_name LIKE '%$searchString%' OR E.last_name LIKE '%$searchString%' OR R.training_title LIKE '%$searchString%' OR R.narrative LIKE '%$searchString%' OR R.date_submitted LIKE '%$searchString%')");
                            $countSearch = $checkAttachments -> num_rows;
                            if ($countSearch == 0) {
                                echo 'No search result found!';
                            }
                        }

						while ($fetchAttachments = $checkAttachments -> fetch_array()) {
					?>
							<tbody>
								<tr>
                                    <td><?=$fetchAttachments['first_name'] . ' ' .$fetchAttachments['middle_name'] . ' '.$fetchAttachments['last_name'] . ' '.$fetchAttachments['name_extension']?></td>
									<td><?php echo $fetchAttachments['training_title']; ?></td>
                                    <td>
                                        <?php  
                                            if (empty($fetchAttachments['narrative'])) {
                                                echo "<font color='red'>Not yet submitted</font>";
                                            }else{
                                                echo "<font color='green'><i class='fa fa-check-circle'> </i> Submitted</font>";
                                            }
                                        ?>
                                    </td>
                                    <td><?=$fetchAttachments['no_participants']?></td>
                                    <td><?=$fetchAttachments['date_submitted']?></td>
                                </tr>
							</tbody>
					<?php
						}
					?>
					</table>
					</div>
				</div>
            </div>
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
    <p class="text-right small">Emplore © <?php echo date('Y') ?> </p>
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
                <p><strong>EMPLORE</strong> is a computerized Employee Trainings and Seminars Record System. The system keeps records of trainings and seminars attended by the employees of Cagayan State University Gonzaga Campus. It enables a more efficient process of sending and approving training and seminars. This is a user friendly application with its updated and easy-to-use features.</p>
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