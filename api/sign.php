<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT']."/Synelia";
echo "\n".$GLOBALS["root"]."ggggg\n";
include $GLOBALS['root']."/modules/sql/User.php";
include $GLOBALS['root']."/modules/sql/Basket.php";

function FilterString($name): array
{
    if (!is_string($name)){
        return ["data"=>$name , "error"=>"we do not accept this type of data."];
    }
    if (strlen($name) <= 3) {
        return ["data"=>$name , "error"=>"your name is too short."];
    }
    return ["data"=>$name , "error"=>""];
}
function FilterPassword($password): array
{
    if (!is_string($password)){
        return ["data"=>$password , "error"=>"we do not accept this type of data."];
    }
    if (strlen($password) < 8) {
       return ["data"=>$password , "error"=>"Your password is too short, it must contain more than 8 letters."];
    }
    if (!preg_match("/[^a-zA-Z0-9]/", $password) > 0) {
        return ["data"=>$password , "error"=>"You must have at least one special character in your password."];
    }
    if (! preg_match("/[A-Z]/", $password) > 0 ) {
        return ["data"=>$password , "error"=>"You must have at least one uppercase character in your password."];
    }
    if ( !preg_match("/[0-9]/", $password) > 0 ) {
        return ["data"=>$password , "error"=>"Your password must contain at least one number."];
    }

    return ["data"=>$password[0] , "error"=> ""];
   
}
function FilterBirthDay(): string
{
    return "";
}
$filter = [
    "firstName" => [
        "filter" => FILTER_CALLBACK,
        "flag" => FILTER_FORCE_ARRAY,
        "options" => "FilterString"
    ],
    "lastName" => [
        "filter" => FILTER_CALLBACK,
        "flag" => FILTER_FORCE_ARRAY,
        "options" => "FilterString"
    ],
    "mail" => [
        "filter" => FILTER_VALIDATE_EMAIL,
        "flag" => FILTER_FLAG_NONE,
    ],
    "password" => [
        "filter" => FILTER_CALLBACK,
        "flag" => FILTER_FORCE_ARRAY,
        "options" => "FilterPassword"
    ],

    // "birthDay"=>[
    //     "filter" =>  FILTER_CALLBACK,
    //     "flag" => FILTER_FLAG_EMPTY_STRING_NULL,
    //     "options" => "FilterBirthDay"
    // ]
];

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
        $filtredData = filter_var_array($data,  $filter);
        for ($i=0; $i < count(UserDataStruct); $i++) { 
            $element = UserDataStruct[$i];
            if (!array_key_exists($element , $data)) {
                echo json_encode(["message"=> "You are missing ($element) in your data" ,"succ"=>false]);
                return;
            }
        }

        if (strlen(string: $filtredData["firstName"]["error"]) >= 1) {
            echo json_encode(["message"=> $filtredData["firstName"]["error"],"succ"=>false]);
            return;
        }
        if (strlen($filtredData["lastName"]["error"]) >= 1) {
            echo json_encode(["message"=> $filtredData["lastName"]["error"],"succ"=>false]);
            return;
        }

        if (strlen($filtredData["mail"]) <= 0) {
            echo json_encode(["message"=> "Your mail is not validated.","succ"=>false]);
            return;
        }
        if (strlen($filtredData["password"]["error"]) >= 1) 
        {
            echo json_encode(["message"=> $filtredData["password"]["error"],"succ"=>false]);
            return;
        }
        if(CreateNewUser($filtredData["firstName"]["data"],$filtredData["lastName"]["data"] ,$filtredData["password"]["data"] ,$filtredData["mail"] ) == true)
        {   echo json_encode(["message"=> "We have successfully created your account.","succ"=>true]);
            echo json_encode(CreateNewBasket(1))  ;  
        }
    }
} catch (Throwable $th) {
    echo $th;
}
    
}



?>