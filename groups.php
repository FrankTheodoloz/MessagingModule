<?php
/**
 * Subject: Group Administration
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

<div class="container">

    <div class="row mt-4">
        <div class="col-md-6">
            <h2><i class="fas fa-users text-primary" aria-hidden="true"></i> Groups administration</h2>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("admin.php") ?>">
                <h2>back to admin page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h2>
            </a>
        </div>
    </div>

    <div class="jumbotron pt-2 pb-5 bg-white">
        <h5 class="collapse text-justify text-muted" id="collapseJumbo">
            Groups allow to manage user access to the messaging application and to the
            administrative functions.&nbsp; There is no other group functions yet available,
            consequently new groups have no effects.&nbsp; Note that restriction apply for
            the ADMIN and USER groups edit functions, since these are required for the system.</h5>
        <hr>
        <button class="collapseIcon" data-toggle="collapse" data-target="#collapseJumbo" aria-expanded="false" aria-controls="collapseJumbo">
            <span id="collapseIconOpen" class="text-muted">show details <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></span>
            <span id="collapseIconClose" class="text-muted">hide details <i class="fa fa-arrow-circle-up" aria-hidden="true"></i></span>
        </button>
    </div>

    <div class="row">
        <div class="col-md-8"><h3>List of Groups</h3></div>
        <div class="col-md-4"><input class="form-control " id="myInput" type="text" placeholder="Search.."/></div>
    </div>
    <table class="table table-striped" id="myTable">
        <thead>
        <tr>
            <th>name</th>
            <th>description</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($groupList as $item) {
            echo '<tr>
                    <td>' . $item["grp_name"] . '</td>
                    <td>' . $item["grp_description"] . '</td>
                    <td class="align-middle text-right">
                        <a class="btn btn-sm btn-outline-primary" href="?id=' . fctUrlOpensslCipher("groupDetail.php," . $item["grp_id"]) . '">
                            <i class="fas fa-edit"></i><small> Edit</small>
                        </a>
                    </td>
                  </tr>';
        }
        ?>
        </tbody>
    </table>

    <div class="text-right">
        <a href="?id=<?= fctUrlOpensslCipher("groupDetail.php") ?>">
            <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add Group</button>
        </a>
    </div>

</div>

<?= fctFilterJS(); ?>
