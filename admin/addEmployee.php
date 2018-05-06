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
        include 'bootstrapIncludes.php';
         
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../datepicker/themes/jquery-ui.css">
    <link rel="stylesheet" href="../datepicker/demos/style.css">
    
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styles.css" />
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
    if (isset($_POST['add'])) {
            #Profile
                $fname      = addslashes(ucwords(strtolower(trim($_POST['fname']))));
                $midname    = addslashes(ucwords(strtolower(trim($_POST['midname']))));
                $lname      = addslashes(ucwords(strtolower(trim($_POST['lname']))));
                $sex        = addslashes($_POST['sex']);
                $ext        = $_POST['extent'];
                $desig      = $_POST['designation'];

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
                $_SESSION['designation'] = $desig;
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
        $sql = "INSERT INTO users(username, password, type) VALUES ('$idnum', '$psswd', 'employee');";
        $sql .= "INSERT INTO employees(first_name, middle_name, last_name, name_extension, sex, age, birth_date, birth_place, barangay, address, mobile_no) VALUES ('$fname', '$midname', '$lname', '$ext', '$sex', '$age', '$bday', '$bplace', '$barangay', '$municipality', '$contact');";
        $sql .="INSERT INTO employee_designations(designated_as, department) VALUES ('$desig', '$college');";

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
        unset($_SESSION['designation']);
        unset($_SESSION['bdate']);
        unset($_SESSION['bplace']);
        unset($_SESSION['barangay']);
        unset($_SESSION['municipality']);
        unset($_SESSION['idnum']);
        unset($_SESSION['psswd']);
        unset($_SESSION['cpsswd']);
        unset($_SESSION['college']);
        unset($_SESSION['contact']);

        echo '<script>window.open("addEmployee.php", "_self");</script>';
        }
    }
?>
<!-- End Traps -->
<!-- Form -->

