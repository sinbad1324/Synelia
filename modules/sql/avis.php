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
    $sth->bindParam(1, $commentaire, PDO::PARAM_STR);
    $sth->execute();
    if ($sth) {
        $Count = $CONN->prepare("SELECT COUNT(*)FROM Avis")->fetchColumn();

        return ["Votre avis a été créé avec succes!", "data" => $Count, "succ" => true];
    }
    return ["Cêstewoifiweighwàoighwrgh", "data" => null, "succ" => false];
}


function ConnectAvisNote($productID, $avisID): bool
{

}

?>