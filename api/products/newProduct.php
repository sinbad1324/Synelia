<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/crypt.php";
include $GLOBALS['root'] . "/modules/sql/product.php";
include $GLOBALS['root'] . "/modules/errorMessge.php";


try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $RequestData = json_decode(file_get_contents("php://input"));
        echo json_encode($RequestData);
        $userData = $RequestData["userData"];
        $productData = $RequestData["productData"];

        if (!isset($userData) || count($userData) <= 0)
            return RtError(["message" => "Il vous manque des information!", "succ" => false]);
        if (!isset($productData) || count($productData) <= 0)
            return RtError(["message" => "Il vous manque des information!", "succ" => false]);
        $user = FindOneUserWithToken($userData["connectionToken"]);
        if (!$user)
            return RtError(["message" => "Vous êtes pas trouver!", "succ" => false]);
        if ($user["status"] != "admin")
            return RtError(["message" => "Vous avez pas les accées!", "succ" => false]);
        $filtredData = filter_input_array($productData);
        echo json_encode($filtredData);
        // $data = CreateNewProduct(produitName: "2o", prix: "20.00", totalStock: 5, marque: "niikker", Description: "wqsqwdqwdqwd", categorieID: 4);
        // if ($data && $data["succ"] != false) {
        //     $productID = $data["data"]["id"];
        //     echo json_encode($data);
        // }
    }
} catch (\Throwable $th) {
    echo $th;
}

?>