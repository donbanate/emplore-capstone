<?php  
    session_start();
    include '../db_connection/db_conn.php';
    $user = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$user' ";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'employee') {
        echo "<script>window.open('../index.php', '_self')</script>";
    }else{
    include '../db_connection/db_conn.php';
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
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Send Request</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body >
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-primary mb-3">
    <div class="flex-row d-flex">
        <a class="navbar-brand" href="employeeProfile.php" title="Admin"><span class="fa fa-user"></span> <?=$row['first_name']?></a>
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
<div class="container-fluid" id="main">
    <div class="row row-offcanvas row-offcanvas-left">
        <div class="col-md-3 col-lg-2 sidebar-offcanvas" id="sidebar" role="navigation">
            <ul class="nav flex-column pl-1">
                <li class="nav-item"><a class="nav-link" href="index.php">Overview</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="#submenu1" data-toggle="collapse" data-target="#submenu1"><span class="badge badge-light"><?php echo $total; ?></span> Trainings▾</a>
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

            <h1 class="display-5 d-none d-sm-block">
            <i class="fa fa-send-o"></i> Send Training Request
            </h1>

<?php  
  $id = $_SESSION['id'];
  $sql = "SELECT * FROM employees AS E, employee_designations AS D WHERE E.id = D.id AND E.id = '$id' ";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
?> 
            <form action="finalRequest.php" method="POST">            
            <div  class="panel panel-info">
                <div class="alert alert-info"><h2>General Information <i class="fa fa-info-circle"></i></h2></div>
                <div class="panel-body">
                    <h6><font color="blue"><i>Please provide the following information about your training or seminars</i></font></h6>
                    <div class="row">
                    <div class="form-group col-md-4">
                        <label class="control-label"><i class="fa fa-black-tie"></i><strong> Designation</strong></label>
                        <p class="form-control"><?=$row['designated_as']?></p>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label"><strong><i class="fa fa-child"></i> Training Title</strong></label>
                        <input class="form-control" type="text" name="gi_training" placeholder="" value="<?php  if(isset($_SESSION['gi_training'])){ echo $_SESSION['gi_training']; unset($_SESSION['gi_training']);} ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label"><strong><i class="fa fa-calendar"></i> Training Date</strong></label>
                        <input class="form-control" type="date" name="gi_training_date" placeholder="" value="<?php if(isset($_SESSION['gi_training_date'])){ echo $_SESSION['gi_training_date']; unset($_SESSION['gi_training_date']); }?>" required>
                    </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><strong><i class="fa fa-ticket"></i> Endorsement</strong></label>
                        <input class="form-control" type="text" name="gi_endorsement" placeholder="Endorsement" value="<?php if(isset($_SESSION['gi_endorsement'])){
                          echo $_SESSION['gi_endorsement']; unset($_SESSION['gi_endorsement']);} ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><strong><i class="fa fa-university"></i> Sponsoring Institution</strong></label>
                        <input class="form-control" type="text" name="gi_sponsor" value="<?php if(isset($_SESSION['gi_sponsor'])){ echo $_SESSION['gi_sponsor']; unset($_SESSION['gi_sponsor']);}?>" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><strong><i class="fa fa-odnoklassniki"></i> Training Officer</strong></label>
                        <input class="form-control" type="text" name="gi_trainingop" value="<?php if(isset($_SESSION['gi_trainingop'])){echo $_SESSION['gi_trainingop']; unset($_SESSION['gi_trainingop']);}?>" required>
                    </div>
                    <div class="row">
                      <div class="form-group  col-md-6">
                        <label class="control-label"><strong><i class="fa fa-location-arrow"></i> Venue</strong></label>
                        <input class="form-control" type="text" name="gi_venue" placeholder="" value="<?php if(isset($_SESSION['gi_venue'])){ echo $_SESSION['gi_venue']; unset($_SESSION['gi_venue']);}?>" required>
                    </div>
                    <div class="form-group  col-md-6">
                        <label class="control-label"><strong><i class="fa fa-calendar"></i> Return-to-post</strong></label>
                        <input class="form-control" type="date" name="gi_return" value="<?php if(isset($_SESSION['gi_return'])){ echo $_SESSION['gi_return']; unset($_SESSION['gi_return']);}?>" required> 
                    </div>
                    </div>
                    <div class="form-group">
                        <label for="reason" class="control-label"><strong><i class="fa fa-comments"></i> Reason(s) for participation to the seminar/training</strong></label>
                          <select class="form-control" id="reason" name="gi_reason" required="">
                          <option> </option>
                          <option value="Acquire basic skills for the position as newly hired employee" <?php if(isset($_SESSION['gi_reason'])){ if($_SESSION['gi_reason'] == 'Acquire basic skills for the position as newly hired employee'){ echo 'selected'; unset($_SESSION['gi_reason']); }} ?>>Acquire basic skills for the position as newly hired employee</option>
                          <option value="Conduct echo/peer teaching for other member(s)" <?php if(isset($_SESSION['gi_reason'])){ if($_SESSION['gi_reason'] == 'Conduct echo/peer teaching for other member(s)'){ echo 'selected'; unset($_SESSION['gi_reason']); }} ?>>Conduct echo/peer teaching for other member(s)</option>
                          <option value="Acquire new skills due to transfer to another section or promotion" <?php if(isset($_SESSION['gi_reason'])){ if($_SESSION['gi_reason'] == 'Acquire new skills due to transfer to another section or promotion'){ echo 'selected'; unset($_SESSION['gi_reason']); }} ?>>Acquire new skills due to transfer to another section or promotion</option>
                          <option value="Improve performance in current job or function" <?php if(isset($_SESSION['gi_reason'])){ if($_SESSION['gi_reason'] == 'Improve performance in current job or function'){ echo 'selected'; unset($_SESSION['gi_reason']); }} ?>>Improve performance in current job or function</option>
                          <option value="Function as a reason/focal on the newly acquired skills and knowledge" <?php if(isset($_SESSION['gi_reason'])){ if($_SESSION['gi_reason'] == 'Function as a reason/focal on the newly acquired skills and knowledge'){ echo 'selected'; unset($_SESSION['gi_reason']); }} ?>>Function as a reason/focal on the newly acquired skills and knowledge</option>
                          <option value="Complete a related project/assignment" <?php if(isset($_SESSION['gi_reason'])){ if($_SESSION['gi_reason'] == 'Complete a related project/assignment'){ echo 'selected'; unset($_SESSION['gi_reason']); }} ?>>Complete a related project/assignment</option>
                        </select>
                    </div>
                    <div class="form-group">
                      <label class="control-label"><strong><i class="fa fa-comments"></i> Other Reason(s)</strong></label>
                        <textarea class="form-control" name="gi_reason1"><?php if (isset($_SESSION['gi_reason1'])){ echo $_SESSION['gi_reason1']; unset($_SESSION['gi_reason1']);}?></textarea>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6">
                      <label class="control-label"><strong><i class="fa fa-book"></i> Project Title</strong></label>
                      <input class="form-control" type="text" name="gi_project" value="<?php if(isset($_SESSION['gi_project'])){echo $_SESSION['gi_project']; unset($_SESSION['gi_project']);} ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label"><strong><i class="fa fa-calendar"></i> Completion Date</strong></label>
                      <input class="form-control" type="date" name="gi_completion" value="<?php if(isset($_SESSION['gi_completion'])){ echo $_SESSION['gi_completion']; unset($_SESSION['gi_completion']);}?>" required>
                    </div>
                    </div>
                </div>
            </div>
            <p></p>
