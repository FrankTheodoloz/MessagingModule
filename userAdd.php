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

!isset($_POST['name']) ?: $name = $_POST['name'];
!isset($_POST['lastname']) ?: $lastname = $_POST['lastname'];
!isset($_POST['email']) ?: $email = $_POST['email'];
!isset($_POST['password']) ?: $password = $_POST['password'];

$id=fctUserAdd($name, $lastname,$email, $password);
$page = fctUrlOpensslCipher("users.php," . $id . ",created");
header("location:.?id=" . $page);