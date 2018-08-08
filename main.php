<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 04/08/2018
 * Time: 12:48
 */

include_once("functionsHtml.inc.php");
include_once("nav.inc.php");

?>

<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1 class="d-inline-block">Welcome, <?= $_SESSION['user']['name'] ?></h1>
        <?= getBadge() ?>

        <p>This is the welcome screen with an overview of the recent messages.</p>
    </div>
</div>


