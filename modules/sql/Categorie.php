<?php
// $GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include_once $GLOBALS['root'] . "/modules/sql/connection.php";
// include_once $GLOBALS['root'] . "/modules/shuffl.php";
function SetCategorieForProduct($catID, $ProdID): array
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("INSERT INTO Categoriser( categorieId , produitId )  VALUES (?,?)");
    $sth->bindParam(1, $catID, PDO::PARAM_INT);
    $sth->bindParam(2, $ProdID, PDO::PARAM_INT);
    $sth->execute();
    if ($sth)
        return ["succ" => true];
    return ["succ" => false];
}


?>