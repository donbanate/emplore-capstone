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
    <title>Training Needs</title>
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
        <?php  
            if (isset($_POST['deleteNeeds'])) {
                $id = $_POST['id'];
                $del = "DELETE FROM expected_trainings WHERE id = '$id'";
                if ($conn->query($del) === TRUE) {
                    echo "<script>alert('Training need deleted');</script>";
                    echo "<script>window.open('trainingNeeds.php','_self');</script>";
                }
            }
        ?>
        <div class="col-md-9 col-lg-10 main">

            <!--toggle sidebar button
            <p class="hidden-md-up">
                <button type="button" class="btn btn-primary-outline btn-sm" data-toggle="offcanvas"><i class="fa fa-chevron-left"></i> Menu</button>
            </p>-->

            <h1 class=" d-none d-sm-block">
            <strong><font color="green">Training Needs <i class="fa fa-bitcoin"></i></font></strong>
            </h1>
            <div class="row mb-3">
                <div class="table-responsive col-12">
                    <form class="pull-right" method="POST" action="trainingNeeds.php">
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
                    <table class="table table-hover table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Employee Name</th>
                                <th>Training Title</th>
                                <th>Expected Date</th>
                                <th>Expected Expenses</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php  
                            $sql = "SELECT * FROM employees AS E, expected_trainings AS X WHERE E.id = X.employee_id ORDER BY X.id DESC";
                            $result = $conn->query($sql);
                            $count = $result->num_rows;
                            while ($row = $result->fetch_assoc()) {
                                if ($count == 0) {
                                    echo "No records found";
                                }else{
                        ?>
                            <tr>
                                <td><?=$row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['name_extension']?></td>
                                <td><?=$row['training_title']?></td>
                                <td><?=$row['expected_date']?></td>
                                <td><?=$row['expected_expenses']?></td>
                                <td>
                                <form method="POST" action="trainingNeeds.php">
                                    <button type="submit" class="btn btn-outline-danger" data-toggle="tooltip" title="DELETE" onclick="return confirm('Are you sure?')" name="deleteNeeds"><i class="fa fa-trash"></i></button>
                                    <input type="hidden" name="id" value="<?=$row['id']?>">
                                </form>
                                </td>
                            </tr>
                        <?php  
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php  
            }else{
                if (isset($_POST['btnSearch'])) {
                    $searchString = addslashes(trim($_POST['searchString']));
                    if ($searchString != '' AND $searchString != ' ') {
                        
                        $sql2 = "SELECT * FROM employees AS E, expected_trainings AS X WHERE (E.first_name LIKE '%$searchString%' OR E.middle_name LIKE '%$searchString%' OR E.last_name LIKE '%$searchString%' OR E.name_extension LIKE '%$searchString%' OR X.training_title LIKE '%$searchString%' OR X.expected_date LIKE '%$searchString%' OR X.expected_expenses LIKE '%$searchString%') AND E.id = X.employee_id ORDER BY X.id DESC";
                    }else{
                        $sql2 = "SELECT * FROM employees AS E, expected_trainings AS X WHERE E.id = X.employee_id ORDER BY X.id DESC";
                    }
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
                    <table class="table table-hover table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Employee Name</th>
                                <th>Training Title</th>
                                <th>Expected Date</th>
                                <th>Expected Expenses</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                while ($fetchTrainings = $checkTrainings -> fetch_array()) {
                            ?>
                            <tr>
                                <td><?=$fetchTrainings['first_name'] . ' ' . $fetchTrainings['middle_name'] . ' ' . $fetchTrainings['last_name'] . ' ' . $fetchTrainings['name_extension']?></td>
                                <td><?=$fetchTrainings['training_title']?></td>
                                <td><?=$fetchTrainings['expected_date']?></td>
                                <td><?=$fetchTrainings['expected_expenses']?></td>
                                <td>
                                <form method="POST" action="trainingNeeds.php">
                                    <button type="submit" class="btn btn-outline-danger" data-toggle="tooltip" title="DELETE" onclick="return confirm('Are you sure?')" name="deleteNeeds"><i class="fa fa-trash"></i></button>
                                    <input type="hidden" name="id" value="<?=$fetchTrainings['id']?>">
                                </form>
                                </td>
                            </tr>
                            <?php      
                            }
                            ?>
                            <a class="btn btn-outline-info" href="trainingNeeds.php"><i class="fa fa-chevron-left"></i> GO BACK</a>
                            <p></p>
                        </tbody>
                    </table>
                </div>
            <?php
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
                <p><strong>EMPLORE</strong> is a computerized Employee Trainings and Seminars Record System. The system keeps records of trainings and seminars attended by the employees of Cagayan State University Gonzaga Campus. It enables a more efficient process of sending and approving training and seminars. This is a user friendly application with its updated and easy-to-use features.</p>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary-outline" data-dismiss="modal">OK</button> -->
            </div>
        </div>
    </div>
</div
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