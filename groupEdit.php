<?php
/**
 * Subject: Groups PHP-SQL update. Check $_POST, insert to DB, reports error and redirect
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

        if ($id > 1) {
            $groupId = $id;
            $result[] = array("success", "Success", "Group created");
        } else if ($id == -2) {
            $result[] = array("warning", "Warning", "Duplicate group");
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

        if ($sqlResult == 1) {
            $result[] = array("success", "Success", "Group details updated");
        } else {
            $result[] = array("error", "Error", "Group update failed");
        }
    } else {
        $result[] = array("error", "Error", "Form input error");
    }

} elseif ($action == 'memberAdd') {
    if (isset($_POST['usr'])) {
        $users = $_POST['usr'];

        $i = 0;
        foreach ($users as $item) {
            fctMembershipAdd($groupId, $item);
            $i++;
        }
        $result[] = array("success", "Success", $i . " user(s) added to group");
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
}

error:
$page = fctUrlOpensslCipher($target . "," . $groupId . "," . serialize($result));
header("location:.?id=" . $page);
//print_r($_REQUEST);
//print_r($result);