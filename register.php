<?php  
    include 'bootstrapIncludes.php';
    include 'db_connection/db_conn.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="strengths/script.js"></script>
    <link rel="stylesheet" type="text/css" href="strengths/meters.css">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link href="libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="css/styles.css" />
<script>
  $( function() {
    $( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true,
    });
  } );
  </script> 
  <style type="text/css">
      h1 {
    font-size: 250%;
}
  </style>
</head>
<body>
    <!-- Traps -->
<?php  
    if (isset($_POST['register'])) {
            #Profile
                $fname      = addslashes(ucwords(strtolower(trim($_POST['fname']))));
                $midname    = addslashes(ucwords(strtolower(trim($_POST['midname']))));
                $lname      = addslashes(ucwords(strtolower(trim($_POST['lname']))));
                $sex        = addslashes($_POST['sex']);
                $ext        = $_POST['extent'];

                $bplace     = addslashes(ucwords(strtolower(trim($_POST['bplace']))));
                $check1      = addslashes(trim($_POST['bdate']));
                $bday      = addslashes(trim($_POST['bdate']));
                $barangay   = addslashes(ucwords(strtolower(trim($_POST['barangay']))));
                $municipality   = addslashes(ucwords(strtolower(trim($_POST['municipality']))));

                
                    //date in mm/dd/yyyy format; or it can be in other formats as well
                  $bdate = $check1;

                  //explode the date to get month, day and year
                  $bdate = explode("/", $bdate);

                  //get age from date or birthdate
                  $age = (date("md", date("U", mktime(0, 0, 0, $bdate[0], $bdate[1], $bdate[2]))) > date("md")
                    ? ((date("Y") - $bdate[2]) - 1)
                    : (date("Y") - $bdate[2]));

                $_SESSION['fname'] = $fname;
                $_SESSION['midname'] = $midname;
                $_SESSION['lname'] = $lname;
                $_SESSION['extent'] = $ext;
                $_SESSION['bplace'] = $bplace;
                $_SESSION['bdate'] = $bday;
                $_SESSION['barangay'] = $barangay;
                $_SESSION['municipality'] = $municipality;
            ///////////////////////////////////////////////////////////////////////////
            #Password
                $idnum      = addslashes(trim($_POST['idnum']));
                $psswd      = addslashes(sha1(md5($_POST['psswd'])));
                $cpsswd     = addslashes(sha1(md5($_POST['cpsswd'])));

                $_SESSION['idnum'] = $idnum;
                $_SESSION['psswd'] = $_POST['psswd'];
                $_SESSION['cpsswd'] = $_POST['cpsswd'];
            ///////////////////////////////////////////////////////////////////////////
            #Designations
                $college    = addslashes($_POST['college']);

                $_SESSION['college'] = $college;
            ///////////////////////////////////////////////////////////////////////////    
            #Contact
                $contact    = addslashes(trim($_POST['contact']));

                $_SESSION['contact'] = $contact;

            $check = $conn -> query("SELECT * FROM users WHERE username = '$idnum'");
            $counter = $check -> num_rows;

        if ($counter >= 1) {     
    ?>
        <div class="alert alert-danger" role="alert">
            <strong>Oh snap!</strong> ID number was already taken.
        </div>
    <?php  
    }elseif ($psswd != $cpsswd || strlen($psswd) <= 7 || strlen($cpsswd) <= 7) {
    ?>
        <div class="alert alert-warning" role="alert">
            <strong>Oops!</strong> Password is too short or password don't match.
        </div>
    <?php  
    }elseif (!ctype_digit($contact)) {
    ?>
        <div class="alert alert-warning" role="alert">
            <strong>Oops!</strong> Your contacts contains numeric characters.
        </div>
    <?php  
    }elseif (strlen($contact) <= 10 || strlen($contact) >= 12) {
    ?>
        <div class="alert alert-warning" role="alert">
            <center><strong>Oops!</strong> Invalid contact info.</center>
        </div>
    <?php 
    }else{
        $sql = "INSERT INTO users(username, password, type) VALUES ('$idnum', '$psswd', 'pending');";
        $sql .= "INSERT INTO employees(first_name, middle_name, last_name, name_extension, sex, age, birth_date, birth_place, barangay, address, mobile_no) VALUES ('$fname', '$midname', '$lname', '$ext', '$sex', '$age', '$bday', '$bplace', '$barangay', '$municipality', '$contact');";
        $sql .="INSERT INTO employee_designations(designated_as, department) VALUES ('requested', '$college');";

        if ($conn->multi_query($sql) === TRUE) {
            echo '<script>alert("Registration sent!");</script>';
                echo '<script>window.open("index.php", "_self");</script>';
            }else{
                    echo "Error: " . $sql . "<br>" . $conn->error;
            }

        unset($_SESSION['fname']);
        unset($_SESSION['midname']);
        unset($_SESSION['lname']);
        unset($_SESSION['ext']);
        unset($_SESSION['bdate']);
        unset($_SESSION['bplace']);
        unset($_SESSION['barangay']);
        unset($_SESSION['municipality']);
        unset($_SESSION['idnum']);
        unset($_SESSION['psswd']);
        unset($_SESSION['cpsswd']);
        unset($_SESSION['college']);
        unset($_SESSION['contact']);

        echo '<script>window.open("register.php", "_self");</script>';
        }
    }
