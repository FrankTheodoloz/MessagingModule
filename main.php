<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 04/08/2018
 * Time: 12:48
 */

include_once("functionsHtml.inc.php");
include_once("functionsSql.inc.php");
include_once("nav.inc.php");

?>

<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1 class="d-inline-block">Welcome, <?= $_SESSION['user']['name'] ?></h1>
        <?= getBadge() ?>

        <p>
            <a class="btn text-muted float-right" href="?id=<?= fctUrlOpensslCipher("about.php") ?>">
                <h3></i>about <?= fctSettingItem('SITE_CONFIG', 'SITE_NAME') ?> <i class="fas fa-angle-double-right " aria-hidden="true"></i></h3>
            </a>
        </p>
    </div>
</div>

