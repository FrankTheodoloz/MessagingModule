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
global $page;   //requestedPage.php, pageParameter, arrMessages
global $pageRequested;
global $pageParameter;
global $pageStatus;
$error = false;

session_start();

fctSessionCheck(); //check the session duration and reset the counter

//catch ?id= and check if key exist
if (isset($_GET['id']) && isset($_SESSION['key'])) {

    try {
        $urlId = fctUrlOpensslDecipher($_GET['id']);
        $page = explode(',', $urlId);

    } catch (Exception $e) {
        $pageStatus[] = array("error", "Error", "Url serialisation Issue");
    }
    if ($page[0] == 'logout.php') {
        file_exists($page[0]) ? include($page[0]) : include($defaultPage);
        exit();
    }
}

//html head /!\ any php header() needs to be before
include_once("header.inc.php");
?>

<body>

<?php

//case when user not logged in
if (!isset($_SESSION['user']['id'])) {

    include("loginForm.php");

//case logged
} else {

    include_once("nav.inc.php"); //which includes the counter
    //then when a page is requested
    if (isset($_GET['id'])) {

        //parsing page parameter if existing
        isset($page[0]) ? $pageRequested = $page[0] : $pageRequested = "";
        isset($page[1]) ? $pageParameter = $page[1] : $pageParameter = 0;

        if (isset($page[2])) {

            try {
                $pageStatus = unserialize($page[2]);
            } catch (Exception $e) {
                $pageStatus[] = array("error", "Error", "Url serialisation Issue");

            }
        } else {
            $pageStatus = "";
        }


        //last check if requested page (file) veritably exists and redirection + avoid calling itself
        file_exists($pageRequested) && $pageRequested != 'index.php' ? include($pageRequested) : include($defaultPage);

    } else {
        //case logged user but no page requested
        include($defaultPage);
    }
}
if ($pageStatus) {

    foreach ($pageStatus as $item) {
        fctShowToast($item[0], $item[1], $item[2]);
    }
}
getDebug(); //toast alert with $_SESSION and $Page details

include_once("footer.inc.php")
?>

</body>
</html>