?>
<!-- End Traps -->
<!-- Form -->

<div class="col-12">
   <div class="card">
    <div class="card-body">
    <center><h1 class="jumbotron"><font color="#2c3e50"><b>Register</b></font></h1></center>
    <form method="POST" action="register.php">
    <div class="form-group">
        <div class="row">
          <div class="col-6">
            <label>ID Number</label>
            <input type="text" class="form-control" autofocus="" name="idnum" value="<?php if(isset($_SESSION['idnum'])){echo $_SESSION['idnum']; unset($_SESSION['idnum']);} ?>" required="" placeholder="15-00001">
          </div>
          <div class="col-6">
            <label>Department</label>
            <select class="form-control" name="college" required="">
                <option selected="" disabled>--</option>
                <option value="College of Busines Entrepreneurship and Accountancy" <?php if(isset($_SESSION['college'])){ if($_SESSION['college'] == 'College of Busines Entrepreneurship and Accountancy'){echo 'selected'; unset($_SESSION['college']);}} ?>>College of Busines Entrepreneurship and Accountancy</option>
                <option value="College of Infomation and Computing Sciences" <?php if(isset($_SESSION['college'])){ if($_SESSION['college'] == 'College of Infomation and Computing Sciences'){ echo 'selected'; unset($_SESSION['college']); }} ?>>College of Infomation and Computing Sciences</option>
                <option value="College of Criminal Justice Administration" <?php if(isset($_SESSION['college'])){ if($_SESSION['college'] == 'College of Criminal Justice Administration'){ echo 'selected'; unset($_SESSION['college']); }} ?>>College of Criminal Justice Administration</option>
                <option value="College of Teacher Education" <?php if(isset($_SESSION['college'])){ if($_SESSION['college'] == 'College of Teacher Education'){ echo 'selected'; unset($_SESSION['college']); }} ?>>College of Teacher Education</option>
                <option value="College of Hospitality and Industry Management" <?php if(isset($_SESSION['college'])){ if($_SESSION['college'] == 'College of Hospitality and Industry Management'){ echo 'selected'; unset($_SESSION['college']); }} ?>>College of Hospitality and Industry Management</option>
                <option value="College of Agriculture" <?php if(isset($_SESSION['college'])){ if($_SESSION['college'] == 'College of Agriculture'){ echo 'selected'; unset($_SESSION['college']); }} ?>>College of Agriculture</option>
            </select>
          </div>
    </div>
    </div>
    <p></p>
    <div class="form-group">
        <div class="row">
          <div class="col-3">
            <label>Firstname</label>
            <input type="text" class="form-control" name="fname" value="<?php if(isset($_SESSION['fname'])){ echo $_SESSION['fname']; unset($_SESSION['fname']);}?>" required="" placeholder="Juan">
          </div>
          <div class="col-3">
            <label>Middlename</label>
            <input type="text" class="form-control" name="midname" value="<?php if(isset($_SESSION['midname'])){ echo $_SESSION['midname']; unset($_SESSION['midname']);}?>" required="" placeholder="Dela">
          </div>
          <div class="col-3">
            <label>Lastname</label>
            <input type="text" class="form-control" name="lname" value="<?php if(isset($_SESSION['lname'])){ echo $_SESSION['lname']; unset($_SESSION['lname']);}?>"  required="" placeholder="Cruz">
        </div>
        <div class="col-3">
            <label>Name extension</label>
            <select class="form-control" name="extent">
                <option></option>
                <option value="Jr." <?php if(isset($_SESSION['extent'])){ if($_SESSION['extent'] == 'Jr.'){ echo 'selected'; unset($_SESSION['extent']); }} ?>>Jr.</option>
                <option value="Sr." <?php if(isset($_SESSION['extent'])){ if($_SESSION['extent'] == 'Sr.'){ echo 'selected'; unset($_SESSION['extent']); }} ?>>Sr.</option>
                <option value="I" <?php if(isset($_SESSION['extent'])){ if($_SESSION['extent'] == 'I'){ echo 'selected'; unset($_SESSION['extent']); }} ?>>I</option>
                <option value="II" <?php if(isset($_SESSION['extent'])){ if($_SESSION['extent'] == 'II'){ echo 'selected'; unset($_SESSION['extent']); }} ?>>II</option>
                <option value="III" <?php if(isset($_SESSION['extent'])){ if($_SESSION['extent'] == 'III'){ echo 'selected'; unset($_SESSION['extent']); }} ?>>III</option>
                <option value="IV" <?php if(isset($_SESSION['extent'])){ if($_SESSION['extent'] == 'IV'){ echo 'selected'; unset($_SESSION['extent']); }} ?>>IV</option>
            </select>
        </div>
    </div>
    </div>
    <p></p>
    <div class="form-group">
        <div class="row">
          <div class="col-6">
            <label>Password</label>
            <input type="password" name="psswd" required="" class="form-control" value="<?php if(isset($_SESSION['psswd'])){echo $_SESSION['psswd']; unset($_SESSION['psswd']);} ?>">
            <div id="pswd_info" class="form-control">
                <h6>When composing a password,please consider this:</h6>
            <ul>
                <li id="letter" class="invalid">At least <strong>one letter</strong></li>
                <li id="capital" class="invalid">At least <strong>one capital letter</strong></li>
                <li id="number" class="invalid">At least <strong>one number</strong></li>
                <li id="length" class="invalid">Be at least <strong>8 characters</strong></li>
            </ul>
            </div>
        </div>
          <div class="col-6">
            <label>Confirm</label>
            <input type="password" name="cpsswd" required="" class="form-control" placeholder="Confirm" value="<?php if(isset($_SESSION['cpsswd'])){echo $_SESSION['cpsswd']; unset($_SESSION['cpsswd']);} ?>">
          </div>
    </div>
    </div>
    <p></p>
    <div class="form-group">
        <div class="row col-12">
        <label>Sex</label>
        <div class="form-check form-check-inline col-12">
          <label class="form-check-label">
            <input class="form-check-input" checked="" type="radio" name="sex" id="inlineRadio1" value="Male"> Male
          </label>
        </div>
        <div class="form-check form-check-inline col-12">
          <label class="form-check-label">
            <input class="form-check-input"  type="radio" name="sex" id="inlineRadio2" value="Female"> Female
          </label>
        </div>
    </div>
    </div>
    <p></p>
    <div class="row">
          <div class="col-6">
            <label>Barangay</label>
            <input type="text" class="form-control" required="" name="barangay" placeholder="Flourishing" value="<?php if(isset($_SESSION['barangay'])){echo $_SESSION['barangay']; unset($_SESSION['barangay']);} ?>">
          </div>
          <div class="col-6">
            <label>Municipality</label>
            <select class="form-control" required="" name="municipality">
                <option selected="" disabled>--</option>
                <option value="Gonzaga Cagayan Valley" <?php if(isset($_SESSION['municipality'])){ if($_SESSION['municipality'] == 'Gonzaga Cagayan Valley'){ echo 'selected'; unset($_SESSION['municipality']); }} ?>>Gonzaga Cagayan Valley</option>
                <option value="Sta. Ana Cagayan Valley" <?php if(isset($_SESSION['municipality'])){ if($_SESSION['municipality'] == 'Sta. Ana Cagayan Valley'){ echo 'selected'; unset($_SESSION['municipality']); }} ?>>Sta. Ana Cagayan Valley</option>
                <option value="Sta. Teresita Cagayan Valley" <?php if(isset($_SESSION['municipality'])){ if($_SESSION['municipality'] == 'Sta. Teresita Cagayan Valley'){ echo 'selected'; unset($_SESSION['municipality']); }} ?>>Sta. Teresita Cagayan Valley</option>
                <option value="Buguey Cagayan Valley" <?php if(isset($_SESSION['municipality'])){ if($_SESSION['municipality'] == 'Buguey Cagayan Valley'){ echo 'selected'; unset($_SESSION['municipality']); }} ?>>Buguey Cagayan Valley</option>
                <option value="Aparri Cagayan Valley" <?php if(isset($_SESSION['municipality'])){ if($_SESSION['municipality'] == 'Aparri Cagayan Valley'){ echo 'selected'; unset($_SESSION['municipality']); }} ?>>Aparri Cagayan Valley</option>
            </select>
          </div>
    </div>
    <p></p>
    <div class="row">
        <div class="col-6">
            <label>Birthdate</label>
            <input type="date" required="" class="form-control" name="bdate" id="datepicker" placeholder="MM/DD/YYYY" value="<?php if(isset($_SESSION['bdate'])){ echo $_SESSION['bdate']; unset($_SESSION['bdate']); } ?>">
        </div>
        <div class="col-6">
            <label>Birthplace</label>
            <input type="text" class="form-control" required="" name="bplace" value="<?php if(isset($_SESSION['bplace'])){ echo $_SESSION['bplace']; unset($_SESSION['bplace']); } ?>" placeholder="Gonzaga Cagayan Valley">
        </div>
    </div>
    <p></p>
    <div class="row">
        <div class="col-6">
            <label>Contact Number</label>
            <input type="number" class="form-control" required="" min="0" name="contact" value="<?php if(isset($_SESSION['contact'])){ echo $_SESSION['contact']; unset($_SESSION['contact']); } ?>" placeholder="09xxxxxxxxx">
        </div>
    </div>
    <p></p>
    <div class="row col-12">
        <button type="submit" name="register" class="btn btn-success">Register</button>
        <a class="btn" href="index.php">BACK</a>
    </div>
</form>
   </div>
 </div>  
</div>
<!-- EndForm -->
</body>
<!-- <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
</html>
<script type="text/javascript" src="bootstrap-v3/jquery.min.js"></script> -->
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