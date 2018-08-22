<?php
/**
 * Subject: POST file for Profile
 * Usage: Check $_POST, insert to DB, reports error and redirect
 * User: Frank
 * Date: 20/08/2018
 * Time: 22:02
 */

session_start();

include_once("functionsSql.inc.php");
include_once("functionsHtml.inc.php");

$target = "profile.php";
$userId = $_SESSION['user']['id'];

if (isset($_POST['action'])) {
    $action = $_POST['action'];
} else {
    $result[] = array("error", "Error", "Form ID error");
    goto error;
}
if ($action == 'update') {
    if (isset($_POST['name']) && isset($_POST['lastname'])) {

        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $sqlResult = fctUserEdit($userId, $name, $lastname);

        if ($sqlResult == 1) {
            $result[] = array("success", "Success", "Profile details updated");
        } else {
            $result[] = array("error", "Error", "Names update failed");
        }
    } else {
        $result[] = array("error", "Error", "Form input error");
    }

} elseif ($action == 'password') {
    if (isset($_POST['password'])) {

        $password = $_POST['password'];
        $sqlResult = fctUserEditPwd($userId, $password);

        if ($sqlResult == 1) {
            $result[] = array("success", "Success", "Password updated");
        } else {
            $result[] = array("error", "Error", "Password update failed");
        }
    } else {
        $result[] = array("error", "Error", "Form input error");
    }
} elseif ($action == 'avatar') {
    if (isset($_POST['avatar'])) {
        $avatar = $_POST['avatar'];

        $sqlResult = fctUserAvatarChange($userId, $avatar);

        if ($sqlResult == 1) {
            $result[] = array("success", "Success", "Avatar changed.");
        } else {
            $result[] = array("error", "Error", "Avatar change failed");
        }
    } else {
        $result[] = array("error", "Error", "Form input error");
    }
} else {
    $result[] = array("error", "System error", "No POST action found<br/>or sent to wrong page.");
}

error:
$page = fctUrlOpensslCipher($target . "," . $userId . "," . serialize($result));
header("location:.?id=" . $page);

?>
<pre>$_REQUEST = <?= print_r($_REQUEST); ?> </pre>
<pre>$result = <?= print_r($result); ?> </pre>