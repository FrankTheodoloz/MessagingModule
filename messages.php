<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 08/08/2018
 * Time: 13:19
 */
global $pageParameter; //always defined
$subjectId = $pageParameter;

if ($subjectId > 0) { //a subject is selected

    isset($_POST['deleted']) ? $deleted = 1 : $deleted = 0;
    $messageList = fctMessageList($_SESSION['user']['id'], $subjectId);
    $userNotInDistribution = fctDistributionUsersNotIn($subjectId);
    $userInDistribution = fctDistributionUsersIn($subjectId);

} else {
    //no subject selected
}
$subjectList = fctUserSubjectList($_SESSION['user']['id']); //AFTER retriving the msgs (set read)
$userList = fctUserList(0);
?>
<link rel="stylesheet" href="css/messaging.css"/>

<div class="container">

    <div class="chatbox">

        <div class="inbox">
            <div class="inbox_header">
                <div class="inbox_title">
                    <h2>Inbox</h2>
                </div>
                <div class="inbox_search">
                    <input class="searchField" type="text" id="myInput" name="SearchInbox" placeholder="Filter subjects..."/>
                </div>
            </div>
            <div class="inbox_list" id="myTable">

                <?php if (count($subjectList) > 0) { //there are subject
                    foreach ($subjectList as $subjectItem) {

                        $subjectItem["sub_id"] == $subjectId ? $active = 1 : $active = 0;
                        $unreadItems = fctNotificationCount($_SESSION['user']['id'], $subjectItem['sub_id'], 1);

                        if ($subjectItem["usr_avatar"]) {
                            $image = "<img src='" . CONST_IMAGE_PATH . $subjectItem["usr_avatar"] . "' alt='" . $subjectItem["usr_name"] . " " . $subjectItem["usr_lastname"] . "'>";
                        } else {
                            $image = "<img avatar='" . $subjectItem["usr_name"] . " " . $subjectItem["usr_lastname"] . "' alt='" . $subjectItem["usr_name"] . " " . $subjectItem["usr_lastname"] . "'>";
                        } ?>


                        <div class="inbox_item <?= $active == 1 ? 'active_item' : '' ?>" onclick="window.location = '?id=<?= fctUrlOpensslCipher("messages.php," . $subjectItem["sub_id"]) ?>'">

                            <div class="item_icon"><?= $image ?></div>
                            <div class="item_content">
                                <div class="item_details">
                                    <h5><?= $subjectItem["usr_name"] . " " . $subjectItem["usr_lastname"] . "<span>" . date("d/m/Y", strtotime($subjectItem["sub_lastdate"])) ?></span></h5>
                                </div>
                                <div class="item_subject">
                                    <?= $subjectItem["sub_name"] ?> <span class="badge badge-pill badge-info"><?= $unreadItems > 0 ? $unreadItems : "" ?></span>
                                </div>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
            <button type="button" class="inbox_newbutton" data-toggle="modal" data-target="#modalMessageNew">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </button>
        </div>

        <!-- Right pane -->
        <?php if ($subjectId > 0) {//a subject is selected ?>
            <div class="subjectHeader">
                <div class="subjectUsers">

                    <?php
                    foreach ($userInDistribution as $userItem) {
                        $tooltip = ' data-toggle="tooltip" data-placement="bottom" title="' . $userItem['usr_name'] . " " . $userItem['usr_lastname'] . '"';
                        if ($userItem['usr_avatar']) {
                            echo '<img src="' . CONST_IMAGE_PATH . $userItem['usr_avatar'] . '" alt="' . $userItem['usr_name'] . " " . $userItem['usr_lastname'] . '"' . $tooltip . '>';
                        } else {
                            echo '<img avatar="' . $userItem['usr_name'] . " " . $userItem['usr_lastname'] . '" alt="' . $userItem['usr_name'] . " " . $userItem['usr_lastname'] . '"' . $tooltip . '>';
                        }
                    }
                    if (sizeof($userNotInDistribution) > 0) {//if it is possible to add someone ?>
                        <a class="text-success" href="#" data-toggle="modal" data-target="#modalDistributionAdd">
                            <i class="fa fa-plus" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Add user to subject"></i>
                        </a>
                    <?php } else { ?>
                        <i class="fa fa-plus" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Everyone's in :)"></i>
                    <?php } ?>

                </div>
            </div>

            <div class="custom-control custom-checkbox cb_delete">
                <form name="frmDeleted" action="?id=<?= fctUrlOpensslCipher("messages.php," . $subjectId) ?>" target="_self" method="post">
                    <input type="checkbox" class="custom-control-input" name="deleted" id="cbDelete" onchange="this.form.submit()" <?= $deleted ? 'checked' : '' ?>>
                    <label class="custom-control-label lef" for="cbDelete">show deleted</label>
                </form>
            </div>
        <?php } ?>

        <div class="history">

            <div class="mesgs">
                <form name="frmNotificationDelete" action="messagePost.php" target="_self" method="post">
                    <input type="hidden" name="action" value="nofiticationDelete">
                    <input type="hidden" name="subjectId" value="<?= $subjectId ?>">
                    <input type="hidden" name="userId" value="-1">
                    <input type="hidden" name="messageId" value="-1">
                    <?php if ($subjectId > 0) {
                        foreach ($messageList as $messageItem) {
                            //deleted : 1 = all (0,1,NULL)| deleted : 0 $messageItem['not_read'] (0 or 1)
                            if (!($deleted == 0 && !$messageItem['not_read'])) {

                                $tooltip = ' data-toggle="tooltip" data-placement="bottom" title="' . $messageItem['usr_name'] . " " . $messageItem['usr_lastname'] . '"';
                                $deleteLink = '| <b href="#" onclick="javascript:fctNotificationDelete(' . $_SESSION["user"]["id"] . "," . $messageItem["msg_id"] . ');">delete</b>';

                                if ($messageItem['usr_id'] != $_SESSION['user']['id']) {//received message

                                    if ($messageItem['usr_avatar']) {
                                        $image = '<img src="' . CONST_IMAGE_PATH . $messageItem['usr_avatar'] . '" alt="' . $messageItem['usr_name'] . " " . $messageItem['usr_lastname'] . '"' . $tooltip . '>';
                                    } else {
                                        $image = '<img avatar="' . $messageItem['usr_name'] . " " . $messageItem['usr_lastname'] . '" alt="' . $messageItem['usr_name'] . " " . $messageItem['usr_lastname'] . '"' . $tooltip . '>';
                                    }
                                    ?>

                                    <div class="message_in">
                                        <div class="message_icon"><?= $image ?></div>
                                        <div class="message_in_content">
                                            <div class="message_body">
                                                <p><?= $messageItem["msg_content"] ?></p>
                                            </div>
                                            <div class="message_details"><?= date("d/m/Y | H:i", strtotime($messageItem["msg_date"])) ?> <?= !$messageItem['not_read'] ? '' : $deleteLink ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else {//sent message ?>

                                    <div class="message_out">
                                        <div class="message_out_content">


                                            <div class="message_body">
                                                <p><?= $messageItem["msg_content"] ?></p>
                                            </div>
                                            <div class="message_details"><?= date("d/m/Y | H:i", strtotime($messageItem["msg_date"])) ?> <?= !$messageItem['not_read'] ? '' : $deleteLink ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            }
                        }
                    } else {//invite to click a subject, will "trigger" notificationRead ?>

                        <div class="text text-muted message_none"><i class="far fa-arrow-alt-circle-left"></i>
                            <h1>Select a subject from the left pane.</h1></div>
                    <?php } ?>
                </form>
            </div>
        </div>

        <?php
        if ($subjectId > 0) {

            echo '<form name="MessageForm" action="messagePost.php" target="_self" method="post">
                        <input type="hidden" name="action" value="add"/>
                        <input type="hidden" name="subjectId" value="' . $subjectId . '"/>
                                                    
                        <div class="message_new">
                               <textarea name="content" id="content" class="form-control" placeholder="Reply..." required></textarea>
                        </div>
                            <button type="submit" class="send_buton" data-toggle="tooltip" data-placement="left" title="Send" ><h3><i class="fa fa-reply" aria-hidden="true"></i></h3></button>
                    </form>';
        } ?>
    </div>
</div>

<!-- Modal form New message -->
<div class="container">
    <div class="modal" id="modalMessageNew">
        <div class="modal-dialog modal-sm  modal-dialog-centered">
            <form name="frmMessageNew" action="messagePost.php" target="_self" method="post">
                <input type="hidden" name="action" value="new"/>

                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-light">
                        <h4 class="modal-title">New Message</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group mt-2">

                            <select name="to[]" id="to" class="selectpicker form-control mb-4" multiple required>
                                <?php
                                foreach ($userList as $item) {
                                    if ($item['usr_id'] != $_SESSION['user']['id']) {
                                        echo '<option value="' . $item['usr_id'] . '" data-subtext="' . $item['usr_lastname'] . '">' . $item['usr_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>

                            <input type="text" name="subject" class="form-control mb-2" placeholder="Enter a subject..." required>
                            <textarea name="content" class="form-control" style="min-height: 150px;" placeholder="Message..." required></textarea>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-danger" data-toggle="modal" data-target="#modalMessageNew">Cancel</button>
                        <button type="submit" class="btn btn-success">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($subjectId > 0) { ?>
    <!-- Modal User Add -->
    <!-- <a href="#" data-toggle="modal" data-target="#modalDistributionAdd"> -->
    <div class="container">

        <div class="modal" id="modalDistributionAdd">
            <div class="modal-dialog modal-sm  modal-dialog-centered">

                <form name="frmDistributionAdd" action="messagePost.php" target="_self" method="post">
                    <input type="hidden" name="action" value="distributionAdd"/>
                    <input type="hidden" name="subjectId" value="<?= $subjectId ?>"/>

                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header bg-dark text-light">
                            <h3><i class="far fa-times-circle" aria-hidden="true"></i></h3>
                            <h4 class="modal-title"> Add a user in conversation</h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="form-group mt-2">
                                <select name="to[]" id="to" class="selectpicker form-control mb-2" multiple required>

                                    <?php
                                    foreach ($userNotInDistribution as $item) {
                                        if ($item['usr_id'] != $_SESSION['user']['id']) {
                                            echo '<option value="' . $item['usr_id'] . '" data-subtext="' . $item['usr_lastname'] . '">' . $item['usr_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-danger" data-toggle="modal" data-target="#modalDistributionAdd">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
<?php } ?>

<script>
    $(document).ready(function () {
        $("#myInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTable .inbox_item").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    //Resource: https://stackoverflow.com/a/426417
    function fctNotificationDelete(userId, messageId) {
        var myForm = document.forms.frmNotificationDelete;
        myForm.messageId.value = messageId;
        myForm.userId.value = userId;
        myForm.submit();
    }
</script>