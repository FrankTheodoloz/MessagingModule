<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 06/08/2018
 * Time: 16:19
 */
global $pageParameter;
global $pageStatus;

$restrictedUser = false;
$newUser = false;

if ($pageParameter > 0) {
    //an Id was given
    $id = $pageParameter;
    $userDetails = fctUserList($id);

    //ADMIN and SUPER Users cannot have their names changed
    strtoupper($userDetails[0]["usr_name"]) == 'ADMIN' || strtoupper($userDetails[0]["usr_name"]) == 'SUPER' ? $restrictedUser = true : $restrictedUser = false;

} else if ($pageParameter == 0) {
    $newUser = true;
}
?>

<div class="container">

    <div class="row mt-4">
        <div class="col-md-6">
            <h2><i class="fas fa-user text-primary" aria-hidden="true"></i> User details</h2>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("users.php") ?>">
                <h2>back to users page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h2>
            </a>
        </div>
    </div>

    <div class="jumbotron pt-2 pb-5 bg-white">
        <h5 class="collapse text-justify text-muted" id="collapseJumbo">
            User details can be changed here.<br/>Note that users cannot change their email by themselves.</h5>
        <hr/>
        <button class="collapseIcon" data-toggle="collapse" data-target="#collapseJumbo" aria-expanded="false" aria-controls="collapseJumbo">
            <span id="collapseIconOpen" class="text-muted">show details <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></span>
            <span id="collapseIconClose" class="text-muted">hide details <i class="fa fa-arrow-circle-up" aria-hidden="true"></i></span>
        </button>
    </div>

    <div class="card">

        <div class="card-header">
            <h4>
                <strong><?= $newUser ? "New " : $userDetails[0]["usr_name"] . " " . $userDetails[0]["usr_lastname"] ?></strong> User details

                <?php if (!$restrictedUser && !$newUser) { ?>
                    <form name="activateForm" action="userEdit.php" target="_self" method="post">
                        <input type="hidden" name="action" value="activate">
                        <input type="hidden" name="id" value="<?= $userDetails[0]['usr_id'] ?>">
                        <input type="hidden" name="active" value="<?= $userDetails[0]['usr_active'] == 0 ? 1 : 0 ?>">
                        <div class="btn-group float-right">
                            <button type="submit" class="btn btn-sm btn-success <?= $userDetails[0]['usr_active'] == 0 ?: "active" ?>">
                                Enable<?= $userDetails[0]['usr_active'] == 0 ? "" : "d" ?></button>
                            <button type="submit" class="btn btn-sm btn-danger <?= $userDetails[0]['usr_active'] == 1 ?: "active" ?>">
                                Disable<?= $userDetails[0]['usr_active'] == 1 ? "" : "d" ?></button>
                        </div>
                    </form>

                <?php } ?>
            </h4>
        </div>

        <form name="frmUserEdit" id="frmUserEdit" action="userEdit.php" target="_self" method="post">
            <input type="hidden" name="id" value="<?= $newUser ? 0 : $userDetails[0]["usr_id"] ?>">
            <input type="hidden" name="action" value="<?= $newUser ? "new" : "update" ?>">

            <div class="card-body">

                <div class="form-row">
                    <div class="col">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text">Name</span></div>
                            <input type="name" class="form-control" name="name" maxlength="64" id="name" value="<?= $newUser ? "" : $userDetails[0]["usr_name"] ?>" <?= $restrictedUser ? "disabled" : "" ?> required>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text">Lastname</span></div>
                            <input type="text" class="form-control" name="lastname" maxlength="64" id="lastname" value="<?= $newUser ? "" : $userDetails[0]["usr_lastname"] ?>" <?= $restrictedUser ? "disabled" : "" ?> required>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text">Email</span></div>
                            <input type="email" class="form-control" name="email" maxlength="128" id="email" <?= $newUser ? '' : 'value="' . $userDetails[0]["usr_email"] . '" disabled' ?>>
                            <?= $newUser ? '' : '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEmailChange"><i class="fas fa-pen "></i> change...</button>' ?>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text">Password</span></div>

                            <input type="password" class="form-control" <?= $newUser ? 'id="inputPwd1" name="password" value=""' : 'name="" value="password" disabled' ?>>
                            <?= $newUser ? '<input type="password" id="inputPwd2" class="form-control" value="" placeholder="Retype password">' : '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPwdChange"><i class="fas fa-pen "></i> change...</button>' ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a class="btn btn-danger" href="?id=<?= fctUrlOpensslCipher("users.php") ?>">Cancel</a>
                <button type="submit" <?= $newUser ? 'id="btnPwdSubmit"' : ''; ?> class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <?php if (!$newUser) { ?>
        <!-- Modal Email change form -->
        <!-- <a href="#" data-toggle="modal" data-target="#myModalId"> -->
        <div class="modal" id="modalEmailChange">
            <div class="modal-dialog modal-md modal-dialog-centered">

                <div class="modal-content">
                    <form name="frmEmailEdit" action="userEdit.php" target="_self" method="post">
                        <input type="hidden" name="action" value="email">
                        <input type="hidden" name="id" value="<?= $userDetails[0]["usr_id"] ?>">

                        <!-- Modal Header -->
                        <div class="modal-header bg-dark text-light">
                            <h4 class="modal-title"> Email change</h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="form-group mt-2" style="height: 40px;">
                                <input type="email" name="email" class="form-control mb-4" value="<?= $userDetails[0]["usr_email"] ?>" required>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-danger" data-toggle="modal" data-target="#modalEmailChange">Cancel</button>
                            <button type="submit" id="btnEmailSubmit" class="btn btn-primary">Submit</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>

        <!-- Modal Password change form -->
        <div class="modal" id="modalPwdChange">
            <div class="modal-dialog modal-sm modal-dialog-centered">

                <div class="modal-content">
                    <form name="frmPwdChange" id="frmPwdChange" action="userEdit.php" target="_self" method="post">
                        <input type="hidden" name="action" value="password">
                        <input type="hidden" name="id" value="<?= $userDetails[0]["usr_id"] ?>">

                        <!-- Modal Header -->
                        <div class="modal-header bg-dark text-light">
                            <h4 class="modal-title">Password change</h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="form-group mt-2" style="height: 120px;">
                                <input type="password" name="password" id="inputPwd1" class="form-control mb-4" value="" placeholder="Password" required>
                                <input type="password" id="inputPwd2" class="form-control" placeholder="Retype password" required>
                                <div class="invalid-feedback">Passwords do not match</div>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-danger" data-toggle="modal" data-target="#modalPwdChange">Cancel</button>
                            <button type="submit" id="btnPwdSubmit" class="btn btn-primary">Submit</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>

    <?php } ?>

    <script src="js/check-passwords.js"></script>
</div>

