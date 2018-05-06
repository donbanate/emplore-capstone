<?php  
    session_start();
    include '../db_connection/db_conn.php';
    $user = $_SESSION['id'];
    $sql = "SELECT * FROM users WHERE id = '$user' ";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'coordinator') {
        echo "<script>window.open('../index.php', '_self');</script>";
    }else{
        include '../db_connection/db_conn.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Uncheck Training</title>
    <link rel="icon" type="image/png" href="../logo/logo.png">
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>
    <?php
        $id = addslashes($_GET['id']);
        $sql = "SELECT * FROM trainings AS T, approvals AS A WHERE T.id = A.id AND T.id = '$id' ";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    ?>
        <div class="card-block col-6 offset-3">
            <div class="card-header text-white bg-danger"><h1>Uncheck Training <i class="fa fa-trash"></i></h1></div>
    <?php  
        if (isset($_POST['uncheck'])) {
            $id = $_POST['id'];
            $reason = addslashes(ucfirst($_POST['reason']));

            $sql = "UPDATE employee_training_needs SET is_in_training_needs = '0', remark = 'unlike', reasons = '$reason' WHERE id = '$id' ";

            if ($conn->query($sql) === TRUE) {
    ?>
        <div class="alert alert-success" role="alert">
            <strong>Success!</strong> Training Unchecked.
            <script type="text/javascript">
                window.open('trainRequest.php', '_self');
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
                <h4><i>*Please reason out why you uncheck</i></h4>
                <form action="removeTrainingRequest.php?id=<? echo $id;?>" method="POST">
                    <div class="form-group">
                        <label><strong>Training Title</strong></label>
                        <p class="form-control"><b><?=$row['training_title']?></b></p>
                    </div>
                    <div class="form-group">
                        <label><strong>Reason(s)</strong></label>
                        <textarea class="form-control" rows="5" name="reason" placeholder="Please attach your reason(s) here!" autofocus="" required=""></textarea>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <a href="trainRequest.php" class="btn btn-outline-primary btn-lg form-control" data-toggle="tooltip" title="GO BACK"><i class="fa fa-chevron-left"></i></a>
                        </div>
                        <div class="form-group col-6">
                            <button type="submit" name="uncheck" class="btn btn-outline-danger btn-lg form-control" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="CLICK TO UNCHECK"><i class="fa fa-remove"></i></button>
                            <input type="hidden" name="id" value="<?=$id?>">
                        </div>
                    </div>
                </form>
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