<?php  
    session_start();
    include '../db_connection/db_conn.php';
    $user = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$user' ";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'employee')  {
        echo "<script>window.open('../index.php', '_self')</script>";
    }else{
    $user = $_SESSION['id'];

    $sql = "SELECT * FROM users AS U, employees AS E, employee_designations AS D WHERE U.id = E.id AND U.id = D.id AND U.id = '$user' ";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $approval = "SELECT * FROM approvals WHERE employee_id = '$user' ";
    $result1 = $conn->query($approval);
    $countApp = $result1-> num_rows;

    $send = "SELECT * FROM employee_training_needs WHERE employee_id = '$user' AND is_in_training_needs = '0' AND is_fund_available = '0' AND remark = 'unchecked' ";
    $result2 = $conn->query($send);
    $countSend = $result2->num_rows;

    $total = $countApp + $countSend;

    $totalTrainings = "SELECT * FROM approvals WHERE remark_type = 'Approved' AND employee_id = '$user' ";
    $result3 = $conn->query($totalTrainings);
    $counttotalTrainings = $result3->num_rows;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="generator" content="Codeply">

    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
    <style type="text/css">
        #icon{
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
        }
    </style>
  </head>
  <body >
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-primary mb-3">
    <div class="flex-row d-flex">
        <a class="navbar-brand" href="employeeProfile.php" title=""><span class="fa fa-user"></span> <?=$row['first_name']?></a> 
        <button type="button" class="navbar-toggler" data-toggle="offcanvas" title="Toggle responsive left sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
        <span class="fa fa-chevron-down"></span>
    </button>
    <div class="navbar-collapse collapse" id="collapsingNavbar">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="Employeemanual.php" target="_blank">Help <i class="fa fa-question-circle"></i><span class="sr-only">Help </span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" data-target="#myModal3" data-toggle="modal">About <i class="fa fa-info-circle"></i><span class="sr-only">About</span></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="" data-target="#myModal" data-toggle="modal"><span class="fa fa-sign-out"></span> Log out<span class="sr-only">Log out</span></a>
            </li>
        </ul>
    </div> 
</nav>
<!-- START -->
<div class="container-fluid" id="main">
    <div class="row row-offcanvas row-offcanvas-left">
        <div class="col-md-3 col-lg-2 sidebar-offcanvas" id="sidebar" role="navigation">
            <ul class="nav flex-column pl-1">
                <li class="nav-item"><a class="nav-link" href="index.php">Overview</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="#submenu1" data-toggle="collapse" data-target="#submenu1"><span class="badge badge-light"><?php echo $total; ?></span> Trainings ▾</a>
                    <ul class="list-unstyled flex-column pl-3 collapse" id="submenu1" aria-expanded="true">
                       <li class="nav-item"><a class="nav-link" href="SendTrainingRequest.php">Send Training Request</a></li>
                       <li class="nav-item"><a class="nav-link" href="approvals.php"><span class="badge badge-pill badge-dark"><?php echo $countApp; ?></span> Training Approval Progress</a></li>
                       <li class="nav-item"><a class="nav-link" href="sentTrainings.php"><span class="badge badge-pill badge-dark"><?php echo $countSend; ?></span> Sent Trainings</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="expectedTrainings.php">Enter Expected Trainings</a></li>
                <li class="nav-item"><a class="nav-link" href="changePsswd.php">Change Password</a></li>
            </ul>
        </div>
        <!--/col-->

        <div class="col-md-9 col-lg-10 main">

            <!--toggle sidebar button
            <p class="hidden-md-up">
                <button type="button" class="btn btn-primary-outline btn-sm" data-toggle="offcanvas"><i class="fa fa-chevron-left"></i> Menu</button>
            </p>-->

            <h1 class="display-4 d-none d-sm-block">
            EMPLORE
            </h1>
            <p class="lead d-none d-sm-block">(Employee Traning and Seminar Record System)</p>


            <div class="row mb-3">
                <div class="col-xl-4 col-sm-6">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body bg-success">
                            <div class="rotate">
                                <i class="fa fa-street-view fa-3x"></i>
                            </div>
                            <a class="text-white" href="employeeProfile.php"><h6 class="text-uppercase"><strong data-toggle="tooltip" title="GO TO PROFILE" data-placement="right"><?=$row['first_name']?></strong></h6></a>
                            <h1 class="display-5"><?=$row['designated_as']?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body bg-info">
                            <div class="rotate">
                                <i class="fa fa-child fa-3x"></i>
                            </div>
                            <a class="text-white" href="approvedTrainings.php"><h6 class="text-uppercase"><strong data-toggle="tooltip" title="GO TO APPROVED TRAININGS" data-placement="right">Total Trainings</strong></h6></a>
                            <h1 class="display-3"><?php echo $counttotalTrainings; ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body bg-warning">
                            <div class="rotate">
                                <i class="fa fa-paper-plane-o fa-3x"></i>
                            </div>
                            <a class="text-white" href="sentTrainings.php"><h6 class="text-uppercase"><strong data-toggle="tooltip" title="GO TO SENT TRAININGS" data-placement="right">Sent Trainings</strong></h6></a>
                            <h1 class="display-3"><?php echo $countSend; ?></h1>
                        </div>
                    </div>
                </div>
            </div>
            <!--/row-->

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
                <p><strong>EMPLORE</strong> is a computerized Employee Trainings and Seminars Record System. The system  keeps records of trainings and seminars attended by the employees of Cagayan State University Gonzaga Campus. It enables a more efficient process of sending and approving training and seminars. This is a user friendly application with its updated and easy-to-use features.</p>
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