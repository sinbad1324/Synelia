<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include_once $GLOBALS['root'] . "/modules/crypt.php";
include_once $GLOBALS['root'] . "/modules/sql/product.php";
include_once $GLOBALS['root'] . "/modules/sql/User.php";
include_once $GLOBALS['root'] . "/modules/errorMessge.php";
include_once $GLOBALS['root'] . "/modules/filters/filterProduct.php";
include_once $GLOBALS['root'] . "/modules/JWT/JWT.php";
try {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $RequestData = json_decode(file_get_contents("php://input"), true);
        $userData = $RequestData["userData"];
        $productData = $RequestData["productData"];
        // User Find
        if ($productData != null && count($productData) <= 0)
            return RtError(message: ["message" => "Il vous manque des information!", "succ" => false]);
        if (!isset($productData["productId"])) 
            return RtError(["message"=>"Il vous manque l'ID du produit!" ,"succ"=>false]);
        $productId = filter_var($productData["productId"] , FILTER_SANITIZE_NUMBER_INT);
        $userVerification = UserDataVerification($userData);
        if ($userVerification["succ"] == false) 
            return RtError($userVerification);

        $user = $userVerification["data"];
        
        /// Product
        $Product = GetProduct($productId);
        if ($Product == null || $Product["succ"]==false  || !isset($Product["data"])) 
            return RtError(["message" => "Nous avons pas trouver ce produit!", "succ" => false]);
        
        $prodData = $Product["data"];
        
        //     $filtredData = filter_var_array($productData, $FilterProduct);
        // $data = CreateNewProduct(produitName: $filtredData["produitName"], prix: $filtredData["prix"], totalStock: $filtredData["totalStock"], marque: $filtredData["marque"], Description: $filtredData["Description"], categorieID: $filtredData["categorieID"]);
        // if ($data && $data["succ"] != false) {
        //     $productID = $data["data"]["id"];
        //     echo json_encode($data);
        // }
    }
} catch (\Throwable $th) {
    echo $th;
}
