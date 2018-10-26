<?php
$router = new Routing();

//AdminController
$router->Map("GET /admin", "Admin", "Index");
$router->Map("GET /admin/login", "Admin", "Login");
$router->Map("GET /admin/products", "Admin", "Products");
$router->Map("GET /admin/products/add", "Admin", "ProductsAdd");
$router->Map("GET /admin/products/edit/[Id]", "Admin", "ProductsEdit");
$router->Map("GET /admin/api/checkauth", "Admin", "ApiGetCheckAuth");
$router->Map("POST /admin/api/login", "Admin", "ApiPostLogin");
$router->Map("DELETE /admin/api/logout", "Admin", "ApiPostLogout");

//ProductsController
$router->Map("GET /api/products", "Products", "GetProducts");
$router->Map("POST /api/products", "Products", "PostProducts");
$router->Map("POST /api/products/image", "Products", "PostProductsImage");
$router->Map("PUT /api/products/", "Products", "PutProducts");
$router->Map("PUT /api/products/inventory", "Products", "PutInventory");
$router->Map("DELETE /api/products/", "Products", "DeleteProducts");

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

//Errors
$router->Map("GET /err/404", "Errors", "Error404");
$router->Map("*", "Errors", "Error404");
?>