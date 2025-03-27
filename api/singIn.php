<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/User.php";
include $GLOBALS['root'] . "/modules/sql/Basket.php";
include $GLOBALS['root'] . "/modules/filters/filterUser.php";
include $GLOBALS['root'] . "/modules/crypt.php";
include $GLOBALS['root'] . "/modules/JWT/JWT.php";
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        $header = getallheaders();
        $filtredData = filter_var_array($data, $filter);
        if (strlen($filtredData["mail"]) <= 0) {
            echo json_encode(["message" => "Your mail is not validated.", "succ" => false]);
            return;
        }
        if (strlen($filtredData["password"]["error"]) >= 1) {
            echo json_encode(["message" => $filtredData["password"]["error"], "succ" => false]);
            return;
        }
        $finidedUser = FindOneUserWithMail($filtredData["mail"]);
        if (!$finidedUser["succ"]) {
            echo json_encode(["message" => "Your mail or passowrd  are not validated .", "succ" => false]);
            return;
        }
        $user = $finidedUser["data"];
        if (!password_verify($filtredData["password"]["data"], $user["password"])) {
            echo json_encode(["message" => "Your mail or passowrd  are not validated passss .", "succ" => false]);
            return;
        }
        if (!$user["verified"]) {
            echo json_encode(value: ["message" => "You still haven't verified your account.", "succ" => false]);
            return;
        }
        $token = SetNewTokenForUser($user["userId"]);
        $efwef = 23;
        $userJWT = new JWT($token, $user["firstName"], 7);
        $US = $userJWT->GenerateJWT($user["userId"]);
        echo json_encode(value: [
            "message" => "You are well connected.",
            "data" => $US,
            "succ" => true
        ]);
    } catch (\Throwable $th) {
        echo $th;
    }
}
?>