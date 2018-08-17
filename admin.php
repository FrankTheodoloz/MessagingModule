<?php
/**
 * Subject: Administration menu
 * User: Frank
 * Date: 15/08/2018
 * Time: 15:35
 */

?>

<div class="container">

    <div class="row mt-4">
        <div class="col-md-6">
            <h2><i class="fas fa-shield-alt text-primary" aria-hidden="true"></i> Administration&nbsp;tools</h2>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("index.php") ?>">
                <h2>back to homepage <i class="fas fa-angle-double-left " aria-hidden="true"></i></h2>
            </a>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><h2><i class="fas fa-user text-primary" aria-hidden="true"></i> Users</h2></div>
                <div class="card-body">
                    <div class="card-text">Create or edit Users<br/> Change User Email or reset Password</div>
                </div>
                <div class="card-footer text-right">
                    <a type="button" class="btn btn-primary" href="?id=<?= fctUrlOpensslCipher("users.php") ?>"><i class="far fa-arrow-alt-circle-right"></i> Manage Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><h2><i class="fas fa-users text-primary" aria-hidden="true"></i> Groups</h2></div>
                <div class="card-body">
                    <div class="card-text">Create or Edit Groups<br/> Change User Group Membership</div>
                </div>
                <div class="card-footer text-right">
                    <a type="button" class="btn btn-primary" href="?id=<?= fctUrlOpensslCipher("groups.php") ?>"><i class="far fa-arrow-alt-circle-right"></i> Manage Groups</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header"><h2><i class="fas fa-database text-primary" aria-hidden="true"></i> Database</h2></div>
                <div class="card-body">
                    <div class="card-text">Database maintenance<br/>for Subject, Messages & Notifications</div>
                </div>
                <div class="card-footer text-right">
                    <a type="button" class="btn btn-primary" href="?id=<?= fctUrlOpensslCipher("database.php") ?>"><i class="far fa-arrow-alt-circle-right"></i> Manage Database</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><h2><i class="fas fa-toolbox text-primary" aria-hidden="true"></i> Settings</h2></div>
                <div class="card-body">
                    <div class="card-text">Change Website/Application-related Settings<br/>&nbsp;</div>
                </div>
                <div class="card-footer text-right">
                    <a type="button" class="btn btn-primary" href="?id=<?= fctUrlOpensslCipher("settings.php") ?>"><i class="far fa-arrow-alt-circle-right"></i> Manage Settings</a>
                </div>
            </div>
        </div>
    </div>


</div>

