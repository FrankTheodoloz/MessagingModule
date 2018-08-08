<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 06/08/2018
 * Time: 16:19
 */
global $pageParameter;
global $pageStatus;
$alert = 0;
if ($pageParameter > 0) {
    //an Id was given
    $id = $pageParameter;
    $categoryDetails = fctCategoryList($id);

} else if ($pageParameter = 0) {
    //a new Id creation
}

?>

<div class="container container-fluid mr-auto mt-4 mb-4">

    <div class="row mb-4">
        <div class="col"><h2>Category Details :: <?= $pageParameter > 0 ? $categoryDetails[0]["cat_name"] : "New item" ?></h2></div>
    </div>

    <form name="editForm" action="<?= $pageParameter > 0 ? "categoryEdit.php" : "categoryAdd.php" ?>" target="_self" method="post">

        <input type="hidden" name="id" value="<?= $pageParameter > 0 ? $categoryDetails[0]["cat_id"] : "" ?>">

        <div class="form-row mb-2">
            <div class="col col-2">
                Category name
            </div>
            <div class="col col-4">
                <input type="name" class="form-control" name="name" size="32" id="name" value="<?= $pageParameter > 0 ? $categoryDetails[0]["cat_name"] : "" ?>" required>
            </div>
        </div>
        <div class="form-row mb-2">
            <div class="col col-2">
                Description
            </div>
            <div class="col col-4">
                <input type="description" class="form-control" name="description" size="64" id="description" value="<?= $pageParameter > 0 ? $categoryDetails[0]["cat_description"] : "" ?>" required>
            </div>
        </div>

        <div class="form-row mb-2">
            <div class="col">
                <button type="submit" class="btn btn-success">Submit</button>
                <a href="?id=<?= fctUrlOpensslCipher("categories.php") ?>">
                    <button type="button" class="btn btn-danger"><i class="fas fa-times-circle "></i> Cancel</button>
                </a>
            </div>
    </form>
</div>