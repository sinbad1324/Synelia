<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT']."/Synelia";
include $GLOBALS['root']."/modules/sql/connection.php";
function CreateNewUser($firstName ,$lastName, $password , $email):array
{
   return Connection(function($conn , $firstName ,$lastName, $password , $email) : array
   {
      if ( $conn->query("INSERT INTO User (firstName,lastName,birthDay,mail,password,status) VALUES ('$firstName' ,'$lastName',NOW() , '$email', '$password' ,'user' )") === true)
         return ["message"=>"Your account has been created successfully!", "error"=>"", "succ"=> true ];
      else  
         return  ["message"=>"Your account could not be created!", "error"=>mysqli_error($conn), "succ"=> false ];
   },"Synelia",$firstName , $lastName ,$password , $email );
}



function FindOneUserWithId($id) : array {
   return Connection(function($conn , $id) : array
   {
      $result = $conn->query("SELECT * FROM User WHERE userId = $id;") ;
      if ($result) 
         return mysqli_fetch_array($result ,MYSQLI_ASSOC);
      else
         return [];
   },"Synelia" , $id);
}

function FindOneUserWithMail($email) : array {
   return Connection(function($conn , $email) : array
   {
      $result = $conn->query("SELECT * FROM User WHERE mail = $email;") ;
      if ($result) 
         return mysqli_fetch_array($result ,MYSQLI_ASSOC);
      else
         return [];
   },"Synelia" , $email);
}

function FindUsers($Condition) : array {
   
}
function FindLastUser() : array {
   return Connection(function($conn) : array
   {
      $result = $conn->query("SELECT * FROM User ORDER BY userId DESC LIMIT 1;") ;
      if ($result) 
         return mysqli_fetch_array($result ,MYSQLI_ASSOC);
      else
         return [];
   },"Synelia");
}

function CreateNewBasket($userID):array
{
   return Connection(function($conn , $userID) : array
   {
      if ( $conn->query("INSERT INTO Basket (userId) VALUES ('$userID')") === true)
         return ["message"=>"Your Basket has been created successfully!", "error"=>"", "succ"=> true ];
      else  
         return  ["message"=>"Your Basket could not be created!", "error"=>mysqli_error($conn), "succ"=> false ];
   },"Synelia", $userID);
}
?>