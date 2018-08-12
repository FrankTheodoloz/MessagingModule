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
            
            <li class="nav-item bg-danger">
                <a class="nav-link text-white" href="?id=' . fctUrlOpensslCipher("admin.php") . '"><i class="fas fa-shield-alt"></i>&nbsp;Admin</a>
            </li>
            
            <li class="nav-item dropdown bg-danger">
                <a class="nav-link dropdown-toggle dropdown-toggle-split text-white" href="#" id="navbardrop" data-toggle="dropdown"></a>
                <div class="dropdown-menu dropdown-menu-right" >
                    <a class="dropdown-item" href="?id=' . fctUrlOpensslCipher("users.php") . '"><i class="fas fa-user-edit text-primary"></i>&nbsp;Users</a>
                    <a class="dropdown-item" href="?id=' . fctUrlOpensslCipher("groups.php") . '"><i class="fas fa-users text-primary"></i>&nbsp;Groups</a>
                    <a class="dropdown-item" href="?id=' . fctUrlOpensslCipher("messagesa.php") . '"><i class="fas fa-envelope text-primary"></i>&nbsp;Messages</a>
                    <a class="dropdown-item" href="?id=' . fctUrlOpensslCipher("settings.php") . '"><i class="fas fa-toolbox text-primary"></i>&nbsp;Settings</a>
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
            <li class="nav-item bg-success">
                <a class="nav-link text-white" href="?id=' . fctUrlOpensslCipher("logout.php") . '" data-toggle="tooltip" data-placement="left" title="Logout">
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


