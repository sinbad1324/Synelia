<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/User.php";

if ($_SERVER['REQUEST_METHOD'] == "PATCH") {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data["userId"];
    $token = $data["token"];
    //TOKEN VERIFIYIN   

    if (!is_int($id)) {
        return;
    }
    $user = FindOneUserWithId($id);
    if (!$user["succ"] || $user["data"] == null) {
        echo "not user";
        return;
    }
    $newCode = RandomText(1, 21);
    if ($user["verified"]) {
        echo "You are already verified!";
        return;
    }
    try {
        $conn = Connection::GetConnection("Synelia");
        if ($conn->query("UPDATE User SET verified=FALSE, urlToVerified ='$newCode ',verifieTime=NOW() WHERE userId = '$id';") === true) {
            echo json_encode(["message" => "Reverify your mail!", "data" => ["link" => "http://localhost/Synelia/api/verifieMailCode.php?code=" . $newCode . "&userId=$id"], "succ" => true]);
            return;
        }
    } catch (\Throwable $th) {
        //throw $th;
        echo $th;
    }
    echo json_encode(["message" => "An error has occurred!", "data" => null, "succ" => false]);
}

?>