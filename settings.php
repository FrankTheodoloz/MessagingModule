<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 18/08/2018
 * Time: 23:08
 */

global $pageParameter;

$typesList = fctSettingTypeList();
$pageParameter ? $selectedType = $pageParameter : $selectedType = $typesList[0][0];
$settingsList = fctSettingList($selectedType);
?>

<div class="container">

    <div class="row mt-4">
        <div class="col-md-6">
            <h2><i class="fas fa-wrench text-primary" aria-hidden="true"></i> Settings</h2>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("admin.php") ?>">
                <h2>back to admin page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h2>
            </a>
        </div>
    </div>

    <div class="jumbotron pt-2 pb-5 bg-white">
        <h5 class="collapse text-justify" style="width: 90%;" id="collapseJumbo">
            Here are some settings that can be changed from the web interface.</h5>
        <hr>
        <button class="collapseIcon" data-toggle="collapse" data-target="#collapseJumbo" aria-expanded="false" aria-controls="collapseJumbo">
            <span id="collapseIconOpen" class="text-muted">show details <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></span>
            <span id="collapseIconClose" class="text-muted">hide details <i class="fa fa-arrow-circle-up" aria-hidden="true"></i></span>
        </button>
    </div>

    <div class="row mt-4">
        <div class="col-md">
            <div class="card">

                <div class="card-header"><h2> Site settings</h2>
                    <select class="form-control form-control-sm form" onchange="location=this.value">
                        <?php foreach ($typesList as $typeItem) {
                            $i=1;?>
                            <option value=".?id=<?= fctUrlOpensslCipher("settings.php," . $typeItem['set_type']) ?>" <?= $typeItem["set_type"] == $selectedType ? "selected" : "" ?>><?= $typeItem["set_type"] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <form name="frmSettingsEdit" id="frmSettingsEdit" action="adminPost.php" target="_self" method="post">
                    <input type="hidden" name="action" value="settingsEdit">
                    <input type="hidden" name="type" value="<?=$typeItem['set_type']?>">

                    <div class="card-body">
                        <?php foreach ($settingsList as $settingItem) { ?>
                            <div class="form-row">
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend"><span class="input-group-text"><?= $settingItem['set_name'] ?></span></div>
                                        <input type="text" class="form-control" name="data[][<?= $settingItem['set_id'] ?>]" maxlength="255" value="<?= $settingItem['set_value'] ?>">
                                    </div>

                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="card-footer text-right">
                        <a href="?id=<?= fctUrlOpensslCipher("admin.php") ?>">
                            <button type="reset" class="btn btn-danger"><i class="fa fa-times-circle" aria-hidden="true"></i> Cancel</button>
                        </a>
                        <button type="submit" class="btn btn-primary"> Submit</button>
                    </div>
                </form>
            </div>
        </div>


    </div>

    <div class="col mt-4">
        <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("admin.php") ?>">
            <h4>back to admin page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h4>
        </a>
    </div>
</div>

