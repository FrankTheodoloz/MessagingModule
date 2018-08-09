<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 09/08/2018
 * Time: 18:56
 */

session_start();
include_once("functionsSql.inc.php");
include_once("functionsHtml.inc.php");

!isset($_POST['groupId']) ?: $id = $_POST['groupId'];
!isset($_POST['groupId']) ?: $id = $_POST['groupId'];
!isset($_POST['groupId']) ?: $id = $_POST['groupId'];

$i=0;
foreach ($_POST['usr'] as $item) {
    fctMembershipAdd($id,$item);
    $i++;
}

$page = fctUrlOpensslCipher("groupDetail.php," . $id . ",".$i." member(s) added");
header("location:.?id=" . $page);
