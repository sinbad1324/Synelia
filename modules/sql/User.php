<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT']."/Synelia";
include $GLOBALS['root']."/modules/sql/connection.php";

$userName = "inDelivery";
$password = 12.34;
$email = "abdoul@gogo.com";

Connection(function($conn , $userName , $password , $email) : void {
   if ( $conn->query("INSERT INTO User (firstName,lastName,birthDay,mail,password,basketId,status) VALUES ('$userName' ,'hassan',NOW() , '$email', '$password' , '1' ,'user' )") === true) {
    echo"lejokweofjewofjoewfjoewjfowejf";
   }else {
    echo "Noèe";
   }
},"Synelia",$userName , $password , $email )

?>