<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 06/08/2018
 * Time: 16:19
 */
global $pageParameter;
global $pageStatus;
$alert = 0;
if ($pageParameter > 0) {
    //an Id was given
    $id = $pageParameter;
    $groupDetails = fctGroupList($id);
    $groupMembers = fctUsersFromGroup($id);

} else if ($pageParameter == 0) {
    //a new Id creation
}

?>

<div class="container container-fluid mt-4 mb-4">

    <div class="row mb-4">
        <div class="col"><h2><strong><?= $pageParameter > 0 ? $groupDetails[0]["grp_name"] : "New " ?></strong> Group details</h2></div>
    </div>

    <form name="editForm" action="<?= $pageParameter > 0 ? "groupEdit.php" : "groupAdd.php" ?>" target="_self" method="post">

        <input type="hidden" name="id" value="<?= $pageParameter > 0 ? $groupDetails[0]["grp_id"] : "" ?>">

        <div class="form-row mb-2">
            <div class="col col-2">
                Group name
            </div>
            <div class="col col-4">
                <input type="name" class="form-control" name="name" size="32" id="name" value="<?= $pageParameter > 0 ? $groupDetails[0]["grp_name"] : "" ?>" required>
            </div>
        </div>
        <div class="form-row mb-2">
            <div class="col col-2">
                Description
            </div>
            <div class="col col-4">
                <input type="description" class="form-control" name="description" size="64" id="description" value="<?= $pageParameter > 0 ? $groupDetails[0]["grp_description"] : "" ?>" required>
            </div>
        </div>

        <div class="form-row mb-2">
            <div class="col">
                <button type="submit" class="btn btn-success">Submit</button>
                <a href="?id=<?= fctUrlOpensslCipher("groups.php") ?>">
                    <button type="button" class="btn btn-danger"><i class="fas fa-times-circle "></i> Cancel</button>
                </a>
            </div>
        </div>
    </form>

    <form name="memberForm" action="groupMemberRemove.php" target="_self" method="post">

        <input type="hidden" name="groupId" value="<?= $pageParameter > 0 ? $groupDetails[0]["grp_id"] : "" ?>">

        <div class="row mt-4">
            <div class="col"><h3>Members</h3></div>
            <div class="col"></div>
            <div class="col"><input class="form-control " id="myInput" type="text" placeholder="Search.."/></div>
        </div>

        <table class="table table-sm table-striped" id="myTable">
            <thead>
            <tr>
                <th>select</th>
                <th>id</th>
                <th>name</th>
                <th>lastname</th>
                <th>email</th>
                <th colspan="2">active</th>

            </tr>
            </thead>
            <tbody>

            <?php
            if ($pageParameter > 0) {
                foreach ($groupMembers as $item) {
                    echo '<tr>
                <td><input type="checkbox" id="select" name="usr[]" value="' . $item["usr_id"] . '"></td>
                <td>' . $item["usr_id"] . '</td><td>' . $item["usr_name"] . '</td><td>' . $item["usr_lastname"] . '</td><td>' . $item["usr_email"] . '</td><td>' . $item["usr_active"] . '</td>
            </tr>';
                }
            }
            ?>

            </tbody>

        </table>
        <?php
        if ($pageParameter > 0) {
            echo '<a href="?id=' . fctUrlOpensslCipher("groupMember.php," . $id) . '">
            <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add Member</button>
        </a>
        <button type="submit" class="btn btn-danger"><i class="fas fa-minus "></i> Remove selected users</button>
        ';
        } ?>
    </form>
</div>

<?= fctFilterJS(); ?>
