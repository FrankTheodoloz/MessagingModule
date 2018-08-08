<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 06/08/2018
 * Time: 16:19
 */
global $pageParameter;
global $pageStatus;
$infoMessage = "";

$alert = 0;
if ($pageParameter > 0) {
    //an Id was given
    $id = $pageParameter;
    $userDetails = fctUserList($id);

    if (!$pageStatus == 0) {
        $record = fctUserList($pageParameter);
        $infoMessage = "User #" . $record[0]['usr_id'] . " " . $record[0]['usr_name'] . " " . $record[0]['usr_lastname'] . " " . $pageStatus;
        $pageStatus = "";
    }

} else if ($pageParameter = 0) {
    //a new Id creation
}
//<link rel="stylesheet" href="css/toggleSwitch.css"
?>
<div class="container container-fluid mt-4 mb-4">
    <form name="editForm" action="<?= $pageParameter > 0 ? "userEdit.php" : "userAdd.php" ?>" target="_self" method="post">

        <div class="row">

            <div class="col"><h2>User Details :: <?= $pageParameter > 0 ? $userDetails[0]["usr_name"] : "New item" ?></h2>
                <a class="badge badge-pill badge-success" href="?id=' . fctUrlOpensslCipher("userActivate.php," . $item["usr_id"]) . '"><small>ACTIF</small></a>

            </div>

            <div class="col align-bottom"><h2></h2>
                <div class="btn-group">
                    <button type="button" class="btn btn-success" >Enabled</button>
                    <button type="button" class="btn btn-danger">Disable</button>

                </div>
                <a href="?id=<?= fctUrlOpensslCipher("userActive.php," . $id) ?>">
                </a>
            </div>


        </div>

        <?= $infoMessage ? '<div class="alert alert-success alert-dismissible">' . $infoMessage . '</div>' : "" ?>

        <input type="hidden" name="id" value="<?= $pageParameter > 0 ? $userDetails[0]["usr_id"] : "" ?>">


        <div class="form-row mb-2">
            <div class="col col-2">
                Name
            </div>
            <div class="col col-4">
                <input type="name" class="form-control" name="name" size="64" id="name" value="<?= $pageParameter > 0 ? $userDetails[0]["usr_name"] : "" ?>" required>
            </div>
        </div>
        <div class="form-row mb-2">
            <div class="col col-2">
                Lastname
            </div>
            <div class="col col-4">
                <input type="lastname" class="form-control" name="lastname" size="64" id="lastname" value="<?= $pageParameter > 0 ? $userDetails[0]["usr_lastname"] : "" ?>" required>
            </div>
        </div>
        <div class="form-row mb-2">
            <div class="col col-2">
                Email
            </div>
            <div class="col col-4">
                <input type="email" class="form-control" name="email" size="128" id="email" value="<?= $pageParameter > 0 ? $userDetails[0]["usr_email"] : "" ?>" disabled>

            </div>
            <div class="col col-1">
                <a href="?id=<?= fctUrlOpensslCipher("userEmail.php," . $id) ?>">
                    <button type="button" class="btn btn-primary"><i class="fas fa-pen "></i> change...</button>
                </a>
            </div>
        </div>
        <div class="form-row mb-2">
            <div class="col col-2">
                Password
            </div>

            <div class="col col-4">
                <input type="password" class="form-control" name="" size="60" id="password" value="................" disabled>

            </div>
            <div class="col col-4">
                <a href="?id=<?= fctUrlOpensslCipher("userPwd.php," . $id) ?>">
                    <button type="button" class="btn btn-primary"><i class="fas fa-pen "></i> change...</button>
                </a>
            </div>
        </div>
        <div class="form-row mb-2">
            <div class="col">
                <button type="submit" class="btn btn-success">Submit</button>
                <a href="?id=<?= fctUrlOpensslCipher("users.php") ?>">
                    <button type="button" class="btn btn-danger"><i class="fas fa-times-circle "></i> Cancel</button>
                </a>
            </div>
    </form>

</div>