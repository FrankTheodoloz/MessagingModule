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
!isset($_POST['email']) ?: $email = $_POST['email'];

fctUserEditEmail($id, $email);
$page = fctUrlOpensslCipher("userDetail.php," . $id . ",email updated");
header("location:.?id=" . $page);