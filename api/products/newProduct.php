<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/crypt.php";
include $GLOBALS['root'] . "/modules/product.php";

CreateNewProduct("yo", 20.00, 5, marque: "niikker", 1)
    ?>