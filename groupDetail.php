<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 06/08/2018
 * Time: 16:19
 */
global $pageParameter;
global $pageStatus;

$restrictedGroup = false;
$newGroup = false;

if ($pageParameter > 0) {
    //an Id was given
    $id = $pageParameter;
    $groupDetails = fctGroupList($id);
    $groupMembers = fctUsersFromGroup($id);

    //ADMIN and USER groups cannot have their names changed
    strtoupper($groupDetails[0]["grp_name"]) == 'ADMIN' || strtoupper($groupDetails[0]["grp_name"]) == 'USER' ? $restrictedGroup = true : $restrictedGroup = false;

} else if ($pageParameter == 0) {
    //a new Id creation
    $newGroup = true;
}

?>

<div class="container ">

    <div class="row mt-4">
        <div class="col-md-6">
            <h2><i class="fas fa-users text-primary" aria-hidden="true"></i> Group details</h2>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("groups.php") ?>">
                <h2>back to Groups page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h2>
            </a>
        </div>
    </div>

    <div class="jumbotron pt-2 pb-5 bg-white">
        <h5 class="collapse text-justify text-muted" id="collapseJumbo">Group details and their user members can be changed here.<br/>
            Note that the ADMIN and SUPER users cannot be removed from the ADMIN and USER groups.</h5>
        <hr/>
        <button class="collapseIcon" data-toggle="collapse" data-target="#collapseJumbo" aria-expanded="false" aria-controls="collapseJumbo">
            <span id="collapseIconOpen" class="btn text-muted">show details <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></span>
            <span id="collapseIconClose" class="btn text-muted">hide details <i class="fa fa-arrow-circle-up" aria-hidden="true"></i></span>
        </button>
    </div>

    <div class="card">
        <div class="card-header"><h4><strong><?= $newGroup ? "New " : $groupDetails[0]["grp_name"] ?></strong> Group details</h4></div>

        <form name="frmGroupEdit" id="frmGroupEdit" action="groupEdit.php" target="_self" method="post">
            <input type="hidden" name="id" value="<?= $newGroup ? 0 : $groupDetails[0]["grp_id"] ?>">
            <input type="hidden" name="action" value="<?= $newGroup ? "new" : "update" ?>">

            <div class="card-body">

                <div class="form-row">
                    <div class="col">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text">Name</span></div>
                            <input type="name" class="form-control" name="name" maxlength="32" value="<?= $newGroup ? "" : $groupDetails[0]["grp_name"] ?>" <?= $restrictedGroup ? "disabled" : "" ?> required>
                        </div>
                    </div>

                </div>
                <div class="form-row">
                    <div class="col">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text">Description</span></div>
                            <input type="name" class="form-control" name="description" maxlength="64" value="<?= $newGroup ? "" : $groupDetails[0]["grp_description"] ?>" <?= $restrictedGroup ? "disabled" : "" ?> required>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer text-right">
                <a class="btn btn-danger" href="?id=<?= fctUrlOpensslCipher("groups.php") ?>"><i class="fas fa-times-circle "></i> Cancel</a>
                <button type="submit" class="btn btn-primary" <?= $restrictedGroup ? 'disabled' : '' ?>>Submit</button>
            </div>

        </form>
    </div>

    <?php if (!$newGroup) { ?>
        <form name="frmMemberEdit" action="groupEdit.php" target="_self" method="post">
            <input type="hidden" name="action" value="memberRemove">
            <input type="hidden" name="id" value="<?= $groupDetails[0]["grp_id"] ?>">

            <div class="row mt-4">
                <div class="col-md-8"><h3>List of members</h3></div>
                <div class="col-md-4"><input class="form-control " id="myInput" type="text" placeholder="Search.."/></div>
            </div>

            <table class="table table-sm table-striped" id="myTable">
                <thead>
                <tr>
                    <th>select</th>
                    <th>name</th>
                    <th>lastname</th>
                    <th>email</th>
                    <th class="text-center">active</th>

                </tr>
                </thead>
                <tbody>

                <?php foreach ($groupMembers as $item) {

                    //test if editing ADMIN or SUPER users -> cannot be removed from ADMIN and USER groups
                    strtoupper($item["usr_name"]) == 'ADMIN' || strtoupper($item["usr_name"]) == 'SUPER' ? $restrictedUser = true : $restrictedUser = false;
                    $item["usr_active"] == 1 ? $icon = "fa fa-check text-success" : $icon = "fa fa-times-circle text-danger";
                    ?>

                    <tr>
                        <td><input type="checkbox" name="usr[]" value="<?= $item["usr_id"] ?>" <?= ($restrictedGroup && $restrictedUser) ? 'Disabled' : '' ?>>
                        </td>
                        <td><?= $item["usr_name"] ?></td>
                        <td><?= $item["usr_lastname"] ?></td>
                        <td><?= $item["usr_email"] ?></td>
                        <td class="align-middle text-center"><h3><i class="<?= $icon ?>"></i></h3></td>
                    </tr>
                <?php } ?>

                </tbody>

            </table>

            <div class="container text-right">
                <button type="submit" class="btn btn-danger"><i class="fas fa-minus "></i> Remove selected users</button>

                <a href="?id=<?= fctUrlOpensslCipher("groupMember.php," . $id) ?>">
                    <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add Member</button>
                </a>
            </div>

        </form>
    <?php } ?>

</div>

<?= fctFilterJS(); ?>
<script>
    // data-toggle="tooltip" data-placement="right" title="Close"
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>