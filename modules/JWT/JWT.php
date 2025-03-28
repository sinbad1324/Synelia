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
        $body = utf8_encode($this->header . "." . $this->payload);
        $signature = hash_hmac('sha256', $body, utf8_encode("W7TaOhCO3661JVRFTJeIQxMwzuuLNz7Aw5c1l7jSehQ=$userID"), true);
        $signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        return $body . "." . $signature;
    }
    public static function IsValidateJWT($JWT, $userID): array
    {
        $JWTArray = preg_split("/[\.]+/", $JWT);
        $signature = $JWTArray[2];
        $HashedSignature = hash_hmac('sha256', $JWTArray[0] . "." . $JWTArray[1], utf8_encode("W7TaOhCO3661JVRFTJeIQxMwzuuLNz7Aw5c1l7jSehQ=$userID"), true);
        if ($HashedSignature === $signature)
            return ["message" => "JWT is valide!", "succ" => true];
        return ["message" => "JWT isn't valide!", "succ" => false];
    }
}

$a =new JWT("ewfwefwe","ewfewf",2);
echo json_encode(JWT::IsValidateJWT('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiZXdmZXdmIiwidXNlclRva2VuIjoiZXdmd2Vmd2UiLCJGaW5hbERhdGUiOjE3NDMzNDUxNTV9.noWJwM48rqnadP8IOT5_vY6hxXOOjH_svLA_QLihysk',2));
echo utf8_encode($a->GenerateJWT(2));

?>