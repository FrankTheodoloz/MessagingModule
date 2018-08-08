<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 08/08/2018
 * Time: 13:19
 */

$subjectList1=fctSubjectAdd();
$subjectList=fctMessageList();
?>
<link rel="stylesheet" href="css/chatbox.css"/>

<div class="container container-fluid">

    <div class="chatbox">

        <div class="inbox">
            <div class="inbox_header">
                <div class="inbox_title">
                    <h4>Inbox</h4>
                </div>
                <div class="inbox_search">
                    <input type="text" name="search"/>
                </div>

            </div>
            <div class="inbox_list">
                <div class="inbox_item active_item">
                    <div class="item_icon">
                        <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">
                    </div>
                    <div class="item_content">
                        <div class="item_details">
                            <h5>Frank Théodoloz <span>08.08.2018</span></h5>
                        </div>
                        <div class="item_subject">
                            Lorem ipsum dolor sit amet, consec qui officia deserunt mollit anim id est laborum.
                        </div>
                    </div>
                </div>
                <div class="inbox_item ">

                    <div class="item_icon">
                        <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">
                    </div>

                    <div class="item_content">

                        <div class="item_details">
                            <h5>Frank Théodoloz <span>08.08.2018</span></h5>
                        </div>

                        <div class="item_subject">
                            Subject
                        </div>
                    </div>
                </div>
                <div class="inbox_item ">

                    <div class="item_icon">
                        <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">
                    </div>

                    <div class="item_content">

                        <div class="item_details">
                            <h5>Frank Théodoloz <span>08.08.2018</span></h5>
                        </div>

                        <div class="item_subject">
                            subject
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="history">
            <div class="messages">
                <div class="message_in">
                    <div class="message_icon">

                    </div>
                    <div class="message_body">
                        <p>We work directly with our designers and suppliers,
                            and sell direct to you, which means quality, exclusive
                            products, at a price anyone can afford.</p>
                    </div>
                    <div class="message_in_details">
                        08.08.2018 - 14:42
                    </div>
                </div>
                <div class="message_out">
                    <div class="message_body">
                        <p>We work directly with our designers and suppliers,
                            products, at a price anyone can afford.</p>
                    </div>
                    <div class="message_out_details">
                        08.08.2018 - 14:43
                    </div>
                </div>
                <div class="message_in">
                    <div class="message_icon">

                    </div>
                    <div class="message_body">
                        <p>bla bla1</p>
                    </div>
                    <div class="message_in_details">
                        08.08.2018 - 14:42
                    </div>
                </div>
                <div class="message_out">
                    <div class="message_body">
                        <p>We work directly with our designers and suppliers,
                            and sell direct to you, which means quality, exclusive
                            products, at a price anyone can afford.</p>
                    </div>
                    <div class="message_out_details">
                        08.08.2018 - 14:43
                    </div>
                </div>
            </div>
            <div class="message_new">
                <input type="text" name="message"/>
            </div>
        </div>
    </div>

</div>