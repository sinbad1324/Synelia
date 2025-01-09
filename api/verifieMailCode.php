<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/User.php";
$conn = Connection::GetConnection("Synelia");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $code = $_GET["code"];
    $id = $_GET["userId"];
    if (!$id || !$code)  {
        echo "puff";
        return;
    }
    if (strlen($code)>= 21) {
        echo "Not valide code!";
        return;
    }
    if (intval($id) <= 0) {
       return;
    }
    $user = FindOneUserWithId(id: $id);
    if (!$user) {
        echo "not user";
        return;
    }
    if ($user["urlToVerified"] != $code) {
        echo "not same url";
        return;
    }
    if (time() - strtotime($user['verifieTime']) <= (3600 * 12)) {
        if ($conn->query("UPDATE User SET verified=TRUE, urlToVerified = NULL WHERE userId = '$id';") === true)
            echo "updated";
    } else {
        echo "False";
    }
}

?>