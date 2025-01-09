<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/User.php";
$conn = Connection::GetConnection("Synelia");

$format = 'Y-m-d H:i:s';
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $code = $_GET["code"];
    $user = FindOneUserWithVerifyCode($code);
    if (time() - strtotime($user["data"]['verifieTime']) <= (3600 * 12)) {
        if ($conn->query("UPDATE User SET verified=TRUE, urlToVerified = NULL WHERE userId = '$id';") === true)
            echo "updated";
    } else {
        echo "False";
    }
}

?>