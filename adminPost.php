<?php
/**
 * Subject: POST file for Message Admin
 * Usage: Check $_POST, insert to DB, reports error and redirect
 * User: Frank
 * Date: 19/08/2018
 * Time: 00:49
 */

session_start();

include_once("functionsSql.inc.php");
include_once("functionsHtml.inc.php");

$target = "admin.php";

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    isset($_POST['subjectId']) ? $subjectId = $_POST['subjectId'] : $subjectId = 0;

} else {
    $result[] = array("error", "Error", "Form ID error");
    goto error;
}

if ($action == 'subjectEdit') {
    $target = "maintenanceDetail.php";

    if (isset($_POST['subjectId']) && isset($_POST['name'])) {

        $subjectId = $_POST['subjectId'];
        $name = $_POST['name'];

        $sqlresult = fctSubjectEdit($subjectId, $name);

        if ($sqlresult == 1) {
            $result[] = array("success", "Success", "Subject renamed");
        } else {
            $result[] = array("error", "Error", "Subject could not be renamed");
        }

    } else {
        $result[] = array("error", "Error", "Form input error");

    }
} elseif ($action == 'settingsEdit') {
    $target = "settings.php";
    if (isset($_POST['data']) && isset($_POST['type'])) {
//$_POST Array ( [type] => SITE_CONFIG [data] => Array ( [0] => Array ( [12] => © 2018 — Frank Théodoloz ) [1] => Array ( [11] => MessagingModule ) ) )
        $type = $_POST['type'];
        $data = $_POST['data'];

        foreach ($data as $settingItem) {
            foreach ($settingItem as $rowItem) {
                $id = array_search($rowItem, $settingItem);
                $value = $rowItem;
                $sqlresult = fctSettingEdit($id, $value);
            }
        }
        if ($sqlresult >= 1) {
            $result[] = array("success", "Success", "Settings updated");
        } else {
            $result[] = array("error", "Error", "Settings could not be renamed");
        }

    } else {
        $result[] = array("error", "Error", "Form input error");

    }
} else {
    $result[] = array("error", "System error", "No POST action found<br/>or sent to wrong page.");
}

error:
$page = fctUrlOpensslCipher($target . "," . $subjectId . "," . serialize($result));
header("location:.?id=" . $page);

?>
<pre>$_REQUEST = <?= print_r($_REQUEST); ?> </pre>
<pre>$_SESSION = <?= print_r($_SESSION); ?> </pre>
<pre>$result = <?= print_r($result); ?> </pre>
