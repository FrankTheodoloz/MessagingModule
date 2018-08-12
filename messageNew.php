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

!isset($_POST['to']) ? $to = NULL : $to = $_POST['to'];
!isset($_POST['subject']) ? $subject = NULL : $subject = $_POST['subject'];
!isset($_POST['content']) ? $content = NULL : $content = $_POST['content'];

$subId = fctSubjectNew($_SESSION['user']['id'], $to, $subject, $content, NULL);

$page = fctUrlOpensslCipher("messages.php," . $subId . ", message sent");
header("location:.?id=" . $page);


