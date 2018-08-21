<?php
/**
 * Subject: POST file for Groups
 * Usage: Check $_POST, insert to DB, reports error and redirect
 * User: Frank
 * Date: 06/08/2018
 * Time: 21:36
 */

session_start();

include_once("functionsSql.inc.php");
include_once("functionsHtml.inc.php");

$target = "groupDetail.php";

if (isset($_POST['id']) && isset($_POST['action'])) {
    $groupId = $_POST['id'];
    $action = $_POST['action'];
} else {
    $result[] = array("error", "Error", "Form ID error");
    goto error;
}

if ($action == 'new') {
    if (isset($_POST['name']) && isset($_POST['description'])) {

        $name = $_POST['name'];
        $description = $_POST['description'];

        $id = fctGroupAdd($name, $description);

        if ($id > 0) {
            $groupId = $id;
            $result[] = array("success", "Success", "Group created");
        } else if ($id == -2) {
            $result[] = array("warning", "Warning", "Duplicate Group name");
        } else {
            $result[] = array("error", "Error", "Group creation failed");
        }
    } else {
        $result[] = array("error", "Error", "Form input error");
    }

} elseif ($action == 'update') {
    if (isset($_POST['name']) && isset($_POST['description'])) {

        $name = $_POST['name'];
        $description = $_POST['description'];

        $sqlResult = fctGroupEdit($groupId, $name, $description);

        if ($sqlResult > 0) {
            $result[] = array("success", "Success", "Group updated");
        } else if ($sqlResult == -2) {
            $result[] = array("warning", "Warning", "Duplicate Group name");
        } else {
            $result[] = array("error", "Error", "Group update failed");
        }
    } else {
        $result[] = array("error", "Error", "Form input error");
    }

} elseif ($action == 'delete') {
    $target="groups.php";

    $sqlResult = fctMembershipGroupDelete($groupId);

    if ($sqlResult > 0) {
        $result[] = array("info", "Info", $sqlResult." user(s) removed from group before deletion.");
    }

    $sqlResult = fctGroupDelete($groupId);

    if ($sqlResult > 0) {
        $result[] = array("success", "Success", "Group deleted");
    } else {
        $result[] = array("error", "Error", "Group deletion failed");
    }


} elseif ($action == 'memberAdd') {
    if (isset($_POST['usr'])) {
        $users = $_POST['usr'];

        $i = 0;
        foreach ($users as $item) {
            fctMembershipAdd($groupId, $item);
            $i++;
        }
        $result[] = array("success", "Success", $i . " user(s) added to Group");
    } else {
        $result[] = array("error", "Error", "No user selected");
        $target = "groupMember.php";
    }

} elseif ($action == 'memberRemove') {
    if (isset($_POST['usr'])) {
        $users = $_POST['usr'];

        $i = 0;
        foreach ($users as $item) {
            fctMembershipRemove($groupId, $item);
            $i++;
        }
        $result[] = array("success", "Success", $i . " user(s) removed from group");
    } else {
        $result[] = array("error", "Error", "No user selected");
    }
} else {
    $result[] = array("error", "System error", "No POST action found<br/>or sent to wrong page.");
}

error:
$page = fctUrlOpensslCipher($target . "," . $groupId . "," . serialize($result));
header("location:.?id=" . $page);

?>
<pre>$_REQUEST = <?= print_r($_REQUEST); ?> </pre>
<pre>$_SESSION = <?= print_r($_SESSION); ?> </pre>
<pre>$result = <?= print_r($result); ?> </pre>