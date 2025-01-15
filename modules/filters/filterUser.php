<?php


function VerifieToken($token) : array {
    if (!is_string($token)) 
        return ["data" => "", "error" => "we do not accept this type of data." ,"succ"=>false];
    if (strlen($token) > 41)
        return ["data" => "", "error" => "Refused tokens!" ,"succ"=>false];
    if (ctype_space($token))
        return ["data" => "", "error" => "Your token is weird!" ,"succ"=>false];
    // if (msqli($token)) 
    //     return ["data" => "", "error" => "we do not accept this type of data." ,"succ"=>false];

    return ["data" => $token, "error" => "" ,"succ"=>true];
}

function FilterString($name): array
{
    if (!is_string($name)) {
        return ["data" => $name, "error" => "we do not accept this type of data."];
    }
    if (strlen($name) <= 3) {
        return ["data" => $name, "error" => "your name is too short."];
    }
    if (strlen($name) >= 50) {
        return ["data" => $name, "error" => "your name is too long."];
    }
    return ["data" => $name, "error" => ""];
}
function FilterPassword($password): array
{
    if (!is_string($password)) {
        return ["data" => $password, "error" => "we do not accept this type of data."];
    }
    if (strlen($password) < 8) {
        return ["data" => $password, "error" => "Your password is too short, it must contain more than 8 letters."];
    }
    if (!preg_match("/[^a-zA-Z0-9]/", $password) > 0) {
        return ["data" => $password, "error" => "You must have at least one special character in your password."];
    }
    if (!preg_match("/[A-Z]/", $password) > 0) {
        return ["data" => $password, "error" => "You must have at least one uppercase character in your password."];
    }
    if (!preg_match("/[0-9]/", $password) > 0) {
        return ["data" => $password, "error" => "Your password must contain at least one number."];
    }

    return ["data" => $password, "error" => ""];

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


?>