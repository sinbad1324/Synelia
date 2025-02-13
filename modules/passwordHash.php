<?php
function pwdHash($pass, $cost = 10): string
{
    return password_hash($pass, PASSWORD_BCRYPT, ["cost" => 10]);
}
?>