<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 08/08/2018
 * Time: 13:19
 */
//
global $pageParameter;
//global $pageStatus;
//$infoMessage="";

$subjectList = fctSubjectList($_SESSION['user']['id']);
$subId = 0;
if ($pageParameter > 0) { //a subject is selected
    $subId = $pageParameter;

} else { //no subject selected take the last one
    if (isset($subjectList[0]['sub_id']) && $subjectList[0]['sub_id'] > 0) { //user has a subject
        $subId = $subjectList[0]['sub_id'];

    } else {
        $subId = 0;
    }
}
$messageList = fctMessageList($subId);
include("messageForm.php");
?>
<link rel="stylesheet" href="css/messaging.css"/>

<div class="container container-fluid">

    <div class="chatbox">

        <div class="inbox">
            <div class="inbox_header">
                <div class="inbox_title">
                    <h4>Inbox</h4>
                </div>
                <div class="inbox_search">
                    <input class="searchField" type="text" name="SearchInbox" placeholder="Search..."/>
                </div>

            </div>
            <div class="inbox_list">

                <?php if ($subId > 0) {
                    foreach ($subjectList as $item) {

                        $item['sub_id'] == $subId ? $active = 1 : $active = 0;

                        if ($active == 1) {
                            echo '<div class="inbox_item active_item">';
                        } else {
                            echo '<div class="inbox_item" onclick="window.location = \'?id=' . fctUrlOpensslCipher("messages.php," . $item["sub_id"]) . '\'">';
                        }

                        echo '<div class="item_icon">
                        <img src="https://www.w3schools.com/howto/img_avatar.png" alt="' . $item['usr_name'] . " " . $item['usr_lastname'] . '">
                    </div>
                    <div class="item_content">
                        <div class="item_details">
                            <h5>' . $item["usr_email"] . ' <span>' . $item["sub_date"] . '</span></h5>
                        </div>
                        <div class="item_subject">
                            ' . $item["sub_name"] . '
                        </div>
                    </div>
                </div>';
                    }
                } ?>

            </div>

            <button type="button" class="btn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus" aria-hidden="true"></i> New</button>

        </div>
        <div class="history">
            <div class="mesgs">
                <?php if ($subId > 0) {
                    foreach ($messageList as $item) {

                        if ($item['usr_id'] != $_SESSION['user']['id']) {//received message

                            echo '<div class="message_in">
                    <div class="message_icon">
                        <img src="https://www.w3schools.com/howto/img_avatar.png" alt="' . $item['usr_name'] . " " . $item['usr_lastname'] . '">
                    </div>
                    <div class="message_in_content">
                        <div class="message_body">
                            <p>' . $item["msg_content"] . '</p>
                        </div>
                        <div class="message_details">' . $item["msg_date"] . '</div>
                    </div>
                </div>';


                        } else {//sent message
                            echo '<div class="message_out">
                    <div class="message_out_content">
                        <div class="message_body">
                            <p>' . $item["msg_content"] . '</p>
                        </div>
                        <div class="message_details">' . $item["msg_date"] . '</div>
                    </div>
                </div>';
                        }

                    }
                } ?>

            </div>
        </div>
        <div class="message_new">
            <textarea name="message"></textarea>
        </div>
    </div>

</div>