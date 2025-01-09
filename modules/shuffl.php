<?php
function RandomText($min, $max): string
{
    $textArray = str_shuffle("1234567890QWERTZUIOPLKJHGGFDSAYXCVBNMmnbvcxyasdfghjklpoiuzuzttrerewq");
    if ($max > strlen($textArray))
        $max = strlen($textArray) - 1;
    return substr($textArray, $min, $max - $min);
}


?>