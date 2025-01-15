<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/User.php";
include $GLOBALS['root'] . "/modules/sql/Basket.php";
include $GLOBALS['root'] . "/modules/filters/filterUser.php";
include $GLOBALS['root'] . "/modules/crypt.php";

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $data = $_GET;
    $header = getallheaders();
    try {
    if (count($data) > 0) {
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
        if (Crypt::decrypt($user["password"]) != $filtredData["password"]["data"]) {
            echo json_encode(["message" => "Your mail or passowrd  are not validated .", "succ" => false]);
            return;
        }
        if (!$user["verified"]) {
            echo json_encode(value: ["message" => "You still haven't verified your account.", "succ" => false]);
            return;
        }
            $token = RandomText(0,40).$user["userId"];
            $conn = Connection::GetConnection("Synelia");
            $encryptToken = Crypt::encrypt($token);
            $conn->query("UPDATE User SET connectionToken='$encryptToken' , connectedDate=NOW()");
            echo json_encode(value: ["message" => "You are well connected.", "data"=>["Token"=>$token], "succ" => true]);
        } 
    }catch (\Throwable $th) {
        echo $th;
    }

}
?>