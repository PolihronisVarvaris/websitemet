<?php
// Stop if the script is loaded without POST data
if (!$_POST) exit;

// Email address verification function (do not edit)
function isEmail($email) {
    return (bool) preg_match(
        "/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+" .
        "(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|" .
        "bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|" .
        "cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|" .
        "gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|" .
        "jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|" .
        "mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|" .
        "np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|" .
        "sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|" .
        "tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|".
        "(([0-9][0-9]?|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.){3}".
        "([0-9][0-9]?|1[0-9][0-9]|2[0-4][0-9]|25[0-5]))$/i",
        $email
    );
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure PHP_EOL is defined
if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");

// Grab only the fields we need
$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name']  ?? '');
$email      = trim($_POST['email']      ?? '');
$phone      = trim($_POST['phone']      ?? '');
$comments   = trim($_POST['comments']   ?? '');

// Basic validation
if ($first_name === '') {
    echo '<div class="error_message">Attention! You must enter your first name.</div>';
    exit;
}
if ($last_name === '') {
    echo '<div class="error_message">Attention! You must enter your last name.</div>';
    exit;
}
if ($email === '' || !isEmail($email)) {
    echo '<div class="error_message">Attention! Please enter a valid email address.</div>';
    exit;
}
if ($comments === '') {
    echo '<div class="error_message">Attention! Please enter your message.</div>';
    exit;
}

// If magic quotes are on, strip slashes
if (get_magic_quotes_gpc()) {
    $comments = stripslashes($comments);
}

// === Configuration ===
// Your destination address:
$address = "polihronisv@gmail.com";

// Email subject:
$e_subject = "You've been contacted by $first_name $last_name.";

// Build the email body
$e_body  = "Name   : $first_name $last_name" . PHP_EOL;
$e_body .= "Email  : $email" . PHP_EOL;
$e_body .= "Phone  : $phone" . PHP_EOL . PHP_EOL;
$e_body .= "Message:" . PHP_EOL . $comments;

$msg = wordwrap($e_body, 70);

// Build headers
$headers  = "From: $email"        . PHP_EOL;
$headers .= "Reply-To: $email"    . PHP_EOL;
$headers .= "MIME-Version: 1.0"   . PHP_EOL;
$headers .= "Content-Type: text/plain; charset=utf-8" . PHP_EOL;
$headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

// Send it
if (mail($address, $e_subject, $msg, $headers)) {
    echo "<fieldset>";
    echo "<div id='success_page'>";
    echo "<h1>Email Sent Successfully.</h1>";
    echo "<p>Thank you <strong>$first_name</strong>, your message has been submitted to us.</p>";
    echo "</div>";
    echo "</fieldset>";
} else {
    echo '<div class="error_message">ERROR! Your message could not be sent.</div>';
}
?>
