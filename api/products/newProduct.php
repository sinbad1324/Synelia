<?php
$GLOBALS["root"] = $_SERVER['DOCUMENT_ROOT'] . "/Synelia";
include $GLOBALS['root'] . "/modules/crypt.php";
include $GLOBALS['root'] . "/modules/sql/product.php";


try {

    $data = CreateNewProduct(produitName: "2o", prix: "20.00", totalStock: 5, marque: "niikker", Description: "wqsqwdqwdqwd", categorieID: 4);
    if ($data && $data["succ"] != false) {
        $productID = $data["data"]["id"];

        echo json_encode($data);
    }
} catch (\Throwable $th) {
    echo $th;
}
?>