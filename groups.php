<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 05/08/2018
 * Time: 22:08
 */
global $pageParameter;
global $pageStatus;
$infoMessage = "";

if (!$pageStatus == 0) {
    $record = fctGroupList($pageParameter);
    $infoMessage = "Group #" . $record[0]['grp_id'] . " " . $record[0]['grp_name'] . " (" . $record[0]['grp_description'] . ") " . $pageStatus;
    $pageStatus = "";
}

$groupList = fctGroupList();

?>

<div class="container container-fluid mt-4 mb-4">

    <div class="row">
        <div class="col"><h2>Groups List</h2></div>
        <div class="col"></div>
        <div class="col"><input class="form-control " id="myInput" type="text" placeholder="Search.."/></div>
    </div>
    <?= $infoMessage ? '<div class="alert alert-success alert-dismissible">' . $infoMessage . '</div>' : "" ?>
    <table class="table table-striped" id="myTable">
        <thead>
        <tr>
            <th>id</th>
            <th>name</th>
            <th>description</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($groupList as $item) {
            echo '<tr><td>' . $item["grp_id"] . '</td><td>' . $item["grp_name"] . '</td><td>' . $item["grp_description"] . '</td>
                    <td><a class="badge badge-primary" href="?id=' . fctUrlOpensslCipher("groupDetail.php," . $item["grp_id"]) . '"><i class="fas fa-edit"></i><small> Edit</small></a></td>
                  </tr>';
        }
        ?>
        </tbody>
    </table>

    <a href="?id=<?= fctUrlOpensslCipher("groupDetail.php") ?>">
        <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add Group</button>
    </a>
</div>

<?= fctFilterJS(); ?>
