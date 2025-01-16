<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/connection.php";
include $GLOBALS['root'] . "/modules/shuffl.php";

function CreateNewProduct(): Returntype
{
    $CONN = Connection::GetConnection("Synelia");
    $CONN->query("INSERT produitName , prix , totalStock , marque  INTO Product VALUES ()");
}

function UpdateProduct(): Returntype
{
}
function DeleteProduct(): Returntype
{
}
function GetProduct(): Returntype
{
}



?>