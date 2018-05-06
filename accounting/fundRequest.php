  <?php  
    session_start();
    include '../db_connection/db_conn.php';
    $user = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$user' ";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'accounting') {
        echo "<script>window.open('../index.php', '_self');</script>";
    }else{
    $funds = "SELECT * FROM employee_training_needs WHERE is_in_training_needs = '1' AND final_remark = 'checked by the fsd' ";
    $result = $conn->query($funds);
    $countFunds = $result-> num_rows;

    $training = "SELECT * FROM employee_training_needs WHERE is_fund_available = '1' AND is_in_training_needs = '1' AND remark = 'checked' ";
    $result1 = $conn->query($training);
    $countTrainingFunds = $result1-> num_rows;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Fund Requests</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body >
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-primary mb-3">
    <div class="flex-row d-flex">
        <a class="navbar-brand" href="#" title="Admin"><span class="fa fa-user"></span> Budget Officer</a>
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
                <a class="nav-link " href="manual.php" target="_blank">Help <i class="fa fa-question-circle"></i><span class="sr-only">Help</span></a>
            </li>
                <li class="nav-item active">
                <a class="nav-link " href="" data-target="#myModal3" data-toggle="modal">About <i class="fa fa-info-circle"></i><span class="sr-only">About</span></a>
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
                <li class="nav-item"><a class="nav-link " href="index.php">Overview</a></li>
                <li class="nav-item">
                    <a class="nav-link " href="#submenu1" data-toggle="collapse" data-target="#submenu1"><span class="badge badge-primary"><?php echo $countFunds; ?></span> Fund Requests ▾</a>
                    <ul class="list-unstyled flex-column pl-3 collapse" id="submenu1" aria-expanded="false">
                       <li class="nav-item"><a class="nav-link " href="fundRequest.php"><span class="badge badge-dark"><?php echo $countFunds; ?></span> Training Fund Requests</a></li>
                       <li class="nav-item"><a class="nav-link " href="checkedRequest.php">Checked Requests </a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link " href="changePsswd.php">Change Password</a></li>
            </ul>
        </div>
        <!--/col-->

        <div class="col-md-9 col-lg-10 main">

            <!--toggle sidebar button
            <p class="hidden-md-up">
                <button type="button" class="btn btn-primary-outline btn-sm" data-toggle="offcanvas"><i class="fa fa-chevron-left"></i> Menu</button>
            </p>-->

            <h1 class="display-4 d-none d-sm-block">
            <strong><font color="#3498db">Fund Request <i class="fa fa-tasks"></i></font></strong>
            </h1>
            <p class="lead d-none d-sm-block">Please take action to these request(s) soon as possible</p>


            <div class="row mb-3">
                <div class="table-responsive">
                  <form class="pull-right" method="POST" action="fundRequest.php">
                     <div class="form-group col-12">
                      <div class="form-group col-12">
                        <label class="sr-only" for="exampleInputAmount"></label>
                        <div class="input-group col-12">
                          <input name="searchString" class="form-control" id="exampleInputAmount" placeholder="Search here" type="text">
                          <button type="submit" name="btnSearch" class="btn input-group-addon"><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                    </form>
                  <table class="table table-responsive table-bordered col-12">
                      <thead class="thead-dark">
                          <tr>
                              <th>Name of Employee</th>
                              <th>Training Title</th>
                              <th>Training Date</th>
                              <th>Venue</th>
                              <th>Expenses to be Incurred</th>
                              <th>Date of Departure</th>
                              <th colspan="2"><center>Action</center></th>
                          </tr>
                      </thead>
                      <tbody>   
                        <?php  
                            if (!isset($_POST['btnSearch'])) {
                            $sql = "SELECT * FROM employee_training_needs AS N, employees AS E, trainings AS T, iteneraries AS I WHERE N.is_in_training_needs = '1' AND N.final_remark = 'checked by the fsd' AND E.id = N.employee_id AND N.id = T.id AND N.id = I.id ORDER BY N.id DESC";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                if ($row['is_in_training_needs'] == '1') {
                        ?>
                          <tr>
                              <td><?=$row['first_name'] . ' ' .$row['middle_name'] . ' ' .$row['last_name'] . ' '.$row['name_extension']?></td>
                              <td><?=$row['training_title']?></td>
                              <td><?=$row['training_date']?></td>
                              <td><?=$row['venue']?></td>
                              <td><font color="#c0392b"><strong>Php <?=$row['total_expenses_to_be_incurred']?>.00</strong></font></td>
                              <td><?=$row['date_departure']?></td>
                              <td><a data-toggle ="tooltip" title="CLICK TO CHECK FUND" data-placement="left" href="checkfund.php?id=<?=$row['id']?>"><button class="btn btn-outline-success"><i class="fa fa-thumbs-up"></i></button></a></td>
                              <td><a data-toggle ="tooltip" title="CLICK TO UNCHECK FUND" data-placement="left" href="uncheckfund.php?id=<?=$row['id']?>"><button class="btn btn-outline-danger"><i class="fa fa-thumbs-down"></i></button></a></td>
                          </tr>
                        <?php  
                           }
                        }
                      }else{
                        $searchString = addslashes($_POST['searchString']);

                        if ($searchString == '' OR $searchString == ' ') {
                          $sql = "SELECT * FROM employee_training_needs AS N, employees AS E, trainings AS T, iteneraries AS I WHERE N.is_in_training_needs = '1' AND N.final_remark = 'checked by the fsd' AND E.id = N.employee_id AND N.id = T.id AND N.id = I.id ORDER BY N.id DESC"; 
                        }else{
                          $sql = "SELECT * FROM employee_training_needs AS N, employees AS E, trainings AS T, iteneraries AS I WHERE N.is_in_training_needs = '1' AND N.final_remark = 'checked by the fsd' AND E.id = N.employee_id AND N.id = T.id AND N.id = I.id AND (E.first_name LIKE '%$searchString%' OR E.middle_name LIKE '%$searchString%' OR E.last_name LIKE '%$searchString%' OR T.training_title LIKE '%$searchString%' OR T.training_date LIKE '%$searchString%' OR T.venue LIKE '%$searchString%' OR N.total_expenses_to_be_incurred LIKE '%$searchString%' OR I.date_departure LIKE '%$searchString%') ORDER BY N.id DESC";
                        }
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                if ($row['is_fund_available'] == '0' AND $row['is_in_training_needs'] == '1' AND $row['final_remark'] == 'checked by the fsd') {
                        ?>
                          <tr>
                              <td><?=$row['first_name'] . ' ' .$row['middle_name'] . ' ' .$row['last_name'] . ' '.$row['name_extension']?></td>
                              <td><?=$row['training_title']?></td>
                              <td><?=$row['training_date']?></td>
                              <td><?=$row['venue']?></td>
                              <td><font color="#c0392b"><strong>Php <?=$row['total_expenses_to_be_incurred']?>.00</strong></font></td>
                              <td><?=$row['date_departure']?></td>
                              <td><a data-toggle ="tooltip" title="CLICK TO CHECK FUND" data-placement="left" href="checkfund.php?id=<?=$row['id']?>"><button class="btn btn-outline-success"><i class="fa fa-thumbs-up"></i></button></a></td> 
                              <td><a data-toggle ="tooltip" title="CLICK TO UNCHECK FUND" data-placement="left"  href="uncheckfund.php?id=<?=$row['id']?>"><button class="btn btn-outline-danger"><i class="fa fa-thumbs-down"></i></button></a></td>
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
                <p><strong>EMPLORE</strong> is a computerized Employee Trainings and Seminars Record System. The system  keeps records of trainings and seminars attended by the employees of Cagayan State University Gonzaga Campus. It enables a more efficient process of sending and approving training and seminars. This is a user friendly application with its updated and easy-to-use features.</p>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary-outline" data-dismiss="modal">OK</button> -->
            </div>
        </div>
    </
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