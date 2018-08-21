<?php
/**
 * Subject: Administration of Groups members : User selection
 * User: Frank
 * Date: 07/08/2018
 * Time: 21:59
 */

global $pageParameter;

$alert = 0;
if ($pageParameter > 0) {
    //an Id was given
    $id = $pageParameter;
    $groupDetails = fctGroupList($id);
    $userList = fctUsersNotGroup($id);

} else if ($pageParameter = 0) {
    //a new Id creation
}

?>

<div class="container">

    <div class="row mt-4">
        <div class="col-md-6">
            <h2><i class="fas fa-user text-primary" aria-hidden="true"></i> User selection</h2>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("groupDetail.php," . $id) ?>">
                <h2>back to group page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h2>
            </a>
        </div>
    </div>

    <div class="jumbotron pt-2 pb-5 bg-white">
        <h5 class="collapse text-justify text-muted" id="collapseJumbo">Select one or several users to add to the group.</h5>
        <hr>
        <button class="collapseIcon" data-toggle="collapse" data-target="#collapseJumbo" aria-expanded="false" aria-controls="collapseJumbo">
            <span id="collapseIconOpen" class="text-muted">show details <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></span>
            <span id="collapseIconClose" class="text-muted">hide details <i class="fa fa-arrow-circle-up" aria-hidden="true"></i></span>
        </button>
    </div>

    <form name="editForm" action="groupPost.php" target="_self" method="post">
        <input type="hidden" name="action" value="memberAdd">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="row">
            <div class="col-md-8"><h3>Available users for Group <?= $groupDetails[0]['grp_name'] ?></h3></div>
            <div class="col-md-4"><input class="form-control " id="myInput" type="text" placeholder="Search.."/></div>
        </div>

        <table class="table table table-striped" id="myTable">
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

            <?php foreach ($userList as $item) {
                $item["usr_active"] == 1 ? $icon = "fa fa-check text-success" : $icon = "fa fa-times-circle text-danger";
                ?>
                <tr>
                    <td><input type="checkbox" id="select" name="usr[]" value="<?= $item["usr_id"] ?>" /></td>
                    <td><?= $item["usr_name"] ?></td>
                    <td><?= $item["usr_lastname"] ?></td>
                    <td><?= $item["usr_email"] ?></td>
                    <td class="text-center"><h3><i class="<?= $icon ?>"></i></h3></td>
                </tr>
            <?php } ?>

            </tbody>

        </table>

        <div class="container text-right">
            <a href="?id=<?= fctUrlOpensslCipher("groupDetail.php," . $id) ?>">
                <button type="button" class="btn btn-danger"><i class="fas fa-times-circle "></i> Cancel</button>
            </a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

    <div class="col mt-4">
        <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("groupDetail.php," . $id) ?>">
            <h4>back to group page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h4>
        </a>
    </div>
</div>
