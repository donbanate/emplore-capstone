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
    <title>Evaluate Trainings</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body >
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-primary mb-3">
    <div class="flex-row d-flex">
        <a class="navbar-brand" href="" title="Admin"><span class="fa fa-user"></span>  Admin</a>
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
                       <li class="nav-item"><a class="nav-link" href="approvedTrainings.php"><span class="badge badge-dark"><?php echo $countApprovals; ?></span> View Training Requests </a></li>
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

            <h1 class="d-none d-sm-block">
            <strong><font color="19B5FE">Training Request <i class="fa fa-inbox"></i></font></strong>
            </h1>
            <p class="lead d-none d-sm-block">Evaluate these requests as soon as possible</p>
            <div class="row mb-3">
            <form method="POST" action="trainingRequest.php">
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
            </div>
            <div class="row mb-3">
                <!-- Approve -->
                <?php  
                    if (isset($_POST['approve'])) {
                        $id = $_GET['id'];
                        $approve = "UPDATE approvals AS A, employee_training_needs AS N SET remark_type = 'Approved', final_remark = 'Approved by the admin' WHERE A.id = N.id AND A.id = '$id' ";
                        if ($conn->query($approve) === TRUE) {
                ?>
                        <div class="alert alert-success" role="alert">
                            <strong>Great!</strong> Training Succesfully Approved.
                        </div>
                        <script>window.open('trainingRequest.php', '_self');</script>
                <?php    
                        }
                    }
                ?>
                <!-- EndApprove -->

                <!-- Delete-->
                <?php  
                    if (isset($_POST['delete'])) {
                        $id = addslashes($_POST['id']);
                        $delete     = "DELETE FROM trainings WHERE id = '$id' ";
                        $delete1    = "DELETE FROM iteneraries WHERE id = '$id' ";
                        $delete2    = "DELETE FROM employee_training_needs WHERE id = '$id' ";
                        $delete3    = "DELETE FROM approvals WHERE id = '$id' ";
                        if ($conn->query($delete) === TRUE AND $conn->query($delete1) === TRUE AND $conn->query($delete2) === TRUE AND $conn->query($delete3) === TRUE) {
                ?>
                        <div class="alert alert-success" role="alert">
                            <strong>Success!</strong> Training Succesfully Deleted.
                        </div>
                        <script>window.open('trainingRequest.php', '_self');</script>
                <?php    
                        }
                    }else{
                ?>
                <!-- EndDelete-->

                <div class="table-responsive col-12">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Name</th>
                                <th>Training Title</th>
                                <th>Training Date</th>
                                <th>Training Expenses</th>
                                <th  colspan="5"><center>Action</center></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php  
                            if (!isset($_POST['searchBtn'])) {
                            $sql = "SELECT * FROM users AS U, employees AS E, employee_training_needs AS A, employee_designations AS D, trainings AS T WHERE U.id = E.id AND U.id = D.id AND E.id = A.employee_id AND A.id = T.id AND final_remark = 'approved'  ";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                        ?>
                        <?php
                            $username = $row['username'];
                            $checkID = $conn -> query("SELECT * FROM users WHERE username = '$username'");
                            $fetchID = $checkID -> fetch_array();

                            $userID = $fetchID['id'];
                        ?>

                            <tr>
                                <td><a href="viewEmployeeDetails.php?id=<?=$userID?>" data-toggle ="tooltip" title="View Details" data-placement="right"><i class="fa fa-paperclip"></i></a> <?=$row['first_name'] . ' ' . $row['middle_name'] . ' '.$row['last_name'] . ' '. $row['name_extension'] ?></td>

                                <td><a href="viewTrainingDetails.php?id=<?=$row['id']?>" data-toggle ="tooltip" title="View Details" data-placement="right"><i class="fa fa-paperclip"></i></a> <?=$row['training_title']?></td>

                                <td><?=$row['training_date']?></td>

                                <td><a href="viewTrainingExpenses.php?id=<?=$row['id']?>" data-toggle ="tooltip" title="View Details" data-placement="right"><i class="fa fa-paperclip"></i></a> Php <?=$row['total_expenses_to_be_incurred']?>.00</td>

                                <td>
                                    <form method="POST" action="trainingRequest.php?id=<?=$row['id']?>">
                                        <button type="submit" class="btn btn-outline-success" data-toggle ="tooltip" title="Approve" data-placement="bottom" name="approve"><i class="fa fa-check"></i></button>
                                    </form>
                                </td>
                                <td>
                                    <a class="btn btn-outline-danger" data-toggle ="tooltip" title="Disapprove" data-placement="bottom" name="disapprove" href="disapproveTraining.php?id=<?=$row['id']?>"><i class="fa fa-minus"></i></a>
                                </td>
                                <td>
                                    <a class="btn btn-outline-warning" data-toggle ="tooltip" title="Postponed" data-placement="bottom" name="postponed" href="postponeTraining.php?id=<?=$row['id']?>"><i class="fa fa-recycle"></i></a>
                                </td>
                                <td>
                                    <a class="btn btn-outline-danger" data-toggle ="tooltip" title="Cancel" data-placement="bottom" name="cancel" href="cancelTraining.php?id=<?=$row['id']?>"><i class="fa fa-toggle-off"></i></a>
                                </td>
                                <td>
                                    <form method="POST" action="trainingRequest.php">
                                        <button type="submit" class="btn btn-danger" data-toggle ="tooltip" title="DELETE" name="delete" data-placement="bottom" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                                        <input type="hidden" name="id" value="<? echo $userID; ?>">
                                    </form>

                                </td>
                            </tr>
                        <?php
                            }

                            }else{
                                $searchString = addslashes($_POST['searchString']);

                                if ($searchString == '' OR $searchString == ' ') {
                                    $sql = "SELECT * FROM users AS U, employees AS E, employee_training_needs AS A, employee_designations AS D, trainings AS T WHERE U.id = E.id AND U.id = D.id AND E.id = A.employee_id AND A.id = T.id AND final_remark = 'approved'  ";    
                                }else{
                                    $sql = "SELECT * FROM users AS U, employees AS E, employee_training_needs AS A, employee_designations AS D, trainings AS T WHERE U.id = E.id AND U.id = D.id AND E.id = A.employee_id AND A.id = T.id AND final_remark = 'approved' AND (E.first_name LIKE '%$searchString%' OR E.middle_name LIKE '%$searchString%' OR E.last_name LIKE '%$searchString%' OR T.training_title LIKE '%$searchString%' OR T.training_date LIKE '%$searchString%' OR A.total_expenses_to_be_incurred LIKE '%$searchString%')";
                                }
                                
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                        ?>
                        <?php
                            $username = $row['username'];
                            $checkID = $conn -> query("SELECT * FROM users WHERE username = '$username'");
                            $fetchID = $checkID -> fetch_array();

                            $userID = $fetchID['id'];
                        ?>

                            <tr>
                                <td><a href="viewEmployeeDetails.php?id=<?=$userID?>" data-toggle ="tooltip" title="View Details" data-placement="right"><i class="fa fa-paperclip"></i></a> <?=$row['first_name'] . ' ' . $row['middle_name'] . ' '.$row['last_name'] . ' '. $row['name_extension'] ?></td>

                                <td><a href="viewTrainingDetails.php?id=<?=$row['id']?>" data-toggle ="tooltip" title="View Details" data-placement="right"><i class="fa fa-paperclip"></i></a> <?=$row['training_title']?></td>

                                <td><?=$row['training_date']?></td>

                                <td><a href="viewTrainingExpenses.php?id=<?=$row['id']?>" data-toggle ="tooltip" title="View Details" data-placement="right"><i class="fa fa-paperclip"></i></a> Php <?=$row['total_expenses_to_be_incurred']?>.00</td>

                                <td>
                                    <form method="POST" action="trainingRequest.php?id=<?=$row['id']?>">
                                        <button type="submit" class="btn btn-outline-success" data-toggle ="tooltip" title="Approve" data-placement="bottom" name="approve"><i class="fa fa-check"></i></button>
                                    </form>
                                </td>
                                <td>
                                    <a class="btn btn-outline-danger" data-toggle ="tooltip" title="Disapprove" data-placement="bottom" name="disapprove" href="disapproveTraining.php?id=<?=$row['id']?>"><i class="fa fa-minus"></i></a>
                                </td>
                                <td>
                                    <a class="btn btn-outline-warning" data-toggle ="tooltip" title="Postponed" data-placement="bottom" name="postponed" href="postponeTraining.php?id=<?=$row['id']?>"><i class="fa fa-recycle"></i></a>
                                </td>
                                <td>
                                    <a class="btn btn-outline-danger" data-toggle ="tooltip" title="Cancel" data-placement="bottom" name="cancel" href="cancelTraining.php?id=<?=$row['id']?>"><i class="fa fa-toggle-off"></i></a>
                                </td>
                                <td>
                                    <form method="POST" action="trainingRequest.php">
                                        <button type="submit" class="btn btn-danger" data-toggle ="tooltip" title="DELETE" name="delete" data-placement="bottom" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                                        <input type="hidden" name="id" value="<? echo $userID; ?>">
                                    </form>

                                </td>
                            </tr>
                        <?php
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--/row--> 


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