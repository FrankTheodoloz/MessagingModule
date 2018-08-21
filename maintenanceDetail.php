<?php
/**
 * Subject: Administration of subject members (Distribution lists) : Detail
 * User: Frank
 * Date: 18/08/2018
 * Time: 21:28
 */

global $pageParameter;
$subjectId = $pageParameter;

$subjectDetails = fctSubjectDetails($subjectId);
$userInDistribution = fctDistributionUsersIn($subjectId);
$userNotInDistribution = fctDistributionUsersNotIn($subjectId);
?>

<div class="container ">

    <div class="row mt-4">
        <div class="col-md-6">
            <h2><i class="fas fa-users text-primary" aria-hidden="true"></i> Distribution list details</h2>
        </div>

        <div class="col-md-6 text-right">
            <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("maintenance.php") ?>">
                <h2>back to maintenance page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h2>
            </a>
        </div>
    </div>

    <div class="jumbotron pt-2 pb-5 bg-white">
        <h5 class="collapse text-justify text-muted" id="collapseJumbo">The subject name can be changed, as well as the user membership to the distribution list (subject). Note that only administrators can remove users from a subject. This process also
            removes message notifications related to the subject, even if they have not been read.</h5>
        <hr/>

        <button class="collapseIcon" data-toggle="collapse" data-target="#collapseJumbo" aria-expanded="false" aria-controls="collapseJumbo">
            <span id="collapseIconOpen" class="btn text-muted">show details <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></span>
            <span id="collapseIconClose" class="btn text-muted">hide details <i class="fa fa-arrow-circle-up" aria-hidden="true"></i></span>
        </button>
    </div>

    <div class="card">
        <div class="card-header"><h4><strong><?= $subjectDetails[0]["sub_name"] ?></strong> Subject details</h4></div>

        <form name="frmGroupEdit" id="frmGroupEdit" action="adminPost.php" target="_self" method="post">
            <input type="hidden" name="action" value="subjectEdit">
            <input type="hidden" name="subjectId" value="<?= $subjectId ?>">

            <div class="card-body">
                <div class="form-row">
                    <div class="col">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text">Name</span></div>
                            <input type="name" class="form-control" name="name" maxlength="32" value="<?= $subjectDetails[0]['sub_name'] ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <div class="row mt-4">
        <div class="col-md-8"><h3>List of members</h3></div>
        <div class="col-md-4"><input class="form-control " id="myInput" type="text" placeholder="Search.."/></div>
    </div>

    <form name="frmDistributionEdit" id="frmDistributionEdit" action="messagePost.php" target="_self" method="post">
        <input type="hidden" name="action" value="distributionRemove">
        <input type="hidden" name="subjectId" value="<?= $subjectId ?>">

        <table class="table table-sm table-striped" id="myTable">
            <thead>
            <tr>
                <th>select</th>
                <th>name</th>
                <th>lastname</th>
                <th>email</th>
                <th class="text-center">unread</th>
                <th class="text-center">active</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($userInDistribution as $userItem) {
                $userItem["usr_active"] == 1 ? $icon = "fa fa-check text-success" : $icon = "fa fa-times-circle text-danger";
                ?>

                <tr>
                    <td><input type="checkbox" name="to[]" value="<?= $userItem["usr_id"] ?>"></td>
                    <td><?= $userItem["usr_name"] ?></td>
                    <td><?= $userItem["usr_lastname"] ?></td>
                    <td><?= $userItem["usr_email"] ?></td>
                    <td class="text-center"><?= fctNotificationCount($userItem["usr_id"], $subjectId) ?></td>
                    <td class="text-center"><h3><i class="<?= $icon ?>"></i></h3></td>
                </tr>
            <?php } ?>

            </tbody>
        </table>

        <div class="container text-right">
            <button type="submit" class="btn btn-primary"><i class="fas fa-minus "></i> Remove selected users</button>
        </div>
    </form>

    <div class="col mt-4">
        <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("maintenance.php") ?>">
            <h4>back to maintenance page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h4>
        </a>
    </div>
</div>

<?= fctFilterJS(); ?>
