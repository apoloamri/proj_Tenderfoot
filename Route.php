<?php
$router = new Routing();
$router->Map("GET /", "Home", "Index");
$router->Map("GET /item/[code]", "Home", "Item", "code");
$router->Map("GET /api/session", "ApiSession", "GetSession");
$router->Map("GET /api/shop/items", "ApiShop", "Items");
$router->Map("*", "Errors", "Error404");
?>