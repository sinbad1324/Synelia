<?php
$format = "Y-M-D H-i-s";
function CreateCommande($basketID ,$quantity ,$deliveryDate=new DateTime(date($format))->modify("+7 day")->format($format) ): array
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("INSERT INTO Commande(quantity ,deliveryDate) VALUES (? , ? );");
    if ($quantity < 1)
        $quantity =1;
    $sth->bindParam(1, $quantity, PDO::PARAM_INT);
    $sth->bindParam(2, $deliveryDate, PDO::PARAM_STR);
    $sth->execute();
    if ($sth) {
        $Count = $CONN->prepare("SELECT COUNT(*)FROM PromotionCode")->fetchColumn();
        if (ConnectCommandeToBasket($Count , $basketID)) 
            return ["Votre Code Promo a été créé avec succes!", "data" => $Count, "succ" => true];
    }
    return ["Cêstewoifiweighwàoighwrgh", "data" => null, "succ" => false];
}


function ConnectCommandeToBasket($commandeID, $BasketID): bool
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("INSERT INTO Commander(commandeId,BasketID) VALUES (? , ?);");
    $sth->bindParam(1 ,$commandeID , type: PDO::PARAM_INT);
    $sth->bindParam(2 ,$BasketID , PDO::PARAM_INT);
    $sth->execute();
    return $sth;
}


function ConnectCommandeToProduct($commandeID, $productID): bool
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("INSERT INTO Commander(commandeId,produitId) VALUES (? , ?);");
    $sth->bindParam(1 ,$commandeID , type: PDO::PARAM_INT);
    $sth->bindParam(2 ,$productID , PDO::PARAM_INT);
    $sth->execute();
    return $sth;
}



?>