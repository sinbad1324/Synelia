<?php 
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include_once $GLOBALS['root'] . "/modules/sql/connection.php";
include_once $GLOBALS['root'] . "/modules/shuffl.php";
include_once $GLOBALS['root'] . "/modules/sql/Categorie.php"

function FindBasketFromId($basketId ) : array {
    $CONN = Connection::GetConnection("Synelia");
    $basket = $CONN->query("SELECT * FROM Basket WHERE basketId = $basketId");
    if ($basket != null) 
        return ["message"=>"","data"=>$basket->fetch(), "succ"=>true]; 
    return ["message"=>"", "succ"=>false];
}

function UpdateBasket($basketId , $newProduct) : array {
    $CONN = Connection::GetConnection("Synelia");
    $basket = FindBasketFromId($basketId);
    if ($basket["succ"]==false) 
        return $basket;
    $ProdutIds = array_merge($basket["data"]["productIds"] , $newProduct);
    $sth = $CONN->prepare("UPDATE Basket SET productIds = ?");
    $sth->bindParam(1  , $ProdutIds ,PDO::PARAM_STR);
    
    if($sth->execute()) return ["message"=>"Update successe!" , "succ"=>true];
    else return ["message"=>"Update UNsuccesse!" , "succ"=>FALSE];

}

?>