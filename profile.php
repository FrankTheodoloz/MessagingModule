<?php
$user = fctUserList($_SESSION['user']['id']);

/**
 * https://mdbootstrap.com/components/forms/
 */
?>

<div class="container container-fluid" style="margin-top:20px; margin-bottom:50px">

    <h2>Profile Edit</h2>
        <form name="profileForm" action="profileEdit.php" target="_self" method="post">
            <div class="card card-content">

                <!-- Card body -->
                <div class="card-body">
                    <div class="form-row mb-4">
                        <div class="col">
                            <!-- First name -->
                            <input type="text" name="name" class="form-control" value="<?= $user[0]['usr_name'] ?>" required>
                        </div>
                        <div class="col">
                            <!-- Last name -->
                            <input type="text" name="lastname" class="form-control" value="<?= $user[0]['usr_lastname'] ?>" required>
                        </div>
                    </div>
                    <!-- E-mail -->
                    <input type="email" name="email" class="form-control" value="<?= $user[0]['usr_email'] ?>" disabled>
                    <small class="form-text text-muted mb-4">
                        Please contact the administrator should you need to update the email address.
                    </small>
                    <!-- Password -->
                    <input type="password" name="pswd" class="form-control mb-4" placeholder="Password" required>
                    <!-- input type="passwordCheck" id="defaultRegisterFormPasswordCheck" class="form-control" placeholder="Retype Password" required -->
                </div>
                <!-- Card footer -->
                <div class="card-footer">
                    <button class="btn btn-success" type="submit">Submit</button>
                </div>
            </div>
    </form>
</div>
