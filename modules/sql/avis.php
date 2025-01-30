<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include_once $GLOBALS['root'] . "/modules/sql/connection.php";

function CreateNewAvis($commentaire, $note, $productID): Returntype
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("INSERT INTO Avis(commentaire , note , dateAvis) VALUES (? , ? , NOW());");
    if ($note > 5)
        $note = 5;

    $sth->bindParam(1, $commentaire, PDO::PARAM_STR);
    $sth->bindParam(2, $note, PDO::PARAM_INT);
    $sth->execute();
    if ($sth) {
        $Count = $CONN->prepare("SELECT COUNT(*)FROM Avis")->fetchColumn();
        if (ConnectAvisNote($productID , $Count)) 
            return ["Votre avis a été créé avec succes!", "data" => $Count, "succ" => true];
    }
    return ["Cêstewoifiweighwàoighwrgh", "data" => null, "succ" => false];
}


function ConnectAvisNote($productID, $avisID): bool
{
    $CONN = Connection::GetConnection("Synelia");
    $sth = $CONN->prepare("INSERT INTO Noter(produitId,avisId) VALUES (? , ?);");
    $sth->bindParam(1 ,$productID , PDO::PARAM_INT);
    $sth->bindParam(2 ,$avisID , PDO::PARAM_INT);
    $sth->execute();
    return $sth;
}


function UpdateAvis($avisID  , $commentaire="",$note=-1): bool
{
    $CONN = Connection::GetConnection("Synelia");
    $result =$CONN->query("SELECT commentaire , note FROM Avis WHERE avisId = '$avisID' ");
    if ($result) {
        $result = $result->fetch(PDO::FETCH_ASSOC);
        $commentaire = $commentaire == empty($commentaire) ? $result["commentaire"] : $commentaire ;
        $note = $note == -1 ? $result["note"] : $note ;
        $sth = $CONN->prepare("UPDATE Avis SET commentaire=? , note=? WHERE avisId = ? ");
        $sth->bindParam(1 , $commentaire , PDO::PARAM_STR);
        $sth->bindParam(2 , $note , PDO::PARAM_INT);
        $sth->bindParam(3 , $avisID , PDO::PARAM_INT);
        $sth->execute();
        if ($sth) 
            return ["Votre avis a été update avec succes!", "succ" => true];
    }
    return ["Nous avons pas pu update votre avis", "succ" => true]; 
}

?>