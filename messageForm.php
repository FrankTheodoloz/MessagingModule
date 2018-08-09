<?php
/**
 * Created by PhpStorm.
 * Resource: https://codepen.io/fsanggang/pen/ZOepzE (Button DropDown)
 * User: Frank
 * Date: 09/08/2018
 * Time: 15:39
 */


?>
<link rel="stylesheet" href="/css/dropdownUsers.css">
<div class="container">

    <form name="MessageForm" action="messageAdd.php" target="_self" method="post">
        <div class="modal" id="myModal">
            <div class="modal-dialog modal-sm  modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-light">
                        <h4 class="modal-title">New Message</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group mt-4">


                            <textarea name="content" class="form-control" placeholder="Message..."></textarea>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-danger" data-toggle="modal" data-target="#myModal">Cancel</button>
                        <button type="submit" class="btn btn-success">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

