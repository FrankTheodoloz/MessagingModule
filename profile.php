<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 05/08/2018
 * Time: 21:34
 */

$user = fctUserList($_SESSION['user']['id']);
strtoupper($user[0]['usr_name']) == "ADMIN" || strtoupper($user[0]['usr_name']) == "SUPER" ? $edit = false : $edit = true;
/**
 * https://mdbootstrap.com/components/forms/
 */
?>

<div class="container">

    <div class="row mt-4">
        <div class="col-md-6">
            <h2><i class="fas fa-user text-primary" aria-hidden="true"></i> My profile</h2>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("index.php") ?>">
                <h2>back to main page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h2>
            </a>
        </div>
    </div>

    <div class="jumbotron pt-2 pb-5 bg-white">
        <h5 class="collapse text-justify text-muted" id="collapseJumbo">
            The user name, lastname and password can be updated here.<br/>
            For a change of the email address please contact an administrator.</h5>
        <hr/>
        <button class="collapseIcon" data-toggle="collapse" data-target="#collapseJumbo" aria-expanded="false" aria-controls="collapseJumbo">
            <span id="collapseIconOpen" class="text-muted">show details <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></span>
            <span id="collapseIconClose" class="text-muted">hide details <i class="fa fa-arrow-circle-up" aria-hidden="true"></i></span>
        </button>
    </div>

    <div class="card">

        <div class="card-header"><h2> My user details</h2></div>

        <form name="frmUserEdit" action="userEdit.php" target="_self" method="post">
            <input type="hidden" name="id" value="<?= $user[0]['usr_id'] ?>">
            <input type="hidden" name="action" value="names">
            <input type="hidden" name="from" value="user">

            <div class="card-body">

                <div class="form-row mb-3">
                    <div class="col">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Name</span></div>
                            <input type="text" class="form-control" name="name" maxlength="64" value="<?= $user[0]["usr_name"] ?>" <?= $edit ? "" : "disabled" ?> required>
                        </div>
                    </div>
                </div>

                <div class="form-row mb-3">
                    <div class="col">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Lastname</span></div>
                            <input type="text" class="form-control" name="lastname" maxlength="64" value="<?= $user[0]["usr_lastname"] ?>" <?= $edit ? "" : "disabled" ?> required>

                        </div>
                    </div>
                </div>

                <div class="form-row mb-3">
                    <div class="col">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Email</span></div>
                            <input type="email" class="form-control" maxlength="128" value="<?= $user[0]["usr_email"] ?>" disabled>
                        </div>
                        <small id="emailHelp" class="form-text text-muted">Please contact an administrator should the email address needs to be changed.</small>

                    </div>
                </div>

                <div class="form-row mb-3">
                    <div class="col">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Password</span></div>
                            <input type="password" class="form-control" maxlength="60" value="Password" disabled>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPwdChange"><i class="fas fa-pen "></i> change...</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a class="btn btn-danger" href="?id=<?= fctUrlOpensslCipher("index.php") ?>">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Modal Password change form -->
    <div class="modal" id="modalPwdChange">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <form name="frmPwdChange" id="frmPwdChange" action="userEdit.php" target="_self" method="post">
                    <input type="hidden" name="id" value="<?= $user[0]["usr_id"] ?>">
                    <input type="hidden" name="from" value="user">
                    <input type="hidden" name="action" value="password">

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

</div>

<script src="js/check-passwords.js"></script>