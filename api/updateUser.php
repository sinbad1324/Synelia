<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/User.php";
include $GLOBALS['root'] . "/modules/filters/filterUser.php";
include $GLOBALS['root'] . "/modules/crypt.php";

if ($_SERVER['REQUEST_METHOD'] == "PATCH") {
    try {
        $conn = Connection::GetConnection("Synelia");
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data["userId"];
        $filtredData = filter_var_array($data, $filter);
        if (!is_int($id)) {
            return;
        }
        if (!isset($data["connectionToken"])) {
            echo json_encode(value: ["message" => "We have a problem with your token!", "succ" => false]);
        }
        $verifieToken = VerifieToken($data["connectionToken"]);
        if (!$verifieToken["succ"]) {
            echo json_encode($verifieToken);
            return ;
        }
        
        $user = FindOneUserWithToken(Crypt::encrypt($data["connectionToken"]));
        if (!$user["succ"] || $user["data"] == null) {
            echo "not user";
            return;
        }
        #...UpdateCode
        if (strlen(string: $filtredData["firstName"]["error"]) >= 1) {
            echo json_encode(["message" => $filtredData["firstName"]["error"], "succ" => false]);
            return;
        }
        if (strlen($filtredData["lastName"]["error"]) >= 1) {
            echo json_encode(["message" => $filtredData["lastName"]["error"], "succ" => false]);
            return;
        }
        if (strlen($filtredData["password"]["error"]) >= 1) {
            echo json_encode(value: ["message" => $filtredData["password"]["error"], "succ" => false]);
            return;
        }
        
        $changement = "";
        if (isset($data["mail"])) {
            if (strlen($filtredData["mail"]) <= 0) {
                echo json_encode(["message" => "Your mail is not validated.", "succ" => false]);
                return;
            }
            $sqlQuery = $changement . "mail='" . $filtredData['mail'] . "' ";
            $newCode = RandomText(1, 21);
            $conn->query(query: "UPDATE User SET verified=FALSE, urlToVerified ='$newCode' ,verifieTime=NOW() ,connectionToken=NULL WHERE userId = '$id';");
            echo "http://localhost/Synelia/api/verifieMailCode.php?code=" . $newCode . "&userId=" . $id;
        }
        if ($filtredData['firstName']["data"] != null)
            $changement = $changement . "firstName='" . $filtredData['firstName']["data"] . "',";
        if ($filtredData['lastName']["data"] != null)
            $changement = $changement . "lastName='" . $filtredData['lastName']["data"] . "',";
        if ($filtredData['password']["data"] != null) {
            $changement = $changement . "password='" . Crypt::encrypt($filtredData['password']["data"]) . "', ";
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