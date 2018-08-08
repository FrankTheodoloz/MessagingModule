<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 06/08/2018
 * Time: 21:36
 */
session_start();
include_once("functionsSql.inc.php");
include_once("functionsHtml.inc.php");

!isset($_POST['id']) ?: $id = $_POST['id'];


if (isset($_POST['active']) && $_POST['active'] == 'Yes') {
    $action = "user enabled";
    fctUserEnable($id);
} else {
    $action = "user disabled";
    fctUserDisable($id);
}

$page = fctUrlOpensslCipher("userDetail.php," . $id . "," . $action);
header("location:.?id=" . $page);