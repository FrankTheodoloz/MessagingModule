<?php
/**
 * Subject: Navigation menu include
 * User: Frank
 * Date: 04/08/2018
 * Time: 16:58
 */

include_once("functionsHtml.inc.php");
include_once("functionsSql.inc.php");
?>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <!-- Brand -->
    <a class="navbar-brand" href="#"><strong><?= fctSettingItem('SITE_CONFIG', 'SITE_NAME') ?></strong></a>
    <!-- Hamburger button for collapsibleItems-->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleItems">
        <span class="navbar-toggler-icon"></span>
    </button>

    <?php if (isset($_SESSION['user']['id'])) {
        echo '
    <div class="navbar-collapse collapse" id="collapsibleItems">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="?id=' . fctUrlOpensslCipher("main.php") . '"><i class="fas fa-home"></i>&nbsp;Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?id=' . fctUrlOpensslCipher("messages.php") . '"><i class="fas fa-envelope"></i>&nbsp;Messages</a>
            </li>
            ';
        if ($_SESSION['user']['admin'] == 1) {
            echo '
            <!-- Dropdown admin menu -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown"><i class="fas fa-toolbox "></i> Admin</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="?id=' . fctUrlOpensslCipher("users.php") . '"><i class="fas fa-user-edit text-primary"></i>&nbsp;Users</a>
                    <a class="dropdown-item" href="?id=' . fctUrlOpensslCipher("groups.php") . '"><i class="fas fa-users text-primary"></i>&nbsp;Groups</a>
                    <a class="dropdown-item" href="?id=' . fctUrlOpensslCipher("categories.php") . '"><i class="fas fa-list text-primary"></i>&nbsp;&nbsp;Categories</a>
                </div>
            </li>
            ';
        }
        echo '
        </ul>
        <!-- Right navbar links -->
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" aria-haspopup="false" href="?id=' . fctUrlOpensslCipher("profile.php") . '" data-toggle="tooltip" data-placement="left" title="Profile">
                <i class="fas fa-user"></i>
                    ' . $_SESSION['user']['name'] . '
                    
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?id=' . fctUrlOpensslCipher("logout.php") . '" data-toggle="tooltip" data-placement="left" title="Logout">
                    <i class="fas fa-door-open"></i>
                </a>
            </li>
        </ul>
    </div>
    ';
    } ?>

    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</nav>
