<?php

// class JWT
// {
//     private $header;
//     private $payload;
//     public function __construct($userConnection, $userName, $nbDay)
//     {
//         $this->header = base64_encode(json_encode(["alg" => "HS256", "typ" => "JWT"]));
//         $this->payload = base64_encode(json_encode([
//             "name" => $userName,
//             "userToken" => $userConnection,
//             "FinalDate" => date("U") + (86400 * $nbDay)
//         ]));
//     }
//     public function GenerateJWT($userID): string
//     {
//         $body = utf8_encode($this->header . "." . $this->payload);
//         $signature = hash_hmac('sha256', $body, utf8_encode("W7TaOhCO3661JVRFTJeIQxMwzuuLNz7Aw5c1l7jSehQ=$userID"), true);
//         $signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
//         return $body . "." . $signature;
//     }
//     public static function IsValidateJWT($JWT, $userID): array
//     {
//         $JWTArray = preg_split("/[\.]+/", $JWT);
//         $signature = $JWTArray[2];
//         $HashedSignature = hash_hmac('sha256', $JWTArray[0] . "." . $JWTArray[1], utf8_encode("W7TaOhCO3661JVRFTJeIQxMwzuuLNz7Aw5c1l7jSehQ=$userID"), true);
//         if ($HashedSignature === $signature)
//             return ["message" => "JWT is valide!", "succ" => true];
//         return ["message" => "JWT isn't valide!", "succ" => false];
//     }
// }

class JWT
{
    private $header;
    private $payload;

    public function __construct($userConnection, $userName, $nbDay)
    {
        // Construction du header JWT standard
        $this->header = $this->base64UrlEncode(json_encode([
            "alg" => "HS256",
            "typ" => "JWT"
        ]));

        // Construction du payload avec les données utilisateur
        $this->payload = $this->base64UrlEncode(json_encode([
            "name" => $userName,
            "userToken" => $userConnection,
            "FinalDate" => time() + (86400 * $nbDay), // Expiration dans $nbDay jours
            "iat" => time() // Issued at (timestamp d'émission)
        ]));
    }

    public function GenerateJWT($userID): string
    {
        // Construction de la signature
        $signature = $this->generateSignature($userID);
        
        // Assemblage des 3 parties du JWT
        return $this->header . "." . $this->payload . "." . $signature;
    }

    public static function IsValidateJWT($JWT, $userID): array
    {
        // Découpage du JWT en ses 3 parties
        $JWTArray = explode('.', $JWT);
        
        // Vérification de la structure
        if (count($JWTArray) !== 3) {
            return ["message" => "Invalid JWT format", "succ" => false];
        }

        // Vérification de la signature
        $signature = $JWTArray[2];
        $validSignature = self::generateSignatureForValidation(
            $JWTArray[0] . "." . $JWTArray[1], 
            $userID
        );

        if (!hash_equals($validSignature, $signature)) {
            return ["message" => "Invalid signature", "succ" => false];
        }

        // Décodage et vérification du payload
        $payload = json_decode(self::base64UrlDecode($JWTArray[1]), true);
        
        // Vérification de l'expiration
        if (time() > $payload['FinalDate']) {
            return ["message" => "Token expired", "succ" => false];
        }

        // Si tout est valide
        return [
            "message" => "JWT is valid!", 
            "succ" => true, 
            "payload" => $payload
        ];
    }

    /************************************
     * Méthodes utilitaires sécurisées *
     ************************************/

    private function generateSignature($userID): string
    {
        $body = $this->header . "." . $this->payload;
        $secretKey = "W7TaOhCO3661JVRFTJeIQxMwzuuLNz7Aw5c1l7jSehQ=$userID";
        
        $hash = hash_hmac('sha256', $body, utf8_encode($secretKey), true);
        return $this->base64UrlEncode($hash);
    }

    private static function generateSignatureForValidation($data, $userID): string
    {
        $secretKey = "W7TaOhCO3661JVRFTJeIQxMwzuuLNz7Aw5c1l7jSehQ=$userID";
        $hash = hash_hmac('sha256', $data, utf8_encode($secretKey), true);
        return self::base64UrlEncode($hash);
    }

    private static function base64UrlEncode($data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private static function base64UrlDecode($data): string
    {
        $base64 = str_replace(['-', '_'], ['+', '/'], $data);
        $padding = strlen($base64) % 4;
        
        if ($padding) {
            $base64 .= str_repeat('=', 4 - $padding);
        }
        
        return base64_decode($base64);
    }
}


?>