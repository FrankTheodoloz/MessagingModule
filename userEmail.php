<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 07/08/2018
 * Time: 16:10
 */

global $pageParameter;
global $pageStatus;
$infoMessage = "";

$alert = 0;
if ($pageParameter > 0) {
    //an Id was given
    $id = $pageParameter;
    $userDetails = fctUserList($id);

} else if ($pageParameter = 0) {
    //a new Id creation
}
?>
<div class="container container-fluid mt-4 mb-4">

    <div class="row">
        <div class="col"><h2>Email update :: <?= $userDetails[0]["usr_name"]." ".$userDetails[0]["usr_lastname"] ?></h2></div>
    </div>

    <form name="editForm" action="userEmailEdit.php" target="_self" method="post">

        <input type="hidden" name="id" value="<?= $userDetails[0]["usr_id"] ?>">


        <div class="form-row mb-2">
            <div class="col col-2">
                Email
            </div>

            <div class="col col-4">
                <input type="email" class="form-control" name="email" size="128" id="email" value="<?=$userDetails[0]["usr_email"]?>" required>

            </div>

        </div>
        <div class="form-row mb-2">
            <div class="col">
                <button type="submit" class="btn btn-success">Submit</button>
                <a href="?id=<?= fctUrlOpensslCipher("userDetail.php," . $id) ?>">
                    <button type="button" class="btn btn-danger"><i class="fas fa-times-circle "></i> Cancel</button>
                </a>
            </div>
    </form>

</div>