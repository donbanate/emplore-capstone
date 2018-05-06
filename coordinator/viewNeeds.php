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
    <title>View Needs</title>
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
                <li class="nav-item"><a class="nav-link" href="#">Overview</a></li>
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
            <strong><font color="F89406">Employee Training Needs <i class="fa fa-folder-open"></i></font></strong>
            </h1>
            <p class="lead d-none d-sm-block">(Employee Traning and Seminar Record System)</p>

            <?php  
                $id = addslashes($_GET['id']);
                $sql = "SELECT * FROM employees AS E, trainings AS T, employee_training_needs AS ETN, iteneraries AS I WHERE T.id = '$id' AND T.employee_id = E.id AND T.id = ETN.id AND T.id = I.id";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                $employee_id = $row['employee_id'];

                #Checking Accounts
                if (isset($_POST['check'])) {
                    $id = addslashes($_GET['id']);
                    $check = "UPDATE employee_training_needs SET is_in_training_needs = '1', remark = 'unchecked', final_remark = 'checked by the fsd' WHERE id = '$id' ";
                    if ($conn->query($check)) {
            
                #-------------------------------------------------------------------------#
            ?>
                <div class="alert alert-success" role="alert">
                    <strong>Great!</strong> Succesfully checked.
                </div>
                <script type="text/javascript">
                    window.open('trainRequest.php','_self');
                </script>
            <?php  
                }
            }
            ?>
            <div class="row mb-3">
                <div class="table-responsive col-4">
                    <div class="alert alert-primary">
                        <strong><i class="fa fa-paperclip"></i> Training Details</strong>
                    </div>
                    <table class="table table-bordered table-hover">
                            <tr>
                                <th>Training Title</th>
                                <td><?=$row['training_title']?></td>
                            </tr>
                            <tr>
                                <th>Endorsement</th>
                                <td><?=$row['endorsement']?></td>
                            </tr>
                            <tr>
                                <th>Completion Date</th>
                                <td><?=$row['completion_date']?></td>
                            </tr>
                            <tr>
                                <th>Return-to-post</th>
                                <td><?=$row['return_to_post']?></td>
                            </tr>
                    </table>
                </div>
                <div class="table-responsive col-4">
                    <div class = "alert alert-primary">
                       <strong><i class="fa fa-google-wallet"></i> Iteneraries</strong> 
                    </div>
                    <table class="table table-bordered table-hover">
                            <tr>
                                <th>Venue</th>
                                <td><?=$row['venue']?></td>
                            </tr>
                            <tr>
                                <th>Date of Departure</th>
                                <td><?=$row['date_departure']?></td>
                            </tr>
                            <tr>
                                <th>Date of Arrival</th>
                                <td><?=$row['date_arrival']?></td>
                            </tr>
                            <tr>
                                <th>Total Expenses</th>
                                <td><?=$row['total']?></td>
                            </tr>
                    </table>
                </div>
                <div class="table-responsive col-4">
                    <div class="alert alert-primary">
                        <strong><i class="fa fa-bitcoin"></i> Training Needs</strong>
                    </div>
                    <table class="table table-bordered table-hover">
                            <tr>
                                <th>Transportation Expense</th>
                                <td><?=$row['transportation_expense']?></td>
                            </tr>
                            <tr>
                                <th>Total cost per diem</th>
                                <td><?=$row['total_cost_of_per_diem']?></td>
                            </tr>
                            <tr>
                                <th>Seminar Training Fee</th>
                                <td><?=$row['seminar_training_fee']?></td>
                            </tr>
                            <tr>
                                <th>Total expenses to be incurred</th>
                                <td><?=$row['total_expenses_to_be_incurred']?></td>
                            </tr>
                    </table>
                </div>
            </div>
                <form method="POST" action="viewNeeds.php?id=<?=$row['id']?>">
                    <a class="btn btn-outline-primary btn-lg" data-toggle="tooltip" title="GO BACK" href="trainRequest.php"><i class="fa fa-chevron-left"></i></a>
                    <button type="submit" onclick="return confirm('Are you sure of your action?')" name="check" data-toggle="tooltip" title="CLICK TO CHECK TRAINING" class="btn btn-outline-success  btn-lg">Check</button>
                </form>
            <p></p>
            <div class="row mb-3">
                 <div class="well">
                     <form method="POST" action="viewNeeds.php?id=<?=$row['id']?>">
                     <div class="form-group">
                      <label class="control-label"><i>Verify Training Needs</i></label>
                      <div class="form-group">
                        <label class="sr-only" for="exampleInputAmount"></label>
                        <div class="input-group">
                          <input name="searchString" class="form-control" id="exampleInputAmount" placeholder="search needs" type="text">
                          <button type="submit" name="btnSearch" class="btn input-group-addon"><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                    </form>
                 </div>
                <?php
                    if (isset($_POST['btnSearch'])) {
                        $searchString = addslashes(trim($_POST['searchString']));
                        if ($searchString != '' AND $searchString != ' ') {
                            $sql2 = "SELECT * FROM expected_trainings WHERE (training_title LIKE '%$searchString%' OR expected_date LIKE '%$searchString%' OR expected_expenses LIKE '%$searchString%')";
                            $checkTrainings = $conn -> query($sql2);

                            $countTrainings = $checkTrainings -> num_rows;

                            if ($countTrainings == 0) {
                ?>
                                <div class="well">
                                    No search result found!
                                </div>
                <?php
                            }else{
                ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Title</th>
                                            <th>Expected Date</th>
                                            <th>Expected Expenses</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            while ($fetchTrainings = $checkTrainings -> fetch_array()) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $fetchTrainings['training_title']; ?></td>
                                                    <td><?php echo $fetchTrainings['expected_date']; ?></td>
                                                    <td><?php echo $fetchTrainings['expected_expenses']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                <?php
                            }
                        }
                    }
                ?>
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