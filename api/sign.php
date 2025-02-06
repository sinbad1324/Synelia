<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/User.php";
include $GLOBALS['root'] . "/modules/sql/Basket.php";
include $GLOBALS['root'] . "/modules/filters/filterUser.php";
include $GLOBALS['root'] . "/modules/crypt.php";

define("UserDataStruct", [
    "firstName",
    "lastName",
    "mail",
    "password"
]);
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $header = getallheaders();
    try {
        if (isset($data) && isset($header)) {
            $filtredData = filter_var_array($data, $filter);
            for ($i = 0; $i < count(UserDataStruct); $i++) {
                $element = UserDataStruct[$i];
                if (!array_key_exists($element, $data)) {
                    echo json_encode(["message" => "You are missing ($element) in your data", "succ" => false]);
                    return;
                }
            }
            if (strlen(string: $filtredData["firstName"]["error"]) >= 1) {
                echo json_encode(["message" => $filtredData["firstName"]["error"], "succ" => false]);
                return;
            }
            if (strlen($filtredData["lastName"]["error"]) >= 1) {
                echo json_encode(["message" => $filtredData["lastName"]["error"], "succ" => false]);
                return;
            }

            if (!isset($filtredData["mail"]) && strlen($filtredData["mail"]) <= 0) {
                echo json_encode(["message" => "Your mail is not validated.", "succ" => false]);
                return;
            }
            if (strlen($filtredData["password"]["error"]) >= 1) {
                echo json_encode(["message" => $filtredData["password"]["error"], "succ" => false]);
                return;
            }
            if (FindOneUserWithMail($filtredData["mail"])["succ"] == true) {
                echo json_encode(["message" => "Your mail is not validated .", "succ" => false]);
                return;
            }
            if (CreateNewUser($filtredData["firstName"]["data"], $filtredData["lastName"]["data"], pwdHash($filtredData["password"]["data"]), $filtredData["mail"]) == true) {
                $userData = FindLastUser();
                echo "http://localhost/Synelia/api/verifieMailCode.php?code=" . $userData["data"]['urlToVerified'] . "&userId=" . $userData["data"]["userId"];
                CreateNewBasket($userData["data"]["userId"]);
                echo json_encode(["message" => "We have successfully created your account.", "succ" => true]);
            }
        }
    } catch (Throwable $th) {
        echo $th;
    }
}
?>