<!--==============================================================================================-->
            <div class="panel panel-info">
                <div class="alert alert-info"><h2>Projected Incidental Expenses</h2></div>
                <div class="panel-body">
                    <h6><font color="blue"><i>*Please provide the following information about the expenses</i></font></h6>
                    <p></p>
                    <div class="row">
            <div class="form-group col-md-4">
                        <label class="control-label"><strong><i class="fa fa-bus"></i> Total Transporation Expenses</strong></label>
                        <input class="form-control" type="number" name="proj_transpo" min="100" value="<?php if(isset($_SESSION['proj_transpo'])){ echo $_SESSION['proj_transpo']; unset($_SESSION['proj_transpo']);}?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label"><strong><i class="fa fa-bitcoin"></i> Total Cost of Per Diem</strong></label>
                       <input  class="form-control" type="number" name="proj_tcpd" min="100" value="<?php if(isset($_SESSION['proj_tcpd'])){ echo $_SESSION['proj_tcpd']; unset($_SESSION['proj_tcpd']);}?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label"><strong><i class="fa fa-shopping-cart"></i> Seminar Training Fee</strong></label>
                        <input class="form-control" type="number" name="proj_stf" min="100" value="<?php if(isset($_SESSION['proj_stf'])){echo $_SESSION['proj_stf']; unset($_SESSION['proj_stf']);}?>" required>
                    </div>                      
                    </div>
                    <h3 class="alert alert-info">Itenerary</h3>
                    <div>
                      <label class="control-label"><strong><i class="fa fa-location-arrow"></i> Venue</strong></label>
                      <input class="form-control" type="text" name="it_venue" value="<?php if(isset($_SESSION['it_venue'])){ echo $_SESSION['it_venue']; unset($_SESSION['it_venue']);}?>" required>
                    </div>
                    <div class="row">
                    <div class="form-group col-md-6">
                      <label class="control-label"><strong><i class="fa fa-calendar"></i> Date of Departure</strong></label>
                      <input class="form-control" type="date" name="it_ddepart" value="<?php if(isset($_SESSION['it_ddepart'])){ echo $_SESSION['it_ddepart']; unset($_SESSION['it_ddepart']); }?>" required onchange="checkDates();" id="datepicker2">
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label"><strong><i class="fa fa-calendar"></i> Date of Arrival</strong></label>
                      <input class="form-control" type="date" id="datepicker3" name="it_doa" value="<?php if(isset($_SESSION['it_doa'])){ echo $_SESSION['it_doa']; unset($_SESSION['it_doa']);}?>" required>
                    </div>
                    </div>
                    <script type="text/javascript">
                        function checkDates(){
                            document.getElementById("datepicker3").value = document.getElementById("datepicker2").value
                        }
                    </script>
                    <div class="row">
                    <div class="form-group col-md-6">
                      <label class="control-label"><strong><i class="fa fa-bus"></i> Transportation</strong></label>
                      <input class="form-control" type="number" name="it_transpo" min="100" value="<?php if(isset($_SESSION['it_transpo'])){ echo $_SESSION['it_transpo']; unset($_SESSION['it_transpo']);} ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label"><strong><i class="fa fa-bitcoin"></i> Expenses</strong></label>
                      <input class="form-control" type="number" name="it_exp" min="100" value="<?php if(isset($_SESSION['it_exp'])){ echo $_SESSION['it_exp']; unset($_SESSION['it_exp']);}?>" required>
                    </div>  
                    </div>
                </div>
                </div>
<!--==============================================================================================-->
        <div class="center">
            <button type="submit" name="request" class="btn btn-lg btn-success btn-responsive"><span class="fa fa-send"></span> Send Request</button>
        </div>
</form>
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
  </body>
</html>
<?php 
}
?>