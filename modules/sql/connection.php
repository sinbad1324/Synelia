<?php

/**
 * /
 * Description : This function create a connection with the dataBase and get a function callback try to execute the function and after finish he function the connection closing.
 * The params $callback get in first the $connection and after urs args.... 👌
 * The SecondParams is the db name
 * Return your callback result.
 * @template  T
 * @param string $dbName 
 * @return T
 */

class Connection
{
    static private $connection;
    static public function GetConnection($dbName)
    {
        if (empty(self::$connection)) {
            self::$connection = new mysqli("localhost", "root", "1324", $dbName);
            if (!self::$connection) {
                echo "connection false " . mysqli_connect_error();
                return null;
            }
            return self::$connection;
        } else {
            return self::$connection;
        }
    }
}



?>