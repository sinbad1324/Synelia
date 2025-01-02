<?php

/**
 * /
 * Description : This function create a connection with the dataBase and get a function callback try to execute the function and after finish he function the connection closing.
 * The params $callback get in first the $connection and after urs args.... 👌
 * The SecondParams is the db name
 * @param callable $callback 
 * @param string $dbName 
 * @return void
 */
function Connection(callable $callback ,$dbName , ...$args) : void {
    $conn = new mysqli("localhost", "root" , "1324",$dbName);
    if (!$conn) {
        echo"connection false ".mysqli_connect_error();
    }
    try {
        $callback($conn , ...$args);
    } catch (\Throwable $th) {
        echo "$th";
    }finally{
        $conn->close();
    }
}
?>