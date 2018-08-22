<?php
/**
 * Subject: POST file for Message Subject and notifications
 * Usage: Check $_POST, insert to DB, reports error and redirect
 * User: Frank
 * Date: 09/08/2018
 * Time: 20:41
 */

session_start();

include_once("functionsSql.inc.php");
include_once("functionsHtml.inc.php");

$target = "messages.php";

if (isset($_POST['subjectId'])) {
    $subjectId = $_POST['subjectId'];
} else {
    $subjectId = 0;
}
if (isset($_POST['action'])) {
    $action = $_POST['action'];
} else {
    $result[] = array("error", "Error", "Form action error");
    goto error;
}

if ($action == 'new') {
    if (isset($_POST['to']) && isset($_POST['subject']) && isset($_POST['content'])) {

        $to = $_POST['to'];
        $subject = $_POST['subject'];
        $content = $_POST['content'];

        $id = fctSubjectNew($_SESSION['user']['id'], $to, $subject, $content, NULL);

        if ($id > 0) {
            $subjectId = $id;
            $result[] = array("success", "Success", "New subject opened.");
        } else {
            $subjectId = 0;
            $result[] = array("error", "Error", "Message could not be sent.");
        }
    } else {
        $result[] = array("error", "Error", "Form input error");
    }

} elseif ($action == 'add') {
    if (isset($_POST['subjectId']) && isset($_POST['content'])) {

        $subjectId = $_POST['subjectId'];
        $content = $_POST['content'];

        $id = fctMessageAdd($subjectId, $_SESSION['user']['id'], $content, NULL);

        if ($id > 0) {
            $result[] = array("success", "Success", "Message sent.");
        } else {
            $result[] = array("error", "Error", "Message could not be sent.");
        }

    } else {
        $result[] = array("error", "Error", "Form input error");
    }

} elseif ($action == 'distributionAdd') {

    if (isset($_POST['subjectId']) && isset($_POST['to'])) {

        $subjectId = $_POST['subjectId'];
        $to = $_POST['to'];

        $users = 0;
        $notif = 0;
        foreach ($to as $userId) {

            $userResult = fctDistributionAdd($subjectId, $userId);

            if ($userResult > 0) {
                $users += $userResult;

                $notifResult = fctNotificationUserAdd2($subjectId, $userId);

                if ($notifResult > 0) {
                    $notif += $notifResult;
                } else {
                    $result[] = array("error", "Error", "Something went wrong with notifications.");
                }
            } else {
                $result[] = array("error", "Error", "User not added to conversation.");
            }
        }
        $result[] = array("success", "Success", $users . " User(s) added to conversation and " . $notif . " notifications sent");

    } else {
        $result[] = array("error", "Error", "Form input error");
    }
} elseif ($action == 'distributionRemove') {
    $target = "maintenanceDetail.php";

    if (isset($_POST['subjectId']) && isset($_POST['to'])) {

        $subjectId = $_POST['subjectId'];
        $to = $_POST['to'];

        $users = 0;
        $notif = 0;

        foreach ($to as $userItem) {

            $userDetails=fctUserList($userItem);

            if (fctNotificationCount($userItem, $subjectId, 1)) {//if there are notifications to delete

                $notifResult = fctNotificationUserRemove2($subjectId,$userItem);

                if ($notifResult > 0) {
                    $notif += $notifResult;

                } else {
                    $result[] = array("error", "Error", "User : ".$userDetails['usr_name']." ".$userDetails['usr_lastname']."<br/> Error when deleting notifications.");
                }
            } else {
                $result[] = array("info", "Info", "User : ".$userDetails['usr_name']." ".$userDetails['usr_lastname']."<br/>No notification to delete.");
            }

            $userResult = fctDistributionRemove($subjectId, $userItem);

            if ($userResult > 0) {
                $users += $userResult;
            } else {
                $result[] = array("error", "Error", "User : ".$userDetails['usr_name']." ".$userDetails['usr_lastname']."<br/>User not removed to conversation.");
            }

        }
        $result[] = array("success", "Success", $users . " User(s) removed from subject and " . $notif . " notification(s) deleted.");

    } else {
        $result[] = array("error", "Error", "Form input error");
    }
} elseif ($action == 'nofiticationDelete') {

    if (isset($_POST['userId']) && isset($_POST['messageId'])) {

        $messageId = $_POST['messageId'];
        $userId = $_POST['userId'];

        $sqlresult = fctNotificationRemove($userId, $messageId);

        if ($sqlresult) {
            $result[] = array("success", "Success", "Notification successfully deleted.");
        } else {
            $result[] = array("error", "Error", "Notification could not be removed.");
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
<pre>$result = <?= print_r($result); ?> </pre>