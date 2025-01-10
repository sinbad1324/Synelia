<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/connection.php";
include $GLOBALS['root'] . "/modules/shuffl.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $header = getallheaders();
    if ($data["userId"]) {
        if (!is_int($data["userId"])) {
            return;
        }

        $user = FindOneUserWithId($id);
        if (!$user["succ"] || $user["data"] == null) {
            echo "not user";
            return;
        }
        echo json_encode(CreateNewBasket($data["userId"]));
    }
}

?>