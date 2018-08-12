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
$messageList = fctMessageList($_SESSION['user']['id'], $subId, 1);
$userList = fctUserList(0);
$to;
?>
<link rel="stylesheet" href="css/messaging.css"/>

<div class="container container-fluid">

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

                <?php if ($subId > 0) {
                    foreach ($subjectList as $item) {

                        $item['sub_id'] == $subId ? $active = 1 : $active = 0;
                        $item['usr_avatar'] ? $image = '<img src="' . $item['usr_avatar'] . '" alt="' . $item['usr_name'] . " " . $item['usr_lastname'] . '">' : $image = '<img avatar="' . $item['usr_name'] . " " . $item['usr_lastname'] . '" alt="' . $item['usr_name'] . " " . $item['usr_lastname'] . '">';

                        if ($active == 1) { //active subject
                            $to = $item['usr_id']; //current correspondant
                            echo '<div class="inbox_item active_item">';
                        } else {
                            echo '<div class="inbox_item" onclick="window.location = \'?id=' . fctUrlOpensslCipher("messages.php," . $item["sub_id"]) . '\'">';
                        }

                        echo '<div class="item_icon">' . $image . '</div>
                    <div class="item_content">
                        <div class="item_details">
                            <h5>' . $item["usr_name"] . ' ' . $item["usr_lastname"] . ' <span>' . date("d/m/Y", strtotime($item["sub_lastdate"])) . '</span></h5>
                        </div>
                        <div class="item_subject">
                            ' . $item["sub_name"] . '
                        </div>
                    </div>
                </div>';
                    }
                } ?>
            </div>
            <button type="button" class="inbox_newbutton" data-toggle="modal" data-target="#newMessageModal">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </button>

        </div>

        <!-- History pane ------------------------------------------------------------------------ -->

        <div class="history">
            <div class="mesgs">
                <?php if ($subId > 0) {
                    foreach ($messageList as $item) {

                        $tooltip=' data-toggle="tooltip" data-placement="bottom" title="' . $item['usr_name'] . " " . $item['usr_lastname'] . '"';

                        if ($item['usr_id'] != $_SESSION['user']['id']) {//received message
                            $item['usr_avatar'] ? $image = '<img src="' . $item['usr_avatar'] . '" alt="' . $item['usr_name'] . " " . $item['usr_lastname'] . '"'.$tooltip.'>' : $image = '<img avatar="' . $item['usr_name'] . " " . $item['usr_lastname'] . '" alt="' . $item['usr_name'] . " " . $item['usr_lastname'] . '"'.$tooltip.'>';

                            echo '<div class="message_in">
                    <div class="message_icon">'.$image.'</div>
                    <div class="message_in_content">
                        <div class="message_body">
                            <p>' . $item["msg_content"] . '</p>
                        </div>
                        <div class="message_details">' . date("d/m/Y | H:i", strtotime($item["msg_date"])) . '</div>
                    </div>
                </div>';


                        } else {//sent message
                            echo '<div class="message_out">
                    <div class="message_out_content">
                        <div class="message_body">
                            <p>' . $item["msg_content"] . '</p>
                        </div>
                        <div class="message_details">' . date("d/m/Y | H:i", strtotime($item["msg_date"])) . '</div>
                    </div>
                </div>';
                        }

                    }
                } ?>

            </div>
        </div>
        <?php
        if ($subId > 0) {

            echo '<form name="MessageForm" action="messageAdd.php" target="_self" method="post">
            <input type="hidden" name="subjectId" value="' . $subId . '"/>
            <input type="hidden" name="to" value="' . $to . '"/>
            <div class="delete_msg"><a href="#" data-toggle="modal" data-target="#confirmDeleteModal">delete conversation...</a></div>
            
            <div class="message_new">
                   <textarea name="content" id="content" class="form-control" placeholder="Reply..." required></textarea>
            </div>
                <button type="submit" class="send_buton" data-toggle="tooltip" data-placement="left" title="Send" ><h3><i class="fa fa-reply" aria-hidden="true"></i></h3></button>

        </form>';

        } ?>

    </div>

    <!-- Modal form New message -------------------------------------------------------------- -->
    <div class="container">

        <form name="NewMessageForm" action="messageNew.php" target="_self" method="post">
            <div class="modal" id="newMessageModal">
                <div class="modal-dialog modal-sm  modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header bg-primary text-light">
                            <h4 class="modal-title">New Message</h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="form-group mt-2">

                                <select name="to[]" id="to" class="selectpicker form-control mb-2" multiple required>


                                    <?php
                                    foreach ($userList as $item) {
                                        if ($item['usr_id'] != $_SESSION['user']['id']) {
                                            echo '<option value="' . $item['usr_id'] . '" data-subtext="' . $item['usr_lastname'] . '">' . $item['usr_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>

                                <!-- todo : categoryId -->
                                <input type="hidden" name="categoryId" value="1"/>
                                <input type="text" name="subject" class="form-control mb-2" placeholder="Enter a subject..." required>
                                <textarea name="content" class="form-control" style="min-height: 150px;" placeholder="Message..." required></textarea>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-danger" data-toggle="modal" data-target="#newMessageModal">Cancel</button>
                            <button type="submit" class="btn btn-success">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal form Delete confirm ----------------------------------------------------------- -->
    <div class="container">
        <form name="ConfirmDeleteForm" action="subjectDelete.php" target="_self" method="post">
            <div class="modal" id="confirmDeleteModal">
                <div class="modal-dialog modal-sm  modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header bg-danger text-light">
                            <h3><i class="far fa-times-circle" aria-hidden="true"></i></h3>
                            <h4 class="modal-title"> Delete confirmation</h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="form-group mt-2">
                                Confirm deletion of Conversation and all Messages ?
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <input type="hidden" name="subjectId" value="<?= $subId ?>"/>
                            <button type="reset" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">Cancel</button>
                            <button type="submit" class="btn btn-success">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>

<script>
    $(document).ready(function () {
        $("#myInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTable .inbox_item").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>