<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include_once $GLOBALS['root'] . "/modules/crypt.php";
include_once $GLOBALS['root'] . "/modules/sql/product.php";
include_once $GLOBALS['root'] . "/modules/sql/User.php";
include_once $GLOBALS['root'] . "/modules/errorMessge.php";
include_once $GLOBALS['root'] . "/modules/filters/filterProduct.php";
include_once $GLOBALS['root'] . "/modules/JWT/JWT.php";
try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $RequestData = json_decode(file_get_contents("php://input"), true);
        $userData = $RequestData["userData"];
        $productData = $RequestData["productData"];
        if ($userData !=null && count($userData) <= 0)
            return RtError(["message" => "Il vous manque des information! (USER ID)", "succ" => false]);
        if ($userData !=null && count($productData) <= 0)
            return RtError(message: ["message" => "Il vous manque des information!", "succ" => false]);
        $user = FindOneUserWithToken($userData["connectionToken"]);
        if (!$user)
            return RtError(["message" => "Vous êtes pas trouver!", "succ" => false]);
        if ( !isset($user["data"]["status"]) || $user["data"]["status"] !== "admin")
            return RtError(["message" => "Vous avez pas les accées!", "succ" => false]);
        
        // JTW VERIFIECATIION USER VERIFICATION
       
        $filtredData = filter_var_array($productData,$FilterProduct);
        $data = CreateNewProduct(produitName:$filtredData["produitName"] , prix: $filtredData["prix"], totalStock: $filtredData["totalStock"], marque: $filtredData["marque"], Description: $filtredData["Description"], categorieID: $filtredData["categorieID"]);
        if ($data && $data["succ"] != false) {
            $productID = $data["data"]["id"];
            // echo json_encode($data);
        }
    }
} catch (\Throwable $th) {
    echo $th;
}
?>