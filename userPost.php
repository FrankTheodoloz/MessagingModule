<?php
/**
 * Subject: User PHP-SQL update. Check $_POST, insert to DB, reports error and redirect
 * User: Frank
 * Date: 06/08/2018
 * Time: 21:36
 */

session_start();

include_once("functionsSql.inc.php");
include_once("functionsHtml.inc.php");

$target = "userDetail";

if (isset($_POST['id']) && isset($_POST['action'])) {
    $userId = $_POST['id'];
    $action = $_POST['action'];
} else {
    $result[] = array("error", "Error", "Form ID error");
    goto error;
}

if ($action == 'new') {
    if (isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['password'])) {

        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $id = fctUserAdd($name, $lastname, $email, $password);

        if ($id > 0) {
            $userId = $id;
            $result[] = array("success", "Success", "User created");
            $result[] = array("info", "Info", "User needs to be activated");
        } elseif ($id == -2) {
            $result[] = array("error", "Error", "Duplicate email");
        } else {
            $result[] = array("error", "Error", "User creation failed");
        }
    } else {
        $result[] = array("error", "Error", "Form input error");
    }

} elseif ($action == 'update') {
    if (isset($_POST['name']) && isset($_POST['lastname'])) {

        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $sqlResult = fctUserEdit($userId, $name, $lastname);

        if ($sqlResult == 1) {
            $result[] = array("success", "Success", "Profile details updated");
        } else {
            $result[] = array("error", "Error", "Profile update failed");
        }
    } else {
        $result[] = array("error", "Error", "Form input error");
    }

} elseif ($action == 'activate') {
    if (isset($_POST['active'])) {

        $active = $_POST['active'];
        $sqlResult = fctUserChangeActive($userId, $active);

        if ($sqlResult == 1) {
            if ($active) {
                $actionDone = "Enabled";
            } else {
                $actionDone = "Disabled";
            }
            $result[] = array("success", "Success", "User " . $actionDone);
        } else {
            $result[] = array("error", "Error", "Update failed");
        }
    } else {
        $result[] = array("error", "Error", "Form input error");
    }

} elseif ($action == 'email') {
    if (isset($_POST['email'])) {

        $email = $_POST['email'];
        $sqlResult = fctUserEditEmail($userId, $email);

        if ($sqlResult == 1) {
            $result[] = array("success", "Success", "Email updated");
        } else if ($sqlResult == -2) {
            $result[] = array("warning", "Warning", "Duplicate email");
        } else {
            $result[] = array("error", "Error", "Email update failed");
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
//header("location:.?id=" . $page);

?>
<pre>$_REQUEST = <?= print_r($_REQUEST); ?> </pre>
<pre>$_SESSION = <?= print_r($_SESSION); ?> </pre>
<pre>$result = <?= print_r($result); ?> </pre>