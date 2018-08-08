<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 07/08/2018
 * Time: 22:15
 */

session_start();
include_once("functionsSql.inc.php");
include_once("functionsHtml.inc.php");

!isset($_POST['groupId']) ?: $id = $_POST['groupId'];
$i=0;
foreach ($_POST['usr'] as $item) {
    fctMembershipAdd($id,$item);
    $i++;
}

$page = fctUrlOpensslCipher("groupDetail.php," . $id . ",".$i." member(s) added");
header("location:.?id=" . $page);
