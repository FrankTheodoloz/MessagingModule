<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 24/08/2018
 * Time: 14:59
 */

?>
<div class="container">

    <div class="row mt-4">
        <div class="col-md-6">
            <h2><i class="fas fa-info-circle text-primary" aria-hidden="true"></i> About...</h2>
        </div>
        <div class="col-md-6 text-right">
            <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("main.php") ?>">
                <h2>back to main page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h2>
            </a>
        </div>
    </div>

    <div class="jumbotron pt-2 pb-5 bg-white">
        <h5 class="collapse text-justify text-muted" id="collapseJumbo">
            Here are some details about this application.</h5>
        <hr/>
        <button class="collapseIcon" data-toggle="collapse" data-target="#collapseJumbo" aria-expanded="false" aria-controls="collapseJumbo">
            <span id="collapseIconOpen" class="text-muted">show details <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></span>
            <span id="collapseIconClose" class="text-muted">hide details <i class="fa fa-arrow-circle-up" aria-hidden="true"></i></span>
        </button>
    </div>

    <div class="card">

        <div class="card-header">
            <h4>
                About <?= fctSettingItem('SITE_CONFIG', 'SITE_NAME') ?>...
            </h4>
        </div>


        <div class="card-body">

            <div class="form-row">
                <div class="col">

                    <p>This site has been developped by Frank Théodoloz as a study work for his 2nd year at the Ecole Supérieure d'Informatique de Gestion (ESIG) in Geneva, Switzerland. </p>
                    <a href="https://www.jetbrains.com/phpstorm/" target="_blank">JetBrains PhpStorm</a> was the main IDE used for this project and contains the following components/plugins :<br/>
                    <br/>

                    <ul>
                        <li><strong>Bootstrap 4</strong><br/>
                            CSS Framework (<a href="https://github.com/twbs/bootstrap/blob/master/LICENSE" target="_blank">MIT License</a>)<br/>
                            <a href="https://getbootstrap.com/" target="_blank">
                                getbootstrap.com</a>
                        </li>
                    </ul>
                    <ul>
                        <li><strong>FontAwesome</strong><br/>
                            Icons and Fonts library (<a href="https://fontawesome.com/license/free" target="_blank">Licensing</a>)<br/>
                            <a href="https://fontawesome.com/" target="_blank">
                                fontawesome.com</a>
                        </li>
                    </ul>

                    <ul>
                        <li><strong>jQuery </strong><br/>
                            JavaScript Library (<a href="https://jquery.org/license/" target="_blank">MIT License</a>)<br/>
                            <a href="https://jquery.com/" target="_blank">
                                jquery.com</a>
                        </li>
                    </ul>


                    <ul>
                        <li><strong>jquery-toast-plugin </strong><br/>
                            Notification plugin (by <a href="https://kamranahmed.info/" target="_blank">Kamran Ahmed</a> - Licence MIT)<br/>
                            <a href="https://github.com/kamranahmedse/jquery-toast-plugin" target="_blank">
                                github.com/kamranahmedse/jquery-toast-plugin</a>
                        </li>
                    </ul>

                    <ul>
                        <li><strong>bootstrap-select </strong><br/>
                            Plugin bootstrap-select - multi-selection dropdown list (by SnapAppointments, LLC - <a href="https://github.com/snapappointments/bootstrap-select/blob/v1.13.0-dev/LICENSE" target="_blank">License MIT)<br/>
                                <a href="https://developer.snapappointments.com/bootstrap-select" target="_blank">
                                    developer.snapappointments.com/bootstrap-select
                                </a>
                        </li>
                    </ul>

                    <ul>
                        <li><strong>jquery-toast-plugin </strong><br/>
                            Some bits of code and formatting for the chatbox (by <a href="https://bootsnipp.com/suneelrajpoot44" target="_blank">Sunil Rajput</a>) <br/>
                            <a href="https://bootsnipp.com/snippets/featured/message-chat-box" target="_blank">bootsnipp.com/snippets/featured/message-chat-box</a>
                        </li>
                    </ul>

                    <ul>
                        <li><strong>jquery-toast-plugin </strong><br/>
                            Code JS Letter Avatars (by <a href="https://codepen.io/arturheinze/#" target="_blank">Arthur</a>)<br/>
                            <a href="https://codepen.io/arturheinze/pen/ZGvOMw" target="_blank">codepen.io/arturheinze/pen/ZGvOMw</a>
                        </li>
                    </ul>

                    <ul>
                        <li><strong>Avatar images from w3schools </strong><br/>
                            <a href="https://www.w3schools.com/howto/howto_css_image_avatar.asp" target="_blank">w3schools.com</a>
                        </li>
                    </ul>

                </div>
            </div>


        </div>

        <div class="card-footer text-right">

        </div>

    </div>

    <div class="col mt-4">
        <a class="btn text-muted" href="?id=<?= fctUrlOpensslCipher("main.php") ?>">
            <h4>back to main page <i class="fas fa-angle-double-left " aria-hidden="true"></i></h4>
        </a>
    </div>
</div>