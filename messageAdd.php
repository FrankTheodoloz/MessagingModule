<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 09/08/2018
 * Time: 20:41
 */

session_start();
include_once("functionsSql.inc.php");
include_once("functionsHtml.inc.php");

!isset($_POST['subjectId']) ? $subjectId = NULL : $subjectId = $_POST['subjectId'];
!isset($_POST['to']) ? $to = NULL : $to = $_POST['to'];
!isset($_POST['content']) ? $content = NULL : $content = $_POST['content'];

fctMessageAdd($to, $_SESSION['user']['id'], $subjectId, $content, NULL);

$page = fctUrlOpensslCipher("messages.php," . $subId . ", message sent");
header("location:.?id=" . $page);
