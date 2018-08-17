<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 03/08/2018
 * Time: 14:37
 */

($_SERVER["SCRIPT_FILENAME"] == __FILE__) ? header("location:.") : ""; //redirect to index if this file is called directly

if (isset($_POST['email']) && isset($_POST['pwd']) && !empty($_POST['email']) && !empty($_POST['pwd'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pwd = filter_var($_POST['pwd'], FILTER_SANITIZE_STRING);

    if (fctUserLogin($email, $pwd)) {
        unset($_SESSION['Error']);
        header("location:.");
    }
} else {
    // these variables must be declared
    $email = "";
    $pwd = "";
}
?>

<div class="container">

    <div class="modal" id="myModal">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <form name="loginForm" action="index.php" target="_self" method="post">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-light">
                        <h4 class="modal-title"><strong><?= fctSettingItem('SITE_CONFIG', 'SITE_NAME') ?></strong> Login</h4>
                    </div>
                    <!-- Error alert message -->
                    <?= fctLoginMessage(); ?>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="card mt-2" align="center" style="margin:auto; width:50%">
                            <div class="card-body">
                                <i class="far fa-user fa-5x text-secondary"></i>
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <input type="email" class="form-control mb-2" name="email" id="email" placeholder="Email" value="<?= $email ?>" required>

                            <input type="password" class="form-control" name="pwd" id="pwd" placeholder="Password" required>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS for modal display once page is loaded + autofocus
Refs
 1  https://stackoverflow.com/a/10234834
 2  https://stackoverflow.com/a/22208662
-->
<script type="text/javascript">
    $(window).on('load', function () {
        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
        $('#email').trigger('focus');
    });
</script>