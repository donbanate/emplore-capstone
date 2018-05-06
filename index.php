<?php  
	session_start();
  include('db_connection/db_conn.php');
	if (isset($_SESSION['id'])) {
    $id = addslashes($_SESSION['id']);
    $sql1 = "SELECT * FROM users WHERE id='$id'";
    $result1 = $conn->query($sql1);
    $fetch1 = $result1->fetch_array();
		if ($fetch1['type'] == 'admin') {
      echo '<script>window.open("admin/index.php", "_self")</script>';            
    }elseif ($fetch1['type'] == 'coordinator') {
      echo '<script>window.open("coordinator/index.php", "_self")</script>';
    }elseif ($fetch1['type'] == 'accounting') {
      echo '<script>window.open("accounting/index.php", "_self")</script>';
    }elseif($fetch1['type'] == 'pending'){
      echo '<script>window.open("temp_user.php", "_self")</script>';
    }else{
      echo '<script>window.open("employee/index.php", "_self")</script>';
    }
	}else{
	include('bootstrapIncludes.php');
?>
<!DOCTYPE html> 
<html>
<head>
	<title>Emplore</title>
<link rel="icon" type="image/png" href="logo/logo.png">
	<style type="text/css">
		.login-page {
  width: 360px;
  padding: 2% 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: #FFF;
  max-width: 360px;
  margin: 0 auto 100px;
  padding: 40px;
  text-align: center;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.form input {
  font-family: "Roboto", sans-serif;
  outline: 0;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 14px;
}

.form .message {
  margin: 15px 0 0;
  color: #b3b3b3;
  font-size: 12px;
}
.form .message a {
  color: #96281B;
  text-decoration: none;
}
.form .register-form {
  display: none;
}
.container {
  position: relative;
  z-index: 1;
  max-width: 300px;
  margin: 0 auto;
}
.container:before, .container:after {
  content: "";
  display: block;
  clear: both;
}
.container .info {
  margin: 50px auto;
  text-align: center;
}
.container .info h1 {
  margin: 0 0 15px;
  padding: 0;
  font-size: 36px;
  font-weight: 300;
  color: #1a1a1a;
}
.container .info span {
  color: #4d4d4d;
  font-size: 12px;
}
.container .info span a {
  color: #000000;
  text-decoration: none;
}
.container .info span .fa {
  color: #EF3B3A;
}
.logo{
  margin-bottom: 5px;
}

body {
  background: #009688; /* fallback for old browsers */
    
}
#map { height: 400px; width: 100%; }
	</style>
</head>
<body>
<div class="login-page">
  <div class="form">
  <img class="logo" src="logo/final.png" style="width: 200;height:100;">
    <form class="login-form" action="" method="POST">

      <input type="text" name="idnum" placeholder="Your ID number" autofocus required/>
      <input type="password" name="psswd" placeholder="Password" required/>
       <button type="submit" name="login" class="btn btn-primary btn-block"><span class="fa fa-sign-in"></span> Login</button> 
      <!-- <input class="btn-primary" type="submit"  value="login"> -->
      <br>
      <p>Not registered? <a href="register.php">Register here!</a></p>
      <p>Copyright &copy; <?php echo date('Y'); ?></p>
      <p><a class="btn btn-info btn-sm" data-target="#myModal" data-toggle="modal" href=""><i class="fa fa-info-circle"></i></a></p>
    </form>
  </div>
</div>
<script type="text/javascript">
	$('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});
</script>
<?php 
								
			if (isset($_POST['login'])) {

				$idnum = addslashes($_POST['idnum']);
				$password = addslashes(sha1(md5($_POST['psswd'])));

				$sql = "SELECT * FROM users WHERE username='$idnum' AND password='$password'";
				$result = $conn->query($sql);
				$count = $result->num_rows;
				$fetch = $result->fetch_array();

				if ($count == 0) {
					echo '<script>alert("Wrong username or password!");</script>';
					echo '<script>window.open("index.php", "_self")</script>';
				}else{
					$_SESSION['id'] = $fetch['id'];

					if ($fetch['type'] == 'admin') {
						echo '<script>alert("Welcome Admin!");</script>';
						echo '<script>window.open("admin/index.php", "_self")</script>';						
					}elseif ($fetch['type'] == 'coordinator') {
						echo '<script>alert("Welcome FSD Coordinator!");</script>';
						echo '<script>window.open("coordinator/index.php", "_self")</script>';
					}elseif ($fetch['type'] == 'accounting') {
						echo '<script>alert("Welcome Budget Officer!");</script>';
						echo '<script>window.open("accounting/index.php", "_self")</script>';
					}elseif($fetch['type'] == 'pending'){
              echo '<script>alert("Welcome Employee!");</script>';
              echo '<script>window.open("temp_usr.php", "_self")</script>';
          }else{
						echo '<script>alert("Welcome Employee!");</script>';
						echo '<script>window.open("employee/index.php", "_self")</script>';
					}
				}
			}
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><strong><font color="#446CB3">Emplore <i class="fa fa-info-circle"></i></font></strong></h4>
            </div>
            <div class="modal-body">
                <p><strong>EMPLORE</strong> is a computerized Employee Trainings and Seminars Record System. The system  keeps records of trainings and seminars attended by the employees of Cagayan State University Gonzaga Campus. It enables a more efficient process of sending and approving training and seminars. This is a user friendly application with its updated and easy-to-use features.</p>
                <br>
                <h5><strong>Where we at<span></span>? </strong></h5>
                <p><i class="fa fa-street-view">
				</i> Cagayan State University Gonzaga Campus <br><i class="fa fa-street-view">
				</i> Flourishing, Gonzaga Cagayan Valley</p>
                <div id="map"></div>
                <script type="text/javascript">
                	function initMap() { var uluru = {lat: 18.252371, lng: 122.000497}; var map = new google.maps.Map(document.getElementById('map'), { zoom: 15, center: uluru }); var marker = new google.maps.Marker({ position: uluru, map: map }); }
                </script>
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDG2dunjBkdRJGS8tJh-v06ZwQBt0RCOk&callback=initMap"> </script>
                <br>
                <div class="table-resposive">
                	<table class="table table-hover">
                    <h4>The Developers <i class="fa fa-bug"></i></h4>
                    <tr>
                      <td><img src="logo/don.png" class="rounded-circle" width="80" height="80"></td>
                      <td><span><strong><i class="fa fa-coffee"></i> Lyndon P. Banate</strong></span><p><i class="fa fa-code"></i> Programmer<br><span> @meizterdon</span></p></td>
                    </tr>
                    <tr>
                      <td><img src="logo/noli.png" class="rounded-circle" width="80" height="80"></td>
                      <td><span><strong><i class="fa fa-coffee"></i> Noli B. Oandasan</strong></span><p><i class="fa fa-code-fork"></i> System Analyst<br><span> @nolioandasan</span></p></td>
                    </tr>
                    <tr>
                      <td><img src="logo/carlo.jpg" class="rounded-circle" width="80" height="80"></td>
                      <td><span><strong><i class="fa fa-coffee"></i> Krys Carlo P. Pajamutan</strong></span><p><i class="fa fa-file-zip-o"></i> Document Manager<br><span> @krys_carlo</span></p></td>
                    </tr>
                    <tr>
                      <td><img src="logo/jun.jpg" class="rounded-circle" width="80" height="80"></td>
                      <td><span><strong><i class="fa fa-coffee"></i> Lodrigo P. Sotelo Jr.</strong></span><p><i class="fa fa-sticky-note-o"></i> Documentator<br><span> @junjun</span></p></td>
                    </tr>
                  </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="bootstrap-v3/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    //Disable full page
    $("body").on("contextmenu",function(e){
        return false;
    });
    
    //Disable part of page
    $("#id").on("contextmenu",function(e){
        return false;
    });
});

(function () {
    if (!$('body').hasClass('debug_mode')) {
        var _z = console;
        Object.defineProperty(window, "console", {
            get: function () {
                if ((window && window._z && window._z._commandLineAPI) || {}) {
                    throw "Nice trick! but not permitted!";
                }
                return _z;
            },
            set: function (val) {
                _z = val;
            }
        });
    }
})();
</script>
</html>
<?php
}
?>

