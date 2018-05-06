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
        include '../db_connection/db_conn.php';
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
                $sql = "SELECT * FROM trainings WHERE id = '$id' ";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
            ?>
            <p></p>
            <div class="table-responsive col-12">
                <table class="table table-bordered">
                <tr>
                    <th><font color="#2980b9" size="5px"><u>T</u></font><font color="#2980b9">raining Title</font></th>
                    <td><font size="5px"><?=$row['training_title']?></font></td>
                </tr>
                <tr>
                    <th><font color="#2980b9" size="5px"><u>T</u></font><font color="#2980b9">raining Date</font></th>
                    <td><font size="5px"><?=$row['training_date']?></font></td>
                </tr>
                <tr>
                    <th><font color="#2980b9" size="5px"><u>E</u></font><font color="#2980b9">ndorsement</font></th>
                    <td><font size="5px"><?=$row['endorsement']?></font></td>
                </tr>
                <tr>
                    <th><font color="#2980b9" size="5px"><u>S</u></font><font color="#2980b9">ponsoring Institution</font></th>
                    <td><font size="5px"><?=$row['sponsor_institution']?></font></td>
                </tr>
                <tr>
                    <th><font color="#2980b9" size="5px"><u>T</u></font><font color="#2980b9">raining Officer</font></th>
                    <td><font size="5px"><?=$row['training_officer']?></font></td>
                </tr>
                <tr>
                    <th><font color="#2980b9" size="5px"><u>V</u></font><font color="#2980b9">enue</font></th>
                    <td><font size="5px"><?=$row['venue']?></font></td>
                </tr>
                <tr>
                    <th><font color="#2980b9" size="5px"><u>R</u></font><font color="#2980b9">eturn-to-post</font></th>
                    <td><font size="5px"><?=$row['return_to_post']?></font></td>
                </tr>
                <tr>
                    <th><font color="#2980b9" size="5px"><u>R</u></font><font color="#2980b9">eason of Participation</font></th>
                    <td><font size="5px"><?=$row['reason_of_participation']?></font></td>
                </tr>
                <tr>
                    <th><font color="#2980b9" size="5px"><u>P</u></font><font color="#2980b9">roject Title</font></th>
                    <td><font size="5px"><?=$row['project_title']?></font></td>
                </tr>
                <tr>
                    <th><font color="#2980b9" size="5px"><u>C</u></font><font color="#2980b9">ompletion Date</font></th>
                    <td><font size="5px"><?=$row['completion_date']?></font></td>
                </tr>
            </table>
            <center><a class="btn btn-outline-info" href="checkedTrainings.php"><i class="fa fa-chevron-left"></i> GO BACK</a></center>
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