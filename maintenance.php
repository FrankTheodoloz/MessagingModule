<?php
/**
 * Subject: Administration - Maintenance tools
 * User: Frank
 * Date: 15/08/2018
 * Time: 21:38
 */

$subjectList = fctUserSubjectList(-1);
?>

<div class="container">

    <div class="row mt-4">
        <div class="col-md-6">
            <h2><i class="fas fa-toolbox text-primary" aria-hidden="true"></i> Maintenance</h2>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("admin.php") ?>">
                <h2>back to admin page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h2>
            </a>
        </div>
    </div>

    <div class="jumbotron pt-2 pb-5 bg-white">
        <h5 class="collapse text-justify text-muted" id="collapseJumbo">
            Distribution of messages to users is based on their relationship to a subject (distribution list).
            Users cannot "leave" conversation; only an administrator can remove them from a distribution list.
            When a user deletes a notification, messages can still be read by using the "show deleted" option.&nbsp;
            Delete subjects and messages everyone read, have empty distribution list or are simply
            outdated in order to reduce the database size.</h5>
        <hr>
        <button class="collapseIcon" data-toggle="collapse" data-target="#collapseJumbo" aria-expanded="false" aria-controls="collapseJumbo">
            <span id="collapseIconOpen" class="text-muted">show details <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></span>
            <span id="collapseIconClose" class="text-muted">hide details <i class="fa fa-arrow-circle-up" aria-hidden="true"></i></span>
        </button>
    </div>

    <div class="row">
        <div class="col-md-8"><h3>List of subjects</h3></div>
        <div class="col-md-4"><input class="form-control " id="myInput" type="text" placeholder="Search.."/></div>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>name</th>
            <th>last update</th>
            <th>last user</th>
            <th class="text-center">members</th>
            <th class="text-center">unread</th>
            <th class="text-right">edit</th>
        </tr>
        </thead>

        <tbody id="myTable">
        <?php foreach ($subjectList as $subjectItem) {
            $nbMembers = sizeof(fctDistributionUsersIn($subjectItem['sub_id']));
            ?>
            <tr>
                <td><?= $subjectItem["sub_name"] ?></td>
                <td><?= date("d/m/Y H:i", strtotime($subjectItem["sub_lastdate"])) ?></td>
                <td><?= $subjectItem["usr_name"] ?> <?= $subjectItem["usr_lastname"] ?></td>
                <td class="text-center"><?= $nbMembers ?></td>
                <td class="text-center"><?= fctNotificationCount(-1, $subjectItem["sub_id"],1) ?>/<?= fctNotificationCount(-1, $subjectItem["sub_id"],0) ?></td>
                <td class="text-right">
                    <a class="btn btn-sm btn-outline-primary" href="?id=<?= fctUrlOpensslCipher("maintenanceDetail.php," . $subjectItem["sub_id"]) ?>">
                        <i class="fas fa-edit"></i>
                        <small> Edit</small>
                    </a>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>

    <div class="col mt-4">
        <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("admin.php") ?>">
            <h4>back to admin page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h4>
        </a>
    </div>
</div>

<?= fctFilterJS(); ?>