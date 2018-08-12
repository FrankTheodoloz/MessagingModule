<?php
/**
 * Subject: PHP functions related to html/Bootstrap4
 * User: Frank
 * Date: 04/08/2018
 * Time: 17:02
 */

include_once("config/config.inc.php");

/***
 * getBadge: Return the badge of the user
 * Reference https://stackoverflow.com/a/49089293
 * @return string
 */
function getBadge()
{
    if ($_SESSION['user']['admin'] == 1) {
        echo '<span class="badge badge-pill badge-danger align-top"><i class="fas fa-user-ninja"></i><strong> ADMIN</strong></span>';
    } else {
        echo '<span class="badge badge-pill badge-success align-top"><i class="fas fa-user" ></i><strong> USER</strong></span>';
    }
}

/***
 * getDebug : Return the $_SESSION details in an alert box if $debugMode = 1 (config)
 * @return string
 */
function getDebug()
{
    if (CONST_DEBUGMODE == 1) {
        echo '
<div class="alert alert-warning alert-dismissible fixed-bottom" style="opacity: 0.5">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-info-circle"></i>
    <small>
        <strong>Debug</strong><br/>
        <pre>';
        echo "Last activity: " . date('H:i:s', $_SESSION['LAST_ACTIVITY']) . "\n";
        !isset($_SESSION['user']) ?: print_r($_SESSION['user']);

        echo '        </pre>
    </small>
</div>
';
    }
}

/***
 * fctLoginMessage: Return error message in alert (index.php login form)
 */
function fctLoginMessage()
{
    if (isset($_SESSION['Error']['Data']["Type"])) {
        if ($_SESSION['Error']['Data']["Type"] == "danger") {
            echo '
            <div class="alert alert-danger"><i class="fas fa-times-circle"></i>
                <small><strong>Error</strong> ' . $_SESSION['Error']['Data']["Message"] . '</small>
            </div>
            ';
        } elseif ($_SESSION['Error']['Data']["Type"] == "warning") {
            echo '
            <div class="alert alert-warning"><i class="fas fa-exclamation-circle"></i>
                <small><strong>Warning</strong> ' . $_SESSION['Error']['Data']["Message"] . '</small>
            </div>
            ';
        }
    }
}

/***
 * Reference: http://php.net/manual/en/function.openssl-encrypt.php
 * @param $plaintext
 * @return string
 */

function fctUrlOpensslCipher($plaintext)
{
    $key = $_SESSION['key'];

    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);

    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    $temp = base64_encode($iv . $hmac . $ciphertext_raw);

    return fctBase64UrlEncode($temp);
}

/***
 * Reference: http://php.net/manual/en/function.openssl-encrypt.php
 * @param $ciphertext
 * @return
 */
function fctUrlOpensslDecipher($ciphertext)
{
    //TODO when key does not exist or when no corresponding url is found
    $key = $_SESSION['key'];

    $c = fctBase64UrlDecode(base64_decode($ciphertext));
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);

    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
    if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
    {
        return $original_plaintext;
    }
}

/***
 * fctFilterJS Return JS code for filtering myTable
 * Reference: https://www.w3schools.com/bootstrap4/bootstrap_filters.asp
 * @return string
 */
function fctFilterJS()
{
    return '<script>
    $(document).ready(function () {
        $("#myInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
    </script>';

}

/***
 * base64_url_en/de-code
 * Reference : https://stackoverflow.com/a/5835352
 * @param $input
 * @return string
 */
function fctBase64UrlEncode($input)
{
    return strtr(base64_encode($input), '+/=', '._-');
}

function fctBase64UrlDecode($input)
{
    return base64_decode(strtr($input, '._-', '+/='));
}

/***
 * Toaster
 * Resource :
 *  https://kamranahmed.info/toast
 *  https://github.com/kamranahmedse/jquery-toast-plugin MIT LICENCE
 * @param string $type
 *  warning, success, error, information
 * @param string $title
 *  title
 * @param string $content
 *  message
 * @param $duration
 *  default=5000
 */
function fctShowToast($type, $title, $content, $duration = 5000)
{
    echo '<script>
    $.toast({
        
        heading: "'.$title.'", // Optional heading to be shown on the toast
        text: "'.$content.'", // Text that is to be shown in the toast
        icon: "'.$type.'", // Warning, success, error, information
        // bgColor: "#444444",  // Background color of the toast [NO ICON]
        // textColor: "#eeeeee",  // Text color of the toast [NO ICON]
        showHideTransition: "fade", // fade, slide or plain
        allowToastClose: true, // Boolean value true or false
        hideAfter: '.$duration.', // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
        stack: 5, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
        position: "bottom-left", // bottom-center/right/left or top-center/right/left or mid-center or an object representing the left, right, top, bottom values

        textAlign: "left",  // Left, right or center
        loader: true,  // Whether to show loader or not. True by default
        loaderBg: "#9EC600",  // Background color of the toast loader
        // beforeShow: function () {}, // will be triggered before the toast is shown
        // afterShown: function () {}, // will be triggered after the toat has been shown
        // beforeHide: function () {}, // will be triggered before the toast gets hidden
        // afterHidden: function () {}  // will be triggered after the toast has been hidden
    });
</script>';
}

?>


