<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/User.php";
include $GLOBALS['root'] . "/modules/sql/Basket.php";
include $GLOBALS['root'] . "/modules/filters/filterUser.php";
include $GLOBALS['root'] . "/modules/crypt.php";

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $data = json_decode(file_get_contents("php://input"), true);
        $header = getallheaders();
        if ($data["userId"]) {
            if (!is_int($data["userId"])) {
                return;
            }
            if (!isset($data["connectionToken"])) {
                echo json_encode(value: ["message" => "We have a problem with your token!", "succ" => false]);
            }
            $verifieToken = VerifieToken($data["connectionToken"]);
            if (!$verifieToken["succ"]) {
                echo json_encode($verifieToken);
                return;
            }
            $user = FindOneUserWithToken(pwdHash($data["connectionToken"]));
            if (!$user["succ"] || $user["data"] == null) {
                echo "not user";
                return;
            }
            if ($data["userId"] != $user["data"]["userId"]) {
                echo json_encode(["message" => "Your id is not validated!", "succ" => false]);
                return;
            }
            echo json_encode(CreateNewBasket($data["userId"]));
        }
    }
} catch (\Throwable $th) {
    echo $th;
}

?>