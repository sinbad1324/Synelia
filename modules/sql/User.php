<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/connection.php";
include $GLOBALS['root'] . "/modules/shuffl.php";
include $GLOBALS['root'] . "/modules/passwordHash.php";



function FindOneUserWithId($id): array
{
   $conn = Connection::GetConnection("Synelia");
   $result = $conn->query("SELECT * FROM User WHERE userId = '$id';");
   if ($result)
      return ["data" => $result->fetch(PDO::FETCH_ASSOC), "succ" => true];
   return ["data" => null, "succ" => false];
}
function FindOneUserWithToken($token): array
{
   $conn = Connection::GetConnection("Synelia");
   $result = $conn->query("SELECT * FROM User WHERE connectionToken = '$token';");
   if ($result)
      return ["data" => $result->fetch(PDO::FETCH_ASSOC), "succ" => true];
   return ["data" => null, "succ" => false];
}
function FindOneUserWithVerifyCode($code): array
{
   $conn = Connection::GetConnection("Synelia");
   $result = $conn->query("SELECT * FROM User WHERE urlToVerified = '$code';");
   if ($result) {
      $newResult = $result->fetch(PDO::FETCH_ASSOC);
      if ($newResult)
         return ["data" => $newResult, "succ" => true];
   }
   return ["data" => null, "succ" => false];
}


function FindOneUserWithMail($email): array
{
   $conn = Connection::GetConnection("Synelia");
   $result = $conn->query("SELECT * FROM User WHERE mail = '$email';");
   if ($result) {
      $newResult = $result->fetch(PDO::FETCH_ASSOC);
      if ($newResult)
         return ["data" => $newResult, "succ" => true];
   }
   return ["data" => null, "succ" => false];

}

function FindUsers($Condition): array
{

}
function FindLastUser(): array
{
   $conn = Connection::GetConnection("Synelia");
   $result = $conn->query("SELECT * FROM User ORDER BY userId DESC LIMIT 1;");
   if ($result)
      return ["data" => $result->fetch(PDO::FETCH_ASSOC), "succ" => true];
   else
      return ["data" => null, "succ" => false];
}

function CreateNewBasket($userID): array
{
   $conn = Connection::GetConnection("Synelia");
   $sth = $conn->query("INSERT INTO Basket (userId) VALUES ('$userID')");
   if ($sth)
      return ["message" => "Your Basket has been created successfully!", "error" => "", "succ" => true];
   else
      return ["message" => "Your Basket could not be created!", "error" => $conn->errorInfo(), "succ" => false];
}

function SetNewTokenForUser($userID): array
{
   $connectionTokenNormal = RandomText(1, 20);
   $connectionTokenHashed = hash_hmac('sha256', $connectionTokenNormal, "M7aXbdrwiXX0yzqdodlWqg==$userID");
   $conn = Connection::GetConnection("Synelia");
   $sth = $conn->prepare("UPDATE User SET connectionToken=? WHERE userId=?");
   $sth->bindParam(1, $connectionTokenHashed, PDO::PARAM_STR);
   $sth->bindParam(2, $userID, PDO::PARAM_STR);
   if ($sth->execute() === true)
      return ["message" => "Your Token has been created successfully!", "data" => $connectionTokenHashed, "error" => "", "succ" => true];
   else
      return ["message" => "Your Token could not be created!", "error" => $conn->errorInfo(), "succ" => false];
}
function CreateNewUser($firstName, $lastName, $password, $email): array
{
   $conn = Connection::GetConnection("Synelia");
   $newUrl = RandomText(1, 21);
   if (!empty($conn)) {
      $sth = $conn->prepare("INSERT INTO User (firstName,lastName,birthDay,mail,password,status , urlToVerified , verifieTime) VALUES (? ,?,NOW() , ?, ? ,'user' ,? , NOW())");
      $sth->bindParam(1, $firstName, PDO::PARAM_STR);
      $sth->bindParam(2, $lastName, PDO::PARAM_STR);
      $sth->bindParam(3, $email, PDO::PARAM_STR);
      $sth->bindParam(4, $password, PDO::PARAM_STR);
      $sth->bindParam(5, $newUrl, PDO::PARAM_STR);
      if ($sth->execute() === true)
         return ["message" => "Your account has been created successfully!", "error" => "", "succ" => true];
   }
   return ["message" => "Your account could not be created!", "error" => mysqli_error($conn), "succ" => false];
}
?>