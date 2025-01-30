<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/connection.php";
include $GLOBALS['root'] . "/modules/shuffl.php";

function CreateNewProduct($produitName, $prix, $totalStock, $marque, $categorieId): Returntype
{
    $CONN = Connection::GetConnection("Synelia");
    $CONN->query("INSERT INTO Product( produitName , prix , totalStock , marque)  VALUES ('$produitName' , '$prix','$totalStock','$marque')");
    $CONN->prepare("INSERT INTO Product( produitName , prix , totalStock , marque)  VALUES (?,?,?,?)");
    $CONN->bindParam();

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