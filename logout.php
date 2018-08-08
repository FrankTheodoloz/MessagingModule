<?php
/**
 * Subject : Destroy user session and redirect to index.php
 * User: Frank
 * Date: 04/08/2018
 * Time: 00:27
 */


session_unset();
session_destroy();
header("location:.");

?>