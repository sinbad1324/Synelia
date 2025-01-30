<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include_once $GLOBALS['root'] . "/modules/sql/connection.php";
include_once $GLOBALS['root'] . "/modules/shuffl.php";


function CreateNewPomoCode($solde, $Code=RandomText(1,7), $date=new DateTime(date("Y-m-d H:i:s"))->modify('+3 month')->format('Y-m-d H:i:s') , $nbToProduct=1): Returntype
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("INSERT INTO PromotionCode(code , soldes , expirationDate , nbToProduct) VALUES (? , ? , ?,?);");
    if ($solde > 100)
        $solde = 100;
    $sth->bindParam(1, $commentaire, PDO::PARAM_STR);
    $sth->bindParam(2, $commentaire, PDO::PARAM_STR);
    $sth->bindParam(3, $date, type: PDO::PARAM_STR);
    $sth->bindParam(4, $nbToProduct, type: PDO::PARAM_STR);
    $sth->execute();
    if ($sth) {
        $Count = $CONN->prepare("SELECT COUNT(*)FROM PromotionCode")->fetchColumn();
            return ["Votre Code Promo a été créé avec succes!", "data" => $Count, "succ" => true];
    }
    return ["Cêstewoifiweighwàoighwrgh", "data" => null, "succ" => false];
}

function ConnectPormoToCommande($commandeID, $CodeID): bool
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("INSERT INTO Promotion(commandeId,promotionId) VALUES (? , ?);");
    $sth->bindParam(1 ,$commandeID , type: PDO::PARAM_INT);
    $sth->bindParam(2 ,$CodeID , PDO::PARAM_INT);
    $sth->execute();
    return $sth;
}


function UpdatePromoCode($promotionId  , $code="",$soldes="" , $expirationDate="" , $nbToProduct=""): bool
{
    $CONN = Connection::GetConnection("Synelia");
    $result =$CONN->query("SELECT * FROM PromotionCode WHERE promotionId = '$promotionId' ");
    if ($result) {
        $result = $result->fetch(PDO::FETCH_ASSOC);
        $sth = $CONN->prepare("UPDATE PromotionCode  SET code=? , soldes=? , expirationDate=? , nbToProduct=?  WHERE promotionId = '$promotionId'");
        $code = $code == empty($code) ? $result["code"] : $code ;
        $soldes = $soldes == empty($soldes) ? $result["soldes"] : $soldes ;
        $expirationDate = $expirationDate == empty($expirationDate) ? $result["expirationDate"] : $expirationDate ;
        $nbToProduct = $nbToProduct == empty($nbToProduct) ? $result["nbToProduct"] : $nbToProduct ;
     
        $sth->bindParam(1 , $code , type: PDO::PARAM_STR);
        $sth->bindParam(2 , $soldes , type: PDO::PARAM_STR);
        $sth->bindParam(3 , $expirationDate , type: PDO::PARAM_STR);
        $sth->bindParam(4 , $nbToProduct , type: PDO::PARAM_STR);

        $sth->execute();
        if ($sth) 
            return ["Votre code de promotion a été update avec succes!", "succ" => true];
    }
    return ["Nous avons pas pu update votre avis", "succ" => true]; 
}

?>