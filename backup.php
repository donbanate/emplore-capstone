<?php  
    session_start();
    include 'db_connection/db_conn.php';
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    $checkUser = $result->fetch_assoc();

    if (!isset($_SESSION['id']) OR $checkUser['type'] != 'admin' ) {
        echo "<script>window.open('index.php', '_self');</script>";
    }else{
?>
<!DOCTYPE html>
<html>
<head>
    <title>Download Database</title>
    <link rel="icon" type="image/png" href="logo/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link href="libs/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
<?php 
/**
 * This file contains the Backup_Database class wich performs
 * a partial or complete backup of any given MySQL database
 * @author Daniel López Azaña <daniloaz@gmail.com>
 * @version 1.0
 */

/**
 * Define database parameters here
 */

$backup = "Database";

define("DB_USER", 'emplore');
define("DB_PASSWORD", 'CTDo9IryDWZqYDYe');
define("DB_NAME", 'emplore');
define("DB_HOST", 'localhost');
define("BACKUP_DIR", $backup); // Comment this line to use same script's directory ('.')
define("TABLES", '*'); // Full backup
//define("TABLES", 'table1 table2 table3'); // Partial backup
define("CHARSET", 'utf8');
define("GZIP_BACKUP_FILE", true);  // Set to false if you want plain SQL backup files (not gzipped)

/**
 * The Backup_Database class
 */
 date_default_timezone_set('Asia/Manila');
    
class Backup_Database {
    /**
     * Host where the database is located
     */
    var $host;

    /**
     * Username used to connect to database
     */
    var $username;

    /**
     * Password used to connect to database
     */
    var $passwd;

    /**
     * Database to backup
     */
    var $dbName;

    /**
     * Database charset
     */
    var $charset;

    /**
     * Database connection
     */
    var $conn;

    /**
     * Backup directory where backup files are stored 
     */
    var $backupDir;

    /**
     * Output backup file
     */
    var $backupFile;

    /**
     * Use gzip compression on backup file
     */
    var $gzipBackupFile;

    /**
     * Constructor initializes database
     */
   
    public function __construct($host, $username, $passwd, $dbName, $charset = 'utf8') {
        $this->host            = $host;
        $this->username        = $username;
        $this->passwd          = $passwd;
        $this->dbName          = $dbName;
        $this->charset         = $charset;
        $this->date_time       = $date_time = date('Y-m-d H:i:s');
        $this->conn            = $this->initializeDatabase();
        $this->backupDir       = BACKUP_DIR ? BACKUP_DIR : '.';
        $this->backupFile      = $this->dbName.'.sql';
        $this->gzipBackupFile  = GZIP_BACKUP_FILE ? GZIP_BACKUP_FILE : true;
    }

    protected function initializeDatabase() {
        try {
            $conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
            if (mysqli_connect_errno()) {
                throw new Exception('ERROR connecting database: ' . mysqli_connect_error());
                die();
            }
            if (!mysqli_set_charset($conn, $this->charset)) {
                mysqli_query($conn, 'SET NAMES '.$this->charset);
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            die();
        }

        return $conn;
    }

    /**
     * Backup the whole database or just some tables
     * Use '*' for whole database or 'table1 table2 table3...'
     * @param string $tables
     */
    public function backupTables($tables = '*') {
        try {
            /**
            * Tables to export
            */
            if($tables == '*') {
                $tables = array();
                $result = mysqli_query($this->conn, 'SHOW TABLES');
                while($row = mysqli_fetch_row($result)) {
                    $tables[] = $row[0];
                }
            } else {
                $tables = is_array($tables) ? $tables : explode(',',$tables);
            }

            $sql = 'CREATE DATABASE IF NOT EXISTS `'.$this->dbName."`;\n\n";
            // $sql .= 'USE '.$this->dbName.";\n\n";

            /**
            * Iterate tables
            */
           
            ?>
            <div class="card-block">
            <?php
            foreach($tables as $table) {
                // $this->obfPrint("Backing up `".$table."` table.");

                /**
                 * CREATE TABLE
                 */
                $sql .= 'DROP TABLE IF EXISTS `'.$table.'`;';
                $row = mysqli_fetch_row(mysqli_query($this->conn, 'SHOW CREATE TABLE `'.$table.'`'));
                $sql .= "\n\n".$row[1].";\n\n";

                /**
                 * INSERT INTO
                 */

                $row = mysqli_fetch_row(mysqli_query($this->conn, 'SELECT COUNT(*) FROM `'.$table.'`'));
                $numRows = $row[0];

                // Split table in batches in order to not exhaust system memory 
                $batchSize = 1000; // Number of rows per batch
                $numBatches = intval($numRows / $batchSize) + 1; // Number of while-loop calls to perform
                for ($b = 1; $b <= $numBatches; $b++) {
                    
                    $query = 'SELECT * FROM `'.$table.'` LIMIT '.($b*$batchSize-$batchSize).','.$batchSize;
                    $result = mysqli_query($this->conn, $query);
                    $numFields = mysqli_num_fields($result);

                    for ($i = 0; $i < $numFields; $i++) {
                        $rowCount = 0;
                        while($row = mysqli_fetch_row($result)) {
                            $sql .= 'INSERT INTO `'.$table.'` VALUES(';
                            for($j=0; $j<$numFields; $j++) {
                                $row[$j] = addslashes($row[$j]);
                                $row[$j] = str_replace("\n","\\n",$row[$j]);
                                if (isset($row[$j])) {
                                    $sql .= '"'.$row[$j].'"' ;
                                } else {
                                    $sql.= '""';
                                }

                                if ($j < ($numFields-1)) {
                                    $sql .= ',';
                                }
                            }

                            $sql.= ");\n";
                        }
                    }

                    $this->saveFile($sql);
                    $sql = '';
                }

                $sql.="\n\n\n";

                // $this->obfPrint(" OK");
                '</div>';
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            return false;
        }

        return ($this->saveFile($sql) and $this->gzipBackupFile());
    }

    /**
     * Save SQL to file
     * @param string $sql
     */
    protected function saveFile(&$sql) {
        if (!$sql) return false;

        try {
            if (!$this->gzipBackupFile) {
                $this->obfPrint('Saving backup file to ' . $dest . '... ', 1, 0);
            }

            if (!file_exists($this->backupDir)) {
                mkdir($this->backupDir, 0775, true);
            }

            file_put_contents($this->backupDir.'/'.$this->backupFile, $sql, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            print_r($e->getMessage());
            return false;
        }

        if (!$this->gzipBackupFile) {
            $this->obfPrint('Complete');

        }

        return true;
    }

    /*
     * Gzip backup file
     *
     * @param integer $level GZIP compression level (default: 9)
     * @return string New filename (with .gz appended) if success, or false if operation fails
     */
    protected function gzipBackupFile($level = 9) {
        if (!$this->gzipBackupFile) {
            return true;
        }

        $source = $this->backupDir . '/' . $this->backupFile;
        $dest =  $source . '.gz';

       
         $this->obfPrint('', 1, 0);
         $this->obfPrint('<i class="fa fa-archive"></i> Database Backup Save to: ' . $dest . '', 1, 0);

        $mode = 'wb' . $level;
        if ($fpOut = gzopen($dest, $mode)) {
            if ($fpIn = fopen($source,'rb')) {
                while (!feof($fpIn)) {
                    gzwrite($fpOut, fread($fpIn, 1024 * 256));
                }
                fclose($fpIn);
            } else {
                return false;
            }
            gzclose($fpOut);
            if(!unlink($source)) {
                return false;
            }
        } else {
            return false;
        }
         //$this->obfPrint('Complete');
        $this->obfPrint('<font color="green"> Complete <i class="fa fa-check-circle"></i></font>');
        return $dest;
    }

    /**
     * Prints message forcing output buffer flush
     *
     */
    public function obfPrint ($msg = '', $lineBreaksBefore = 0, $lineBreaksAfter = 1) {
        if (!$msg) {
            return false;
        }

        $output = '';

        if (php_sapi_name() != "cli") {
            $lineBreak = "<br />";
        } else {
            $lineBreak = "\n";
        }

        if ($lineBreaksBefore > 0) {
            for ($i = 1; $i <= $lineBreaksBefore; $i++) {
                $output .= $lineBreak;
            }                
        }

        $output .= $msg;

        if ($lineBreaksAfter > 0) {
            for ($i = 1; $i <= $lineBreaksAfter; $i++) {
                $output .= $lineBreak;
            }                
        }

        echo $output;

        if (php_sapi_name() != "cli") {
            ob_flush();
        }

        flush();
    }
}
echo "<center><hr><h3><strong>Download Database Backup <i class='fa fa-database'></i></strong></h3><br/>";
/**
 * Instantiate Backup_Database and perform backup
 */

// Report all errors
error_reporting(E_ALL);
// Set script max execution time
set_time_limit(0); // 15 minutes

if (php_sapi_name() != "cli") {

    echo '<div style="font-family: monospace;">';

$backupDatabase = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$result = $backupDatabase->backupTables(TABLES, BACKUP_DIR) ? 'Database Backup Complete <i class="fa fa-check-circle"></i>' : 'Failed';
$backupDatabase->obfPrint('Backup result: <font color="green">' . $result, 1 . ' </font>');
 

//     echo "<p>Note: Click download button to download the database!</p>";
//     echo "<form method='POST' action='b_up.php'>
// <button type='submit' class='btn btn-success' name='download'>Download</button>
// </form>";
//     echo '</div></center>';


    // header('Content-Type: application/force-download');
    // header('Content-Disposition: attachment; filename="'.basename($backup).'"');
    // header('Content-Length: ' . filesize($backup));
    // readfile($backup);

}
    $db_backup = "Database/emplore.sql.gz";
?>
<br>
    
<a class="btn btn-outline-success" href="dl_backup.php?id=<?php echo $db_backup; ?>">Download Database Backup</a>
<hr>
<a class="btn btn-outline-primary" href="admin/tools.php"><i class="fa fa-chevron-left"></i> Back</a>
</body>
</html>
<script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <script src="js/scripts.js"></script>


<?php  
}
?>