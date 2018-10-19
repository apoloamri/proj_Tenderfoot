<?php
$router = new Routing();

//HomeController
$router->Map("GET /", "Home", "Index");
$router->Map("GET /item/[code]", "Home", "Item", "code");
$router->Map("GET /cart", "Home", "Cart");

//ApiSessionController
$router->Map("GET /api/session", "ApiSession", "GetSession");
$router->Map("POST /api/session", "ApiSession", "PostSession");

//ApiShopController
$router->Map("GET /api/shop/items", "ApiShop", "Items");
$router->Map("GET /api/shop/cart", "ApiShop", "GetCart");
$router->Map("POST /api/shop/cart", "ApiShop", "AddCart");
$router->Map("DELETE /api/shop/cart", "ApiShop", "DeleteCart");

//Unassigned
$router->Map("*", "Errors", "Error404");
?>