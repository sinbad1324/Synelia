<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include_once $GLOBALS['root'] . "/modules/sql/User.php";
include_once $GLOBALS['root'] . "/modules/filters/filterUser.php";
include_once $GLOBALS['root'] . "/modules/crypt.php";
include_once $GLOBALS['root'] . "/modules/errorMessge.php";

if ($_SERVER['REQUEST_METHOD'] == "PATCH") {
    try {
        $conn = Connection::GetConnection("Synelia");
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data["userId"];
        $filtredData = filter_var_array($data, $filter);
        $token = $data["token"];
        //TOKEN VERIFIYIN   

        if (!is_int($id)) {
            return;
        }
        $user = FindOneUserWithId($id);
        if (!$user["succ"] || $user["data"] == null) {
            echo "not user";
            return RtError(["Message"=>"This user dont exist!", "succ"=>false]);
        }
        #...UpdateCode
        if (strlen(string: $filtredData["firstName"]["error"]) >= 1) {
            return RtError(["message" => $filtredData["firstName"]["error"], "succ" => false]);
        }
        if (strlen($filtredData["lastName"]["error"]) >= 1) {
            return RtError(["message" => $filtredData["lastName"]["error"], "succ" => false]);
        }
        if (strlen($filtredData["password"]["error"]) >= 1) {
            return RtError(["message" => $filtredData["password"]["error"], "succ" => false]);
        }

        echo isset($data["mail"]);
        $changement = "";
        if (isset($data["mail"])) {
            if (strlen($filtredData["mail"]) <= 0) {
                return RtError(["message" => "Your mail is not validated.", "succ" => false]);
            }
            $sqlQuery = $changement . "mail='" . $filtredData['mail'] . "' ";
            $newCode = RandomText(1, 21);
            $conn->query(query: "UPDATE User SET verified=FALSE, urlToVerified ='$newCode' ,verifieTime=NOW() ,connectionToken=NULL WHERE userId = '$id';");
            echo "http://localhost:8080/Synelia/api/verifieMailCode.php?code=" . $newCode . "&userId=" . $id;
        }
        if ($filtredData['firstName']["data"] != null)
            $changement = $changement . "firstName='" . $filtredData['firstName']["data"] . "',";
        if ($filtredData['lastName']["data"] != null)
            $changement = $changement . "lastName='" . $filtredData['lastName']["data"] . "',";
        if ($filtredData['password']["data"] != null) {
            $changement = $changement . "password='" . pwdHash($filtredData['password']["data"]) . "', ";
            $conn->query(query: "UPDATE User SET connectionToken=NULL WHERE userId = '$id';");
        }

        $position = strpos($changement, ",");
        if ($position !== false) {
            $changement = substr($changement, 0, $position);
        }
        if ($conn->query(query: "UPDATE User SET " . $changement . " WHERE userId = '$id';")) {
            echo json_encode(["message" => "Updated", "succ" => true]);
            return ["message" => "Updated", "succ" => true];
        }
        echo json_encode(["message" => "An error has occurred!", "data" => null, "succ" => false]);
    } catch (\Throwable $th) {
        echo $th;
    }
}
?>