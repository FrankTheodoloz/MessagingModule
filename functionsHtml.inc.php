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
    $key = $_SESSION['key'];

    $c = fctBase64UrlDecode(base64_decode($ciphertext));
    $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);

    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key,  OPENSSL_RAW_DATA, $iv);
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
function fctFilterJS(){
    return'<script>
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

