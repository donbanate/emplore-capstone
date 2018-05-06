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
    <title>Check Trainings</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body >
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-primary mb-3">
    <div class="flex-row d-flex">
        <a class="navbar-brand" href="" title="Admin"><span class="fa fa-user"></span> Coordinator</a>
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
            <strong><font color="#2ecc71">Checked Trainings <i class="fa fa-check-circle"></i></font></strong>
            </h1>
            <!-- <p class="lead d-none d-sm-block">(Employee Traning and Seminar Record System)</p> -->
            <p></p>


            <div class="row mb-3">
                <div class="table-responsive col-12">
                    <form class="pull-right" method="POST" action="checkedTrainings.php">
                     <div class="form-group">
                      <div class="form-group">
                        <label class="sr-only" for="exampleInputAmount"></label>
                        <div class="input-group col-12">
                          <input name="searchString" class="form-control" id="exampleInputAmount" placeholder="Search" type="text">
                          <button type="submit" name="btnSearch" class="btn input-group-addon"><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                    </form>
                    <table class="table table-bordered  table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Name</th>
                                <th>Training Title</th>
                                <th>Training Date</th>
                            </tr>
                        </thead>
                        <?php
                            if(!isset($_POST['btnSearch'])){
                        ?>
                        <tbody>
                        <?php
                            $sqlComplete = "SELECT * FROM employees AS E, employee_designations AS D,users AS U, employee_training_needs AS A, trainings AS T WHERE E.id = U.id AND E.id = D.id AND E.id = A.employee_id AND A.id = T.id AND is_in_training_needs = '1' ";
                            $resultComplete = $conn->query($sqlComplete);

                            $numItems = $resultComplete -> num_rows;
                            if ($numItems % 10 == 0) {
                                $numPages = $numItems / 10;
                            }else{
                                $numPages = ($numItems / 10) + 1;
                            }

                            $min = 0;

                            if(isset($_GET['page'])){
                                $page = addslashes($_GET['page']);
                                $min = ($page * 10) - 10;
                            }else{
                                $min = 0;
                            }

                            $sql = "SELECT * FROM employees AS E, employee_designations AS D,users AS U, employee_training_needs AS A, trainings AS T WHERE E.id = U.id AND E.id = D.id AND E.id = A.employee_id AND A.id = T.id AND is_in_training_needs = '1' LIMIT $min, 10";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><strong><?=$row['first_name'] . ' ' .$row['middle_name'] . ' ' .$row['last_name'] . ' '.$row['name_extension']?></strong></td>
                                <td><a href="viewTrainingDetails.php?id=<?=$row['id']?>" data-toggle="tooltip" title="VIEW DETAILS" data-placement="left"><i class="fa fa-paperclip"></i></a> <strong><font color="#27ae60"><?=$row['training_title']?> <i class="fa fa-check-circle"></i></font></strong></td>
                                <td><strong><font color="#27ae60"><?=$row['training_date']?> <i class="fa fa-check-circle"></i></font></strong></td>
                            </tr>
                        <?php  
                        }

                            if ($numItems > 10) {
                           
                        ?>
                            <tr>
                                <td colspan="3">
                                    <nav>
                                        <ul class="pagination">
                                            <?php
                                                if (isset($_GET['page'])) {
                                                    $checkPage = addslashes($_GET['page']);

                                                    if ($checkPage > 1) {
                                            ?>
                                                        <li class="page-item">
                                                            <a href="checkedTrainings.php?page=<?php echo $checkPage-1; ?>" class="page-link">
                                                                <
                                                            </a>
                                                        </li>
                                            <?php
                                                    }
                                                }

                                                for($i = 1; $i <= $numPages; $i++){
                                            ?>
                                                    <li class="page-item">
                                                        <a href="checkedTrainings.php?page=<?php echo $i; ?>" class="page-link">
                                                            <?php echo $i; ?>
                                                        </a>
                                                    </li>
                                            <?php
                                                }

                                                if (!isset($_GET['page'])) {
                                            ?>
                                                    <li class="page-item">
                                                        <a href="checkedTrainings.php?page=2" class="page-link">
                                                           >
                                                        </a>
                                                    </li>
                                            <?php
                                                }else{
                                                    $checkPage = addslashes($_GET['page']);

                                                    if ($checkPage != $numPages) {
                                            ?>
                                                        <li class="page-item">
                                                            <a href="checkedTrainings.php?page=<?php echo $checkPage+1; ?>" class="page-link">
                                                                >
                                                            </a>
                                                        </li>
                                            <?php
                                                    }
                                                }  
                                            ?>
                                        </ul>
                                    </nav>
                                </td>
                            </tr>
                            <?php
                                 }
                            ?>
                        </tbody>
                        <?php
                            }else{
                        ?>
                        <tbody>
                        <?php
                            $searchString = addslashes($_POST['searchString']);

                            if($searchString == '' OR $searchString == ' '){
                                $sql = "SELECT * FROM employees AS E, employee_designations AS D,users AS U, employee_training_needs AS A, trainings AS T WHERE E.id = U.id AND E.id = D.id AND E.id = A.employee_id AND A.id = T.id AND is_in_training_needs = '1'";
                            }else{
                                $sql = "SELECT * FROM employees AS E, employee_designations AS D,users AS U, employee_training_needs AS A, trainings AS T WHERE E.id = U.id AND E.id = D.id AND E.id = A.employee_id AND A.id = T.id AND is_in_training_needs = '1' AND (E.first_name LIKE '%$searchString%' OR E.middle_name LIKE '%$searchString%' OR E.last_name LIKE '%$searchString%' OR T.training_title LIKE '%$searchString%' OR T.training_date LIKE '%$searchString%')";    
                            }

                            $result = $conn->query($sql);

                            $searchResult = $result -> num_rows;

                            if($searchResult == 0){
                                echo 'No search result found!';
                            }else{
                                while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><strong><?=$row['first_name'] . ' ' .$row['middle_name'] . ' ' .$row['last_name'] . ' '.$row['name_extension']?></strong></td>
                                <td><a href="viewTrainingDetails.php?id=<?=$row['id']?>" data-toggle="tooltip" title="VIEW DETAILS" data-placement="left"><i class="fa fa-paperclip"></i></a> <strong><font color="#27ae60"><?=$row['training_title']?> <i class="fa fa-check-circle"></i></font></strong></td>
                                <td><strong><font color="#27ae60"><?=$row['training_date']?> <i class="fa fa-check-circle"></i></font></strong></td>
                            </tr>
                        <?php  
                                }
                            }
                        ?>
                        </tbody>
                        <?php
                            }
                        ?>
                    </table>
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