<div class="col-12">
   <div class="card">
    <div class="card-body">
    <center><h1 class="jumbotron"><font color="#2c3e50"><b><i class="fa fa-plus"></i> ADD EMPLOYEE</b></font></h1></center>
    <form method="POST" action="addEmployee.php">
    <div class="form-group">
        <div class="row">
          <div class="col-6">
            <label><strong><i class="fa fa-credit-card"></i> ID Number</strong></label>
            <input type="text" class="form-control" autofocus="" name="idnum" value="<?php if(isset($_SESSION['idnum'])){echo $_SESSION['idnum']; unset($_SESSION['idnum']);} ?>" required="" placeholder="15-00001">
          </div>
          <div class="col-6">
            <label><strong><i class="fa fa-building"></i> Department</strong></label>
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
            <label><strong><i class="fa fa-user"></i> Firstname</strong></label>
            <input type="text" class="form-control" name="fname" value="<?php if(isset($_SESSION['fname'])){ echo $_SESSION['fname']; unset($_SESSION['fname']);}?>" required="" placeholder="Juan">
          </div>
          <div class="col-3">
            <label><strong><i class="fa fa-user"></i> Middlename</strong></label>
            <input type="text" class="form-control" name="midname" value="<?php if(isset($_SESSION['midname'])){ echo $_SESSION['midname']; unset($_SESSION['midname']);}?>" required="" placeholder="Dela">
          </div>
          <div class="col-3">
            <label><strong><i class="fa fa-user"></i> </strong>Lastname</label>
            <input type="text" class="form-control" name="lname" value="<?php if(isset($_SESSION['lname'])){ echo $_SESSION['lname']; unset($_SESSION['lname']);}?>"  required="" placeholder="Cruz">
        </div>
        <div class="col-3">
            <label><strong><i class="fa fa-user"></i> Name extension</strong></label>
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
            <label><strong><i class="fa fa-lock"></i> Password</strong></label>
            <input type="password" name="psswd" required="" class="form-control" value="<?php if(isset($_SESSION['psswd'])){echo $_SESSION['psswd']; unset($_SESSION['psswd']);} ?>">
        </div>
          <div class="col-6">
            <label><strong><i class="fa fa-lock"></i> Confirm</strong></label>
            <input type="password" name="cpsswd" required="" class="form-control" placeholder="Confirm" value="<?php if(isset($_SESSION['cpsswd'])){echo $_SESSION['cpsswd']; unset($_SESSION['cpsswd']);} ?>">
          </div>
    </div>
    </div>
    <p></p>
    <div class="form-group">
        <div class="row col-12">
        <label><strong><i class="fa fa-group"></i> Sex</strong></label>
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
            <label><strong><i class="fa fa-road"></i> Barangay</strong></label>
            <input type="text" class="form-control" required="" name="barangay" placeholder="Flourishing" value="<?php if(isset($_SESSION['barangay'])){echo $_SESSION['barangay']; unset($_SESSION['barangay']);} ?>">
          </div>
          <div class="col-6">
            <label><strong><i class="fa fa-institution"></i> Municipality</strong></label>
            <select class="form-control" required="" name="municipality">
                <option selected="" disabled>--</option>
                <option value="Gonzaga Cagayan Valley" <?php if(isset($_SESSION['municipality'])){ if($_SESSION['municipality'] == 'Gonzaga Cagayan Valley'){ echo 'selected'; unset($_SESSION['municipality']); }} ?>>Gonzaga Cagayan Valley</option>
                <option value="Sta. Ana Cagayan Valley" <?php if(isset($_SESSION['municipality'])){ if($_SESSION['municipality'] == 'Sta. Ana Cagayan Valley'){ echo 'selected'; unset($_SESSION['municipality']); }} ?>>Sta. Ana Cagayan Valley</option>
                <option value="Sta. Teresita Cagayan Valley" <?php if(isset($_SESSION['municipality'])){ if($_SESSION['municipality'] == 'Sta. Teresita Cagayan Valley'){ echo 'selected'; unset($_SESSION['municipality']); }} ?>>Sta. Teresita Cagayan Valley</option>
                <option value="Buguey Cagayan Valley" <?php if(isset($_SESSION['municipality'])){ if($_SESSION['municipality'] == 'Buguey Cagayan Valley'){ echo 'selected'; unset($_SESSION['municipality']); }} ?>>Buguey Cagayan Valley</option>
                <option value="Aparri Cagayan Valley" 
                <?php if(isset($_SESSION['municipality'])){ 
                    if($_SESSION['municipality'] == 'Aparri Cagayan Valley'){ 
                        echo 'selected'; unset($_SESSION['municipality']); 
                    }
                } 
                ?>>Aparri Cagayan Valley</option>
            </select>
          </div>
    </div>
    <p></p>
    <div class="row">
        <div class="col-6">
            <label><strong><i class="fa fa-calendar"></i> Birthdate</strong></label>
            <input type="text" required="" class="form-control" name="bdate" id="datepicker" placeholder="MM/DD/YYYY" value="<?php if(isset($_SESSION['bdate'])){ echo $_SESSION['bdate']; unset($_SESSION['bdate']); } ?>">
        </div>
        <div class="col-6">
            <label><strong><i class="fa fa-location-arrow"></i> Birthplace</strong></label>
            <input type="text" class="form-control" required="" name="bplace" value="<?php if(isset($_SESSION['bplace'])){ echo $_SESSION['bplace']; unset($_SESSION['bplace']); } ?>" placeholder="Gonzaga Cagayan Valley">
        </div>
    </div>
    <p></p>
    <div class="row">
        <div class="col-6">
            <label><strong><i class="fa fa-phone"></i> Contact Number</strong></label>
            <input type="number" class="form-control" required="" min="0" name="contact" value="<?php if(isset($_SESSION['contact'])){ echo $_SESSION['contact']; unset($_SESSION['contact']); } ?>" placeholder="09xxxxxxxxx">
        </div>
        <div class="col-6">
            <label><strong><i class="fa fa-black-tie "></i> Designation</strong></label>
                <select class="form-control" required=""  name="designation">
                    <option selected="" disabled>--</option>
                    <option value="Faculty" <?php if (isset($_SESSION['designation'])) {
                        if ($_SESSION['designation'] == 'Faculty') {
                            echo "selected";
                            unset($_SESSION['designation']);
                        }
                    } ?>>Faculty</option>
                    <option value="Associate Dean" <?php if (isset($_SESSION['designation'])) {
                        if ($_SESSION['designation'] == 'Associate Dean') {
                            echo "selected";
                            unset($_SESSION['designation']);
                        }
                    } ?>>Associate Dean</option>
                    <option value="Research Extension" <?php if (isset($_SESSION['designation'])) {
                        if ($_SESSION['designation'] == 'Research Extension') {
                            echo "selected";
                            unset($_SESSION['designation']);
                        }
                    } ?>>Research Extension</option>
                    <option value="Research Coordinator" <?php if (isset($_SESSION['designation'])) {
                        if ($_SESSION['designation'] == 'Research Coordinator') {
                            echo "selected";
                            unset($_SESSION['designation']);
                        }
                    } ?>>Research Coordinator</option>
                </select>
        </div>
    </div>
    <p></p>
    <div class="row col-12">
        <a class="btn" href="index.php"><i class="fa fa-chevron-left"></i> BACK</a>
        <button type="submit" name="add" class="btn btn-success"><i class="fa fa-plus"></i> Add Employee</button>
    </div>
    <p></p>
</form>
   </div>
 </div>  
</div>
<!-- EndForm -->
</body>
<script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/scripts.js"></script>
    <script src="../datepicker/jquery-1.12.4.js"></script>
    <script src="../datepicker/jquery-ui.js"></script>
</html>
<?php  
}
?>