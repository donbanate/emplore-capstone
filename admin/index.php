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
        
    $users = "SELECT * FROM users WHERE type = 'employee' ";
    $result = $conn->query($users);
    $countUsers = $result->num_rows;

    $approvals = "SELECT * FROM employee_training_needs WHERE final_remark = 'approved'";
    $result1 = $conn->query($approvals);
    $countApprovals = $result1->num_rows;

    $trainings = "SELECT * FROM approvals WHERE remark_type = 'checked' ";
    $result2 = $conn->query($trainings);
    $countTrainings = $result2->num_rows;

    $finish = "SELECT * FROM approvals WHERE remark_type = 'finished' ";
    $result3 = $conn->query($finish);
    $countFish = $result3->num_rows;

    $accounts = "SELECT * FROM users WHERE type = 'pending' ";
    $result4 = $conn->query($accounts);
    $countAcc = $result4->num_rows;

   $total =  $countAcc + $countApprovals;

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body >
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-primary mb-3">
    <div class="flex-row d-flex">
        <a class="navbar-brand" href="tools.php" title="Admin"><i class="fa fa-user"></i> Admin</a>
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
                <a class="nav-link" href="manual.php" target="_blank">Help <i style="font-size: 100%;" class="fa fa-question-circle"></i><span class="sr-only"></span></a>
            </li>
                <li class="nav-item active">
                <a class="nav-link" href="#" data-target="#myModal3" data-toggle="modal">About <i style="font-size: 100%;"  class="fa fa-info-circle"></i><span class="sr-only">About</span></a>
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
                    <a class="nav-link" href="#submenu1" data-toggle="collapse" data-target="#submenu1"><span class="badge badge-primary"><?php echo $total; ?></span> Requests ▾</a>
                    <ul class="list-unstyled flex-column pl-3 collapse" id="submenu1" aria-expanded="false">
                       <li class="nav-item"><a class="nav-link" href="acc_requests.php"><span class="badge badge-dark"><?php echo $countAcc; ?></span> View Account Requests</a></li>
                       <li class="nav-item"><a class="nav-link" href="trainingRequest.php"><span class="badge badge-dark"><?php echo $countApprovals; ?></span> View Training Requests </a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="approvedTrainings.php">View Approved Trainings</a></li>
                <li class="nav-item"><a class="nav-link" href="viewReports.php">View Training Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="addEmployee.php">Add New Employee</a></li>
                <li class="nav-item"><a class="nav-link" href="changePsswd.php">Change Password</a></li>
            </ul>
        </div>
        <!--/col-->

        <div class="col-md-9 col-lg-10 main">

            <!--toggle sidebar button
            <p class="hidden-md-up">
                <button type="button" class="btn btn-primary-outline btn-sm" data-toggle="offcanvas"><i class="fa fa-chevron-left"></i> Menu</button>
            </p>-->

            <h1>
            <img class="img-responsive" style="height: 25%; width: 25%; margin-bottom: 0;" src="../logo/final.png">
            </h1>
            <p class="lead d-none d-sm-block">(Employee Traning and Seminar Record System)</p>
            <div class="row mb-12">
                <div class="col-xl-3 col-sm-6">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body bg-success">
                            <div class="rotate">
                                <i class="fa fa-bar-chart fa-3x"></i>
                            </div>
                            <h6 class="text-uppercase"><a class="text-white" data-toggle="tooltip" title="LIST OF USERS IS BELOW THIS PAGE" href="index.php"><strong>Users</strong></a></h6>
                            <h1 class="display-4"><?php echo $countUsers; ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body bg-danger">
                            <div class="rotate">
                                <i class="fa fa-inbox fa-3x"></i>
                            </div>
                            <h6 class="text-uppercase"><strong><a class="text-white" data-toggle="tooltip" title="CLICK TO VIEW REQUESTS" href="trainingRequest.php">Requests</strong></a></h6>
                            <h1 class="display-4"><?php echo $countApprovals; ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body bg-info">
                            <div class="rotate">
                                <i class="fa fa-tag fa-3x"></i>
                            </div>
                            <h6 class="text-uppercase"><a class="text-white" data-toggle="tooltip" title="CLICK TO VIEW APPROVED TRAININGS" href="approvedTrainings.php"><strong>Trainings</strong></a></h6>
                            <h1 class="display-4"><?php echo $countTrainings; ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body bg-warning">
                            <div class="rotate">
                                <i class="fa fa-flag fa-3x"></i>
                            </div>
                            <h6 class="text-uppercase"><a class="text-white" href="viewReports.php" data-toggle="tooltip" title="CLICK TO VIEW TRAINING REPORTS"><strong>Submitted Reports</strong></a></h6>
                            <h1 class="display-4"><?php echo $countFish; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <!--/row--> 

            <a id="features"></a>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <h2><font color="#27ae60"><strong>Employees <i class="fa fa-group"></i></strong></font></h2>
                    <p></p>
                    <form method="POST" action="index.php"> 
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
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID Number</th>
                                    <th>Name</th>
                                    <th>Sex</th>
                                    <th>Designation</th>
                                    <th>College</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (!isset($_POST['searchBtn'])) {
                                    $sql = "SELECT * FROM employees AS E, users AS U, employee_designations AS D WHERE U.id = D.id AND U.id = E.id";
                                }else{
                                    $searchString = addslashes($_POST['searchString']);
                                    $sql = "SELECT * FROM employees AS E, users AS U, employee_designations AS D WHERE U.id = D.id AND U.id = E.id AND (E.first_name LIKE '%$searchString%' OR E.middle_name LIKE '%$searchString%' OR E.last_name LIKE '%$searchString%' OR E.name_extension LIKE '%$searchString%' OR E.sex LIKE '%$searchString%' OR D.designated_as LIKE '%$searchString%' OR D.department LIKE '%$searchString%')";
                                }

                                $result = $conn->query($sql);

                                $countSearch = $result -> num_rows;

                                if ($countSearch == 0) {
                                    echo 'No search result found!';
                                }

                                    while ($row = $result->fetch_assoc()) {
                                        if ($row['type'] == 'employee') {
                            ?>
                                <tr>
                                    <td><?=$row['username']?></td>
                                    <td><?=$row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['name_extension']?></td>
                                    <td><?=$row['sex']?></td>
                                    <td><?=$row['designated_as']?></td>
                                    <td><?=$row['department']?></td>
                                </tr>
                            <?php
                                }  
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
