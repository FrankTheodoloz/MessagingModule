<?php
/**
 * Subject: Configuration of the site
 * User: Frank
 * Date: 26/07/2018
 * Time: 13:15
 */

//Constants
define("CONST_DEBUGMODE", 0);
define("CONST_IMAGE_PATH", "img/avatar/");
define("CONST_TIMEOUT_DURATION", 1800); //in seconds
define("CONST_BCRYPT_COST", 12); //takes significantly more time above 12

/*** TODO ANALYSE MORE IN DETAILS
 * Class MyPDO : Creates a constructor for PDO-MySQL connection
 * Reference: https://stackoverflow.com/a/9328613 and https://stackoverflow.com/a/18684115
 */
class myPDO extends PDO
{
    public function __construct($dsn = null, $username = null, $password = null, array $driver_options = null)
    {
        $dbServer = "192.168.1.10";
        $dbName = "MessagingModule";
        $username = "frank";
        $password = "1234..aa";
        $dsn = "mysql:host=$dbServer;dbname=$dbName;charset=utf8";

        $driver_options = array(
            PDO::MYSQL_ATTR_FOUND_ROWS => TRUE,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        parent:: __construct($dsn, $username, $password, $driver_options);


    }
}