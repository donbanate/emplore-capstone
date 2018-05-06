
<?php
	session_start();
    if (isset($_SESSION['id'])) {
    	$path = 'manual.pdf';
		$filename = 'manual.pdf';

		    header('Content-Type: application/pdf');
		    header('Content-Disposition: inline; filename="'.$filename .'"');
		    header('Content-Transfer-Encoding: binary');
		    header('Accept-Ranges: bytes');

		    @readfile($filename);
    }else{
    		echo "<script>window.open('../index.php', '_self');</script>";
    }
?>