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



function FindOneUserWithId() : array {
   
}

function FindUsers($Condition) : array {
   
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