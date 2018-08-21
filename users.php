<?php
/**
 * Subject: Users Administration
 * User: Frank
 * Date: 05/08/2018
 * Time: 21:34
 */
global $pageParameter;
global $pageStatus;
$infoMessage = "";

if (!$pageStatus == 0) {
    $record = fctUserList($pageParameter);
    $infoMessage = "User #" . $record[0]['usr_id'] . " " . $record[0]['usr_name'] . " " . $record[0]['usr_lastname'] . " " . $pageStatus;
    $pageStatus = "";
}

$userList = fctUserList();

?>

    <div class="container">

        <div class="row mt-4">
            <div class="col-md-6">
                <h2><i class="fas fa-user text-primary" aria-hidden="true"></i> Users administration</h2>
            </div>
            <div class="col-md-6 text-right">
                <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("admin.php") ?>">
                    <h2>back to admin page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h2>
                </a>
            </div>
        </div>

        <div class="jumbotron pt-2 pb-5 bg-white">
            <h5 class="collapse text-justify text-muted" id="collapseJumbo">
                Users need to be registered in order to authenticate and use the application.
                There is no self-enrollment feature available yet, thus each need to be created
                by a member of the ADMIN group.&nbsp; Note that some restrictions apply for the
                SUPER and ADMIN users as they are needed by the system.</h5>
            <hr>
            <button class="collapseIcon" data-toggle="collapse" data-target="#collapseJumbo" aria-expanded="false" aria-controls="collapseJumbo">
                <span id="collapseIconOpen" class="text-muted">show details <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></span>
                <span id="collapseIconClose" class="text-muted">hide details <i class="fa fa-arrow-circle-up" aria-hidden="true"></i></span>
            </button>
        </div>

        <?= $infoMessage ? '<div class="alert alert-success alert-dismissible">' . $infoMessage . '</div>' : "" ?>

        <div class="row">
            <div class="col-md-8"><h2>List of Users</h2></div>
            <div class="col-md-4"><input class="form-control " id="myInput" type="text" placeholder="Search.."/></div>
        </div>
        <table class="table table-striped" id="myTable">
            <thead>
            <tr>
                <th>avatar</th>
                <th>name, lastname</th>
                <th>email</th>
                <th class="text-center">active</th>
                <th></th>

            </tr>
            </thead>
            <tbody>

            <?php
            foreach ($userList as $item) {

                $item['usr_avatar'] ? $image = '<img id="avatar" src="' . CONST_IMAGE_PATH.$item['usr_avatar'] . '" alt="' . $item['usr_name'] . " " . $item['usr_lastname'] . '">' : $image = '<img id="avatar" avatar="' . $item['usr_name'] . " " . $item['usr_lastname'] . '" alt="' . $item['usr_name'] . " " . $item['usr_lastname'] . '">';

                $item["usr_active"] == 1 ? $icon = "fa fa-check text-success" : $icon = "fa fa-times-circle text-danger";
                echo '<tr>
            <td>' . $image . '</td>
            <td>' . $item["usr_name"] . '<br>' . $item["usr_lastname"] . '</td>
            <td class="align-middle">' . $item["usr_email"] . '</td>
            <td class="align-middle text-center"><h3><i class="' . $icon . '"></i></h3></td>
            <td class="align-middle text-right">
                <a class="btn btn-sm btn-outline-primary" href="?id=' . fctUrlOpensslCipher("userDetail.php," . $item["usr_id"]) . '">
                    <i class="fas fa-edit"></i><small> Edit</small>
                </a>
            </td>
        </tr>';
            }
            ?>

            </tbody>

        </table>

        <div class="text-right">
            <a href="?id=<?= fctUrlOpensslCipher("userDetail.php") ?>">
                <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add User</button>
            </a>
        </div>

        <div class="col mt-4">
            <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("admin.php") ?>">
                <h4>back to admin page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h4>
            </a>
        </div>
    </div>

<?= fctFilterJS(); ?>