<?php  
    session_start();
    if (!isset($_SESSION['id'])) {
        echo "<script>window.open('../index.php', '_self');</script>";
    }else{
        include '../db_connection/db_conn.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link href="../libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../css/styles.css" />
  </head>
  <body >
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-primary mb-3">
    <div class="flex-row d-flex">
        <a class="navbar-brand" href="#" title="Admin"><span class="fa fa-user"></span> Coordinator</a>
        <button type="button" class="navbar-toggler" data-toggle="offcanvas" title="Toggle responsive left sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="collapsingNavbar">
        <ul class="navbar-nav">
                <li class="nav-item">
                <a class="nav-link" href="#">Help<span class="sr-only">Help</span></a>
            </li>
                <li class="nav-item">
                <a class="nav-link" href="#">About <span class="sr-only">About</span></a>
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
                <li class="nav-item"><a class="nav-link" href="#">Overview</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="#submenu1" data-toggle="collapse" data-target="#submenu1">Trainings ▾</a>
                    <ul class="list-unstyled flex-column pl-3 collapse" id="submenu1" aria-expanded="false">
                       <li class="nav-item"><a class="nav-link" href="">Training Requests</a></li>
                       <li class="nav-item"><a class="nav-link" href="">Training Needs</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Change Password</a></li>
            </ul>
        </div>
        <!--/col-->

        <div class="col-md-9 col-lg-10 main">

            <!--toggle sidebar button
            <p class="hidden-md-up">
                <button type="button" class="btn btn-primary-outline btn-sm" data-toggle="offcanvas"><i class="fa fa-chevron-left"></i> Menu</button>
            </p>-->

            <h1 class="display-4 d-none d-sm-block">
            EMPLORE
            </h1>
            <p class="lead d-none d-sm-block">(Employee Traning and Seminar Record System)</p>


            <div class="row mb-3">
                <div class="col-xl-4 col-sm-4">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body bg-success">
                            <div class="rotate">
                                <i class="fa fa-check-circle fa-3x"></i>
                            </div>
                            <h6 class="">Checked Trainings</h6>
                            <h1 class="display-4">128</h1>
                        </div>
                    </div>  
                </div>
                <div class="col-xl-4 col-sm-4">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body bg-info">
                            <div class="rotate">
                                <i class="fa fa-envelope-o fa-3x"></i>
                            </div>
                            <h6 class="">Training Requests</h6>
                            <h1 class="display-4">128</h1>
                        </div>
                    </div>  
                </div>
                <div class="col-xl-4 col-sm-4">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body bg-warning">
                            <div class="rotate">
                                <i class="fa fa-file-text-o fa-3x"></i>
                            </div>
                            <h6 class="">Submitted Reports</h6>
                            <h1 class="display-4">128</h1>
                        </div>
                    </div>  
                </div>
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
    <p class="text-right small">©2017 EmpLore</p>
</footer>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">EMPLORE</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure to sign out?</p>
            </div>
            <div class="modal-footer">
                <a href="../logout.php" class="btn btn-primary-outline">Sign-out</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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