<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 02/08/2018
 * Time: 18:22
 */

// https://fontawesome.com/icons?d=gallery&m=free

include_once("config/config.inc.php");
include_once("functionsSql.inc.php");
include_once("functionsHtml.inc.php");

session_start();
fctSessionCheck();

include_once("header.inc.php");

?>
<body>

<?php
include_once("nav.inc.php");
$defaultPage = "main.php";
global $pageParameter;
global $pageStatus;

//case user not logged in
if (!isset($_SESSION['user']['id'])) {
    include("login.php");

//case logged AND a page is requested
} else if (isset($_GET['id'])) {
    $urlId = fctUrlOpensslDecipher($_GET['id']); //requestedPage.php,pageParameter

    $page = explode(',', $urlId);

    //parsing page parameter if existing
    isset($page[1]) ? $pageParameter = $page[1] : $pageParameter = 0;
    isset($page[2]) ? $pageStatus = $page[2] : $pageStatus = 0;

    //last check if requested page (file) veritably exists and redirection
    file_exists($page[0]) ? include($page[0]) : include($defaultPage);

} else {
    //case logged user but no page requested
    include($defaultPage);
}

getDebug(); //bottom alert with $_SESSION details
include_once("footer.inc.php")
?>


</body>
</html>