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
!isset($_POST['name']) ?: $name = $_POST['name'];
!isset($_POST['description']) ?: $description = $_POST['description'];

fctCategoryEdit($id, $name, $description);
$page = fctUrlOpensslCipher("categories.php," . $id . ",updated");
header("location:.?id=" . $page);