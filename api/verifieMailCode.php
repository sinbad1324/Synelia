<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/User.php";
include_once $GLOBALS['root'] . "/modules/errorMessge.php";

$conn = Connection::GetConnection("Synelia");
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $code = $_GET["code"];
    $id = $_GET["userId"];
    if (!$id || !$code) {
        return RtError(["Message"=>"TPuff!! void! " , "succ"=>false]);
        return;
    }
    if (strlen($code) >= 21) {
        return RtError(["Message"=>"This code is not valide" , "succ"=>false]);
        return;
    }
    if (intval($id) <= 0) {
        return;
    }
    $user = FindOneUserWithId(id: $id);
    if (!$user) {
        return RtError(["Message"=>"This user is not valide" , "succ"=>false]);
        return;
    }
    if ($user["data"]["urlToVerified"] != $code) {
        return RtError(["Message"=>"This url is not valide" , "succ"=>false]);

    }
    if (time() - strtotime($user["data"]['verifieTime']) <= (3600 * 12)) {
        if ($conn->exec("UPDATE User SET verified=TRUE, urlToVerified = NULL WHERE userId = '$id';"))
            return RtError(["Message"=>"Your mail is verified!" , "succ"=>true]);
    } else {
        echo "False";
    }
}

?>