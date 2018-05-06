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
    $funds = "SELECT * FROM employee_training_needs WHERE is_fund_available = '0' AND is_in_training_needs = '1' AND final_remark = 'checked by the fsd' ";
    $result = $conn->query($funds);
    $countFunds = $result-> num_rows;

     $training = "SELECT * FROM employee_training_needs WHERE final_remark = 'disapproved' OR final_remark = 'approved' ";
    $result1 = $conn->query($training);
    $countTrainingFunds = $result1-> num_rows;

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"> 
    <title>Change Password</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="stylesheet" href="password_strength.css" />  
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
                <a class="nav-link" href="manual.php" target="_blank">Help <i class="fa fa-question-circle"></i><span class="sr-only">Help</span></a>
            </li>
                <li class="nav-item active">
                <a class="nav-link" data-target="#myModal3" data-toggle="modal">About <i class="fa fa-info-circle"></i><span class="sr-only">About</span></a>
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
            Change Password
        </h1>	
            <?php  
            	if (isset($_POST['update'])) {
					$id = addslashes($_SESSION['id']);
					$check = "SELECT * FROM users WHERE id='$id' ";
					$result = $conn->query($check);
					$row = $result->fetch_assoc();

					
					$lpsswd = sha1(md5($_POST['old_psswd']));
					$npsswd = sha1(md5($_POST['new_psswd']));
					$cpsswd = sha1(md5($_POST['cpsswd']));

					if ($row['password'] != $lpsswd OR $npsswd !== $cpsswd){
			?>
					<div class="alert alert-warning">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Warning!</strong> Wrong password.
				</div>
			<?php
					}else{
						$update = "UPDATE users SET password='$npsswd' WHERE id='$id' ";

						if ($conn->query($update) === TRUE) {
			?>
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
					    <strong>Success!</strong> Your password has been updated.
					</div>
			<?php	
						}
					}
				}
            ?>

            <?php
				$id = $_SESSION['id'];
				$sql = "SELECT * FROM users AS U, employees AS E WHERE U.id = E.id AND U.id='$id' ";
				$result = $conn->query($sql);
				if ($result-> num_rows < 0) {
					echo "<script>alert('Something went wrong!');</script>";	
				}else{
			?>
            <p class="lead d-none d-sm-block">Please, remember to keep your password strong.</p>
            <div class="row mb-3">
          
        <br>

          <form action="changePsswd.php" method="POST" class="form-horizontal">
					<label>OLD Password</label>
					<input type="Password" class="form-control"" name="old_psswd" placeholder="OLD Password" data-toggle="password"
               data-placement="before" autofocus="" required="">
					<p></p>
					<label>New Password</label>
					<input type="Password" class="form-control" name="new_psswd" placeholder="New Password" data-toggle="password"
               data-placement="before" id="pass" required="">
                    <div id="meter_wrapper">
                    <div id="meter"></div>
                    <b><span id="pass_type"></span></b>
                </div>
					<p></p>
					<div class="form-group">
                    <label>Confirm Password</label>
                    <input type="Password" class="form-control" name="cpsswd" placeholder="Confirm New Password" data-toggle="password"
               data-placement="before" required="">               
                    </div>
					<p></p>
					<input type="hidden" name="id" value="<?=$id?>">
					<button type="submit" class="btn btn-primary" onclick="return confirm('Do you really want to change your password?')" name="update"><i class="fa fa-gears"></i> Update</button>
				</form>
			<?php 
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
    <script src="../bootstrap-v3/bootstrap-show-password.js"></script>

    <script>
    $(function() {
        $('#password').password().on('show.bs.password', function(e) {
            $('#eventLog').text('On show event');
            $('#methods').prop('checked', true);
        }).on('hide.bs.password', function(e) {
                    $('#eventLog').text('On hide event');
                    $('#methods').prop('checked', false);
                });
        $('#methods').click(function() {
            $('#password').password('toggle');
        });
    });
</script>
<script type="text/javascript">
$(document).ready(function(){
 $("#pass").keyup(function(){
  check_pass();
 });
});

function check_pass()
{
 var val=document.getElementById("pass").value;
 var meter=document.getElementById("meter");
 var no=0;
 if(val!="")
 {
  // If the password length is less than or equal to 6
  if(val.length<=8)no=1;

  // If the password length is greater than 6 and contain any lowercase alphabet or any number or any special character
  if(val.length>8 && (val.match(/[a-z]/) || val.match(/\d+/) || val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)))no=2;

  // If the password length is greater than 6 and contain alphabet,number,special character respectively
  if(val.length>8 && ((val.match(/[a-z]/) && val.match(/\d+/)) || (val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) || (val.match(/[a-z]/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))))no=3;

  // If the password length is greater than 6 and must contain alphabets,numbers and special characters
  if(val.length>8 && val.match(/[a-z]/) && val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))no=4;

  if(no==1)
  {
   $("#meter").animate({width:'50px'},300);
   meter.style.backgroundColor="#F22613";
   document.getElementById("pass_type").innerHTML="Very Weak";
  }

  if(no==2)
  {
   $("#meter").animate({width:'100px'},300);
   meter.style.backgroundColor="#F9690E";
   document.getElementById("pass_type").innerHTML="Weak";
  }

  if(no==3)
  {
   $("#meter").animate({width:'150px'},300);
   meter.style.backgroundColor="#F39C12";
   document.getElementById("pass_type").innerHTML="Good";
  }

  if(no==4)
  {
   $("#meter").animate({width:'200px'},300);
   meter.style.backgroundColor="#00e640";
   document.getElementById("pass_type").innerHTML="Strong";
  }
 }

 else
 {
  meter.style.backgroundColor="white";
  document.getElementById("pass_type").innerHTML="";
 }
}
</script>

  </body>
</html>
<?php 
}
?>