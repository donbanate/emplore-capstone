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
    <title>View Training Details</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body>
    <div class="card-block col-6 offset-3">
        <div class="card-header bg-primary text-white"><h3><strong>Employee Training Details</strong> <i class="fa fa-info-circle"></i></h3></div>
            <?php  
                $id = addslashes($_GET['id']);
                $sql = "SELECT * FROM employee_training_needs WHERE id = '$id' ";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
            ?>
            <p></p>
            <div class="table-responsive col-12">
                <table class="table table-bordered">
                <tr>
                    <th class="table-dark"><font size="5px"><u>T</u></font>raining Title</th>
                    <td><font size="5px"><?=$row['training_title']?></font></td>
                </tr>
                <tr>
                    <th class="table-dark"><font size="5px"><u>T</u></font>otal Transporation Expenses</th>
                    <td><font size="5px">Php <?=$row['transportation_expense']?>.00</font></td>
                </tr>
                <tr>
                    <th class="table-dark"><font size="5px"><u>T</u></font>otal Cost of Per Diem</th>
                    <td><font size="5px">Php <?=$row['total_cost_of_per_diem']?>.00</font></td>
                </tr>
                <tr>
                    <th class="table-dark"><font size="5px"><u>S</u></font>eminar Training Fee</th>
                    <td><font size="5px">Php <?=$row['seminar_training_fee']?>.00</font></td>
                </tr>
                <tr>
                    <th class="table-dark"><font size="5px"><u>T</u></font>otal Expenses to be Incurred</th>
                    <td><font size="5px">Php <?=$row['total_expenses_to_be_incurred']?>.00</font></td>
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