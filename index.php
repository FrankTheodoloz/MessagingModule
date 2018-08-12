<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 02/08/2018
 * Time: 18:22
 */

include_once("config/config.inc.php");
include_once("functionsSql.inc.php");
include_once("functionsHtml.inc.php");

$defaultPage = "main.php";
global $pageParameter;
global $pageStatus;

session_start();
fctSessionCheck(); //TODO Change to counter

if (isset($_GET['id'])) {
    $urlId = fctUrlOpensslDecipher($_GET['id']); //requestedPage.php,pageParameter
    $page = explode(',', $urlId);
    if ($page[0] == 'logout.php') {
        include($page[0]);
    }
}

include_once("header.inc.php");

?>

<body style="padding-bottom: 35px;">

<?php

//case when user not logged in
if (!isset($_SESSION['user']['id'])) {
    include("loginForm.php");

//case logged
} else {

    include_once("nav.inc.php");

    //then when a page is requested
    if (isset($_GET['id'])) {

        //parsing page parameter if existing
        isset($page[1]) ? $pageParameter = $page[1] : $pageParameter = 0;
        isset($page[2]) ? $pageStatus = $page[2] : $pageStatus = 0;

        //last check if requested page (file) veritably exists and redirection
        file_exists($page[0]) ? include($page[0]) : include($defaultPage);

    } else {
        //case logged user but no page requested
        include($defaultPage);
    }
}

getDebug(); //bottom alert with $_SESSION details
include_once("footer.inc.php")
?>


</body>
</html>