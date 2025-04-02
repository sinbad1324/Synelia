<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include_once $GLOBALS['root'] . "/modules/sql/connection.php";
include_once $GLOBALS['root'] . "/modules/shuffl.php";
include_once $GLOBALS['root'] . "/modules/sql/Categorie.php";

function CreateNewProduct($produitName, $prix, $totalStock, $marque, $Description, $categorieID): array
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("INSERT INTO Product( produitName , prix , totalStock , marque ,description )  VALUES (?,?,?,?,?)");
    $sth->bindParam(1, $produitName, PDO::PARAM_STR);
    $sth->bindParam(2, $prix, PDO::PARAM_STR);
    $sth->bindParam(3, $totalStock, PDO::PARAM_INT);
    $sth->bindParam(4, $marque, PDO::PARAM_STR);
    $sth->bindParam(5, $Description, PDO::PARAM_STR);
    $sth->execute();
    if ($sth) {
        $Count = $CONN->query("SELECT COUNT(*) FROM Product")->fetchColumn();
        if (SetCategorieForProduct($categorieID, $Count)["succ"] == true) {
            return ["message" => "Your Product has been created successfully!", "data" => ["id" => $Count], "error" => "", "succ" => true];
        }
    }
    return ["message" => "Your Product could not be created!", "error" => $sth->errorInfo(), "data" => [], "succ" => false];
}
function UpdateProductName($ProdID, $newName): bool
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("UPDATE Product SET produitName = ? WHERE produitId  = ?");
    $sth->bindParam(1, $newName, PDO::PARAM_STR);
    $sth->bindParam(2, $ProdID, PDO::PARAM_INT);
    $sth->execute();
    if ($sth)
        return true;
    return false;
}

function UpdateProductPrix($ProdID, $newPrix): bool
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("UPDATE Product SET prix = ? WHERE produitId  = ?");
    $sth->bindParam(1, $newPrix, PDO::PARAM_STR);
    $sth->bindParam(2, $ProdID, PDO::PARAM_INT);
    $sth->execute();
    if ($sth)
        return true;
    return false;
}

function UpdateProductStock($ProdID, $newStock): bool
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("UPDATE Product SET totalStock = ? WHERE produitId  = ?");
    $sth->bindParam(1, $newStock, PDO::PARAM_INT);
    $sth->bindParam(2, $ProdID, PDO::PARAM_INT);
    $sth->execute();
    if ($sth)
        return true;
    return false;
}
function UpdateProductMarque($ProdID, $newmarque): bool
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("UPDATE Product SET marque = ? WHERE produitId  = ?");
    $sth->bindParam(1, $newmarque, PDO::PARAM_STR);
    $sth->bindParam(2, $ProdID, PDO::PARAM_INT);
    $sth->execute();
    if ($sth)
        return true;
    return false;
}

function UpdateProductDescription($ProdID, $newDescription): bool
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("UPDATE Product SET description = ? WHERE produitId  = ?");
    $sth->bindParam(1, $newDescription, PDO::PARAM_STR);
    $sth->bindParam(2, $ProdID, PDO::PARAM_INT);
    $sth->execute();
    if ($sth)
        return true;
    return false;
}
function DeleteProduct($ProdID): bool
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->query("DELETE FROM Product WHERE produitId = $ProdID");
    $sth->execute();
    if ($sth)
        return true;
    return false;
}
function GetProduct($ProdID): array
{
    $CONN = Connection::GetConnection("Synelia");
    $Prod = $CONN->query("SELECT * FROM Product WHERE produitId = $ProdID");
    // $CatID = $CONN->query("SELECT * FROM Categoriser WHERE produitId = $ProdID");
    // if (condition) {
    //     # code...
    // }
    if ($Prod)
        return ["message" => "", "data" => $Prod->fetch(), "succ" => true];
    else
        return ["message" => "", "data" => [], "succ" => false];
}




?>