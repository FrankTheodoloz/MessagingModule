<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 07/08/2018
 * Time: 21:59
 */
global $pageParameter;
global $pageStatus;

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

<div class="container container-fluid mt-4 mb-4">
    <form name="editForm" action="groupMemberAdd.php" target="_self" method="post">

        <div class="row">
            <div class="col"><h2>Add member to <?= $groupDetails[0]['grp_name'] ?></h2></div>
            <div class="col"></div>
            <div class="col"><input class="form-control " id="myInput" type="text" placeholder="Search.."/></div>
        </div>

        <table class="table table table-striped" id="myTable">
            <thead>
            <tr>
                <th>select</th>
                <th>id</th>
                <th>name</th>
                <th>lastname</th>
                <th>email</th>
                <th>active</th>

            </tr>
            </thead>
            <tbody>

            <?php
            foreach ($userList as $item) {
                echo '<tr><td><input type="checkbox" id="select" name="usr[]" value="' . $item["usr_id"] . '"></td><td>' . $item["usr_id"] . '</td><td>' . $item["usr_name"] . '</td><td>' . $item["usr_lastname"] . '</td><td>' . $item["usr_email"] . '</td><td>' . $item["usr_active"] . '</td>
                    <td><a class="badge badge-primary" href="?id=' . fctUrlOpensslCipher("userDetail.php," . $item["usr_id"]) . '"><i class="fas fa-edit"></i><small> Edit</small></a></td>
                  </tr>';
            }
            ?>

            </tbody>

        </table>

        <input type="hidden" name="groupId" value="<?= $id ?>">

        <button type="submit" class="btn btn-success">Submit</button>
        <a href="?id=<?= fctUrlOpensslCipher("groupDetail.php," . $id) ?>">
            <button type="button" class="btn btn-danger"><i class="fas fa-times-circle "></i> Cancel</button>
        </a>
    </form>
</div>
