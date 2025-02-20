<?php
class JWT
{
    private $header;
    private $payload;
    public function __construct($userConnection, $userName, $nbDay)
    {
        $this->header = base64_encode(json_encode(["alg" => "HS256", "typ" => "JWT"]));
        $this->payload = base64_encode(json_encode([
            "name" => $userName,
            "userToken" => $userConnection,
            "FinalDate" => date("U") + (86400 * $nbDay)
        ]));
    }
    public function GenerateJWT($userID): string
    {
        $body = $this->header . "." . $this->payload;
        $signature = hash_hmac('sha256', $body, "W7TaOhCO3661JVRFTJeIQxMwzuuLNz7Aw5c1l7jSehQ=$userID", true);
        return $body . "." . $signature;
    }
    public static function IsValidateJWT($JWT, $userID): array
    {
        $JWTArray = preg_split("/[\.]+/", $JWT);
        $signature = $JWTArray[2];
        $HashedSignature = hash_hmac('sha256', $JWTArray[0] . "." . $JWTArray[1], "W7TaOhCO3661JVRFTJeIQxMwzuuLNz7Aw5c1l7jSehQ=$userID", true);
        if ($HashedSignature === $signature)
            return ["message" => "JWT is valide!", "succ" => true];
        return ["message" => "JWT isn't valide!", "succ" => false];
    }
}
?>