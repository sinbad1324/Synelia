<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/sql/connection.php";
include $GLOBALS['root'] . "/modules/shuffl.php";



function CreateNewUser($firstName, $lastName, $password, $email): array
{
   $conn = Connection::GetConnection("Synelia");
   $newUrl = RandomText(1, 21);
   if (!empty($conn)) {
      $conn->prepare("INSERT INTO User (firstName,lastName,birthDay,mail,password,status , urlToVerified , verifieTime) VALUES (? ,?,NOW() , ?, ? ,'user' ,? , NOW())");
      $conn->bindParams(1, $firstName, PDO::PARAM_STR);
      $conn->bindParams(2, $lastName, PDO::PARAM_STR);
      $conn->bindParams(3, $email, PDO::PARAM_STR);
      $conn->bindParams(4, $password, PDO::PARAM_STR);
      $conn->bindParams(5, $newUrl, PDO::PARAM_STR);
      if ($conn->execute() === true)
         return ["message" => "Your account has been created successfully!", "error" => "", "succ" => true];
   }
   return ["message" => "Your account could not be created!", "error" => mysqli_error($conn), "succ" => false];
}

function FindOneUserWithId($id): array
{
   $conn = Connection::GetConnection("Synelia");
   $result = $conn->query("SELECT * FROM User WHERE userId = '$id';");
   if ($result)
      return ["data" => mysqli_fetch_array($result, MYSQLI_ASSOC), "succ" => true];
   return ["data" => null, "succ" => false];
}
function FindOneUserWithToken($token): array
{
   $conn = Connection::GetConnection("Synelia");
   $result = $conn->query("SELECT * FROM User WHERE connectionToken = '$token';");
   if ($result)
      return ["data" => mysqli_fetch_array($result, MYSQLI_ASSOC), "succ" => true];
   return ["data" => null, "succ" => false];
}
function FindOneUserWithVerifyCode($code): array
{
   $conn = Connection::GetConnection("Synelia");
   $result = $conn->query("SELECT * FROM User WHERE urlToVerified = '$code';");
   if ($result) {
      $newResult = mysqli_fetch_array($result, MYSQLI_ASSOC);
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
      $newResult = mysqli_fetch_array($result, MYSQLI_ASSOC);
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
      return ["data" => mysqli_fetch_array($result, MYSQLI_ASSOC), "succ" => true];
   else
      return ["data" => null, "succ" => false];
}

function CreateNewBasket($userID): array
{
   $conn = Connection::GetConnection("Synelia");
   if ($conn->query("INSERT INTO Basket (userId) VALUES ('$userID')") === true)
      return ["message" => "Your Basket has been created successfully!", "error" => "", "succ" => true];
   else
      return ["message" => "Your Basket could not be created!", "error" => mysqli_error($conn), "succ" => false];
}
?>