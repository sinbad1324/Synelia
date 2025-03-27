<?php

function FilterForIDs($id): array
{
    if ($id <= 0)
        return ["data" => null, "message" => "Votre id doit etre plus grand que 0.", "succ" => false];
    if (!is_int($id))
        return ["data" => null, "message" => "Votre id doit etre du type int.", "succ" => false];
    if (!filter_var($id ,FILTER_VALIDATE_INT))
             return ["data" => null, "message" => "Votre id doit etre du type int.", "succ" => false];
    return ["data" => $id, "message" => "", "succ" => true];
}

function FilterName($str): array
{
    if (strlen($str) <= 0)
        return ["data" => null, "message" => "Cette donnée doit etre plus grand que 0.", "succ" => false];
    if (!is_string($str))
        return ["data" => null, "message" => "Votre valeur doit etre du type string.", "succ" => false];
    if (!preg_match("/[^a-zA-Z]/"))
        return ["data" => null, "message" => "Votre valeur doit etre composé que de a-zA-Z", "succ" => false];
    return ["data" => filter_var($str , FILTER_SANITIZE_SPECIAL_CHARS), "message" => "", "succ" => true];
}
function DecimalPrix($prix): array
{
    if (strlen($prix) <= 0)
        return ["data" => null, "message" => "Cette donnée doit etre plus grand que 0.", "succ" => false];
    if (strpos($prix, ".") === false)
        return ["data" => null, "message" => "Cette donnée doit etre du type decimal ('00.00')", "succ" => false];
    if (substr($prix, 0, strpos($prix, ".") + 1)) {
        # code...
    }
}
$FilterProduct = [
    "produitName" => [
        "filter" => FILTER_CALLBACK,
        "flag" => FILTER_FORCE_ARRAY,
        "options" => ""
    ],
    "prix" => [
        "filter" => FILTER_CALLBACK,
        "flag" => FILTER_FORCE_ARRAY,
        "options" => ""
    ],
    "totalStock" => [
        "filter" => FILTER_CALLBACK,
        "flag" => FILTER_FORCE_ARRAY,
        "options" => ""
    ],
    "marque" => [
        "filter" => FILTER_CALLBACK,
        "flag" => FILTER_FORCE_ARRAY,
        "options" => ""
    ],
    "Description" => [
        "filter" => FILTER_CALLBACK,
        "flag" => FILTER_FORCE_ARRAY,
        "options" => ""
    ],
    "categorieID" => [
        "filter" => FILTER_CALLBACK,
        "flag" => FILTER_FORCE_ARRAY,
        "options" => ""
    ],
]


    ?>