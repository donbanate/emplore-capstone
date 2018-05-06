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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dissaprove Fund</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>
    <div class="row mb-3">
        <div class="card-block col-6 offset-3">
            <div class="card-header text-white bg-danger"><h3>Dissaprove Fund <i class="fa fa-meh-o"></i></h3></div>
            <?php  
                if (isset($_POST['disapprove'])) {
                    $id = $_POST['id'];
                    $reason = addslashes(ucfirst($_POST['reason']));

                    date_default_timezone_set('Asia/Manila');
                    $currentTime = date('Y-m-d h:i:s');

                    $update = "UPDATE employee_training_needs SET is_fund_available = '0', remark = 'checked', final_remark = 'disapproved', reasons = '$reason', date_checked = '$currentTime' WHERE id = '$id' ";
                    if ($conn->query($update) === TRUE) {
            ?>
                    <div class="alert alert-success" role="alert">
                        <strong>Success!</strong> Training Disapproved.
                        <script type="text/javascript">
                            window.open('fundRequest.php', '_self');
                        </script>
                    </div>
            <?php
                    }else{
            ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>Oh snap!</strong> Something went wrong.
                    </div>
            <?php            
                    }

                }
            ?>
                <div class="card-body">
                    <?php
                        $id = addslashes($_GET['id']);
                        $sql = "SELECT * FROM trainings AS T, employee_training_needs AS N, iteneraries AS I WHERE T.id = N.id AND T.id = I.id AND T.id = '$id' ";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                    ?>
                    <form method="POST" action="uncheckfund.php?id=<?=$row['id']?>">
                        <div class="form-group">
                                <label><b>Training Title</b></label>
                                <p class="form-control"><?=$row['training_title']?></p>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label><b>Requested Funds</b></label>
                                <p class="form-control"><font color="red">Php <?=$row['total_expenses_to_be_incurred']?>.00</font></p>
                            </div>
                            <div class="form-group col-6">
                                <label><b>Date of Departure</b></label>
                                <p class="form-control"><?=$row['date_departure'] ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                        <label><strong>Reason(s)</strong></label>
                        <textarea class="form-control" rows="5" name="reason" placeholder="Please attach your reason(s) here!" autofocus="" required=""></textarea>
                        </div>
                        <div class="row">
                        <div class="form-group col-6">
                            <a href="fundRequest.php" class="btn btn-outline-primary btn-lg form-control" data-toggle="tooltip" title="GO BACK"><i class="fa fa-chevron-left"></i></a>
                        </div>
                        <div class="form-group col-6">
                            <button type="submit" name="disapprove" class="btn btn-outline-danger btn-lg form-control" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="CLICK TO DISAPROVE"><i class="fa fa-remove"></i></button>
                            <input type="hidden" name="id" value="<?=$id?>">
                        </div>
                    </div>
                    </form>
                </div>
        </div>
    </div>
</body>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/scripts.js"></script>
    <script>
        $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</html>
<?php 
}
?>