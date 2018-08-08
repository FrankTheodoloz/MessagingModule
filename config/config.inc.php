<?php
/**
 * Subject: Configuration of the site
 * User: Frank
 * Date: 26/07/2018
 * Time: 13:15
 */

//Constants
define("CONST_TIMEOUT_DURATION", 1800);
define("CONST_DEBUGMODE", 0);
define("CONST_BCRYPT_COST", 12);


//Globals
global $dbUser;
global $dbPassword;
global $dsn;

//SQL DB Connection
$dbServer = "192.168.1.10";
$dbName = "ContactModule";
$dbUser = "frank";
$dbPassword = "1234..aa";
$dsn = "mysql:host=$dbServer;dbname=$dbName;charset=utf8";


/***
 * Class MyPDO :
 * Reference: https://stackoverflow.com/a/9328613
 */
class MyPDO extends PDO
{
    public function __construct($dsn, $username = null, $password = null, array $driver_options = null)
    {
        parent:: __construct($dsn, $username, $password, $driver_options);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}

/***
 * fctSessionCheck : Session timeout
 */
function fctSessionCheck()
{
    if (isset($_SESSION['LAST_ACTIVITY']) && ($_SERVER['REQUEST_TIME'] - $_SESSION['LAST_ACTIVITY']) > CONST_TIMEOUT_DURATION) {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['Error']['Data'] = array("Type" => "warning", "Message" => "Session timeout");

    }
    $_SESSION['LAST_ACTIVITY'] = $_SERVER['REQUEST_TIME'];
}



?>