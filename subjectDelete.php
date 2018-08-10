<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 09/08/2018
 * Time: 23:14
 */

session_start();
include_once("functionsSql.inc.php");
include_once("functionsHtml.inc.php");

!isset($_POST['subjectId']) ? $subjectId = NULL : $subjectId = $_POST['subjectId'];
$row=fctSubjectDelete($subjectId);
$page = fctUrlOpensslCipher("messages.php");
header("location:.?id=" . $page);