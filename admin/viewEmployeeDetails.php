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
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>View Employee Details</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body>
    <div class="card-block col-6 offset-3">
        <div class="card-header bg-primary text-white"><strong><h3>Employee Details</strong> <i class="fa fa-info-circle"></i></h3></div>
            <?php  
                $id = addslashes($_GET['id']);
                $sql = "SELECT * FROM employees WHERE id = '$id' ";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
            ?>
            <p></p>
            <div class="table-responsive col-12">
                <table class="table table-bordered">
                <tr>
                    <th class="table-dark"><font size="5px"><u>N</u></font>ame</th>
                    <td><font size="5px"><?=$row['first_name'] . ' ' . $row['middle_name'] . ' '.$row['last_name'] . ' '. $row['name_extension'] ?></font></td>
                </tr>
                <tr>
                    <th class="table-dark"><font size="5px"><u>S</u></font>ex</th>
                    <td><font size="5px"><?=$row['sex']?></font></td>
                </tr>
                <tr>
                    <th class="table-dark"><font size="5px"><u>A</u></font>ge</th>
                    <td><font size="5px"><?=$row['age']?></font></td>
                </tr>
                <tr>
                    <th class="table-dark"><font size="5px"><u>B</u></font>irthdate</th>
                    <td><font size="5px"><?=$row['birth_date']?></font></td>
                </tr>
                <tr>
                    <th class="table-dark"><font size="5px"><u>B</u></font>irthplace</th>
                    <td><font size="5px"><?=$row['birth_place']?></font></td>
                </tr>
                <tr>
                    <th class="table-dark"><font size="5px"><u>B</u></font>arangay</th>
                    <td><font size="5px"><?=$row['barangay']?></font></td>
                </tr>
                <tr>
                    <th class="table-dark"><font size="5px"><u>A</u></font>ddress</th>
                    <td><font size="5px"><?=$row['address']?></font></td>
                </tr>
                <tr>
                    <th class="table-dark"><font size="5px"><u>M</u></font>obile Number</th>
                    <td><font size="5px"><?=$row['mobile_no']?></font></td>
                </tr>
            </table>
            <center><a class="btn btn-outline-info" href="trainingRequest.php"><i class="fa fa-chevron-left"></i> GO BACK</a></center>
            </div>
    </div>
  </body>
  <!--scripts loaded here-->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    
    <script src="../js/scripts.js"></script>
</html>
<?php  
}
?>