<?php
include $GLOBALS['root'] . "/modules/shuffl.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST["userId"];
    if (intval($id) <= 0) {
        return;
    }
    $user = FindOneUserWithId(id: $id);
    if (!$user) {
        echo "not user";
        return;
    }
    $newCode = RandomText(1,21);
    if ($conn->query("UPDATE User SET verified=FALSE, urlToVerified =' $newCode ',verifieTime=NOW() WHERE userId = '$id';") === true)
        echo "updated";
}

?>