<?php


class Crypt
{
    private static $key = "loAJQFlt7iJ3HQ6qknD7uwYO9G64w";
    private static $method = "camellia-256-ofb";
    private static $ivlen;
    private static $iv;
    private static function Init()
    {
        if (empty(self::$iv) || empty(self::$ivlen)) {
            $ivlen = openssl_cipher_iv_length(self::$method);
            $iv = openssl_random_pseudo_bytes(50);
        }
    }
    public static function encrypt($text): string
    {
        self::Init();
        return openssl_encrypt($text, self::$method, self::$key, 0, self::$iv);
    }
    public static function decrypt($text): string
    {
        self::Init();
        return openssl_decrypt($text, self::$method, self::$key, 0, self::$iv);
    }
}
?>