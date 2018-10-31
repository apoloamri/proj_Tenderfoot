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

//ApiProductsController
$router->Map("GET /api/products", "ApiProducts", "GetProducts");
$router->Map("GET /api/products/tags", "ApiProducts", "GetTags");
$router->Map("POST /api/products", "ApiProducts", "PostProducts");
$router->Map("POST /api/products/image", "ApiProducts", "PostProductsImage");
$router->Map("PUT /api/products/", "ApiProducts", "PutProducts");
$router->Map("PUT /api/products/inventory", "ApiProducts", "PutInventory");
$router->Map("DELETE /api/products/", "ApiProducts", "DeleteProducts");

//HomeController
$router->Map("GET /", "Home", "Index");
$router->Map("GET /[Search]", "Home", "Index");
$router->Map("GET /detail/[Code]", "Home", "Detail");
$router->Map("GET /cart", "Home", "Cart");

//Errors
$router->Map("GET /err/404", "Errors", "Error404");
$router->Map("*", "Errors", "Error404");
?>