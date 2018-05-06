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
    <title>Approved Trainings</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body >
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-primary mb-3">
    <div class="flex-row d-flex">
        <a class="navbar-brand" href="#" title="Admin"><span class="fa fa-user"></span>  Admin</a>
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
                <a class="nav-link" href="#" data-target="#myModal" data-toggle="modal">About <i style="font-size: 100%;"  class="fa fa-info-circle"></i><span class="sr-only">About</span></a>
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

            <h3 class="display-6 d-none d-sm-block">
            APPROVED TRAININGS <i class="fa fa-check-circle"></i>
            </h3>
            <form method="POST" action="approvedTrainings.php">
                <div class="form-group col-12">
                      <div class="form-group col-12">
                        <label class="sr-only" for="exampleInputAmount"></label>
                        <div class="input-group col-12">
                          <input name="searchString" class="form-control" id="exampleInputAmount" placeholder="Search..." type="text">
                          <button type="submit" name="searchBtn" class="btn input-group-addon"><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
            </form>
            <div class="row mb-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        
                        <thead class="thead-dark">
                            <tr>
                                <th>Name</th>
                                <th>Training Title</th>
                                <th>Training Date</th>
                                <th>Print</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if (!isset($_POST['searchBtn'])) {
                                $sql = "SELECT * FROM users AS U, employees AS E, employee_training_needs AS A, employee_designations AS D, trainings AS T, approvals AS V WHERE U.id = E.id AND U.id = D.id AND E.id = A.employee_id AND A.id = T.id AND A.id = V.id AND remark_type = 'Approved' ORDER BY T.date_of_submission DESC";   
                            }else{
                                $searchString = addslashes($_POST['searchString']);
                                $sql = "SELECT * FROM users AS U, employees AS E, employee_training_needs AS A, employee_designations AS D, trainings AS T, approvals AS V WHERE U.id = E.id AND U.id = D.id AND E.id = A.employee_id AND A.id = T.id AND A.id = V.id AND remark_type = 'Approved' AND (E.first_name LIKE '%$searchString%' OR E.middle_name LIKE '%$searchString%' OR E.last_name LIKE '%$searchString%' OR E.name_extension LIKE '%$searchString%' OR T.training_title LIKE '%$searchString%' OR T.training_date LIKE '%$searchString%') ORDER BY T.date_of_submission DESC";
                            }
                             $result = $conn->query($sql);

                             $countSearch = $result -> num_rows;

                                if ($countSearch == 0) {
                                    echo 'No search result found!';
                                }

                             while ($row = $result->fetch_assoc()) {
                       
                        ?>
                            <tr>
                                <td><?=$row['first_name'] . ' ' . $row['middle_name'] . ' '.$row['last_name'] . ' '. $row['name_extension'] ?></td>
                                <td><a href="viewTrainingDetails1.php?id=<?=$row['id']?>" data-toggle="tooltip" title="VIEW TRAINING DETAILS"><i class="fa fa-paperclip"></i></a> <?=$row['training_title']?></td>
                                <td><?=$row['training_date']?></td>
                                <td><a href="printPreview.php?id=<?=$row['id']?>" data-toggle ="tooltip" title="PRINT REQUEST" data-placement="bottom" class="btn btn-outline-primary"> <i class="fa fa-print"></i></a></td>
                            </tr>
                        <?php  
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--/row--> 

            <a id="features"></a>
            <hr>
            <div class="row my-4">
                
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